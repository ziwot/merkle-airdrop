<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class All extends BaseMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/5/en/migrations.html#the-up-method
     *
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
                'limit' => 2147483647,
                'null' => true,
            ])
            ->addColumn('metadata', 'json', [
                'collation' => 'utf8mb4_bin',
                'default' => null,
                'limit' => 2147483647,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('token_id')
                    ->setName('airdrops_ibfk_1')
            )
            ->create();

        $this->table('airdrops_recipients')
            ->addColumn('airdrop_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('recipient_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('amount', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('claimed', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('recipient_id')
                    ->setName('FK_9EB53DD7A76ED395')
            )
            ->addIndex(
                $this->index('airdrop_id')
                    ->setName('FK_9EB53DD713543E34')
            )
            ->create();

        $this->table('cake_seeds')
            ->addColumn('plugin', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('seed_name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('executed_at', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('recipients')
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
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
            ])
            ->addColumn('metadata', 'json', [
                'collation' => 'utf8mb4_bin',
                'default' => null,
                'limit' => 2147483647,
                'null' => true,
            ])
            ->addColumn('token_metadata', 'json', [
                'collation' => 'utf8mb4_bin',
                'default' => null,
                'limit' => 2147483647,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
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
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('address')
                    ->setName('pkh')
                    ->setType('unique')
            )
            ->create();

        $this->table('airdrops')
            ->addForeignKey(
                $this->foreignKey('token_id')
                    ->setReferencedTable('tokens')
                    ->setReferencedColumns('id')
                    ->setDelete('RESTRICT')
                    ->setUpdate('RESTRICT')
                    ->setName('airdrops_ibfk_1')
            )
            ->update();

        $this->table('airdrops_recipients')
            ->addForeignKey(
                $this->foreignKey('recipient_id')
                    ->setReferencedTable('recipients')
                    ->setReferencedColumns('id')
                    ->setDelete('RESTRICT')
                    ->setUpdate('RESTRICT')
                    ->setName('FK_9EB53DD7A76ED395')
            )
            ->addForeignKey(
                $this->foreignKey('airdrop_id')
                    ->setReferencedTable('airdrops')
                    ->setReferencedColumns('id')
                    ->setDelete('RESTRICT')
                    ->setUpdate('RESTRICT')
                    ->setName('FK_9EB53DD713543E34')
            )
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/5/en/migrations.html#the-down-method
     *
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
        $this->table('cake_seeds')->drop()->save();
        $this->table('recipients')->drop()->save();
        $this->table('tokens')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
