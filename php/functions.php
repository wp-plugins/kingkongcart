<?php

add_action( 'wp_ajax_save_wpc', 'wpc_save' );

function wpc_save(){

	parse_str($_POST['options'], $options);

	$login_id 						= $options['login_id'];							// 로그인 페이지 ID
	$join_id 							= $options['join_id'];							// 회원가입 페이지 ID
	$modify_id 						= $options['modify_id'];						// 회원정부 수정 페이지 ID
	$mbroke_id 						= $options['mbroke_id'];						// 회원탈퇴 페이지 ID
	$reset_pwd_id 				= $options['reset_pwd_id'];					// 비밀번호 초기화 페이지 ID
	$login_redirection 		= $options['login_redirection'];		// 로그인 페이지 리다이렉션
	$login_enable 				= $options['login_enable'];					// 로그인 사용 여부
	$admin_bar 						= $options['admin_bar'];						// 어드민 바 감추기
	$find_pwd 						= $options['find_pwd'];							// 비밀번호 찾기
	$join_member 					= $options['join_member'];					// 회원가입
	$social_facebook 			= $options['social_facebook'];			// 소셜 페이스북 로그인
	$social_twitter 			= $options['social_twitter'];				// 소셜 트위터 로그인
	$social_naver 				= $options['social_naver'];					// 소셜 네이버 로그인
	$social_kakao 				= $options['social_kakao']; 				// 소셜 카카오 로그인
	$social_google 				= $options['social_google'];				// 소셜 구글플러스 로그인
	$social_priority 			= $options['social_priority'];			// 소셜로그인 노출 우선순위
	$kakao_app_key 				= $options['kakao_app_key'];				// 카카오로그인 APP KEY
	$facebook_app_id 			= $options['facebook_app_id'];			// 페이스북 앱 아이디
	$naver_client_id 			= $options['naver_client_id'];			// 네이버 클라이언트 아이디
	$naver_client_secret 	= $options['naver_client_secret'];	// 네이버 클라이언트 시크릿
	$google_client_id 		= $options['google_client_id'];			// 구글 클라이언트 아이디
	$social_style 				= $options['social_style'];					// 소셜 버튼 스타일 (list or icon)
	$social_position 			= $options['social_position'];			// 소셜 버튼 위치

/**
	## 회원가입 관련 지정 변수들 ##
*/
	$join_method_priority			= $options['join_method_priority'];
	$join_method_array 				= explode(",", $join_method_priority);
	$join_method_length 			= count( $join_method_array );
	$join_methods 						= "";
	$check_required_name 			= "";
	$check_required_tel				= "";
	$check_required_phone 		= "";
	$check_required_address 	= "";
	$check_required_private 	= "";
	$check_required_term_use 	= "";

	$woo_billing_input 				= $options['woo_billing_input'];
	$woo_shipping_input 			= $options['woo_shipping_input'];

	if(isset($options['wpc_term_use_content'])){
		$wpc_term_use_content 	= $options['wpc_term_use_content'];
	} else {
		$wpc_term_use_content 	= "";
	}
	if(isset($options['wpc_private_content'])){
		$wpc_private_content 		= $options['wpc_private_content'];
	} else {
		$wpc_private_content 		= "";
	}

	$woo_join_setup 					= array();
	$woo_join_setup 					= array(
		'billing_input'					=> $woo_billing_input,
		'shipping_input'				=> $woo_shipping_input,
		'term_use_id'						=> $wpc_term_use_content,
		'private_id'						=> $wpc_private_content
	);

	$woo_join_setup 					= serialize($woo_join_setup);
	update_option("wpc_woo_join_setup", $woo_join_setup);

	$thankyou_page_content 		= $options['wpc_thankyou_page'];
	update_option("wpc_thankyou_page", $thankyou_page_content);

	if(isset($options['check_required_name'])){
		$check_required_name = "required";
	}

	if(isset($options['check_required_tel'])){
		$check_required_tel = "required";
	}

	if(isset($options['check_required_phone'])){
		$check_required_phone = "required";
	}

	if(isset($options['check_required_address'])){
		$check_required_address = "required";
	}

	if(isset($options['check_required_private'])){
		$check_required_private = "required";
	}

	if(isset($options['check_required_term_use'])){
		$check_required_term_use = "required";
	}


	for ($i=0; $i < $join_method_length; $i++) { 
		switch($join_method_array[$i]){

			case "id" :
				$join_methods['id'] = array(
					'label' 		=> $options['wpc_label_id'],
					'value'			=> 'id',
					'size' 			=> $options['wpc_size_id'],
					'required' 	=> true,
					'mustuse'		=> true,
					'using'			=> true
				);
			break;

			case "password" :
				$join_methods['password'] = array(
					'label'			=> $options['wpc_label_password'],
					'value'			=> 'password',
					'size'			=> $options['wpc_size_password'],
					'required'	=> true,
					'mustuse'		=> true,
					'using'			=> true
				);
			break;

			case "name" :
				$join_methods['name'] = array(
					'label'			=> $options['wpc_label_name'],
					'value'			=> 'name',
					'size'			=> $options['wpc_size_name'],
					'required'	=> $check_required_name,
					'mustuse'		=> false,
					'using'			=> true
				);
			break;

			case "email" :
				$join_methods['email'] = array(
					'label'			=> $options['wpc_label_email'],
					'value'			=> 'email',
					'size'			=> $options['wpc_size_email'],
					'required'	=> true,
					'mustuse'		=> true,
					'using'			=> true
				);
			break;

			case "tel" :
				$join_methods['tel']	= array(
					'label'			=> $options['wpc_label_tel'],
					'value'			=> 'tel',
					'size'			=> $options['wpc_size_tel'],
					'required'	=> $check_required_tel,
					'mustuse'		=> false,
					'using'			=> true
				);
			break;

			case "phone" :
				$join_methods['phone']	= array(
					'label'			=> $options['wpc_label_phone'],
					'value'			=> 'phone',
					'size'			=> $options['wpc_size_phone'],
					'required'	=> $check_required_phone,
					'mustuse'		=> false,
					'using'			=> true
				);
			break;

			case "address" :
				$join_methods['address']	= array(
					'label'			=> $options['wpc_label_address'],
					'value'			=> 'address',
					'size'			=> $options['wpc_size_address'],
					'required'	=> $check_required_address,
					'mustuse'		=> false,
					'using'			=> true
				);
			break;

			case "term_use" :
				$join_methods['term_use']	= array(
					'label'			=> $options['wpc_label_term_use'],
					'value'			=> 'term_use',
					'size'			=> $options['wpc_size_term_use'],
					'required'	=> $check_required_term_use,
					'mustuse'		=> false,
					'using'			=> true
				);
			break;

			case "private" :
				$join_methods['private']	= array(
					'label'			=> $options['wpc_label_private'],
					'value'			=> 'private',
					'size'			=> $options['wpc_size_private'],
					'required'	=> $check_required_private,
					'mustuse'		=> false,
					'using'			=> true
				);
			break;
		}
	}

	$join_methods = serialize($join_methods);

	$join_insert  = update_option("wpc_join_methods", $join_methods);

	$social_options 					= array(
		'login_enable' 					=> $login_enable,
		'login_id'							=> $login_id,
		'join_id'								=> $join_id,
		'modify_id'							=> $modify_id,
		'mbroke_id'							=> $mbroke_id,
		'reset_pwd_id'					=> $reset_pwd_id,
		'login_redirection'			=> $login_redirection,
		'admin_bar'							=> $admin_bar,
		'find_pwd'							=> $find_pwd,
		'join_member'						=> $join_member,
		'social_priority' 			=> $social_priority,
		'social_login'					=> array(
			'facebook'						=> $social_facebook,
			'twitter'							=> $social_twitter,
			'naver'								=> $social_naver,
			'kakao'								=> $social_kakao,
			'google'							=> $social_google
		),
		'social_style'					=> $social_style,
		'social_position'				=> $social_position,
		'kakao_app_key'					=> $kakao_app_key,
		'facebook_app_id'				=> $facebook_app_id,
		'naver_client_id'				=> $naver_client_id,
		'naver_client_secret' 	=> $naver_client_secret,
		'google_client_id'			=> $google_client_id
	);

	$social_options = serialize($social_options);

	$status = update_option('wpc_login', $social_options);

	if(is_wp_error($status) or is_wp_error($join_insert)){
		echo "error";
	} else {
		echo "success";
	}

	exit();

}

