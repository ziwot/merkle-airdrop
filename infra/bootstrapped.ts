import { exec } from "child_process";

// wait for sandbox to be bootstrapped

(async () => {
    let isBootstrapped = await isSandboxBootstrapped();

    console.log("Waiting for the sandbox to be bootstrapped...");
    while (!isBootstrapped) {
        console.log("Not yet.");
        await sleep(5000);
        isBootstrapped = await isSandboxBootstrapped();
    }

    console.log("[OK] sandbox is bootstrapped");
})();

async function isSandboxBootstrapped(): Promise<boolean> {
    return new Promise((resolve, reject) => {
        exec("octez-client bootstrapped", (_, stdout) => {
            resolve(stdout ? "Node is bootstrapped." === stdout.trim() : false);
            reject(false);
        });
    });
}

function sleep(ms: number) {
    return new Promise((resolve) => {
        setTimeout(resolve, ms);
    });
}
