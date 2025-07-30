<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class HomepageController extends AppController
{
    /**
     * @param \Cake\Event\EventInterface $event
     *
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['index']);
    }

    /**
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        $network = $this->Network->get();

        /**
         * @var \App\Model\Table\AirdropsTable $Airdrops
         */
        $Airdrops = $this->fetchTable('Airdrops');
        $recentAirdrops = $Airdrops->recentAirdrops($network)->all();

        $this->set('recentAirdrops', $recentAirdrops);
    }
}