add_action( 'wp_ajax_wpc_user_role_changer', 'wpc_user_role_changer' );

function wpc_user_role_changer(){
	$new_role = $_POST['role'];
	$user_id = $_POST['user_id'];
	
	global $wp_roles;

	$user 				 	= get_userdata($user_id);

	if( !empty($user->roles) && is_array( $user->roles) ){
		foreach( $user->roles as $role ){
			$user->remove_role($role);
		}
	}

	$user->add_role( $new_role );

	$result['status'] = 'success';

  header( "Content-Type: application/json" );
  echo json_encode($result);

	exit();
}

add_action( 'wp_ajax_wpc_custom_social_login', 'wpc_custom_social_login' );
add_action( 'wp_ajax_nopriv_wpc_custom_social_login', 'wpc_custom_social_login' );

function wpc_custom_social_login(){
	$id 				= "";
	$name 			= "";
	$real_name 	= "";
	$social 		= "";

	$id 				= $_POST['id'];
	$name				= $_POST['name'];
	$real_name 	= $_POST['real_name'];
	$social 		= $_POST['social'];

	$name				= str_replace("\"", "", $name);
	$name 			= str_replace("\\", "", $name);

	$wpc_login 	= new wpcoop_login();
	$insert 		= $wpc_login->wpc_login_proc($id, $name, $real_name, $social);

	if($insert){
		echo "success";
	} else {
		echo "failed";
	}

	exit();
}

