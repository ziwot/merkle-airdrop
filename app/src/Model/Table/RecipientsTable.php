<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Recipients Model
 *
 * @property \App\Model\Table\AirdropsTable&\Cake\ORM\Association\BelongsToMany $Airdrops
 *
 * @method \App\Model\Entity\Recipient newEmptyEntity()
 * @method \App\Model\Entity\Recipient newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Recipient[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Recipient get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Recipient findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Recipient patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Recipient[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Recipient|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Recipient saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RecipientsTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array<string,mixed> $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config): void {
		parent::initialize($config);

		$this->setTable('recipients');
		$this->setDisplayField('id');
		$this->setPrimaryKey('id');

		$this->addBehavior('Timestamp');

		$this->belongsToMany(
			'Airdrops',
			[
			'foreignKey' => 'recipient_id',
			'targetForeignKey' => 'airdrop_id',
			'joinTable' => 'airdrops_recipients',
			],
		);
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator): Validator {
		$validator
			->scalar('address')
			->maxLength('address', 36)
			->requirePresence('address', 'create')
			->notEmptyString('address');

		return $validator;
	}

}
