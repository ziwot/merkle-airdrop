<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
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
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'token_id',
                ]
            )
            ->create();

        $this->table('airdrops_users')
            ->addColumn('airdrop_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('amount', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->addIndex(
                [
                    'airdrop_id',
                ]
            )
            ->create();

        $this->table('tokens')
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('identifier', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
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
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
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

        $this->table('airdrops_users')
            ->addForeignKey(
                'user_id',
                'users',
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

        $this->table('airdrops_users')
            ->dropForeignKey(
                'user_id'
            )
            ->dropForeignKey(
                'airdrop_id'
            )->save();

        $this->table('airdrops')->drop()->save();
        $this->table('airdrops_users')->drop()->save();
        $this->table('tokens')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
