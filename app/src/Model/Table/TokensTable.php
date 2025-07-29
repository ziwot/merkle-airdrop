<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tokens Model
 *
 * @property \App\Model\Table\AirdropsTable&\Cake\ORM\Association\HasMany $Airdrops
 *
 * @method \App\Model\Entity\Token newEmptyEntity()
 * @method \App\Model\Entity\Token newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Token> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Token get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Token findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Token patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Token> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Token|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Token saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Token>|false saveMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Token> saveManyOrFail(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Token>|false deleteMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Token> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @extends \Cake\ORM\Table<array{Timestamp: \Cake\ORM\Behavior\TimestampBehavior}>
 */
class TokensTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     *
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tokens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Airdrops', [
            'foreignKey' => 'token_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     *
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('network')
            ->maxLength('network', 15)
            ->requirePresence('network', 'create')
            ->notEmptyString('network');

        $validator
            ->scalar('address')
            ->maxLength('address', 36)
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        $validator
            ->integer('identifier')
            ->requirePresence('identifier', 'create')
            ->notEmptyString('identifier');

        $validator
            ->scalar('metadata')
            ->maxLength('metadata', 4294967295)
            ->allowEmptyString('metadata');

        $validator
            ->scalar('token_metadata')
            ->maxLength('token_metadata', 4294967295)
            ->allowEmptyString('token_metadata');

        return $validator;
    }
}
