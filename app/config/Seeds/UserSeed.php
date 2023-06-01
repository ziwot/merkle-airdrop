<?php

declare(strict_types=1);

use Cake\Collection\Collection;
use Migrations\AbstractSeed;

/**
 * User seed.
 */
class UserSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $this->execute('SET foreign_key_checks=0');
        $this->execute('TRUNCATE TABLE users');
        $this->execute('SET foreign_key_checks=1');

        $created = date('Y-m-d H:i:s');

        $addresses = [
            'tz1baLSnTXirZwSqbH6LJf136JhP4J1FpvEG',
            'tz1dmn3QEzmVwtuf72B1bhsuA9uL8NYoRwxq',
            'tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb',
            'tz1aSkwEot3L2kmUvcoxzjMomb9mvBNuzFK6',
        ];

        $data = (new Collection($addresses))
            ->map(fn ($address) => ['address' => $address, 'created' => $created])
            ->toArray();

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
