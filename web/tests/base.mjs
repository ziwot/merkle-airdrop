import dotenv from "dotenv";

import { MerkleTree } from "merkletreejs";
import SHA256 from "crypto-js/sha256.js";
import { InMemorySigner } from "@taquito/signer";
import { TokenContract, AirdropContract } from "../dist/cjs/index.js";
import { buf2hex, generateKeys, generateMnemonic } from "sotez";

import dropsJson from "./testdata/drops.json" assert { type: "json" };
import airdropCode from "../../compiled/airdrop.json" assert { type: "json" };
import { packDataBytes } from "@taquito/michel-codec";
import { MichelsonMap, TezosToolkit } from "@taquito/taquito";
import { confirmOperation } from "./scripts/utils.mjs";

dotenv.config();

export const getLeaf = (pkh, amount) => {
    const data = (addr, amount) => ({
        prim: "Pair",
        args: [{ string: addr }, { int: `${amount}` }],
    });

    const type = "(pair address nat)";

    return SHA256(packDataBytes(data(pkh, amount), type).bytes);
};

const buildTree = () => {
    const leaves = dropsJson.map((drop) => getLeaf(drop.pkh, drop.amount));

    return new MerkleTree(leaves, SHA256);
};

export const setup = async () => {
    const toolkit = new TezosToolkit(process.env.RPC_URL);
    toolkit.setProvider({ signer: new InMemorySigner(process.env.SIGNER_KEY) });

    const tree = buildTree();
    const root = tree.getHexRoot();

    try {
        const airdropAddress = await originate(
            airdropCode,
            `(Pair (Pair {}
            ${root}
            "${process.env.TOKEN_CONTRACT}"
            0)
      { Elt "" 0x05010000001574657a6f732d73746f726167653a636f6e74656e74 ;
        Elt "content" 0x01 })`
        );
        const airdrop = new AirdropContract(toolkit, airdropAddress, {
            test: true,
        });

        const token = new TokenContract(toolkit, process.env.TOKEN_CONTRACT, {
            test: true,
        });

        const totalAirdrop = dropsJson.reduce(
            (prev, curr) => curr.amount + prev,
            0
        );

        console.log(`total airdrop: ${totalAirdrop}`)
        
        console.log('Transferring amount to airdrop contract...')
        // otherwise, add contract as operator?

        await (
            await token.transfer(
                await airdrop.get_addr(),
                airdropAddress,
                0,
                totalAirdrop
            )
        ).confirmation();

        return { toolkit, airdrop, token, tree };
    } catch (e) {
        console.log(e);
    }
};

const originate = async (code, init) => {
    const toolkit = new TezosToolkit(process.env.RPC_URL);
    toolkit.setProvider({ signer: new InMemorySigner(process.env.SIGNER_KEY) });

    console.log(`Generating origination operation...`);
    const originationOp = await toolkit.contract.originate({
        code,
        init,
    });
    console.log(`Originating contract...`);
    await confirmOperation(toolkit, originationOp.hash);
    const { contractAddress } = originationOp;
    console.log(`[OK] ${contractAddress}`);

    return contractAddress;
};
