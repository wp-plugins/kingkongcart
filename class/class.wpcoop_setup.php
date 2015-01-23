<?php

	class wpcoop_setup {

		function __construct(){
			add_action( 'admin_menu', array($this, 'wpc_add_menu') );
			add_action( 'admin_enqueue_scripts', array($this, 'wpc_admin_style') );
			add_action( 'wp_enqueue_scripts', array($this, 'wpc_style'));
			add_action( 'manage_users_columns', array($this, 'wpc_modify_user_columns') );
			add_action( 'manage_users_custom_column', array($this, 'wpc_user_role_changer'), 10, 3 );
		}

		function wpc_modify_user_columns($column_headers){
			unset($column_headers['role']);
			$column_headers['role-changer'] = '회원등급변경';
			return $column_headers;
		}

		function wpc_user_role_changer($value, $column_name, $user_id){

			global $wp_roles;
			$roles 		 = $wp_roles->get_names();
			$user_info = get_userdata($user_id);
			$user_role = implode(', ', $user_info->roles);

			foreach($roles as $role => $name){
				if($role == $user_role){
					$options .= "<option selected>".$role."</option>";
				} else {
					$options .= "<option>".$role."</option>";
				}
			}

			if ( 'role-changer' == $column_name ){
				$value = '<div class="wpc-user-role-changer" data="'.$user_id.'"><div style="float:left"><select class="user-role-select">'.$options.'</select></div> <div class="ajax-loader" style="float:left; position:relative; left:5px; top:5px; display:none"><img src=\''.WPCOOP_PLUGINS_URL.'/images/ajax-loader.gif\'></div></div>';
			}
			return $value;
		}

		public function wpc_add_menu(){
		    add_menu_page( 'wpcoop', __('Coop Members','wpcoop'), 'administrator', 'wpcoop_dashboard', 'wpcoop_dashboard', WPCOOP_PLUGINS_URL.'/images/icon-wpcoop.png', '31.4' ); 
		    add_submenu_page('wpcoop_dashboard', 'wpcoop', __('대시보드', 'wpcoop'), 'administrator', 'wpcoop_dashboard'); 
		}

		public function wpc_admin_style(){
		    wp_enqueue_media();
		    wp_enqueue_script('jquery-ui-datepicker');
		    wp_enqueue_script('jquery-ui-core');
		    wp_enqueue_script('jquery-ui-draggable');
		    wp_enqueue_script('jquery-ui-droppable');
		    wp_enqueue_style('wpcoop-admin-style', WPCOOP_PLUGINS_URL."/css/wpcoop-admin.css" );
		    wp_enqueue_script('iris', WPCOOP_PLUGINS_URL."/files/js/admin/iris.min.js", array('jquery'));
		    wp_enqueue_script('wpcoop-admin-js', WPCOOP_PLUGINS_URL."/js/wpcoop-admin.js", array('jquery'));			
		}

		public function wpc_style() {
		    wp_enqueue_script('jquery');
		    wp_enqueue_style('wpcoop-style', WPCOOP_PLUGINS_URL."/css/wpcoop.css");
		    wp_enqueue_script('wpcoop-js', WPCOOP_PLUGINS_URL."/js/wpcoop.js", array('jquery'));
		    wp_enqueue_script('wpcoop-postcode', 'http://dmaps.daum.net/map_js_init/postcode.js');
		    wp_localize_script('wpcoop-js', 'ajax_wpcoop', array( 'ajax_url' => admin_url('admin-ajax.php')));

		    $options = new wpcoop_login();

		    if($options->social_google == "T"){
		    	wp_enqueue_script('wpcoop-social-google', WPCOOP_PLUGINS_URL."/js/google.js");
		    	wp_enqueue_script('google-client', 'https://apis.google.com/js/client:platform.js');
		    }
		}

		public function wpc_set_options(){

			$old_login_options = get_option('wpc_login');
			$old_join_options  = get_option('wpc_woo_join_setup');

			if(!$old_login_options){
				$social_options 			= array(
					'login_enable' 			=> "F",
					'login_id'					=> '',
					'join_id'						=> '',
					'reset_pwd_id'			=> '',
					'modify_id'					=> '',
					'mbroke_id'					=> '',
					'login_redirection'	=> 'F',
					'admin_bar'					=> 'T',
					'find_pwd'					=> 'T',
					'join_member'				=> 'T',
					'social_priority' 	=> 'GP,FB,NV,KA,TW',
					'social_login'			=> array(
						'facebook'		=> 'F',
						'twitter'			=> 'F',
						'naver'				=> 'F',
						'kakao'				=> 'F',
						'google'			=> 'F'
					),
					'social_style'					=> 'list',
					'social_position'				=> 'top',
					'kakao_app_key'					=> '',
					'facebook_app_id'				=> '',
					'naver_client_id'				=> '',
					'naver_client_secret' 	=> '',
					'google_client_id'			=> ''
				);
				$social_options = serialize($social_options);
				update_option('wpc_login', $social_options);
			}

			if(!$old_join_options){
				$woo_join_setup 	= array(
					'billing_input'		=> 'T',
					'shipping_input'	=> 'T',
					'term_use_id'			=> null,
					'private_id'			=> null
				);

				$woo_join_setup = serialize($woo_join_setup);
				update_option('wpc_woo_join_setup', $woo_join_setup);
			}	
		}

	}

	new wpcoop_setup();


?>