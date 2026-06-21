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
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Token>|false saveMany(iterable<\App\Model\Entity\Token> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Token> saveManyOrFail(iterable<\App\Model\Entity\Token> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Token>|false deleteMany(iterable<\App\Model\Entity\Token> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Token> deleteManyOrFail(iterable<\App\Model\Entity\Token> $entities, array<string, mixed> $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @extends \Cake\ORM\Table<array{Timestamp: \Cake\ORM\Behavior\TimestampBehavior}, \App\Model\Entity\Token>
 * @method \App\Model\Entity\Token patchEntity(\App\Model\Entity\Token $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Token> patchEntities(iterable<\App\Model\Entity\Token> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Token|false save(\App\Model\Entity\Token $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Token saveOrFail(\App\Model\Entity\Token $entity, array<string, mixed> $options = [])
 * @method bool delete(\App\Model\Entity\Token $entity, array<string, mixed> $options = [])
 * @method bool deleteOrFail(\App\Model\Entity\Token $entity, array<string, mixed> $options = [])
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
        $this->getSchema()->setColumnType('metadata', 'json');
        $this->getSchema()->setColumnType('token_metadata', 'json');

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
