<?php
	function wpcoop_dashboard(){

		$options = new wpcoop_login();

?>
	<h2>Coop Members</h2>
	<p>
		편의를 위해 개발된 워드프레스협동조합(<a href="http://www.wpcoop.kr" target="_blank">wpcoop.kr</a>) 한국형 소셜로그인/회원가입 플러그인 입니다.<br />
		문의사항은 <a href="http://www.wpcoop.kr" target="_blank">www.wpcoop.kr</a> 이나 070-4693-6557 로 연락 주시기 바랍니다.
	</p>
	<div id="wpc_wrap">
		<input type="hidden" class="plugin_url" value="<?php echo WPCOOP_PLUGINS_URL;?>">
		<div class="wpc_top_menu">
			<ul>
				<li data="all">전체</li>
				<li data="login_basic">로그인 기본 설정</li>
				<li data="login_api">로그인 API 설정</li>
				<li data="join">회원가입 설정</li>
			</ul>
		</div>
		<form id="wpc_form" method="post">
			<table>
				<tr class="wpc_tr wpc_tr_login_basic">
					<th>
						<ul>
							<li>
								<h3>로그인설정</h3>
								<p style="font-size:12px; color:gray; font-weight:normal">로그인 기능을 사용하지 않으려면<br>OFF 하시면 됩니다.</p>
							</li>
							<li>
								<?php
									if($options->login_enable == "T"){
								?>
									<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" style="width:70px; height:auto" class="btn_onoff">
								<?php
									} else {
								?>
									<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" style="width:70px; height:auto" class="btn_onoff">
								<?php
									} 
								?>
								<input type="hidden" name="login_enable" value="<?php echo $options->login_enable;?>">
							</li>
						</ul>
					</th>
					<td style="border-right:1px dashed #e0e0e0; position:relative" class="sortable_td">
						<h3>기본설정 <div style="float:right"><button type="button" class="button" onclick="wpc_auto_create_page();">자동 페이지 생성</button></div></h3>
						<div class="page_id_setup_loading" style="display:none; position:absolute; top:10px; left:0px; width:100%; z-index:999"><div style="width:100%; height:220px; border-radius:10px; background:#000; opacity:0.7; z-index:1"></div><div style="width:100%; color:#fff; position:absolute; z-index:2; top:40px; text-align:center;"><img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/loading.GIF"><br><br>설정 중입니다...</div></div>
						<ul id="social_controll">
							<li>로그인 페이지 ID</li>
							<li class="controll_btn"><input type="text" name="login_id" style="width:100px" value="<?php echo $options->login_id;?>"></li>
						</ul>
						<ul id="social_controll">
							<li>회원가입 페이지 ID</li>
							<li class="controll_btn"><input type="text" name="join_id" style="width:100px" value="<?php echo $options->join_id;?>"></li>
						</ul>
						<ul id="social_controll">
							<li>회원정보 수정 페이지 ID</li>
							<li class="controll_btn"><input type="text" name="modify_id" style="width:100px" value="<?php echo $options->modify_id;?>"></li>
						</ul>
						<ul id="social_controll" style="padding-bottom:10px;  top:-7px">
							<li>회원탈퇴 페이지 ID</li>
							<li class="controll_btn"><input type="text" name="mbroke_id" style="width:100px" value="<?php echo $options->mbroke_id;?>"></li>
						</ul>						
						<ul id="social_controll" style="padding-bottom:10px; position:relative; top:-7px">
							<li>비밀번호 초기화 페이지 ID</li>
							<li class="controll_btn"><input type="text" name="reset_pwd_id" style="width:100px" value="<?php echo $options->reset_pwd_id;?>"></li>
						</ul>
						<h3 style="border-top:1px dashed #e0e0e0; clear:both; padding-top:10px">로그인페이지 리다이렉션</h3>
						<ul id="social_controll">
							<li>로그인/어드민 페이지 리다이렉션</li>
							<li class="controll_btn">
							<?php
								if($options->login_redirection == "T"){
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" class="btn_onoff">
							<?php
								} else {
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" class="btn_onoff">
							<?php
								}
							?>
								<input type="hidden" name="login_redirection" value="<?php echo $options->login_redirection;?>">
							</li>
						</ul>
						<h3 style="border-top:1px dashed #e0e0e0; clear:both; padding-top:10px">Admin Bar</h3>
						<ul id="social_controll">
							<li>어드민 바 감추기</li>
							<li class="controll_btn">
							<?php
								if($options->admin_bar == "T"){
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" class="btn_onoff">
							<?php
								} else {
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" class="btn_onoff">
							<?php
								}
							?>
								<input type="hidden" name="admin_bar" value="<?php echo $options->admin_bar;?>">
							</li>
						</ul>
						<h3 style="border-top:1px dashed #e0e0e0; clear:both; padding-top:10px">옵션버튼</h3>
						<ul id="social_controll">
							<li>비밀번호 초기화</li>
							<li class="controll_btn">
							<?php
								if($options->find_pwd == "T"){
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" class="btn_onoff">
							<?php
								} else {
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" class="btn_onoff">
							<?php
								}
							?>
								<input type="hidden" name="find_pwd" value="<?php echo $options->find_pwd;?>">
							</li>
						</ul>
						<ul id="social_controll">
							<li>회원가입</li>
							<li class="controll_btn">
							<?php
								if($options->join_member == "T"){
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" class="btn_onoff">
							<?php
								} else {
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" class="btn_onoff">
							<?php
								}
							?>
								<input type="hidden" name="join_member" value="<?php echo $options->join_member;?>">
							</li>
						</ul>
						<h3 style="border-top:1px dashed #e0e0e0; clear:both; padding-top:10px">Social Button Style</h3>
						<ul id="social_controll">
							<li>스타일 선택</li>
							<li class="controll_btn">
<?php
	switch($options->social_style){
		case "list" :
			$list_selected = "checked";
		break;

		case "icon" :
			$icon_selected = "checked";
		break;

		default :
			$icon_selected = "checked";
		break;
	}
	switch($options->social_position){
		case "top" :
			$position_top = "checked";
		break;

		case "bottom" :
			$position_bottom = "checked";
		break;

		default :
			$position_bottom = "checked";
		break;
	}
?>
								<input type="radio" name="social_style" value="list" <?php echo $list_selected;?>>리스트형 
								<input type="radio" name="social_style" value="icon" <?php echo $icon_selected;?>>아이콘형
							</li>
						</ul>
						<ul id="social_controll">
							<li>소셜 버튼 위치</li>
							<li class="controll_btn">
								<input type="radio" name="social_position" value="top" <?php echo $position_top;?>>상단 
								<input type="radio" name="social_position" value="bottom" <?php echo $position_bottom;?>>하단
							</li>
						</ul>
						<h3 style="border-top:1px dashed #e0e0e0; clear:both; padding-top:10px">Social API</h3>
<?php
	$priority = $options->social_priority;
?>
						<input type="hidden" name="social_priority" value="<?php echo $priority;?>">
						<div class="sortable_social_method">
<?php
	
	
	$priority = explode(",", $priority);

	for ($i=0; $i < count($priority); $i++) {
		switch($priority[$i]){
			case "FB" :
				$social_class = "social_facebook";
				$social_name  = "Facebook";
			break;

			case "TW" :
				$social_class = "social_twitter";
				$social_name  = "Twitter";
			break;

			case "NV" :
				$social_class = "social_naver";
				$social_name  = "Naver";
			break;

			case "KA" :
				$social_class = "social_kakao";
				$social_name  = "Kakao";
			break;

			case "GP" :
				$social_class = "social_google";
				$social_name  = "Google";
		}
?>

							<ul id="social_controll" class="each_social_method" data="<?php echo $priority[$i];?>">
								<li><?php echo $social_name;?></li>
								<li class="controll_btn">
								<?php
									if($options->$social_class == "T"){
								?>
									<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" class="btn_onoff">
								<?php
									} else {
								?>
									<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" class="btn_onoff">
								<?php
									}
								?>
									<input type="hidden" name="<?php echo $social_class;?>" value="<?php echo $options->$social_class;?>">
								</li>
							</ul>
<?php
	}
?>

						</div>
					</td>
					<td style="padding-left:40px; text-align:center"><button type="button" class="button button-primary save_wpc">저장하기</button></td>
				</tr>
				<tr class="wpc_tr wpc_tr_login_api">
					<th style="vertical-align:top">
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>API 설정</h3>
								<p style="font-size:12px; color:gray; font-weight:normal">소셜 로그인의 API 인증 키값과<br />토큰값을 설정합니다.</p>
							</li>
						</ul>
					</th>
					<td style="border-right:1px dashed #e0e0e0; vertical-align:top" class="sortable_td">
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>카카오 로그인 API 설정</h3>
							</li>
						</ul>
						<ul>
							<li>
								<table style="width:100%">
									<tr>
										<td style="text-align:left">앱 키 (Javascript 키)</td>
										<td style="text-align:right"><input type="text" name="kakao_app_key" value="<?php echo $options->kakao_app_key;?>"></td>
									</tr>
									<tr>
										<td colspan="2">* <a href="https://developers.kakao.com" target="_blank">KakaoDevelopers</a> 사이트에서<br />앱 키와 웹 도메인(플랫폼)을 반드시 등록하셔야 합니다.</td>
									</tr>
								</table>
							</li>
						</ul>
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>페이스북 로그인 API 설정</h3>
							</li>
						</ul>
						<ul>
							<li>
								<table style="width:100%">
									<tr>
										<td style="text-align:left">앱 아이디</td>
										<td style="text-align:right"><input type="text" name="facebook_app_id" value="<?php echo $options->facebook_app_id;?>"></td>
									</tr>
									<tr>
										<td colspan="2">* <a href="https://developers.facebook.com/" target="_blank">Facebook Developers</a> 에서 앱 아이디를<br />생성후 등록하셔야 이용가능합니다.</td>
									</tr>
								</table>
							</li>
						</ul>
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>네이버 로그인 API 설정</h3>
							</li>
						</ul>
						<ul>
							<li>
								<table style="width:100%">
									<tr>
										<td style="text-align:left">ClientID</td>
										<td style="text-align:right"><input type="text" name="naver_client_id" value="<?php echo $options->naver_client_id;?>"></td>
									</tr>
									<tr>
										<td style="text-align:left">ClientSecret</td>
										<td style="text-align:right"><input type="text" name="naver_client_secret" value="<?php echo $options->naver_client_secret;?>"></td>
									</tr>
									<tr>
										<td colspan="2">* <a href="http://developer.naver.com/wiki/pages/OpenAPI" target="_blank">Naver 개발자센터</a> 에서<br />어플리케이션 등록과정을 마친 후 이용 가능합니다.<br />네이버 로그인 API 는 CURL 라이브러리를 사용합니다.<br />계정에 CURL 라이브러리가 설치되어 있지 않다면<br />정상적으로 동작하지 않습니다.</td>
									</tr>
								</table>
							</li>
						</ul>
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>Google+ 로그인 API 설정</h3>
							</li>
						</ul>
						<ul>
							<li>
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Client ID</td>
										<td style="text-align:right"><input type="text" name="google_client_id" value="<?php echo $options->google_client_id;?>"></td>
									</tr>
									<tr>
										<td colspan="2">* <a href="https://console.developers.google.com" target="_blank">Google Developers Console</a> 에서<br />웹 어플리케이션 등록과정을 마친 후 이용 가능합니다.
									</tr>
								</table>
							</li>
						</ul>
					</td>
					<td style="padding-left:40px; text-align:center"><button type="button" class="button button-primary save_wpc">저장하기</button></td>
				</tr>
				<tr class="wpc_tr wpc_tr_join">
					<th style="vertical-align:top">
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>회원가입 설정</h3>
								<p style="font-size:12px; color:gray; font-weight:normal">회원가입 페이지 세부기능을 설정합니다.</p>
							</li>
							<li>
								원하시는 폼 요소를<br />드래그 해서 설정하세요.
							</li>
<?php

	$joinform = new wpcoop_joinForm();
	$using_methods = $joinform->using_method;
	$not_using_methods = $joinform->not_using_method;
	$method_priority = $joinform->join_method_priority;

?>
							<li style="padding:10px 10px">
								<div class="stand_by_menu">
									<ul style="padding:10px 10px" class="stand_by_method">
<?php
	$join_method_priority = "";
	if($not_using_methods){
		foreach($not_using_methods as $method){
?>
										<li class="each_stand_by_method" style="background:#e0e0e0"><label><?php echo $method['label'];?></label><input type="hidden" name="method_value" value="<?php echo $method['value'];?>"></li>
<?php
		}
	}
?>
									</ul>
								</div>
							</li>
						</ul>
					</th>
					<td style="border-right:1px dashed #e0e0e0; vertical-align:top" class="sortable_td">
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>회원가입 페이지 스타일링</h3>
							</li>
						</ul>
						<ul>
							<li>
								<input type="hidden" name="join_method_priority" value="<?php echo $method_priority;?>">
								<ul class="join_method">
<?php

	foreach($using_methods as $method){

		if(!isset($method['type'])){

			$size_option = "<input type='text' name='wpc_size_".$method['value']."' style='width:100%;' value='".$method['size']."' placeholder='픽셀 혹은 퍼센트로 입력하세요.'>";

			$insert_page_id = '';
		if($method['value'] == "term_use" or $method['value'] == "private"){

			global $wpdb;
			$post_table = $wpdb->prefix."posts";
			$results 		= $wpdb->get_results("SELECT ID, post_title from $post_table WHERE post_status = 'publish' and post_type = 'page'");
			$cnt = 0;
			$option_value = "";
			foreach($results as $result){
				if($method['value'] == 'term_use' && $options->term_use_id == $result->ID){
					$option_value .= "<option value='".$result->ID."' selected>".$result->post_title."</option>";
				} else if($method['value'] == 'private' && $options->private_id == $result->ID){
					$option_value .= "<option value='".$result->ID."' selected>".$result->post_title."</option>";
				} else {
					$option_value .= "<option value='".$result->ID."'>".$result->post_title."</option>";
				}			
				$cnt++;
			}
			$insert_page_id = '<li>페이지 선택</li><li><select name="wpc_'.$method['value'].'_content">'.$option_value.'</select></li>';
		}

		if($method['mustuse']){
			$must_use_content = '<div class="dropdown_content" style="display:block; height:0px; overflow:hidden; position:relative; top:15px"><ul><li>라벨명</li><li><input type="text" name="wpc_label_'.$method['value'].'" style="width:100%" placeholder="라벨명을 변경하시려면 입력하세요." value="'.$joinform->get_label($method['label'], $method['value']).'"></li><li>사이즈</li><li>'.$size_option.'</li>'.$insert_page_id.'</ul></div>';
		} else {
			if($method['required'] == "required"){
				$required = "checked";
			} else {
				$required = "";
			}
			$must_use_content = '<div class="dropdown_content" style="display:block; height:0px; overflow:hidden; position:relative; top:15px"><ul><li>라벨명</li><li><input type="text" name="wpc_label_'.$method['value'].'" style="width:100%"></li><li>사이즈</li><li>'.$size_option.'</li>'.$insert_page_id.'<li><input type="checkbox" name="check_required_'.$method['value'].'" value="required" '.$required.'> 필수기입요소</li></ul></div>';		
		}

		if($method['using']){

?>

									<li class="each_join_method">
										<table>
											<tr>
												<td>
													<?php echo $joinform->get_label($method['label'], $method['value']);?> : <?php echo $method['value'];?>
													<div class="btn_dropdown" style="position:absolute; top:13px; right:13px;">▼</div>
													<?php echo $must_use_content;?>
													<input type="hidden" name="method_value" value="<?php echo $method['value'];?>">
													<input type="hidden" name="method_label" value="<?php echo $joinform->get_label($method['label'], $method['value']);?>">
												</td>
											</tr>
										</table>
									</li>
<?php
			}
		}
	}
?>
								</ul>
							</li>
						</ul>
						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>가입완료 페이지 설정</h3>
							</li>
						</ul>
<?php
	$content  = get_option("wpc_thankyou_page");
	$editor_id = "wpc_thankyou_page";
	$settings = array(
		'textarea_name' => 'wpc_thankyou_page',
		'textarea_rows' => 10
	);
?>
						<ul>
							<li><?php wp_editor($content, $editor_id, $settings);?></li>
						</ul>

						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>회원정보 수정완료 페이지 설정</h3>
							</li>
						</ul>
<?php
	$content  = get_option("wpc_modify_thankyou_page");
	if(is_wp_error($content)){
		$content = "";
	}
	$editor_id = "wpc_modify_thankyou_page";
	$settings = array(
		'textarea_name' => 'wpc_modify_thankyou_page',
		'textarea_rows' => 10
	);
?>
						<ul>
							<li><?php wp_editor($content, $editor_id, $settings);?></li>
						</ul>

						<ul>
							<li style="border-top:1px dashed #e0e0e0">
								<h3>우커머스 연동설정</h3>
							</li>
						</ul>
						<ul id="social_controll">
							<li>회원정보를 billing 정보에 입력</li>
							<li class="controll_btn">
							<?php
								if($options->woo_billing_input == "T"){
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" class="btn_onoff">
							<?php
								} else {
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" class="btn_onoff">
							<?php
								}
							?>
								<input type="hidden" name="woo_billing_input" value="<?php echo $options->woo_billing_input;?>">
							</li>
						</ul>
						<ul id="social_controll">
							<li>회원정보를 shipping 정보에 입력</li>
							<li class="controll_btn">
							<?php
								if($options->woo_shipping_input == "T"){
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-on.png" class="btn_onoff">
							<?php
								} else {
							?>
								<img src="<?php echo WPCOOP_PLUGINS_URL;?>/images/btn-off.png" class="btn_onoff">
							<?php
								}
							?>
								<input type="hidden" name="woo_shipping_input" value="<?php echo $options->woo_shipping_input;?>">
							</li>
						</ul>
					</td>
					<td style="padding-left:40px; text-align:center"><button type="button" class="button button-primary save_wpc">저장하기</button></td>
				</tr>
			</table>
		</form>
	</div>
<?php
	}
?>