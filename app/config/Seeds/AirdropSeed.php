<?php

declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Airdrop seed.
 */
class AirdropSeed extends AbstractSeed
{
    public function getDependencies(): array
    {
        return [
            'TokenSeed',
        ];
    }


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
        $this->execute('TRUNCATE TABLE airdrops');
        $this->execute('SET foreign_key_checks=1');

        $data = [
            'token_id' => 1,
            'name' => 'Test Airdrop',
            'description' => 'Testing',
            'created' => date('Y-m-d H:i:s')
        ];

        $table = $this->table('airdrops');
        $table->insert($data)->save();
    }
}
