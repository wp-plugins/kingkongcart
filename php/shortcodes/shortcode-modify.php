<?php
add_shortcode("wpc_modify","wpc_modify");
function wpc_modify($attr){

  $joinForm = new wpcoop_joinForm();
  $options  = new wpcoop_login();

  if(isset($_POST['wpc_input_id'])){
    $wpc_input_id             = ""; // 아이디
    $wpc_input_password       = ""; // 패스워드
    $wpc_input_re_password    = ""; // 패스워드 확인
    $wpc_input_email          = ""; // 이메일
    $wpc_input_tel            = ""; // 연락처
    $wpc_input_post_code      = ""; // 우편번호
    $wpc_input_basic_address  = ""; // 기본주소
    $wpc_input_else_address   = ""; // 나머지주소
    $wpc_input_name           = ""; // 이름
    $wpc_input_term_use       = ""; // 이용약관
    $wpc_input_private        = ""; // 개인정보보호정책
    $wpc_input_phone          = ""; // 휴대폰
    $required_methods         = $joinForm->required_method(); // (Array) 필수기입요소
    $user_id                  = $_POST['user_id'];

    if(isset($_POST['wpc_input_id'])){
      $wpc_input_id             = $_POST['wpc_input_id'];
    }
    if(isset($_POST['wpc_input_password'])){
      $wpc_input_password       = $_POST['wpc_input_password'];
    }
    if(isset($_POST['wpc_input_email'])){
      $wpc_input_email          = $_POST['wpc_input_email'];
    }
    if(isset($_POST['wpc_input_tel'])){
      $wpc_input_tel            = $_POST['wpc_input_tel'];
    }
    if(isset($_POST['wpc_input_post_code'])){
      $wpc_input_post_code      = $_POST['wpc_input_post_code'];
    }
    if(isset($_POST['wpc_input_basic_address'])){
      $wpc_input_basic_address  = $_POST['wpc_input_basic_address'];
    }
    if(isset($_POST['wpc_input_else_address'])){
      $wpc_input_else_address   = $_POST['wpc_input_else_address'];
    }
    if(isset($_POST['wpc_input_name'])){
      $wpc_input_name           = $_POST['wpc_input_name'];
    }
    if(isset($_POST['wpc_input_phone'])){
      $wpc_input_phone          = $_POST['wpc_input_phone'];
    }

    if($wpc_input_password){
      wp_set_password( $wpc_input_password, $user_id );
    }

    wp_update_user( array('ID' => $user_id, 'display_name' => $wpc_input_name) );
    update_user_meta($user_id, "wpc_user_tel", $wpc_input_tel);
    update_user_meta($user_id, "wpc_user_phone", $wpc_input_phone);
    update_user_meta($user_id, "wpc_user_postcode", $wpc_input_post_code);
    update_user_meta($user_id, "wpc_user_basic_address", $wpc_input_basic_address);
    update_user_meta($user_id, "wpc_user_else_address", $wpc_input_else_address);

    $content = get_option("wpc_modify_thankyou_page");

    echo $content;
 
  } else {
    echo $joinForm->wpc_field_modify_output();
  }
}
?>