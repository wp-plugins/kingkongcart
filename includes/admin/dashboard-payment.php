<?php

if(sanitize_text_field( $_POST['form_status'] )){

	$payment = array();
	$payment['method'] 					= $_POST['payment_method'];
	$payment['yd_check'] 				= $_POST['yd_check'];
	$payment['paykind'] 				= $_POST['paykind'];
	$payment['private_account'] 		= $_POST['private_account'];
	$payment['private_account_bank'] 	= $_POST['private_account_bank'];
	$payment['private_account_number']	= $_POST['private_account_number']; 
	$payment['inicis_key_id']			= $_POST['inicis_key_id'];
	$payment['inicis_key_pwd']			= $_POST['inicis_key_pwd'];
	

	$payment = serialize($payment);

	update_option("kingkong_payment", $payment);
}

$get_payments 			= unserialize(get_option("kingkong_payment"));
$get_payment_method 	= $get_payments['method'];
$yd_check 				= $get_payments['yd_check'];
$site_code 				= $get_payments['site_code'];
$site_key 				= $get_payments['site_key'];
$paykind				= $get_payments['paykind'];
$private_account 		= $get_payments['private_account'];
$private_account_bank 	= $get_payments['private_account_bank'];
$private_account_number = $get_payments['private_account_number'];
$inicis_key_id 			= $get_payments['inicis_key_id'];
$inicis_key_pwd			= $get_payments['inicis_key_pwd'];


for ($i=0; $i < count($paykind); $i++) { 
	$pay[$paykind[$i]] = "checked";
}

switch($yd_check){

	case 1:
		$yd_real = "checked";
		$yd_test = "";
	break;

	case 0:
		$yd_real = "";
		$yd_test = "checked";
	break;

	default :
		$yd_real = "";
		$yd_test = "checked";
	break;
}

switch($private_account){

	case "T" :
		$private_account_true = "checked";
	break;

	case "F" :
		$private_account_false = "checked";
	break;

	default :
		$private_account_false = "checked";
	break;

}

$plugins_url = KINGKONGCART_PLUGINS_URL;
$plugins_url = explode("plugins",$plugins_url);
$plugins_url[1] = str_replace("/", "", $plugins_url[1]);
$absolute_url = str_replace(home_url('/'), "", $plugins_url[0]);
$dir = ABSPATH.$absolute_url."plugins/".$plugins_url[1]."/payment";

$payment_method = scandir($dir, 1);

for ($i=0; $i < count($payment_method); $i++) { 
 	if($payment_method[$i] != "." and $payment_method[$i] != ".." and strpos($payment_method[$i],".") === false ){
 		if($payment_method[$i] == $get_payment_method){
 			$payment_option .= "<option selected>".$payment_method[$i]."</option>";
 		}
 		else {
 			$payment_option .= "<option>".$payment_method[$i]."</option>";
 		}
 	}
 }

$inicis_key_dir 	= ABSPATH.$absolute_url."plugins/".$plugins_url[1]."/payment/INICIS/key";
$inicis_key_count 	= scandir($inicis_key_dir, 1);

for ($i=0; $i < count($inicis_key_count); $i++) { 
 	if($inicis_key_count[$i] != "." and $inicis_key_count[$i] != ".." and strpos($inicis_key_count[$i],".") === false ){
 		if($inicis_key_count[$i] == $inicis_key_count){
 			$inicis_key_option .= "<option selected>".$inicis_key_count[$i]."</option>";
 		}
 		else {
 			$inicis_key_option .= "<option>".$inicis_key_count[$i]."</option>";
 		}
 	}
 }


switch($get_payment_method){

	case "INICIS" :

	$input_screat_value = "<tr><td>KEY 아이디</td><td><select name='inicis_key_id'>".$inicis_key_option."</select> *이니시스로 부터 제공받은 키 폴더를 kingkongcart/payment/INICIS/key/밑에 업로드 하시면 자동으로 리스트에 표기됩니다.</td></tr><tr><td>KEY 패스워드</td><td><input type='text' name='inicis_key_pwd' value='".$inicis_key_pwd."'></td></tr>";

	break;

}


?>
<ul>
	<li><span class="dashboard-content-title">결제 설정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		결제사를 선택하시고 인증받으신 코드와 키값을 입력하셔야 동작합니다.<br>
		결제사 등록은 개인의 몫이며, 킹콩카트와는 무관함을 알려드립니다. (결제사 등록은 각 회사에 문의하시기 바랍니다.)
		</span>
	</li>
