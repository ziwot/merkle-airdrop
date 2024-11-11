import fs from "fs";
import { execSync } from "node:child_process";
import { generateKeys, generateMnemonic } from "sotez";
import { MerkleTree } from "merkletreejs";
import SHA256 from "crypto-js/sha256.js";
import { MichelsonType, packDataBytes, Parser } from "@taquito/michel-codec";

import sandboxName from "./bootstrapped";

// For test purpose,
// 1. generate a JSON containing an array of keys, with a random amount
// 2. generate a test token to be airdropped
// 3. generate merkle root

const NB_DROPS = 400;
const MIN_AMOUNT = 1;
const MAX_AMOUNT = 200;
const TESTDATA_PATH = "./testdata";

// add here some keys that you want to retrieve later
const DETEMINISTIC_KEYS = ["alice", "bob"];

makeDrops(MIN_AMOUNT, MAX_AMOUNT, NB_DROPS).then((drops) => {
    const fpath = `${TESTDATA_PATH}/drops.json`;
    fs.writeFileSync(fpath, JSON.stringify(drops));
    console.info(
        `[OK] ${fpath} created with ${
            NB_DROPS + DETEMINISTIC_KEYS.length
        } drops`
    );
});

const tree = buildTree();
const merkleRoot = tree.getHexRoot();

const tokenAddr = makeToken();
console.log(`[OK] ${tokenAddr} deployed`);

fs.writeFileSync(`${TESTDATA_PATH}/token.json`, JSON.stringify(tokenAddr));
fs.writeFileSync(`${TESTDATA_PATH}/merkleRoot.json`, JSON.stringify(merkleRoot));
fs.writeFileSync(
    `${TESTDATA_PATH}/airdrop_storage.mligo`,
    `
let token = ("${tokenAddr}": address), 0n
let merkle_root = ${merkleRoot}
`
);

function makeToken() {
    execSync(`docker exec ${sandboxName} octez-client originate contract token \
    transferring 0 from alice running "$(cat testdata/token.tz)" \
    --init "$(cat testdata/token_storage.tz)" --burn-cap 0.62175 --force`);

    return execSync(
        `docker exec ${sandboxName} octez-client show known contract token`
    )
        .toString()
        .trim();
}

async function makeDrops(minAmount: number, maxAmount: number, nbKeys: number) {
    const drops = DETEMINISTIC_KEYS.map((name) => ({
        pkh: pubkey(name),
        amount: between(minAmount, maxAmount),
    }));

    for (let i = 0; i <= nbKeys; i++) {
        const key = await generateKeys(generateMnemonic());
        drops.push({
            pkh: key.pkh,
            amount: between(minAmount, maxAmount),
        });
    }

    return drops;
}

function between(min: number, max: number): number {
    return Math.floor(Math.random() * (max - min) + min);
}

// the sandbox key command is used, it gives us deterministic keys
function pubkey(name: string): string {
    return execSync(`docker exec ${sandboxName} flextesa key ${name}`)
        .toString()
        .split(",")[2];
}

function getLeaf(pkh: string, amount: number) {
    const p = new Parser();

    return SHA256(
        packDataBytes(
            {
                prim: "Pair",
                args: [{ string: pkh }, { int: `${amount}` }],
            },
            p.parseMichelineExpression("(pair address nat)") as MichelsonType
        ).bytes
    );
}

function buildTree() {
    const dropsJson = fs.readFileSync(`${TESTDATA_PATH}/drops.json`).toString();
    const leaves = JSON.parse(dropsJson).map((drop: any) =>
        getLeaf(drop.pkh, drop.amount)
    );

    return new MerkleTree(leaves, SHA256);
}
