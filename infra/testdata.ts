import fs from "fs";
import { execSync, spawnSync } from "node:child_process";
import { generateKeys, generateMnemonic } from "sotez";

// For test purpose,
// 1. generate a JSON containing an array of keys, with a random amount
// 2. generate a test token to be airdropped

const NB_DROPS = 400;
const MIN_AMOUNT = 1;
const MAX_AMOUNT = 200;
const DROPS_FILEPATH = "./testdata/drops.json";
const TOKEN_FILEPATH = "./testdata/token.json";

// add here some keys that you want to retrieve later
const DETEMINISTIC_KEYS = ["alice", "bob"];

if (!isSandboxRunning() || !isSandboxBootstrapped())
    console.error("Please check sandbox is running and bootstapped.");

makeDrops(MIN_AMOUNT, MAX_AMOUNT, NB_DROPS).then((drops) => {
    fs.writeFileSync(DROPS_FILEPATH, JSON.stringify(drops));
    console.info(
        `[OK] ${DROPS_FILEPATH} created with ${
            NB_DROPS + DETEMINISTIC_KEYS.length
        } drops`
    );
});

const tokenAddr = makeToken();
fs.writeFileSync(TOKEN_FILEPATH, JSON.stringify(tokenAddr));
console.log(`[OK] ${tokenAddr} deployed`);

function isSandboxRunning() {
    return (
        0 ===
        spawnSync("sh", ["-c", `docker compose ps | grep -q sandbox`]).status
    );
}

function isSandboxBootstrapped() {
    return (
        "Node is bootstrapped." ===
        execSync("docker compose exec sandbox octez-client bootstrapped")
            .toString()
            .trim()
    );
}

function makeToken() {
    execSync(`docker compose exec sandbox octez-client originate contract token \
    transferring 0 from alice running "$(cat testdata/token.tz)" \
    --init "$(cat testdata/token_storage.tz)" --burn-cap 0.62175 --force`);

    return execSync(
        "docker compose exec sandbox octez-client show known contract token"
    )
        .toString()
        .trim();
}

async function makeDrops(minAmount: number, maxAmount: number, nbKeys: number) {
    const drops = DETEMINISTIC_KEYS.map((name) => ({
        pkh: pubkey(name),
        amount: between(minAmount, maxAmount),
    }));

    for (let i = 0; i <= nbKeys; i++) {
        const key = await generateKeys(generateMnemonic());
        drops.push({
            pkh: key.pkh,
            amount: between(minAmount, maxAmount),
        });
    }

    return drops;
}

function between(min: number, max: number): number {
    return Math.floor(Math.random() * (max - min) + min);
}

// the sandbox key command is used, it gives us deterministic keys
function pubkey(name: string): string {
    return execSync(`docker compose run sandbox flextesa key ${name}`)
        .toString()
        .split(",")[2];
}
