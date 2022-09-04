import { outputFile } from "fs-extra";
import crypto from "crypto";

export const saveJson = (path: string, data: string) =>
  outputFile(`${process.cwd()}/${path}.json`, data);

export const saveContractAddress = (name: string, address: string) =>
  outputFile(
    `${process.cwd()}/deployments/${name}.ts`,
    `export default "${address}";`
  );

export const between = (min: number, max: number) =>
  Math.floor(Math.random() * (max - min) + min);

// https://stackoverflow.com/a/53203618
const arbuf2hex = (buffer : ArrayBuffer) => {
  var hexCodes = [];
  var view = new DataView(buffer);
  for (var i = 0; i < view.byteLength; i += 4) {
    // Using getUint32 reduces the number of iterations needed (we process 4 bytes each time)
    var value = view.getUint32(i)
    // toString(16) will give the hex representation of the number without padding
    var stringValue = value.toString(16)
    // We use concatenation and slice for padding
    var padding = '00000000'
    var paddedValue = (padding + stringValue).slice(-padding.length)
    hexCodes.push(paddedValue);
  }

  // Join all the hex strings into one
  return hexCodes.join("");
}

export const sha256 = async (hexstr: string) => {
  // We transform the string into an arraybuffer.
  var buffer = new Uint8Array(hexstr.match(/[\da-f]{2}/gi).map(function (h) {
    return parseInt(h, 16)
  }));

  const hash = await crypto.subtle.digest("SHA-256", buffer);

  return arbuf2hex(hash);
}
