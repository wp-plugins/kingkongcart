<?php
add_shortcode("wpc_join","wpc_join");
function wpc_join($attr){

  if(!is_user_logged_in()){
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

      $user_id = wp_create_user($wpc_input_id, $wpc_input_password, $wpc_input_email);

      if (!$user_id || is_wp_error($user_id)){
        echo "회원등록에 실패 하였습니다. 관리자에게 문의 바랍니다.";
      } else {

        if($wpc_input_name){
          $userinfo = array(
            'ID' => $user_id,
            'display_name' => $wpc_input_name
          );
          wp_update_user($userinfo);
        }

        update_user_meta($user_id, "first_name", $wpc_input_name);
        update_user_meta($user_id, "wpc_user_tel", $wpc_input_tel);
        update_user_meta($user_id, "wpc_user_phone", $wpc_input_phone);
        update_user_meta($user_id, "wpc_user_postcode", $wpc_input_post_code);
        update_user_meta($user_id, "wpc_user_basic_address", $wpc_input_basic_address);
        update_user_meta($user_id, "wpc_user_else_address", $wpc_input_else_address);

        do_action("wpc_update_user_after", $user_id, $_POST);

        if( $options->woo_billing_input == "T" ){
          if(isset($_POST['wpc_input_name'])){
            update_user_meta($user_id, "billing_first_name", $wpc_input_name);
          }
          if(isset($_POST['wpc_input_phone'])){
            update_user_meta($user_id, "billing_phone", $wpc_input_phone);
          }
          if(isset($_POST['wpc_input_basic_address'])){
            update_user_meta($user_id, "billing_address_1", $wpc_input_basic_address);
          }
          if(isset($_POST['wpc_input_else_address'])){
            update_user_meta($user_id, "billing_address_2", $wpc_input_else_address);
          }
          if(isset($_POST['wpc_input_post_code'])){
            update_user_meta($user_id, "billing_postcode", $wpc_input_post_code);
          }
          if(isset($_POST['wpc_input_email'])){
            update_user_meta($user_id, "billing_email", $wpc_input_email);
          }
        }

        if( $options->woo_shipping_input == "T" ){
          if(isset($_POST['wpc_input_name'])){
            update_user_meta($user_id, "shipping_first_name", $wpc_input_name);
          }
          if(isset($_POST['wpc_input_basic_address'])){
            update_user_meta($user_id, "shipping_address_1", $wpc_input_basic_address);
          }
          if(isset($_POST['wpc_input_else_address'])){
            update_user_meta($user_id, "shipping_address_2", $wpc_input_else_address);
          }
          if(isset($_POST['wpc_input_post_code'])){
            update_user_meta($user_id, "shipping_postcode", $wpc_input_post_code);
          }
        }



        $content = get_option("wpc_thankyou_page");

        echo $content;

      }    
   
    } else {
      echo $joinForm->wpc_field_output();
    }
  } else {
      print('<script>window.location.href="'.home_url().'";</script>');
  }
}
?>