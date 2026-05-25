import { execSync } from "node:child_process";
import { writeFileSync } from "node:fs";
import { TESTDATA_PATH } from "./config";

// For test purpose,
// generate a test token to be airdropped

const tokenAddr = makeToken();
console.log(`[OK] ${tokenAddr} deployed`);
writeFileSync(`${TESTDATA_PATH}/token.json`, JSON.stringify(tokenAddr));

function makeToken() {
    const metadata = {
        name: "FA2",
        description: "Example FA2 MultiAsset",
        authors: ["Steven J."],
        homepage: "https://github.com/ziwot/merkle-airdrop",
        interfaces: ["TZIP-16-21fb73fe"],
    };

    const metadataBytes = execSync(
        `ligo compile expression cameligo '[%bytes "${JSON.stringify(metadata).replace(/"/g, '\\"')}"]'`
    )
        .toString()
        .trim();

    const tokenStorage =
        execSync(`cd ../contract; ligo compile expression cameligo --init-file ./tests/token.mligo \
          'get_token_initial_storage(${metadataBytes}, ("tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb": address), 0n, 300n)'`)
            .toString()
            .trim();

    writeFileSync(`${TESTDATA_PATH}/token_storage.tz`, tokenStorage);

    execSync(`octez-client originate contract token \
transferring 0 from alice running "$(cat ${TESTDATA_PATH}/token.tz)" \
--init "$(cat ${TESTDATA_PATH}/token_storage.tz)" --burn-cap 0.84025 --force`);

    return execSync(`octez-client show known contract token`).toString().trim();
}
