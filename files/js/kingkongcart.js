jQuery(document).ready(function(){

	jQuery(".sub-thumbnail > li > img").mouseover(function(){
		var thumbnail_id = jQuery(this).parent().attr("label");
		var url = jQuery(".main-thumbnail > img").attr("src");
		var origin = url.split("/");	
		var count = origin.length - 1;
		var origin_2 = origin[count].split(".");
		var file_type = origin_2[1];

			origin_2 = origin_2[0];
			origin_2 = origin_2.split("-");

		var change_id = origin_2[3]+"-"+origin_2[4]+"."+file_type;
		var change_file_type = jQuery(this).attr("alt");
			change_file_type = change_file_type.split(".");
			change_file_type = change_file_type[1];

			thumbnail_id = thumbnail_id+"."+change_file_type;

		var change_url = url.replace(change_id,thumbnail_id);

		jQuery(".main-thumbnail > img").attr("src",change_url);
	});

	jQuery("[name=equal_info]").click(function(){
		if(jQuery(this).prop("checked") == true){
			jQuery("[name=shipping_name]").val(jQuery("[name=order_name]").val());
			jQuery("[name=shipping_tel1]").val(jQuery("[name=order_tel1]").val());
			jQuery("[name=shipping_tel2]").val(jQuery("[name=order_tel2]").val());
			jQuery("[name=shipping_tel3]").val(jQuery("[name=order_tel3]").val());
		} else {
			jQuery("[name=shipping_name]").val("");
			jQuery("[name=shipping_tel1]").val("");
			jQuery("[name=shipping_tel2]").val("");
			jQuery("[name=shipping_tel3]").val("");
		}
	});


	jQuery(".vote-star > img").mouseover(function(){
		var this_star = jQuery(this).attr("class");
			this_star = this_star.split("-");
			this_star = this_star[1];

		for (var i = 1; i <= this_star; i++) {
			jQuery(".votestar-"+i).attr("src", jQuery("#vote-path").val()+"star-one.png" );
		};

		for (var j = 5; j > (this_star * 1); j--){
			jQuery(".votestar-"+j).attr("src", jQuery("#vote-path").val()+"star-none.png" );
		}

		jQuery("#vote-point").val( (i - 1));

	});


	jQuery(".vote-star > img").click(function(){

		var this_star = jQuery(this).attr("class");
			this_star = this_star.split("-");
			this_star = this_star[1];

		for (var i = 1; i <= this_star; i++) {
			jQuery(".votestar-"+i).attr("src", jQuery("#vote-path").val()+"star-one.png" );
		};

		for (var j = 5; j > (this_star * 1); j--){
			jQuery(".votestar-"+j).attr("src", jQuery("#vote-path").val()+"star-none.png" );
		}

		jQuery("#vote-point").val( (i - 1));

	});

	jQuery("[name=uid]").bind("keyup", function(){
		jQuery("[name=chk_duplicate]").val(0);
	});

	jQuery("[name=email]").bind("keyup", function(){
		jQuery("[name=chk_email_duplicate]").val(0);
	});

	jQuery("[name=email_domain]").bind("keyup", function(){
		jQuery("[name=chk_email_duplicate]").val(0);
	});

	jQuery(".selectpaymethod").click(function(){
		jQuery("[name=gopaymethod]").val(jQuery(this).val());
	})

});



function option_select_check(){

	var option1_id = jQuery("[name=option1]").val();
		option1_id = option1_id.split("*-*");
		option1_id = option1_id[0];

	if(jQuery("[name=option1]").val() == "-1"){
		alert("옵션을 선택 해 주세요.");
		return false;
	}
	else {
		if( jQuery(".option2_tr").hasClass("disable-option") == false && jQuery(".second-option-"+option1_id).val() == "-1"){
			alert("추가 옵션을 선택 해 주세요.");
			return false;
		}
		else {
			var plugins_url = jQuery("[name=plugins_url]").val();
			return true;
		}
	}
			
}

function go_cart(id,kind){


	if(option_select_check()){
			
	  var data = {
	    'action': 'go_cart',
	    'post_id' : id,
	    'data' : jQuery("#product-info-form").serialize()
	  };

	  jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {

	  	switch(kind){
	  		case "cart" :
	  			notice_message_display(response);
	  		break;

	  		case "buy" :
	  			jQuery("#product-info-form").submit();
	  		break;
	  	}

	  });

	}

}

