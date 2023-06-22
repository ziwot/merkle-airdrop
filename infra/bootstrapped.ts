import { execSync, spawnSync } from "node:child_process";

// check that sandbox is bootsrapped

if (!isSandboxRunning() || !isSandboxBootstrapped())
    console.error("Please check sandbox is running and bootstapped.");

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
