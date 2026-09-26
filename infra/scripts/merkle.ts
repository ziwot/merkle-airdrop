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

export type ProofStep = {
    sibling: string;
    // true: the accumulated hash is the left operand of the parent,
    // i.e. the parent is sha256(acc ++ sibling)
    accOnLeft: boolean;
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
 * The tree is a standard one, MerkleTree hashes left ++ right. The contract
 * cannot know on which side a sibling sits, so the proof carries it, see
 * getProof() and MerkleProof.verify in contract/src/airdrop.mligo.
 */
export function buildTree(drops: Drop[]) {
    return new MerkleTree(
        drops.map((drop) => getLeaf(drop.pkh, drop.amount)),
        sha256
    );
}

/*
 * The proof of a drop, as expected by the claim entrypoint: the sibling
 * hashes, from the leaf up to the root, each one with the side the
 * accumulated hash sits on.
 *
 * merkletreejs does not expose that ordering, but it does not have to: the
 * sibling path is known, so the flags are the combination that folds back to
 * the root. There is at most one, a different one giving the root would be a
 * sha256 collision.
 */
export function getProof(drops: Drop[], index: number): ProofStep[] {
    const tree = buildTree(drops);
    const leaf = getLeaf(drops[index].pkh, drops[index].amount);
    const root = tree.getRoot().toString("hex");
    const siblings = tree
        .getHexProof(leaf)
        .map((sibling) => Buffer.from(sibling.slice(2), "hex"));

    for (let flags = 0; flags < 2 ** siblings.length; flags++) {
        const steps = siblings.map((sibling, level) => ({
            sibling: sibling.toString("hex"),
            accOnLeft: (flags >> level) % 2 === 1,
        }));

        if (fold(steps, leaf) === root) {
            return steps;
        }
    }

    throw new Error(`no proof found for drop ${index}`);
}

function fold(steps: ProofStep[], leaf: Buffer): string {
    return steps
        .reduce((acc, { sibling, accOnLeft }) => {
            const sib = Buffer.from(sibling, "hex");

            return sha256(
                accOnLeft
                    ? Buffer.concat([acc, sib])
                    : Buffer.concat([sib, acc])
            );
        }, leaf)
        .toString("hex");
}
