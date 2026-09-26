import { execFileSync } from "node:child_process";
import { readFileSync, writeFileSync } from "node:fs";
import { TESTDATA_PATH } from "./config";

// For test purpose,
// generate a test token to be airdropped

const ALIAS = "token";
const OWNER = "tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb";
const GENESIS = 300;

// TZIP-16 metadata, stored as bytes: the UTF-8 bytes of its JSON form, in the
// micheline hex literal notation. Same as `[%bytes "…"]`, without the shell
// and without a ligo call to escape the JSON for.
const METADATA_BYTES = `0x${Buffer.from(
    JSON.stringify({
        name: "FA2",
        description: "Example FA2 MultiAsset",
        authors: ["Steven J."],
        homepage: "https://github.com/ziwot/merkle-airdrop",
        interfaces: ["TZIP-16-21fb73fe"],
    })
).toString("hex")}`;

makeToken();

function makeToken() {
    // the initial storage of the FA2 MultiAsset implementation pinned in
    // contract/ligo.json, built by contract/tests/token.mligo
    const storage = execFileSync(
        "ligo",
        [
            "compile",
            "expression",
            "cameligo",
            "--init-file",
            "./tests/token.mligo",
            `get_token_initial_storage(${METADATA_BYTES}, ("${OWNER}": address), 0n, ${GENESIS}n)`,
        ],
        { cwd: "../contract", encoding: "utf8" }
    ).trim();

    const storagePath = `${TESTDATA_PATH}/token_storage.tz`;
    writeFileSync(storagePath, storage);

    execFileSync("octez-client", [
        "originate",
        "contract",
        ALIAS,
        "transferring",
        "0",
        "from",
        "alice",
        "running",
        readFileSync(`${TESTDATA_PATH}/token.tz`, "utf8").trim(),
        "--init",
        storage,
        "--burn-cap",
        "0.84025",
        "--force",
    ]);

    const address = contractAddress();
    const fpath = `${TESTDATA_PATH}/token.json`;
    writeFileSync(fpath, JSON.stringify(address));
    console.info(`[OK] ${fpath} created, ${ALIAS} at ${address}`);
}

function contractAddress() {
    return execFileSync("octez-client", ["show", "known", "contract", ALIAS], {
        encoding: "utf8",
    }).trim();
}