add_action( 'wp_ajax_wpc_twitter_oauth_connect', 'wpc_twitter_oauth_connect' );
add_action( 'wp_ajax_nopriv_wpc_twitter_oauth_connect', 'wpc_twitter_oauth_connect' );

function wpc_twitter_oauth_connect(){

	require_once(WPCOOP_ABSPATH.'/class/twitteroauth.php');

	$options = new wpcoop_login();
	$options->define_twitter_config();

	if(!isset($_SESSION)){
		session_start();		
	}

	/* Build TwitterOAuth object with client credentials. */
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	 
	/* Get temporary credentials. */
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

	/* Save temporary credentials to session. */
	$_SESSION['oauth_token'] 				= $token = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	 
	/* If last connection failed don't display authorization link. */
	switch ($connection->http_code) {
	  case 200:
	    /* Build authorize URL and redirect user to Twitter. */
	    //$url = $connection->getAuthorizeURL($token);
	    $url = $connection->getAuthorizeURL($token);
	    echo $url;
	    break;
	  default:
	    echo "false";
	}

	exit();

}



add_action( 'wp_ajax_wpc_auto_create_page', 'wpc_auto_create_page' );

function wpc_auto_create_page(){

	global $wpdb; // Not sure if you need this, maybe

	$wpc_login_page = array(
		'post_title'    => '로그인',
		'post_content'  => '',
		'post_status'   => 'publish',
		'post_type'     => 'page',
		'post_author'   => 1
	);

	$page_exists = get_page_by_title( $wpc_login_page['post_title'] );

	if( $page_exists == null ){
		$insert = wp_insert_post( $wpc_login_page );
		if($insert){
		  $page_ids['login_page'] = array(
		  	'id' 			=> $insert,
		  	'status'	=> 'created'
		  );
		}
	} else {
			$page_ids['login_page']	= array(
				'id'			=> $page_exists->ID,
				'status'	=> 'exists'
			);
	}

	$wpc_join_page = array(
		'post_title'    => '회원가입',
		'post_content'  => '',
		'post_status'   => 'publish',
		'post_type'     => 'page',
		'post_author'   => 1
	);

	$page_exists = get_page_by_title( $wpc_join_page['post_title'] );

	if( $page_exists == null ){
		$insert = wp_insert_post( $wpc_join_page );
		if($insert){
		  $page_ids['join_page'] = array(
		  	'id' 			=> $insert,
		  	'status'	=> 'created'
		  );
		}
	} else {
			$page_ids['join_page'] = array(
				'id'			=> $page_exists->ID,
				'status'	=> 'exists'
			);
	}

	$wpc_reset_page = array(
		'post_title'    => '비밀번호 초기화',
		'post_content'  => '',
		'post_status'   => 'publish',
		'post_type'     => 'page',
		'post_author'   => 1
	);

	$page_exists = get_page_by_title( $wpc_reset_page['post_title'] );

	if( $page_exists == null ){
		$insert = wp_insert_post( $wpc_reset_page );
		if($insert){
		  $page_ids['reset_page'] = array(
		  	'id' 			=> $insert,
		  	'status'	=> 'created'
		  );
		}
	} else {
			$page_ids['reset_page']	= array(
				'id'			=> $page_exists->ID,
				'status'	=> 'exists'
			);
	}


	$wpc_modify_page = array(
		'post_title'    => '회원정보수정',
		'post_content'  => '',
		'post_status'   => 'publish',
		'post_type'     => 'page',
		'post_author'   => 1
	);

	$page_exists = get_page_by_title( $wpc_modify_page['post_title'] );

	if( $page_exists == null ){
		$insert = wp_insert_post( $wpc_modify_page );
		if($insert){
		  $page_ids['modify_page'] = array(
		  	'id' 			=> $insert,
		  	'status'	=> 'created'
		  );
		}
	} else {
			$page_ids['modify_page']	= array(
				'id'			=> $page_exists->ID,
				'status'	=> 'exists'
			);
	}

	$wpc_mbroke_page = array(
		'post_title'    => '회원탈퇴',
		'post_content'  => '',
		'post_status'   => 'publish',
		'post_type'     => 'page',
		'post_author'   => 1
	);

	$page_exists = get_page_by_title( $wpc_mbroke_page['post_title'] );

	if( $page_exists == null ){
		$insert = wp_insert_post( $wpc_mbroke_page );
		if($insert){
		  $page_ids['mbroke_page'] = array(
		  	'id' 			=> $insert,
		  	'status'	=> 'created'
		  );
		}
	} else {
			$page_ids['mbroke_page']	= array(
				'id'			=> $page_exists->ID,
				'status'	=> 'exists'
			);
	}

	header( "Content-Type: application/json" );
	echo json_encode($page_ids);

	exit();
}

