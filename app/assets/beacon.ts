import { BeaconEvent, DAppClient } from "@airgap/beacon-sdk";
import { createMessagePayload, signIn } from "@siwt/sdk";

export default () => ({
	error: undefined,
	accountAddress: undefined,
	walletConnected: false,
	dAppClient: undefined,
	init() {
		this.dAppClient = new DAppClient({ name: 'Tez Drops' });

		this.dAppClient.subscribeToEvent(BeaconEvent.ACTIVE_ACCOUNT_SET, async (account) => {
			console.log(
				`${BeaconEvent.ACTIVE_ACCOUNT_SET} triggered: `,
				account?.address,
			);

			if (!account) {
				return;
			}

			this.accountAddress = account.address;
			this.walletConnected = true;
		});

	},
	disconnect(dappUrl: string) {
		this.dAppClient
			.disconnect()
			.then(() => {
				window.location.href = dappUrl;
			})
			.catch((err) => console.error(err.message));

	},
	async login(dappUrl: string, csrfToken: string) {
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

		window.location.href = dappUrl;
	},

	async logout(logoutUrl: string) {
		this.dAppClient.disconnect();
		window.location.href = logoutUrl;
	}
});