function notice_message_display(value){

	var result_text;
	var cart_url = jQuery("[name=cart_url]").val();
	var result = value * 1;

	switch(result){

	  	case 10 :
	  		result_text = "장바구니에 등록되었습니다.확인하시겠습니까?";
	  		if( confirm(result_text) == true ){
	  			location.href = cart_url;
	  		}
	  	break;

	  	case 20 :
	  		result_text = "위시리스트에 등록되었습니다.";
	  		alert(result_text);
	  	break;

	  	case 2 :
	  		result_text = "위시리스트는 로그인 후 이용 가능 합니다.";
	  		alert(result_text);
	  	break;

	  	default :
	  		result_text = "이미 등록되어 있는 상품입니다.";
	  		alert(result_text);
	  	break;

	}
	
}



function go_wish(id){

	if(option_select_check()){
	  var data = {
	    'action': 'go_wish',
	    'post_id' : id,
	    'data' : jQuery("#product-info-form").serialize()
	  };

	  jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
	  	notice_message_display(response);
	  });
	}

}

function kingkongcart_remove_cart(id){

  var data = {
    'action': 'kingkongcart_remove_cart',
    'id' : id
  };

  jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
  	location.reload();
  });

}

function kingkongcart_remove_wish(id){

  var data = {
    'action': 'kingkongcart_remove_wish',
    'id' : id
  };

  jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
  	location.reload();
  });

}

function kingkongcart_go_wish(id){

	var data = {
		'action': 'kingkongcart_go_wish',
		'id' : id
	};

  jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {

  	var response = response * 1 + 1;

  	switch(response){
  		case 1 :
  			kingkongcart_remove_cart(id);
  			alert("위시리스트에 등록 되었습니다.");
  		break;

  		default :
  			notice_message_display( (response - 1) );
  		break;
  	}
  });

}

function kingkongcart_go_cart(id){

	var data = {
		'action': 'kingkongcart_go_cart',
		'id' : id
	};

  jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {

  	var response = response * 1 + 1;

  	switch(response){
  		case 1 :
  			kingkongcart_remove_wish(id);
  			alert("장바구니에 등록 되었습니다.");
  		break;

  		default :
  			notice_message_display((response - 1));
  		break;
  	}
  });
  
}

function quantity_up(){
	var quantity = jQuery("[name=quantity]").val();
	var change_quantity = (quantity * 1) + 1;

	jQuery("[name=quantity]").val(change_quantity);
}

function quantity_down(){
	var quantity = jQuery("[name=quantity]").val();
	var change_quantity = (quantity * 1) - 1;

	if(change_quantity > 0){
		jQuery("[name=quantity]").val(change_quantity);
	}	
}

function change_order_value(value, kind, method){

	switch(kind){

		case "name" :
			switch(method){
				case "INICIS" :
					jQuery("[name=buyername]").val(value);
				break;

				case "KCP" :
				// code here
				break;
			}
		break;

		case "email" :
			switch(method){
				case "INICIS" :
					jQuery("[name=buyeremail]").val(value);
				break;

				case "KCP" :
				// code here
				break;
			}
		break;

		case "tel" :
			switch(method){
				case "INICIS" :
					jQuery("[name=buyertel]").val(jQuery("[name=order_tel1]").val()+"-"+jQuery("[name=order_tel2]").val()+"-"+jQuery("[name=order_tel3]").val());
				break;

				case "KCP" :
				// code here
				break;
			}
		break;	
	}
}

function insert_postcode(postcode1, postcode2, doro, jibun){
	jQuery("[name=postcode1]").val(postcode1);
	jQuery("[name=postcode2]").val(postcode2);
	jQuery("[name=doro_address]").val(doro);
	jQuery("[name=jibun_address]").val(jibun);
	jQuery("#kingkong_modal").hide();
	jQuery("[name=else_address]").focus();
}

