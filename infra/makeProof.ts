import fs from "fs";
import { MerkleTree } from "merkletreejs";
import SHA256 from "crypto-js/sha256.js";
import { MichelsonType, packDataBytes, Parser } from "@taquito/michel-codec";
import { TESTDATA_PATH } from "./config";

// For test purpose, generate merkle tree

const tree = buildTree();
const merkleRoot = tree.getHexRoot();
fs.writeFileSync(
    `${TESTDATA_PATH}/merkleRoot.json`,
    JSON.stringify(merkleRoot)
);

const tokenAddr = fs.readFileSync(`${TESTDATA_PATH}/token.json`).toString();

fs.writeFileSync(
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

function buildTree() {
    const dropsJson = fs.readFileSync(`${TESTDATA_PATH}/drops.json`).toString();
    const leaves = JSON.parse(dropsJson).map((drop: any) =>
        getLeaf(drop.pkh, drop.amount)
    );

    return new MerkleTree(leaves, SHA256);
}
