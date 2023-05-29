<?php

declare(strict_types=1);

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
        $data = [
            [
                'pkh' => 'tz1baLSnTXirZwSqbH6LJf136JhP4J1FpvEG',
            ],
            [
                'pkh' => 'tz1dmn3QEzmVwtuf72B1bhsuA9uL8NYoRwxq',
            ]
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
