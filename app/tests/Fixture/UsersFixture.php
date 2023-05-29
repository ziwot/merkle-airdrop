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
                'pkh' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-05-29 17:22:31',
                'modified' => '2023-05-29 17:22:31',
            ],
        ];
        parent::init();
    }
}
