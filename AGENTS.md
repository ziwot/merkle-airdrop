# AGENTS.md

Merkle-tree airdrop for Tezos. Three parts, wired by the root `Makefile` (`make help` lists every target):

- `contract/` — the smart contract, **CameLIGO** (`.mligo`, *not* jsligo). Entrypoint `src/airdrop.mligo`; suites are registered in `tests/all.mligo`; tests use `ligo-breathalyzer`. LIGO deps are pinned in `contract/ligo.json` (`@ligo/fa` 1.4.2, `ligo-breathalyzer` 1.7.0) and fetched into `contract/_ligo` by `ligo install`.
- `app/` — server-rendered **CakePHP 5.4** dApp (no frontend build), MySQL, SIWT login via `ziwot/cake-tezos`.
- `infra/` — TypeScript scripts run directly with `tsx` (never compiled) that generate test data against the sandbox.

Docs: `README.md` (dev flow), `infra/README.md` (per-script reference — read it before touching `infra/scripts/`), `docs/README.md` (why the leaf hash looks like that).

## Dev flow — the order matters, and the README gets one step wrong

Each step consumes the previous step's output:

1. `make install` — `ligo install` + `npm ci` (infra) + `composer install` (app) + `bin/cake plugin assets symlink` (creates the `app/webroot/*` plugin symlinks). Required before `make test`/`make testdata` too: `makeToken` compiles `contract/tests/token.mligo`, which imports `@ligo/fa`.
2. `ENV=dev make config` — copies `app/config/app_local.<ENV>.php` → `app_local.php` and injects a random salt. **`ENV` is mandatory** (`make config` alone copies `app_local..php` and fails).
3. `make testaccounts` — **before `make up`** on a fresh clone: `make up` bind-mounts `infra/testdata/accounts.hjson`, which is gitignored, so the file must exist or docker mounts a directory. Also imports the seeded keys (`accounts.hjson.dist` → `alice`, `bob`) into the *host* `octez-client` key store, then appends 25 freshly generated accounts (feeds both the sandbox and `makeDrops`).
4. `make up` — tezbox sandbox (tezos-v25.2) on `http://localhost:8732` + MySQL 8.0 on port **3307**, volume `mysql-data`. `make down` to stop.
5. `make compile` → `contract/build/airdrop.tz`.
6. `make testdata` (blocks on `make bootstrapped`) → `infra/testdata/{drops,token,merkleRoot}.json` + `token_storage.tz`. Re-originates the FA2 token, so the address changes on every run.
7. `make compile-storage` → `infra/testdata/airdrop_storage.tz`.
8. `make deploy` — `octez-client originate contract airdrop_dev ... from alice --burn-cap 2 --force`.
9. `make data-reset` — drop/create schema `airdrop`, `migrations migrate` + `seeds run --force`.

Re-running `make testaccounts` invalidates the running sandbox (aliases/balances) and every drop → `make down && make up`, then re-run testdata → compile-storage → deploy → data-reset.

## QA

- Contract: `make test` (LIGO binary on `PATH`).
- App, from `app/`: `composer run cs-check` (phpcs, PhpCollective + Slevomat rules), `composer run cs-fix`, `composer run stan` (phpstan level 8, `src/` only). Wrappers: `make cs-check` / `make cs-fix` / `make static-check`.
- **There are no app tests.** `app/tests/TestCase/` does not exist, so `phpunit` (and therefore `composer run check`) fails with `Test directory ... not found` — use `cs-check`/`stan` instead, not `composer check`.
- Infra: `npm --prefix infra run ci` (biome, read-only) or `run check`. Biome only covers `scripts/**/*.ts`; there is no eslint/prettier.

## Critical invariant: the merkle leaf hash

`sha256( pack((address, nat), addr, amount) )`, computed in two places that must stay byte-for-byte identical:

- TS: `getLeaf()` in `infra/scripts/makeProof.ts` (`@taquito/michel-codec` `packDataBytes` + `crypto-js/sha256`).
- CameLIGO: `Crypto.sha256 (Bytes.pack (addr, amnt))` in the `claim` entrypoint, `contract/src/airdrop.mligo:142`.

The proof verifier is **unsorted** (`Bytes.concat h acc`, no left/right ordering — `MerkleProof.verify`, airdrop.mligo:68), matching merkletreejs defaults. Changing leaf encoding, hash, or sort order on one side only invalidates every claim.

## Gotchas

- **Nothing funds the airdrop contract.** `claim` transfers FA2 tokens with `from_ = self`, and `makeToken` only originates the token with the genesis 300 units on `alice`; no step transfers them to the airdrop address. Fund it manually (as `contract/tests/test_airdrop.mligo` does) before any real claim.
- `Makefile:35-37` read `token.json`, `merkleRoot.json` and `airdrop_storage.tz` through `$(shell …)` at **parse** time. Stale testdata is baked in silently, and if the files are missing `make deploy` still runs with `--init ''`. Always re-run `make compile-storage` after regenerating testdata, and never chain `make testdata compile-storage deploy` in one invocation.
- `infra/testdata/` is generated, not hand-edited: only `accounts.hjson.dist` and `token.tz` are committed (`infra/.gitignore`).
- **cwd rules:** `infra/scripts/*` must run with cwd = `infra/` (`TESTDATA_PATH = "./testdata"`, and `makeToken` shells out with `cd ../contract`) → use `npm --prefix infra run …` or the make targets. `contract/src/generate_metadata_bytes.sh` is the opposite: it hardcodes `contract/src/metadata.json`, so it only works from the repo root.
- `infra/scripts/makeAccounts.ts` appends exactly `NB_ACCOUNTS = 24` accounts to the seeds of `accounts.hjson.dist` (alice, bob, eve), so `accounts.hjson` holds 27. Names come from a small dictionary set (52 colors) and collide in ~3% of runs, so a name is retried rather than overwriting an existing account. Re-running the script does not touch `accounts.hjson.dist`, only the generated file and the host key store.
- `infra/scripts/bootstrapped.ts` polls `octez-client bootstrapped` every 5s, logging the elapsed time and the first error. It gives up after 10 minutes and exits 1 (so `make testdata` fails instead of hanging); override with `BOOTSTRAPPED_TIMEOUT` in ms, `0` to wait forever.
- `ligo` and `octez-client` are **host** CLIs, not dockerized, and the host `octez-client` must already point at `http://localhost:8732`. `makeToken`/`AirdropSeed` depend on aliases `token` and `airdrop_dev` existing. Only `make compile` and `make test` run without a sandbox.
- The app is configured in `app/config/app_custom.php` (`CakeTezos` networks: `local` = `http://localhost:8732`); `app/config/bootstrap.php` has dotenv loading commented out, so env vars must come from the real environment.
- The app seeds read `ROOT . '/../infra/testdata/…'`, i.e. they escape the `app/` deploy root: `make data-reset` only works from a repo checkout (the FTP deploy ships `app/` alone). `AirdropSeed` also shells out to `octez-client list known contracts` and needs the `airdrop_dev` alias → **`make deploy` must run before `make data-reset`**, otherwise the airdrop row is seeded without an address.
- The app does **not** implement the claim flow yet: no merkle-proof generation, no claim submission. `App\Tezos\Airdrop\ClaimStatus` is unused, and the `get_claim_status` offchain view (`make compile-view`) has no consumer. Current app surface = SIWT login, homepage list (`AirdropsTable::recentAirdrops`), Admin CRUD, htmx partials (`isHTMXRequest()` + `layout/ajax` + `templates/**/list.php`).
