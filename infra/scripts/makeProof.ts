import { readFileSync, writeFileSync } from "node:fs";
import { TESTDATA_PATH } from "./config";
import { buildTree, type Drop } from "./merkle";

// For test purpose, generate merkle tree

const drops = JSON.parse(
    readFileSync(`${TESTDATA_PATH}/drops.json`, "utf8")
) as Drop[];

const fpath = `${TESTDATA_PATH}/merkleRoot.json`;
writeFileSync(fpath, JSON.stringify(buildTree(drops).getHexRoot()));
console.info(`[OK] ${fpath} created with ${drops.length} leaves`);
