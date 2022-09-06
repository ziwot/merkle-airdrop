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

export const saveContractAddress = (name: string, address: string) =>
  outputFile(
    `${process.cwd()}/deployments/${name}.ts`,
    `export default "${address}";`
  );

export const between = (min: number, max: number) =>
  Math.floor(Math.random() * (max - min) + min);

// https://stackoverflow.com/a/53203618
const arbuf2hex = (buffer : ArrayBuffer) => {
  var hexCodes = [];
  var view = new DataView(buffer);
  for (var i = 0; i < view.byteLength; i += 4) {
    // Using getUint32 reduces the number of iterations needed (we process 4 bytes each time)
    var value = view.getUint32(i)
    // toString(16) will give the hex representation of the number without padding
    var stringValue = value.toString(16)
    // We use concatenation and slice for padding
    var padding = '00000000'
    var paddedValue = (padding + stringValue).slice(-padding.length)
    hexCodes.push(paddedValue);
  }

  // Join all the hex strings into one
  return hexCodes.join("");
}

export const sha256 = async (hexstr: string) => {
  // We transform the string into an arraybuffer.
  var buffer = new Uint8Array(hexstr.match(/[\da-f]{2}/gi).map(function (h) {
    return parseInt(h, 16)
  }));

  const hash = await crypto.subtle.digest("SHA-256", buffer);

  return arbuf2hex(hash);
}
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

