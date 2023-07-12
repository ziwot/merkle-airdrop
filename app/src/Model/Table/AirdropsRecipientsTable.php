<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AirdropsRecipients Model
 *
 * @property \App\Model\Table\AirdropsTable&\Cake\ORM\Association\BelongsTo $Airdrops
 * @property \App\Model\Table\RecipientsTable&\Cake\ORM\Association\BelongsTo $Recipients
 *
 * @method \App\Model\Entity\AirdropsRecipient newEmptyEntity()
 * @method \App\Model\Entity\AirdropsRecipient newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AirdropsRecipient[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AirdropsRecipient get($primaryKey, $options = [])
 * @method \App\Model\Entity\AirdropsRecipient findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AirdropsRecipient patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AirdropsRecipient[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AirdropsRecipient|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AirdropsRecipient saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AirdropsRecipient[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AirdropsRecipient[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AirdropsRecipient[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AirdropsRecipient[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AirdropsRecipientsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('airdrops_recipients');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Airdrops', [
            'foreignKey' => 'airdrop_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Recipients', [
            'foreignKey' => 'recipient_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
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
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('airdrop_id', 'Airdrops'), ['errorField' => 'airdrop_id']);
        $rules->add($rules->existsIn('recipient_id', 'Recipients'), ['errorField' => 'recipient_id']);

        return $rules;
    }

    public function byAirdrop(int $airdropId)
    {
        return $this->find('all', [
            'conditions' => ['airdrop_id' => $airdropId]
        ])->all();
    }
}
