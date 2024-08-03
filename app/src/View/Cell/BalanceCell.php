<?php

namespace App\View\Cell;

use App\Tezos\Mutez;
use App\Tezos\Network;
use Bzzhh\Tzkt\Api\AccountsApi;
use Bzzhh\Tzkt\Configuration;
use Cake\View\Cell;

class BalanceCell extends Cell {

	/**
	 * @param string $network
	 * @return void
	 */
	public function display(string $network = 'local') {
		$identity = $this->request->getAttribute('identity')->getOriginalData();
		$host = Network::from($network)->tzktUrl();
		$mutez = (new AccountsApi(null, (new Configuration())->setHost($host)))->accountsGetBalance($identity->get('address'));
		$balance = (new Mutez($mutez))->tez();

		$this->set('balance', $balance);
	}

}