function get_postcode(){

	var region 	= jQuery("[name=postcode_region]").val();
	var keyword = jQuery("[name=postcode_keyword]").val();

   	var url = "http://ithemeso.cafe24.com/postcode/?region="+region+"&keyword="+keyword;
   	url = encodeURI(url);

   	jQuery(".postcode_result_table > tbody").html("");
  jQuery.getJSON(url,function(list){ //JSON Load

         var message, key;

         if(list.status == "success"){

	        for (key in list) {
	            message = list[key];
	            var zipcode1 = message.zipcode1;
	            var zipcode2 = message.zipcode2
	            var sido = message.sido;
	            var gugun = message.gugun;
	            var eupmyun = message.eupmyun;
	            var doro = message.doro;
	            var bldname = message.bldname;
	            var dong = message.dong;
	            var ri = message.ri;
	            var san = message.san;
	            var jibunp = message.jibunp;
	            var jibuns = message.jibuns;
	            var result_jibun;
	            if(san == 1){
	            	if(jibuns != 0){
	            		result_jibun = "산 "+jibunp+"-"+jibuns;
	            	} else {
	            		result_jibun = "산 "+jibunp;
	            	} 
	        	} else {
	        		if(jibuns != 0){
	        			result_jibun = jibunp+"-"+jibuns;
	        		} else {
	        			result_jibun = jibunp;
	        		}
	        	}

	        	var doro_address = sido+" "+gugun+" "+eupmyun+" "+doro+" "+bldname;
	        	var jibun_address = sido+" "+gugun+" "+eupmyun+" "+dong+" "+ri+" "+bldname+" "+result_jibun;


	            if(zipcode1 && zipcode2){
	            jQuery(".postcode_result_table > tbody").append("<tr><td><a onclick=\"insert_postcode('"+zipcode1+"','"+zipcode2+"', '"+doro_address+"', '"+jibun_address+"');\">"+zipcode1+"-"+zipcode2+"</a></td><td><table><tr><td>도로명주소:</td><td>"+doro_address+"</td></tr><tr><td>지번주소:</td><td>"+jibun_address+"</td></tr></table></td></tr>");
	        	}
	        }

         } else {

         		jQuery(".postcode_result_table > tbody").html("<tr><td colspan='2'>검색 결과가 없습니다.</td></tr>");

         }
  });

}

function postcode_popup(){

		jQuery(".kingkong_modal_bg").css("width", jQuery("body").width()+"px");
		jQuery(".kingkong_modal_bg").css("height", jQuery("body").height()+"px");
		jQuery("#kingkong_modal").show();

}

function kingkong_popup_close(){
	jQuery("#kingkong_modal").hide();
}

function board_qna_write(id){

	var editor = tinymce.get('board_content');
	editor.save();

	var data = {
		'action': 'board_qna_write',
		'id' : id,
		'data' : jQuery("#board_write_form").serialize(),
		'content' : editor.getContent()
	};

  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
  		history.back();
  	});

}

function board_afternote_write(id){

	var title = jQuery("[name=title]").val();
	var writer = jQuery("[name=writer]").val();
	var email1 = jQuery("[name=email]").val();
	var email_domain = jQuery("[name=email_domain]").val();
	var vote_point = jQuery("[name=vote_point]").val();
	var thumbnail = jQuery("[name=thumbnail]").val();
	var editor = tinymce.get('board_content');
		editor.save();

	var content = editor.getContent();

	if(!title){
		alert("제목을 입력 해 주세요");
		jQuery("[name=title]").focus();
	} else if (!writer){
		alert("작성자를 입력 해 주세요");
		jQuery("[name=writer]").focus();
	} else if (!email1){
		alert("이메일을 입력 해 주세요");
		jQuery("[name=email]").focus();
	} else if (!email_domain){
		alert("이메일을 입력 해 주세요");
		jQuery("[name=email_domain]").focus();
	} else if (!vote_point){
		alert("평점을 선택 해 주세요");
	} else if (!content){
		alert("내용을 입력 해 주세요");
		jQuery("[name=board_content]").focus();
	} else {
		board_write_form.submit();
	}

	//board_write_form.submit();

}


function board_view_content(id){

   	if( jQuery("#board-content-"+id).css("display") == "none" ){
  		jQuery(".board-content-tr").hide();
  		jQuery("#board-content-"+id).show();

  	} else {
  			jQuery("#board-content-"+id).hide();
  	}
 
}

function board_reply_proc(id){
	var data = {
		'action' : 'board_reply_proc',
		'data'	: jQuery("#board_reply_form_"+id).serialize(),
		'id'	: id
	}

  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
  		var result = response * 1 + 10;
   		if(result == 11){
   			alert("답변글이 정상적으로 등록되었습니다.");
   			location.reload();
   		}

  	});

}



function board_pwd_check(id){
	var data = {
		'action' : 'board_pwd_check',
		'id'	: id,
		'data'	: jQuery("#board-pwd-form-"+id).serialize()
	}

  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {

  		if(response != 0){
  			jQuery("#board-content-div-"+id).html(response);
  		} else {
  			alert("비밀번호가 일치하지 않습니다.");
  		}

  	});

}

