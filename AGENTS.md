# AGENTS.md

Merkle-tree airdrop for Tezos. Monorepo, three parts wired together by the root `Makefile`:

- `contract/` — the Tezos smart contract, written in **CameLIGO** (`.mligo`, not jsligo). Entrypoint: `src/airdrop.mligo`. Tests in `tests/` use the `ligo-breathalyzer` framework.
- `app/` — server-rendered **CakePHP 5.3** PHP dApp (no frontend build). MySQL-backed. QA via phpcs / phpstan (level 8) / phpunit.
- `infra/` — TypeScript scripts (`tsx`, not compiled) that generate test data and run against the sandbox.

`make help` lists every target. The README `make ...` sequence is the source of truth.

## Dev flow — order matters

Later steps consume outputs of earlier ones (testdata feeds storage, storage feeds deploy), so run in order:

1. `make install` — `ligo install` + `npm ci` (infra) + `composer install` (app) + cake plugin asset symlink.
2. `ENV=dev make config` — copies `app/config/app_local.<ENV>.php` → `app_local.php` and injects a random salt. **`ENV` is required** (`make config` alone breaks).
3. `make up` — starts tezbox sandbox (tezos-v25.2) on port 8732 and MySQL 8.0 on **port 3307** (not 3306), volume `mysql-data`. Stop with `make down`.
4. `make testaccounts` — imports the seeded keys (`infra/testdata/accounts.hjson.dist`) into host `octez-client`, then appends ~random accounts and rewrites `infra/testdata/accounts.hjson` (feeds the sandbox at `make up` and drops generation). Not fully deterministic across runs.
5. `make compile` — LIGO → `contract/build/airdrop.tz`.
6. `make testdata` — refuses to run until the sandbox is bootstrapped (`make bootstrapped`); writes `infra/testdata/{drops,token,merkleRoot}.json`.
7. `make compile-storage` — needs testdata; emits `infra/testdata/airdrop_storage.tz`. Runs `generate_metadata_bytes.sh`, which shells out to `ligo compile expression` for TZIP-16 metadata bytes.
8. `make deploy` — `octez-client originate contract airdrop_dev ... from alice --burn-cap 2`. Requires `octez-client` CLI on the host.
9. `make data-reset` — drops + recreates the MySQL schema, then `bin/cake migrations migrate` + `seeds run`.

## Critical invariant: merkle leaf hash

The leaf hash is `sha256( pack((address, nat), addr, amount) )`, computed in two places that must stay byte-for-byte identical:

- TS: `getLeaf()` in `infra/scripts/makeProof.ts` (uses `@taquito/michel-codec` `packDataBytes` + `crypto-js/sha256`).
- CameLIGO: `Crypto.sha256 (Bytes.pack (addr, amnt))`, computed in the `claim` entrypoint (`MerkleProof.get_leaf` is `Crypto.sha256 message`) in `contract/src/airdrop.mligo`.

The contract proof verifier is **unsorted** (`Bytes.concat h acc`, no left/right ordering), matching merkletreejs defaults. Changing leaf encoding, hash algo, or ordering in one place breaks every claim.

## Testing & QA

- Contract: `make test` → `ligo run test --no-warn contract/tests/all.mligo` (LIGO binary required).
- App (from `app/`): `composer run-script check` (= phpunit + phpcs), `cs-fix`, `stan`. Makefile wrappers: `make cs-check`, `make cs-fix`, `make static-check`.
- Infra: `npm --prefix infra run ci` (biome).
- Run the app locally with `bin/cake server` from `app/`.

## Gotchas

- The contract's `claim` entrypoint transfers FA2 tokens with `from_ = self`, so the airdrop contract must own (or be operator of) the token balance. The token is the FA2 multi-asset example from the LIGO contract-catalogue (see `infra/README.md`).
- `infra/testdata/` is regenerated, not hand-edited: only `accounts.hjson.dist` and `token.tz` are committed. `drops.json`, `token.json`, `merkleRoot.json`, `airdrop_storage.tz`, `token_storage.tz` are outputs of `make testdata` + `make compile-storage`. Re-running `make testdata` re-randomizes accounts/amounts → new merkle root → `airdrop_storage.*` and the deployed contract must follow.
- `octez-client` and `ligo` are host CLIs (not dockerized), and the whole dev flow depends on them — not just deploy: `make testaccounts` imports keys, and `make testdata`/`make bootstrapped` shell out to `octez-client` + `ligo` too (`makeToken` compiles LIGO and originates the FA2 token against the sandbox). Only `make compile` / `make test` run without a sandbox or network.
- App networks are configured in `app/config/app_custom.php` (`local` = `http://localhost:8732`);
- CI: only `.github/workflows/deploy-ftp.yml`, manual `workflow_dispatch`, PHP 8.5 `--no-dev`, deploys just `app/` over FTP — never runs tests.
- `infra/scripts/makeAccounts.ts` loops `i <= nb`, so it appends **25** accounts, not the 24 its `NB_ACCOUNTS` name suggests, and logs `NB_ACCOUNTS + 2` (26) while the file actually holds 27 (alice + bob + 25). Cosmetic, but don't trust the log line.
- `infra/testdata/airdrop_storage.mligo` (written by `makeProof.ts`) has no consumer — nothing reads it. `make compile-storage` gets the token and root from `token.json` + `merkleRoot.json` directly.
- `Makefile:35-37` capture `token.json`, `merkleRoot.json` and `airdrop_storage.tz` via `$(shell cat …)`, evaluated when the Makefile is *parsed*. Stale testdata is silently baked into `make deploy`; always re-run `make compile-storage` after regenerating testdata.
- `infra/` scripts must run with cwd = `infra/`: `TESTDATA_PATH` is the relative `./testdata` and `makeToken.ts` shells out with `cd ../contract`. `npm --prefix infra run …` gets this right; running `tsx infra/scripts/…` from the root does not.
- `infra/scripts/bootstrapped.ts` polls forever with no timeout — Ctrl-C if the sandbox never comes up, rather than waiting on a hung `make testdata`.
- `infra/.eslintrc.js` and `infra/.prettierrc.js` are dead config: biome replaced them and there are no eslint/prettier dependencies left. Don't try to run either linter.
- `infra/README.md` is the per-script reference (what each script reads, writes, and shells out to); the invariant summary above is deliberately duplicated there for human readers.
