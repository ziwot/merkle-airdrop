<?php

namespace App\Controller;

class HomepageController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['index']);
    }

    public function index()
    {
        $recentAirdrops = $this->fetchTable('Airdrops')->recentAirdrops();

        $this->set('recentAirdrops', $recentAirdrops);
    }
}
