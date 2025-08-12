<?
	/*
	Silentum Boards v1.4.3
	board_top.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	if($config['show_page_execution_time'] == 1) {
	$mtime = explode(" ",microtime());
	$starttime = $mtime[1] + $mtime[0];
	}

	if($config['use_output_caching'] == 1) {
	ob_start();
	}

	if($user_logged_in == 1) {
	online_set();
	}
	$online_file = myfile("objects/online_users.txt"); $online_file_size = sizeof($online_file);
	$n1 = 0; $n2 = 0; $online_member = array();
	for($i = 0; $i < $online_file_size; $i++) {
	$currentr_online = myexplode($online_file[$i]);
	$new_time = $currentr_online[0] + ($config['online_users_timeout'] * 144);
	if($new_time >= time()) {
	if(substr($currentr_online[1],0,5) == "") $n1++;
	else { $online_member[$n2] = " "; $n2++; }
	}
	if($online_member[0] == "") $members = ""; else $members = "".implode($online_member,"");
	if($online_member[0] != "") $members=implode($online_member,"")."".sizeof($online_member)."";
	if(sizeof($online_member) > 1) $members .= "";
	if($n1 >= 1 && $members != "") $members .= "";
	}
	if(sizeof($online_member) < 1 || sizeof($online_member) > 1) $users = "users"; else $users = "user";
	if(sizeof($online_member) < 1 || sizeof($online_member) > 1) $are = "are"; else $are = "is";
	if(trim($members) == "") $members = "0";
	if($config['show_online_users'] == 1) $showonline = "
	<tr>
		<td class=\"heading2\" colspan=\"5\" style=\"text-align: center\"><span class=\"heading\">There ".$are." currently "; else $showonline = "";
	if($config['show_online_users'] == 1) $showonline2 = trim($members)." registered ".$users." online.</span></td>
	</tr>";
	if($config['enable_search'] == 1) $showsearch = "
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php?page=search\" title=\"Search for a certain topic or post.\">Search</a></strong></span></td>"; else $showsearch = "";
	if($config['enable_top_10'] == 1) $showtop10 = "
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php?page=top_10\" title=\"The top 10 users ranked by Karma.\">Top 10 Users</a></strong></span></td>"; else $showtop_10 = "";

	if($user_logged_in == 1) {
	$user_file1 = myfile("members/".$user_data['id'].".txt"); $user_file1_size = sizeof($user_file1);
	$user_file1[19] = "1\n";
	myfwrite("members/".$user_data['id'].".txt",$user_file1,"w");
	$l_user_id = $user_data['id'];
	include("last_activity.php");
	$queue = myfile("objects/queue.txt");
	$suspendedusers = myfile("objects/suspended_users.txt");
		
	$unread = 0;
	$user_notes = myfile("members/$user_id.notebox.txt"); $user_notes_number = sizeof($user_notes);
	for($i = 0; $i < $user_notes_number; $i++) {
	$current_note = myexplode($user_notes[$i]);
	if($current_note[7] == 1) $unread++;
	}
		
	if($unread == 0) $unread = "";

	elseif($unread == 1) $noteamount = "
	<tr>
		<td class=\"error\" colspan=\"5\" style=\"text-align: center; width: 100%\"><span class=\"normal\"><strong>You have ".$unread." new note. View it <a href=\"index.php?page=note_box\">here</a>.</strong></span></td>
	</tr>";

	elseif($unread > 1) $noteamount = "
	<tr>
		<td class=\"error\" colspan=\"5\" style=\"text-align: center; width: 100%\"><span class=\"normal\"><strong>You have ".$unread." new notes. View them <a href=\"index.php?page=note_box\">here</a>.</strong></span></td>
	</tr>";

	if($user_data['status'] == "1" || $user_data['status'] == "2") $queuelink =	"(<a href=\"index.php?page=queue\" title=\"A list of the reported messages.\">Queue</a> - ".sizeof($queue).") ";

	$newest_member = myfile("objects/id_users.txt");
	$number_of_members = myfile("objects/id_users.txt");
	$today = date("Y-F-d / h:i:sa");
		
	$tools = "<object>
<table cellspacing=\"$cellspacing\" class=\"table\" style=\"width: $twidth\">$noteamount
	<tr>
		<td class=\"heading1\" colspan=\"2\" style=\"width: 50%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php?page=profile\" title=\"View your current profile.\">".$user_data['nick']."</a> (<a href=\"index.php?page=logout\" title=\"Logout from your current user name.\">Logout</a>) - <a href=\"index.php?page=user_cp\" title=\"All your user options are here.\">User CP</a> $queuelink</strong></span></td>
		<td class=\"heading1\" colspan=\"3\" style=\"text-align: right; width: 50%\" valign=\"top\"><span class=\"normal\"><strong>$today</strong></span></td>
	</tr>$showonline$showonline2
	<tr>
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php\" title=\"Return to the main board listing.\">Board Index</a></strong></span></td>
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php?page=faqs\" title=\"Find the answers to some of the most frequently asked questions.\">FAQs</a></strong></span></td>$showsearch
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php?page=terms_of_service\" title=\"Read the rules and regulations of the boards.\">Terms of Service</a></strong></span></td>$showtop10
	</tr>
</table>
";
	}
	else {
	$newest_member = myfile("objects/id_users.txt");
	$number_of_members = myfile("objects/id_users.txt");
	$today = date("Y-F-d / h:i:sa");

	$tools = "<object>
<table cellspacing=\"$cellspacing\" class=\"table\" style=\"width: $twidth\">
	<tr>				
		<td class=\"heading1\" colspan=\"2\" style=\"width: 50%\" valign=\"top\"><span class=\"normal\"><strong>Guest (<a href=\"index.php?page=login\" title=\"Login to an existing account.\">Login</a>) - <a href=\"index.php?page=register\" title=\"Register a new account.\">Register</a></strong></span></td>
		<td class=\"heading1\" colspan=\"3\" style=\"text-align: right; width: 50%\" valign=\"top\"><span class=\"normal\"><strong>$today</strong></span></td>
	</tr>$showonline$showonline2
	<tr>
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php\" title=\"Return to the main board listing.\">Board Index</a></strong></span></td>
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php?page=faqs\" title=\"Find the answers to some of the most frequently asked questions.\">FAQs</a></strong></span></td>$showsearch
		<td class=\"one\" style=\"text-align: center; width: 20%\" valign=\"top\"><span class=\"normal\"><strong><a href=\"index.php?page=terms_of_service\" title=\"Read the rules and regulations of the boards.\">Terms of Service</a></strong></span></td>$showtop10
	</tr>
</table>
";

	}

	$board_data = get_board_data($board);
	if($user_logged_in != 1) {
	$whichstylesheet = "stylesheets/blue.css";
	}
	else $whichstylesheet = $user_data['stylesheet'];
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
<table cellspacing="0" style="margin-left: auto; margin-right: auto; width: <?=$twidth?>">
	<tr>
		<td style="text-align: center" valign="middle"><span class="title"><?=trim($config['board_name'])?></span></td>
	</tr>
</table>
</object><br />
<?=$tools?>
</object>