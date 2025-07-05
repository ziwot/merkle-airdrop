<?php

declare(strict_types=1);

namespace App\View\Cell;

use App\Tezos\Mutez;
use Cake\View\Cell;
use Tzkt\Api\AccountsApi;
use function Tzkt\get_client;

class BalanceCell extends Cell
{
    /**
     * @param string $network
     *
     * @return void
     */
    public function display(string $network = 'local')
    {
        $AccountsApi = new AccountsApi(get_client());

        $identity = $this->request->getAttribute('identity')->getOriginalData();
        $mutez = $AccountsApi->accountsGetBalance($identity['address']);
        $balance = (new Mutez($mutez))->tez();

        $this->set('balance', $balance);
    }
}
