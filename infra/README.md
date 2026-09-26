# Infra

TypeScript scripts that generate the sandbox test data for the airdrop flow:
the FA2 token to airdrop, a list of drops, the merkle root over them, and the
storage the airdrop contract is originated with.

Nothing here is built ahead of time — the scripts are executed directly with
`tsx`. They shell out to the **host** `octez-client` and `ligo` binaries, so
both must be on `PATH`. The root [`Makefile`](../Makefile) wires them together;
its `make` targets are the source of truth for ordering.

## Usage

Run these through the root `Makefile`, in this order:

| Target | Script(s) | Writes |
| --- | --- | --- |
| `make up` | — | starts the tezbox sandbox (RPC `http://localhost:8732`) and MySQL on `3307` |
| `make testaccounts` | [`make:accounts`](./scripts/makeAccounts.ts) | `testdata/accounts.hjson` |
| `make bootstrapped` | [`bootstrapped`](./scripts/bootstrapped.ts) | — (blocks until the sandbox is up) |
| `make testdata` | [`make:drops`](./scripts/makeDrops.ts), [`make:token`](./scripts/makeToken.ts), [`make:proof`](./scripts/makeProof.ts) | `testdata/drops.json`, `testdata/token.json`, `testdata/token_storage.tz`, `testdata/merkleRoot.json`, `testdata/airdrop_storage.mligo` |
| `make compile-storage` | — (root target, runs `ligo compile storage`) | `testdata/airdrop_storage.tz` |

They can also be run directly:

```sh
npm run bootstrapped
npm run make:accounts
npm run make:drops
npm run make:token
npm run make:proof
```

Direct invocation only works **from this directory**. `TESTDATA_PATH` in
[`scripts/config.ts`](./scripts/config.ts) is the relative path `./testdata`, and
[`makeToken.ts`](./scripts/makeToken.ts) runs `ligo` with `cwd: "../contract"`.
From the repository root, use `npm --prefix infra run make:token` (which is what
the `Makefile` does) or the `make` targets above.

## Scripts

### bootstrapped.ts

`npm run bootstrapped` (a `make testdata` prerequisite).

Polls `octez-client bootstrapped` every 5s and exits 0 as soon as the command
succeeds with `Node is bootstrapped.` on stdout. It gives up after 10 minutes
and exits 1, so `make testdata` fails instead of hanging; override with
`BOOTSTRAPPED_TIMEOUT` in ms, `0` to wait forever. The host `octez-client` must
already be pointed at the sandbox RPC.

### makeAccounts.ts

`npm run make:accounts` (`make testaccounts`).

- Reads the committed [`testdata/accounts.hjson.dist`](./testdata/accounts.hjson.dist)
  and imports each secret key into the **host** `octez-client` key store with
  `octez-client import secret key <alias> <sk> --force`. This is what makes
  `from alice` resolvable by every later step.
- Appends `NB_ACCOUNTS` freshly generated keypairs (`sotez` for the keys, one
  word from `unique-names-generator` for the alias, 1–99 tez for the balance).
  Aliases are retried until unique, so a collision never overwrites a seed
  account.
- Writes [`testdata/accounts.hjson`](./testdata/accounts.hjson) in the HJSON
  shape tezbox expects. `make up` bind-mounts it to
  `/tezbox/overrides/accounts.hjson`, so **the file has to exist before the
  sandbox is started**.

The resulting addresses are the beneficiary set: `makeDrops.ts` turns them into
drops, and the app signs the claims with them. Re-running regenerates a
completely different set, so the sandbox has to be recreated (`make up` again)
and the testdata regenerated.

### makeDrops.ts

`npm run make:drops`.

Reads `accounts.hjson` and emits one drop per account with a random amount in
`[1, 200)` (`MIN_AMOUNT`/`MAX_AMOUNT` plus `between()` from
[`scripts/utils.ts`](./scripts/utils.ts)). Writes
[`testdata/drops.json`](./testdata/drops.json) as a flat array of
`{ pkh, amount }`, ordered like `accounts.hjson`.

The app seeds read it: `RecipientSeed` maps `pkh` to `recipients.address`, and
`AirdropRecipientSeed` maps `amount` to `airdrops_recipients.amount`.

### makeToken.ts

`npm run make:token`.

1. Encodes the TZIP-16 metadata object as a micheline bytes literal: the
   UTF-8 bytes of its JSON form, `0x…`.
2. Compiles the FA2 storage with `get_token_initial_storage()` from
   [`contract/tests/token.mligo`](../contract/tests/token.mligo), with
   `ligo compile expression cameligo` run from the `contract` directory: the
   genesis allocation is alice holding 300 of token id `0`. Written to
   [`testdata/token_storage.tz`](./testdata/token_storage.tz).
3. Originates [`testdata/token.tz`](./testdata/token.tz) from alice under the
   alias `token` (`--burn-cap 0.84025`), then resolves the alias and writes the
   KT1 address to [`testdata/token.json`](./testdata/token.json).

