<?php
session_start();

add_shortcode("kingkongcart_order","kingkongcart_order");

function kingkongcart_order($attr){



/* 	single product 의 구매 상품을 post 로 불러와 배열을 만들고
* 	해당 상품을 주문해야 한다.
*/


$dir 					= KINGKONGCART_ABSPATH;
$payments 				= unserialize(get_option("kingkong_payment"));
$payment_method 		= $payments['method'];
$yd_check 				= $payments['yd_check'];
$site_code 				= $payments['site_code'];
$site_key 				= $payments['site_key'];
$paykind				= $payments['paykind'];
$private_account 		= $payments['private_account'];
$private_account_bank 	= $payments['private_account_bank'];
$private_account_number = $payments['private_account_number'];

switch($payment_method){

	case "INICIS" :

		$inicis_key_id 	= $payments['inicis_key_id'];
		$inicis_key_pwd = $payments['inicis_key_pwd'];

	break;
}


$option1 	= sanitize_text_field( $_POST['option1'] );
$option1 	= explode("*-*",$option1);
$option2 	= sanitize_text_field( $_POST['option2'] );
$option2 	= explode("*-*",$option2);
$quantity 	= sanitize_text_field( $_POST['quantity'] );


$info = unserialize(get_post_meta(sanitize_text_field( $_POST['post_id'] ), 'kingkongcart-product-info', true));

$original_price 	= $info[0];	// 소비자 판매가격
$results_price 		= $info[1];	// 할인 적용 판매가격
$discount_price		= $info[3];	// 할인가격
$mileage_price		= $info[5];	// 적립금(마일리지) 원(점)
$provide_price		= $info[7];	// 공급가(vat포함)	

if($results_price){
		$last_price = $results_price;
} else {
	$last_price = $original_price;
}


$cart[0] = array(
	'product_id'  => sanitize_text_field( $_POST['post_id'] ),
	'quantity' => $quantity,
	'discount_price' => $discount_price,
	'price'	=> $last_price,
	'provide_price' => $provide_price,
	'mileage_price' => $mileage_price,
	'first' => array(
		'id' 		 => $option1[0],
		'name'		 => $option1[1],
		'plus_price' => $option1[2]
	),
	'second' => array(
		'id' 		 => $option2[0],
		'name' 		 => $option2[1],
		'plus_price' => $option2[2]
	)
);

$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
$mileage_status = $mileage_config['mileage_use'];

if($option1[0] != ""){

$_SESSION['temp_kingkongcart_product'] = $cart;

} else {
	if(!$_SESSION['temp_kingkongcart_product']){
		$_SESSION['temp_kingkongcart_product'] = "";
	} else {
		$_SESSION['temp_kingkongcart_product'] = get_kingkong_cart();
	}
}

if (!is_user_logged_in() and sanitize_text_field( $_POST['type'] ) == false){ //로그인하지 않은 상태

	$args = array(
        'echo' => true,
        'redirect' => site_url( $_SERVER['REQUEST_URI'] ), 
        'form_id' => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => NULL,
        'value_remember' => false );

	wp_login_form( $args );


?>
	<input type="button" value="회원가입" onclick="location.href='<?php echo get_the_permalink(KINGKONG_JOIN);?>';">
	<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
		<input type="hidden" name="type" value="guest">
		<input type="submit" value="비회원 주문">
	</form>

<?php
} else {
	
if (is_user_logged_in()){

	global $current_user;
	get_currentuserinfo();

	$user_id 			= $current_user->ID;
	$user_name 		 	= $current_user->user_login;
	$user_display_name	= $current_user->display_name;
	$user_email			= $current_user->user_email;

	$user_info = get_user_meta($user_id, "kingkong_user_info", true);
	$user_info = unserialize($user_info);
	$tel = $user_info['tel'];
	$phone = $user_info['phone'];
	$address_doro = $user_info['address_doro'];
	$address_jibun = $user_info['address_jibun'];
	$address_else = $user_info['address_else'];
	$zipcode = $user_info['zipcode'];
	$zipcode = explode("-",$zipcode);
	$zipcode1 = $zipcode[0];
	$zipcode2 = $zipcode[1];

	$tel = explode("-",$tel);
	$tel1 = $tel[0];
	$tel2 = $tel[1];
	$tel3 = $tel[2];
	$phone = explode("-",$phone);
	$phone1 = $phone[0];
	$phone2 = $phone[1];
	$phone3 = $phone[2];

	// 보유 적립금
	$kingkong_mileage = get_user_meta($user_id, "kingkong_mileage", true);
	if(!$kingkong_mileage){
		$kingkong_mileage = 0;
	}
}

	/*
	* 단일 상품 바로구매로 들어온다면 세션을 불러오고 나머지는 카트를 불러온다.
	*/

	if($_SESSION['temp_kingkongcart_product'] != ""){
		$cart = $_SESSION['temp_kingkongcart_product'];
	} else {
		$cart = get_kingkong_cart(); // 장바구니 정보를 불러온다.
		$_SESSION['temp_kingkongcart_product'] = $cart;
	}


	@usort($cart);

	$mileage_text = apply_filters("change_mileage_text", MILEAGE_TEXT);
	$currency_text = apply_filters("change_currency_text", CURRENCY_TEXT);

	if(count($cart) > 1){
		$payment_insert_title = get_the_title($cart[0]['product_id'])." 외 ".( count($cart) - 1)."건";
	} else {
		$payment_insert_title = get_the_title($cart[0]['product_id']);
	}

?>

	<div id="kingkongcart-order">

		<h3>상품정보</h3>
		<div class="mypage-div">
			<table>
				<thead>
					<tr>
						<th>상품명</th>
						<th>상품가격</th>
						<th>수량</th>
<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
						<th><?php echo $mileage_text;?></th>
<?php
		}
	}
