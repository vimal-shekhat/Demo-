<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Controller\Component\CookieComponent;
use Cake\Datasource\ConnectionManager;

class UsersController extends AppController {

    public $helpers = array('Paginator' => array('Paginator'));
    public $paginate = array('limit' => 5, 'order' => ['id' => 'desc']);

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function index($roleId = null) {
        $this->set('title_for_layout', 'All Imported Data');
    }

    public function login() {

        if ($this->Auth->user('id')) {
            return $this->redirect(['action' => 'dashboard']);
        }
        $this->layout = 'login';
        $this->set('title_for_layout', 'Login');

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();			
            if (isset($this->request->data['remember_me']) && $this->request->data['remember_me'] == "on") { //User has checked Remember me                
                $cookie = array();
                $cookie['username'] = $this->request->data['username'];
                $cookie['password'] = $this->request->data['password'];
                $this->Cookie->write('rememberMe', $cookie, true, "2 weeks");
            }
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect(['action' => 'dashboard']);
            }
            $this->Flash->error('Invalid username or password. Please try again.');
        }
    }

    public function change_pwd() {
        $this->set('title_for_layout', 'Change PassWord');
        $user = $this->Users->get($this->Auth->user('id'));

        if (!empty($this->request->data)) {
            $user = $this->Users->patchEntity($user, ['old_password' => $this->request->data['old_password'], 'password' => $this->request->data['password1'], 'password1' => $this->request->data['password1'], 'password2' => $this->request->data['password2']], ['validate' => 'password']);

            if ($this->Users->save($user)) {
                $this->Flash->success('The password is successfully changed');
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } else {
                $this->Flash->error('There was an error during the save!');
            }
        }
        $this->set('user', $user);
    }

    public function dashboard() {
        $this->set('title_for_layout', 'Dashboard'); 
        $UserCount = $this->Users->find("all")->where(['Users.status !=' => '0'])->count();
        $this->set('UserCount', $UserCount);
    }

	 public function usersList() {
        $this->set('title_for_layout', 'List Of User');
		$query = $this->Users->find('all')->contain(['Profiles']);
        $this->set('users', $this->paginate($query));
	}
	 public function add($id = null) { 
        if (!empty($id)) {
			$user = $this->Users->get($id, ['contain' => ['Profiles']]);
        } else {
            $user = $this->Users->newEntity();
        }
		if ($this->request->is('post') || $this->request->is('PUT')) {            
         	if(!empty($user['profile']['photo'])){
				$user['old_photo'] = $user['profile']['photo'];
			}
            if (!empty($this->request->data['profile']['photo'])) {
               $this->request->data['profile']['photo'] = $this->Users->processImageUpload($this->request->data['profile'],$user); 
            } 
			$user = $this->Users->patchEntity($user, $this->request->data); 
            if ($this->Users->save($user)) {
                if ($user['old_photo']) {
                    $filename = basename($user['old_photo']);
					unlink(WWW_ROOT . 'img\users' . DIRECTORY_SEPARATOR . $filename);
		       }
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'usersList']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
		$this->set('title_for_layout', 'Edit User');
        $this->set('user', $user);
		$this->loadModel('Countries');
		$this->set('countries', $this->Countries->find('list',['keyField' => 'id','valueField' => 'country_name'])->toArray());

    }
	public function state()
	{		 
		$this->layout = false;
		$countryid=$this->request->data('country_id');
		$stateId = $this->request->data('state_id');
		$this->loadModel('States');
		$selectstate = $this->States->find('list',['keyField' => 'id','valueField' => 'state_name'])->where(['States.country_id' => $countryid])->toArray();
		$this->set('selectbox',$selectstate);
		$this->set('stateId',$stateId);
	}
	public function city()
	{		 
		$this->layout = false;
		$countryid=$this->request->data('country_id');
		$stateId = $this->request->data('state_id');
		$cityId = $this->request->data('city_id');
		$this->loadModel('Cities');
		$selectcity = $this->Cities->find('list',['keyField' => 'id','valueField' => 'city_name'])->where(['Cities.state_id' => $stateId])->toArray();
		$this->set('selectboxcity',$selectcity);  
		$this->set('cityId',$cityId);
	}


}
