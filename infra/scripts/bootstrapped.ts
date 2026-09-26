import { exec } from "node:child_process";
import { setTimeout as sleep } from "node:timers/promises";

// Wait for the sandbox to be bootstrapped.
//
// `octez-client bootstrapped` exits 0 and prints "Node is bootstrapped." once
// the node is ready, and exits 1 with an empty stdout before that. Both are
// checked on purpose: if the wording ever changes, we get a timeout rather than
// a false [OK]. A sandbox bootstraps in ~20s on a dev machine, hence the 10
// minutes default, which can be raised to wait forever with
// BOOTSTRAPPED_TIMEOUT=0.

const COMMAND = "octez-client bootstrapped";
const READY = "Node is bootstrapped.";
const POLL_INTERVAL = 5000;
const TIMEOUT = Number(process.env.BOOTSTRAPPED_TIMEOUT ?? 600_000);

const start = Date.now();

(async () => {
    console.log("Waiting for the sandbox to be bootstrapped...");

    let reported = false;

    for (;;) {
        const { stdout, error } = await probe();

        if (!error && stdout.trim() === READY) {
            console.log(`[OK] sandbox is bootstrapped (${since()})`);
            return;
        }

        if (error && !reported) {
            reported = true;
            const lines = error.message.split("\n").map((line) => line.trim());
            console.error(`[KO] ${lines.filter(Boolean).pop()}`);
        }

        if (TIMEOUT > 0 && elapsed() >= TIMEOUT) {
            console.error(`[KO] sandbox is not bootstrapped after ${since()}`);
            process.exit(1);
        }

        console.log(`[..] not yet (${since()})`);
        await sleep(POLL_INTERVAL);
    }
})();

function probe(): Promise<{ stdout: string; error: Error | null }> {
    return new Promise((resolve) => {
        exec(COMMAND, (error, stdout) => resolve({ stdout, error }));
    });
}

function elapsed(): number {
    return Date.now() - start;
}

function since(): string {
    return `${Math.round(elapsed() / 1000)}s`;
}
