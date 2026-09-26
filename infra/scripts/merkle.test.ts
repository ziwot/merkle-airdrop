import assert from "node:assert/strict";
import { createHash } from "node:crypto";
import { test } from "node:test";
import { buildTree, type Drop, getLeaf, getProof } from "./merkle";

/*
 * The expected leaves are the output of the contract implementation, e.g.
 *
 *   ligo compile expression cameligo \
 *     'Crypto.sha256 (Bytes.pack (("tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn": address), 42n))'
 *
 * If getLeaf() ever returns something else, the JS and the LIGO sides of the
 * leaf hash have drifted apart: the generated merkle root would not match the
 * one the contract verifies, and every claim would fail. A taquito upgrade is
 * the likely way to break this, PackData used to be a Buffer.
 */

// the example of docs/README.md
test("getLeaf, address and amount", () => {
    assert.equal(
        getLeaf("tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn", 42).toString("hex"),
        "f526684b6478ea1fbf21107785d4036d5d650ab79bbd7ec6cebdcccdf5ad4c7d"
    );
});

test("getLeaf, lower amount bound", () => {
    assert.equal(
        getLeaf("tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb", 1).toString("hex"),
        "8b728decda84054ff3151da2b6f6d77b990a22c5543a2ae8441066d16e4b9073"
    );
});

// amounts above 2 ** 32 are packed on more bytes
test("getLeaf, amount above 2 ** 32", () => {
    assert.equal(
        getLeaf("tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb", 5_000_000_000).toString(
            "hex"
        ),
        "311fd0731d11ac37812385a7f0a66d5729cb3c9eff725910336bf06455762a64"
    );
});

// the fixture tree of contract/tests/merkle_proof.mligo
const fixture: Drop[] = [
    { pkh: "tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod", amount: 32 },
    { pkh: "tz1WbpqNj8Pg9dbz1v8nJo9ofAHGGPQAcXTM", amount: 39 },
    { pkh: "tz1Wd9gcgMi6jHpLuTnfY21q5gtNQkCY4AMH", amount: 20 },
    { pkh: "tz1bKNizuoecFy2o7fMKdochymfce3oNZD5V", amount: 10 },
];

// the verification done by MerkleProof.verify in contract/src/airdrop.mligo
function fold(drops: Drop[], index: number) {
    return getProof(drops, index)
        .reduce(
            (acc, { sibling, accOnLeft }) => {
                const sib = Buffer.from(sibling, "hex");
                const pair = accOnLeft
                    ? Buffer.concat([acc, sib])
                    : Buffer.concat([sib, acc]);

                return createHash("sha256").update(pair).digest();
            },
            getLeaf(drops[index].pkh, drops[index].amount)
        )
        .toString("hex");
}

test("every drop has a proof the contract accepts", () => {
    const root = buildTree(fixture).getHexRoot().slice(2);

    for (const index of fixture.keys()) {
        assert.equal(fold(fixture, index), root, `drop ${index}`);
    }
});

// the proof the contract test claims with, side by side with merkle_proof.mligo
test("getProof, the fixture of the contract tests", () => {
    assert.deepEqual(getProof(fixture, 1), [
        {
            sibling:
                "11d493116efc9abebcdb30c7df8d41f0f2d80bb73348eb7dd4a2ae99c7a62b2c",
            accOnLeft: false,
        },
        {
            sibling:
                "23b9ad2635bab6a413a847cee949c20abd17bff89576a9020d553dde2401d3f3",
            accOnLeft: true,
        },
    ]);
});

// merkletreejs returns the leaf itself as the root of a single leaf tree, so
// there is no sibling to carry and the contract folds an empty proof
test("getProof, a single drop needs no sibling", () => {
    const single = [fixture[0]];

    assert.deepEqual(getProof(single, 0), []);
    assert.equal(
        buildTree(single).getHexRoot().slice(2),
        getLeaf(single[0].pkh, single[0].amount).toString("hex")
    );
});

// two identical drops hash to the same leaf, so merkletreejs returns the path
// of the first for both, and it folds back to the root either way
test("getProof, two identical drops", () => {
    const twice = [fixture[0], fixture[0]];
    const root = buildTree(twice).getHexRoot().slice(2);

    for (const index of twice.keys()) {
        assert.equal(fold(twice, index), root, `drop ${index}`);
    }
});
