<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'id' => 1,
                'address' => 'Lorem ipsum dolor sit amet',
                'created' => 1688890710,
                'modified' => 1688890710,
            ],
        ];
        parent::init();
    }
}
