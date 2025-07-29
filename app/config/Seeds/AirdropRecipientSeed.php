<?php

declare(strict_types = 1);

use Cake\Collection\Collection;
use Migrations\BaseSeed;

/**
 * AirdropRecipient seed.
 */
class AirdropRecipientSeed extends BaseSeed
{

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return ['AirdropSeed', 'RecipientSeed'];
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $this->execute('SET foreign_key_checks=0');
        $this->execute('TRUNCATE TABLE airdrops_recipients');
        $this->execute('SET foreign_key_checks=1');

        $json = file_get_contents(ROOT . '/../infra/testdata/drops.json');
        $data = (new Collection(json_decode($json)))
            ->map(
                fn ($drop, $index) => [
                            'airdrop_id' => 1,
                            'recipient_id' => ++$index,
                            'amount' => $drop->amount,
                        ],
            )
            ->toArray();

        $table = $this->table('airdrops_recipients');
        $table->insert($data)->save();
    }

}
