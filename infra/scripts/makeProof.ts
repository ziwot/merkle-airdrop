import { readFileSync, writeFileSync } from "node:fs";
import { type MichelsonType, packDataBytes } from "@taquito/michel-codec";
import SHA256 from "crypto-js/sha256.js";
import { MerkleTree } from "merkletreejs";
import { TESTDATA_PATH } from "./config";

// For test purpose, generate merkle tree

// (pair address nat), the type of a drop: equivalent to parsing the
// expression, but done once instead of once per leaf
const DROP_TYPE = {
    prim: "pair",
    args: [{ prim: "address" }, { prim: "nat" }],
} as MichelsonType;

type Drop = {
    pkh: string;
    amount: number;
};

buildTree();

function getLeaf(pkh: string, amount: number) {
    return SHA256(
        packDataBytes(
            {
                prim: "Pair",
                args: [{ string: pkh }, { int: `${amount}` }],
            },
            DROP_TYPE
        ).bytes
    );
}

function buildTree() {
    const drops = JSON.parse(
        readFileSync(`${TESTDATA_PATH}/drops.json`, "utf8")
    ) as Drop[];

    const leaves = drops.map((drop) => getLeaf(drop.pkh, drop.amount));

    // MerkleTree defaults to unsorted pairs, the pairing expected by
    // MerkleProof.verify (Bytes.concat h acc) in contract/src/airdrop.mligo
    const tree = new MerkleTree(leaves, SHA256);

    const fpath = `${TESTDATA_PATH}/merkleRoot.json`;
    writeFileSync(fpath, JSON.stringify(tree.getHexRoot()));
    console.info(`[OK] ${fpath} created with ${leaves.length} leaves`);
}
