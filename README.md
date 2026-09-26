# Merkle Airdrop

## What is this?

Airdrop solution for [Tezos](https://tezos.com/) tokens, using merkle trees.
The advantages are of 2 kinds:

- It is cheap because fees will be paid by the claimers.
- It brings engagement, requiring some action from the claimers.

Status is experimental / side-project: the contract and the test-data pipeline
are functional, but the dApp does not yet generate proofs or submit claims.

If you are looking for real airdrops, take a look at [organicgrowth.wtf](https://www.organicgrowth.wtf)
(on etherlink)

## How it works?

An airdrop project consists in off-chain and on-chain data:
- [on-chain](./contract), there is a smart-contract that holds projects information, e.g: the token contract address,
the hex of a merkle root of beneficiaries (address, amount), and the registry of already claimed beneficiaries entries.
- [off-chain](./app), the merkle tree must be stored to be able to generate the merkle proofs required to claim.
An off-chain app also is helping on the merkle tree generation and validation before deploying the airdrop contract.

## Prerequisites

- ligo, install from [here](https://ligolang.org/docs/intro/installation)
- octez-client, see [howtoget](https://octez.tezos.com/docs/introduction/howtoget.html)
- PHP8 is used for the dApp (no front build at this time)
- nodejs for the test data generation scripts.
- docker for local infra (tezos sandbox)

## Dev

Run `make` to list every target. The order matters, each step consumes the output of the previous one:

1. Install dependencies: `make install`
2. Create config: `ENV=dev make config`
3. Create test accounts: `make testaccounts`
4. Launch infra: `make up` (Stop it: `make down`)
5. Compile contracts: `make compile`
6. Generate test data: `make testdata`
7. Compile storage: `make compile-storage`
8. Deploy contracts: `make deploy`
9. Reset App data: `make data-reset`

`make testaccounts` must run before `make up`: the sandbox bind-mounts the generated `infra/testdata/accounts.hjson`, which is not committed.

## QA

- Contract: `make test`
- App: `make cs-check` / `make cs-fix` (phpcs), `make static-check` (phpstan)
- Infra: `npm --prefix infra run ci` (biome). It only reports; there is no autofix script.

## Testdata

Use [tezbox](https://github.com/tez-capital/tezbox#accounts)'s bob or alice for dev purpose, you can also add
choosen deterministic keys in the [`makeAccounts` script](./infra/scripts/makeAccounts.ts).

Otherwise, you can add your address in same script.

## Resources

- [Evolution of Airdrop: from Common Spam to the Merkle Tree](https://hackernoon.com/evolution-of-airdrop-from-common-spam-to-the-merkle-tree-30caa2344170)
- [Merkle Airdrop: One of the best Airdrop Solution for Token Issues](https://medium.com/mochilab/merkle-airdrop-one-of-the-best-airdrop-solution-for-token-issues-e2279df1c5c1)
- [Merkle tree](https://en.wikipedia.org/wiki/Merkle_tree)
- [Merkle proofs Explained](https://medium.com/crypto-0-nite/merkle-proofs-explained-6dd429623dc5)
- [The Ultimate Merkle Tree Guide in Solidity](https://soliditydeveloper.com/merkle-tree)
- <https://github.com/ziwot/merkle-airdrop>
- <https://tezostaquito.io/docs/signing#signing-michelson-data>
