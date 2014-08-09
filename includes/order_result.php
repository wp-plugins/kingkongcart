<?php
/*
* Kingkongcart Order Result Page
*/

/****************************
* 0. 세션 시작				*
****************************/
session_start(); 					//주의:파일 최상단에 위치시켜주세요!!

get_header();

$dir = KINGKONGCART_ABSPATH;
$payments = unserialize(get_option("kingkong_payment"));
$payment_method 	= $payments['method'];
$yd_check 			= $payments['yd_check'];
$site_code 			= $payments['site_code'];
$site_key 			= $payments['site_key'];



if (is_user_logged_in()){

	global $current_user;
	get_currentuserinfo();

	$user_id = $current_user->ID;	
} else {
	$user_id = "";
}


switch($payment_method){
/*
	case "KCP" :
	include $dir.'/KCP/cfg/site_conf_inc.php'; 	// KCP 결제 Config
	include $dir.'/KCP/order.php';				// KCP 결제폼
	$button = '<input type="button" value="결제하기" onclick="return jsf__pay(this.form);">';
	break;
*/
	case "INICIS" :
	include $dir.'payment/INICIS/order_result.php';			// INICIS 결과페이지
	break;

}

	// 지불 성공 이라면
	if($kingkongcart_pay_result == 00){

	/*
	*	처리 해야 할것들
	*	1. order list table 로 해당 주문 내역 전송 처리상태 까지 함께 전송 (solved)
	*	2. 옵션 재고 처리 기능
	*	3. 구매 감사 이메일 전송 기능
	*	4. 관리자에게 신규주문 이메일 전송 기능
	*
	*/


		switch($kingkongcart_pay_method){

			case "VCard" :

				$paid_method = "신용카드(ISP)";
				$paid_status = 1;

			break;

			case "Card" :

				$paid_method = "신용카드(안심클릭)";
				$paid_status = 1;

			break;

			case "OCBPoint" :

				$paid_method = "OK 캐시백 포인트";
				$paid_status = 1;

			break;

			case "DirectBank" : //은행계좌이체

				$paid_method = "은행계좌이체";
				$paid_status = 1;

			break;

			case "HPP" : // 핸드폰 결제

				$paid_method = "핸드폰";
				$paid_status = 1;

			break;

			case "VBank" : // 무통장입금(가상계좌)

				$paid_method = "무통장입금(가상계좌)";
				$paid_status = 0;

			break;

			case "PhoneBill" :

				$paid_method = "폰빌전화결제";
				$paid_status = 1;

			break;

			case "Culture" : // 문화 상품권 결제

				$paid_method = "문화 상품권";
				$paid_status = 1;

			break;

			case "TEEN" : // 틴캐시(TeenCash) 결제

				$paid_method = "탠캐시(TeenCash)";
				$paid_status = 1;

			break;

			case "DGCL" :

				$paid_method = "스마트 문상";
				$paid_status = 1;

			break;

			case "BCSH" :

				$paid_method = "도서문화 상품권";
				$paid_status = 1;

			break;

			case "HPMN" : // 해피머니 상품권

				$paid_method = "해피머니 상품권";
				$paid_status = 1;

			break;

			case "MMLG" :

				$paid_method = "M 마일리지";
				$paid_status = 1;

			break;

			case "YPAY" : // 옐로페이

				$paid_method = "옐로페이";
				$paid_status = 1;

			break;

		}

	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";

	$wpdb->insert( 
		$order_table, 
		array( 
			'pid' 				=> $kingkongcart_pay_pid,
			'status'			=> $paid_status,
			'kind'				=> $paid_method,
			'pname'				=> $kingkongcart_pay_pname,
			'order_id'			=> $user_id,
			'order_price' 		=> $kingkongcart_pay_price,
			'receive_name'		=> $kingkongcart_pay_receive_name,
			'receive_contact' 	=> $kingkongcart_pay_receive_contact,
			'address_doro'		=> $kingkongcart_pay_address_doro,
			'address_jibun'		=> $kingkongcart_pay_address_jibun,
			'address_detail'	=> $kingkongcart_pay_address_detail,
			'mktime'			=> mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
			'order_date'		=> date("Y-m-d H:i:s")
		) 
	);

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];

	$orders = $wpdb->get_row("SELECT ID from $order_table where pid = '".$kingkongcart_pay_pid."' limit 1");

	update_order_meta($orders->ID, "buying_product", serialize($_SESSION['temp_kingkongcart_product']) );
	update_order_meta($orders->ID, "shipping_cost", $kingkongcart_pay_shippig_cost ); 	// 배송비 저장
	update_order_meta($orders->ID, "order_memo", $kingkongcart_pay_memo);				// 배송메모


	$auto_quantity 	= get_option("kingkongcart_auto_quantity");

	if($auto_quantity == "T"){
/*
*
* 주문시 재고 처리 로직 시작 ///////////////////////////////////////////////////////////////////////////////////////////////////
*
*/
	$all_products = $_SESSION['temp_kingkongcart_product'];

		for ($i=0; $i < count($all_products); $i++) { 

			// 주문상품 제고 처리 시작

			// 첫번째 옵션명, 두번째 옵션명
			$first_option_name 	= $all_products[$i]['first']['name'];
			$second_option_name = $all_products[$i]['second']['name'];

			// 개개 메타값을 불러온다.
			$poptions = get_post_meta($all_products[$i]['product_id'], 'kingkongcart-product-option', true );
			$poptions = unserialize($poptions);

			for ($o=0; $o < count($poptions); $o++) { 
				$main_option_name 	= $poptions[$o]['main']['name'];
				$main_option_price	= $poptions[$o]['main']['plus_price'];
				$main_option_amount = $poptions[$o]['main']['total_amount'];
				$main_option_status = $poptions[$o]['main']['option_status'];

				// 두번째 옵션의 개수
				$sub_option_count	= count($poptions[$o]['sub']);

				// 첫번째 옵션 명이 일치 한다면
				if($main_option_name == $first_option_name){
					
					// 첫번째 전체 제고 개수에서 주문 수량만큼을 제외시킨다.
					$poptions[$o]['main']['total_amount'] = $main_option_amount - $all_products[$i]['quantity'];


					// 두번째 옵션 리스트 만큼 반복
					for ($s=0; $s < $sub_option_count; $s++) { 
						
						$sub_option_name 	= $poptions[$o]['sub'][$s]['name'];
						$sub_option_price	= $poptions[$o]['sub'][$s]['plus_price'];
						$sub_option_amount	= $poptions[$o]['sub'][$s]['total_amount'];
						$sub_option_status	= $poptions[$o]['sub'][$s]['option_status'];

						// 두번째 옵션 명이 일치 한다면
						if($second_option_name == $sub_option_name){

							// 두번째 전체 재고 개수에서 주문 수량만큼을 감한다.
							$poptions[$o]['sub'][$s]['total_amount'] = $sub_option_amount - $all_products[$i]['quantity'];

						} 

					}					

				}

			}

			// 변경된 옵션 재고수량을 다시 업데이트 한다.
			$poptions = serialize($poptions);
			update_post_meta($all_products[$i]['product_id'], 'kingkongcart-product-option', $poptions );
		}

/*
*
* 주문시 재고 처리 로직 끝 /////////////////////////////////////////////////////////////////////////////////////////////////////
*
*/
		} // if auto_quantity == 'T'


	if($mileage_status == "T"){
		update_order_meta($orders->ID, "mileage", $kingkongcart_pay_mileage );				// 마일리지 저장
		update_order_meta($orders->ID, "using_mileage", $kingkongcart_pay_input_mileage);	// 사용한 마일리지
		// 현재마일리지를 불러와 입력 한 마일리지를 공제 후 다시 업데이트 한다.
		$current_user_mileage = get_user_meta($user_id, "kingkong_mileage", true);
		$calculated_milage = $current_user_mileage - $kingkongcart_pay_input_mileage;
		update_user_meta($user_id, "kingkong_mileage", $calculated_milage);
	}
