<?php

namespace App\Controller;

use Cake\Event\EventInterface;

class HomepageController extends AppController {

	/**
	 * @param \Cake\Event\EventInterface $event
	 * @return void
	 */
	public function beforeFilter(EventInterface $event) {
		parent::beforeFilter($event);

		$this->Authentication->allowUnauthenticated(['index']);
	}

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function index() {
		/** @var \App\Model\Table\AirdropsTable $airdropsTable */
		$airdropsTable = $this->fetchTable('Airdrops');
		$recentAirdrops = $airdropsTable->recentAirdrops()->all();
		$totalAmounts = $recentAirdrops->reduce(
			function ($acc, $airdrop) {
				/** @var \App\Model\Table\AirdropsRecipientsTable $airdropsRecipientsTable */
				$airdropsRecipientsTable = $this->fetchTable('AirdropsRecipients');
				$recipients = $airdropsRecipientsTable->byAirdrop($airdrop->id)->all();
				$totalAmount = $recipients->sumOf('amount');

				return [...$acc, $airdrop->id => $totalAmount];
			},
			[],
		);

		$this->set('recentAirdrops', $recentAirdrops);
		$this->set('totalAmounts', $totalAmounts);
	}

}
