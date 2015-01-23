<h2>팝업 설정</h2>
팝업을 새로 등록하거나 수정 할 수 있습니다.<br /><br />

<button class="button button-primary dashicons-before icon-wpc-add" style="margin-bottom:10px" onclick="location.href='?page=wpcoop_popup&view=create_new';"> 팝업 신규추가</button>
<input type="hidden" class="plugin_url" value="<?php echo WPCOOP_PLUGINS_URL;?>">
  <table class="wp-list-table widefat fixed eg-bostbox">
    <thead>
      <tr>
        <th>ID</th>
        <th>팝업명</th>
        <th>환경설정</th>
        <th>시작일</th>
        <th>종료일</th>
        <th>상태</th>
      </tr>
    </thead>
    <tbody>
<?php
  global $wpdb;
  $table_name = $wpdb->prefix . "posts";
  $results   = $wpdb->get_results("SELECT * FROM $table_name WHERE post_type = 'wpc_popup' ");
  foreach($results as $result){
    $popup_id = $result->ID;

    $popup_options = get_post_meta($popup_id, "wpc_popup_options", true);
    $popup_options = unserialize($popup_options);

?>
      <tr>
        <td><?php echo $result->ID;?><input type="hidden" class="popup_id" value="<?php echo $result->ID;?>"></td>
        <td><?php echo $result->post_title;?></td>
        <td>
          <div class="btn-wrap">
            <a href="?page=wpcoop_popup&view=popup_view&popup_id=<?php echo $result->ID;?>" class="button-primary revgreen"><span class="dashicons-before icon-wpc-setting"></span> 설정하기</a>
            <a class="button button-popup-remove"><span class="dashicons-before icon-wpc-remove"></span></a>
          </div>
        </td>
        <td><?php echo $popup_options['start'];?></td>
        <td><?php echo $popup_options['end'];?></td>
        <td style="vertical-align:middle">
<?php
  if($popup_options['status'] == "T"){
?>
          <img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" style="width:50px; height:auto" class="btn_list_onoff active">
          <input type="hidden" value="T">
<?php
  } else {
?>
          <img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" style="width:50px; height:auto" class="btn_list_onoff">
          <input type="hidden" value="F">
<?php
  }
?>
          
        </td>
      </tr>
<?php
  }
?>
    </tbody>
  </table>