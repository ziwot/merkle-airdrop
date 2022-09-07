# Sandbox Sample Data

As this repo focuses on smart contract side.

While handing on TEA, putting here some way to generate used Sample Data.

## Drops

Sample Data may be generated with this kind of scripts:

<h5 a><strong><code>helpers.ts</code></strong></h5>

```typescript
import { outputFile } from "fs-extra";
import crypto from "crypto";

export const saveJson = (path: string, data: string) =>
  outputFile(`${process.cwd()}/${path}.json`, data);

export const between = (min: number, max: number) =>
  Math.floor(Math.random() * (max - min) + min);
```

<h5 a><strong><code>drops.ts</code></strong></h5>

```typescript
import { execSync } from "node:child_process";
import { between } from "./helpers";
import { saveJson } from "./helpers";

// For test purpose,
// generate a JSON containing an array of keys, with a random amount
// the sandbox key command is used, it gives us deterministic keys
// TODO: can probably run in parallel to be faster

const NB_DROPS = 4;

let done = 0;
let drops = [];

do {
  const output = execSync(
    `docker run --rm oxheadalpha/flextesa:20220715 flextesa key ${done}`
  );

  const key = output.toString().split(",");

  drops = [
    {
      pkh: key[2],
      sk: key[3].substring(12, key[3].length - 1),
      amount: between(10, 50),
    },
    ...drops,
  ];

  done++;
} while (done < NB_DROPS);

saveJson("./scripts/drops", JSON.stringify(drops));
```

<h5 a><strong><code>drops.json</code></strong></h5>

```json
[
  {
    "pkh": "tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod",
    "sk": "edsk34T7cUNpANMWrL7CKf8aiRK8LJWmM54g54kVhnQSghL9vdeGRw",
    "amount": 32
  },
  {
    "pkh": "tz1WbpqNj8Pg9dbz1v8nJo9ofAHGGPQAcXTM",
    "sk": "edsk341UGdbmhCsiRMLgYidU8TT92zQTghVVf39DVvSHb65HP4ZY8H",
    "amount": 39
  },
  {
    "pkh": "tz1Wd9gcgMi6jHpLuTnfY21q5gtNQkCY4AMH",
    "sk": "edsk33ZpvnpjE3PuzNaAmn8MYVb9jgJA2KvKF1XwJ4U8VUpQmAnWuc",
    "amount": 20
  },
  {
    "pkh": "tz1bKNizuoecFy2o7fMKdochymfce3oNZD5V",
    "sk": "edsk338Bax3gksv7ZPoezqdExXjASNBrMxM8pyvf6CVyPsZYAULH7g",
    "amount": 10
  }
]
```

## Merkle tree

<h5 a><strong><code>merkle_tree.ts</code></strong></h5>

```typescript
import { MerkleTree } from "merkletreejs";
import SHA256 from "crypto-js/sha256";

const leaves = dropsJson.map((drop: any) =>
  SHA256(packDataBytes(data(drop.pkh, drop.amount), type).bytes)
);

console.log(leaves.map((leaf) => leaf.toString()));
const tree = new MerkleTree(leaves, SHA256);
const root = tree.getHexRoot()
console.log(root);

// [
//   '73e6a3e9e5a2b3d909f55b698cea5a668307a34b4fd5029d9a010f7183429806',
//   'c86dcb2a50e7f15d779be9c5b2b69b8899c9b1e67618467917d4a043884ce6c2',
//   '0ffa00dc1b8a89b698e140416c8d162bd9c599e6f5a7e3ef9ffb30a4c6f1d4b1',
//   'a9dbc6ac0e3461ea9690cdacffa63c78eeeb718b2f975f8ce65d24adebd5c236'
// ]
// 0x33560c62de2b695029e54fe4b395441ef53c7b4d08c7542502dbb346a9fac6a6

const proof = tree.getProof(
  "73e6a3e9e5a2b3d909f55b698cea5a668307a34b4fd5029d9a010f7183429806"
);
console.log(
  tree.verify(
    proof,
    "73e6a3e9e5a2b3d909f55b698cea5a668307a34b4fd5029d9a010f7183429806",
    root
  )
);
// true
```
