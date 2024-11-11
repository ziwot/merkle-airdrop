<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Recipients Controller
 *
 * @property \App\Model\Table\RecipientsTable $Recipients
 * @method \App\Model\Entity\Recipient[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecipientsController extends AppController {

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function index() {
		$query = $this->Recipients->find();
		$q = $this->request->getQuery('q');

		if ($q) {
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

	/**
	 * @param string|null $id
	 *
	 * @return \Cake\Http\Response|null|void
	 */
	public function view($id = null) {
		$recipient = $this->Recipients->get($id, contain: ['Airdrops']);

		$this->set(compact('recipient'));
	}

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function add() {
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
	 * @param string|null $id
	 *
	 * @return \Cake\Http\Response|null|void
	 */
	public function edit($id = null) {
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
	 * @param string|null $id
	 *
	 * @return \Cake\Http\Response|null|void
	 */
	public function delete($id = null) {
		$this->request->allowMethod('delete');
		$todoItem = $this->Recipients->get($id);
		if ($this->Recipients->delete($todoItem)) {
			$this->Flash->success(__('The recipient has been deleted.'));

			return $this->redirect(['action' => 'index']);
		}
		$this->Flash->error(__('The recipient could not be deleted.'));
	}

}
