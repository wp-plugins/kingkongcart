<?php
add_shortcode("wpc_reset_pwd","wpc_reset_pwd");
function wpc_reset_pwd($attr){
  global $wpdb;
?>

    <div id="result"></div>
<form method="post" action="" class="wp-user-form">
<div class="username">
    <label for="user_login">
      가입한 이메일이나 아이디를 입력하세요.<br>
       <input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
    </label>
   
</div>
<div class="login_fields">
    <?php do_action('login_form', 'resetpass'); ?>
    <button type="submit" name="user-submit" class="wpc_btn" tabindex="1002" /><?php echo _e("비밀번호 초기화", "wpcoop");?></button>

    <?php
    $error = array();
    if (isset($_POST['reset_pass']))
    {
        $username = trim($_POST['user_login']);
        $user_exists = false;
        if ( username_exists($username) ){
            $user_exists = true;
            $user = get_user_by('login', $username);
        } else if ( email_exists($username) ){
            $user_exists = true;
            $user = get_user_by('email', $username);
        } else {
            $error[] = '<p>' . __('이메일이나 아이디를 찾을 수 없습니다.') . '</p>';
        }
        if ( $user_exists ){

            $user_login = $user->user_login;
            $user_email = $user->user_email;
            // Generate something random for a password... md5'ing current time with a rand salt
            $key = substr(md5(uniqid(microtime())), 0, 8);
            // Now insert the new pass md5'd into the db
            $wpdb->query("UPDATE $wpdb->users SET user_activation_key = '$key' WHERE user_login = '$user_login'");
            //create email message
            $message = __('누군가 목조주택시공자협동조합 웹사이트의 비밀번호 변경을 요청하였습니다.') . "\r\n\r\n";
            $message .= get_option('siteurl') . "\r\n\r\n";
            $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
            $message .= __('비밀번호를 변경하기 위해서는 하기 주소를 클릭하시기 바랍니다. 변경을 원치 않으시면 무시하셔도 좋습니다.') . "\r\n\r\n";
            $message .= get_option('siteurl') . "/wp-login.php?action=rp&key=$key\r\n";
            //send email meassage
            $headers = "From: 목조주택시공자협동조합 <msh@wpcoop.kr>". "\r\n\\";
            if (!wp_mail($user_email, sprintf(__('[%s] Password Reset'), get_option('blogname')), $message, $headers) ){
              $error[] = '<p>' . __('이메일을 보낼 수 없습니다. 관리자에게 문의하시기 바랍니다.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') . '</p>';
          }

          //echo sprintf(__('[%s] Password Reset'), get_option('blogname'));
        }
        if (count($error) > 0)
        {
            echo $error[0];
        } else
        {
            echo '<p>' . __('가입하신 이메일로 메세지가 전송되었습니다. 확인해주세요.') . '</p>';
        }
    }
    ?> 
    <input type="hidden" name="reset_pass" value="1" />
    <input type="hidden" name="user-cookie" value="1" />
</div>
</form>

<?php
}
?>