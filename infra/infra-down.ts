import { execSync } from "node:child_process";
import cfg from "./config";

// INFRA-DOWN script
//
// This script is used to gracefully stop cfg.CONTAINER_NAME

try {
    console.info("Stopping container ...");
    execSync(`docker stop ${cfg.CONTAINER_NAME}`);
    console.info("[OK] stopped");
} catch (e) {
    console.error("the container could not be stopped.");
    console.error(e);
}
