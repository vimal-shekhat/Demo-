<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validator;

class UsersTable extends Table {

    public function initialize(array $config) {
        $this->hasOne("Profiles");
		//$this->hasMany('Hearts');
		$this->hasMany('Hearts',['foreignKey' => 'toid']);
		$this->hasMany('ViewProfiles',['foreignKey' => 'toid']);
		
    }

//    public function validationDefault(Validator $validator) {
//        $validator
//                ->notEmpty('name', 'Name is required')
//                ->notEmpty('username', 'Username is required')
//                ->notEmpty('password', 'Password is required')
//                ->notEmpty('c_password', 'Comfirm password is required')
//                ->notEmpty('role_id', 'Please select Role name')
//                ->add('c_password', 'compareWith', [
//                    'rule' => ['compareWith', 'password'],
//                    'message' => 'Passwords not equal.'
//                ])
//
////                ->requirePresence('username', 'create')
////                ->notEmpty('username','Username is required')
////                ->add('username', 'unique', ['rule' => 'validateUnique', 'message' => 'This user name is allready exits', 'provider' => 'table'])
////                
////                ->add('email', 'valid', ['rule' => 'email','message'=>'Email is required'])                
////                ->requirePresence('email', 'create')
////                ->notEmpty('email','Email is required')
////                ->add('email', 'unique', ['rule' => 'validateUnique', 'message' => 'This email id is allready exits', 'provider' => 'table'])
//        ;
//
//        return $validator;
//    }

    public function validationPassword(Validator $validator) {
        $validator->notEmpty('old_password', 'Password is required')->add('old_password', 'custom', ['rule' => function($value, $context) {
                $user = $this->get($context['data']['id']);
                if ($user) {                    
                    if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                        return true;
                    }
                } return false;
            }, 'message' => 'The old password does not match the current password!',])->notEmpty('old_password');
        $validator->notEmpty('password1', 'New password is required')->add('password1', ['match' => ['rule' => ['compareWith', 'password2'], 'message' => 'The passwords does not match!',]])->notEmpty('password1');
        $validator->notEmpty('password2', 'Confirm password is required')->add('password2', ['match' => ['rule' => ['compareWith', 'password1'], 'message' => 'The passwords does not match!',]])->notEmpty('password2');
        return $validator;
    }


public function processImageUpload($check = array(),$CurrentUserId = null) {
		/* $ext = substr(strtolower(strrchr($check['image_path']['name'], '.')), 1); //get the extension
        $arr_ext = array('jpg', 'jpeg', 'gif'); */
		//$ExplodeImageName = explode('.',$check['photo']['name']);
		$filename = basename($check['photo']['name']);
		$NewFileName = rand()."_".$filename;
		$check['photo']['name'] = $NewFileName;
		if(!is_uploaded_file($check['photo']['tmp_name'])){
		   return FALSE;
		}
		if (!move_uploaded_file($check['photo']['tmp_name'], WWW_ROOT . 'img' . DS . 'users' . DS . $check['photo']['name'])){
			return FALSE;
		}
		$check['photo'] = PROJECT_URL . 'img/users/'.$check['photo']['name']; 
		return $check['photo'];
	}
}
