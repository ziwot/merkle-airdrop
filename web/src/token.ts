import {
    TezosToolkit,
    Signer,
    Wallet,
    ContractAbstraction,
    compose,
} from "@taquito/taquito";
import {
    Tzip12Module,
    tzip12,
    Tzip12ContractAbstraction,
} from "@taquito/tzip12";
import {
    Tzip16Module,
    tzip16,
    Tzip16ContractAbstraction,
} from "@taquito/tzip16";
import { RpcClientInterface } from "@taquito/rpc";

export class TokenContract {
    private toolkit: TezosToolkit;

    private contractAddr: string;

    constructor(
        RPC_URL_or_TEZ_TOOLKIT: string,
        contractAddr: string,
        options: { signer: Signer }
    );

    constructor(
        RPC_URL_or_TEZ_TOOLKIT: TezosToolkit,
        contractAddr: string,
        options?: Record<string, unknown>
    );

    constructor(
        RPC_URL_or_TEZ_TOOLKIT: string | TezosToolkit,
        contractAddr: string,
        options: { signer?: Signer } = {}
    ) {
        if (typeof RPC_URL_or_TEZ_TOOLKIT === "string") {
            const toolkit = new TezosToolkit(RPC_URL_or_TEZ_TOOLKIT);
            toolkit.addExtension(new Tzip12Module());
            toolkit.addExtension(new Tzip16Module());
            toolkit.setProvider({ signer: options.signer });
            if (typeof options !== "object") options = {};
            this.toolkit = toolkit;
            this.contractAddr = contractAddr;
            return;
        }

        this.toolkit = RPC_URL_or_TEZ_TOOLKIT;
        this.contractAddr = contractAddr;
    }

    async get_contract(): Promise<
        ContractAbstraction<Wallet> & {
            tzip12: () => Tzip12ContractAbstraction;
            tzip16: () => Tzip16ContractAbstraction;
        }
    > {
        return await this.toolkit.wallet.at(
            this.contractAddr,
            compose(tzip12, tzip16)
        );
    }

    async get_rpc(): Promise<RpcClientInterface> {
        return this.toolkit.rpc;
    }

    get ContractAddress() {
        return this.contractAddr;
    }

    async get_contract_storage() {
        return (await this.get_contract()).storage();
    }

    async get_addr() {
        try {
            return await this.toolkit.wallet.pkh();
        } catch {
            return await this.toolkit.signer.publicKeyHash();
        }
    }

    async transfer(
        from_: string,
        to_: string,
        token_id: number,
        amount: number
    ) {
        const contract = await this.get_contract();
        return await contract.methodsObject
            .transfer([
                {
                    from_,
                    txs: [
                        {
                            to_,
                            token_id,
                            amount,
                        },
                    ],
                },
            ])
            .send();
    }
}
