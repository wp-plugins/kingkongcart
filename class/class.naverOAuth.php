<?php
	if(!isset($_SESSION)){
		session_start();		
	}
	define( 'NAVER_OAUTH_AUTHORIZE_URL', 'https://nid.naver.com/oauth2.0/authorize' );
	define( 'NAVER_OAUTH_TOKEN_URL', 'https://nid.naver.com/oauth2.0/token' );
	define( 'NAVER_GET_USERINFO_URL', 'https://apis.naver.com/nidlogin/nid/getUserProfile.xml');
	class wpcNaverOAuth {

		function __construct(){
			if(isset($_SESSION['naver_state'])){
				$this->state 		= $_SESSION['naver_state'];
				$validation 		= $this->state_validation();
				$this->validation 	= $validation;

				if($validation == true){
					$this->code = $_GET['code'];
					
					$wpc = new wpcoop_login();

					$this->accesstoken_url = "https://nid.naver.com/oauth2.0/token?client_id=".$wpc->naver_client_id."&client_secret=".$wpc->naver_client_secret."&grant_type=authorization_code&state=".$this->state."&code=".$_GET['code'];
					$this->call_accesstoken();
				}
			}
		}

		function state_validation(){

			if(isset($_GET['state'])){
				$session_state = $this->state;
				$original_state = $_GET['state'];

				if($original_state == $session_state){
					return true;
				} else {
					return $session_state;
				}
			}
		}

		public function call_accesstoken(){
			if(isset($this->accesstoken_url)){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $this->accesstoken_url );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt($ch, CURLOPT_COOKIE, '' );
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
				$g = curl_exec($ch);
				curl_close($ch);
				$data = json_decode($g, true);
				if(isset($data['token_type'])){
					$this -> tokenArr = array(
						 'Authorization : '.$data['token_type'].' '.$data['access_token']
					);
					$this->get_user_profile();
				}
			}
		}

		public function get_user_profile(){
			if(isset($this->tokenArr)){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, NAVER_GET_USERINFO_URL );
				curl_setopt($ch, CURLOPT_HTTPHEADER, $this -> tokenArr );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt($ch, CURLOPT_COOKIE, '' );
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
				$g = curl_exec($ch);
				curl_close($ch);
				$xml = simplexml_load_string($g);
				$uid = explode("@", (string)$xml -> response -> email );
				$this -> userInfo = array(
					'userID' => $uid[0],
					'nickname' => (string)$xml -> response -> nickname,
					'age' => (string)$xml -> response -> age,
					'birth' => (string)$xml -> response -> birthday,
					'gender' => (string)$xml -> response -> gender,
					'profImg' => (string)$xml -> response -> profile_image
				);

				$this->create_wpc_user();
			}
		}
		public function get_userInfo(){
			return $this -> userInfo;
		}
		public function get_userID(){
			if(isset($this->userInfo)){
				return $this -> userInfo['userID'];
			}
		}
		public function get_nickname(){
			if(isset($this->userInfo)){
				return $this -> userInfo['nickname'];
			}
		}
		public function get_age(){
			if(isset($this->userInfo)){
				return $this -> userInfo['age'];
			}
		}
		public function get_birth(){
			if(isset($this->userInfo)){
				return $this -> userInfo['birth'];
			}
		}
		public function get_gender(){
			if(isset($this->userInfo)){
				return $this -> userInfo['gender'];
			}
		}
		public function get_profImg(){
			if(isset($this->userInfo)){
				return $this -> userInfo['profImg'];
			}
		}

		public function create_wpc_user(){
			$save 		 = new wpcoop_login();
			$user_id 	 = $this->get_userID();
			$name 		 = $this->get_userID();
			$real_name 	 = $this->get_nickname();
			$social 	 = "naver";

			$save_status = $save->wpc_login_proc($user_id, $name, $real_name, $social);
			if($save_status === true){
				echo "<script>location.href='".home_url()."';</script>";
			}
		}
	}

if(isset($_GET['code']) && isset($_GET['state'])){
	$naver = new wpcNaverOAuth();
}
?>