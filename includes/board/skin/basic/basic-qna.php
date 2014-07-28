<?php
/*
 Skin Name: basic style
 Skin URI: http://kingkongcart.com
 Description: 기본 스타일 스킨으로 이 스킨을 바탕으로 제작 하시면 됩니다.
 Author: Bryan Lee
*/

	global $wpdb, $post;
	$board_table = $wpdb->prefix."kingkong_board";
	$boards = $wpdb->get_results("SELECT * from $board_table where pid = $post->ID and kind = 'qna' and type='normal' order by ID asc");	

?>

<h2>이용문의</h2>
<table class="board-table">
	<thead>
		<tr>
			<th>번호</th>
			<th>제목</th>
			<th>작성자</th>
			<th>등록일</th>
			<th>현황</th>
		</tr>
	</thead>
	<tbody>
<?php

	if ($boards){
		$cnt = count($boards);
		foreach ($boards as $board) {

			$board_reply 	= get_board_meta($board->ID, "board_reply");
			$board_info 	= get_board_meta($board->ID, "board_info");
			$board_info		= unserialize($board_info);
			$pwd_setup		= $board_info['pwd_setup'];

			if($board_reply){
				$board_reply_text = "답변완료";
			} else {
				$board_reply_text = "답변대기";
			}

?>
		<tr id="board-title-<?php echo $board->ID;?>">
			<td><?php echo $cnt;?></td>
			<td style="text-align:left">
				
<?php
	if($pwd_setup == "T"){
		echo "[비밀글]";
	} else {
		echo "[일반글]";
	}
?>
			<a onclick="board_view_content(<?php echo $board->ID;?>);" style="cursor:pointer; cursor:hand"><?php echo $board->title;?></a>

			</td>
			<td><?php echo $board->writer;?></td>
			<td><?php echo substr($board->date,0,10);?></td>
			<td><?php echo $board_reply_text;?></td>
		</tr>
		<tr id="board-content-<?php echo $board->ID;?>" class="board-content-tr">
			<td colspan="5" style="text-align:left; width:100%">
				<div class="board-content-div" id="board-content-div-<?php echo $board->ID;?>">

<?php
	if($pwd_setup == "T" && !current_user_can('administrator')){
?>
				<form method="post" id="board-pwd-form-<?php echo $board->ID;?>">
				비공개 글입니다. 확인하시려면 하단에 작성시 입력하신 비밀번호를 입력 해 주세요.<br>
				<input type="password" name="pwd"><input type="button" value="확인" onclick="board_pwd_check(<?php echo $board->ID;?>);">
				</form>

<?php
	} else {
		echo $board->content;
		if($board_reply){
?>
				

				<div class="board-content-answer">
					<ul>
						<li><b>관리자 답변</b></li>
						<li><?php echo $board_reply;?></li>
					</ul>
				</div>

				</div>
<?php
		}
	}
?>

<?php 
	if ( current_user_can('administrator') ) {
?>
				<div class="board-content-reply">
					<form method="post" id="board_reply_form_<?php echo $board->ID;?>">
						<table>
							<tr>
								<td>답변</td>
								<td><textarea name="reply_content"></textarea></td>
								<td><input type="button" value="답변완료" onclick="board_reply_proc(<?php echo $board->ID;?>);"></td>
							</tr>
						</table>
					</form>
				</div>
<?php
	}
?>
			</td>
		</tr>
<?php
			$cnt--;
		}

	} else {
?>
		<tr>
			<td colspan="5">등록된 글이 없습니다.</td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<div class="board-button">
<form method="post">
<input type="hidden" name="status_type" value="board">
<input type="submit" value="문의글 작성">
</form>
</div>
