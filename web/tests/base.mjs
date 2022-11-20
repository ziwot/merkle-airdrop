import fs from "fs/promises";

import { InMemorySigner } from "@taquito/signer";
import { parse } from "hjson";
import { get } from "lodash-es";
import { TokenContract, AirdropContract } from "../dist/cjs/index.js";

const appHjson = parse((await fs.readFile("../app.hjson")).toString());

const SIGNER_KEY = "edsk3QoqBuvdamxouPhin7swCvkQNgq4jP5KZPbwWNnwdZpSpJiEbq";
const RPC_URL = `http://localhost:${get(appHjson, "sandbox.rpc_port", 20000)}`;

export const setup = async () => {
    const { id } = appHjson;
    const { contractAddress: airdropAddress } = JSON.parse(
        (await fs.readFile(`../deploy/sandbox-${id}.json`)).toString()
    );
    const { contractAddress: tokenAddress } = JSON.parse(
        (await fs.readFile(`../deploy/sandbox-token.json`)).toString()
    );
    const airdrop = new TokenContract(RPC_URL, airdropAddress, {
        test: true,
        signer: await InMemorySigner.fromSecretKey(SIGNER_KEY),
    });
    const token = new TokenContract(RPC_URL, tokenAddress, {
        test: true,
        signer: await InMemorySigner.fromSecretKey(SIGNER_KEY),
    });
    return {
        airdrop,
        token,
        airdropAddress,
        tokenAddress,
    };
};