add_action( 'wp_ajax_wpc_required_method_check', 'wpc_required_method_check' );
add_action( 'wp_ajax_nopriv_wpc_required_method_check', 'wpc_required_method_check' );

function wpc_required_method_check(){
	$options = array();
	parse_str($_POST['data'], $options);
	$joinForm 				= new wpcoop_joinForm();
	$required_methods = $joinForm->required_method();
	$error_message		= array();
	foreach($required_methods as $method){
		switch($method){
			case "password" :
				if(empty($options['wpc_input_password'])){
					$error_message['password']['msg'] = __("[".$joinForm->get_label(null, 'password')."] 은 필수로 기입하셔야 합니다.", 'wpcoop');
				} else {
					if($options['wpc_input_password'] != $options['wpc_input_re_pwd']){
						$error_message['password']['msg'] = __($joinForm->get_label(null, 'password').",".$joinForm->get_label(null, 'password')." 동일 하지 않습니다.");
					}
				}
			break;

			case "email" :
				if(empty($options['wpc_input_email'])){
					$error_message['email']['msg'] = __("[".$joinForm->get_label(null, 'email')."] 은 필수로 기입하셔야 합니다.", 'wpcoop');
				} else {
					if(strpos($options['wpc_input_email'], "@") === false){
						$error_message['email']['msg'] = __('올바른 이메일 형식이 아닙니다.', 'wpcoop');
					}
				}
			break;

			case "phone" :
				if(empty($options['wpc_input_phone'])){
					$error_message['phone']['msg'] = __("[".$joinForm->get_label(null, 'phone')."] 은 필수로 기입하셔야 합니다.", 'wpcoop');
				} else {
					$phone = $options['wpc_input_phone'];
					$phone = str_replace("-", "", $phone);
					if(!preg_match("/^([0-9]*){3,15}$/", $phone)){
						$error_message['phone']['msg'] = __('숫자만 기입하시기 바랍니다.(하이픈(-)는 사용가능합니다.)', 'wpcoop');
					}					
				}
			break;

			case "tel" :
				if(empty($options['wpc_input_tel'])){
					$error_message['tel']['msg'] = __("[".$joinForm->get_label(null, 'tel')."] 은 필수로 기입하셔야 합니다.", 'wpcoop');
				} else {
					$tel = $options['wpc_input_tel'];
					$tel = str_replace("-", "", $tel);
					if(!preg_match("/^([0-9]*){3,15}$/", $tel)){
						$error_message['tel']['msg'] = __('숫자만 기입하시기 바랍니다.(하이픈(-)는 사용가능합니다.)', 'wpcoop');
					}					
				}
			break;

			case "term_use" :
				if(empty($options['wpc_checkbox_term_use'])){
					$error_message['term_use']['msg'] = __("[".$joinForm->get_label(null, 'term_use')."] 동의하셔야 가입이 가능 합니다.", 'wpcoop');
				}
			break;

			case "private" :
				if(empty($options['wpc_checkbox_private'])){
					$error_message['private']['msg'] = __("[".$joinForm->get_label(null, 'private')."] 동의하셔야 가입이 가능 합니다.", 'wpcoop');
				}
			break;

			case "address" :
				if(empty($options['wpc_input_post_code'])){
					$error_message['post_code']['msg'] = __("우편번호는 필수로 기입하셔야 합니다.", 'wpcoop');
				}
				if(empty($options['wpc_input_basic_address'])){
					$error_message['address']['msg'] = __('기본 주소는 필수로 기입하셔야 합니다.', 'wpcoop');
				}
			break;

			default :
				if(empty($options['wpc_input_'.$method])){
						$error_message[$method]['msg'] = __("[".$joinForm->get_label(null, $method)."] 은 필수로 기입하셔야 합니다.", 'wpcoop');
				}
			break;

		}
	}

	if($options['username_exists'] == 0){
		$error_message['username_exists']['msg'] = __("기입하신 아이디는 사용중입니다.", "wpcoop");
	}

	if($options['email_exists'] == 0){
		$error_message['email_exists']['msg'] = __("가입하신 이메일은 사용중입니다.", "wpcoop");
	}

	if($options['pwd_success'] == 0){
		$error_message['pwc_success']['msg'] = __("비밀번호와 비밀번호 확인이 일치하지 않습니다.", "wpcoop");
	}
	
	header( "Content-Type: application/json" );
	echo json_encode($error_message);
	//echo print_r($error_message);
	exit();
}

