import { readFileSync, writeFileSync } from "node:fs";
import Hjson from "hjson";
import { TESTDATA_PATH } from "./config";
import { between } from "./utils";

// For test purpose,
// generate a drops.json containing an array of keys, with a random amount

const MIN_AMOUNT = 1;
const MAX_AMOUNT = 200;

makeDrops(MIN_AMOUNT, MAX_AMOUNT);

function makeDrops(minAmount: number, maxAmount: number) {
    const accounts = Hjson.parse(
        readFileSync(`${TESTDATA_PATH}/accounts.hjson`, "utf8")
    ) as Record<string, { pkh: string }>;

    const drops = Object.values(accounts).map((account) => ({
        pkh: account.pkh,
        amount: between(minAmount, maxAmount),
    }));

    const fpath = `${TESTDATA_PATH}/drops.json`;
    writeFileSync(fpath, JSON.stringify(drops));
    console.info(`[OK] ${fpath} created with ${drops.length} drops`);
}
