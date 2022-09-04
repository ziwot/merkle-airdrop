import dotenv from "dotenv";
import { TezosToolkit } from "@taquito/taquito";
import {
  Parser,
  packDataBytes,
  MichelsonData,
  MichelsonType,
} from "@taquito/michel-codec";
import { InMemorySigner } from "@taquito/signer";
import { MerkleTree } from "merkletreejs";
import { sha256 } from "./helpers";

// import code from "../compiled/merkle-airdrop.json";
// import drops from "./drops.json";

// Read environment variables from .env file
dotenv.config();

// Initialize RPC connection
const Tezos = new TezosToolkit(process.env.NODE_URL);

// Deploy to configured node with configured secret key
const deploy = async () => {
  try {
    const signer = await InMemorySigner.fromSecretKey(process.env.SECRET_KEY);

    Tezos.setProvider({ signer });

    const data = '(Pair "tz1bxhumMQDUi9hGd7FHGHBCjbY3qgfCr7Vn" 42)';
    const type = "(pair address nat)";

    const p = new Parser();
    const dataJSON = p.parseMichelineExpression(data);
    const typeJSON = p.parseMichelineExpression(type);

    const packed = packDataBytes(
      dataJSON as MichelsonData,
      typeJSON as MichelsonType
    );

    const elements = [await sha256(packed.bytes)];
    const merkleTree = new MerkleTree(elements, sha256, { sort: true });
    console.log(merkleTree);
  } catch (e) {
    console.log(e);
  }
};

deploy();
