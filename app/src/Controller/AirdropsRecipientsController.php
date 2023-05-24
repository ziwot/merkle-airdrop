<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AirdropsRecipients Controller
 *
 * @property \App\Model\Table\AirdropsRecipientsTable $AirdropsRecipients
 * @method \App\Model\Entity\AirdropsRecipient[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirdropsRecipientsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Airdrops', 'Recipients'],
        ];
        $airdropsRecipients = $this->paginate($this->AirdropsRecipients);

        $this->set(compact('airdropsRecipients'));
    }

    /**
     * View method
     *
     * @param string|null $id Airdrops Recipient id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $airdropsRecipient = $this->AirdropsRecipients->get($id, [
            'contain' => ['Airdrops', 'Recipients'],
        ]);

        $this->set(compact('airdropsRecipient'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $airdropsRecipient = $this->AirdropsRecipients->newEmptyEntity();
        if ($this->request->is('post')) {
            $airdropsRecipient = $this->AirdropsRecipients->patchEntity($airdropsRecipient, $this->request->getData());
            if ($this->AirdropsRecipients->save($airdropsRecipient)) {
                $this->Flash->success(__('The airdrops recipient has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The airdrops recipient could not be saved. Please, try again.'));
        }
        $airdrops = $this->AirdropsRecipients->Airdrops->find('list', ['limit' => 200])->all();
        $recipients = $this->AirdropsRecipients->Recipients->find('list', ['limit' => 200])->all();
        $this->set(compact('airdropsRecipient', 'airdrops', 'recipients'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Airdrops Recipient id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $airdropsRecipient = $this->AirdropsRecipients->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $airdropsRecipient = $this->AirdropsRecipients->patchEntity($airdropsRecipient, $this->request->getData());
            if ($this->AirdropsRecipients->save($airdropsRecipient)) {
                $this->Flash->success(__('The airdrops recipient has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The airdrops recipient could not be saved. Please, try again.'));
        }
        $airdrops = $this->AirdropsRecipients->Airdrops->find('list', ['limit' => 200])->all();
        $recipients = $this->AirdropsRecipients->Recipients->find('list', ['limit' => 200])->all();
        $this->set(compact('airdropsRecipient', 'airdrops', 'recipients'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Airdrops Recipient id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $airdropsRecipient = $this->AirdropsRecipients->get($id);
        if ($this->AirdropsRecipients->delete($airdropsRecipient)) {
            $this->Flash->success(__('The airdrops recipient has been deleted.'));
        } else {
            $this->Flash->error(__('The airdrops recipient could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
