import { Contract } from "./contract";

export class AirdropContract extends Contract {
    async claim(addr: string, amnt: number, merkle_proof: string[]) {
        const contract = await this.get_contract();
        return await contract.methodsObject
            .default({
                addr,
                amnt,
                merkle_proof,
            })
            .send();
    }
}
