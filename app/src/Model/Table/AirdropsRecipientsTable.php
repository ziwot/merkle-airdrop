<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AirdropsRecipients Model
 *
 * @property \App\Model\Table\AirdropsTable&\Cake\ORM\Association\BelongsTo $Airdrops
 * @property \App\Model\Table\RecipientsTable&\Cake\ORM\Association\BelongsTo $Recipients
 *
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\AirdropsRecipient>|false saveMany(iterable<\App\Model\Entity\AirdropsRecipient> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\AirdropsRecipient> saveManyOrFail(iterable<\App\Model\Entity\AirdropsRecipient> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\AirdropsRecipient>|false deleteMany(iterable<\App\Model\Entity\AirdropsRecipient> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\AirdropsRecipient> deleteManyOrFail(iterable<\App\Model\Entity\AirdropsRecipient> $entities, array<string, mixed> $options = [])
 * @extends \Cake\ORM\Table<array{}, \App\Model\Entity\AirdropsRecipient>
 * @method \App\Model\Entity\AirdropsRecipient patchEntity(\App\Model\Entity\AirdropsRecipient $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\AirdropsRecipient> patchEntities(iterable<\App\Model\Entity\AirdropsRecipient> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\AirdropsRecipient|false save(\App\Model\Entity\AirdropsRecipient $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\AirdropsRecipient saveOrFail(\App\Model\Entity\AirdropsRecipient $entity, array<string, mixed> $options = [])
 * @method bool delete(\App\Model\Entity\AirdropsRecipient $entity, array<string, mixed> $options = [])
 * @method bool deleteOrFail(\App\Model\Entity\AirdropsRecipient $entity, array<string, mixed> $options = [])
 */
class AirdropsRecipientsTable extends Table
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

        $this->setTable('airdrops_recipients');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Airdrops',
            [
            'foreignKey' => 'airdrop_id',
            'joinType' => 'INNER',
            ],
        );
        $this->belongsTo(
            'Recipients',
            [
            'foreignKey' => 'recipient_id',
            'joinType' => 'INNER',
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
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('airdrop_id')
            ->notEmptyString('airdrop_id');

        $validator
            ->integer('recipient_id')
            ->notEmptyString('recipient_id');

        $validator
            ->integer('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->dateTime('claimed')
            ->allowEmptyDateTime('claimed');

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
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('airdrop_id', 'Airdrops'), ['errorField' => 'airdrop_id']);
        $rules->add($rules->existsIn('recipient_id', 'Recipients'), ['errorField' => 'recipient_id']);

        return $rules;
    }

    /**
     * Returns list of airdrops recipients matching given airdrop
     *
     * @param int $airdropId
     *
     * @return \Cake\ORM\Query\SelectQuery<\App\Model\Entity\AirdropsRecipient>
     */
    public function byAirdrop(int $airdropId): SelectQuery
    {
        return $this->find('all', conditions: ['airdrop_id' => $airdropId]);
    }
}