?>
						<th>합계</th>
						<th>선택</th>
					</tr>
				</thead>
				<tbody>

<?php

	if($cart){

		for ($i=0; $i < count($cart); $i++) { 

			$product_id 		= $cart[$i]['product_id'];
			$option1 			= $cart[$i]['first']['name'];
			$option1_plus_price = $cart[$i]['first']['plus_price'];
			$option2			= $cart[$i]['second']['name'];
			$option2_plus_price = $cart[$i]['second']['plus_price'];
			$quantity			= $cart[$i]['quantity'];

			$info = get_product_info($product_id); //상품 기본 정보 불러옴

			$each_total_mileage 	= $info->mileage_price * $quantity;
			$each_total_price			= ($info->final_price * $quantity) + ($option1_plus_price * $quantity) + ($option2_plus_price * $quantity);


			$thumbnail_ids = unserialize(get_post_meta($product_id,"kingkongcart_added_thumbnail_id", true));
			$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');

			$total_price 	+= $each_total_price;
			$total_mileage 	+= $each_total_mileage;

?>
					<tr>
						<td style="text-align:left;">
							<ul class="cart-thumbnail-display">
								<li><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></li>
								<li>
									<ul>
										<li>
											상품명 : <a href="<?php echo get_the_permalink($product_id);?>"><?php echo get_the_title($product_id);?></a>
										</li>
										<li>
											옵션 1 : <?php echo $option1;?> (추가금액:<?php echo number_format($option1_plus_price).$currency_text;?>)
										</li>
<?php
	if($option2){
?>
										<li>
											옵션 2 : <?php echo $option2;?> (추가금액:<?php echo number_format($option2_plus_price).$currency_text;?>)
										</li>
<?php
}
?>
									</ul>
								</li>
							</ul>
						</td>
						<td><?php echo $info->final_price.$currency_text;?></td>
						<td>
							<?php echo $quantity;?>
						</td>
<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
						<td><?php echo $each_total_mileage;?></td>
<?php
		}
	}
?>				
						<td><?php echo $each_total_price.$currency_text;?></td>
						<td class="cart-each-choice">
<?php 
	if(count($cart) > 1){
?>
							<li onclick="kingkongcart_remove_cart(<?php echo $i;?>);">삭제</li>
<?php
	}
?>
						</td>
					</tr>

<?php
		} // end for

		$shipping_cost = check_shipping_cost($total_price);

		if($shipping_cost == 0){
			$shipping_cost = "무료배송";
			$shipping_cost_data = 0;
			$with_shipping_price = $total_price - sanitize_text_field( $_POST['using_mileage'] );
		} else {
			$shipping_cost_data = $shipping_cost;
			$with_shipping_price = $total_price + $shipping_cost - sanitize_text_field( $_POST['using_mileage'] );
			$shipping_cost = number_format($shipping_cost).$currency_text;
		}

?>


				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" class="cart-tfoot-td">총 구매금액 : <?php echo number_format($with_shipping_price).$currency_text;?> 
