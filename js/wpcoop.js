jQuery(document).ready(function(){

	jQuery(".wpc_center_popup .wpc-icon-close").click(function(){

		var popup_name 		= 'wpc_popup_'+jQuery(this).attr('data-id');
		var value 			= 'popup_working';
		var popup_session 	= jQuery(this).attr('data-expire');
		var today 			= new Date();
		today.setHours(24*popup_session);

		if(popup_session != 0){
			//alert(expired.toGMTString());
			document.cookie = popup_name + "=" + escape(value) + "; path=/; expires=" + today.toGMTString() + ";";
		}

		jQuery(this).parent().parent().parent().stop().animate({
			"opacity" : 0
		}, {duration:300, complete: function(){
			jQuery(this).hide();
		}});
	});

	jQuery(".wpc_center_popup_session_check").click(function(){
		if(jQuery(this).prop("checked")){
			jQuery(this).parent().parent().find(".wpc-icon-close").attr("data-expire", jQuery(this).val());
		}
	});

	jQuery(".wpc_mobile_popup_check").click(function(){
		if(jQuery(this).prop("checked")){
			jQuery(this).parent().parent().find(".wpc-icon-close").attr("data-expire", jQuery(this).val());
		}
	})

	jQuery("#wpc_popup_mobile .wpc-icon-close").click(function(){

		var popup_name		= 'wpc_popup_'+jQuery(this).attr("data-id");
		var value 			= 'popup_working';
		var popup_session 	= jQuery(this).attr('data-expire');
		var today 			= new Date();
		today.setHours(24*popup_session);

		if(popup_session != 0){
			document.cookie = popup_name + "=" + escape(value) + "; path=/; expires=" + today.toGMTString() + ";";
		}

		jQuery(this).parent().parent().stop().animate({
			"opacity" : 0
		}, {duration:300, complete: function(){
			jQuery(this).hide();
		}});
	});

	jQuery(".wpc_popup").each(function(){
		var type = jQuery(this).attr("data");
		switch(type){
			case "top" :
				var height = jQuery(this).height();
				jQuery(this).css("height", "0px");
				jQuery(this).stop().animate({"height" : height+"px"}, 600);
			break;
		}
	})

	jQuery(".wpc_popup_top_close").click(function(){
		var popup_name		= 'wpc_popup_'+jQuery(this).attr("data-id");
		var value 			= 'popup_working';
		var popup_session 	= jQuery(this).attr('data-session');
		var today 			= new Date();
		today.setHours(24*popup_session);

		if(popup_session != 0){
			document.cookie = popup_name + "=" + escape(value) + "; path=/; expires=" + today.toGMTString() + ";";
		}

		jQuery(this).parent().animate({"height" : 0}, {duration:500, complete: function(){
			jQuery(this).hide();
		}});
	});

	jQuery(".wpc_join_submit").click(function(){
		var type = jQuery("[name=form_type]").val();
		var data = {
			'action' : 'wpc_required_method_check',
			'data'	 : jQuery("#wpc_join_form").serialize(),
			'type'	 : type
		}
		//jQuery(".wpc_form_notice").remove();
		jQuery(".wpc_form_error").each(function(){
			jQuery(this).removeClass("wpc_form_error");
		});
		jQuery.post(ajax_wpcoop.ajax_url, data, function(response){
			//console.log(response);
			var err_msg = '';
			var key, count = 0;
			for(key in response){
				if(response.hasOwnProperty(key)){
					err_msg += (response[key]['msg'])+"\n";
					if(!jQuery("[name=wpc_input_"+key+"]").parent().find(".wpc_form_notice").html()){
						jQuery("[name=wpc_input_"+key+"]").after("<div class='wpc_form_notice'>"+response[key]['msg']+"</div>");
					} else {
						jQuery("[name=wpc_input_"+key+"]").parent().find(".wpc_form_notice").html(response[key]['msg']);
					}
					jQuery("[name=wpc_input_"+key+"]").addClass("wpc_form_error");
					count++;
				}
			}
			console.log(err_msg);
			if(count == 0){
				wpc_join_form.submit();
			}
			
		});
	});



	jQuery("[name=wpc_input_id]").change(function(){
		var length = jQuery(this).val().length;
		var origin = jQuery(this);

		if(length > 3){
			// ajax 호출
			if(!origin.parent().find(".wpc_form_notice").html()){
				origin.after("<div class='wpc_form_notice'>checking...</div>");
			} else {
				origin.parent().find(".wpc_form_notice").html("checking...");
			}

			var data = {
				'action' : 'wpc_check_user_name',
				'user_name' : jQuery(this).val()
			}

			jQuery.post(ajax_msh.ajax_url, data, function(response) {
				switch(response){
					case "exist" :
						origin.parent().find(".wpc_form_notice").html("사용불가");
						origin.addClass("wpc_form_error");
						jQuery("[name=username_exists]").val(0);
					break;

					case "canuse" :
						origin.parent().find(".wpc_form_notice").html("사용가능");
						origin.removeClass("wpc_form_error");
						jQuery("[name=username_exists]").val(1);
					break;
				}
			});
		}
	});



	jQuery("[name=wpc_input_email]").keyup(function(){
		var origin 		= jQuery(this);
		var length 		= jQuery(this).val().length;
		var email 		= jQuery(this).val();
		var email_split = email.split("@");

		if(!origin.parent().find(".wpc_form_notice").html()){
			if(length > 0){
				origin.after("<div class='wpc_form_notice'>checking...</div>");
			}
		}	

		if(!email_split[1]){
			if(length > 0){
				origin.parent().find(".wpc_form_notice").html("올바르지 않은 이메일 입니다.");
				jQuery("[name=email_exists]").val(0);
			}
		} else {
			//origin.parent().find(".msh_loading").html("checking...");

			var data = {
				'action' : 'wpc_check_email',
				'user_email' : email
			}

			jQuery.post(ajax_msh.ajax_url, data, function(response) {
				switch(response){
					case "exist" :
						origin.parent().find(".wpc_form_notice").html("사용불가");
						origin.addClass("wpc_form_error");
						jQuery("[name=email_exists]").val(0);
					break;

					case "canuse" :
						origin.parent().find(".wpc_form_notice").html("사용가능");
						origin.removeClass("wpc_form_error");
						jQuery("[name=email_exists]").val(1);
					break;
				}
			});
		}
	});



	jQuery("[name=wpc_input_re_pwd]").keyup(function(){
		var origin_pwd  = jQuery("[name=wpc_input_password]").val();
		var origin 		= jQuery(this);

		if(!origin.parent().find(".wpc_form_notice").html()){
			origin.after("<div class='wpc_form_notice'>checking...</div>");
		}	

		if(origin_pwd == jQuery(this).val()){
			origin.parent().find(".wpc_form_notice").html("일치");
			jQuery("[name=pwd_success]").val(1);
		} else {
			origin.parent().find(".wpc_form_notice").html("불일치");
			jQuery("[name=pwd_success]").val(0);
		}
	});

});

