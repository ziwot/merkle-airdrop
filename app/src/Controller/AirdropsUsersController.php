<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AirdropsUsers Controller
 *
 * @property \App\Model\Table\AirdropsUsersTable $AirdropsUsers
 * @method \App\Model\Entity\AirdropsUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirdropsUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Airdrops', 'Users'],
        ];
        $airdropsUsers = $this->paginate($this->AirdropsUsers);

        $this->set(compact('airdropsUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Airdrops User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $airdropsUser = $this->AirdropsUsers->get($id, [
            'contain' => ['Airdrops', 'Users'],
        ]);

        $this->set(compact('airdropsUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $airdropsUser = $this->AirdropsUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $airdropsUser = $this->AirdropsUsers->patchEntity($airdropsUser, $this->request->getData());
            if ($this->AirdropsUsers->save($airdropsUser)) {
                $this->Flash->success(__('The airdrops user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The airdrops user could not be saved. Please, try again.'));
        }
        $airdrops = $this->AirdropsUsers->Airdrops->find('list', ['limit' => 200])->all();
        $users = $this->AirdropsUsers->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('airdropsUser', 'airdrops', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Airdrops User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $airdropsUser = $this->AirdropsUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $airdropsUser = $this->AirdropsUsers->patchEntity($airdropsUser, $this->request->getData());
            if ($this->AirdropsUsers->save($airdropsUser)) {
                $this->Flash->success(__('The airdrops user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The airdrops user could not be saved. Please, try again.'));
        }
        $airdrops = $this->AirdropsUsers->Airdrops->find('list', ['limit' => 200])->all();
        $users = $this->AirdropsUsers->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('airdropsUser', 'airdrops', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Airdrops User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $airdropsUser = $this->AirdropsUsers->get($id);
        if ($this->AirdropsUsers->delete($airdropsUser)) {
            $this->Flash->success(__('The airdrops user has been deleted.'));
        } else {
            $this->Flash->error(__('The airdrops user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
