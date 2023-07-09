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

        $config = json_decode(file_get_contents(ROOT . '/../.taq/config.local.development.json'), true);
        $merkleRoot = json_decode(file_get_contents(ROOT . '/../infra/testdata/merkleRoot.json'));

        $data = [
            'merkle_root' => substr($merkleRoot, 2),
            'token_id' => 1,
            'address' => $config['contracts']['airdrop']['address'],
            'name' => 'Test Airdrop',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lorem, pretium vitae risus in, varius rhoncus est. Pellentesque at tellus odio. Donec eu tempus sapien, sit amet pulvinar metus. Phasellus dignissim convallis cursus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Maecenas varius porta auctor.',
        ];

        $table = $this->table('airdrops');
        $table->insert($data)->save();
    }
}
