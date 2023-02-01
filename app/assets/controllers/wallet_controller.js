import { Controller } from '@hotwired/stimulus';
import { BeaconWallet } from '@taquito/beacon-wallet';
import { PermissionScope, SigningType } from '@airgap/beacon-sdk';
import { char2Bytes } from '@taquito/utils';
import $ from 'jquery';

export default class extends Controller {
    static targets = ['loginForm'];
    static values = {
        logoutUrl: String,
    };

    wallet = null;

    async initialize() {
        const options = {
            name: 'Airdrop',
            iconUrl: 'https://tezostaquito.io/img/favicon.svg',
            preferredNetwork: 'ghostnet',
            eventHandlers: {
                PERMISSION_REQUEST_SUCCESS: {
                    handler: async (data) => {
                        console.log('permission data:', data);
                    },
                },
            },
        };
        this.wallet = new BeaconWallet(options);
    }

    async login() {
        const [pubKey, signature, payload] = await this.signLoginRequest();

        $(this.loginFormTarget).find('#pubKey').val(pubKey);
        $(this.loginFormTarget).find('#sig').val(signature);
        $(this.loginFormTarget).find('#msg').val(payload);
        $(this.loginFormTarget).trigger('submit');
    }

    async logout() {
        this.wallet.clearActiveAccount();
        window.location = this.logoutUrlValue;
    }

    async signLoginRequest() {
        await this.wallet.requestPermissions({
            network: {
                type: 'ghostnet',
                scopes: [PermissionScope.SIGN],
            },
        });
        // The data to format
        const dappUrl = 'tezos-test-d.app';
        const ISO8601formatedTimestamp = new Date().toISOString();
        const input = 'Hello world!';

        // The full string
        const formattedInput = [
            'Tezos Signed Message:',
            dappUrl,
            ISO8601formatedTimestamp,
            input,
        ].join(' ');

        // The bytes to sign
        const bytes = char2Bytes(formattedInput);
        const payloadBytes =
            '05' + '0100' + char2Bytes(bytes.length.toString()) + bytes;

        const { address, publicKey } = await this.wallet.client.getActiveAccount();

        // The payload to send to the wallet
        const payload = {
            signingType: SigningType.MICHELINE,
            payload: payloadBytes,
            sourceAddress: address,
        };

        // The signing
        const signedPayload = await this.wallet.client.requestSignPayload(
            payload
        );

        // The signature
        const { signature } = signedPayload;
        return [publicKey, signature, payload.payload];
    }
}
