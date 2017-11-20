<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Cake\Routing\Router;

class UsersController extends AppController {
	function beforeFilter(Event $event){
		header('Content-type: application/json');
		define("PROJECT_URL", Router::url('/', true));
		$this->Auth->allow(); // allow all action
	}

	function signup(){
		if(!empty($this->request->data)){
			$UserDetails = $this->Users->find()->where(['Users.email'=>$this->request->data['email']])->hydrate(false)->first();
			if(!empty($UserDetails)){
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'Email address '.$this->request->data['email'].' is already registered.')));
				exit;
			}else{
				$pswd = $this->request->data['password'];
				$conform_pswd = $this->request->data['confirm_password'];
				if($pswd != $conform_pswd)
				{
					$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
					echo json_encode(array('result'=>$result));
					exit;
				}else{
					//fnMappingMasterData($this->request->data);
					$currentDate = date('Y-m-d H:i:s');
					$user = $this->Users->newEntity();
					$this->request->data['username'] = $this->request->data['email'];
					$this->request->data['created_date'] = $currentDate;
					$this->request->data['profile']['age'] = $this->request->data['age'];
					$this->request->data['profile']['gender'] = $this->request->data['gender'];
					$user = $this->Users->patchEntity($user, $this->request->data);
					if ($this->Users->save($user)){
						$user = $this->Auth->identify();
						$udata = array();
						if($user){
							$Token = md5($user['email'].date('Ymdhis'));
							$this->Users->updateAll
							(
								['token' => $Token, 'is_login' => '1'], // fields
								['id' => $user['id']] // conditions
							);
							$udata['id'] = $user['id'];
							$udata['token'] = $Token;
							echo json_encode(array('result'=>array('status'=>'success','msg'=>'Register successfully.','data'=>$udata)));
							exit;
						}else{
							echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error creating your account. Please try again.')));
							exit;
						}
						
					} else {
						echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error creating your account. Please try again.')));
						exit;
					}
				}
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error creating your account. Please try again.')));
			exit;
		}
	}

	function login() {
		if(!empty($this->request->data['email']) && !empty($this->request->data['password'])){
			$this->request->data['username'] = $this->request->data['email'];
			$user = $this->Auth->identify();
			if($user){
				$Token = md5($user['email'].date('Ymdhis'));
				$this->Users->updateAll
				(
					['token' => $Token, 'is_login' => '1'], // fields
					['id' => $user['id']] // conditions
				);
				$this->loadModel('Users');
				$user = $this->Users->get($user['id'],['contain' => ['Profiles']])->toArray();
				unset($user['first_name'],$user['last_name'],$user['role_id'],$user['username'],$user['email'],$user['token'],$user['is_login'],$user['created_by'],$user['created_date'],$user['updated_by'],$user['updated_date'],$user['is_delete']);
				$result = array('status'=>'success','msg'=>'LoggedIn successfully.','token'=>$Token,'data'=>$user);
			}else{
				$result = array('status'=>'error','msg'=>'Invalid email and password,Please try agin.');
			}
		}else{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
		}
		echo json_encode(array('result'=>$result));
		exit;
	}

	function logout(){
		if(!empty($this->request->data['token'])){
			$UserToken = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->hydrate(false)->first();
			if(!empty($UserToken)){
				$this->Users->updateAll
				(
					['token' => '', 'is_login' => '0'], //fields
					['token' => $this->request->data['token']] //conditions
				);
				$result = array('status'=>'success','msg'=>'Successfully logout.');
			}else{
				$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			}
		}else{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
		}
		echo json_encode(array('result'=>$result));
		exit;
	}

	function edit_profile(){
		if(!empty($this->request->data['token'])){
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->first();
		}
		if ($this->request->is('post') && isset($this->request->data['display_name'])) {
			$this->request->data['profile']['age']=$this->request->data['age'];
			$this->request->data['profile']['gender']=$this->request->data['gender'];
			$this->request->data['profile']['tags']=$this->request->data['tags'];
			$this->request->data['profile']['display_name']=$this->request->data['display_name'];
			$this->request->data['profile']['bio']=$this->request->data['bio'];
			$this->request->data['profile']['interested_in']=$this->request->data['interested_in'];
			$this->request->data['profile']['city']=$this->request->data['city'];
			$this->request->data['profile']['state_id']=$this->request->data['state_id'];
			$this->request->data['profile']['country_id']=$this->request->data['country_id'];
			$this->request->data['profile']['see_profile']=$this->request->data['see_profile'];
			$this->request->data['profile']['ageFrom']=$this->request->data['ageFrom'];
			$this->request->data['profile']['ageTo']=$this->request->data['ageTo'];
			$this->request->data['profile']['snapchat']=$this->request->data['snapchat'];
			$this->request->data['profile']['instagram']=$this->request->data['instagram'];
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)){
				echo json_encode(array('result'=>array('status'=>'success','msg'=>'Profile Added successfully.','data'=>$user)));
					exit;
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'Profile is not Added.')));
					exit;
			}
		}else{
				if(!empty($this->request->data['token']) && !empty($user)){
					$result = array('status'=>'success','data'=>$user);
				}else{
					$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
				}
		}
		echo json_encode(array('result'=>$result));
		exit;
	}

	function profile_photo(){
	/*if(!empty($this->request->data['token'])){
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->first(); 
		} */
		if($this->request->is('post') && isset($this->request->data['image_path'])) {
			$this->request->data['profile']['photo']="";
			if (!empty($this->request->data['image_path'])) {
			$this->request->data['profile']['photo']=$this->Users->processImageUpload($this->request->data['image_path']);
				unset($this->request->data['image_path']);
			} else {
				unset($this->request->data['image_path']);
			}
			$user = $this->Users->patchEntity($user, $this->request->data);
			if($this->Users->save($user)){
				echo json_encode(array('result'=>array('status'=>'success','msg'=>'Profile Photo Uploded successfully.','data'=>$user)));
				exit; 
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'Profile Photo is not Uploded.')));
				exit; 
			}
		}else{
			if(!empty($this->request->data['token']) && !empty($user)){
				$result = array('status'=>'success','data'=>$user);
			}else{
				$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			}
		}
		echo json_encode(array('result'=>$result));
		exit;
	}

	function getuser(){
		
		if (!empty($this->request->data['id']) &&  !empty($this->request->data['token'])) {
			
			$Login_user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->first();
			$user = $this->Users->find()->where(['Users.id'=>$this->request->data['id']])->contain(['Profiles'])->first(); 
			
			if($Login_user){
				$toid = $this->request->data['id'];
				$this->loadModel("ViewProfiles");
				$ViewProfileData= $this->ViewProfiles->find()->where(['ViewProfiles.fromid '=> $Login_user['id'],'ViewProfiles.toid '=>$toid])->count();
				if(!$ViewProfileData){
					unset($this->request->data['token'],$this->request->data['id']);
					$ViewProfiles = $this->ViewProfiles->newEntity();
					$this->request->data['fromid']= $Login_user['id'];
					$this->request->data['toid']= $toid; 
					$this->request->data['created'] = date('Y-m-d H:i:s');
					$ViewProfiles = $this->ViewProfiles->patchEntity($ViewProfiles, $this->request->data);
					$ResViewProfile = $this->ViewProfiles->save($ViewProfiles);
					$this->request->data['id'] = $toid;
				}
			}
			if(!empty($user)){
				$this->loadModel("Hearts");
				$res = $this->Hearts->find()->where(['Hearts.fromid '=> $Login_user['id'],'Hearts.toid '=>$this->request->data['id']])->select(['action'])->order(['Hearts.id ASC'])->hydrate(false)->toArray();
				$user['hearts']="No";
				$user['dhearts']="No";
				if(count($res) > 0){
					for($i=0;$i<count($res);$i++){
						$user[$res[$i]['action']]="Yes";
					}
				}else{
					$user['hearts']="No";
					$user['dhearts']="No";
				}
				$result = array('status'=>'success','data'=>$user);
			}else{
				$result = array('status'=>'success','data'=>$user);
			}
		}else{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
		}
		echo json_encode(array('result'=>$result));
		exit;
	}

	function forgot_password(){
			if(empty($this->request->data['email']))
			{
				$result = array('status'=>'error','msg'=>'Please provide your email address that you used to register with us.');
			}
			else
			{
				$fu = $this->Users->find()->where(['Users.email'=>$this->request->data['email']])->contain(['Profiles'])->hydrate(false)->first();
				if($fu)
				{
					if($fu['status']){
					$otp = rand(1000,9999);
					$ms= 'Your OTP Number : '.$otp;
					$fu['otp']=$otp;
					$this->Users->updateAll(['Users.otp' => $fu['otp']],['Users.id' => $fu['id']]);
					$this->request->data['url'] = $ms;
					$ToEmail = $fu['email'];
					$Subject = 'Reset Your Password';
					$this->request->data['message_to'] = $ToEmail;
					$this->request->data['user_name'] = $fu['username'];
					$this->request->data['message'] = $ms;
					 $this->request->data['unsubscribe_msg'] = "";
					$res = $this->EmailSend($to = $ToEmail, $subject = $Subject, $template = 'resetpw', $body = null, $from = 'sigmatesteremail@gmail.com');
						if($res){
								$result = array('status'=>'success','msg'=>'Check your email to reset your password','otp'=>$fu['otp']);
								echo json_encode(array('result'=>$result));
								exit;
						}else{
							$result = array('status'=>'error','msg'=>'Error Generating Reset link');
							echo json_encode(array('result'=>$result));
						 	exit;
						}
					}else{
						$result = array('status'=>'error','msg'=>'This Account is not Active yet.Check Your mail to activate it');
						echo json_encode(array('result'=>$result));
					 	exit;
					}
				}
				else
				{
					$result = array('status'=>'error','msg'=>'Email does Not Exist');
				}
			}
		 echo json_encode(array('result'=>$result));
	 	 exit;
	}

	function reset(){
		if(empty($this->request->data['otp']) || empty($this->request->data['password']) || empty($this->request->data['confirm_password']))
		{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			echo json_encode(array('result'=>$result));
			exit;
		}
		else
		{
			$pswd = $this->request->data['password'];
			$conform_pswd = $this->request->data['confirm_password'];
			if($pswd != $conform_pswd)
			{ 
				$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
				echo json_encode(array('result'=>$result));
				exit;
			}else{ 
				$user = $this->Users->find()->where(['Users.otp'=>$this->request->data['otp']])->first(); 
				if(!empty($user)){
				$user = $this->Users->patchEntity($user, $this->request->data);
				$user['password']=$this->request->data['password'];
				$user['otp']='';
				if($this->Users->save($user)){
					$result = array('status'=>'success','msg'=>'Your password has been reset successfully!','data'=>$user);
					echo json_encode(array('result'=>$result));
					exit;
				}
				}else{
					$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
					echo json_encode(array('result'=>$result));
					exit;
				}
			}
		}
		
	}

	function getnearbyuser(){
		if(empty($this->request->data['offset']) || empty($this->request->data['token']))
		{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			echo json_encode(array('result'=>$result));
			exit;
		}
		else{
			$offset = $this->request->data['offset'] - 20; 
			if(!empty($this->request->data['token'])){
				$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->hydrate(false)->first();
				$city = $user['profile']['city'];
				$this->loadModel("Profiles");
				$userdata = $this->Profiles->find()->where(['Profiles.city LIKE '=> $city,'Profiles.user_id !='=>$user['id']])->select(['user_id','display_name','age','photo'])->order(['Profiles.id ASC'])->offset($offset)->limit(20)->hydrate(false)->toArray();
				$main_array = array();
				$this->loadModel("Hearts");
				foreach($userdata as $udata){
					$PremiumMember = $this->Users->find()->where(['Users.id'=>$udata['user_id']])->select(['is_premium_member'])->hydrate(false)->first();
					$main_array1=$PremiumMember;
					$main_array1['user_id']=$udata['user_id'];
					$main_array1['display_name']=$udata['display_name'];
					$main_array1['age']=$udata['age'];
					$main_array1['photo']=$udata['photo'];
					$main_array1['hearts']="No";
					$main_array1['dhearts']="No";
					$res = $this->Hearts->find()->where(['Hearts.fromid '=> $user['id'],'Hearts.toid '=>$udata['user_id']])->select(['action'])->order(['Hearts.id ASC'])->hydrate(false)->toArray();
						if(count($res) > 0){
							for($i=0;$i<count($res);$i++){
								$main_array1[$res[$i]['action']]="Yes";
							}
						}else{
							$main_array1['hearts']="No";
							$main_array1['dhearts']="No";
						}
					$main_array[] = $main_array1;
					}
				if($main_array){
					$result = array('status'=>'success','data'=>$main_array);
					echo json_encode(array('result'=>$result));
					exit;
				}else{
					$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
					echo json_encode(array('result'=>$result));
					exit;
				}
			}else{
				$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
				echo json_encode(array('result'=>$result));
				exit;
			}
		}
	}

	function getcountries(){
		$this->loadModel("Countries");
		$data = $this->Countries->find('list',['keyField' => 'id','valueField' => 'country_name'])->toArray();
		if($data){
			$result = array('status'=>'success','data'=>$data);
		}else{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
		}
		echo json_encode(array('result'=>$result));
		exit;
	}

	function getstate(){
		if(empty($this->request->data['country_id']))
		{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			echo json_encode(array('result'=>$result));
			exit;
		}else{
			$this->loadModel("States");
			$data = $this->States->find('list',['keyField' => 'id','valueField' => 'state_name'])->where(['country_id' => $this->request->data['country_id']])->toArray();
			if($data){
				$result = array('status'=>'success','data'=>$data);
			}else{
				$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			}
			echo json_encode(array('result'=>$result));
			exit;
		}
	}
	
	public function getcity(){
		if(empty($this->request->data['state_id']))
		{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			echo json_encode(array('result'=>$result));
			exit;
		}else{
			$this->loadModel("Cities");
			$data = $this->Cities->find('list',['keyField' => 'id','valueField' => 'city_name'])->where(['state_id' => $this->request->data['state_id']])->toArray();
			if($data){
				$result = array('status'=>'success','data'=>$data);
			}else{
				$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			}
			echo json_encode(array('result'=>$result));
			exit;
		}
		
	}

	function upload_profile_photo(){
		//$postData = file_get_contents("php://input"); 
		//$data = explode('&',$postData);
		//$arr = array();
		//print_r($data);
		//file_put_contents('file.txt',$this->request->data,FILE_APPEND);
		//exit;
		//foreach($data as $i){
		//	$pdata = explode('=',$i);
		//	$arr[$pdata[0]]=$pdata[1];
		//}
		//$this->request->data
		if(!empty($this->request->data['filename'])) {
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->first(); 
			$file_name = rand()."_".$this->request->data['filename'];
			$postData = $this->request->data['image']; 
			//$postData = substr($arr['image'],0,'-1');
			if(!empty($postData))
			{
				if(!empty($user['profile']['photo']) && file_exists(WWW_ROOT . 'img' . DS . 'users' . DS .$user['profile']['photo'])){
					unlink(WWW_ROOT . 'img' . DS . 'users' . DS .$user['profile']['photo']);
				}
				$filePath = WWW_ROOT . 'img' . DS . 'users' . DS .$file_name; 
				$file_ext1 = explode('.',$file_name);
				$file_ext=strtolower($file_ext1[1]);
				$extensions= array("jpeg","jpg","png","tiff","gif","bmp");
				if(in_array($file_ext,$extensions)=== false){
					 $errors[]="Invalid file type, please upload a .jpg,.gif,.png,.tiff or a .bmp file";
				}
				if(empty($errors)==true){
					$fileData = base64_decode($postData);
					file_put_contents($filePath,$fileData);
				}
				$this->request->data['profile']['photo'] = PROJECT_URL.'img/users/'.$file_name;
				unset($this->request->data['image'],$this->request->data['filename']);
				$user = $this->Users->patchEntity($user, $this->request->data);
				
				if($this->Users->save($user)){
					echo json_encode(array('result'=>array('status'=>'success','msg'=>'Profile Photo Uploded successfully.','data'=>$user)));
					exit; 
				}else{
					echo json_encode(array('result'=>array('status'=>'error','msg'=>'Profile Photo is not Uploded.')));
					exit; 
				}
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'Profile Photo is not Uploded.')));
				exit; 
			}
		}else{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			echo json_encode(array('result'=>$result));
			exit;
		}
	}

	function get_random_num(){
		if(!empty($this->request->data['token'])){
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->first(); 
			if($user){
				$data = array();
				$cur_dt = date('Y-m-d');
				$profileDt = date('Y-m-d',strtotime( @$user['profile']['rdate']));
				$data['token']=$this->request->data['token'];
				if(($cur_dt > $profileDt) || $profileDt == NULL){
					$rnum = mt_rand(10,25);
					$data['random_number']=$rnum;
					$result = array('status'=>'success','data'=>$data);
				}else{
					$data['random_number']=0;
					$result = array('status'=>'success','msg'=>'You already got the bonus coins.','data'=>$data);
				}
				echo json_encode(array('result'=>$result));
				exit;
			
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}

	function random_num_ok(){
		if(!empty($this->request->data['token']) && !empty($this->request->data['random_number'])){ 
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->first(); 
			if($user){
				$data = array();
				$profileDt = date('Y-m-d',strtotime( $user['profile']['rdate']));
				$cur_dt = date('Y-m-d');
				if(($cur_dt > $profileDt) || $profileDt==NULL){
					$total_coins = $this->request->data['random_number'] + $user['profile']['coins'];
					$this->request->data['profile']['coins']=$total_coins;
					$this->request->data['profile']['rdate']=$cur_dt;
					$user = $this->Users->patchEntity($user, $this->request->data);
					if($this->Users->save($user)){
						
						$data['token']=$user['token'];
						$data['coins']=$user['profile']['coins'];
						$data['rdate']=date('Y-m-d',strtotime( $user['profile']['rdate']));
						
					}
					$result = array('status'=>'success','data'=>$data);
					echo json_encode(array('result'=>$result));
					exit;
				}else{
					echo json_encode(array('result'=>array('status'=>'error','msg'=>'You already got the bonus coins.')));
					exit;
				}
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}		
	}

	function getHeartsAction(){
		if(!empty($this->request->data['token']) && !empty($this->request->data['toid']) && !empty($this->request->data['action'])){ 
		$token = $this->request->data['token'];
		$toid = $this->request->data['toid'];
		$action = $this->request->data['action'];
		$From_userdata = $this->Users->find()->where(['Users.token'=>$token])->contain(['Profiles'])->first(); 
		$this->loadModel("Hearts");
		$res = $this->Hearts->find()->where(['Hearts.fromid '=> $From_userdata['id'],'Hearts.toid '=>$toid,'Hearts.action' => $action])->select(['action'])->order(['Hearts.id ASC'])->hydrate(false)->toArray();
		if(count($res) > 0){
			if($res[0]['action'] == 'hearts'){
				$action_txt = 'Hearts';
			}else if($res[0]['action'] == 'dhearts'){
				$action_txt = 'Double Hearts';
			}
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'You successfully already sent '.$action_txt.'.')));
			exit;
		}else{
			unset($this->request->data['token']);
			$user = $this->Users->find()->where(['Users.id'=>$toid])->contain(['Profiles'])->first();
			if($user){
				if($action == 'hearts'){
					$this->request->data['profile']['coins']= $user['profile']['coins'] + 3; 
					$action_txt = 'Hearts';
				}else if($action == 'dhearts'){
					$this->request->data['profile']['coins']= $user['profile']['coins'] + 10; 
					$action_txt = 'Double Hearts';
				}
				$user = $this->Users->patchEntity($user, $this->request->data);
				if($this->Users->save($user)){
						$From_userdata = $this->Users->find()->where(['Users.token'=>$token])->contain(['Profiles'])->first(); 
						$this->loadModel("Hearts");
						$Hearts = $this->Hearts->newEntity();
						$this->request->data['fromid'] = $From_userdata['id'];
						$this->request->data['action'] = $action;
						$this->request->data['created'] = date('Y-m-d H:i:s');
						$Hearts = $this->Hearts->patchEntity($Hearts, $this->request->data);
						if($this->Hearts->save($Hearts)){
							$result = array('status'=>'success','msg'=>'You successfully send '.$action_txt.'.');
						}else{
							$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
						}
						echo json_encode(array('result'=>$result));
						exit; 
				}else{
					echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
					exit;
				}
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
		}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}

	function whoheartsme(){
		if(!empty($this->request->data['offset']) && !empty($this->request->data['token']) && !empty($this->request->data['action']))
		{
			$userdata = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])
				->contain([
					'Profiles',
					'Hearts.Users' => [
						'fields' => ['Users.id']
					],
					'Hearts.Users.Profiles' => [
						'fields' => [
							'Profiles.id',
							'Profiles.user_id',
							'Profiles.display_name',
							'Profiles.age',
							'Profiles.photo'
						]
					],
					'Hearts' => function(\Cake\ORM\Query $q) {
						return $q->where(['Hearts.action' => $this->request->data['action']])->offset($this->request->data['offset'] - 20)->limit(20)->select(['Hearts.toid']);
				}])->select(['Users.id','Profiles.display_name','Profiles.age','Profiles.photo'])->hydrate(false)->toArray();
				
				 foreach($userdata as &$ud){
					unset($ud['id'],$ud['profile']);
					foreach($ud['hearts'] as &$hs){
						$PremiumMember = $this->Users->find()->where(['Users.id'=>$hs['user']['id']])->select(['is_premium_member'])->hydrate(false)->first();
						unset($hs['toid'],$hs['user']['id']);
						$Profile = $hs['user']['profile'];
						unset($hs['user']);
						$hs['profile'] = $Profile;
						$hs['profile']['is_premium_member'] = $PremiumMember['is_premium_member'];
					}
				}
				if($userdata){
						$result = array('status'=>'success','data'=>$userdata);
						echo json_encode(array('result'=>$result));
					exit;
				}else{
					$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
					echo json_encode(array('result'=>$result));
					exit;
				}
		}else{
			$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
			echo json_encode(array('result'=>$result));
			exit;
		}
	}

	function ViewUserProfile(){
		if(!empty($this->request->data['token']) && !empty($this->request->data['toid'])){
			$token = $this->request->data['token'];
			$toid = $this->request->data['toid'];		
			$From_user = $this->Users->find()->where(['Users.token'=>$token])->first(); 
			if($From_user){
				$this->loadModel("ViewProfiles");
				$res = $this->ViewProfiles->find()->where(['ViewProfiles.fromid '=> $From_user['id'],'ViewProfiles.toid '=>$toid])->count();		
				if($res > 0){
					echo json_encode(array('result'=>array('status'=>'error','msg'=>'You are already view user profile.')));
					exit;
				}else{
					unset($this->request->data['token']);
					$ViewProfiles = $this->ViewProfiles->newEntity();
					$this->request->data['fromid']= $From_user['id']; 
					$this->request->data['toid']= $toid; 
					$this->request->data['created'] = date('Y-m-d H:i:s');
					$ViewProfiles = $this->ViewProfiles->patchEntity($ViewProfiles, $this->request->data);
					$ResViewProfile = $this->ViewProfiles->save($ViewProfiles);
					echo json_encode(array('result'=>array('status'=>'success','msg'=>'you are successfully view user profile')));
					exit;
				}
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}

	function WhoViewMyProfile(){
		if(!empty($this->request->data['token']))
		{
			$userdata = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])
				->contain([
					'Profiles',
					'ViewProfiles.Users' => [
						'fields' => ['Users.id']
					],
					'ViewProfiles.Users.Profiles' => [
						'fields' => [
							'Profiles.id',
							'Profiles.user_id',
							'Profiles.display_name',
							'Profiles.age',
							'Profiles.photo'
						]
					],
					'ViewProfiles' => function(\Cake\ORM\Query $q) {
						return $q->offset($this->request->data['offset'] - 20)->limit(20)->select(['ViewProfiles.toid']);
				}])->select(['Users.id','Profiles.display_name','Profiles.age','Profiles.photo'])->hydrate(false)->toArray();

				 foreach($userdata as &$ud){
					unset($ud['id'],$ud['profile']);
					foreach($ud['view_profiles'] as &$hs){
						$PremiumMember = $this->Users->find()->where(['Users.id'=>$hs['user']['id']])->select(['is_premium_member'])->hydrate(false)->first();
						unset($hs['toid'],$hs['user']['id']);
						$Profile = $hs['user']['profile'];
						unset($hs['user']);
						$hs['profile'] = $Profile;
						$hs['profile']['is_premium_member'] = $PremiumMember['is_premium_member'];
					}
				}

				if($userdata){
					$result = array('status'=>'success','data'=>$userdata);
					echo json_encode(array('result'=>$result));
					exit;
				}else{
					$result = array('status'=>'error','msg'=>'There was an error,Please try agin.');
					echo json_encode(array('result'=>$result));
					exit;
				}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}

	function Search(){
		if(!empty($this->request->data['token']) && !empty($this->request->data)){
			$conditions = [];
			if(!empty($this->request->data['tag'])){
				$conditions['Profiles.tags LIKE'] = '%' . $this->request->data['tag'] . '%';
			}
			
			if(!empty($this->request->data['everyone_like_girls']) && !empty($this->request->data['everyone_like_guys'])){
				$conditions['Profiles.interested_in !='] = "";
			}elseif(!empty($this->request->data['everyone_like_girls'])){
				$conditions['Profiles.interested_in'] = "Female";
			}elseif(!empty($this->request->data['everyone_like_guys'])){
				$conditions['Profiles.interested_in'] = "Male";
			}
			
			if(!empty($this->request->data['girls_like_girls']) && !empty($this->request->data['girls_like_guys'])){
				$conditions['Profiles.gender'] = "Female";
				$conditions['Profiles.interested_in !='] = "";
			}elseif(!empty($this->request->data['girls_like_girls'])){
				$conditions['Profiles.gender'] = "Female";
				$conditions['Profiles.interested_in'] = "Female";
			}elseif(!empty($this->request->data['girls_like_guys'])){
				$conditions['Profiles.gender'] = "Female";
				$conditions['Profiles.interested_in'] = "Male";
			}
			
			if(!empty($this->request->data['guys_like_girls']) && !empty($this->request->data['guys_like_guys'])){
				$conditions['Profiles.gender'] = "Male";
				$conditions['Profiles.interested_in !='] = "";
			}elseif(!empty($this->request->data['guys_like_girls'])){
				$conditions['Profiles.gender'] = "Male";
				$conditions['Profiles.interested_in'] = "Female";
			}elseif(!empty($this->request->data['guys_like_guys'])){
				$conditions['Profiles.gender'] = "Male";
				$conditions['Profiles.interested_in'] = "Male";
			}
			
			if(!empty($this->request->data['age_range'])){
				$AgeRange = explode("-",$this->request->data['age_range']);
				$FromAge = $AgeRange[0];
				$ToAge = $AgeRange[1];
				$conditions['Profiles.ageFrom >='] = $FromAge;
				$conditions['Profiles.ageTo <='] = $ToAge;
			}
			
			if(!empty($this->request->data['snapchat'])){
				$conditions['Profiles.snapchat !='] = "";
			}
			if(!empty($this->request->data['instagram'])){
				$conditions['Profiles.instagram !='] = "";
			}
			
			if($conditions){
				$this->loadModel("Profiles");
				$userdata = $this->Profiles->find()
					->where([$conditions])
					->offset($this->request->data['offset'] - 20)
					->limit(20)
					->select(['Profiles.id','Profiles.user_id','Profiles.display_name','Profiles.age','Profiles.photo'])
					->hydrate(false)
					->toArray();
					
				foreach($userdata as &$ud){
					$PremiumMember = $this->Users->find()->where(['Users.id'=>$ud['id']])->select(['is_premium_member'])->hydrate(false)->first();
					$ud['is_premium_member'] = $PremiumMember['is_premium_member'];
				}
					
				$result = array('status'=>'success','data'=>$userdata);
				echo json_encode(array('result'=>$result));
				exit;
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'No record found.')));
				exit;
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}

	function PremiumMember(){
		if(!empty($this->request->data['token']) && !empty($this->request->data['success_code'])){
			$this->Users->updateAll
				(
					['success_code' => $this->request->data['success_code'], 'is_premium_member' => '1'], //fields
					['token' => $this->request->data['token']] //conditions
				);
			echo json_encode(array('result'=>array('status'=>'success','msg'=>"you are successfully added as premium member")));
			exit;
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}
	
	function AddCoins(){
		if(!empty($this->request->data['token']) && !empty($this->request->data['success_code']) && !empty($this->request->data['coins'])){
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->contain(['Profiles'])->select(['Users.id','Profiles.id','Profiles.user_id','Profiles.coins'])->hydrate(false)->first();
			if(!empty($user)){
				$ProfileId = $user['profile']['id'];
				$BuyCoin = $user['profile']['coins'] + $this->request->data['coins'];
				$this->loadModel('Profiles');
				$this->Profiles->updateAll
				(
					['coins' => $BuyCoin], //fields
					['id' => $ProfileId] //conditions
				);
				echo json_encode(array('result'=>array('status'=>'success','msg'=>"Coin added Successfully")));
				exit;
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
			
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}
	
	function ChatIn(){
		if(!empty($this->request->data['token']) && !empty($this->request->data['toid']) && !empty($this->request->data['message'])){
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->hydrate(false)->select(['id'])->first();
			unset($this->request->data['token']);
			if(!empty($user['id']))
			{
				$this->loadModel("Chats");
				$Chat = $this->Chats->newEntity();
				$this->request->data['fromid'] = $user['id'];
				$this->request->data['created_date'] = date("Y-m-d H:i:s");
				$Chat = $this->Chats->patchEntity($Chat, $this->request->data);
				$res = $this->Chats->save($Chat);
				if ($res){
					echo json_encode(array('result'=>array('status'=>'success','msg'=>"Message sent successfully.")));
					exit;
				}else{
					echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
					exit;
				}
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}

	function ChatOut(){
		if(!empty($this->request->data['token'])){
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->hydrate(false)->select(['id'])->first();
			unset($this->request->data['token']);
			if(!empty($user['id']))
			{
				$this->loadModel('Chats');
				$Messages = $this->Chats->find()
					->where(['Chats.fromid'=>$user['id']])
					->orWhere(['Chats.toid'=>$user['id']])
					->contain(['Sender','Receiver'])
					->select(['id','message','fromid','toid','Sender.first_name','Sender.last_name','Receiver.first_name','Receiver.last_name'])
					->hydrate(false)
					->toArray();
				$MyNew = array();
				foreach($Messages as $k=>$sm){
					$CheckVal = $sm['fromid'].'-'.$sm['toid'].'-'.$sm['toid'].'-'.$sm['fromid'];
					$CheckVal1 = $sm['toid'].'-'.$sm['fromid'].'-'.$sm['fromid'].'-'.$sm['toid'];
					if(!in_array($CheckVal, $MyNew) && !in_array($CheckVal1, $MyNew)){
						$MyNew[] = $sm['fromid'].'-'.$sm['toid'].'-'.$sm['toid'].'-'.$sm['fromid'];
					}
				}
				$AllMessages = array();
				foreach($MyNew as $kk=>$mn){
					$FromToId = explode('-',$mn);
					$FromToId1 = $FromToId[0];
					$FromToId2 = $FromToId[1];
					$FromToId3 = $FromToId[2];
					$FromToId4 = $FromToId[3];

					$ToFromUser = $this->Chats->find()
						->where(['Chats.fromid'=>$FromToId1,'Chats.fromid'=>$FromToId2])
						->orWhere(['Chats.toid'=>$FromToId3,'Chats.fromid'=>$FromToId4])
						->contain(['Sender','Receiver'])
						->select(['id','message','fromid','toid','created_date','Sender.first_name','Sender.last_name','Receiver.first_name','Receiver.last_name'])
						->order(['Chats.created_date ASC'])
						->hydrate(false)
						->toArray();
					
					$AllMessages[$kk] = $ToFromUser;
				}
				
				if(!empty($AllMessages)){
					echo json_encode(array('result'=>array('status'=>'success','data'=>$AllMessages)));
					exit;
				}else{
					echo json_encode(array('result'=>array('status'=>'error','msg'=>'No any message found.')));
					exit;
				}
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}

	function UserMsg(){
		//pertiquler Token and ToId wise msg
		if(!empty($this->request->data['token']) && !empty($this->request->data['toid'])){
			$user = $this->Users->find()->where(['Users.token'=>$this->request->data['token']])->hydrate(false)->select(['id'])->first();
			if(!empty($user)){
				$this->loadModel('Chats');
				$Messages = $this->Chats->find()->where(['Chats.fromid'=>$user['id'],'Chats.toid'=>$this->request->data['toid']])
					->orWhere(['Chats.fromid'=>$this->request->data['toid'],'Chats.toid'=>$user['id']])
					->contain(['Sender','Receiver'])
					->select(['id','message','fromid','toid','Sender.first_name','Sender.last_name','Receiver.first_name','Receiver.last_name'])
					->order(['Chats.created_date ASC'])
					->hydrate(false)
					->toArray();
				if(!empty($Messages)){
					echo json_encode(array('result'=>array('status'=>'success','data'=>$Messages)));
					exit;
				}else{
					echo json_encode(array('result'=>array('status'=>'error','msg'=>'No any message found.')));
					exit;
				}
			}else{
				echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
				exit;
			}
		}else{
			echo json_encode(array('result'=>array('status'=>'error','msg'=>'There was an error,Please try agin.')));
			exit;
		}
	}
}