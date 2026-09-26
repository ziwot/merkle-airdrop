<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Recipients Model
 *
 * @property \App\Model\Table\AirdropsTable&\Cake\ORM\Association\BelongsToMany $Airdrops
 *
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Recipient>|false saveMany(iterable<\App\Model\Entity\Recipient> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Recipient> saveManyOrFail(iterable<\App\Model\Entity\Recipient> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Recipient>|false deleteMany(iterable<\App\Model\Entity\Recipient> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<int, \App\Model\Entity\Recipient> deleteManyOrFail(iterable<\App\Model\Entity\Recipient> $entities, array<string, mixed> $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @extends \Cake\ORM\Table<array{Timestamp: \Cake\ORM\Behavior\TimestampBehavior}, \App\Model\Entity\Recipient>
 * @method \App\Model\Entity\Recipient patchEntity(\App\Model\Entity\Recipient $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Recipient> patchEntities(iterable<\App\Model\Entity\Recipient> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Recipient|false save(\App\Model\Entity\Recipient $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Recipient saveOrFail(\App\Model\Entity\Recipient $entity, array<string, mixed> $options = [])
 * @method bool delete(\App\Model\Entity\Recipient $entity, array<string, mixed> $options = [])
 * @method bool deleteOrFail(\App\Model\Entity\Recipient $entity, array<string, mixed> $options = [])
 */
class RecipientsTable extends Table
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
     *
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
