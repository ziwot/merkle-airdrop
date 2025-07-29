<?php

declare(strict_types = 1);

use Cake\Collection\Collection;
use Migrations\BaseSeed;

/**
 * User seed.
 */
class UserSeed extends BaseSeed
{

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $this->execute('SET foreign_key_checks=0');
        $this->execute('TRUNCATE TABLE users');
        $this->execute('SET foreign_key_checks=1');

        $addresses = [
        'tz1baLSnTXirZwSqbH6LJf136JhP4J1FpvEG',
        'tz1dmn3QEzmVwtuf72B1bhsuA9uL8NYoRwxq',
        'tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb',
        'tz1aSkwEot3L2kmUvcoxzjMomb9mvBNuzFK6',
        ];

        $data = (new Collection($addresses))
            ->map(fn ($address) => ['address' => $address])
            ->toArray();

        $table = $this->table('users');
        $table->insert($data)->save();
    }

}
