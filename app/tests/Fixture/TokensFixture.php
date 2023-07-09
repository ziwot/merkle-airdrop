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
                'network' => 'Lorem ipsum d',
                'address' => 'Lorem ipsum dolor sit amet',
                'identifier' => 1,
                'created' => 1688890705,
                'modified' => 1688890705,
            ],
        ];
        parent::init();
    }
}
