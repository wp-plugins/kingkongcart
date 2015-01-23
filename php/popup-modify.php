<?php

  date_default_timezone_set('Asia/Seoul');

  $popup_id           = $_POST['popup_id'];
  $popup_title        = $_POST['wpc_popup_title'];
  $start_date         = $_POST['wpc_popup_start_date'];
  $end_date           = $_POST['wpc_popup_end_date'];
  $start_hour         = $_POST['wpc_popup_start_hour'];
  $end_hour           = $_POST['wpc_popup_end_hour'];
  $start_minute       = $_POST['wpc_popup_start_minute'];
  $end_minute         = $_POST['wpc_popup_end_minute'];
  $responsive         = $_POST['wpc_popup_responsive'];

  $explode_sdate      = explode("-", $start_date);
  $start_year         = $explode_sdate[0];
  $start_month        = $explode_sdate[1];
  $start_day          = $explode_sdate[2];

  $explode_edate      = explode("-", $end_date);
  $end_year           = $explode_edate[0];
  $end_month          = $explode_edate[1];
  $end_day            = $explode_edate[2];

  $start_mktime       = mktime( $start_hour, $start_minute, 0, $start_month, $start_day, $start_year );
  $end_mktime         = mktime( $end_hour, $end_minute, 0, $end_month, $end_day, $end_year );

  $popup_status       = $_POST['wpc_popup_status'];
  $popup_style        = $_POST['wpc_popup_style'];
  $popup_width        = $_POST['wpc_popup_width'];
  $popup_height       = $_POST['wpc_popup_height'];
  $popup_position     = $_POST['wpc_popup_position'];
  $popup_detail_top   = $_POST['wpc_popup_detail_top'];
  $popup_detail_left  = $_POST['wpc_popup_detail_left'];
  $popup_url          = $_POST['wpc_popup_url'];

if(isset($_POST['wpc_popup_url_open'])){
  $popup_url_open     = $_POST['wpc_popup_url_open'];
} else {
  $popup_url_open     = null;
}

  $popup_session      = $_POST['wpc_popup_session'];
  $popup_upload_type  = $_POST['wpc_popup_upload_type'];

  if($popup_upload_type == "image"){
    $popup_content    = $_POST['wpc_popup_image_content'];
  } else {
    $popup_content    = $_POST['wpc_popup_editor'];
  }

  $popup = array(
    'status'      => $popup_status,
    'start'       => date("Y-m-d H:i:s", $start_mktime),
    'end'         => date("Y-m-d H:i:s", $end_mktime),
    'smktime'     => $start_mktime,
    'emktime'     => $end_mktime,
    'style'       => $popup_style,
    'width'       => $popup_width,
    'height'      => $popup_height,
    'position'    => $popup_position,
    'top'         => $popup_detail_top,
    'left'        => $popup_detail_left,
    'url'         => $popup_url,
    'url_open'    => $popup_url_open,
    'responsive'  => $responsive,
    'session'     => $popup_session,
    'upload'      => $popup_upload_type
  );

  $popup = serialize($popup);

  $args = array(
    'ID'            => $popup_id,
    'post_title'    => $popup_title
  );

  wp_update_post($args);

  update_post_meta($popup_id, "wpc_popup_options", $popup);
  update_post_meta($popup_id, "wpc_popup_content", $popup_content);

?>
<script>
location.href='?page=wpcoop_popup&view=popup_view&popup_id=<?php echo $popup_id;?>';
</script>