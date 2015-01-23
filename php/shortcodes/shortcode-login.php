<?php
if(!isset($_SESSION)){
	session_start();		
}

add_shortcode("wpc_login","wpc_login");

function generate_state() {
        $mt = microtime();
        $rand = mt_rand();
        return md5($mt . $rand); 
}

function wpc_login($attr){
	if(is_user_logged_in()){
		echo "<script>alert('".__('이미 로그인 되어 있습니다. 메인화면으로 이동합니다.','wpcoop')."'); location.href='".home_url()."';</script>";
	}
	$login_content_social = "";
	$options 		= new wpcoop_login();
	$priority 		= $options->social_priority;
	$priority 		= explode(",", $priority);
	$login_enable 	= $options->login_enable;

	if($options->social_twitter == "T"){
		require_once(WPCOOP_ABSPATH.'/class/twitteroauth.php');
		$options->define_twitter_config();

		if( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ){

			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$connection = new wpcTwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			/* Request access tokens from twitter */
			$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
			/* Save the access tokens. Normally these would be saved in a database for future use. */
			$_SESSION['access_token'] = $access_token;

		}



		/* If access tokens are not available redirect to connect page. */
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			/* Build an image link to start the redirect process. */
			$twitter_connect = '<button type="button" class="btn wpc-s-btn wpc-btn-twitter" onclick="twitter_oauth();"><ul><li class="wpc-i wpc-i-twitter"></li><li class="wpc-i-text">트위터 아이디로 로그인</li></ul></button>';
		} else {

			if( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ){

				/* Get user access tokens out of the session. */
				$access_token = $_SESSION['access_token'];
				/* Create a TwitterOauth object with consumer/user tokens. */
				$connection = new wpcTwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
				/* If method is set change API call made. Test is called by default. */
				$content = $connection->get('account/verify_credentials');
				
				$twt_user_id 	= $content->id;
				$twt_name 		= $content->name;

				echo "<script>wpc_social_login('".$twt_user_id."', '".$twt_name."', '".$twt_name."', 'twitter');</script>";
			}
			
			//echo "<script>location.href='".home_url()."';</script>";

			$twitter_connect = '<button type="button" class="btn wpc-s-btn wpc-btn-twitter" onclick="twitter_oauth();"><ul><li class="wpc-i wpc-i-twitter"></li><li class="wpc-i-text">트위터 아이디로 로그인</li></ul></button>';

		}
	}
	$display = '';
	if( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ){
		$display = 'style="display:block"';
	} else {
		$display = "";
	}
	$login_content  = '<div id="wpc_loading" '.$display.'><div class="loading_text"><div><img src="'.WPCOOP_PLUGINS_URL.'/images/loading.GIF" style="margin-bottom:5px"><br>Now processing...<br />Please wait for a while.</div></div><div class="loading_background"></div></div>';
	$login_content .= '<form name="wpc_loginform" id="loginform" action="'.wp_login_url().'" method="post">';
	$login_content .= '<div id="wpc_login">';
	$login_content .= '<ul style="list-style:none; padding:0px">';
 
	if($login_enable == "T"){

		if($options->social_style == "list"){
			if($options->social_facebook == "T" or $options->social_kakao == "T" or $options->social_naver == "T" or $options->social_google == "T" or $options->social_twitter == "T"){
				$login_content_social .= "<li>하단의 계정 아이디로도 로그인 하실 수 있습니다.</li>";
				$login_content_social .= '<li style="border-top:1px dashed #e0e0e0"></li>';
			}
			
			for ($i=0; $i < count($priority); $i++) { 
				switch($priority[$i]){
					case "FB" :
						if($options->social_facebook == "T" && $options->facebook_app_id != ""){
							$login_content_social .= '<li><div id="fb-root"></div><button type="button" class="btn wpc-s-btn wpc-btn-facebook" onclick="fb_login();"><ul><li class="wpc-i wpc-i-facebook"></li><li class="wpc-i-text">페이스북 아이디로 로그인</li></ul></button></li>';
						}
					break;

					case "TW" :
						if($options->social_twitter == "T"){
							$login_content_social .= '<li><button type="button" class="btn wpc-s-btn wpc-btn-twitter" onclick="twitter_oauth();"><ul><li class="wpc-i wpc-i-twitter"></li><li class="wpc-i-text">트위터 아이디로 로그인</li></ul></button></li>';
						}
					break;

					case "NV" :

						$state = generate_state();
						$_SESSION['naver_state'] = $state;
						
						if($options->social_naver == "T" && $options->naver_client_id != "" && $options->naver_client_secret != ""){

							$login_content_social .= '<li><button type="button" target="_blank" class="btn wpc-s-btn wpc-btn-naver" onclick="location.href=\'https://nid.naver.com/oauth2.0/authorize?client_id='.$options->naver_client_id.'&response_type=code&redirect_uri='.site_url().'&state='.$state.'\';"><ul><li class="wpc-i wpc-i-naver"></li><li class="wpc-i-text">네이버 아이디로 로그인</li></ul></button></li>';
						}
					break;

					case "KA" :
						if($options->social_kakao == "T" && $options->kakao_app_key != ""){
							$login_content_social .= '<li><button type="button" id="custom-login-btn" class="btn wpc-s-btn wpc-btn-kakao" onclick="loginWithKakao()"><ul><li class="wpc-i wpc-i-kakao"></li><li class="wpc-i-text">카카오 아이디로 로그인</li></ul></button></li>';
						}
					break;

					case "GP" :
						if($options->social_google == "T" && $options->google_client_id != ""){
							$login_content_social .= '<li onclick="google_plus_load();"><div id="wpcSignIn" class="customGPlusSignIn">
    <span class="icon"></span>
    <span class="buttonText"><button type="button" id="custom-login-btn" class="btn wpc-s-btn wpc-btn-google"><ul><li class="wpc-i wpc-i-google"></li><li class="wpc-i-text">Google+ 아이디로 로그인</li></ul></button></span>
  </div></li>';
						}

				}
			}
			if($options->social_facebook == "T" or $options->social_kakao == "T" or $options->social_naver == "T" or $options->social_google == "T" or $options->social_twitter == "T"){
				$login_content_social .= '<li style="border-top:1px dashed #e0e0e0"></li>';
			}
		}

		if($options->social_style == "icon"){
			if($options->social_facebook == "T" or $options->social_kakao == "T" or $options->social_naver == "T" or $options->social_google == "T" or $options->social_twitter == "T"){
				$login_content_social .= "<li>하단의 계정 아이디로도 로그인 하실 수 있습니다.</li>";
				$icon_dashed_line  = "padding:10px 0px; min-height:61px; border-top:1px dashed #e0e0e0; border-bottom:1px dashed #e0e0e0";
			}

			$login_content_social .= "<li style='".$icon_dashed_line."'><ul style='list-style:none; padding:0px;'>";

			for ($i=0; $i < count($priority); $i++) { 
				switch($priority[$i]){
					case "FB" :
						if($options->social_facebook == "T" && $options->facebook_app_id != ""){
							$login_content_social .= '<div id="fb-root"></div><li class="wpc-icon wpc-icon-btn-facebook" onclick="fb_login();"><div class="wpc-icon-facebook"></div></li>';
						}
					break;

					case "TW" :
						if($options->social_twitter == "T"){
							$login_content_social .= "<li class='wpc-icon wpc-icon-btn-twitter'><div class='wpc-icon-twitter'></div></li>";
						}
					break;

					case "NV" :
						if($options->social_naver == "T" && $options->naver_client_id != "" && $options->naver_client_secret != ""){
							$state = generate_state();
							$_SESSION['naver_state'] = $state;
							$login_content_social .= '<li class="wpc-icon wpc-icon-btn-naver" onclick="location.href=\'https://nid.naver.com/oauth2.0/authorize?client_id='.$options->naver_client_id.'&response_type=code&redirect_uri='.site_url().'&state='.$state.'\';"><div class="wpc-icon-naver"></div></li>';
						}
					break;

					case "KA" :
						if($options->social_kakao == "T" && $options->kakao_app_key != ""){
							$login_content_social .= "<li class='wpc-icon wpc-icon-btn-kakao' onclick='loginWithKakao()'><div class='wpc-icon-kakao'></div></li>";
						}
					break;

					case "GP" :

						if($options->social_google == "T" && $options->google_client_id != ""){
						$login_content_social .= "<li class='wpc-icon wpc-icon-btn-google' onclick='google_plus_load();'><div id='wpcSignIn' class='customGPlusSignIn'>
    <span class='icon'></span>
    <span class='buttonText'><div class='wpc-icon-google'></div></span>
  </div></li>";
  						}
					break;
				}
			}
			$login_content_social .= "</ul></li>";			
		}

	}

	if($options->social_position == "top"){
		$login_content .= $login_content_social;
	}
	$login_content .= '<li style="clear:both"><table><tr><th><label>'.__('아이디','wpcoop').'</label></th></tr><tr><td><input type="text" name="log" class="wpc_input" id="user_login" value="" placeholder="아이디 또는 이메일"/></td></tr></table></li>';
	$login_content .= '<li><table><tr><th><label>'.__('비밀번호','wpcoop').'</label></th></tr><tr><td><input type="password" name="pwd" class="wpc_input" id="user_pass" value="" placeholder="비밀번호"/></td></tr></table></li>';
	$login_content .= '<li><button type="submit" class="btn btn-primary">확인</button></li>';

	if($options->social_position == "bottom"){
		$login_content .= $login_content_social;
	}

	if($options->find_pwd == "T" or $option->join_member == "T"){
		$login_content .= '<li>
							<ul style="list-style:none; padding:0; text-align:center">';
			if($options->find_pwd == "T"){
				$login_content .= '<li style="display:inline-block; padding:0px 10px"><a href=\''.get_the_permalink($options->join_id).'\'>회원가입</a></li>';
			}
			if($options->join_member == "T"){
				$login_content .= '<li style="display:inline-block; padding:0px 10px"><a href=\''.get_the_permalink($options->reset_pwd_id).'\'>비밀번호 초기화</a></li>';
			}
		$login_content .= '</ul></li>';
	}

	$login_content .= '</ul>';
