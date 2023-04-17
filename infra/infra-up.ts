import { spawn, spawnSync, execSync } from "node:child_process";
import cfg from "./config";
import saveOriginatedAddress from "./helpers/saveOriginatedAddress";

// INFRA-UP script
//
// This script starts a new tezos container in detached mode
// The used container is named after cfg.CONTAINER_NAME and the used
// image is cfg.DOCKER_IMAGE
//
// The container is then accessed to:
// - initialize the rollup node config to point to cfg.RPC_URL
// - import cfg.OPERATOR_SK as alice
// - check that alice's balance is filled
// - originate a smart rollup from alice's address with cfg.KERNEL
// - and finally start the rollup node

// optionally, the script can be called with the address of a smart rollup
// otherwise, the script will originate a new smart rollup
const args = process.argv.slice(2);
let srAddress = args[0];

// should return 0 exit code if the container is running
// see https://stackoverflow.com/questions/38273253/using-two-commands-using-pipe-with-spawn/39482486#39482486
const isRunning = () =>
    0 ===
    spawnSync("sh", ["-c", `docker ps | grep -q ${cfg.CONTAINER_NAME}`]).status;

try {
    if (isRunning()) {
        throw `container ${cfg.CONTAINER_NAME} is already running.`;
    }

    console.info(
        "Initializing container (this may take a while in case of a new docker image)..."
    );
    execSync(
        `docker run -t --rm -d --entrypoint=/bin/sh \
        --name ${cfg.CONTAINER_NAME}  \
        --env TEZOS_CLIENT_UNSAFE_DISABLE_DISCLAIMER=y \
        ${cfg.DOCKER_IMAGE}`
    );
    console.info(`[OK] ${cfg.CONTAINER_NAME} initialized`);

    console.info("Updating rollup node config...");
    execSync(
        `docker exec ${cfg.CONTAINER_NAME} \
        octez-smart-rollup-node-alpha \
        --endpoint ${cfg.RPC_URL} config update`
    );
    console.info(`[OK] using ${cfg.RPC_URL} as endpoint`);

    console.info("Importing operator secret key...");
    execSync(
        `docker exec ${cfg.CONTAINER_NAME} \
        octez-client \
        import secret key alice unencrypted:${cfg.OPERATOR_SK}`
    );

    const account = execSync(
        `docker exec ${cfg.CONTAINER_NAME} \
        octez-client \
        show address alice`
    )
        .toString()
        .trim();

    console.info(
        `[OK] alice imported:
${account}`
    );

    const balance = execSync(
        `docker exec ${cfg.CONTAINER_NAME} \
        octez-client \
        get balance for alice`
    )
        .toString()
        .trim();

    if (parseInt(balance) > 0) {
        console.info(`[OK] alice is filled (${balance})`);
    } else {
        throw "Please get some tez from faucet";
    }

    if (undefined === srAddress) {
        console.info("Originating rollup...");
        srAddress = execSync(
            `docker exec ${cfg.CONTAINER_NAME} \
        octez-client \
        originate smart rollup \
        from alice \
        of kind wasm_2_0_0 of type bytes \
        with kernel "${cfg.KERNEL}" --burn-cap 999 | awk -F'Address:' '{print $2}'`
        )
            .toString()
            .trim();

        console.log(`[OK] smart rollup originated with address ${srAddress}`);
        saveOriginatedAddress("srAddress", srAddress);
    } else {
        console.info(`using smart rollup address ${srAddress}`);
    }

    console.info("Running smart rollup node...");
    execSync(
        `docker exec ${cfg.CONTAINER_NAME} \
        octez-smart-rollup-node-alpha \
        init operator config for ${srAddress} with operators alice`
    );

    spawn(
        "docker",
        ["exec", cfg.CONTAINER_NAME, "octez-smart-rollup-node-alpha", "run"],
        {
            stdio: "inherit",
            detached: true,
        }
    );
} catch (e) {
    console.error("the container could not be started.");
    console.error(e);
}
