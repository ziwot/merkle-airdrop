<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Tokens Controller
 *
 * @property \App\Model\Table\TokensTable $Tokens
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Token> paginate($object = null, array $settings = [])
 */
class TokensController extends AppController
{
    /**
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        $query = $this->Tokens->find();
        $q = $this->request->getQuery('q');

        if ($q) {
            $q = trim($q);
            $query = $query->where(['address LIKE' => "%{$q}%"]);
        }
        $tokens = $this->paginate($query);
        $this->set(compact('tokens', 'q'));

        if ($this->isHTMXRequest()) {
            $this->viewBuilder()
                ->setLayout('ajax')
                ->setTemplate('list');
        }
    }

    /**
     * @param string|null $id
     *
     * @return \Cake\Http\Response|null|void
     */
    public function view($id = null)
    {
        $token = $this->Tokens->get($id, contain: ['Airdrops']);

        $this->set(compact('token'));
    }

    /**
     * @return \Cake\Http\Response|null|void
     */
    public function add()
    {
        $token = $this->Tokens->newEmptyEntity();
        if ($this->request->is('post')) {
            $token = $this->Tokens->patchEntity($token, $this->request->getData());
            if ($this->Tokens->save($token)) {
                $this->Flash->success(__('The token has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The token could not be saved. Please, try again.'));
        }
        $this->set(compact('token'));
    }

    /**
     * @param string|null $id
     *
     * @return \Cake\Http\Response|null|void
     */
    public function edit($id = null)
    {
        $token = $this->Tokens->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $token = $this->Tokens->patchEntity($token, $this->request->getData());
            if ($this->Tokens->save($token)) {
                $this->Flash->success(__('The token has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The token could not be saved. Please, try again.'));
        }
        $this->set(compact('token'));
    }

    /**
     * @param string|null $id
     *
     * @return \Cake\Http\Response|null|void
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $token = $this->Tokens->get($id);
        if ($this->Tokens->delete($token)) {
            $this->Flash->success(__('The token has been deleted.'));
        } else {
            $this->Flash->error(__('The token could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
