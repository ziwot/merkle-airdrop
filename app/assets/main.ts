import Alpine from 'alpinejs';
import Focus from "@alpinejs/focus"; // optional unless you want to use x-trap
import AlpineFloatingUI from "@awcodes/alpine-floating-ui";
import { BeaconEvent, DAppClient } from '@airgap/beacon-sdk';
import { createMessagePayload, signIn } from '@siwt/sdk'

window.Alpine = Alpine;
Alpine.plugin(Focus); // optional unless you want to use x-trap
Alpine.plugin(AlpineFloatingUI);
document.addEventListener('alpine:init', () => {
    Alpine.data('beacon', () => ({
        error: false,
        dAppClient: new DAppClient({ name: 'Tez Drops' }),
        async login(dappUrl: string, csrfToken: string) {
            try {
                this.dAppClient.subscribeToEvent(BeaconEvent.ACTIVE_ACCOUNT_SET, (account: string) => {
                    // An active account has been set, update the dApp UI
                    console.log(`${BeaconEvent.ACTIVE_ACCOUNT_SET} triggered: `, account);
                });

                // request wallet permissions with Beacon dAppClient
                const permissions = await this.dAppClient.requestPermissions();

                // create the message to be signed
                const messagePayload: any = createMessagePayload({
                    dappUrl: dappUrl,
                    pkh: permissions.address,
                });

                // request the signature
                const signedPayload = await this.dAppClient.requestSignPayload(messagePayload)

                // sign in the user to our app
                const { status, data }: any = await signIn(dappUrl)({
                    pk: permissions.accountInfo?.publicKey || '',
                    pkh: permissions.address,
                    message: messagePayload.payload,
                    signature: signedPayload.signature,
                    _csrfToken: csrfToken
                })
                if (200 === status) {
                    window.location.replace(dappUrl);
                }
                this.error = data.error;
            } catch (error) {
                console.log(error);
                const { statusText } = error.response;
                this.error = statusText;
            }
        }

    }))
});
Alpine.start();