function remove_cart_all(){

	if(confirm("장바구니의 모든 상품들이 삭제됩니다.\n비우시겠습니까?") == true){

		var data = {
			'action' : 'remove_cart_all'
		}

	  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
	  		location.reload();
	  	});

	}
}

function remove_wish_all(){

	if(confirm("위시리스트의 모든 상품들이 삭제됩니다.\n비우시겠습니까?") == true){

		var data = {
			'action' : 'remove_wish_all'
		}

	  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
	  		location.reload();
	  	});

	}
}


function kingkong_member_join(){

	var uid 				= jQuery("[name=uid]").val();
	var chk_duplicate		= jQuery("[name=chk_duplicate]").val();
	var pwd 				= jQuery("[name=pwd]").val();
	var re_pwd 				= jQuery("[name=re-pwd]").val();
	var uname 				= jQuery("[name=uname]").val();
	var zipcode1 			= jQuery("[name=postcode1]").val();
	var zipcode2 			= jQuery("[name=postcode2]").val();
	var address1 			= jQuery("[name=address]").val();
	var address2 			= jQuery("[name=else_address]").val();
	var tel1 				= jQuery("[name=tel1]").val();
	var tel2 				= jQuery("[name=tel2]").val();
	var tel3 				= jQuery("[name=tel3]").val();
	var phone1 				= jQuery("[name=phone1]").val();
	var phone2 				= jQuery("[name=phone2]").val();
	var phone3 				= jQuery("[name=phone3]").val();
	var email 				= jQuery("[name=email]").val();
	var email_domain 		= jQuery("[name=email_domain]").val();
	var chk_email_duplicate = jQuery("[name=chk_email_duplicate]").val();
	var newsletter 			= jQuery("[name=newsletter]").val();
	var send_agree 			= jQuery("[name=send-agree]").val();
	var agree_policy 		= jQuery("[name=agree-policy]").prop("checked");
	var agree_privacy 		= jQuery("[name=agree-privacy]").prop("checked");
	var birth_year			= jQuery("[name=birth_year]").val();
	var birth_month			= jQuery("[name=birth_month]").val();
	var birth_day			= jQuery("[name=birth_day]").val();


	var pattern = /^[a-z]+[a-z0-9_]+[a-z0-9_]$/;
	var num_pattern = /^[0-9]+$/;

	if(!uid){
		alert("아이디를 입력해 주세요");
		jQuery("[name=uid]").focus();
	} else if(!pattern.test(uid)){
		alert("아이디는 영문소문자로 시작하고 \r\n영문소문자,숫자,언더바(_)만 사용하실 수 있습니다.");
	} else if(chk_duplicate == 0){
		alert("아이디 중복확인 버튼을 눌러 중복여부를 확인해 주세요");
	} else if(!pwd){
		alert("비밀번호를 입력해 주세요");
		jQuery("[name=pwd]").focus();
	} else if(pwd != re_pwd){
		alert("비밀번호와 비밀번호 확인이 일치하지 않습니다.");
		jQuery("[name=re-pwd]").focus();
	} else if(!uname){
		alert("이름을 입력해 주세요");
		jQuery("[name=uname]").focus();
	} else if(!zipcode1 || !zipcode2){
		alert("우편번호를 입력해 주세요");
	} else if(!num_pattern.test(zipcode1) || !num_pattern.test(zipcode2)){
		alert("우편번호는 숫자만 기입하셔야 합니다.");
	} else if(!address1){
		alert("주소를 입력해 주세요");
	} else if(!address2){
		alert("나머지 주소를 입력해 주세요");
		jQuery("[name=address2]").focus();
	} else if(!phone1 || !phone2 || !phone3){
		alert("휴대전화를 입력해 주세요");
	} else if(!num_pattern.test(phone1) || !num_pattern.test(phone2) || !num_pattern.test(phone3)){
		alert("휴대전화 번호는 숫자만 기입하셔야 합니다.");
	} else if(!num_pattern.test(tel1) || !num_pattern.test(tel2) || !num_pattern.test(tel3)){
		alert("유선전화는 숫자만 기입하셔야 합니다.");
	} else if(!email || !email_domain){
		alert("이메일을 입력해 주세요");
	} else if(chk_email_duplicate == 0){
		alert("이메일 중복확인 버튼을 눌러 중복여부를 확인해 주세요");
	} else if(!birth_year || !birth_month || !birth_day){
		alert("생년월일을 입력해 주세요");
	} else if(!agree_policy){
		alert("이용약관에 동의하셔야 가입하실 수 있습니다.");
	} else if(!agree_privacy){
		alert("개인정보 수집 및 이용에 동의 하셔야 가입하실 수 있습니다.");
	} else {
		member_join_form.submit();
	}

}

