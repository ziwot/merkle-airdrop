<?php

namespace App\View\Cell;

use App\Tezos\Mutez;
use Bzzhh\Tzkt\Api\AccountsApi;
use Cake\View\Cell;
use App\Tezos\Network;
use Bzzhh\Tzkt\Configuration;

class BalanceCell extends Cell
{
    public function display(string $network = 'local')
    {
        $identity = $this->request->getAttribute('identity')->getOriginalData();
        $host = Network::from($network)->tzkt_url();
        $mutez = (new AccountsApi(null, (new Configuration)->setHost($host)))->accountsGetBalance($identity->get('address'));
        $balance = (new Mutez($mutez))->tez();

        $this->set('balance', $balance);
    }
}