add_action( 'wp_ajax_wpc_check_user_name', 'wpc_check_user_name' );
add_action( 'wp_ajax_nopriv_wpc_check_user_name', 'wpc_check_user_name' );

function wpc_check_user_name(){

	$user_name = $_POST['user_name'];

	if ( username_exists($user_name) ){
		echo "exist";
	} else {
		echo "canuse";
	}

	exit();
}

add_action( 'wp_ajax_wpc_check_email', 'wpc_check_email' );
add_action( 'wp_ajax_nopriv_wpc_check_email', 'wpc_check_email' );

function wpc_check_email(){

	$user_email = $_POST['user_email'];

	if ( email_exists($user_email) ){
		echo "exist";
	} else {
		echo "canuse";
	}

	exit();
}

add_action( 'wp_ajax_wpc_get_all_page', 'wpc_get_all_page' );

function wpc_get_all_page(){

	global $wpdb;
	$post_table = $wpdb->prefix."posts";
	$results 		= $wpdb->get_results("SELECT ID, post_title from $post_table WHERE post_status = 'publish' and post_type = 'page'");
	$all_page 	= array();
	$cnt = 0;
	foreach($results as $result){
		$all_page[$cnt]['id'] = $result->ID;
		$all_page[$cnt]['title'] = $result->post_title;
		$cnt++;
	}

	header( "Content-Type: application/json" );
	echo json_encode($all_page);

	exit();
}



























?>