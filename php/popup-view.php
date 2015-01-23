<?php
  date_default_timezone_set('Asia/Seoul');
  $popup_id = $_GET['popup_id'];

  $shour_options = "";
  $ehour_options = "";
  $sminute_options = "";
  $eminute_options = "";
  $session_options = "";

  $popup_options = get_post_meta($popup_id, "wpc_popup_options", true);
  $popup_options = unserialize($popup_options);
  $popup_shour    = date("H", $popup_options['smktime']);
  $popup_ehour    = date("H", $popup_options['emktime']);
  $popup_sminute  = date("i", $popup_options['smktime']);
  $popup_eminute  = date("i", $popup_options['emktime']);

  for ($i=0; $i < 24; $i++) { 
    if($i == $popup_shour){
      $shour_options .= "<option selected>".sprintf("%02d", $i)."</option>";
    } else {
      $shour_options .= "<option>".sprintf("%02d", $i)."</option>";
    }
    if($i == $popup_ehour){
      $ehour_options .= "<option selected>".sprintf("%02d", $i)."</option>";
    } else {
      $ehour_options .= "<option>".sprintf("%02d", $i)."</option>";
    }
  }

  for ($j=0; $j < 60; $j++){
    if($j == $popup_sminute){
      $sminute_options .= "<option selected>".sprintf("%02d", $j)."</option>";
    } else {
      $sminute_options .= "<option>".sprintf("%02d", $j)."</option>";
    }
    if($j == $popup_eminute){
      $eminute_options .= "<option selected>".sprintf("%02d", $j)."</option>";
    } else {
      $eminute_options .= "<option>".sprintf("%02d", $j)."</option>";
    }
  }

  for ($c=1; $c < 31; $c++){
    if($c == $popup_options['session']){
      $session_options .= "<option selected>".$c."</option>";
    } else {
      $session_options .= "<option>".$c."</option>";
    }
  }

  switch($popup_options['style']){
    case "top":
      $popup_style_top_checked    = "checked";
    break;

    case "center" :
      $popup_style_center_checked = "checked";
    break;

    case "bottom" :
      $popup_style_bottom_checked = "checked";
    break;
  }

  switch($popup_options['position']){
    case "absolute" :
      $popup_position_absolute = "checked";
    break;

    case "fixed" :
      $popup_position_fixed    = "checked";
    break;
  }

if(isset($popup_options['responsive'])){
  switch($popup_options['responsive']){
    case "T" :
      $responsive_enable = "checked";
    break;

    case "F" :
      $responsive_disable = "checked";
    break;
  }
}

if(isset($popup_options['url_open'])){
  if($popup_options['url_open'] == "_blank"){
    $url_open_check = "checked";
  } else {
    $url_open_check = "";
  }
}

  switch($popup_options['upload']){
    case "image" :
      $popup_upload_image     = "checked";
      $popup_content_image    = "display:block;";
      $popup_content_editor   = "display:none";
      $popup_editor_content   = null;
      $popup_image_content    = get_post_meta($popup_id, "wpc_popup_content", true);
    break;

    case "editor" :
      $popup_upload_editor    = "checked";
      $popup_content_image    = "display:none;";
      $popup_content_editor   = "display:block;";
      $popup_editor_content   = get_post_meta($popup_id, "wpc_popup_content", true);
      $popup_image_content    = null;
    break;
  }

?>
<h2>팝업 신규추가</h2>
팝업을 신규로 등록합니다.<br /><br />
<form method="POST" id="wpc_popup_add_form">
<input type="hidden" class="plugin_url" value="<?php echo WPCOOP_PLUGINS_URL;?>">
<input type="hidden" name="popup_id" value="<?php echo $popup_id;?>">
<table>
  <tr>
    <th>팝업명:</th>
    <td><input type="text" name="wpc_popup_title" style="width:80%" value="<?php echo get_the_title($popup_id);?>"></td>
  </tr>
  <tr>
    <th>팝업 활성화:</th>
    <td>
<?php
  if($popup_options['status'] == "T"){
?>
      <img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" style="width:50px; height:auto" class="btn_onoff">
      <input type="hidden" name="wpc_popup_status" value="T">
<?php
  } else {
?>
      <img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" style="width:50px; height:auto" class="btn_onoff">
      <input type="hidden" name="wpc_popup_status" value="F">
<?php
  }
