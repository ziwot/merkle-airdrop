import fs from "fs";
import { generateKeys, generateMnemonic } from "sotez";
import Hjson from "hjson";
import {
    adjectives,
    animals,
    colors,
    Config,
    uniqueNamesGenerator,
} from "unique-names-generator";
import { between } from "./utils";
import { TESTDATA_PATH } from "./config";

// For test purpose,
// generate an acounts.hjson to be used with the tezbox sandbox
// (see ../contract/__tea/tools/sandbox/start.lua)

const NB_ACCOUNTS = 24;

makeAccounts(NB_ACCOUNTS);

async function makeAccounts(nb: number) {
    const accounts = Hjson.parse(
        fs.readFileSync(`${TESTDATA_PATH}/accounts.hjson.dist`).toString()
    );

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
    fs.writeFileSync(fpath, Hjson.stringify(accounts));
    // + alice and bob
    console.info(`[OK] ${fpath} created with ${NB_ACCOUNTS + 2} accounts`);
}
