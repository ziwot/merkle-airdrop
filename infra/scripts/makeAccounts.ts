import { execSync } from "node:child_process";
import { readFileSync, writeFileSync } from "node:fs";
import Hjson from "hjson";
import { generateKeys, generateMnemonic } from "sotez";
import {
    adjectives,
    animals,
    type Config,
    colors,
    uniqueNamesGenerator,
} from "unique-names-generator";
import { TESTDATA_PATH } from "./config";
import { between } from "./utils";

// For test purpose,
// generate an accounts.hjson to be used with the tezbox sandbox
// (see https://github.com/tez-capital/tezbox#accounts)

const NB_ACCOUNTS = 24;

makeAccounts(NB_ACCOUNTS);

async function makeAccounts(nb: number) {
    const accounts = Hjson.parse(
        readFileSync(`${TESTDATA_PATH}/accounts.hjson.dist`).toString()
    );

    for (const [alias, data] of Object.entries(accounts)) {
        const account = data as { sk?: string; };
        const cmd = `octez-client import secret key ${alias} ${account.sk} --force`;
        execSync(cmd);
    }

    for (let i = 0; i <= nb; i++) {
        const key = await generateKeys(generateMnemonic());
        const config: Config = {
            dictionaries: [adjectives, colors, animals],
            length: 1,
            style: "lowerCase",
        };
        const name = uniqueNamesGenerator(config);
        accounts[name] = {
            pkh: key.pkh,
            pk: key.pk,
            sk: `unencrypted:${key.sk}`,
            balance: between(1, 100),
        };
    }

    const fpath = `${TESTDATA_PATH}/accounts.hjson`;
    writeFileSync(fpath, Hjson.stringify(accounts));
    // + alice and bob
    console.info(`[OK] ${fpath} created with ${NB_ACCOUNTS + 2} accounts`);
}
