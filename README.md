# Merkle Airdrop

## What is this?

Complete airdrop solution for [Tezos](https://tezos.com/) tokens.

The chosen solution uses merkle trees. 
The advantages are of 2 kinds:

- It is cheap because fees will be paid by the claimers.
- It brings engagement, requiring some action from the claimers.

## How it works?

An airdrop project consists in off-chain and on-chain data:
- [on-chain](./contracts), there is a smart-contract that holds projects information, e.g: the token contract address, 
the hex of a merkle root of beneficiaries (address, amount), and the registry of already claimed beneficiaries entries.
- [off-chain](./app), the merkle tree must be stored to be able to generate the merkle proofs required to claim.
An off-chain app also is helping on the merkle tree generation and validation before deploying the airdrop contract.

## Resources

- [Evolution of Airdrop: from Common Spam to the Merkle Tree](https://hackernoon.com/evolution-of-airdrop-from-common-spam-to-the-merkle-tree-30caa2344170)
- [Merkle Airdrop: One of the best Airdrop Solution for Token Issues](https://medium.com/mochilab/merkle-airdrop-one-of-the-best-airdrop-solution-for-token-issues-e2279df1c5c1)
- [Merkle tree](https://en.wikipedia.org/wiki/Merkle_tree)
- [Merkle proofs Explained](https://medium.com/crypto-0-nite/merkle-proofs-explained-6dd429623dc5)
- [The Ultimate Merkle Tree Guide in Solidity](https://soliditydeveloper.com/merkle-tree)
- <https://github.com/steve-ng/merkle-airdrop>
- <https://tezostaquito.io/docs/signing#signing-michelson-data>
