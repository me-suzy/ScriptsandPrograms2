<?
	/*
	Silentum Boards v1.4.3
	function_list.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	$config['datapath'] = ".";

	function killnl($text) {
	return str_replace("\n","",str_replace("\r\n","",$text));
	}

	function nlbr($text) {
	$text = str_replace("\r\n", "<br />", $text);
	return str_replace("\n", "<br />", $text);
	}

	function brnl($text) {
	$text = str_replace("<br />", "\n", $text);
	return str_replace("<br />", "\n", $text);
	}

	function get_user_data($user_id) {
	if(!$user_file = myfile("members/$user_id.txt")) return FALSE;
	if(killnl($user_file[4]) == 5) return FALSE;
	$user_data[0] = killnl($user_file[0]); $user_data['nick'] = &$user_data[0];
	$user_data[1] = killnl($user_file[1]); $user_data['id'] = &$user_data[1];
	$user_data[2] = killnl($user_file[2]); $user_data['pw'] = &$user_data[2];
	$user_data[3] = killnl($user_file[3]); $user_data['email'] = &$user_data[3];
	$user_data[4] = killnl($user_file[4]); $user_data['status'] = &$user_data[4];
	$user_data[5] = killnl($user_file[5]); $user_data['posts'] = &$user_data[5];
	$user_data[6] = killnl($user_file[6]); $user_data['regdate'] = &$user_data[6];
	$user_data[7] = killnl($user_file[7]); $user_data['timezone'] = &$user_data[7];
	$user_data[8] = killnl($user_file[8]); $user_data['signature'] = &$user_data[8];
	$user_data[9] = killnl($user_file[9]); $user_data['aim'] = &$user_data[9];
	$user_data[10] = killnl($user_file[10]); $user_data['displayoptions'] = &$user_data[10];
	$user_data[11] = killnl($user_file[11]); $user_data['title'] = &$user_data[11];
	$user_data[12] = killnl($user_file[12]); $user_data['msn'] = &$user_data[12];
	$user_data[13] = killnl($user_file[13]); $user_data['yahoo'] = &$user_data[13];
	$user_data[14] = killnl($user_file[14]); $user_data['possiblekarma'] = &$user_data[14];
	$user_data[15] = killnl($user_file[15]); $user_data['stylesheet'] = &$user_data[15];
	$user_data[16] = killnl($user_file[16]); $user_data['karma'] = &$user_data[16];
	$user_data[17] = killnl($user_file[17]); $user_data['quote'] = &$user_data[17];
	$user_data[18] = killnl($user_file[18]); $user_data['icq'] = &$user_data[18];
	$user_data[20] = killnl($user_file[20]); $user_data['publicemail'] = &$user_data[20];

	$display_options = explode(",",$user_data[10]);
	$user_data['showemail'] = $display_options[0];
	$user_data['showsignatures'] = $display_options[1];
	$user_data['showtitles'] = $display_options[2];
	$user_data['showsmilies'] = $display_options[3];

	return $user_data;
	}

	function mydate() {
	return gmdate("YmdHis");
	}

	function makedate($text) {
	global $config,$txt,$user_data,$user_logged_in;
	if($user_logged_in != 1) $timezone = $config['default_timezone']; else $timezone = $user_data['timezone'];

	$x = substr($timezone,1,2)*3600 + substr($timezone,3,2)*60;
	if(substr($timezone,0,1) == "-") $x = -1*$x;

	$text = mktime(substr($text,8,2),substr($text,10,2),substr($text,12,2),substr($text,4,2),substr($text,6,2),substr($text,0,4)) + $x + date("Z");
	$text = gmstrftime("%Y%m%d%H%M%S%a",$text);

	$year = substr($text,0,4);
	$month = substr($text,4,2);
	switch($month) {
	case "01":
	$month = "January";
	break;
	case "02":
	$month = "February";
	break;
	case "03":
	$month = "March";
	break;
	case "04":
	$month = "April";
	break;
	case "05":
	$month = "May";
	break;
	case "06":
	$month = "June";
	break;
	case "07":
	$month = "July";
	break;
	case "08":
	$month = "August";
	break;
	case "09":
	$month = "September";
	break;
	case "10":
	$month = "October";
	break;
	case "11":
	$month = "November";
	break;
	case "12":
	$month = "December";
	break;
	}
	$date = substr($text,6,2);
	$hour = substr($text,8,2);
	{
	if($hour <= 11) $ampm = "am";
	else $ampm = "pm";
	}
	if($hour == "13") $hour = "01";
	if($hour == "14") $hour = "02";
	if($hour == "15") $hour = "03";
	if($hour == "16") $hour = "04";
	if($hour == "17") $hour = "05";
	if($hour == "18") $hour = "06";
	if($hour == "19") $hour = "07";
	if($hour == "20") $hour = "08";
	if($hour == "21") $hour = "09";
	if($hour == "22") $hour = "10";
	if($hour == "23") $hour = "11";
	if($hour == "00") $hour = "12";
	$minute = substr($text,10,2);
	$second = substr($text,12,2);
	$text = "$year-$month-$date / $hour:$minute:$second$ampm";
	return $text;
	}

	function makeregdate($string) {
	global $txt;
	$year = substr($string,0,4);
	$month = substr($string,4,2);
	switch($month) {
	case "01":
	$month = "January";
	break;
	case "02":
	$month = "February";
	break;
	case "03":
	$month = "March";
	break;
	case "04":
	$month = "April";
	break;
	case "05":
	$month = "May";
	break;
	case "06":
	$month = "June";
	break;
	case "07":
	$month = "July";
	break;
	case "08":
	$month = "August";
	break;
	case "09":
	$month = "September";
	break;
	case "10":
	$month = "October";
	break;
	case "11":
	$month = "November";
	break;
	case "12":
	$month = "December";
	break;
	}
	$date = substr($string,6,2);
	$hour = substr($string,8,2);
	if($hour == "13") $hour = "01";
	if($hour == "14") $hour = "02";
	if($hour == "15") $hour = "03";
	if($hour == "16") $hour = "04";
	if($hour == "17") $hour = "05";
	if($hour == "18") $hour = "06";
	if($hour == "19") $hour = "07";
	if($hour == "20") $hour = "08";
	if($hour == "21") $hour = "09";
	if($hour == "22") $hour = "10";
	if($hour == "23") $hour = "11";
	if($hour == "00") $hour = "12";
	$minute = substr($string,10,2);
	$second = substr($string,12,2);
	$ampm = substr($string,14,2);
	$reg_date = "$year-$month-$date / $hour:$minute:$second$ampm";
	return $reg_date;
	}

	function get_board_data($board) {
	if(!$board_file = myfile("objects/boards.txt")) return FALSE;
	for($i = 0; $i < sizeof($board_file); $i++) {
	$act_board = myexplode($board_file[$i]);
	if($act_board[0] == $board) {
	$board_data[0] = $act_board[0]; $board_data['id'] = &$board_data[0];
	$board_data[1] = $act_board[1]; $board_data['name'] = &$board_data[1];
	$board_data[2] = $act_board[2]; $board_data['descr'] = &$board_data[2];
	$board_data[3] = $act_board[3]; $board_data['topics'] = &$board_data[3];
	$board_data[4] = $act_board[4]; $board_data['posts'] = &$board_data[4];
	$board_data[5] = $act_board[5]; $board_data['catid'] = &$board_data[5];
	$board_data[6] = $act_board[6]; $board_data['last_post_time'] = &$board_data[6];
	$board_data[7] = $act_board[7]; $board_data['options'] = &$board_data[7];
	$board_data[8] = $act_board[8]; $board_data[] = &$board_data[8];
	$board_data[9] = $act_board[9]; $board_data['ltopic'] = &$board_data[9];
	$board_data[10] = $act_board[10]; $board_data['rights_data'] = &$board_data[10];

	$board_options = explode(',',$board_data[7]);
	$board_data['basic_html'] = $board_options[0];
	$board_data['smilies'] = $board_options[2];

	$board_data['rights'] = explode(',',$board_data[10]);

	break;
	}
	}
	if(!isset($board_data)) return FALSE;
	return $board_data;
	}

	function get_topic_data($board,$thread) {
	if(!$topic_file = myfile("boards/$board.$thread.txt")) return FALSE;
	$topic = myexplode($topic_file[0]);
	$topic_data[0] = $topic[0]; $topic_data['status'] = &$topic_data[0];
	$topic_data[1] = $topic[1]; $topic_data['title'] = &$topic_data[1];
	$topic_data[2] = $topic[2]; $topic_data['creator_id'] = &$topic_data[2];
	$topic_data[3] = $topic[3]; $topic_data['post_icon'] = &$topic_data[3];
	$topic_data[4] = $topic[4]; $topic_data['smilies'] = &$topic_data[4];
	$topic_data[5] = $topic[5]; $topic_data['lpost'] = &$topic_data[5];
	$topic_data[6] = $topic[6]; $topic_data['poll_id'] = &$topic_data[6];
	$topic_data[7] = $topic[7]; $topic_data[''] = &$topic_data[7];
	$topic_data[8] = $topic[8]; $topic_data[''] = &$topic_data[8];
	$topic_data[9] = $topic[9]; $topic_data[''] = &$topic_data[9];
	$topic_data[10] = $topic[10]; $topic_data[''] = &$topic_data[10];
	$topic_data[11] = $topic[11]; $topic_data[''] = &$topic_data[11];

	$topic_data['posts'] = sizeof($topic_file)-1;
	$lpost = myexplode($topic_file[sizeof($topic_file)-1]); $topic_data['lpost_id'] = $lpost[0];

	return $topic_data;
	}

	function get_post_data($board,$thread,$post_id) {
	if(!$topic_file = myfile("boards/$board.$thread.txt")) return FALSE;
	for($i = 1; $i < sizeof($topic_file); $i++) {
	$act_post = myexplode($topic_file[$i]);
	if($act_post[0] == $post_id) {
	$post_data[0] = $act_post[0]; $post_data['id'] = &$post_data[0];
	$post_data[1] = $act_post[1]; $post_data['creator_id'] = &$post_data[1];
	$post_data[2] = $act_post[2]; $post_data['ctime'] = &$post_data[2];
	$post_data[3] = $act_post[3]; $post_data['post'] = &$post_data[3];
	$post_data[4] = $act_post[4]; $post_data['creator_ip'] = &$post_data[4];
	$post_data[5] = $act_post[5]; $post_data['signature'] = &$post_data[5];
	$post_data[7] = $act_post[7]; $post_data['smilies'] = &$post_data[7];
	$post_data[8] = $act_post[8]; $post_data['basic_html'] = &$post_data[8];
	$post_data[10] = $act_post[10]; $post_data[] = &$post_data[10];
	$post_data[11] = $act_post[11]; $post_data[] = &$post_data[11];
	break;
	}
	}
	return $post_data;
	}

	function get_user_name($user_id) {
	if(strncmp($user_id,'0',1) == 0) $user_name = substr($user_id,1,strlen($user_id));
	elseif(!$user_data = myfile("members/$user_id.txt")) $user_name = "Deleted";
	else $user_name = killnl($user_data[0]);
	return $user_name;
	}

	function verify_email_address($email) {	if(preg_match('/^[\.0-9a-z_-]{1,}@[\.0-9a-z-]{1,}\.[a-z]{1,}$/si',$email)) return TRUE;
	return FALSE;
	}

	function make_last_post($board,$data,$board_status) {
	global $MYSID1,$MYSID2,$config,$txt,$user_logged_in,$current_board;
	$last_post_data = explode(',',$data);
	$act_board_rights = explode(',',$current_board[10]);
	if($last_post_data[0] != "") {
	$right = 0;
	if($user_logged_in != 1) {
	if($act_board_rights[4] == 1) $right = 1;
	}
	elseif(check_right($board,0) == 1) $right = 1;
	if($right != 1) $last_post = makedate($last_post_data[2]);
	else {
	if(!$topic_file = myfile("boards/$board.$last_post_data[0].txt")) $topic_link = makedate($last_post_data[2])."<br /><span class=\"normal\"> Post deleted or moved</span>";
	else {
	$topic_info = myexplode($topic_file[0]);
	if($config['enable_censor'] == 1) $topic_info[1] = censor($topic_info[1]);
	$hover_title = $topic_info[1];
	if(strlen($topic_info[1]) > 40) $topic_info[1] = substr($topic_info[1],0,40)."...";
	$topic_link = makedate($last_post_data[2])."<br />";
	}
	$post_icon = get_post_icon_address($last_post_data[3]);
	$last_post = "$topic_link<span class=\"normal\"><strong><a href=\"index.php?method=topic&amp;board=$board&amp;thread=$last_post_data[0]\" title=\"".$hover_title."\">".$topic_info[1]."</a></strong></span>";
	}
	}
	return $last_post;
	}

	function get_board_name($text) {
	$board_data = myfile("objects/boards.txt"); $id_boards = sizeof($board_data);
	for($i = 0; $i < $id_boards; $i++) {
	$current_board = myexplode($board_data[$i]);
	if($current_board[0] == $text) {
	$board_name = $current_board[1];
	break;
	}
	}
	return $board_name;
	}

	function get_thread_name($board,$thread) {
	if(!$topic_file = myfile("boards/$board.$thread.txt")) $topic_name = "Deleted";
	else {
	$topic_info = myexplode($topic_file[0]);
	$topic_name = $topic_info[1];
	}
	return $topic_name;
	}

	function increase_topic_amount($text) {
	$boards = myfile("objects/boards.txt"); $id_boards = sizeof($boards);
	for($i = 0; $i < $id_boards; $i++) {
	$current_board = myexplode($boards[$i]);
	if($text == $current_board[0]) {
	$current_board[3]++;
	$boards[$i] = myimplode($current_board);
	$save = 1;
	break;
	}
	}
	if($save == 1) myfwrite("objects/boards.txt",$boards,"w");
	else echo "Board does not exist.";
	}

	function decrease_topic_amount($text) {
	$boards = myfile("objects/boards.txt"); $id_boards = sizeof($boards);
	for($i = 0; $i < $id_boards; $i++) {
	$current_board = myexplode($boards[$i]);
	if($text == $current_board[0]) {
	$current_board[3] = $current_board[3] - 1;
	$boards[$i] = myimplode($current_board);
	$save = 1; break;
	}
	}
	if($save == 1) myfwrite("objects/boards.txt",$boards,"w");
	else echo "Board does not exist.";
	}

	function increase_post_amount($string) {
	$boards = myfile("objects/boards.txt"); $id_boards = sizeof($boards);
	for($i = 0; $i < $id_boards; $i++) {
	$current_board = myexplode($boards[$i]);
	if($string == $current_board[0]) {
	$current_board[4] = $current_board[4] + 1;
	$boards[$i] = myimplode($current_board);
	$save = 1; break;
	}
	$boards[$i] = myimplode($current_board);
	}
	if($save == 1) myfwrite("objects/boards.txt",$boards,"w");
	else echo "Board does not exist.";
	}

	function increase_post_amountx($board,$posts) {
	$boards = myfile("objects/boards.txt"); $id_boards = sizeof($boards);
	for($i = 0; $i < $id_boards; $i++) {
	$current_board = myexplode($boards[$i]);
	if($board == $current_board[0]) {
	$current_board[4] = $current_board[4] + $posts;
	$boards[$i] = myimplode($current_board);
	$save = 1; break;
	}
	$boards[$i] = myimplode($current_board);
	}
	if($save == 1) myfwrite("objects/boards.txt",$boards,"w");
	else echo "Board does not exist.";
	}

	function decrease_post_amount($string, $post_number) {
	$boards = myfile("objects/boards.txt"); $id_boards = sizeof($boards);
	for($i = 0; $i < $id_boards; $i++) {
	$current_board = myexplode($boards[$i]);
	if($string == $current_board[0]) {
	$current_board[4] = $current_board[4] - $post_number;
	$boards[$i] = myimplode($current_board);
	$save = 1; break;
	}
	$boards[$i] = myimplode($current_board);
	}
	if($save == 1) myfwrite("objects/boards.txt",$boards,"w");
	else echo "Board does not exist.";
	}

	function update_last_post($board,$date,$creator_id,$thread,$post_icon) {
	$boards = myfile("objects/boards.txt"); $id_boards = sizeof($boards);
	for($i = 0; $i < $id_boards; $i++) {
	$current_board = myexplode($boards[$i]);
	if($board == $current_board[0]) {
	$current_board[9] = "$thread,$creator_id,$date,$post_icon";
	$current_board[6] = time();
	$boards[$i] = myimplode($current_board);
	$save = 1; break;
	}
	}
	if($save == 1) myfwrite("objects/boards.txt",$boards,"w");
	else echo "Board does not exist.";
	}

	function get_post($board,$thread,$post_id) {
	global $user_id,$user_logged_in;
	$post = "";
	$board_data = get_board_data($board);
	$right = 0;
	if($user_logged_in != 1) {
	if($board_data['rights'][4] == 1) $right = 1;
	}
	elseif(check_right($board,0) == 1) $right = 1;
	if($right == 1) {
	$post_file = myfile("boards/$board.$thread.txt"); $post_file_number = sizeof($post_file);
	for($i = 1; $i < $post_file_number; $i++) {
	$currentrpost_data = myexplode($post_file[$i]);
	if($currentrpost_data[0] == $post_id) {
	$post = brnl($currentrpost_data[3]);
	break;
	}
	}
	}
	return $post;
	}

	function get_post_ip($board, $thread, $post_id) {
	$post_file = myfile("boards/$board.$thread.txt");
	for($i = 1; $i < sizeof($post_file); $i++) {
	$act_post = myexplode($post_file[$i]);
	if($act_post[0] == $post_id) {
	$post_ip = $act_post[4];
	break;
	}
	}
	return $post_ip;
	}

	function rank_topic($board, $thread) {
	$topic_file = myfile("boards/$board.topics.txt"); $topic_number = sizeof($topic_file);
	for($i = 0; $i < $topic_number; $i++) {
	if($thread == killnl($topic_file[$i])) {
	if($i != $topic_number-1) {
	$topic_file[$i] = "";
	$topic_file[$topic_number] = $thread."\r\n";
	$save = 1;
	}
	break;
	}
	}
	if($save == 1) myfwrite("boards/$board.topics.txt",$topic_file,"w");
	}

	function check_name($user_name,$except) {
	$user_exists = 0; $user_name = strtolower($user_name);
	$members = myfile("objects/id_users.txt"); $members = $members[0] + 1;
	for($i = 1; $i < $members; $i++) {
	if($currentr_member = myfile("members/$i.txt")) {
	if(strtolower(killnl($currentr_member[0])) == $user_name && killnl($currentr_member[4]) != 5 && killnl($currentr_member[1]) != $except) {
	$user_exists = 1;
	break;
	}
	}
	}
	return $user_exists;
	}

	function check_email($email,$except) {
	$email_exists = 0; $email = strtolower($email);
	$members = myfile("objects/id_users.txt"); $members = $members[0] + 1;
	for($i = 1; $i < $members; $i++) {
	if($currentr_member = myfile("members/$i.txt")) {
	if(strtolower(killnl($currentr_member[3])) == $email && killnl($currentr_member[4]) != 5 && killnl($currentr_member[1]) != $except) {
	$email_exists = 1;
	break;
	}
	}
	}
	return $email_exists;
	}

	function increase_user_posts($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	$user_data[5] = killnl($user_data[5])+1; $user_data[5] .= "\r\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	}
	}
	}

	function ban_user($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	$today = date("Y-F-d / h:i:sa");
	$user_data[4] = 4; $user_data[4] .= "\n";
	$user_data[16] = killnl($user_data[16])-10; $user_data[16] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	$moderations = myfile("members/".$user_id.".moderations.txt");
	$new_id = sizeof($moderations)+1;
	$towrite = "$new_id\t$today\tBan\tN/A\t10\t\n";
	myfwrite("members/".$user_id.".moderations.txt",$towrite,"a");
	}
	}
	}

	function suspend_user($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	$today = date("Y-F-d / h:i:sa");
	$writeit = "$user_id\t$today\t\r\n";
	myfwrite("objects/suspended_users.txt",$writeit,"a");
	$user_data[4] = 6; $user_data[4] .= "\n";
	$user_data[16] = killnl($user_data[16])-5; $user_data[16] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	$moderations = myfile("members/".$user_id.".moderations.txt");
	$new_id = sizeof($moderations)+1;
	$towrite = "$new_id\t$today\tSuspension\tN/A\t5\t\n";
	myfwrite("members/".$user_id.".moderations.txt",$towrite,"a");
	$reference = trim(mutate($reference)); $notebox = nlbr(trim(mutate($notebox)));
	$new_id = myfile("members/".$user_id.".notebox.txt"); $new_id = myexplode($new_id[sizeof($new_id)-1]); $new_id = $new_id[0]+1;
	$timesent = date("Y-F-d / h:i:sa");
	$reference = "Your account has been suspended";
	$notebox = "Your account has been suspended for violating the Terms of Service. Please review your <a href=\"index.php?page=moderations\">moderations</a>. Any further violations, until your account is restored, will result in a ban. Your account will be restored within 72 hours.";
	$towrite = "$new_id\t$reference\t$notebox\t$note_box_id\t$timesent\t1\t1\t1\t\r\n";
	myfwrite("members/".$user_id.".notebox.txt",$towrite,"a");
	}
	}
	}

	function unsuspend_user($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	$user_data[4] = 3; $user_data[4] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	$reference = trim(mutate($reference)); $notebox = nlbr(trim(mutate($notebox)));
	$new_id = myfile("members/$user_id.notebox.txt"); $new_id = myexplode($new_id[sizeof($new_id)-1]); $new_id = $new_id[0]+1;
	$timesent = date("Y-F-d / h:i:sa");
	$reference = "Your account has been unsuspended";
	$notebox = "Your account has been restored from being suspended. Please note that this suspension will be taken into consideration in all further moderations.";
	$towrite = "$new_id\t$reference\t$notebox\t$note_box_id\t$timesent\t1\t1\t1\t\r\n";
	myfwrite("members/$user_id.notebox.txt",$towrite,"a");
	$suspended = myfile("objects/suspended_users.txt");
	for($i = 0; $i < sizeof($suspended); $i++) {
	$act_suspended = myexplode($suspended[$i]);
	if($user_id == $act_suspended[0]) {
	$save = 1; $suspended[$i] = ""; break;
	}
	}
	if($save == 1) {
	myfwrite("objects/suspended_users.txt",$suspended,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("8","%1: Suspended User Deleted [IP: %2]");
	}
	}
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("8","%1: User ID $user_id Unsuspended [IP: %2]");
	}
	header("Location: index.php?page=suspended_users");
	}
	}
	}

	function increase_karma($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	if($user_data[4] != 4 && $user_data[4] != 6 && $user_data[4] != 7 && $user_data[19] == 1) {
	$user_data[16] = killnl($user_data[16])+1; $user_data[16] .= "\n";
	$user_data[19] = "0"; $user_data[19] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	}
	}
	}
	}

	function decrease_karma($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	$user_data[16] = killnl($user_data[16])-1; $user_data[16] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	}
	}
	}

	function increase_days_registered($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	if($user_data[4] != 4 && $user_data[4] != 6 && $user_data[4] != 7) {
	$user_data[14] = killnl($user_data[14])+1; $user_data[14] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	}
	}
	}
	}

	function mutate($text) {
	$text = htmlspecialchars(mysslashes($text));
	return $text;
	}

	function mysslashes($text) {
	$text = str_replace("\\\"","\"",$text);
	$text = str_replace("\\\\","\\",$text);
	$text = str_replace("\\'","'",$text);
	$text = str_replace("\t","",$text);
	return $text;
	}

	function demutate($text) {
	$text = str_replace("&lt;","<",$text);
	$text = str_replace("&gt;",">",$text);
	$text = str_replace("&quot;",'"',$text);
	$text = str_replace("&amp;","&",$text);
	return $text;
	}

	function make_smilies($text) {
	$sm_file = myfile("objects/smilies.txt");
	for($i = 0; $i < sizeof($sm_file); $i++) {
	$act_sm = myexplode($sm_file[$i]);
	$text = str_replace($act_sm[1],"<img alt=\"".$act_sm[1]."\" class=\"top\" src=\"".trim($act_sm[2])."\" title=\"".$act_sm[1]."\" />",$text);
	}
	return $text;
	}

	function get_status_stars($user_status,$up,$id) {
	global $config,$cache;
	$status_pic = "";
	if($id == 1) {
	for($i = 0; $i < 10; $i++) {
	$status_pic = "<img alt=\"Stars\" class=\"stars\" src=\"images/stars/star_host.png\" title=\"Stars\" />";
	}
	}
	if($id != 1 && $user_status == 1) {
	for($i = 0; $i < 10; $i++) {
	$status_pic = "<img alt=\"Stars\" class=\"stars\" src=\"images/stars/star_administrator.png\" title=\"Stars\" />";
	}
	}
	switch($user_status) {
	case "2":
	for($i = 0; $i < 10; $i++) {
	$status_pic = "<img alt=\"Stars\" class=\"stars\" src=\"images/stars/star_moderator.png\" title=\"Stars\" />";
	}
	break;
	case "4":
	for($i = 0; $i < 0; $i++) {
	$status_pic .= "";
	}
	break;
	default:
	if($up < 0) $status_pic = "";
	else {
	if($user_status != 1 && $user_status != 2) {
	if(!$cache[statuses]) $cache[statuses] = myfile("objects/statuses.txt");
	for($i = 0; $i < sizeof($cache[statuses]); $i++) {
	$act_status = myexplode($cache[statuses][$i]);
	if($up >= $act_status[2] && $up <= $act_status[3]) {
	for($j = 0; $j <= $act_status[4]; $j++) {
	$status_pic = "<img alt=\"Stars\" class=\"stars\" src=\"images/stars/star_".$act_status[4].".png\" title=\"Stars\" />";
	}
	break;
	}
	}
	}
	}
	break;
	case "6":
	for($i = 0; $i < 0; $i++) {
	$status_pic .= "";
	}
	break;
	case "7":
	for($i = 0; $i < 0; $i++) {
	$status_pic .= "";
	}
	break;
	}
	return $status_pic;
	}

	function get_category_name($category_id,$category_file) {
	for($i = 0; $i < sizeof($category_file); $i++) {
	$act_category = myexplode( $category_file[$i]);
	if($act_category[0] == $category_id) {
	$category_name = $act_category[1];
	break;
	}
	}
	if(!$category_name) $category_name = "No category";
	return $category_name;
	}

	function get_note_subject($user_id,$note_id) {
	$notebox = myfile("members/$user_id.notebox.txt"); $note_number = sizeof($notebox);
	for($i = 0; $i < $note_number; $i++) {
	$current_note = myexplode($notebox[$i]);
	if($current_note[0] == $note_id) {
	$note_name = $current_note[1];
	break;
	}
	}
	return $note_name;
	}

	function make_note_read($user_id,$note_id) {
	$notebox = myfile("members/$user_id.notebox.txt"); $note_number = sizeof($notebox);
	for($i = 0; $i < $note_number; $i++) {
	$current_note = myexplode($notebox[$i]);
	if($current_note[0] == $note_id) {
	$current_note[7] = 0;
	$save = "yes";
	$notebox[$i] = myimplode($current_note);
	break;
	}
	}
	if($save == "yes") myfwrite("members/$user_id.notebox.txt",$notebox,"w");
	}

	function basic_html($text) {
	if(substr_count($text,"&lt;") > 0 && substr_count($text,"&gt;") > 0) {
	$text = preg_replace("#\&lt;b\&gt;(.*?)\&lt;/b\&gt;#si",'<strong>\1</strong>',$text);
	$text = preg_replace("#\&lt;strong\&gt;(.*?)\&lt;/strong\&gt;#si",'<strong>\1</strong>',$text);
	$text = preg_replace("#\&lt;i\&gt;(.*?)\&lt;/i\&gt;#si",'<em>\1</em>',$text);
	$text = preg_replace("#\&lt;em\&gt;(.*?)\&lt;/em\&gt;#si",'<em>\1</em>',$text);
	$text = preg_replace("#\&lt;u\&gt;(.*?)\&lt;/u\&gt;#si",'<ins>\1</ins>',$text);
	$text = preg_replace("#\&lt;ins\&gt;(.*?)\&lt;/ins\&gt;#si",'<ins>\1</ins>',$text);
	$text = preg_replace("#\&lt;s\&gt;(.*?)\&lt;/s\&gt;#si",'<del>\1</del>',$text);
	$text = preg_replace("#\&lt;del\&gt;(.*?)\&lt;/del\&gt;#si",'<del>\1</del>',$text);
	$text = preg_replace("#\&lt;red\&gt;(.*?)\&lt;/red\&gt;#siU",'<span style="color: #ff0000">\1</span>',$text);
	$text = preg_replace("#\&lt;green\&gt;(.*?)\&lt;/green\&gt;#siU",'<span style="color: #00cc00">\1</span>',$text);
	$text = preg_replace("#\&lt;blue\&gt;(.*?)\&lt;/blue\&gt;#siU",'<span style="color: #0000ff">\1</span>',$text);
	do {
	$text = preg_replace("#\&lt;quote\&gt;(.*?)\&lt;/quote\&gt;#siU",'
		<object>
		<table align="center" cellspacing="0" class="quote" style="width: 85%">
			<tr>
				<td class="one"><span class="normal"><strong>Original Quote</strong></span></td>
			</tr>
			<tr>
				<td class="two"><span class="normal">\1</span></td>
			</tr>
		</table>
		</object>
		',$text);
	} while(preg_match("#\&lt;quote\&gt;(.*?)\&lt;/quote\&gt;#siU",$text));
	}
	return $text;
	}

	function basic_html_profile($text) {
	if(substr_count($text,"&lt;") > 0 && substr_count($text,"&gt;") > 0) {
	$text = preg_replace("#\&lt;b\&gt;(.*?)\&lt;/b\&gt;#si",'<strong>\1</strong>',$text);
	$text = preg_replace("#\&lt;strong\&gt;(.*?)\&lt;/strong\&gt;#si",'<strong>\1</strong>',$text);
	$text = preg_replace("#\&lt;i\&gt;(.*?)\&lt;/i\&gt;#si",'<em>\1</em>',$text);
	$text = preg_replace("#\&lt;em\&gt;(.*?)\&lt;/em\&gt;#si",'<em>\1</em>',$text);
	$text = preg_replace("#\&lt;u\&gt;(.*?)\&lt;/u\&gt;#si",'<ins>\1</ins>',$text);
	$text = preg_replace("#\&lt;ins\&gt;(.*?)\&lt;/ins\&gt;#si",'<ins>\1</ins>',$text);
	$text = preg_replace("#\&lt;s\&gt;(.*?)\&lt;/s\&gt;#si",'<del>\1</del>',$text);
	$text = preg_replace("#\&lt;del\&gt;(.*?)\&lt;/del\&gt;#si",'<del>\1</del>',$text);
	$text = preg_replace("#\&lt;red\&gt;(.*?)\&lt;/red\&gt;#siU",'<span style="color: #ff0000">\1</span>',$text);
	$text = preg_replace("#\&lt;green\&gt;(.*?)\&lt;/green\&gt;#siU",'<span style="color: #00cc00">\1</span>',$text);
	$text = preg_replace("#\&lt;blue\&gt;(.*?)\&lt;/blue\&gt;#siU",'<span style="color: #0000ff">\1</span>',$text);
	}
	return $text;
	}

	function get_post_icon_address($post_icon_id) {
	$post_icon_file = myfile("objects/post_icons.txt");
	for($i = 0; $i < sizeof($post_icon_file); $i++) {
	$act_post_icon = myexplode($post_icon_file[$i]);
	if($act_post_icon[0] == $post_icon_id) {
	$post_icon_address = $act_post_icon[1];
	break;
	}
	}
	if(!$post_icon_address) $post_icon_address = "images/post_icons/icon_1.png";
	return $post_icon_address;
	}

	function nix() {
	}

	function change_user_information($user_id,$field_position,$field_number) {
	if(!$user_file = myfile("members/$user_id.txt")) return FALSE;
	$user_file_size = sizeof($user_file);
	$user_file[$field_position] = $field_number."\n";
	myfwrite("members/$user_id.txt",$user_file,"w");
	}

	function update_topic_time($board,$thread) {
	$topic_file = myfile("boards/$board.$thread.txt");
	$topic_status = myexplode($topic_file[0]); $topic_status[5] = time(); $topic_file[0] = myimplode($topic_status);
	myfwrite("boards/$board.$thread.txt",$topic_file,"w");
	}

	function get_random_number($length) {
	srand((double)microtime()*1000000);
	$x = "";
	for($i = 0; $i < $length; $i++) $x .= rand(0,9);
	return $x;
	}

	function morph_status($status,$karma) {
	global $config,$cache;
	switch($status) {
	case "1":
	$status = $config['status_administrator'];
	break;
	case "2":
	$status = $config['status_moderator'];
	break;
	case "3":
	if(!$cache[statuses]) {
	$cache[statuses] = myfile("objects/statuses.txt");
	}
	for($i = 0; $i < sizeof($cache[statuses]); $i++) {
	$act_status = myexplode($cache[statuses][$i]);
	if($karma >= $act_status[2] && $karma <= $act_status[3]) { $status = $act_status[1]; break; }
	}
	break;
	case "4":
	$status = $config['status_banned'];
	break;
	case "5":
	$status = "-5: Deleted";
	break;
	case "6":
	$status = $config['status_suspended'];
	break;
	case "7":
	$status = $config['status_closed'];
	break;
	}
	return $status;
	}

	function check_suspension($ip,$board) {
	$ips = myfile("objects/ip_suspensions.txt"); $access = 1;
	for($i = 0; $i < sizeof($ips); $i++) {
	$act_ip = myexplode($ips[$i]);
	if($act_ip[0] == $ip && $act_ip[2] == $board && ($act_ip[1] > time() || $act_ip[1] == -1)) {
	$access = 0; break;
	}
	}
	return $access;
	}

	function get_suspension_duration($ip,$board) {
	$endtime = 0;
	$ips = myfile("objects/ip_suspensions.txt"); $access = 1;
	for($i = 0; $i < sizeof($ips); $i++) {
	$act_ip = myexplode($ips[$i]);
	if($act_ip[0] == $ip && $act_ip[2] == $board && ($act_ip[1] > time() || $act_ip[1] == -1)) {
	$endtime = $act_ip[1];
	}
	}
	return $endtime;
	}

	function censor($text) {
	$censored = myfile("objects/censored_words.txt");
	for($i = 0; $i < sizeof($censored); $i++) {
	$act_cword = myexplode($censored[$i]);
	$text = eregi_replace($act_cword[1],trim($act_cword[2]),$text);
	}
	return $text;
	}

	function myfwrite($file,$towrite,$method) {
	global $cache,$file_counter,$config;
	$set_chmod = 0;
	if(!myfile_exists($file)) $set_chmod = 1;
	$fp = fopen($config['datapath'].'/'.$file,$method.'b') or die(record("1","Data Error: Data: $file; method: $method")); flock($fp,LOCK_EX);
	if(!is_array($towrite)) {
	fwrite($fp,$towrite);
	}
	else {
	for($i = 0; $i < sizeof($towrite); $i++) {
	fwrite($fp,$towrite[$i]);
	}
	}
	flock($fp,LOCK_UN); fclose($fp);
	if($set_chmod == 1) {
	@chmod($config['datapath'].'/'.$file,0777);
	}
	if($method == "w") $cache['files'][$file] = $towrite;
	else {
	$file_counter++;
	$cache['files'][$file] = @file($config['datapath'].'/'.$file);
	}
	}

	function online_write() {
	global $config;
	$online_file = myfile("objects/online_users.txt"); $online_file_size = sizeof($online_file); $x = 0;
	for($i = 0; $i < $online_file_size; $i++) {
	$currentr_online = myexplode($online_file[$i]);
	if($currentr_online[0] + ($config['online_users_timeout'] * 60) < time()) {
	$online_file[$i] = ""; $x++;
	}
	}
	if($x > 0) myfwrite("objects/online_users.txt",$online_file,"w");
	}

	function online_set() {
	global $config,$session_online,$special_id;
	if($config['show_online_users'] == 1) {
	online_write();
	$write = "";
	if($session_online != "no") {
	$online_file = myfile("objects/online_users.txt");
	for($i = 0; $i < sizeof($online_file); $i++) {
	$act_online = myexplode($online_file[$i]);
	if($act_online[1] == $session_online || $act_online[1] == $special_id) {
	$write = "yes"; $act_online[0] = time();
	$online_file[$i] = myimplode($act_online); break;
	}
	}
	if($write == "yes") myfwrite("objects/online_users.txt",$online_file,"w");
	else {
	$towrite = time()."\t$special_id\t\n";
	myfwrite("objects/online_users.txt",$towrite,"a");
	}
	}
	}
	}

	function navigation($data) {
	global $twidth,$config,$user_status,$MYSID1,$MYSID2;
	$data = str_replace("\t"," - ",$data);
	if(@func_get_arg(1) == "no") $temp_user_status = "";
	else $temp_user_status = "";
	return "
<object>
<table cellspacing=\"0\" style=\"margin-left: auto; margin-right: auto; width: $twidth\">
	<tr>
		<td style=\"text-align: left\"><h1>$data</h1></td>
	</tr>
</table>
</object>";
	}

	function record($method,$data) {
	global $config,$user_data,$user_logged_in,$REMOTE_ADDR;
	$x = explode(",",$config['record_options']);
	for($i = 0; $i < sizeof($x); $i++) {
	if($x[$i] == $method) {
	if($user_logged_in == 1) $log_name = "User ID $user_data[id] - $user_data[nick]";
	else $log_name = "User ID 0 - Guest";
	$date1 = date("Y-F-d");
	$data = str_replace("%1",$log_name,$data);
	$data = str_replace("%2",$REMOTE_ADDR,$data);
	$data = date("Y-F-d / h:i:sa")." ".$data."\r\n";
	myfwrite("records/$date1.txt",$data,"a");
	break;
	}
	}
	}

	function myfile($file) {
	global $file_counter,$cache,$config;
	if(!isset($cache['files'][$file]) || $config['use_file_caching'] != 1) {
	$cache['files'][$file] = @file($config['datapath'].'/'.$file);
	$file_counter++;
	}
	return $cache['files'][$file];
	}

	function administrator() {
	global $administrator,$twidth,$txt,$config,$twidth_old;
	$twidth_old = $twidth;
	if(!isset($txt)) {
	include("text.php");
	}
	$administrator = 1; $twidth = $twidth;
	}

	function increase_topic_views($board,$thread,$number) {
	if($topic_file = myfile("boards/$board.$thread.txt")) {
	$topic_info = myexplode($topic_file[0]);
	$topic_info[6] += $number;
	$topic_file[0] = myimplode($topic_info);
	myfwrite("boards/$board.$thread.txt",$topic_file,"w");
	}
	}

	function get_time_string($data) {
	return mktime(substr($data,8,2),substr($data,10,2),0,substr($data,4,2),substr($data,6,2),substr($data,0,4)) + date("Z");
	}

	function update_last_posts($board,$thread,$user_id,$date) {
	$lposts = myfile("objects/newest_posts.txt");
	if($lposts[0] == "") $lposts = "$board,$thread,$user_id,$date"."\t";
	else {
	$lposts = myexplode($lposts[0]);
	$lposts[19] = $lposts[18]; $lposts[18] = $lposts[17];
	$lposts[17] = $lposts[16]; $lposts[16] = $lposts[15];
	$lposts[15] = $lposts[14]; $lposts[14] = $lposts[13];
	$lposts[13] = $lposts[12]; $lposts[12] = $lposts[11];
	$lposts[11] = $lposts[10]; $lposts[10] = $lposts[9];
	$lposts[9] = $lposts[8]; $lposts[8] = $lposts[7];
	$lposts[7] = $lposts[6]; $lposts[6] = $lposts[5];
	$lposts[5] = $lposts[4]; $lposts[4] = $lposts[3];
	$lposts[3] = $lposts[2]; $lposts[2] = $lposts[1];
	$lposts[1] = $lposts[0];
	$lposts[0] = "$board,$thread,$user_id,$date";
	$lposts = myimplode($lposts);
	}
	myfwrite("objects/newest_posts.txt",$lposts,"w");
	}

	function mycrypt($text) {
	return crypt($text,"Xb");
	}

	function get_message($data) {
	global $txt,$twidth,$cellspacing;
	if(func_num_args() > 1) {
	$txt['Navigation'][$data][1] .= func_get_arg(1);
	}
	if(func_num_args() > 2) {
	$temp = func_get_arg(2);
	$txt['Navigation'][$data][1] = sprintf($txt['Navigation'][$data][1],$temp);
	}
	return "
<object>
<table cellspacing=\"$cellspacing\" class=\"table\" style=\"width: $twidth\">
	<tr>
		<td class=\"heading1\" style=\"text-align: left\"><span class=\"heading\">".$txt['Navigation'][$data][0]."</span></td>
	</tr>
	<tr>
		<td class=\"one\" style=\"text-align: center\"><span class=\"normal\"><br />".$txt['Navigation'][$data][1]."<br /><br /></span></td>
	</tr>
</table>
</object><br />
";
	}

	function myexplode($data) {
	return explode("\t",$data);
	}

	function myimplode($data) {
	return implode("\t",$data);
	}

	function get_title_data($title_id) {
	$title_data = FALSE;
	$titles_file = myfile("objects/titles.txt");
	for($i = 0; $i < sizeof($titles_file); $i++) {
	$act_title = myexplode($titles_file[$i]);
	if($act_title[0] == $title_id) {
	$title_data = array();
	$title_data['name'] = $act_title[1];
	$title_data['id'] = $act_title[2];
	break;
	}
	}
	return $title_data;
	}

	function myfile_exists($file) {
	global $config;
	return file_exists($config['datapath'].'/'.$file);
	}

	function array_mutate(&$item) {
	$item = mutate($item);
	}

	function get_board_rights($board) {
	global $cache;
	if(isset($cache['boardrights'][$board])) return $cache['boardrights'][$board];
	else {
	$board_file = myfile('objects/boards.txt');
	for($i = 0; $i < sizeof($board_file); $i++) {
	$act_board = myexplode($board_file[$i]);
	if($act_board[0] == $board) {
	$cache['boardrights'][$board] = explode(',',$act_board[10]);
	return $cache['boardrights'][$board];
	break;
	}
	}
	}
	}

	function check_right($board,$what) {
	global $user_id,$user_data,$user_logged_in,$cache;
	if(isset($cache['rights'][$board][$what])) return $cache['rights'][$board][$what];
	else {
	$right = 0;
	if($user_logged_in == 1) {
	$board_rights = get_board_rights($board);
	if($user_data['status'] == 1 || $user_data['status'] == 2) $right = 1;
	else {
	$board_special_rights = myfile("boards/$board.rights.txt");
	if($board_rights[$what] == 1) {
	$right = 1;
	for($i = 0; $i < sizeof($board_special_rights); $i++) {
	$act_right = myexplode($board_special_rights[$i]);
	if(($act_right[1] == 1 && $act_right[2] == $user_id) || ($act_right[1] == 2 && $user_data[15] == $act_right[2])) {
	if($act_right[$what+3] != 1) $right = 0;
	else $right = 1;
	break;
	}
	}
	}
	else {
	$right = 0;
	for($i = 0; $i < sizeof($board_special_rights); $i++) {
	$act_right = myexplode($board_special_rights[$i]);
	if(($act_right[1] == 1 && $act_right[2] == $user_id) || ($act_right[1] == 2 && $user_data[15] == $act_right[2])) {
	if($act_right[$what+3] == 1) $right = 1;
	else $right = 0;
	break;
	}
	}
	}
	}
	}
	return $right;
	}
	}
?>