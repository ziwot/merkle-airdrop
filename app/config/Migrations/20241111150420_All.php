<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class All extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        $this->table('airdrops')
            ->addColumn('token_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('merkle_root', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => true,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'current_timestamp()',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'token_id',
                ],
                [
                    'name' => 'airdrops_ibfk_1',
                ]
            )
            ->create();

        $this->table('airdrops_recipients')
            ->addColumn('airdrop_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('recipient_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('amount', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('claimed', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'recipient_id',
                ],
                [
                    'name' => 'FK_9EB53DD7A76ED395',
                ]
            )
            ->addIndex(
                [
                    'airdrop_id',
                ],
                [
                    'name' => 'FK_9EB53DD713543E34',
                ]
            )
            ->create();

        $this->table('recipients')
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'current_timestamp()',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('tokens')
            ->addColumn('network', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('identifier', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('metadata', 'text', [
                'collation' => 'utf8mb4_bin',
                'default' => null,
                'limit' => 4294967295,
                'null' => true,
            ])
            ->addColumn('token_metadata', 'text', [
                'collation' => 'utf8mb4_bin',
                'default' => null,
                'limit' => 4294967295,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'current_timestamp()',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('users')
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'current_timestamp()',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'address',
                ],
                [
                    'name' => 'pkh',
                    'unique' => true,
                ]
            )
            ->create();

        $this->table('airdrops')
            ->addForeignKey(
                'token_id',
                'tokens',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                    'constraint' => 'airdrops_ibfk_1'
                ]
            )
            ->update();

        $this->table('airdrops_recipients')
            ->addForeignKey(
                'recipient_id',
                'recipients',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                    'constraint' => 'FK_9EB53DD7A76ED395'
                ]
            )
            ->addForeignKey(
                'airdrop_id',
                'airdrops',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                    'constraint' => 'FK_9EB53DD713543E34'
                ]
            )
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down(): void
    {
        $this->table('airdrops')
            ->dropForeignKey(
                'token_id'
            )->save();

        $this->table('airdrops_recipients')
            ->dropForeignKey(
                'recipient_id'
            )
            ->dropForeignKey(
                'airdrop_id'
            )->save();

        $this->table('airdrops')->drop()->save();
        $this->table('airdrops_recipients')->drop()->save();
        $this->table('recipients')->drop()->save();
        $this->table('tokens')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
