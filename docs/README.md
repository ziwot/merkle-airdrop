# Merkle Airdrop

## Usage

Just run `make` to see a list of available commands.

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

We can generate the hash in javascript like this (this is what
[`infra/scripts/merkle.ts`](../infra/scripts/merkle.ts) does):

```ts
import { createHash } from "node:crypto";
import { type MichelsonType, packDataBytes } from "@taquito/michel-codec";

const pkh = "tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn";
const amount = 42;

// "(pair address nat)", written out so it is not parsed on every call
const type = {
  prim: "pair",
  args: [{ prim: "address" }, { prim: "nat" }],
} as MichelsonType;

const packed = packDataBytes(
  {
    prim: "Pair",
    args: [{ string: pkh }, { int: `${amount}` }],
  },
  type
);

console.log(
  createHash("sha256")
    .update(Buffer.from(packed.bytes, "hex"))
    .digest("hex")
);
// f526684b6478ea1fbf21107785d4036d5d650ab79bbd7ec6cebdcccdf5ad4c7d
```

`packed.bytes` is an hex **string**, not a `Buffer`, so it has to be decoded
before being hashed: `sha256(packed.bytes)` hashes the 66 characters of the hex
text instead of the 32 packed bytes, and silently yields a leaf — and hence a
merkle root — the contract will never verify.
[`infra/scripts/merkle.test.ts`](../infra/scripts/merkle.test.ts) pins the
leaves against the LIGO implementation quoted above.

A claim also carries the proof, the sibling hash of each level from the leaf up
to the root, together with the side the accumulated hash sits on: a standard
merkle tree hashes `left ++ right`, so the contract cannot guess it and would
reject three leaves out of four if it always concatenated the sibling first.
