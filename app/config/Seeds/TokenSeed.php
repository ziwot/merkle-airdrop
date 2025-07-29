<?php

declare(strict_types = 1);

use CakeTezos\Domain\Network;
use Migrations\BaseSeed;

/**
 * Token seed.
 */
class TokenSeed extends BaseSeed
{

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $this->execute('SET foreign_key_checks=0');
        $this->execute('TRUNCATE TABLE tokens');
        $this->execute('SET foreign_key_checks=1');

        $token = file_get_contents(ROOT . '/../infra/testdata/token.json');

        $data = [
            'network' => Network::Local->value,
            'address' => json_decode($token),
            'identifier' => 0,
        ];

        $table = $this->table('tokens');
        $table->insert($data)->save();
    }

}