function wpc_social_login(result_id, result_name, real_name, social_name){

	// result_id 	: 사용자 아이디 (int)
	// result_name 	: 사용자 이름(닉네임)
	// social_name 	: 소셜 종류

	jQuery("#wpc_loading").show();

	var data = {
		'action'	: 'wpc_custom_social_login',
		'id'		: result_id,
		'name'		: result_name,
		'real_name' : real_name,
		'social' 	: social_name
	};

	jQuery.post(ajax_wpcoop.ajax_url, data, function(response) {
		//alert(response);
	  	if(response == "success"){
	  		location.href = '/';
	  	} else {
	  		alert('이미 가입되어 있는 이메일 혹은 아이디 입니다.\n 직접 로그인 하시기 바랍니다.');
	  		jQuery("#wpc_loading").hide();
	  	}
	});

	setTimeout(function() {
		if(jQuery("#wpc_loading").css("display") == "block"){
			alert('해당 계정으로 로그인 중 문제가 발생하였습니다.\n잠시 후 다시 시도하시거나 다른 계정으로 로그인 하시기 바랍니다.');
			jQuery("#wpc_loading").hide();
		}
	}, 20000);

}

function twitter_oauth(){

	var data = {
		'action'	: 'wpc_twitter_oauth_connect'
	};
	jQuery.post(ajax_wpcoop.ajax_url, data, function(response) {
		if(response != "false"){
			location.href=response;
		}
	});
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
            jQuery("[name=wpc_input_post_code]").val(data.postcode1+"-"+data.postcode2);
            jQuery("[name=wpc_input_basic_address]").val(data.address);
            jQuery("[name=wpc_input_else_address]").focus();
            element.style.display = 'none';
        },
        width : '100%',
        height : '100%'
    }).embed(element);

    // iframe을 넣은 element를 보이게 한다.
    element.style.display = 'block';
}



