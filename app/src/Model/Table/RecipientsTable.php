<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
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
 * @method \App\Model\Entity\Recipient get($primaryKey, $options = [])
 * @method \App\Model\Entity\Recipient findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Recipient patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Recipient[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Recipient|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Recipient saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RecipientsTable extends Table
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

        $this->setTable('recipients');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Airdrops', [
            'foreignKey' => 'recipient_id',
            'targetForeignKey' => 'airdrop_id',
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
            ->scalar('address')
            ->maxLength('address', 36)
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        return $validator;
    }
}
