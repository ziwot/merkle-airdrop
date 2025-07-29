import fs from "fs";
import { execSync } from "node:child_process";
import { TESTDATA_PATH } from "./config";

// For test purpose,
// generate a test token to be airdropped

const tokenAddr = makeToken();
console.log(`[OK] ${tokenAddr} deployed`);
fs.writeFileSync(`${TESTDATA_PATH}/token.json`, JSON.stringify(tokenAddr));

function makeToken() {
    execSync(`octez-client originate contract token \
    transferring 0 from alice running "$(cat ${TESTDATA_PATH}/token.tz)" \
    --init "$(cat ${TESTDATA_PATH}/token_storage.tz)" --burn-cap 0.62175 --force`);

    return execSync(`octez-client show known contract token`).toString().trim();
}
