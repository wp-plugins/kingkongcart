<?php

	class wpcoop_joinForm {

		function __construct(){
      $this->all_method       = array( 
        'id'        => 'id',
        'password'  => 'password',
        'name'      => 'name',
        'tel'       => 'tel',
        'phone'     => 'phone',
        'email'     => 'email',
        'address'   => 'address',
        'term_use'  => 'term_use',
        'private'   => 'private'
        );
      $this->using_method     = $this->wpc_using_method();
      $this->not_using_method = $this->wpc_not_using_method();
      $this->join_method_priority = $this->wpc_join_method_priority();
		}
 
    function wpc_using_method(){
      $get_join_methods = get_option('wpc_join_methods');
      if($get_join_methods){
        $get_join_methods = get_option('wpc_join_methods', true);
        $join_methods = unserialize($get_join_methods);
        $join_methods = apply_filters("wpc_using_method_after", $join_methods);
      } else {
        // default option
        $join_methods = array(
          'id'        => array(
            'label'     => $this->get_label(null, 'id'),
            'value'     => 'id',
            'size'      => null,
            'required'  => true,
            'mustuse'   => true,
            'using'     => true
          ),
          'email'        => array(
            'label'     => $this->get_label(null, 'email'),
            'value'     => 'email',
            'size'      => null,
            'required'  => true,
            'mustuse'   => true,
            'using'     => true
          ),          
          'password'        => array(
            'label'     => $this->get_label(null, 'password'),
            'value'     => 'password',
            'size'      => null,
            'required'  => true,
            'mustuse'   => true,
            'using'     => true
          )
        );
      }
      return $join_methods;
    }

    function get_required($value){
      $required = "";
      $using_methods = $this->using_method;
      if(isset($using_methods[$value])){
        $required =  $using_methods[$value]['required'];
      }
      if(!$required){
        return false;
      } else {
        return true;
      }
    }

    function required_display(){

      $result = apply_filters('required_display', '(필수)');
      return $result;
    }

    function required_method(){
      $result        = "";
      $using_methods = $this->using_method;
      foreach($using_methods as $method){
        if($method['required'] == true or $method['required'] == 'required'){
          if(!isset($method['type'])){
            $result .= $method['value'].",";
          }
        }
      }
      $result = substr($result, 0, strlen($result) - 1);
      $result = explode(",", $result);
      return $result;
    }

    function get_label($label, $value){
      if(!$label){
        switch($value){
          case "id" :
            $labelling = "아이디";
          break;

          case "password" :
            $labelling = "비밀번호";
          break;

          case "name" :
            $labelling = "이름";
          break;

          case "email" :
            $labelling = "이메일";
          break;

          case "tel" :
            $labelling = "유선전화";
          break;

          case "phone" :
            $labelling = "휴대폰";
          break;

          case "address" :
            $labelling = "주소";
          break;

          case "term_use" :
            $labelling = "이용약관";
          break;

          case "private" :
            $labelling = "개인정보보호정책";
          break;    
        }

        return $labelling;
      } else {
        return $label;
      }
    }

    function wpc_join_method_priority(){

      $using_methods = $this->using_method;
      $priority      = "";
      foreach($using_methods as $method){
        if(!isset($method['type'])){
          $priority .= $method['value'].",";
        }
      }
      $priority = substr($priority, 0, strlen($priority) - 1);
      return $priority;
    }

    function wpc_not_using_method(){
      $all_methods      = $this->all_method;
      $using_methods    = $this->using_method;
      $arrange_methods  = "";

      foreach($using_methods as $method){
          if(!isset($method['type'])){
            if(in_array($method['value'], $all_methods, false) == true){
              unset($all_methods[$method['value']]);
            }
          }
      }

      foreach($all_methods as $method){
        $arrange_methods[$method] = array(
          'value' => $method,
          'label' => $this->get_label(null, $method)
        );
      }

      return $arrange_methods;

    }



    public function wpc_input_basic($method){
      if(isset($method['value'])){
        if($this->get_required($method['value'])){
          $required = $this->required_display();
        } else{
          $required = "";
        }
        return "<li><ul><li>".$this->get_label($method['label'], $method['value'])." ".$required."</li><li><input type='text' name='wpc_input_".$method['value']."' style='width:".$method['size']."'></li></ul></li>";
      } else {
        return false;
      }
    }

    public function wpc_input_modify_basic($method, $value){
      $current_user   = wp_get_current_user();
      $user_login     = $current_user->user_login;
      $user_id        = $current_user->ID;
      $user_email     = $current_user->user_email;
      $user_name      = $current_user->display_name;
      $user_tel       = get_user_meta($user_id, "wpc_user_tel", true);
      $user_phone     = get_user_meta($user_id, "wpc_user_phone", true);

      switch($value){
        case "id" :
          return "<li><ul><li>".$this->get_label($method['label'], $method['value'])."</li><li>".$user_login."<input type='hidden' name='wpc_input_".$method['value']."' value='".$user_id."'></li></ul></li>";    
        break;

        case "email" :
          return "<li><ul><li>".$this->get_label($method['label'], $method['value'])."</li><li>".$user_email."<input type='hidden' name='wpc_input_".$method['value']."' value='".$user_email."'></li></ul></li>";
        break;

        case "phone" :
          return "<li><ul><li>".$this->get_label($method['label'], $method['value'])."</li><li><input type='text' name='wpc_input_".$method['value']."' style='width:".$method['size']."' value='".$user_phone."'></li></ul></li>";
        break;

        case "tel" :
          return "<li><ul><li>".$this->get_label($method['label'], $method['value'])."</li><li><input type='text' name='wpc_input_".$method['value']."' style='width:".$method['size']."' value='".$user_tel."'></li></ul></li>";
        break;

        case "name" :
          return "<li><ul><li>".$this->get_label($method['label'], $method['value'])."</li><li><input type='text' name='wpc_input_".$method['value']."' style='width:".$method['size']."' value='".$user_name."'></li></ul></li>";
        break;
      }
    }

    public function wpc_input_modify_address($method){
      if(isset($method['value'])){
        if($this->get_required($method['value'])){
          $required = $this->required_display();
        } else{
          $required = "";
        }

        $current_user   = wp_get_current_user();
        $user_id        = $current_user->ID;
        $basic_address  = get_user_meta($user_id, "wpc_user_basic_address", true);
        $else_address   = get_user_meta($user_id, "wpc_user_else_address", true);
        $post_code      = get_user_meta($user_id, "wpc_user_postcode", true);

        $result = "<li><ul><li>우편번호 ".$required."</li><li><input type='text' name='wpc_input_post_code' value='".$post_code."'> <button class='wpc_btn' type='button' onclick='showDaumPostcode();' style='width:".$method['size']."'>우편번호 검색</button>
        <div id='layer' style='display:none;border:5px solid;position:fixed;max-width:400px; width:100%; height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden'><img src='//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png' id='btnCloseLayer' style='cursor:pointer;position:absolute;right:-3px;top:-3px' onclick='closeDaumPostcode()'>
          </div></li></ul></li>";
        $result .= "<li><ul><li>기본주소</li><li><input type='text' name='wpc_input_basic_address' style='width:".$method['size']."' value='".$basic_address."'></li></ul></li>";
        $result .= "<li><ul><li>나머지주소</li><li><input type='text' name='wpc_input_else_address' style='width:".$method['size']."' value='".$else_address."'></li></ul></li>";

        return $result;
      }
    }

    public function wpc_input_address($method){
      if(isset($method['value'])){
        if($this->get_required($method['value'])){
          $required = $this->required_display();
        } else{
          $required = "";
        }

        $result = "<li><ul><li>우편번호 ".$required."</li><li><input type='text' name='wpc_input_post_code'> <button class='wpc_btn' type='button' onclick='showDaumPostcode();' style='width:".$method['size']."'>우편번호 검색</button>
        <div id='layer' style='display:none;border:5px solid;position:fixed;max-width:400px; width:100%; height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden'><img src='//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png' id='btnCloseLayer' style='cursor:pointer;position:absolute;right:-3px;top:-3px' onclick='closeDaumPostcode()'>
          </div></li></ul></li>";
        $result .= "<li><ul><li>기본주소</li><li><input type='text' name='wpc_input_basic_address' style='width:".$method['size']."'></li></ul></li>";
        $result .= "<li><ul><li>나머지주소</li><li><input type='text' name='wpc_input_else_address' style='width:".$method['size']."'></li></ul></li>";

        return $result;
      }
    }

    public function wpc_input_password($method){
      if(isset($method['value'])){

        if($this->get_required($method['value'])){
          $required = $this->required_display();
        } else{
          $required = "";
        }

        $result  = "<li><ul><li>".$this->get_label($method['label'], $method['value'])." ".$required."</li><li><input type='password' name='wpc_input_".$method['value']."' style='width:".$method['size']."'></li></ul></li>";
        $result .= "<li><ul><li>".$this->get_label($method['label'], $method['value'])." 확인 ".$required."</li><li><input type='password' name='wpc_input_re_pwd' style='width:".$method['size']."'></li></ul></li>";
        return $result;
      } else {
        return false;
      }
    }

    public function wpc_input_policy($method){
      $options = new wpcoop_login();
      if(isset($method['value'])){

        if($this->get_required($method['value'])){
          $required = '<li><input type="checkbox" name="wpc_checkbox_'.$method['value'].'"> '.$this->get_label($method['label'], $method['value']).'에 동의합니다.</li>';
        } else {
          $required = '';
        }

        switch($method['value']){
          case "term_use" :
            $value_id = $options->term_use_id;
          break;

          case "private" :
            $value_id = $options->private_id;
          break;
        }

        $result = "<li><ul><li>".$this->get_label($method['label'], $method['value'])."</li><li><textarea name='wpc_input_".$method['value']."' style='width:".$method['size']."'>".get_post_field('post_content', $value_id)."</textarea></li>".$required."</ul></li>";

        return $result;
      } else {
        return false;
      }
    }

    public function wpc_field_output(){
      $using_methods = $this->using_method;
      $wpc_results   = "";
      foreach($using_methods as $method){

        $optional = apply_filters("wpc_field_output_before", $method);

        if(!is_string($optional)){
          switch($method['value']){
      
            case "address" :
              $wpc_results .= $this->wpc_input_address( $method );
            break;

            case "password" :
              $wpc_results .= $this->wpc_input_password( $method );
            break;

            case "term_use" :
              $wpc_results .= $this->wpc_input_policy( $method );
            break;

            case "private" :
              $wpc_results .= $this->wpc_input_policy( $method );
            break;

            default :
                $wpc_results .= $this->wpc_input_basic( $method );
            break;
          }
        } else {
          $wpc_results .= $optional;
        }
      }
      $wpc_results = "<form method='post' id='wpc_join_form'><div id='wpc_join'><ul>".$wpc_results."</ul><button type='button' class='wpc_btn wpc_join_submit'>확인</button></div><input type='hidden' name='username_exists' value='0'><input type='hidden' name='email_exists' value='0'><input type='hidden' name='pwd_success' value='0'><input type='hidden' name='form_type' value='join'></form>";
      return $wpc_results;
    }

    public function wpc_field_modify_output(){

      if(is_user_logged_in()){
        $current_user   = wp_get_current_user();
        $user_id        = $current_user->ID;
        $using_methods = $this->using_method;
        $wpc_results   = "";
        foreach($using_methods as $method){

          $optional = apply_filters("wpc_field_output_before", $method);

          if(!is_string($optional)){
            switch($method['value']){
        
              case "address" :
                $wpc_results .= $this->wpc_input_modify_address( $method );
              break;

              case "password" :
                $wpc_results .= $this->wpc_input_password( $method );
              break;

              case "term_use" :
                $wpc_results .= null;
              break;

              case "private" :
                $wpc_results .= null;
              break;

              default :
                  $wpc_results .= $this->wpc_input_modify_basic( $method, $method['value'] );
              break;
            }
          } else {
            $wpc_results .= $optional;
          }
        }
        $wpc_results = "<form method='post' id='wpc_join_form'><div id='wpc_join'><ul>".$wpc_results."</ul><button type='button' class='wpc_btn wpc_join_submit' name='wpc_modify_submit' value='T'>수정</button></div><input type='hidden' name='username_exists' value='0'><input type='hidden' name='email_exists' value='0'><input type='hidden' name='pwd_success' value='0'><input type='hidden' name='user_id' value='".$user_id."'><input type='hidden' name='form_type' value='modify'></form>";
      } else {
        $wpc_results = "회원정보 수정은 로그인 후 이용하실 수 있습니다.";
      }
      return $wpc_results;
    }
	}

?>