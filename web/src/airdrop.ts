import { Contract } from "./contract";

export class AirdropContract extends Contract {
    async setLock(locked: boolean) {
        const contract = await this.get_contract();
        return await contract.methodsObject.setLock(locked).send();
    }

    async claim(addr: string, amnt: number, merkle_proof: string[]) {
        const contract = await this.get_contract();
        return await contract.methodsObject
            .claim({
                addr,
                amnt,
                merkle_proof,
            })
            .send();
    }
}
