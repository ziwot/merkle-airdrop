<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Networks Model
 *
 * @method \App\Model\Entity\Network newEmptyEntity()
 * @method \App\Model\Entity\Network newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Network[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Network get($primaryKey, $options = [])
 * @method \App\Model\Entity\Network findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Network patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Network[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Network|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Network saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Network[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Network[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Network[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Network[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class NetworksTable extends Table
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

        $this->setTable('networks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
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
            ->scalar('name')
            ->maxLength('name', 55)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('base_url')
            ->maxLength('base_url', 55)
            ->requirePresence('base_url', 'create')
            ->notEmptyString('base_url');

        return $validator;
    }
}
