import test from "ava";
import { TezosToolkit } from "@taquito/taquito";
import { InMemorySigner } from "@taquito/signer";

import { setup, getLeaf } from "./base.mjs";
import dropsJson from "../../../infra/testdata/drops.json" assert { type: "json" };
import { between, confirmOperation } from "./utils.mjs";
import tokenAddr from "../../../infra/testdata/token.json" assert { type: "json" };

test.serial("claim airdrop", async (t) => {
    const toolkit = new TezosToolkit(process.env.RPC_URL);
    toolkit.setProvider({ signer: new InMemorySigner(process.env.SIGNER_KEY) });

    toolkit.rpc
        .getContract(tokenAddr)
        .catch(() => t.log(`The token ${tokenAddr} is not deployed!`));

    const { airdrop, token, tree } = await setup(toolkit, tokenAddr);
    const { pkh, amount } = dropsJson[between(0, dropsJson.length - 1)];
    const merkle_proof = tree.getHexProof(getLeaf(pkh, amount));

    const claimOp = await airdrop.claim(pkh, amount, merkle_proof);
    await confirmOperation(toolkit, claimOp.opHash);

    let storage = await token.get_contract_storage();
    let actualAmount = await storage.ledger.get([pkh, 0]);

    t.assert(amount == actualAmount);
});
