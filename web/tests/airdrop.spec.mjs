import test from "ava";
import { setup, getLeaf } from "./base.mjs";
import dropsJson from "./testdata/drops.json" assert { type: "json" };
import { between, confirmOperation } from "./scripts/utils.mjs";

test.serial("claim airdrop", async (t) => {
    const { toolkit, airdrop, token, tree } = await setup();
    const { pkh, amount } = dropsJson[between(0, dropsJson.length - 1)];
    const merkle_proof = tree.getHexProof(getLeaf(pkh, amount));

    const claimOp = await airdrop.claim(pkh, amount, merkle_proof);
    await confirmOperation(toolkit, claimOp.opHash);

    let storage = await token.get_contract_storage();
    let actualAmount = await storage.ledger.get([pkh, 0]);

    t.assert(amount == actualAmount)
});
