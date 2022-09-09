# Merkle Airdrop

This project is using [TEA](https://github.com/alis-is/tea) as dev stack.

## How it works?

The merkle tree stores hashes of packed pairs of (address, amount),
which is of type (address * nat).

We can get sha256 hashs of `(address, amount)` tuples as follows:

```
> tc hash data 'Pair "tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn" 42' of type 'pair address nat'
> ligo compile expression cameligo 'Crypto.sha256 (Bytes.pack (("tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn": address), 42n))'
> tc convert data 'Pair "tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn" 42' from michelson to json
 { "prim": "Pair",
   "args":
     [ { "string": "tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn" }, { "int": "42" } ] }
```

We can generate the hash in javascript like this:

```
import {
  Parser,
  packDataBytes,
  MichelsonData,
  MichelsonType,
} from "@taquito/michel-codec";
import SHA256 from "crypto-js/sha256";

const data = '(Pair "tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn" 42)';
const type = "(pair address nat)";

const p = new Parser();
const dataJSON = p.parseMichelineExpression(data);
const typeJSON = p.parseMichelineExpression(type);

const packed = packDataBytes(
  dataJSON as MichelsonData,
  typeJSON as MichelsonType
);

// or
// const data = (addr: string, amt: number): MichelsonData => ({
//   prim: "Pair",
//   args: [{ string: addr }, { int: `${amt}` }],
// });

// const type = {
//   prim: "pair",
//   args: [{ prim: "address" }, { prim: "nat" }],
// } as MichelsonType;

// const packed = packDataBytes(
//   data("tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn", 42),
//   type
// );

console.log(SHA256(packed.bytes));
```

## Resources

- [Evolution of Airdrop: from Common Spam to the Merkle Tree](https://hackernoon.com/evolution-of-airdrop-from-common-spam-to-the-merkle-tree-30caa2344170)
- [Merkle Airdrop: One of the best Airdrop Solution for Token Issues](https://medium.com/mochilab/merkle-airdrop-one-of-the-best-airdrop-solution-for-token-issues-e2279df1c5c1)
- [Merkle tree](https://en.wikipedia.org/wiki/Merkle_tree)
- [Merkle proofs Explained](https://medium.com/crypto-0-nite/merkle-proofs-explained-6dd429623dc5)
- [The Ultimate Merkle Tree Guide in Solidity](https://soliditydeveloper.com/merkle-tree)
- <https://github.com/steve-ng/merkle-airdrop>
- <https://tezostaquito.io/docs/signing#signing-michelson-data>
