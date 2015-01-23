jQuery(document).ready(function(){

	var plugins_url = jQuery(".plugin_url").val();

	jQuery(".user-role-select", ".wpc-user-role-changer").change(function(){
		var origin_loader = jQuery(this).parent().parent().find(".ajax-loader");
		jQuery(this).parent().parent().find(".ajax-loader").show();
		var user_id = jQuery(this).parent().parent().attr("data");
		var data = {
			'action': 'wpc_user_role_changer',
			'role'	: jQuery(this).val(),
			'user_id' : user_id
		};

	  	jQuery.post(ajaxurl, data, function(response) {
	  		if(response.status == 'success'){
	  			origin_loader.hide();
	  		}
	  	});		
	});

	jQuery(".icon-wpc-close").click(function(){
		jQuery(this).parent().parent().find(".image_result").html('');
		jQuery(this).parent().hide();
		jQuery(this).parent().parent().parent().find(".popup_image_upload_button").show();
	});

	jQuery(".save_wpc_popup").click(function(){
		tinyMCE.triggerSave();
	})

	jQuery(".button-popup-remove").click(function(){
		var origin = jQuery(this).parent().parent().parent();
		if(confirm("해당 팝업은 완전히 삭제됩니다.\n삭제 하시겠습니까?") == true){
			var id = origin.find(".popup_id").val();
			var data = {
				'action': 'wpc_popup_list_remove',
				'id'	 : id
			};

			jQuery.post(ajaxurl, data, function(response){
				origin.css("background", "yellow");
				origin.animate({"opacity" :  0},{duration : 800, complete:function(){
					origin.remove();
				}});
			});			
		}
	});

	if(jQuery(".wpc_mobile_popup_background").html()){
		jQuery(".wpc_mobile_popup_background").wpColorPicker();
		jQuery(".wpc_mobile_popup_under_background").wpColorPicker();
		jQuery(".wpc_mobile_popup_under_text").wpColorPicker();
	}

	jQuery("[name=wpc_popup_upload_type]").click(function(){
		if(jQuery(this).prop("checked")){
			var this_type = jQuery(this).val();
			switch(this_type){
				case "image" :
					jQuery(".wpc_popup_new_image").show();
					jQuery(".wpc_popup_new_editor").hide();
				break;

				case "editor" :
					jQuery(".wpc_popup_new_image").hide();
					jQuery(".wpc_popup_new_editor").show();
				break;

				default :
					jQuery(".wpc_popup_new_image").show();
					jQuery(".wpc_popup_new_editor").hide();
				break;
			}
		}
	});

	jQuery(".wpc_top_menu > ul > li").each(function(){
		var title = jQuery(this).attr("data");
		if(title == "all"){
			jQuery(this).addClass("active");
		}
	});

	jQuery(".wpc_top_menu > ul > li").click(function(){
		var title = jQuery(this).attr("data");
		switch(title){
			case "all" :
				jQuery(".wpc_tr").show();
				jQuery(".wpc_top_menu > ul > .active").removeClass("active");
				jQuery(this).addClass("active");
			break;

			default :
				jQuery(".wpc_tr").hide();
				jQuery(".wpc_tr_"+title).show();
				jQuery(".wpc_top_menu > ul > .active").removeClass("active");
				jQuery(this).addClass("active");
			break;
		}
	});

	jQuery("#wpc_popup_start_date").datepicker({
		dateFormat: 'yy-mm-dd'
	});

	jQuery("#wpc_popup_end_date").datepicker({
		dateFormat: 'yy-mm-dd'
	});


	jQuery(".sortable_social_method").sortable({
		items: '.each_social_method',
		opacity: 0.6,
		cursor: 'move',
		axis:'y',
		update: function(){
			measure_social_priority();
		}
	});

	jQuery(".join_method").sortable({
		items: '.each_join_method',
		opacity: 0.6,
		cursor: 'move',
		update: function(){
			measure_join_method_priority();
		}
	});

	jQuery(".each_stand_by_method").draggable({ revert: true });
	jQuery( ".join_method" ).droppable({
      drop: function( event, ui ) {
        var parent_class = ui.draggable.parent().attr("class");
        	parent_class = parent_class.split(" ");
        	parent_class = parent_class[0];
        var method_value = ui.draggable.find("[name=method_value]").val();
        var title 		 = ui.draggable.find("label").html();
        var origin 		 = jQuery(this);
        if(parent_class == "stand_by_method"){

        	if(method_value == 'id' || method_value == 'password' || method_value == 'email'){
        		var required_option = '';
        	} else {
        		var required_option = '<li><input type="checkbox" name="check_required_'+method_value+'" value="required"> 필수기입요소</li>';
        	}

        	ui.draggable.remove();

        	if(method_value == 'term_use' || method_value == 'private'){
        		var option_result = '';
				var data = {
					'action': 'wpc_get_all_page'
				};

			  	jQuery.post(ajaxurl, data, function(response) {
					for (var i = 0; i < response.length; i++) {
						option_result += '<option value="'+response[i]['id']+'">'+response[i]['title']+'</option>';
					};

        		jQuery( origin ).append('<li class="each_join_method"><table><tbody><tr><td>'+title+' : '+method_value+'<div class="btn_dropdown" style="position:absolute; top:13px; right:13px">▼</div><div class="dropdown_content" style="display:block; height:0px; overflow:hidden; position:relative; top:15px"><ul><li>라벨명</li><li><input type="text" name="wpc_label_'+method_value+'" style="width:100%;" placeholder="라벨명을 변경하시려면 입력하세요." value="'+title+'"></li><li>사이즈</li><li><input type="text" name="wpc_size_'+method_value+'" placeholder="픽셀 혹은 퍼센트로 입력하세요." style="width:100%"></li><li>페이지 선택</li><li><select name="wpc_'+method_value+'_content">'+option_result+'</select></li>'+required_option+'</ul></div><input type="hidden" name="method_value" value="'+method_value+'"><input type="hidden" name="method_label" value="'+title+'"></td></tr></tbody></table></li>');

	        	measure_join_method_priority();

				jQuery(".btn_dropdown").toggle(function(){
					if( jQuery(this).parent().find("[name=method_value]").val() == "private" || jQuery(this).parent().find("[name=method_value]").val() == "term_use" ){
						jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '220px' }, 200);
					} else {
						jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '160px' }, 200);
					}
					
					jQuery(this).html("▲");
				}, function(){
					jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '0px' }, 200);
					jQuery(this).html("▼");
				});

			 	});

        	} else {

        		jQuery( this ).append('<li class="each_join_method"><table><tbody><tr><td>'+title+' : '+method_value+'<div class="btn_dropdown" style="position:absolute; top:13px; right:13px">▼</div><div class="dropdown_content" style="display:block; height:0px; overflow:hidden; position:relative; top:15px"><ul><li>라벨명</li><li><input type="text" name="wpc_label_'+method_value+'" style="width:100%;" placeholder="라벨명을 변경하시려면 입력하세요." value="'+title+'"></li><li>사이즈</li><li><input type="text" name="wpc_size_'+method_value+'" placeholder="픽셀 혹은 퍼센트로 입력하세요." style="width:100%"></li>'+required_option+'</ul></div><input type="hidden" name="method_value" value="'+method_value+'"><input type="hidden" name="method_label" value="'+title+'"></td></tr></tbody></table></li>');

        	}
        	measure_join_method_priority();

			jQuery(".btn_dropdown").toggle(function(){
				if( jQuery(this).parent().find("[name=method_value]").val() == "private" || jQuery(this).parent().find("[name=method_value]").val() == "term_use" ){
					jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '220px' }, 200);
				} else {
					jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '160px' }, 200);
				}
				
				jQuery(this).html("▲");
			}, function(){
				jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '0px' }, 200);
				jQuery(this).html("▼");
			});

    	}
      },
      activeClass: "active",
      hoverClass: "wpc_hover"
    });

    jQuery(".stand_by_method").droppable({
    	drop: function( event, ui ) {
    		var parent_class = ui.draggable.parent().attr("class");
        		parent_class = parent_class.split(" ");
        		parent_class = parent_class[0];
        	var title 		 = ui.draggable.find("[name=method_label]").val();
        	var method_value = ui.draggable.find("[name=method_value]").val();
        	if(parent_class == "join_method"){
        		if(method_value == 'id' || method_value == 'password' || method_value == 'email'){
        			alert('필수요소는 삭제가 불가능 합니다.');
        			return false;
        		} else {
	        		ui.draggable.remove();
	        		jQuery( this ).append('<li class="each_stand_by_method ui-draggable" style="position:relative; background:#e0e0e0"><label>'+title+'</label><input type="hidden" name="method_value" value="'+method_value+'" ></li>');
	        		measure_join_method_priority();
	        		jQuery( "li", this ).draggable( { revert: true });
	        	}
        	}
    	},
      tolerance: "touch",
      activeClass: "active",
      hoverClass: "wpc_left_hover"
    });

	jQuery(".btn_list_onoff").click(function(){
		var status  = '';
		var id 		= jQuery(this).parent().parent().find(".popup_id").val();

		if(jQuery(this).hasClass("active")){
			jQuery(this).attr("src", plugins_url+"/images/btn-off.png" );
			jQuery(this).removeClass("active");
			jQuery(this).parent().find("input").val("F");
			status = 'F';
		} else {
			jQuery(this).attr("src", plugins_url+"/images/btn-on.png" );
			jQuery(this).addClass("active");
			jQuery(this).parent().find("input").val("T");
			status = 'T';
		}
		var data = {
			'action': 'wpc_popup_list_status_change',
			'status' : status,
			'id'	 : id
		};

		jQuery.post(ajaxurl, data, function(response){
			
		});


	});


	jQuery(".btn_onoff").click(function(){
		if(jQuery(this).hasClass("active")){
			jQuery(this).attr("src", plugins_url+"/images/btn-off.png" );
			jQuery(this).removeClass("active");
			jQuery(this).parent().find("input").val("F");
		} else {
			jQuery(this).attr("src", plugins_url+"/images/btn-on.png" );
			jQuery(this).addClass("active");
			jQuery(this).parent().find("input").val("T");
		}
	});

	jQuery(".save_wpc").click(function(){
		tinyMCE.triggerSave();

		var data = {
			'action': 'save_wpc',
			'options' : jQuery("#wpc_form").serialize()
		};

	  	jQuery.post(ajaxurl, data, function(response) {
	  		if(response == "success"){
	  			alert('save successfully');
	  			location.reload();
	  		} else {
	  			alert('It has something problem');
	  		}
	 	});
	});

	jQuery(".btn_question").click(function(){
		//jQuery(this).after("어드민바는 로그인시 상단에 나타나는 검은색 바 입니다.");
	});

	jQuery(".btn_dropdown").toggle(function(){
		if( jQuery(this).parent().find("[name=method_value]").val() == "private" || jQuery(this).parent().find("[name=method_value]").val() == "term_use" ){
			jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '220px' }, 200);
		} else {
			jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '160px' }, 200);
		}			
			jQuery(this).html("▲");
		}, function(){
			jQuery(this).parent().find(".dropdown_content").stop().animate({ 'height' : '0px' }, 200);
			jQuery(this).html("▼");
		});

});

	function measure_social_priority(){
		var social_length = jQuery(".each_social_method").length;
		var priority = '';
		jQuery(".each_social_method").each(function(i){
			var sname = jQuery(this).attr("data");
			if(i == (social_length - 1)){
				priority = priority+sname;
			} else {
				priority = priority+sname+",";
			}
		});

		jQuery("[name=social_priority]").val(priority);
	}

	function measure_join_method_priority(){
		var join_method_length = jQuery(".each_join_method").length;
		var priority = '';
		jQuery(".each_join_method").each(function(i){
			var method_value = jQuery(this).find("[name=method_value]").val();
			if(i == (join_method_length - 1)){
				priority = priority+method_value;
			} else {
				priority = priority+method_value+",";
			}
		});

		jQuery("[name=join_method_priority]").val(priority);
	}


	function wpc_auto_create_page(){
		if(confirm('새로운 페이지를 생성하시겠습니까?\n기존 페이지 ID 정보는 삭제되며\n새로 생성된 페이지 ID 로 대체 됩니다.\n"로그인/회원가입/비밀번호 찾기" 명칭으로\n등록된 페이지가 있으면\n해당 페이지 아이디로 대체 됩니다.') == true ){

			jQuery(".page_id_setup_loading").show();
			var data = {
				'action': 'wpc_auto_create_page'
			};
 
			setTimeout(function() {jQuery(".page_id_setup_loading").hide();}, 5000);

		  	jQuery.post(ajaxurl, data, function(response) {
		  		var login_page 			= response.login_page.id;
		  		var join_page 			= response.join_page.id;
		  		var reset_page 			= response.reset_page.id;
		  		var modify_page 		= response.modify_page.id;
		  		var mbroke_page 		= response.mbroke_page.id;

		  		jQuery("[name=login_id]").val(login_page);
		  		jQuery("[name=join_id]").val(join_page);
		  		jQuery("[name=reset_pwd_id]").val(reset_page);
		  		jQuery("[name=modify_id]").val(modify_page);
		  		jQuery("[name=mbroke_id]").val(mbroke_page);

		  		jQuery(".page_id_setup_loading").hide();

		 	});
		} else {
			return false;
		}
	}

function wpc_popup_image_upload(){
	wp.media.editor.send.attachment = function(props, attachment){
		jQuery(".image_result_id").val(attachment.id);
		jQuery(".image_result_path").val(attachment.url);
		jQuery(".image_result").html("<img src='"+attachment.url+"'>");
		jQuery(".image_result_close").show();
		jQuery(".popup_image_upload_button").hide();
	}
	wp.media.editor.open(this);
	return false;
}















