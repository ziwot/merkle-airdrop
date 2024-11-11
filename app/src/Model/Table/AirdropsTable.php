<?php

declare(strict_types = 1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Airdrops Model
 *
 * @property \App\Model\Table\TokensTable&\Cake\ORM\Association\BelongsTo $Tokens
 * @property \App\Model\Table\RecipientsTable&\Cake\ORM\Association\BelongsToMany $Recipients
 *
 * @method \App\Model\Entity\Airdrop newEmptyEntity()
 * @method \App\Model\Entity\Airdrop newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Airdrop[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Airdrop get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Airdrop findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Airdrop patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Airdrop[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Airdrop|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Airdrop saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Airdrop[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Airdrop[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Airdrop[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Airdrop[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AirdropsTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array<string,mixed> $config The configuration for the Table.
	 *
	 * @return void
	 */
	public function initialize(array $config): void {
		parent::initialize($config);

		$this->setTable('airdrops');
		$this->setDisplayField('name');
		$this->setPrimaryKey('id');

		$this->addBehavior('Timestamp');

		$this->belongsTo(
			'Tokens',
			[
			'foreignKey' => 'token_id',
			'joinType' => 'INNER',
			],
		);
		$this->belongsToMany(
			'Recipients',
			[
			'foreignKey' => 'airdrop_id',
			'targetForeignKey' => 'recipient_id',
			'joinTable' => 'airdrops_recipients',
			],
		);
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 *
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator): Validator {
		$validator
			->integer('token_id')
			->notEmptyString('token_id');

		$validator
			->scalar('merkle_root')
			->maxLength('merkle_root', 64)
			->allowEmptyString('merkle_root');

		$validator
			->scalar('address')
			->maxLength('address', 36)
			->allowEmptyString('address');

		$validator
			->scalar('name')
			->maxLength('name', 255)
			->requirePresence('name', 'create')
			->notEmptyString('name');

		$validator
			->scalar('description')
			->maxLength('description', 4294967295)
			->allowEmptyString('description');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 *
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules): RulesChecker {
		$rules->add($rules->existsIn('token_id', 'Tokens'), ['errorField' => 'token_id']);

		return $rules;
	}

	/**
	 * @return \Cake\ORM\Query\SelectQuery
	 */
	public function recentAirdrops(): SelectQuery {
		return $this->find(
			'all',
			limit: 5,
			order: 'Airdrops.created DESC',
			contain: ['Tokens'],
		);
	}

}
