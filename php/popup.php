<?php
function wpcoop_popup(){
  $popup_main   = WPCOOP_ABSPATH."php/popup-list.php";
  $popup_new    = WPCOOP_ABSPATH."php/popup-new.php";
  $popup_proc   = WPCOOP_ABSPATH."php/popup-proc.php";
  $popup_view   = WPCOOP_ABSPATH."php/popup-view.php";
  $popup_modify = WPCOOP_ABSPATH."php/popup-modify.php";
?>
<div id="wpc_popup_wrap" style="padding-right:15px">
<?php

  if(isset($_GET['view'])){
    switch($_GET['view']){
      case "create_new" :
        if(isset($_POST['submit'])){
          include_once($popup_proc);
        } else {
          include_once($popup_new);
        }
      break;

      case "popup_view" :
        if(isset($_POST['submit'])){
          include_once($popup_modify);
        } else {
          include_once($popup_view);
        }
      break;

      default :
        include_once($popup_main);
      break;
    }
  } else {
    include_once($popup_main);
  }
?>
</div>
<?php
}
?>