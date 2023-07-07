import { execSync, spawnSync } from "node:child_process";

// check that sandbox is bootsrapped

(() => {
    if (!isSandboxRunning() || !isSandboxBootstrapped())
        console.error("Please check sandbox is running and bootstapped.");

    function isSandboxRunning() {
        return (
            0 ===
            spawnSync("sh", [
                "-c",
                `docker ps --format '{{.Names}}' | grep flextesa`,
            ]).status
        );
    }

    function isSandboxBootstrapped() {
        return (
            "Node is bootstrapped." ===
            execSync("octez-client bootstrapped").toString().trim()
        );
    }
})();

const name = spawnSync("sh", [
    "-c",
    `docker ps --format '{{.Names}}' | grep flextesa`,
]).stdout;

export default name.toString().trim();
