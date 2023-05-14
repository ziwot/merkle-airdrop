<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DoctrineMigrationVersionsFixture
 */
class DoctrineMigrationVersionsFixture extends TestFixture
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
                'version' => 'f9659036-919e-43ae-9f31-42a221f37e8b',
                'executed_at' => '2023-05-14 10:38:36',
                'execution_time' => 1,
            ],
        ];
        parent::init();
    }
}
