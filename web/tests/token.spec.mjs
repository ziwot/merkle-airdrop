import test from "ava";
import { setup } from "./base.mjs";

test.serial("alice transfers 200 to airdrop", async (t) => {
    const { token, airdropAddress } = await setup();

    let storage = await token.get_contract_storage();
    let initialValue = await storage.ledger.get([airdropAddress, 0]);
    initialValue = initialValue ? initialValue.toNumber() : 0;

    const signer = await token.get_addr();
    const amount = 200;
    await (
        await token.transfer(signer, airdropAddress, 0, amount)
    ).confirmation();

    storage = await token.get_contract_storage();
    t.is(
        (await storage.ledger.get([airdropAddress, 0])).toNumber(),
        initialValue + amount
    );
});