function chk_uid_exist(){
	var uid = jQuery("[name=uid]").val();

	if(!uid || uid.length < 4){
		alert("아이디는 최소 4자 이상이어야 하며 영문자만 가능합니다");
	} else {
		var data = {
			'action' : 'chk_uid_exist',
			'uid'	: uid
		}

	  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {
	  		
	  		var result = response * 1 + 10;

	  		switch(result){
	  			case 10 :
	  				alert("등록가능한 아이디 입니다.");
	  				jQuery("[name=chk_duplicate]").val(1);
	  			break;

	  			case 11 :
	  				alert("이미 등록되어 있는 아이디 입니다.");
	  				jQuery("[name=chk_duplicate]").val(0);
	  			break;
	  		}
	  	});

	}
}



function chk_email_exist(){
	var email 			= jQuery("[name=email]").val();
	var email_domain 	= jQuery("[name=email_domain]").val();

	if(!email || !email_domain){
		alert("이메일을 먼저 기입해 주세요");
	} else {
		var data = {
			'action' : 'chk_email_exist',
			'email'	: email+"@"+email_domain
		}

	  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {

	  		var result = response * 1 + 10;

	  		switch(result){
	  			case 10 :
	  				alert("등록가능한 이메일주소 입니다.");
	  				jQuery("[name=chk_email_duplicate]").val(1);
	  			break;

	  			case 11 :
	  				alert("이미 등록되어 있는 이메일주소 입니다.");
	  				jQuery("[name=chk_email_duplicate]").val(0);
	  			break;
	  		}
	  	});

	}
}



function use_mileage(user_id){
	var num_pattern = /^[0-9]+$/;
	var mileage = jQuery("[name=input_mileage]").val();

	if(!num_pattern.test(mileage)){
		alert("숫자만 입력하시기 바랍니다.");
	} else {

		var data = {
			'action' : 'use_mileage',
			'user_id' : user_id,
			'mileage' : mileage,
			'price'	: jQuery("[name=without_shipping_cost]").val()
		}

	  	jQuery.post(ajax_kingkongcart.ajax_url, data, function(response) {

	  		var result = response * 1 + 10;

	  		switch(response){
	  			case 10 :
	  				alert("입력하신 금액이 보유 적립금 보다 큽니다.");
	  			break;

	  			case 11 :
		  			jQuery("[name=using_mileage]").val(mileage);
		  			use_mileage_form.submit();
	  			break;

	  			case 12 :
	  				alert("상품가격은 최소 1,000원 이 되어야 결제가 가능하며\n상품가격 이상의 적립금은 사용하실 수 없습니다");
	  			break;

	  			case 13 :
	  				alert("설정된 최소 사용금액 보다 작습니다.");
	  			break;

	  			case 14 :
	  				alert("설정된 최대 사용금액보다 큽니다.");
	  			break;
	  		}

	  	});

	}
}




function closeDaumPostcode() {

	// 우편번호 찾기 화면을 넣을 element
	var element = document.getElementById('layer');

    // iframe을 넣은 element를 안보이게 한다.
    element.style.display = 'none';
}

function showDaumPostcode() {

	// 우편번호 찾기 화면을 넣을 element
	var element = document.getElementById('layer');

    new daum.Postcode({
        oncomplete: function(data) {
            // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분. 우편번호와 주소 및 영문주소 정보를 해당 필드에 넣는다.
            //document.getElementById('postcode1').value = data.postcode1;
            jQuery("[name=postcode1]").val(data.postcode1);
            jQuery("[name=postcode2]").val(data.postcode2);
            jQuery("[name=address]").val(data.address);
            //document.getElementById('postcode2').value = data.postcode2;
            //document.getElementById('address').value = data.address;
            //document.getElementById('addressEnglish').value = data.addressEnglish;
            // iframe을 넣은 element를 안보이게 한다.
            element.style.display = 'none';
        },
        width : '100%',
        height : '100%'
    }).embed(element);

    // iframe을 넣은 element를 보이게 한다.
    element.style.display = 'block';
}


function input_email_to_field(value){
	if(value != "-1"){
		jQuery("[name=email_domain]").val(value);
	}
}









