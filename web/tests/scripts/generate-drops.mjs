import fs from "fs";
import { generateKeys, generateMnemonic } from "sotez";
import { between } from "./utils.mjs";

// get or create drops JSON file
// containing a bunch of keys

const NB_KEYS = 200;
const MIX_AMOUNT = 1;
const MAX_AMOUNT = 200;
const FILEPATH = "./tests/testdata/drops.json";

function* makeKeys() {
    for (let i = 0; i <= NB_KEYS; i++) {
        yield generateKeys(generateMnemonic());
    }
}

if (false === fs.existsSync(FILEPATH)) {
    const keys = makeKeys();
    const drops = [];

    for (const key of keys) {
        const amount = between(MIX_AMOUNT, MAX_AMOUNT);
        drops.push({ ...(await key), amount });
    }

    fs.writeFileSync(FILEPATH, JSON.stringify(drops));
}
