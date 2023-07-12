<?php

namespace App\Controller;

use Cake\Datasource\ResultSetInterface;
use Cake\Log\Log;

class HomepageController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['index']);
    }

    public function index()
    {
        /** @var ResultSetInterface */
        $recentAirdrops = $this->fetchTable('Airdrops')->recentAirdrops();
        $totalAmounts = $recentAirdrops->reduce(
            function ($acc, $airdrop) {
                /** @var ResultSetInterface */
                $recipients = $this->fetchTable('AirdropsRecipients')->byAirdrop($airdrop->id);
                $totalAmount = $recipients->sumOf('amount');
                return [...$acc, $airdrop->id => $totalAmount];
            },
            []
        );

        $this->set('recentAirdrops', $recentAirdrops);
        $this->set('totalAmounts', $totalAmounts);
    }
}
