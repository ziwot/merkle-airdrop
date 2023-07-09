<?php

namespace App\View\Cell;

use App\Tezos\Mutez;
use Bzzhh\Tzkt\Api\AccountsApi;
use Cake\View\Cell;

class BalanceCell extends Cell
{
    public function display()
    {
        $identity = $this->request->getAttribute('identity')->getOriginalData();
        $mutez = (new AccountsApi())->accountsGetBalance($identity->get('address'));
        $balance = (new Mutez($mutez))->tez();

        $this->set('balance', $balance);
    }
}
