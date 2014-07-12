<?php

add_action('save_post', 'kkcart_save_details');


function kkcart_save_details(){

global $post;

$post_id = sanitize_text_field( $_POST['ID'] );

if(!$post_id) {
    return false;
}

if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
	return $post_id;
}

// 상품코드 포스트메타 입력 /////////////////////////////////////////////////////

$kingkongcart_prefix = KINGKONGCART_PRODUCT_PREFIX;
$rand_number = rand(1000,9999);
$product_code = $kingkongcart_prefix.$post_id.$rand_number;

if(!get_post_meta($post_id, "kingkongcart-product-code", true)){
	update_post_meta($post_id, "kingkongcart-product-code", $product_code);
}


// 상품 정보 포스트메타에 배열로 입력 /////////////////////////////////////////////
$data = array();
$data[0] = sanitize_text_field( $_POST['origin_price'] ); 		// 소비자 판매가격
$data[1] = sanitize_text_field( $_POST['results_price'] ); 	// 할인 적용 판매가격
$data[2] = sanitize_text_field( $_POST['discount'] );			// 할인율 %
$data[3] = sanitize_text_field( $_POST['discount_price'] );	// 할인가격
$data[4] = sanitize_text_field( $_POST['mileage'] );			// 적립금(마일리지) %
$data[5] = sanitize_text_field( $_POST['mileage_price'] );		// 적립금(마일리지) 원(점)
$data[6] = sanitize_text_field( $_POST['short_desc'] );		// 짤막소개
$data[7] = sanitize_text_field( $_POST['provide_price'] );		// 공급가(vat 포함)

$data = serialize($data);
update_post_meta($post_id, 'kingkongcart-product-info', $data);

// 옵션정보 포스트메타에 배열로 입력 /////////////////////////////////////////////
$option         = array();

$option_length 	= sanitize_text_field( $_POST['kkcart_total_number'] ); // 등록된 전체 옵션의 길이
$option_name 	= sanitize_text_field( $_POST['input_option'] );
for ($o=0; $o < $option_length; $o++) { 


	$option[$o]['main'] = array(
		'name' 			=> sanitize_text_field( $_POST['kkcart_option_name_'.$o] ), 	// 옵션명
		'plus_price' 	=> sanitize_text_field( $_POST['kkcart_plus_price_'.$o] ),
		'total_amount'	=> sanitize_text_field( $_POST['kkcart_total_amount_'.$o] ),
		'option_status' => sanitize_text_field( $_POST['kkcart_option_status_'.$o] )
	);

    // 2nd 옵션 설정값 저장
    $option_second_length[$o] = sanitize_text_field( $_POST['second_option_'.$o.'_length'] );
    $sub_total_amount = 0;
    for ($i=0; $i < $option_second_length[$o]; $i++){

    $option[$o]['sub'][$i] = array(
    	'name' 			=> sanitize_text_field( $_POST['kkcart_second_option_'.$o.'_'.($i+1)] ),
    	'plus_price'	=> sanitize_text_field( $_POST['kkcart_second_price_'.$o.'_'.($i+1)] ),
    	'total_amount'	=> sanitize_text_field( $_POST['kkcart_second_amount_'.$o.'_'.($i+1)] ),
    	'option_status' => sanitize_text_field( $_POST['kkcart_second_option_status_'.$o.'_'.($i+1)] )
    );

    $sub_total_amount += sanitize_text_field( $_POST['kkcart_second_amount_'.$o.'_'.($i+1)] );

    }
    if($option_second_length[$o] > 0){
    	$option[$o]['main']['total_amount'] = $sub_total_amount;
	}

}

$options = serialize($option);
update_post_meta($post_id, 'kingkongcart-product-option', $options);


require_once(ABSPATH.'wp-load.php');
require_once(ABSPATH.'wp-admin/includes/image.php');

global $wpdb;

$added_thumb_id = array();

$added_thumbnail_id = "";
$added_thumbnail_id = sanitize_text_field( $_POST['added_thumbnail_list'] );