if($options->social_google == "T"){
	$login_content .= '<input type="hidden" class="gcid" value="'.$options->google_client_id.'">';
}
	$login_content .= '</div>';
	$login_content .= '</form>';

	if($options->login_enable == "T" && $options->social_kakao == "T"){
		$login_content .= '    <script>
	    // 사용할 앱의 Javascript 키를 설정해 주세요.
	    Kakao.init(\''.$options->kakao_app_key.'\');
	    function loginWithKakao() {
	      // 로그인 창을 띄웁니다.
	      Kakao.Auth.login({
	        success: function(authObj) {
		      // 로그인 성공시 API를 호출합니다.
		      Kakao.API.request({
		        url: \'/v1/user/me\',
		        success: function(res) {
		          var result_id = JSON.stringify(res.id);
		          var result_name = JSON.stringify(res.properties.nickname);
		          wpc_social_login(result_id, result_name, \'null\', \'kakao\');
		        },
		        fail: function(error) {
		          //alert(JSON.stringify(error));
		        	jQuery("#wpc_loading").hide();
		        }
		      });
	        },
	        fail: function(err) {
	          //alert(JSON.stringify(err));
	          jQuery("#wpc_loading").hide();
	        }
	      });
	    };
	    </script>';
	}

	if($options->login_enable == "T" && $options->social_facebook == "T"){

		$login_content .= "
      <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '".$options->facebook_app_id."', // 앱 ID
              status     : true,
              cookie     : true,
              xfbml      : true
            });
			};
   
			function fb_login(){
			    FB.login(function(response) {

			        if (response.authResponse) {
			            //console.log('Welcome!  Fetching your information.... ');
			            //console.log(response); // dump complete info
			            access_token = response.authResponse.accessToken; //get access token
			            fb_user_id = response.authResponse.userID; //get FB UID

			            FB.api('/me', function(response) {
			                fb_user_email = response.email; //get user email
			          		fb_user_name  = response.name;
			          		wpc_social_login(fb_user_id, fb_user_email, fb_user_name, 'facebook');
			            });

			        } else {
			            //user hit cancel button
			            jQuery('#wpc_loading').hide();
			        }
			    }, {
			        scope: 'publish_stream,email'
			    });
			}
			(function() {
			    var e = document.createElement('script');
			    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
			    e.async = true;
			    document.getElementById('fb-root').appendChild(e);
			}());
      </script>
		";

	}

	echo $login_content;
}

?>