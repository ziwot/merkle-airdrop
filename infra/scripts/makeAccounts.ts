import { execFileSync } from "node:child_process";
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
const MAX_NAME_ATTEMPTS = 100;

const NAME_CONFIG: Config = {
    dictionaries: [adjectives, colors, animals],
    length: 1,
    style: "lowerCase",
};

type Account = {
    pkh: string;
    pk: string;
    sk: string;
    balance: number;
};

makeAccounts(NB_ACCOUNTS).catch((error: Error) => {
    console.error(`[KO] ${error.message}`);
    process.exit(1);
});

async function makeAccounts(nb: number) {
    const accounts = Hjson.parse(
        readFileSync(`${TESTDATA_PATH}/accounts.hjson.dist`, "utf8")
    ) as Record<string, Account>;

    // the seed accounts (alice, bob, eve) are imported in the host key store,
    // then kept as is in the generated file
    for (const [alias, { sk }] of Object.entries(accounts)) {
        execFileSync("octez-client", [
            "import",
            "secret",
            "key",
            alias,
            sk,
            "--force",
        ]);
    }

    const names = new Set(Object.keys(accounts));

    for (let i = 0; i < nb; i++) {
        const name = newName(names);
        names.add(name);

        const key = await generateKeys(generateMnemonic());
        accounts[name] = {
            pkh: key.pkh,
            pk: key.pk,
            sk: `unencrypted:${key.sk}`,
            balance: between(1, 100),
        };
    }

    const fpath = `${TESTDATA_PATH}/accounts.hjson`;
    writeFileSync(fpath, Hjson.stringify(accounts));
    console.info(
        `[OK] ${fpath} created with ${Object.keys(accounts).length} accounts`
    );
}

// the colors dictionary is small (52 words), so collisions happen in ~3% of the
// runs: a duplicated name would silently overwrite an existing account
function newName(taken: Set<string>): string {
    for (let attempt = 0; attempt < MAX_NAME_ATTEMPTS; attempt++) {
        const name = uniqueNamesGenerator(NAME_CONFIG);
        if (!taken.has(name)) {
            return name;
        }
    }

    throw new Error("no unique account name left, add dictionaries");
}
