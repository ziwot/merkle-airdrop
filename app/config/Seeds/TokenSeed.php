<?php

declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Token seed.
 */
class TokenSeed extends AbstractSeed
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
            'address' => '',
            'identifier' => 0,
            'created' => date('Y-m-d H:i:s')
        ];

        $table = $this->table('tokens');
        $table->insert($data)->save();
    }
}