<?php
	if (sanitize_text_field( $_POST['using_mileage'] )){
?>
						(적립금 <?php echo number_format($_POST['using_mileage']);?>원 사용)
<?php
	}
?>
						(배송비:<?php echo $shipping_cost;?> 포함)
<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
 (적립금 <?php echo number_format($total_mileage);?>)
 <?php
 		}
 	}
 ?>
						</td>
					</tr>
				</tfoot>
			</table>

<?php 
	} // end if
	else {
?>
		<tr>
			<td colspan="6">장바구니가 비어있습니다.</td>
		</tr>
	</table>

<?php
	}
?>
		</div>

<?php

		switch($payment_method){
			case "KCP" :
			include $dir.'payment/KCP/cfg/site_conf_inc.php'; 	// KCP 결제 Config
			include $dir.'payment/KCP/order.php';				// KCP 결제폼
			$button = '<input type="button" class="kingkongtheme_button" value="결제하기" onclick="return jsf__pay(this.form);">';
			break;

			case "INICIS" :
			include $dir.'payment/INICIS/order.php';			// INICIS 결제폼
			$button = "<input type='submit' class='kingkongtheme_button' value='결제하기'>";
			break;
		}

?>

		<input type="hidden" name="status_type" value="order_result">
		<input type="hidden" name="shipping_cost" value="<?php echo $shipping_cost_data;?>">
		<input type="hidden" name="total_mileage" value="<?php echo $total_mileage;?>">
		<input type="hidden" name="with_shipping_price" value="<?php echo $with_shipping_price;?>">
		<input type="hidden" name="product_desc_title" value="<?php echo $payment_insert_title;?>">

		<h3>주문자 정보</h3>
		<div class="kingkongcart-order-info">


			<table>
				<tr>
					<th>주문하시는 분</th>
					<td><input type="text" name="order_name" class="order_name" value="<?php echo $user_display_name;?>" onchange="change_order_value(this.value, 'name', '<?php echo $payment_method;?>');"></td>
				</tr>
				<tr>
					<th>연락처</th>
					<td><input type="text" name="order_tel1" class="kingkong_input_s" value="<?php echo $phone1;?>"> - <input type="text" name="order_tel2" class="kingkong_input_s" value="<?php echo $phone2;?>"> - <input type="text" name="order_tel3" class="kingkong_input_s" onchange="change_order_value('','tel', '<?php echo $payment_method;?>');" value="<?php echo $phone3;?>"></td>
				</tr>
				<tr>
					<th>이메일</th>
					<td><input type="text" name="order_email" class="order_email" value="<?php echo $user_email;?>" onchange="change_order_value(this.value, 'email', '<?php echo $payment_method;?>');"></td>
				</tr>
			</table>
		</div>

		<h3>배송지 정보</h3>
		<div class="kingkongcart-shipping-info">
			<table>
				<tr>
					<th>배송지</th>
					<td>
						<ul class="postcode_input_ul">
							<li><input type="checkbox" name="equal_info"> 주문자와 동일</li>
							<li><input type="text" name="postcode1" class="kingkong_input_s" value="<?php echo $zipcode1;?>"> - <input type="text" name="postcode2" class="kingkong_input_s" value="<?php echo $zipcode2;?>"> <input type="button" class="kingkongtheme_button" value="우편번호 검색" onclick="showDaumPostcode();"></li>
							<li><input type="text" name="address" class="kingkong_input_l" value="<?php echo $address_jibun;?>"> 기본 주소</li>
							<li><input type="text" name="else_address" class="kingkong_input_l" value="<?php echo $address_else;?>"> 나머지주소</li>
						</ul>
						<div id="layer" style="display:none;border:5px solid;position:fixed;width:400px;height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode()">
						</div>
					</td>
				</tr>
				<tr>
					<th>받으시는 분</th>
					<td><input type="text" name="shipping_name"></td>
				</tr>
				<tr>
					<th>연락처</th>
					<td><input type="text" name="shipping_tel1" class="kingkong_input_s"> - <input type="text" name="shipping_tel2" class="kingkong_input_s"> - <input type="text" name="shipping_tel3" class="kingkong_input_s"></td>
				</tr>
				<tr>
					<th>배송메모</th>
					<td><textarea></textarea></td>
				</tr>