const NB_DROPS = 25;

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
    "pkh": "tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn",
    "sk": "edsk341fqxiDSXEF2JPxoWe9T12hkegkyULx9D5szT4qFiuhDqnXFS",
    "amount": 22
  },
  {
    "pkh": "tz1dNuPfErDKZ5LPcdDQT7dVxt4KDgtUsp8T",
    "sk": "edsk341a4J9zZs3yipsKg7donjEvPpYcL5vDu87YkBkZRQVVHA6KQT",
    "amount": 19
  },
  {
    "pkh": "tz1WbpqNj8Pg9dbz1v8nJo9ofAHGGPQAcXTM",
    "sk": "edsk341UGdbmhCsiRMLgYidU8TT92zQTghVVf39DVvSHb65HP4ZY8H",
    "amount": 15
  },
  {
    "pkh": "tz1euJBs52Kphpp6inBjTLW62kjaoqMcXMgr",
    "sk": "edsk341NUy3YpYhT7sp3RKd8UBfMgAGK3K4mQxAtFf81kmf5PEfVVC",
    "amount": 17
  },
  {
    "pkh": "tz1SXh3iGeLHjLvDzjaT31fhoTbY2yAX8Uqa",
    "sk": "edsk341GhJVKwtXBpQHQHvcnousaKL8APve3AsCZ1PojvTEsSs9Daa",
    "amount": 26
  },
  {
    "pkh": "tz1UDyk7xaHN9eSHhpMqBUj5hPFicrWBACiC",
    "sk": "edsk33adE7GWDJp2QAoHnxB4pfuQcKRMBRL9BhJbGAzKA2A4B6CHwy",
    "amount": 46
  },
  {
    "pkh": "tz1hRijAMK1z3YHbnXfgiqCMjays4gcmPqZC",
    "sk": "edsk33aXSSiHLedm6hGefZAjAQ7dFVHCY2uQwcLG1ug3KhjrHeXayj",
    "amount": 37
  },
  {
    "pkh": "tz1S93mpYqbyrD54S8b5MY72yLnYZ7YsDGSx",
    "sk": "edsk33aRenA4TzTVoDk1YAAPW8Kqtf93teUghXMvmeMmVPKeNAVQVA",
    "amount": 48
  },
  {
    "pkh": "tz1U3WFN6EHRJojh3MFXeXQB8STxifofzPny",
    "sk": "edsk33aKs7bqbLHEVkDNQmA3qrY4XpzuFG3xTSPbXP3Vf4uSRhJQWP",
    "amount": 32
  },
  {
    "pkh": "tz1eTv9Jsem1SGLsijnyiBDTh7uDJQ6J3sUW",
    "sk": "edsk33aE5T3cig6yCGgjHN9iBakHAzrkbsdEDMRGH7jDpkVEUFgXCi",
    "amount": 25
  },
  {
    "pkh": "tz1hdsJKvDehBHB6SQPhpqquNjQD2Kxx5gkH",
    "sk": "edsk33a8HnVPr1vhtoA69y9NXJxVpAibxVCVyGSw2rQwzS52VWgmcX",
    "amount": 26
  },
  {
    "pkh": "tz1iNeAQkkR3X4oasDg3GYN6TS5TwuPzr6ig",
    "sk": "edsk33a2W7wAyMkSbKdT2a92s3AiTLaTK6mmjBUbnb6gA7epZ4eJsR",
    "amount": 13
  },
  {
    "pkh": "tz1SMT8G9sKFHDVvGzhDbfzfUGuiBMGrSPEm",
    "sk": "edsk33ZviTNx6haBHr6ouB8hCmNw6WSJfiM3V6WGYKnQKoEce9SkTP",
    "amount": 40
  },
  {
    "pkh": "tz1Wd9gcgMi6jHpLuTnfY21q5gtNQkCY4AMH",
    "sk": "edsk33ZpvnpjE3PuzNaAmn8MYVb9jgJA2KvKF1XwJ4U8VUpQmAnWuc",
    "amount": 34
  },
  {
    "pkh": "tz1gtr9FzvP8b6udyu6aocU57Q52TdzS23Lu",
    "sk": "edsk33Zj98GWMPDegu3XeP81tDoNNrA1NwVazvZc3o9rfAQCmTBVLe",
    "amount": 43
  },
  {
    "pkh": "tz1hvVs3R21zYSCEhS7KF9yRPJYEG26hQpK3",
    "sk": "edsk376yfX34yMEKSCiGyJ9GECV48BAbKJVkZEQAvxDPGLsPae3yFN",
    "amount": 48
  },
  {
    "pkh": "tz1N5PLwjZQHdhZ3pm5razcUz1AfsTbeDZFq",
    "sk": "edsk36fLKgG2WBkX1DwmCMe9eEd4ps4Hevva9Cntj6FEAjcWyugwM6",
    "amount": 26
  },
  {
    "pkh": "tz1PtyZr18vVDwQJJ6AfCmtnyPDw1qPYwpcB",
    "sk": "edsk36DgyqUz32GiaFBFRR934Gm5XYwyzZMPjBBcXEH558MePZq3qJ",
    "amount": 12
  },
  {
    "pkh": "tz1L3ThW2CWQykZjnQiYQKuzkBJxAnjULiU4",
    "sk": "edsk35n3dzhwZrnv9GQjeUdvUJu6EEqgLBnDK9aLKNJuyX6mo9ZJGh",
    "amount": 39
  },
  {
    "pkh": "tz1d45mw3qFZa94KTYh2LP2FBhn77RFbyQoN",
    "sk": "edsk35LQJ9vu6hK7iHeDsY8otM36vvjNfpD2u7y47WLksuqu7EfRdk",
    "amount": 48
  },
  {
    "pkh": "tz1U8TFfPx3H7QDGxks8uBQWwUieib1y7yqc",
    "sk": "edsk34tkxK9rdXqKHJsi6bdhJPB7dcd51SdrV6MmueNbnJb2Z3XRNe",
    "amount": 40
  },
  {
    "pkh": "tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod",
    "sk": "edsk34T7cUNpANMWrL7CKf8aiRK8LJWmM54g54kVhnQSghL9vdeGRw",
    "amount": 16
  },
  {
    "pkh": "tz1WbpqNj8Pg9dbz1v8nJo9ofAHGGPQAcXTM",
    "sk": "edsk341UGdbmhCsiRMLgYidU8TT92zQTghVVf39DVvSHb65HP4ZY8H",
    "amount": 38
  },
  {
    "pkh": "tz1Wd9gcgMi6jHpLuTnfY21q5gtNQkCY4AMH",
    "sk": "edsk33ZpvnpjE3PuzNaAmn8MYVb9jgJA2KvKF1XwJ4U8VUpQmAnWuc",
    "amount": 13
  },
  {
    "pkh": "tz1bKNizuoecFy2o7fMKdochymfce3oNZD5V",
    "sk": "edsk338Bax3gksv7ZPoezqdExXjASNBrMxM8pyvf6CVyPsZYAULH7g",
    "amount": 13
  }
]
```

## Merkle tree
