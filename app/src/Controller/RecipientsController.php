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
    public function index(): void
    {
        $query = $this->Recipients->find();
        if ($q = $this->request->getQuery('q')) {
            $q = trim($q);
            $query = $query->where(['address LIKE' => "%{$q}%"]);
        }
        $recipients = $this->paginate($query);
        $this->set(compact('recipients', 'q'));

        if ($this->isHTMXRequest()) {
            $this->viewBuilder()
                ->setLayout('ajax')
                ->setTemplate('list');
        }
    }

    public function view(?string $id = null): void
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
        $contact = $this->Recipients->newEmptyEntity();
        if ($this->request->is('post')) {
            $contact = $this->Recipients->patchEntity($contact, $this->request->getData());
            if ($this->Recipients->save($contact)) {
                $this->Flash->success(__('The contact has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contact could not be saved. Please, try again.'));

        }
        $this->set(compact('contact'));
    }
    /**
     * Edit method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $contact = $this->Recipients->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contact = $this->Recipients->patchEntity($contact, $this->request->getData());
            if ($this->Recipients->save($contact)) {
                $this->Flash->success(__('The contact has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contact could not be saved. Please, try again.'));
        }
        $this->set(compact('contact'));
    }
    /**
     * Delete method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod('delete');
        $todoItem = $this->Recipients->get($id);
        if ($this->Recipients->delete($todoItem)) {
            $this->Flash->success(__('The contact has been deleted.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The contact could not be deleted.'));
    }
}
