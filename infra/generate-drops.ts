import fs from "fs";
import { generateKeys, generateMnemonic } from "sotez";

const NB_KEYS = 200;
const MIN_AMOUNT = 1;
const MAX_AMOUNT = 200;

// Generate random drops and create testdata files for both contracts and app
makeDrops(MIN_AMOUNT, MAX_AMOUNT, NB_KEYS).then(([drops, phpDrops]) =>
    [
        ["../contracts/web/tests/testdata/drops.json", JSON.stringify(drops)],
        ["../app/fixtures/airdrop_user.json", JSON.stringify(phpDrops)],
    ].forEach(([filepath, content]) => {
        fs.writeFileSync(filepath, content);
        console.info(`[OK] ${filepath}`);
    })
);

type PhpDrop = {
    "App\\Entity\\User": {
        [key: string]: { address: string };
    };

    "App\\Entity\\AirdropUser": {
        [key: string]: { airdrop: string; user: string; amount: number };
    };
};

async function makeDrops(minAmount: number, maxAmount: number, nbKeys: number) {
    const drops = [];

    for (let i = 0; i <= nbKeys; i++) {
        drops.push({
            ...(await generateKeys(generateMnemonic())),
            amount: between(minAmount, maxAmount),
        });
    }

    const phpDrops: PhpDrop = {
        "App\\Entity\\User": {},
        "App\\Entity\\AirdropUser": {},
    };

    drops.forEach((drop, index) => {
        phpDrops["App\\Entity\\User"][`user_${index}`] = {
            address: drop.pkh,
        };

        phpDrops["App\\Entity\\AirdropUser"][`drop_${index}`] = {
            airdrop: "@airdrop_1",
            user: `@user_${index}`,
            amount: drop.amount,
        };
    });

    return [drops, phpDrops];
}

function between(min: number, max: number): number {
    return Math.floor(Math.random() * (max - min) + min);
}
