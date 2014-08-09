<?php
	
	global $wpdb, $current_user;
	$user_id = $current_user->ID;
	$user_name = $current_user->display_name;

	$page_type = $_GET['page_type'];
	$product_id = $_GET['product_id'];
	$result_count = 0;

	// 구매한 이력이 있다면
	$order_table = $wpdb->prefix."kingkong_order";
	$orders = $wpdb->get_results("SELECT ID from $order_table where order_id = $user_id where status = '3' ");
	
	if($orders){

		foreach ($orders as $order) {
			$buying_product = unserialize(get_order_meta($order->ID, "buying_product"));

			for ($i=0; $i < count($buying_product); $i++) { 
				if($product_id == $buying_product[$i]['product_id']){
					$result_count = 1;
				}
			}

		}

		if($result_count > 0){

			$file_name = get_post_meta($product_id, "kingkong_download", true);
			$file_name = trim($file_name);
			$file_path = ABSPATH."download_product/".$product_id."/".$file_name;
		        header('Cache-Control: must-revalidate');
		        header('Pragma: public');
		        header("Content-type: application/octet-stream");
		        header('Content-Transfer-Encoding: binary');
		        header("Content-Disposition: attachment; filename=\"$file_name\"");
		        header("Content-length:".(string)(filesize($file_path)));
		        ob_clean();
		    	flush();
		    	readfile($file_path);
		}

	} else {
		return false;
	}



?>