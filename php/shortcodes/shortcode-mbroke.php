<?php
add_shortcode("wpc_mbroke","wpc_mbroke");
function wpc_mbroke($attr){

  $joinForm = new wpcoop_joinForm();
  $options  = new wpcoop_login();
  $current_user   = wp_get_current_user();

  if(is_user_logged_in()){
    if(isset($_POST['wpc_input_password']) && isset($_POST['wpc_mbroke_checkbox'])){
      
      $password = $_POST['wpc_input_password'];

      if($current_user && wp_check_password($password, $current_user->data->user_pass, $current_user->ID)){
        require_once(ABSPATH.'wp-admin/includes/user.php');
        wp_delete_user( $current_user->ID );
        echo "<script>alert('탈퇴 처리 되었습니다. 메인으로 이동합니다.'); location.href='".home_url()."';</script>";
      } else {
        echo "비밀번호가 일치하지 않습니다.";
      }

    } else { 

      $mbroke_before = apply_filters("wpc_mbroke_before", $value);
      $mbroke_checkbox_text = apply_filters("wpc_mbroke_checkbox_text", "회원 탈퇴에 동의 합니다.");
?>
<form method="post">
  <div id="wpc_mbroke_wrap">
    <ul>
      <?php if (isset($mbroke_before)) : echo $mbroke_before; endif; ?>
      <li><input type="checkbox" name="wpc_mbroke_checkbox" value="T"> <?php echo $mbroke_checkbox_text;?></li>
      <li class='label-password'>현재 비밀번호</li>
      <li><input type="password" name="wpc_input_password" value=""></li>
      <li><button type="submit" class="button button-wpc">확인</button></li>
    </ul>
  </div>
</form>
<?php
   }
  } else {
?>
  로그인 하셔야 이용하실 수 있습니다.
<?php
  }
}
?>