if($added_thumbnail_id){
	$added_thumbnail_id = explode(",",$added_thumbnail_id);
	$added_thumbnail_count = count($added_thumbnail_id);

	$update_thumb = serialize($added_thumbnail_id);

	update_post_meta($post_id,"kingkongcart_added_thumbnail_id",$update_thumb);
}
else {
	$added_thumbnail_count = 0;
}

$thumb_tmp_name 	= $_FILES['added_thumb_file_name']['tmp_name'];
$thumb_real_name 	= $_FILES['added_thumb_file_name']['name'];
$upload_dir 		= wp_upload_dir();
$artDir 			= $upload_dir['baseurl']."/kingkongcart/";
$artDir				= str_replace(home_url(),"",$artDir);

	// Thread, Trash 처리 
	$args = array(
		'post_type'   => 'attachment',
		'numberposts' => -1,
		'post_status' => 'any',
		'post_parent' => $post_id,
		'exclude' => $added_thumbnail_id
		);

	$attachments = get_posts($args);

	if($attachments){
		foreach ($attachments as $attachment){
			if( strpos( $attachment->post_title, "kingkongcart-product-thumbnail-" ) !== false ){
				wp_delete_attachment( $attachment->ID );
			}
		}
	}
	wp_reset_postdata();
	/////////////////////////////////////////////////////////


if(!file_exists(ABSPATH.$artDir)) {
    mkdir(ABSPATH.$artDir);
}

for ($i=0; $i < count($thumb_tmp_name); $i++) { 

	$extpop = explode(".", $thumb_real_name[$i]);
	$ext = array_pop($extpop);

	$new_filename = "kingkongcart-product-thumbnail-".$post_id."-".($i+1+$added_thumbnail_count).".".$ext; // 썸네일 파일 네임 재정의

if (@fclose(@fopen($thumb_tmp_name[$i], "r"))) { // 파일이 실제 존재하는지 확인

    copy($thumb_tmp_name[$i], ABSPATH.$artDir.$new_filename);

    $siteurl = get_option('siteurl');
    $file_info = getimagesize(ABSPATH.$artDir.$new_filename);

    //wp_posts 정의
    $artdata = array();
    $artdata = array(
        'post_author' => 1, 
        'post_date' => current_time('mysql'),
        'post_date_gmt' => current_time('mysql'),
        'post_title' => $new_filename, 
        'post_status' => 'inherit',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_name' => sanitize_title_with_dashes(str_replace("_", "-", $new_filename)),                                            'post_modified' => current_time('mysql'),
        'post_modified_gmt' => current_time('mysql'),
        'post_parent' => $post_id,
        'post_type' => 'attachment',
        'guid' => $siteurl.'/'.$artDir.$new_filename,
        'post_mime_type' => $file_info['mime'],
        'post_excerpt' => '',
        'post_content' => ''
    );

    $uploads = wp_upload_dir();
    $save_path = $uploads['basedir'].'/kingkongcart/'.$new_filename;

    // 썸네일 posts 업로드
   
    $attach_id = wp_insert_attachment( $artdata, $save_path, $post_id );

    $added_thumb_id[$i] = $attach_id;

    // 썸네일과 메타 데이터를 생성
    if ($attach_data = wp_generate_attachment_metadata( $attach_id, $save_path)) {
        wp_update_attachment_metadata($attach_id, $attach_data);
    }

}
else {
    return false;
}

} // end for
	// 등록된 썸네일 아이디를 포스트 메타에 저장
	

	$added_thumbnail_ids = get_post_meta($post_id, 'kingkongcart_added_thumbnail_id', true);
	$each_thumb_id = unserialize($added_thumbnail_ids);

	if($each_thumb_id[0] == ""){
		$added_thumb = serialize($added_thumb_id);
		update_post_meta($post_id,"kingkongcart_added_thumbnail_id",$added_thumb);
	}
	else {
		$added_thumb = array_merge($added_thumb_id,$each_thumb_id);
		$added_thumb = implode("//", $added_thumb);
		$added_thumb = explode("//", $added_thumb);
		$added_thumb_merge = serialize($added_thumb);
		update_post_meta($post_id,"kingkongcart_added_thumbnail_id",$added_thumb_merge);
	}

} // function end


?>