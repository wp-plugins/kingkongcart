<div id="member-join">
	<form method="post" id="member_join_form">
	<h3>기본정보</h3>
	<table>
		<tr>
			<th>아이디</th>
			<td><input type="text" name="uid"> <input type="button" value="중복확인" onclick="chk_uid_exist();"> (영문소문자/숫자, 4~16자) <input type="hidden" name="chk_duplicate" value="0"></td>
		</tr>
		<tr>
			<th>비밀번호</th>
			<td>
				<input type="password" name="pwd"> 영문 대소문자,숫자, 또는 특수문자 중 2가지 이상 조합하여 10~16자로 입력해 주세요.
			</td>
		</tr>
		<tr>
			<th>비밀번호 확인</th>
			<td><input type="password" name="re-pwd"></td>
		</tr>
		<tr>
			<th>이름</th>
			<td><input type="text" name="uname"></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td>
				<input type="text" name="email"> @ <input type="text" name="email_domain">
				<select onchange="input_email_to_field(this.value);">
					<option value="-1">이메일 선택</option>
<?php
	$email_domain = added_email_domain(); // function 에 등록되어 있는 리스트를 불러옴

	for ($i=0; $i < count($email_domain); $i++) { 
		echo "<option>".$email_domain[$i]."</option>";
	}

?>
				</select>
				<input type="button" value="이메일 중복확인" onclick="chk_email_exist();">
				<input type="hidden" name="chk_email_duplicate" value="0">
			</td>
		</tr>
		<tr>
			<th>주소</th>
			<td>
				<table>
					<tr>
						<td>
							<input type="text" name="postcode1" class="kingkong_input_s"> - <input type="text" name="postcode2" class="kingkong_input_s"> <input type="button" value="우편번호" onclick="showDaumPostcode();">
							<div id="layer" style="display:none;border:5px solid;position:fixed;width:400px;height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode()">
							</div>
						</td>
					</tr>
					<tr>
						<td><input type="text" name="address" class="kingkong_input_l"></td>
					</tr>
					<tr>
						<td><input type="text" name="else_address" class="kingkong_input_l"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>유선전화</th>
			<td>
				<select name="tel1">
					<option>02</option>
					<option>031</option>
					<option>032</option>
					<option>033</option>
					<option>041</option>
					<option>042</option>
					<option>043</option>
					<option>044</option>
				</select>
				-
				<input type="text" name="tel2" class="kingkong_input_s"> - <input type="text" name="tel3" class="kingkong_input_s">
			</td>
		</tr>
		<tr>
			<th>휴대전화</th>
			<td>
				<select name="phone1">
					<option>010</option>
					<option>011</option>
					<option>016</option>
					<option>017</option>
					<option>018</option>
					<option>019</option>
				</select>
				-
				<input type="text" name="phone2" class="kingkong_input_s"> - <input type="text" name="phone3" class="kingkong_input_s"> <input type="radio" name="send-agree" value="T" checked> 수신함 <input type="radio" name="send-agree" value="F"> 수신안함
			</td>
		</tr>
		<tr>
			<th>생년월일</th>
			<td><input type="text" name="birth_year" class="kingkong_input_s"> 년 <input type="text" name="birth_month" class="kingkong_input_s"> 월 <input type="text" name="birth_day" class="kingkong_input_s"> 일 <input type="radio" name="birth_kind" value="Y" checked> 양력 <input type="radio" name="birth_kind" value="E"> 음력</td>
		</tr>
		<tr>
			<th>뉴스메일</th>
			<td>뉴스 메일을 받으시겠습니까? <input type="radio" name="newsletter" value="T" checked>수신함 <input type="radio" name="newsletter" value="F">수신안함</td>
		</tr>
	</table>

	<h3>이용약관 동의</h3>
	<table>
		<tr>
			<td>
				<textarea style="height:100px; background:#f1f1f1"><?php echo get_post_field('post_content',KINGKONG_POLICY);?></textarea>
			</td>
		</tr>
		<tr>
			<td>이용약관에 동의하십니까? <input type="checkbox" name="agree-policy">동의함</td>
		</tr>
	</table>

	<h3>개인정보 수집 및 이용 동의</h3>
	<table>
		<tr>
			<td>
				<textarea style="height:100px; background:#f1f1f1"><?php echo get_post_field('post_content',KINGKONG_PRIVACY);?></textarea>
			</td>
		</tr>
		<tr>
			<td>개인정보 수집 및 이용에 동의하십니까? <input type="checkbox" name="agree-privacy">동의함</td>
		</tr>
	</table>

	<input type="button" value="취소"> <input type="button" value="회원가입" onclick="kingkong_member_join();">

	</form>
</div>