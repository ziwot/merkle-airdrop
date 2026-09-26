import assert from "node:assert/strict";
import { test } from "node:test";
import { getLeaf } from "./merkle";

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
