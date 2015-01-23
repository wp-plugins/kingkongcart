<?php

	class wpcoop_login {

		function __construct(){
			$login_options 				= $this->wpc_get_login_options();
			$join_options					= $this->wpc_get_join_options();
			$popup_options 				= $this->wpc_get_popup_options();

			$login_enable					= $login_options['login_enable'];
			$login_id 						= $login_options['login_id'];
			$join_id 							= $login_options['join_id'];
			$modify_id 						= $login_options['modify_id'];
			$mbroke_id 						= $login_options['mbroke_id'];
			$reset_pwd_id 				= $login_options['reset_pwd_id'];
			$login_redirection 		= $login_options['login_redirection'];
			$admin_bar 						= $login_options['admin_bar'];
			$find_pwd 						= $login_options['find_pwd'];
			$join_member 					= $login_options['join_member'];
			$social_priority 			= $login_options['social_priority'];
			$social_facebook 			= $login_options['social_login']['facebook'];
			$social_twitter 			= $login_options['social_login']['twitter'];
			$social_naver 				= $login_options['social_login']['naver'];
			$social_kakao 				= $login_options['social_login']['kakao'];
			$social_google 				= $login_options['social_login']['google'];
			$social_style 				= $login_options['social_style'];
			$social_position			= $login_options['social_position'];
			$kakao_app_key 				= $login_options['kakao_app_key'];
			$facebook_app_id 			= $login_options['facebook_app_id'];
			$naver_client_id 			= $login_options['naver_client_id'];
			$naver_client_secret 	= $login_options['naver_client_secret'];
			$google_client_id 		= $login_options['google_client_id'];

			$woo_billing_input 		= $join_options['billing_input'];
			$woo_shipping_input 	= $join_options['shipping_input'];
			$term_use_id 					= $join_options['term_use_id'];
			$private_id 					= $join_options['private_id'];


			$this->login_enable 				= $login_enable;
			$this->login_id 						= $login_id;
			$this->join_id 							= $join_id;
			$this->modify_id 						= $modify_id;
			$this->mbroke_id 						= $mbroke_id;
			$this->reset_pwd_id 				= $reset_pwd_id;
			$this->login_redirection 		= $login_redirection;
			$this->admin_bar 						= $admin_bar;
			$this->find_pwd 						= $find_pwd;
			$this->join_member 					= $join_member;
			$this->social_facebook 			= $social_facebook;
			$this->social_twitter 			= $social_twitter;
			$this->social_naver 				= $social_naver;
			$this->social_kakao 				= $social_kakao;
			$this->social_google 				= $social_google;
			$this->social_priority 			= $social_priority;
			$this->social_style 				= $social_style;
			$this->social_position 			= $social_position;
			$this->kakao_app_key 				= $kakao_app_key;
			$this->facebook_app_id 			= $facebook_app_id;
			$this->naver_client_id 			= $naver_client_id;
			$this->naver_client_secret 	= $naver_client_secret;
			$this->google_client_id 		= $google_client_id;
			$this->woo_billing_input		= $woo_billing_input;
			$this->woo_shipping_input 	= $woo_shipping_input;
			$this->term_use_id 					= $term_use_id;
			$this->private_id 					= $private_id;
			$this->popup_options 				= $popup_options;

/**
	로그인 설정을 ON 한 경우 로그인 리다이렉션과 숏코드 인풋 필터를 적용한다.
*/
			if($login_enable == "T"){
				if($login_redirection == "T"){
					add_filter('login_redirect', array($this, 'wpc_login_page'),20, 3);
				}
				add_filter('the_content', array($this, 'wpc_page_content'),10,1 );
				if($social_kakao == "T"){
					add_action('wp_enqueue_scripts', array($this, 'wpc_kakao_script_init')); 
				}
			}

/**
	어드민 바 감추기를 ON 한 경우 액션 훅을 실행한다.
*/
			if($admin_bar == "T"){
				add_action('set_current_user', array($this, 'wpc_hide_admin_bar'));
			}
		}


/**
	팝업 설정 옵션정보
*/

		public function wpc_get_popup_options(){

			$options = get_option("wpc_popup_options", false);
			
			if($options){
				$options = unserialize($options);
			} else {
				$options = '';
			}

			return $options;
		}

/**
	로그인 설정 옵션정보
*/
		public function wpc_get_login_options(){
			$options = get_option("wpc_login");
			if($options){
				$options = unserialize($options);
			}
			return $options;
		}

/**
	회원가입 설정 옵션정보
*/
		public function wpc_get_join_options(){
			$options = get_option("wpc_woo_join_setup");
			if($options){
				$options = unserialize($options);
			}
			return $options;
		}

/**
	로그인 페이지 리다이렉션
*/
		public function wpc_login_page($redirect_to, $url_redirect_to = '', $user = null) {

		    if($user->ID){
		      wp_redirect(site_url());
		      exit();

		    } else {
		      wp_redirect(get_the_permalink($this->login_id), 301);
		      exit();
		    }
		}

/**
	관리자가 아닐 시 상단 어드민 바 숨김
*/
		public function wpc_hide_admin_bar() { 
		    if (!current_user_can('manage_options')) { 
		        show_admin_bar(false); 
		    } 
		}

/**
	현재 페이지일 경우 파트별 숏코드 삽입
*/
		public function wpc_page_content($content){
			global $post;
			if($post->ID == $this->login_id){
				$content = "[wpc_login]";
			}
			if($post->ID == $this->join_id){
				$content = "[wpc_join]";
			}
			if($post->ID == $this->modify_id){
				$content = "[wpc_modify]";
			}
			if($post->ID == $this->mbroke_id){
				$content = "[wpc_mbroke]";
			}
			if($post->ID == $this->reset_pwd_id){
				$content = "[wpc_reset_pwd]";
			}
			return $content;
		}

		public function wpc_kakao_script_init(){
			wp_enqueue_script('wpcoop-external-kakao', 'https://developers.kakao.com/sdk/js/kakao.min.js');
		}

		public function wpc_login_proc($id, $name, $real_name, $social){
			require_once(ABSPATH . WPINC .'/pluggable.php');
			$site_url 	= site_url();
			$site_url	= str_replace("http://", "", $site_url);
			$site_url 	= str_replace("https://", "", $site_url);
			$site_url 	= str_replace("www.", "", $site_url);

			switch($social){
				case "kakao" :
					$md5_id 	= md5($id);
					$md5_name 	= md5($name);
					$user_login = "kakao_user_".$md5_id;
					$user_pwd 	= "kakao_".$md5_name;
					$user_email = "kakao_user_".$md5_id."@".$site_url;
					$user_name 	= $name;
				break;

				case "facebook" :
					$md5_name 	= md5($real_name);
					$user_login = "facebook_user_".$id;
					$user_pwd 	= "facebook_".$md5_name;
					$user_email = "facebook_user_".$id."@".$site_url;
					$user_name 	= $real_name;
				break;

				case "naver" :
					$md5_name 	= md5($real_name);
					$user_login = "naver_user_".$id;
					$user_pwd 	= "naver_".$md5_name;
					$user_email = "naver_user_".$id."@".$site_url;
					$user_name 	= $real_name;
				break;

				case "google" :
					$md5_name	= md5($real_name);
					$user_login = "google_user_".$id;
					$user_pwd 	= "google_".$md5_name;
					$user_email = "google_user_".$id."@".$site_url;
					$user_name 	= $real_name;
				break;

				case "twitter" :
					$md5_name 	= md5($real_name);
					$user_login = "twitter_user_".$id;
					$user_pwd 	= "twitter_".$md5_name;
					$user_email = "twitter_user_".$id."@".$site_url;
					$user_name 	= $real_name;
				break;
			}



			if( username_exists($user_login) ){
				$get_id = username_exists($user_login);
				$user = get_user_by( 'id', $get_id );
				$get_user_login = $user->user_login;
				$get_user_email = $user->user_email;

				if( ($get_user_login == $user_login) && ($get_user_email == $user_email) ){
					// 기존 로그인 사용자
					$auto_login = $this->wpc_auto_login($get_id, $user_login);

					if($auto_login){
						return true;
					}
				} else {
					// 유저아이디는 같은데 비밀번호가 일치하지 않는... 예외.. 그럴수가...
				}
			} else {

				$status = wp_create_user($user_login, $user_pwd, $user_email);

				if (!$status || is_wp_error($status)){
					return false;
				} else {

				$userinfo = array(
					'ID' => $status,
					'display_name' => $user_name
				);

				wp_update_user($userinfo);

				$auto_login = $this->wpc_auto_login($status, $user_login);

					if($auto_login){
						return true;
					}
				}
			}
		}

		public function wpc_auto_login($user_id, $user_name){

			if(!is_user_logged_in()){
				wp_set_current_user($user_id, $user_name);
				wp_set_auth_cookie($user_id);
				return true;
			} else {
				return false;
			}
		}


	}

	new wpcoop_login();

?>