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
