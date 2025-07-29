<?php

declare(strict_types = 1);

use Cake\Collection\Collection;
use Migrations\BaseSeed;

/**
 * Airdrop seed.
 */
class AirdropSeed extends BaseSeed
{

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return ['TokenSeed'];
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $this->execute('SET foreign_key_checks=0');
        $this->execute('TRUNCATE TABLE airdrops');
        $this->execute('SET foreign_key_checks=1');

        $output = $result = null;
        exec("octez-client list known contracts", $output, $result);

        $memoToAddress = new Collection($output)->reduce(function($acc, $v) {
            preg_match('/(.*): (.*)/', $v, $matches);
            $acc[$matches[1]] = $matches[2];
            return $acc;
        }, []);

        $merkleRoot = json_decode(
            file_get_contents(ROOT . '/../infra/testdata/merkleRoot.json'),
        );

        $data = [
            'address' => $memoToAddress['airdrop_dev'],
            'merkle_root' => substr($merkleRoot, 2),
            'token_id' => 1,
            'name' => 'Test Airdrop',
            'description' =>
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lorem, pretium vitae risus in, varius rhoncus est. Pellentesque at tellus odio. Donec eu tempus sapien, sit amet pulvinar metus. Phasellus dignissim convallis cursus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Maecenas varius porta auctor.',
        ];

        $table = $this->table('airdrops');
        $table->insert($data)->save();
    }

}
