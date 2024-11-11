<?php

declare(strict_types = 1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

	/**
	 * @param \Cake\Event\EventInterface $event
	 *
	 * @return void
	 */
	public function beforeFilter(EventInterface $event) {
		parent::beforeFilter($event);

		$this->Authentication->allowUnauthenticated(['login']);
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index() {
		$users = $this->paginate($this->Users);

		$this->set(compact('users'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id User id.
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function view($id = null) {
		$user = $this->Users->get($id, contain: []);

		$this->set(compact('user'));
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$user = $this->Users->newEmptyEntity();
		if ($this->request->is('post')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($this->Users->save($user)) {
				$this->Flash->success(__('The user has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The user could not be saved. Please, try again.'));
		}
		$this->set(compact('user'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 *
	 * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
	 */
	public function edit($id = null) {
		$user = $this->Users->get($id, contain: []);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($this->Users->save($user)) {
				$this->Flash->success(__('The user has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The user could not be saved. Please, try again.'));
		}
		$this->set(compact('user'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id User id.
	 *
	 * @return \Cake\Http\Response|null|void Redirects to index.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function login() {
		$result = $this->Authentication->getResult();

		// If the user is logged in send them away.
		if ($result && $result->isValid()) {
			return $this->response->withType('application/json')->withStatus(200)
				->withStringBody('OK');
		}

		if ($this->request->is('post')) {
			return $this->response->withType('application/json')->withStatus(400)
				->withStringBody('Backend Failure.');
		}
	}

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function logout() {
		$this->Authentication->logout();

		return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	}

}
