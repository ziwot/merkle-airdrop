<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AirdropsUsersFixture
 */
class AirdropsUsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'airdrop_id' => 1,
                'user_id' => 1,
                'amount' => 1,
            ],
        ];
        parent::init();
    }
}
