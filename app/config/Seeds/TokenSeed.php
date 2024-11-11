<?php

declare(strict_types = 1);

use App\Tezos\Network;
use Migrations\AbstractSeed;

/**
 * Token seed.
 */
class TokenSeed extends AbstractSeed {

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
	public function run(): void {
		$this->execute('SET foreign_key_checks=0');
		$this->execute('TRUNCATE TABLE tokens');
		$this->execute('SET foreign_key_checks=1');

		$token = file_get_contents(ROOT . '/../infra/testdata/token.json');

		$data = [
			'network' => Network::LOCAL->value,
			'address' => json_decode($token),
			'identifier' => 0,
		];

		$table = $this->table('tokens');
		$table->insert($data)->save();
	}

}
