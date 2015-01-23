<?php
  
  $hour_options     = "";
  $minute_options   = "";
  $session_options  = "";
  for ($i=0; $i < 24; $i++) { 
    $hour_options .= "<option>".sprintf("%02d", $i)."</option>";
  }

  for ($j=0; $j < 60; $j++){
    $minute_options .= "<option>".sprintf("%02d", $j)."</option>";
  }

  for ($c=1; $c < 31; $c++){
    $session_options .= "<option>".$c."</option>";
  }

?>
<h2>팝업 신규추가</h2>
팝업을 신규로 등록합니다.<br /><br />
<form method="POST" id="wpc_popup_add_form">
<input type="hidden" class="plugin_url" value="<?php echo WPCOOP_PLUGINS_URL;?>">
<table>
  <tr>
    <th>팝업명:</th>
    <td><input type="text" name="wpc_popup_title" style="width:80%"></td>
  </tr>
  <tr>
    <th>팝업 활성화:</th>
    <td>
      <img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" style="width:50px; height:auto" class="btn_onoff">
      <input type="hidden" name="wpc_popup_status" value="F">
    </td>
  </tr>
  <tr>
    <th rowspan="2">적용 일시:</th>
    <td>시작일 <input type="text" id="wpc_popup_start_date" name="wpc_popup_start_date" style="width:100px"> <select name="wpc_popup_start_hour"><?php echo $hour_options;?></select> 시 <select name="wpc_popup_start_minute"><?php echo $minute_options;?></select> 분</td>
  </tr>
  <tr>
    <td>종료일 <input type="text" id="wpc_popup_end_date" name="wpc_popup_end_date" style="width:100px"> <select name="wpc_popup_end_hour"><?php echo $hour_options;?></select> 시 <select name="wpc_popup_end_minute"><?php echo $minute_options;?></select> 분</td>
  </tr>
  <tr>
    <th>스타일:</th>
    <td>
      <table>
        <tr>
          <td><input type="radio" name="wpc_popup_style" value="top"></td>
          <td><img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/wpc-popup-icon-style1.png" style="width:40px; height:auto"></td>
          <td><strong>최상단 노출</strong> : 최상단에 고정적으로 표시 됩니다.</td>
        </tr>
        <tr>
          <td><input type="radio" name="wpc_popup_style" value="center" checked></td>
          <td><img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/wpc-popup-icon-style2.png" style="width:40px; height:auto"></td>
          <td><strong>화면 중앙 노출</strong> : 기본적으로 화면 중앙에 위치시키며 세부설정을 통해 위치를 변경하실 수 있습니다.</td>
        </tr>
        <tr>
          <td><input type="radio" name="wpc_popup_style" value="bottom"></td>
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
          <td><input type="text" name="wpc_popup_width"></td>
        </tr>
        <tr>
          <th>세로폭:</th>
          <td><input type="text" name="wpc_popup_height"></td>
        </tr>
        <tr>
          <th>Position:</th>
          <td>
            <input type="radio" name="wpc_popup_position" value="absolute" checked> Absolute <input type="radio" name="wpc_popup_position" value="fixed"> Fixed
          </td>
        </tr>
        <tr>
          <th>세부위치:</th>
          <td>Top <input type="text" name="wpc_popup_detail_top"> Left <input type="text" name="wpc_popup_detail_left"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <th>Link URL:</th>
    <td><input type="text" name="wpc_popup_url" style="width:500px"> <input type="checkbox" name="wpc_popup_url_open" value="_blank"> 새창으로 열기</td>
  </tr>
  <tr>
    <th>쿠키유지기간:</th>
    <td><select name="wpc_popup_session"><?php echo $session_options;?></select> 일</td>
  </tr>
  <tr>
    <th>모바일 노출:</th>
    <td><input type="radio" name="wpc_popup_responsive" value="T" checked> 노출 <input type="radio" name="wpc_popup_responsive" value="F"> 미노출</td>
  </tr>
  <tr>
    <th>내용 형태:</th>
    <td><input type="radio" name="wpc_popup_upload_type" value="image" checked> 이미지등록 <input type="radio" name="wpc_popup_upload_type" value="editor"> 에디터</td>
  </tr>
  <tr>
    <td colspan="2">
      <div class="wpc_popup_new_image">
        <input type="hidden" name="wpc_popup_image_content" class="image_result_path">
        <input type="hidden" class="image_result_id">
        <div style="position:relative"><div class="image_result"></div><div class="image_result_close" style="position:absolute; top:0px; right:-40px; cursor:pointer"><div class="icon-wpc-close"></div></div></div>
        <div class="popup_image_upload_button" style="margin-top:70px">
          아래의 이미지 업로드 버튼을 눌러 등록하실 이미지를 선택하세요.<br><br>
          <button type="button" class="button button-primary" onclick="wpc_popup_image_upload()">이미지 업로드</button>
        </div>
      </div>
      <div class="wpc_popup_new_editor" style="display:none">
        <?php
          $settings = array('textarea_rows' => 20); 
          wp_editor('', 'wpc_popup_editor', $settings);
        ?>
      </div>
    </td>
  </tr>
</table>
<button type="submit" value="submit" name="submit" class="button button-primary save_wpc_popup">팝업 등록</button>
</form>