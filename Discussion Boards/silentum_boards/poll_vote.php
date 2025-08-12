<?
	/*
	Silentum Boards v1.4.3
	poll_vote.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once('permission.php');

	if(isset($edit)) {
	header("Location: index.php?page=edit_poll&board=$board&thread=$thread&poll_id=$poll_id");
	exit;
	}

	if(!$poll_file = myfile("boards/polls/$poll_id.1.txt")) die("There was an error while loading the poll data.");

	$poll_data = myexplode($poll_file[0]);
	$poll_where = explode(',',$poll_data[5]);
	$board_data = get_board_data($board,$poll_where[0]);
	$poll_voters = myfile("boards/polls/$poll_id.2.txt"); $poll_voters = explode(',',$poll_voters[0]);

	$right = 0;
	if($user_logged_in != 1) {
	if($board_data['rights'][6] == 1) $right = 1;
	else {
	include("board_top.php");
	echo navigation($txt['Navigation']['Not_Logged_In'][0]);
	echo get_message('Not_Logged_In','<br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	}
	else {
	if(check_right($board,0) != 1) {
	include("board_top.php");
	echo navigation($txt['Navigation']['Restricted_Board'][0]);
	echo get_message('Restricted_Board','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	else $right = 1;
	}

	if($right == 1) {
	$temp_var = "session_poll_$poll_id";
	$temp_var2 = "cookie_poll_$poll_id";

	if($user_logged_in != 1 && $poll_data[0] != 1) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\"".$topic_data[1]."</a>\t".$txt['Navigation']['Logged_In_To_Vote'][0]);
	echo get_message('Logged_In_To_Vote');
	}
	elseif(($user_logged_in == 1 && in_array($user_id,$poll_voters)) || isset($$temp_var) || isset($$temp_var2)) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\"".$topic_data[1]."</a>\t".$txt['Navigation']['Already_Voted'][0]);
	echo get_message('Already_Voted');
	}
	else {
	for($i = 1; $i < sizeof($poll_file); $i++) {
	$act_poll = myexplode($poll_file[$i]);
	if($act_poll[0] == $vote_id) {
	$poll_data[4]++;
	$act_poll[2]++;
	$poll_file[$i] = myimplode($act_poll);
	$poll_file[0] = myimplode($poll_data);
	myfwrite("boards/polls/$poll_id.1.txt",$poll_file,"w");
	record("8","%1: Poll Vote Added [IP: %2]");
	if($user_logged_in == 1) {
	if($poll_voters[0] == '') $poll_voters[0] = $user_id;
	else $poll_voters[] = $user_id;
	myfwrite("boards/polls/$poll_id.2.txt",implode(',',$poll_voters),'w');
	}
	$$temp_var = 1;
	session_register($temp_var); rank_topic($board,$thread);
	setcookie("cookie_poll_$poll_id",1,time()+(3600*24*365),$config['default_directory']);
	header("Location: index.php?method=topic&board=$board&thread=$thread");
	exit;
	}
	}
	}
	}
?>