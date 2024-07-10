<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Recipients Controller
 *
 * @property \App\Model\Table\RecipientsTable $Recipients
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecipientsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $recipients = $this->paginate($this->Recipients);

        $this->set(compact('recipients'));
    }

    /**
     * View method
     *
     * @param string|null $id Recipient id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $recipient = $this->Recipients->get($id, contain: ['Airdrops']);

        $this->set(compact('recipient'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $recipient = $this->Recipients->newEmptyEntity();
        if ($this->request->is('post')) {
            $recipient = $this->Recipients->patchEntity($recipient, $this->request->getData());
            if ($this->Recipients->save($recipient)) {
                $this->Flash->success(__('The recipient has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The recipient could not be saved. Please, try again.'));
        }
        $airdrops = $this->Recipients->Airdrops->find('list', ['limit' => 200])->all();
        $this->set(compact('recipient', 'airdrops'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Recipient id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $recipient = $this->Recipients->get($id, contain: ['Airdrops']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $recipient = $this->Recipients->patchEntity($recipient, $this->request->getData());
            if ($this->Recipients->save($recipient)) {
                $this->Flash->success(__('The recipient has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The recipient could not be saved. Please, try again.'));
        }
        $airdrops = $this->Recipients->Airdrops->find('list', ['limit' => 200])->all();
        $this->set(compact('recipient', 'airdrops'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Recipient id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $recipient = $this->Recipients->get($id);
        if ($this->Recipients->delete($recipient)) {
            $this->Flash->success(__('The recipient has been deleted.'));
        } else {
            $this->Flash->error(__('The recipient could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
