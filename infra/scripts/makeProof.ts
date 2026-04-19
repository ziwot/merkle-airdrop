import { readFileSync, writeFileSync } from "node:fs";
import {
    type MichelsonType,
    Parser,
    packDataBytes,
} from "@taquito/michel-codec";
import SHA256 from "crypto-js/sha256.js";
import { MerkleTree } from "merkletreejs";
import { TESTDATA_PATH } from "./config";

// For test purpose, generate merkle tree

const tree = buildTree();
const merkleRoot = tree.getHexRoot();
writeFileSync(`${TESTDATA_PATH}/merkleRoot.json`, JSON.stringify(merkleRoot));

const tokenAddr = readFileSync(`${TESTDATA_PATH}/token.json`).toString();

writeFileSync(
    `${TESTDATA_PATH}/airdrop_storage.mligo`,
    `
let token = (${tokenAddr}: address), 0n
let merkle_root = ${merkleRoot}
`
);

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

interface Drop {
    pkh: string;
    amount: number;
}

function buildTree() {
    const dropsJson = readFileSync(`${TESTDATA_PATH}/drops.json`).toString();
    const leaves = JSON.parse(dropsJson).map((drop: Drop) =>
        getLeaf(drop.pkh, drop.amount)
    );

    return new MerkleTree(leaves, SHA256);
}
