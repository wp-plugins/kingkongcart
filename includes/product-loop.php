<?php

add_shortcode("kingkong_product_loop","kingkong_product_loop");

function kingkong_product_loop($attr){

$cat_id = $attr['cat'];

if($cat_id){
$term = get_term($cat_id, "section");
}
if($term != ""){

	$args = array(
		'post_type' => 'kkcart_product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'section',
				'field' => 'slug',
				'terms' => $term->slug
			)
		)

	);	

} else {
	$args = array(
		'post_type' => 'kkcart_product',
		'post_status' => 'publish',
		'posts_per_page' => -1
	);
}

$kkcart_products = new WP_Query( $args ); 

$options = get_kingkongcart_product_loop_options(); // 상품 loop 스타일링 옵션
$fonts_value = product_style_fonts($options->fonts);
$display_option = explode("-",$options->display);
$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
$mileage_status = $mileage_config['mileage_use'];

$content ='<div id="kingkongcart-product-loop" style="'.$fonts_value.'"><ul>';

?>




<?php

if( count($kkcart_products) != 0 ){

	$original_mileage_text = MILEAGE_TEXT;
	$original_currency_text = CURRENCY_TEXT;

	// Apply Filter
	$mileage_text = apply_filters("change_mileage_text", $original_mileage_text);
	$currency_text = apply_filters("change_currency_text", $original_currency_text);

	while ( $kkcart_products -> have_posts() ) {
		$kkcart_products->the_post();
		$post_id = $kkcart_products->post->ID;
		$thumbnail_ids = unserialize(get_post_meta($post_id,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'full');

		$info = get_product_info($post_id);	// 해당상품 가격 정보

		$each_product_option = "";


		for ($i=0; $i < count($display_option); $i++) { 
			

			switch($display_option[$i]){

				case "thumbnail" :
					$each_product_option .= "<li><a href='".get_permalink($post_id)."'><img src='".$thumbnail_url[0]."'></a></li>";
				break;

				case "title" :
					$each_product_option .= "<li style='text-align:".$options->title_justify."'><span style='font-weight:".$options->title_bold."; color:".$options->title_color."; font-size:".$options->title_size."'><a href='".get_permalink($post_id)."'>".get_the_title($post_id)."</a></span></li>";
				break;

				case "price" :
					$each_product_option .= "<li style='text-align:".$options->price_justify."'><span style='font-weight:".$options->price_bold."; color:".$options->price_color."; font-size:".$options->price_size."'>".number_format($info->final_price).$currency_text."</span></li>";
				break;

				case "mileage" :
					if($mileage_status == "T"){
						if($info->mileage_price){
						$each_product_option .= "<li style='text-align:".$options->mileage_justify."'><span style='font-weight:".$options->mileage_bold."; color:".$options->mileage_color."; font-size:".$options->mileage_size."'>".$mileage_text." : ".number_format($info->mileage_price)."</span></li>";
						}
					}
				break;

				case "shortdesc" :
					if($info->short_desc){
					$each_product_option .= "<li style='line-height:100%; text-align:".$options->shortdesc_justify."'><span style='font-weight:".$options->shortdesc_bold."; color:".$options->shortdesc_color."; font-size:".$options->shortdesc_size."'>".$info->short_desc."</span></li>";
					}
				break;

				case "discount" :
					if($info->discount_price){
					$each_product_option .= "<li style='text-align:".$options->discount_justify."'><span style='font-weight:".$options->discount_bold."; color:".$options->discount_color."; font-size:".$options->discount_size."; text-decoration:line-through'>".number_format($info->original_price).$currency_text."</span></li>";
					}
			}

		}
		

		$content .= '<li><ul class="each_product_method" style="max-width:'.$options->grid_width.'; height:auto;">'.$each_product_option.'</ul></li>';

	} 	// end while

}		// end if	
else {

	$content .= "<li>등록된 제품이 없습니다.</li>";
}
	$content .= "</ul></div>";

	$content = wpautop(trim($content));
	
	return $content;
}
?>