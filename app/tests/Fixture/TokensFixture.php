<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TokensFixture
 */
class TokensFixture extends TestFixture
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
                'identifer' => 1,
                'created' => '2023-05-14 08:53:55',
                'modified' => '2023-05-14 08:53:55',
            ],
        ];
        parent::init();
    }
}