The token is the FA2 `MultiAsset` implementation from `@ligo/fa` (pinned
`1.4.2` in `contract/ligo.json`); `token.tz` is its compiled code, and the
storage shape is built by `contract/tests/token.mligo`. It superseded the
earlier, hand-compiled
[contract-catalogue](https://github.com/tez-capital/contract-catalogue/blob/main/lib/fa2/asset/multi_asset.mligo)
version.

Re-running re-originates the contract and overwrites the `token` alias, so the
new address has to be picked up downstream: `make compile-storage`,
`make deploy`, then `make data-reset` to re-seed the app.

The `ligo` command can be run by hand, from the
[`contract`](../contract) directory, with the metadata bytes inlined:


```sh
ligo compile expression cameligo --init-file ./tests/token.mligo \
  'get_token_initial_storage(<metadata-bytes>, ("tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb": address), 0n, 300n)'
```

The first argument is the TZIP-16 `contents` bytes, the hex literal of the
UTF-8 bytes of `JSON.stringify(metadata)`.

### makeProof.ts

`npm run make:proof` (last step of `make testdata`, so `token.json` already
exists).

Builds the merkle tree with `merkletreejs` over the leaves derived from
`drops.json`, then writes:

- [`testdata/merkleRoot.json`](./testdata/merkleRoot.json) — the root, as a
  quoted JSON string with its `0x` prefix. The `Makefile` strips the quotes
  with `jq -r`; `AirdropSeed` strips the `0x` with `substr($merkleRoot, 2)`.

The tree lives in [`scripts/merkle.ts`](./scripts/merkle.ts), so that
`getLeaf()` and `getProof()` can be tested without the script writing
`merkleRoot.json` on import.

**The leaf hash is the contract's trust boundary.** It is
`sha256( pack((address, nat), addr, amount) )`, computed by `getLeaf()` with
`@taquito/michel-codec`'s `packDataBytes` and `node:crypto`'s
`createHash("sha256")`. It has to stay byte-for-byte identical to
`Crypto.sha256 (Bytes.pack (addr, amnt))` in the `claim` entrypoint of
[`contract/src/airdrop.mligo`](../contract/src/airdrop.mligo). Changing the
leaf encoding or the hash on one side only invalidates every claim.

`packDataBytes()` returns its `bytes` as an hex **string** (`BytesLiteral`),
not as a `Buffer`, so `getLeaf()` decodes it before hashing: hashing the string
would hash its 66 characters instead of the 32 packed bytes, and every leaf —
hence the root — would silently differ from the contract. The same trap applies
to `crypto-js`, which turns a `Buffer` into a string instead of hashing its
bytes, and is why the tree is hashed with `node:crypto`.

**A proof carries the side of each sibling.** The tree is a standard one
(`merkletreejs` hashes `left ++ right`), so the contract cannot tell from a
sibling hash alone whether it comes before or after the accumulated hash — it
concatenates the sibling first either way and rejects three leaves out of four.
`claim` therefore takes a `merkle_proof : (bytes * bool) list`, each step being
the sibling and `true` when the accumulated hash is the left operand.
`getProof(drops, index)` returns it in that shape, deriving the flags by
keeping the combination that folds back to the root, so the flags cannot be
guessed wrong.

[`scripts/merkle.test.ts`](./scripts/merkle.test.ts) pins `getLeaf()` against
leaves generated by LIGO, and checks that every drop of the contract fixture
has a proof the contract accepts, folded by an implementation of
`MerkleProof.verify` independent from `merkle.ts`. It runs as part of
`npm --prefix infra run ci`. To regenerate an expected leaf:

```sh
ligo compile expression cameligo \
  'Crypto.sha256 (Bytes.pack (("tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn": address), 42n))'
```

### config.ts and utils.ts

- [`scripts/config.ts`](./scripts/config.ts) — `TESTDATA_PATH = "./testdata"`,
  relative to the current working directory (see the note above).
- [`scripts/utils.ts`](./scripts/utils.ts) — `between(min, max)`, a random
  integer in `[min, max)`.

## testdata

Everything in this directory is generated except the two files marked as
committed; see [`.gitignore`](./.gitignore).

| File | In git | Written by | Read by |
| --- | --- | --- | --- |
| `accounts.hjson.dist` | yes | — (hand-seeded) | `makeAccounts.ts` |
| `accounts.hjson` | no | `make:accounts` | `make up` (container mount), `makeDrops.ts` |
| `token.tz` | yes | — (hand-committed) | `makeToken.ts` |
| `drops.json` | no | `make:drops` | `makeProof.ts`, app `RecipientSeed`, app `AirdropRecipientSeed` |
| `token.json` | no | `make:token` | `makeProof.ts`, `Makefile:35`, app `TokenSeed` |
| `token_storage.tz` | no | `make:token` | `makeToken.ts` (originate) |
| `merkleRoot.json` | no | `make:proof` | `Makefile:36`, app `AirdropSeed` |
| `airdrop_storage.mligo` | no | `make:proof` | — (readable snapshot) |
| `airdrop_storage.tz` | no | `make compile-storage` | `Makefile:37`, `make deploy` |

The app seeds load these through `ROOT . '/../infra/testdata/...'`, which
escapes the `app/` deploy root — so they only resolve from a repository
checkout. The FTP deployment ships `app/` alone.

## QA

```sh
npm run ci     # biome ci (CI mode)
npm run check  # biome check
```

Or from the repository root: `npm --prefix infra run ci`.
