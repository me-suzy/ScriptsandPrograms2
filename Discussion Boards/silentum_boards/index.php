<?
	/*
	Silentum Boards v1.4.3
	index.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");
	require_once("text.php");

	$allowed = 1;
	if(!isset($page) && isset($silentum)) $page = $silentum;
	elseif(!isset($page)) $page = "";

	if($session_connection != "1") {
	$session_connection = 1;
	session_register("session_connection");
	$logging = explode(',',$config['record_options']);
	if(in_array(6,$logging)) {
	record("6","User Connected [IP: %2]");
	}
	}

	if($config['offline'] == 1 && $user_data['status'] != 1) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<!-- Silentum Boards copyright 2005 "HyperSilence" -->
<head>
	<link href="images/shortcut_icon.png" rel="shortcut icon" type="image/png" />
	<link href="<?=$whichstylesheet?>" rel="stylesheet" type="text/css" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=ISO-8859-1" />
	<meta content="HyperSilence" name="author" />
	<meta content="EditPlus Text Editor v2.12" name="generator" />
	<meta content="hypersilence,hyper,silence,silentum,free,php,script,simple,easy,discussion,message,board,forum,counter,form,formsend,videogame" name="keywords" />
	<title><?=$config['board_name']?><?=$htmltitle?></title>
</head>
<body>
<object>
<table cellspacing="<?=$cellspacing?>" style="width: <?=$twidth?>">
	<tr>
		<td style="text-align: center" valign="middle"><strong><?=$config['board_name']?></strong><br /><?=$config['offline_message']?></td>
	</tr>
</table>
</object>
</body>
</html>
<?
	error_reporting(0);
	$allowed = 0;
	}

	elseif(check_suspension($REMOTE_ADDR,-1) != 1) {
	$endtime = get_suspension_duration($REMOTE_ADDR,-1);
	if($endtime == -1) $endtime = "You have been IP banned.";
	else {
	$endtime = ceil(($endtime - time()) / 60);
	$endtime = "You have been IP suspended for ".$endtime." minutes.";
	}

	include("board_top.php");
	echo navigation("Access Denied");
	echo get_message("IP_Suspended",'<br />'.$endtime);
	include("board_bottom.php");

	$allowed = 0;
	}

	elseif($config['must_be_logged_in'] == 1 && $user_logged_in != 1 && $page != "register" && $page != "login" && $page != "tos" & $page != "sendpw") {
	include("board_top.php");
	echo navigation("Access Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	include("board_bottom.php");
	$allowed = 0;
	}

	if($allowed == 1) {
	switch($page) {

	case "faqs":
	$htmltitle = " - FAQs";
	include("board_top.php");
	include("navigation.php");
	include("board_bottom.php");
	break;

	case "login":
	$htmltitle = " - Login";
	include("login_logout.php");
	include("board_bottom.php");
	break;

	case "logout":
	$htmltitle = " - Logout";
	include("login_logout.php");
	break;

	case "mod_post":
	include("board_top.php");
	include("mod_post.php");
	include("board_bottom.php");
	break;

	case "moderations":
	$htmltitle = " - User CP - Moderations";
	include("board_top.php");
	include("moderations_note_box.php");
	include("board_bottom.php");
	break;

	case "note_box":
	$htmltitle = " - User CP - Note Box";
	include("moderations_note_box.php");
	include("board_bottom.php");
	break;

	case "poll_vote":
	include('poll_vote.php');
	break;

	case "post_poll":
	setcookie("silentumwhere","index.php?page=post_poll&board=$board");
	$htmltitle = " - ".get_board_name($board)." - Post Poll";
	include("board_top.php");
	include("post_poll.php");
	include("board_bottom.php");
	break;

	case "post_reply":
	setcookie("silentumwhere","index.php?page=post_reply&board=$board&thread=$thread");
	if($config['enable_censor'] == 1) $topicname = censor(get_thread_name($board,$thread)); else $topicname = get_thread_name($board,$thread);
	$htmltitle = " - ".get_board_name($board)." - ".$topicname." - Post Reply";
	include("board_top.php");
	include("post_reply.php");
	include("board_bottom.php");
	break;

	case "post_topic":
	setcookie("silentumwhere","index.php?page=post_topic&board=$board");
	$htmltitle = " - ".get_board_name($board)." - Post Topic";
	include("board_top.php");
	include("post_topic.php");
	include("board_bottom.php");
	break;

	case "profile":
	include("profile.php");
	include("board_bottom.php");
	break;

	case "queue":
	$htmltitle = " - Queue";
	include("mod_queue.php");
	break;

	case "register":
	$htmltitle = " - Register";
	include("register.php");
	include("board_bottom.php");
	break;

	case "report_post":
	if($config['enable_censor'] == 1) $topicname = censor(get_thread_name($board,$thread)); else $topicname = get_thread_name($board,$thread);
	$htmltitle = " - ".get_board_name($board)." - ".$topicname." - Report Post";
	include("board_top.php");
	include("report_post.php");
	break;

	case "search":
	$htmltitle = " - Search";
	include("board_top.php");
	include("navigation.php");
	include("board_bottom.php");
	break;

	case "suspended_users":
	$htmltitle = " - Suspended Users";
	include("mod_suspended_users.php");
	break;

	case "terms_of_service":
	$htmltitle = " - Terms of Service";
	include("board_top.php");
	include("navigation.php");
	include("board_bottom.php");
	break;

	case "top_10":
	$htmltitle = " - Top 10 Users";
	include("board_top.php");
	include("navigation.php");
	include("board_bottom.php");
	break;

	case "topic":
	include("board_top.php");
	include("mod_topic.php");
	include("board_bottom.php");
	break;

	case "user_cp":
	$htmltitle = " - User CP";
	include("user_control_panel.php");
	break;

	default:
	if(check_suspension($REMOTE_ADDR,$board) != 1) {
	$endtime = get_suspension_duration($REMOTE_ADDR,$board);
	if($endtime == -1) $endtime = "You have been IP banned.";
	else {
	$endtime = ceil(($endtime - time()) / 60);
	$endtime = "You have been IP suspended for ".$endtime." minutes.";
	}

	include("board_top.php");
	echo navigation("<a href=\"index.php?method=board&board=$board\">".get_board_name($board)."</a>\t".$txt['Navigation']['IP_Suspended'][0]);
	echo get_message("IP_Suspended",'<br />'.$endtime);
	include("board_bottom.php");
	}
	else {
	if($method == "board") {
	$htmltitle = " - ".get_board_name($board)."";
	setcookie("silentumwhere","index.php?method=board&board=$board");
	}
	elseif($method == "topic") {
	if($config['enable_censor'] == 1) $topicname = censor(get_thread_name($board,$thread)); else $topicname = get_thread_name($board,$thread);
	$htmltitle = " - ".get_board_name($board)." - ".$topicname."";
	setcookie("silentumwhere","index.php?method=topic&board=$board&thread=$thread");

	$temp_var = "session.tview.$board.$thread";
	if($$temp_var != 1) {
	$$temp_var = 1;
	increase_topic_views($board,$thread,1);
	session_register($temp_var);
	}

	}
	else setcookie("silentumwhere","index.php");

	include("board_top.php");
	include("board.php");
	include("board_bottom.php");
	}
	break;

	}
	}
?>