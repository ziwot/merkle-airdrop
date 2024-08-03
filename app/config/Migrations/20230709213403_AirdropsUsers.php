<?php

use Migrations\AbstractMigration;

class AirdropsUsers extends AbstractMigration {

	/**
	 * Up Method.
	 *
	 * More information on this method is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
	 * @return void
	 */
	public function up(): void {
		$this->table('airdrops_recipients')
			->addColumn('claimed', 'timestamp', [
				'after' => 'amount',
				'default' => null,
				'length' => null,
				'null' => true,
			])
			->update();
	}

	/**
	 * Down Method.
	 *
	 * More information on this method is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
	 * @return void
	 */
	public function down(): void {
		$this->table('airdrops_recipients')->removeColumn('claimed')->update();
	}

}
