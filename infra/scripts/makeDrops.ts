import { readFileSync, writeFileSync } from "node:fs";
import Hjson from "hjson";
import { TESTDATA_PATH } from "./config";
import { between } from "./utils";

// For test purpose,
// generate a drops.json containing an array of keys, with a random amount

const MIN_AMOUNT = 1;
const MAX_AMOUNT = 200;

makeDrops(MIN_AMOUNT, MAX_AMOUNT).then((drops) => {
    const fpath = `${TESTDATA_PATH}/drops.json`;
    writeFileSync(fpath, JSON.stringify(drops));
    console.info(`[OK] ${fpath} created`);
});

async function makeDrops(minAmount: number, maxAmount: number) {
    const accounts = Hjson.parse(
        readFileSync(`${TESTDATA_PATH}/accounts.hjson`).toString()
    );

    const drops: { pkh: string; amount: number }[] = [];

    for (const name in accounts) {
        drops.push({
            pkh: accounts[name].pkh,
            amount: between(minAmount, maxAmount),
        });
    }

    return drops;
}