/* wp_mail 관리자 전송 */

	//$admin_email = get_option( 'admin_email' );
	//$email_contents = "<p>신규주문이 들어왔습니다.</p><p>결제금액 :".$kingkongcart_pay_price."</p>";
	//wp_mail($admin_email, '신규주문이 추가 되었습니다.', $email_contents);


	$_SESSION['temp_kingkongcart_product'] = ""; //세션 제거

?>
	<div class="entry-content">
	<h2>감사합니다. 정상적으로 주문처리 되었습니다.</h2>
	<br>
	<div id="order-result">
		<h3>수취자 정보</h3>
		<table>
			<tr>
				<th>수취자명</th>
				<td><?php echo $kingkongcart_pay_receive_name;?></td>
			</tr>
			<tr>
				<th>연락처</th>
				<td><?php echo $kingkongcart_pay_receive_contact;?></td>
			</tr>
		</table>

		<h3>주문내역</h3>
		<table>
			<tr>
				<th>주문번호</th>
				<td><?php echo $kingkongcart_pay_pid;?></td>
			</tr>
			<tr>
				<th>주문상품</th>
				<td><?php echo $kingkongcart_pay_pname;?></td>
			</tr>
			<tr>
				<th>결제방법</th>
				<td><?php echo $paid_method;?></td>
			</tr>
			<tr>
				<th>결제금액</th>
				<td>
					<?php echo number_format($kingkongcart_pay_price);?>

<?php 
	if($paid_status == 0){

		echo "(입금예정)";
	}
?>

				</td>
			</tr>

<?php
	if($paid_status == 0){
?>

			<tr>
				<th>입금계좌</th>
				<td><?php echo get_bank_name($kingkongcart_pay_bank);?>, 계좌번호 : <?php echo $kingkongcart_pay_account; ?></td>
			</tr>

			<tr>
				<th>입금예정일</th>
				<td><?php echo $kingkongcart_pay_bank_date;?></td>
			</tr>

			<tr>
				<th>예금자명</th>
				<td><?php echo $kingkongcart_pay_send_name;?></td>
			</tr>

<?php
	}
?>			
		</table>

	</div>
	<input type="button" value="확인" onclick="location.href='<?php echo home_url();?>';">
	</div>

<?php
	} else {
		echo "<script>location.href='".home_url()."';</script>";
	}
get_footer();
?>
