<?php
header("Content-Type: text/html; charset=UTF-8");


		$P_TID;				// 거래번호
		$P_MID;				// 상점아이디
		$P_AUTH_DT;			// 승인일자
		$P_STATUS;			// 거래상태 (00:성공, 01:실패)
		$P_TYPE;			// 지불수단
		$P_OID;				// 상점주문번호
		$P_FN_CD1;			// 금융사코드1
		$P_FN_CD2;			// 금융사코드2
		$P_FN_NM;			// 금융사명 (은행명, 카드사명, 이통사명)
		$P_AMT;				// 거래금액
		$P_UNAME;			// 결제고객성명
		$P_RMESG1;			// 결과코드
		$P_RMESG2;			// 결과메시지
		$P_NOTI;			// 노티메시지(상점에서 올린 메시지)
		$P_AUTH_NO;			// 승인번호

$P_STATUS = $_POST['P_STATUS'];
$P_UNAME  = iconv("EUC-KR", "UTF-8", $_POST['P_UNAME']);
$P_RMESG1 = iconv("EUC-KR", "UTF-8", $_POST['P_RMESG1']);
$P_RMESG2 = iconv("EUC-KR", "UTF-8", $_POST['P_RMESG2']);
$P_OID 	  = $_POST['P_OID'];

if($P_STATUS == "00"){
?>
	<script>
		window.opener.document.pay_result.result_status.value 	= "success";
		window.opener.document.pay_result.P_RMESG1.value 		= "<?php echo $P_RMESG1;?>";
		window.opener.document.pay_result.P_RMESG2.value 		= "<?php echo $P_RMESG2;?>";
		window.opener.document.getElementById("payment_loading").style.display 	= "block";
		window.close();
		window.opener.document.pay_result.submit();
	</script>
<?php
} else {
?>
	<script>
		window.opener.document.pay_result.result_status.value 	= "failed";
		window.opener.document.pay_result.P_RMESG1.value 		= "<?php echo $P_RMESG1;?>";
		window.opener.document.pay_result.P_RMESG2.value 		= "<?php echo $P_RMESG2;?>";
		window.close();
		//window.opener.document.pay_result.submit();
	</script>
<?php
}
?>