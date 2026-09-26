import { createHash } from "node:crypto";
import { type MichelsonType, packDataBytes } from "@taquito/michel-codec";
import { MerkleTree } from "merkletreejs";

// For test purpose, the merkle tree shared with the airdrop contract

// (pair address nat), the type of a drop
const DROP_TYPE = {
    prim: "pair",
    args: [{ prim: "address" }, { prim: "nat" }],
} as MichelsonType;

export type Drop = {
    pkh: string;
    amount: number;
};

// merkletreejs hashes buffers, it expects a Buffer -> Buffer function
const sha256 = (data: Buffer) => createHash("sha256").update(data).digest();

/*
 * The leaf hash is the trust boundary of the airdrop: it has to stay
 * byte-for-byte identical to
 *     Crypto.sha256 (Bytes.pack (addr, amnt))
 * in the claim entrypoint of contract/src/airdrop.mligo.
 *
 * packDataBytes() returns the packed value as an hex *string* (BytesLiteral),
 * not as bytes, so it must be decoded before being hashed: hashing the string
 * itself would silently hash its 66 characters instead of the 32 packed bytes.
 * scripts/merkle.test.ts pins this against the LIGO implementation.
 */
export function getLeaf(pkh: string, amount: number): Buffer {
    const packed = packDataBytes(
        {
            prim: "Pair",
            args: [{ string: pkh }, { int: `${amount}` }],
        },
        DROP_TYPE
    );

    return sha256(Buffer.from(packed.bytes, "hex"));
}

/*
 * The tree is unsorted (MerkleTree default), the pairing expected by
 * MerkleProof.verify (Bytes.concat h acc) in contract/src/airdrop.mligo.
 */
export function buildTree(drops: Drop[]) {
    return new MerkleTree(
        drops.map((drop) => getLeaf(drop.pkh, drop.amount)),
        sha256
    );
}
