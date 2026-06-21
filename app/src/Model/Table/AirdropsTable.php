<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Expression\CommonTableExpression;
use Cake\ORM\Locator\LocatorAwareTrait;
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
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Airdrop>|false saveMany(iterable<\App\Model\Entity\Airdrop> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Airdrop> saveManyOrFail(iterable<\App\Model\Entity\Airdrop> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Airdrop>|false deleteMany(iterable<\App\Model\Entity\Airdrop> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Airdrop> deleteManyOrFail(iterable<\App\Model\Entity\Airdrop> $entities, array<string, mixed> $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @extends \Cake\ORM\Table<array{Timestamp: \Cake\ORM\Behavior\TimestampBehavior}, \App\Model\Entity\Airdrop>
 * @method \App\Model\Entity\Airdrop patchEntity(\App\Model\Entity\Airdrop $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Airdrop> patchEntities(iterable<\App\Model\Entity\Airdrop> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Airdrop|false save(\App\Model\Entity\Airdrop $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Airdrop saveOrFail(\App\Model\Entity\Airdrop $entity, array<string, mixed> $options = [])
 * @method bool delete(\App\Model\Entity\Airdrop $entity, array<string, mixed> $options = [])
 * @method bool deleteOrFail(\App\Model\Entity\Airdrop $entity, array<string, mixed> $options = [])
 */
class AirdropsTable extends Table
{
    use LocatorAwareTrait;

    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('airdrops');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->getSchema()->setColumnType('metadata', 'json');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Tokens', [
            'foreignKey' => 'token_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsToMany('Recipients', [
            'foreignKey' => 'airdrop_id',
            'targetForeignKey' => 'recipient_id',
            'joinTable' => 'airdrops_recipients',
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

        $validator
            ->scalar('metadata')
            ->maxLength('metadata', 4294967295)
            ->allowEmptyString('metadata');

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
        $rules->add($rules->existsIn(['token_id'], 'Tokens'), ['errorField' => 'token_id']);

        return $rules;
    }

    /**
     * @param string $network
     *
     * @return \Cake\ORM\Query\SelectQuery<\App\Model\Entity\Airdrop>
     */
    public function recentAirdrops(string $network): SelectQuery
    {
        /**
         * @var \App\Model\Table\AirdropsRecipientsTable $AirdropsRecipients
         */
        $AirdropsRecipients = $this->fetchTable('AirdropsRecipients');

        return $this->find(
            fields: [
                'name', 'address', 'description', 'token_id',
                'recipient_count' => 'recipients_per_airdrop.recipient_count',
                'sum_amount' => 'recipients_per_airdrop.sum_amount',
            ],
            limit: 5,
            order: 'Airdrops.created DESC',
            contain: ['Tokens'],
        )->with(
            function (CommonTableExpression $cte) use ($AirdropsRecipients) {
                $q = $AirdropsRecipients->subquery();
                $q->select(
                    [
                    'recipient_count' => $q->func()->count('*'),
                    'sum_amount' => $q->func()->sum('amount'),
                    'airdrop_id',
                    ],
                )->groupBy('airdrop_id');

                return $cte
                    ->name('recipients_per_airdrop')
                    ->query($q);
            },
        )
        ->leftJoinWith(
            'Tokens',
            fn (SelectQuery $q) => $q->where(['Tokens.network' => $network]),
        )->join(
            [
            'recipients_per_airdrop' => [
                'table' => 'recipients_per_airdrop',
                'type' => 'LEFT',
                'conditions' => ['recipients_per_airdrop.airdrop_id = Airdrops.id'],
            ],
             ],
        );
    }
}