<?php
	if(sanitize_text_field( $_POST['type'] ) != false){
?>
				<tr>
					<th>개인정보동의</th>
					<td>
						<ul class="private_info_ul">
							<li><textarea name="order_memo"></textarea></li>
							<li><input type="radio"> 약관에 동의합니다. <input type="radio"> 동의하지 않습니다.</li>
						</ul>
					</td>
				</tr>
<?php
	}
?>
			</table>
		</div>
<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
		<h3><?php echo $mileage_text;?> 사용</h3>
		<div class="kingkongcart-mileage-use">
			<table>
				<tr>
					<th>사용할 적립금</th>
					<td><input type="text" name="input_mileage" value="<?php echo $_POST['using_mileage'];?>"> <input type="button" class="kingkongtheme_button" value="적립금 사용" onclick="use_mileage(<?php echo $user_id;?>);"> 
						(보유적립금: <?php echo number_format($kingkong_mileage);?>원
<?php
	if(sanitize_text_field( $_POST['using_mileage'] )){
?>
						- <?php echo number_format(sanitize_text_field( $_POST['using_mileage'] ));?>원
<?php
	}
?>
						)
					</td>
				</tr>
			</table>
		</div>
<?php
		}
	}
?>
<?php
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
		<h3>쿠폰 사용</h3>
		<div class="kingkongcart-coupon-use">
			<table>
				<tr>
					<th>사용할 쿠폰 선택</th>
					<td><input type="text"> <input type="button" class="kingkongtheme_button" value="쿠폰선택"></td>
				</tr>
			</table>
		</div>
<?php 
		}
?>

		<h3>결제수단</h3>
		<div class="kingkongcart-coupon-use">
			<table>
				<tr>
					<th>결제수단 선택</th>
					<td>
<?php
	
	if($private_account == "T"){

		echo "<input type='radio' name='selectpaymethod' class='selectpaymethod' id='private_account' value='private_account'> 무통장 입금 ";

	}

	for ($i=0; $i < count($paykind); $i++) { 
		echo "<input type='radio' name='selectpaymethod' class='selectpaymethod' value='".$paykind[$i]."'> ".get_paykind_option_name($paykind[$i])." ";
	}
?>
					<input type="hidden" name="gopaymethod" value="Card">
					</td>
				</tr>
			</table>
		</div>
		<div class="kingkongcart-coupon-use" id="private_account_select">

		</div>
		<div class="kingkongcart-order-buttons">
			<?php echo $button;?>
			<input type="button" class="kingkongtheme_button" value="주문취소" onclick="history.back();">
		</div>
	</form>
	<form method="post" id="use_mileage_form">
		<input type="hidden" name="using_mileage" value="<?php echo sanitize_text_field( $_POST['using_mileage'] );?>">
		<input type="hidden" name="without_shipping_cost" value="<?php echo $total_price;?>">
	</form>
	</div>
<?php

	for ($i=0; $i < count($private_account_bank); $i++) { 
		if($private_account_number[$i] != ""){
			$option_value .= "<option>".$private_account_bank[$i]."(계좌번호 : ".$private_account_number[$i].")</option>";
		}
	}

?>
<script>

	jQuery(".selectpaymethod").click(function(){
		if(jQuery("#private_account").prop("checked") == true ){
			jQuery("#private_account_select").html("<h3>결제은행 선택</h3><table><tr><th>은행선택</th><td><select name='private_account_select'><?php echo $option_value;?></select></td></tr><tr><th>입금자명</th><td><input type='text' name='private_account_name'> * 입금자명이 다를시 주문 및 배송에 차질이 있을 수 있으니 반드시 정확하게 기입하시기 바랍니다.</td></tr></table>");
			jQuery(".kingkongcart-order-buttons").html("<input type='button' class='kingkongtheme_button' value='결제하기' onclick='private_pay();'> <input type='button' class='kingkongtheme_button' value='주문취소' onclick='history.back();'>");
			jQuery(".kingkong_order_form").attr("id", "private_order_form");
			jQuery(".kingkong_order_form").attr("action", "<?php echo get_the_permalink(KINGKONG_ORDER_RESULT);?>");
		} else {
			jQuery("#private_account_select").html("");
			jQuery(".kingkongcart-order-buttons").html("<input type='submit' class='kingkongtheme_button' value='결제하기'> <input type='button' class='kingkongtheme_button' value='주문취소' onclick='history.back();'>");
		}
	});

</script>

<?php
	include "zipcode.php";
} // end else is_user_logged_in()
}



















?>