</ul>
<form method="POST">
	<input type="hidden" name="form_status" value="1">
	<table style="padding-top:10px; padding-left:10px">
		<tr style="height:40px" class="pg_choice">
			<td>결제사 선택</td>
			<td><select name="payment_method"><?php echo $payment_option;?></select></td>
		</tr>
		<?php echo $input_screat_value;?>
		<tr style="height:40px">
			<td>연동여부</td>
			<td><input type="radio" name="yd_check" value="1" <?php echo $yd_real;?>>실제사용 <input type="radio" name="yd_check" value="0" <?php echo $yd_test;?>>테스트</td>
		</tr>
		<tr style="height:40px">
			<td>결제수단 선택</td>
			<td>
				<table>
					<tr>
						<td><input type="checkbox" name="paykind[]" value="Card" <?php echo $pay['Card'];?>>신용카드</td>
						<td><input type="checkbox" name="paykind[]" value="HPP" <?php echo $pay['HPP'];?>>핸드폰결제</td>
						<td><input type="checkbox" name="paykind[]" value="DirectBank" <?php echo $pay['DirectBank'];?>>실시간 계좌이체</td>
						<td><input type="checkbox" name="paykind[]" value="VBank" <?php echo $pay['VBank'];?>>무통장입금(가상계좌)</td>
						<td><input type="checkbox" name="paykind[]" value="PhoneBill" <?php echo $pay['PhoneBill'];?>>받는전화 결제</td>
					</tr>
					<tr>
						<td><input type="checkbox" name="paykind[]" value="OCBPoint" <?php echo $pay['OCBPoint'];?>>OK 캐쉬백포인트 결제</td>
						<td><input type="checkbox" name="paykind[]" value="Ars1588Bill" <?php echo $pay['Ars1588Bill'];?>>1588 전화 결제</td>
						<td><input type="checkbox" name="paykind[]" value="Culture" <?php echo $pay['Culture'];?>>문화상품권 결제</td>
						<td><input type="checkbox" name="paykind[]" value="BCSH" <?php echo $pay['BCSH'];?>>도서문화 상품권 결제</td>
						<td><input type="checkbox" name="paykind[]" value="HPMN" <?php echo $pay['HPMN'];?>>해피머니 상품권 결제</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="height:40px">
			<td>개인통장입금 사용</td>
			<td><input type="radio" name="private_account" value="T" <?php echo $private_account_true;?>> 사용 <input type="radio" name="private_account" value="F" <?php echo $private_account_false;?>> 미사용 <span style="color:gray">* 개인통장입금은 결제대행사를 걸치지 않고 회사 또는 개인의 계좌로 무통장 거래시 쓰여집니다.</span></td>
		</tr>
		<tr style="height:40px">
			<td>은행 계좌정보</td>
			<td>
				<table>
					<tr>
						<th>은행 명</th>
						<th>계좌번호</th>
					</tr>
					<tr>
						<td><input type="text" name="private_account_bank[]" style="width:150px" value="<?php echo $private_account_bank[0];?>"></td>
						<td><input type="text" name="private_account_number[]" style="width:250px" value="<?php echo $private_account_number[0];?>"></td>
					</tr>
					<tr>
						<td><input type="text" name="private_account_bank[]" style="width:150px" value="<?php echo $private_account_bank[1];?>"></td>
						<td><input type="text" name="private_account_number[]" style="width:250px" value="<?php echo $private_account_number[1];?>"></td>
					</tr>
					<tr>
						<td><input type="text" name="private_account_bank[]" style="width:150px" value="<?php echo $private_account_bank[2];?>"></td>
						<td><input type="text" name="private_account_number[]" style="width:250px" value="<?php echo $private_account_number[2];?>"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<input type="submit" class="button button-primary" value="확인" style="margin-left:10px">
</form>
<script>
	jQuery("[name=payment_method]").change(function(){
		var this_value = jQuery(this).val();

		switch(this_value){
			case "INICIS" :
				jQuery(".pg_choice").after("<tr><td>KEY 아이디</td><td><select name='inicis_key_id'><?php echo $inicis_key_option;?></select></td></tr><tr><td>KEY 패스워드</td><td><input type='text' name='inicis_key_pwd'></td></tr>");
			break;
		}
	});
</script>