<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Networks Controller
 *
 * @property \App\Model\Table\NetworksTable $Networks
 * @method \App\Model\Entity\Network[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NetworksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $networks = $this->paginate($this->Networks);

        $this->set(compact('networks'));
    }

    /**
     * View method
     *
     * @param string|null $id Network id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $network = $this->Networks->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('network'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $network = $this->Networks->newEmptyEntity();
        if ($this->request->is('post')) {
            $network = $this->Networks->patchEntity($network, $this->request->getData());
            if ($this->Networks->save($network)) {
                $this->Flash->success(__('The network has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The network could not be saved. Please, try again.'));
        }
        $this->set(compact('network'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Network id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $network = $this->Networks->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $network = $this->Networks->patchEntity($network, $this->request->getData());
            if ($this->Networks->save($network)) {
                $this->Flash->success(__('The network has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The network could not be saved. Please, try again.'));
        }
        $this->set(compact('network'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Network id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $network = $this->Networks->get($id);
        if ($this->Networks->delete($network)) {
            $this->Flash->success(__('The network has been deleted.'));
        } else {
            $this->Flash->error(__('The network could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
