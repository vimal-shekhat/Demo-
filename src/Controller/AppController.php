<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Network\Email\Email;
use Cake\Core\App;
use Cake\Network\Exception\NotFoundException;
use Cake\Datasource\ConnectionManager;
use SoapClient;
use Cake\Utility\Security;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize() {
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie', ['expiry' => '1 day']);
        $this->Cookie->config(['expires' => '+10 days', 'httpOnly' => false]);

        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $authArray = [
                'loginRedirect' => ['controller' => 'Users', 'action' => 'dashboard', 'prefix' => 'admin'],
                'logoutRedirect' => ['controller' => 'Users', 'action' => 'login', 'prefix' => 'admin']
            ];
        } else {
            $authArray = [
                'loginRedirect' => ['controller' => 'Users', 'action' => 'index'],
                'logoutRedirect' => ['controller' => 'Users', 'action' => 'login']				
            ];
        }	
        $this->loadComponent('Auth', $authArray);
    }

    public function beforeFilter(Event $event) {
		// header('Content-type: application/json'); 
        $this->Cookie->key = 'qSI232qs*&sXOw!adre@34SAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^';
        $this->Cookie->httpOnly = true;

        if (!$this->Auth->User('id') && $this->Cookie->read('rememberMe')) {
            $cookie = $this->Cookie->read('rememberMe');

            $this->loadModel('Users');
            $user = $this->Auth->identify();
            $user = $this->Users->find('all', array(
                        'conditions' => array(
                            'Users.username' => $cookie['username'],
                        //  'Users.password' => $cookie['password']
                        )
                    ))->hydrate(false)->toArray();
            ;
            if ($user) {
                $this->request->data['username'] = $cookie['username'];
                $this->request->data['password'] = $cookie['password'];
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    return $this->redirect(['controller' => 'users', 'action' => 'dashboard', 'prefix' => 'admin']);
                } else {
                    return $this->redirect(['controller' => 'users', 'action' => 'logout', 'prefix' => 'admin']);
                }
            } else {
                return $this->redirect(['controller' => 'users', 'action' => 'logout', 'prefix' => 'admin']);
            }
        }
        $allAllowAction = [];
        $allAllowAction[] = 'login';
        $allAllowAction[] = 'logout';
        $allAllowAction[] = 'signup';
        $allAllowAction[] = 'unsubscribe';
        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $this->layout = 'admin';
            if ($this->Auth->User('id')) {
                $AdminRole = array('1', '2');
                if (!in_array($this->Auth->User('role_id'), $AdminRole)) {
                    return $this->redirect(Router::url('/', true));
                }
            }
        } else {
            $allAllowAction = array_combine(array_values($allAllowAction), array_values($allAllowAction));
            if ($this->Auth->User('id')) {
                if ($this->Auth->User('role_id') == 1 || $this->Auth->User('role_id') == 2) {
                    return $this->redirect(Router::url('/admin', true));
                }
            }
        }

        $this->Auth->allow($allAllowAction);

        $this->set('currentAction', $this->request->action);
        $this->set('currentController', $this->name);
        $this->set('currentpage', strtolower($this->name) . '_' . strtolower($this->request->action));

       
        if (!$this->name) {
            throw new NotFoundException('Could not find that post');
        }

        if (!defined('currentUserId')) {
            define("currentUserId", $this->Auth->User('id'));
        }
        if (!defined('currentUserRole')) {
            define("currentUserRole", $this->Auth->User('role_id'));
        }
        if (!defined('FullName')) {
            define("FullName", $this->Auth->User('first_name') . ' ' . $this->Auth->User('last_name'));
        }
        if (!defined('currentDateTime')) {
            define("currentDateTime", date('Y-m-d H:i:s'));
        }
        if (!defined('PROJECT_URL')) {
            define("PROJECT_URL", Router::url('/', true));
        }
        if (!defined('PROJECT_DIR')) {
            define("PROJECT_DIR", WWW_ROOT);
        }

        if (!defined('CLIENT_IP')) {
            define("CLIENT_IP", $this->request->clientIp());
        }

        if (!defined('CURRENT_PAGE_URL')) {
            define("CURRENT_PAGE_URL", Router::url(null, true));
        }
        define("CSV_DIR", PROJECT_DIR . "file/emailnotification/csv/");
        define("CSV_URL", PROJECT_URL . "file/emailnotification/csv/");

        define("EDITOR_IMG_DIR", PROJECT_DIR . "img/editor_img/");
        define("EDITOR_IMG_URL", PROJECT_URL . "img/editor_img/");
    }

	 public function logout() {
        $this->Cookie->delete('rememberMe');
        return $this->redirect($this->Auth->logout());
    }
	
	 public function EmailSend($to = null, $subject = null, $template = null, $body = null, $from = ADMIN_EMAIL, $cc = NULL, $bcc = NULL) {
        $email = new Email();
        //$email->transport('default');
        $email->transport('Gmail');

        $this->isEmailSend = false;
        $email->to($to);
        $email->bcc($bcc);
        $email->cc($cc);
        $email->subject($subject);
        $email->from($from);

        if (empty($template) && !empty($body)) {
            $this->set('content', $body);
            $email->template('default');
        } else {
            $email->template($template);
        }

        $email->emailFormat('html');

        if ($email->send()) {
            $this->isEmailSend = true;
        } else {
            $this->isEmailSend = false;
        }
        return $this->isEmailSend;
    }


}