?>
      
    </td>
  </tr>
  <tr>
    <th rowspan="2">적용 일시:</th>
    <td>시작일 <input type="text" id="wpc_popup_start_date" name="wpc_popup_start_date" style="width:100px" value="<?php echo date('Y-m-d', $popup_options['smktime']);?>"> <select name="wpc_popup_start_hour"><?php echo $shour_options;?></select> 시 <select name="wpc_popup_start_minute"><?php echo $sminute_options;?></select> 분</td>
  </tr>
  <tr>
    <td>종료일 <input type="text" id="wpc_popup_end_date" name="wpc_popup_end_date" style="width:100px" value="<?php echo date('Y-m-d', $popup_options['emktime']);?>"> <select name="wpc_popup_end_hour"><?php echo $ehour_options;?></select> 시 <select name="wpc_popup_end_minute"><?php echo $eminute_options;?></select> 분</td>
  </tr>
  <tr>
    <th>스타일:</th>
    <td>
      <table>
        <tr>
          <td><input type="radio" name="wpc_popup_style" value="top" <?php echo $popup_style_top_checked;?>></td>
          <td><img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/wpc-popup-icon-style1.png" style="width:40px; height:auto"></td>
          <td><strong>최상단 노출</strong> : 최상단에 고정적으로 표시 됩니다.</td>
        </tr>
        <tr>
          <td><input type="radio" name="wpc_popup_style" value="center" <?php echo $popup_style_center_checked;?>></td>
          <td><img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/wpc-popup-icon-style2.png" style="width:40px; height:auto"></td>
          <td><strong>화면 중앙 노출</strong> : 기본적으로 화면 중앙에 위치시키며 세부설정을 통해 위치를 변경하실 수 있습니다.</td>
        </tr>
        <tr>
          <td><input type="radio" name="wpc_popup_style" value="bottom" <?php echo $popup_style_bottom_checked;?>></td>
          <td><img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/wpc-popup-icon-style3.png" style="width:40px; height:auto"></td>
          <td><strong>최하단 노출</strong> : 최하단에 고정적으로 표시 됩니다.</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <th>세부설정:</th>
    <td>
      <table>
        <tr>
          <th>가로폭:</th>
          <td><input type="text" name="wpc_popup_width" value="<?php echo $popup_options['width'];?>"></td>
        </tr>
        <tr>
          <th>세로폭:</th>
          <td><input type="text" name="wpc_popup_height" value="<?php echo $popup_options['height'];?>"></td>
        </tr>
        <tr>
          <th>Position:</th>
          <td>
            <input type="radio" name="wpc_popup_position" value="absolute" <?php echo $popup_position_absolute;?>> Absolute <input type="radio" name="wpc_popup_position" value="fixed" <?php echo $popup_position_fixed;?>> Fixed
          </td>
        </tr>
        <tr>
          <th>세부위치:</th>
          <td>Top <input type="text" name="wpc_popup_detail_top" value="<?php echo $popup_options['top'];?>"> Left <input type="text" name="wpc_popup_detail_left" value="<?php echo $popup_options['left'];?>"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <th>Link URL:</th>
    <td><input type="text" name="wpc_popup_url" style="width:500px" value="<?php echo $popup_options['url'];?>"> <input type="checkbox" value="_blank" name="wpc_popup_url_open" <?php echo $url_open_check;?>> 새창으로 열기</td>
  </tr>
  <tr>
    <th>쿠키유지기간:</th>
    <td><select name="wpc_popup_session"><?php echo $session_options;?></select> 일</td>
  </tr>
  <tr>
    <th>모바일 노출:</th>
    <td><input type="radio" name="wpc_popup_responsive" value="T" <?php echo $responsive_enable;?>> 노출 <input type="radio" name="wpc_popup_responsive" value="F" <?php echo $responsive_disable;?>> 미노출</td>
  </tr>
  <tr>
    <th>내용 형태:</th>
    <td><input type="radio" name="wpc_popup_upload_type" value="image" <?php echo $popup_upload_image;?>> 이미지등록 <input type="radio" name="wpc_popup_upload_type" value="editor" <?php echo $popup_upload_editor;?>> 에디터</td>
  </tr>
  <tr>
    <td colspan="2">
      <div class="wpc_popup_new_image" style="<?php echo $popup_content_image;?>">
        <input type="hidden" name="wpc_popup_image_content" class="image_result_path" value="<?php echo $popup_image_content;?>">
        <input type="hidden" class="image_result_id">
        

      
<?php
  if($popup_image_content){
?>
      <div style="position:relative">
        <div class="image_result">
          <img src="<?php echo $popup_image_content;?>">
        </div>
        <div class="image_result_close" style="display:block; position:absolute; top:0px; right:-40px; cursor:pointer">
          <div class="icon-wpc-close"></div>
        </div>
      </div>
      <div class="popup_image_upload_button" style="display:none; margin-top:70px">
        아래의 이미지 업로드 버튼을 눌러 등록하실 이미지를 선택하세요.<br><br>
        <button type="button" class="button button-primary" onclick="wpc_popup_image_upload()">이미지 업로드</button>
      </div>          
<?php
  } else {
?>
      <div style="position:relative">
        <div class="image_result"></div>
        <div class="image_result_close" style="position:absolute; top:0px; right:-40px; cursor:pointer">
          <div class="icon-wpc-close"></div>
        </div>
      </div>
      <div class="popup_image_upload_button" style="margin-top:70px">
        아래의 이미지 업로드 버튼을 눌러 등록하실 이미지를 선택하세요.<br><br>
        <button type="button" class="button button-primary" onclick="wpc_popup_image_upload()">이미지 업로드</button>
      </div>
<?php
  }
?>
      </div>
      <div class="wpc_popup_new_editor" style="<?php echo $popup_content_editor;?>">
        <?php
          $settings = array('textarea_rows' => 20); 
          wp_editor($popup_editor_content, 'wpc_popup_editor', $settings);
        ?>
      </div>
    </td>
  </tr>
</table>
<button type="submit" value="submit" name="submit" class="button button-primary save_wpc_popup">수정 완료</button>
</form>