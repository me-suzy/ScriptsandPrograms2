<?
	/*
	Silentum Boards v1.4.3
	profile.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	if(!$id) $id = $user_id;
	if($id == 0 || !$profile_data = get_user_data($id)) {
	}

	if($user_logged_in != 1) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=profile\">View Profile</a>\tAccess Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if(!myfile_exists("members/".$profile_data['id'].".txt")) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=profile\">View Profile</a>\tUnknown User");
	echo get_message('Unknown_User','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}

	else {
	switch($method) {

	default:
	$htmltitle = " - View Profile";
	include("board_top.php");
	if($user_data['status'] == "4") {
	echo navigation("<a href=\"index.php?page=profile\">View Profile</a>\tAccess Denied");
	echo get_message('Banned','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($config['enable_censor'] == 1) {
	$profile_data[8] = censor($profile_data[8]);
	$profile_data[9] = censor($profile_data[9]);
	$profile_data[12] = censor($profile_data[12]);
	$profile_data[13] = censor($profile_data[13]);
	$profile_data[17] = censor($profile_data[17]);
	}
	if($profile_data['showemail'] != 1 && $user_data[4] != "1" && $user_data[4] != "2") $email1 = "<span class=\"unknown\">Hidden</span>";
	else $email1 = $profile_data['publicemail'];
	if($profile_data['nick'] != "") $navigation = $profile_data['nick']; else $navigation = "Guest";
	$registration_date ="".makeregdate($profile_data['6'])."";
	echo navigation("View Profile - ".$navigation);
	$l_user_id = $profile_data['id'];
	include("last_activity.php");
	$moderations = myfile("members/".$profile_data['id'].".moderations.txt");
	$notebox = myfile("members/".$profile_data['id'].".notebox.txt");
	if($profile_data['title'] != "") {
	$title_data = get_title_data($profile_data['title']);
	$profile_data['title_name'] = $title_data['name'];
	}
	$posts = 0; $topics = 0;
	$category = myfile("objects/categories.txt"); $category_size = sizeof($category);
	$boards = myfile("objects/boards.txt"); $boards_number = sizeof($boards);
	for($k = 0; $k < $category_size; $k++) {
	$current_category = myexplode($category[$k]); 
	$x = FALSE;
	while($act_value = each($boards)) {
	$current_board = myexplode($act_value[1]);
	$posts += $current_board[4]; $topics += $current_board[3];
	unset($boards[$act_value[0]]);
	}
	reset($boards);
	}
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4" style="text-align: left"><span class="heading">View Profile</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4" style="text-align: left"><span class="heading">User Information</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>User ID</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="top"><span class="normal"><?=$profile_data['id']?> <? if($user_data['id'] == 1) echo "<a href=\"administrator_user.php?method=edit&amp;id=$id\">Edit User</a>"; ?></span></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong>E-mail Address</strong></span></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><? if($profile_data['showemail'] == 1 && $profile_data['status'] != "4") echo $profile_data['publicemail']?><? if($profile_data['showemail'] != 1 && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo "<span class=\"unknown\">Hidden</span>"?><? if($profile_data['status'] == "4") echo "<span class=\"unknown\">User Banned - Cannot View</span>";?><? if($profile_data['status'] == "6") echo "<span class=\"unknown\">User Suspended - Cannot View</span>";?></span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>User Name</strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><?=$profile_data['nick']?></span></td>
		<td class="two" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Registration Date</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="middle"><span class="normal"><?=$registration_date?></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong>User Status</strong></span></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><? if($profile_data['id'] != "1") echo "<strong>".morph_status($profile_data['status'],$profile_data['karma'])."</strong> ".get_status_stars($profile_data['status'],$profile_data['karma'],$profile_data['id']).""; else echo "<strong>".$config['status_host']."</strong> ".get_status_stars($profile_data['status'],$profile_data['karma'],$profile_data['id']).""; ?></span></td>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>Last Activity</strong></span></td>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><? if($l_log_in != ".. ::" && $l_log_in != "" ) echo $l_log_in; else echo "<span class=\"unknown\">Never Logged In</span>"; ?></span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>Karma</strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><?=$profile_data['karma']?> (<?=$profile_data['possiblekarma']?> Possible)</span></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>Stylesheet</strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><?=$profile_data['stylesheet']?></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Signature</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="top"><span class="normal"><? if($profile_data['signature'] != "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo basic_html_profile($profile_data['signature'])?><? if($profile_data['signature'] == "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo "<span class=\"unknown\">Unknown</span>"?><? if($profile_data['status'] == "4") echo "<span class=\"unknown\">User Banned - Cannot View</span>"?><? if($profile_data['status'] == "6") echo "<span class=\"unknown\">User Suspended - Cannot View</span>"?></span></td>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Quote</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="top"><span class="normal"><? if($profile_data['quote'] != "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo basic_html_profile($profile_data['quote'])?><? if($profile_data['quote'] == "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo "<span class=\"unknown\">Unknown</span>"?><? if($profile_data['status'] == "4") echo "<span class=\"unknown\">User Banned - Cannot View</span>"?><? if($profile_data['status'] == "6") echo "<span class=\"unknown\">User Suspended - Cannot View</span>"?></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading2" colspan="4" style="text-align: left"><span class="heading">Instant Message Information</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>AIM Handle</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="middle"><span class="normal"><? if($profile_data['aim'] != "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo $profile_data['aim']?><? if($profile_data['aim'] == "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo "<span class=\"unknown\">Unknown</span>"?><? if($profile_data['status'] == "4") echo "<span class=\"unknown\">User Banned - Cannot View</span>"?><? if($profile_data['status'] == "6") echo "<span class=\"unknown\">User Suspended - Cannot View</span>"?></span></td>
		<td class="two" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>MSN Handle</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="top"><span class="normal"><? if($profile_data['msn'] != "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo $profile_data['msn']?><? if($profile_data['msn'] == "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo "<span class=\"unknown\">Unknown</span>"?><? if($profile_data['status'] == "4") echo "<span class=\"unknown\">User Banned - Cannot View</span>"?><? if($profile_data['status'] == "6") echo "<span class=\"unknown\">User Suspended - Cannot View</span>"?></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>ICQ Handle</strong></span></td>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><? if($profile_data['icq'] != "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo $profile_data['icq']?><? if($profile_data['icq'] == "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo "<span class=\"unknown\">Unknown</span>"?><? if($profile_data['status'] == "4") echo "<span class=\"unknown\">User Banned - Cannot View</span>"?><? if($profile_data['status'] == "6") echo "<span class=\"unknown\">User Suspended - Cannot View</span>"?></span></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong>Yahoo! Handle</strong></span></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><? if($profile_data['yahoo'] != "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo $profile_data['yahoo']?><? if($profile_data['yahoo'] == "" && $profile_data['status'] != "4" && $profile_data['status'] != "6") echo "<span class=\"unknown\">Unknown</span>"?><? if($profile_data['status'] == "4") echo "<span class=\"unknown\">User Banned - Cannot View</span>"?><? if($profile_data['status'] == "6") echo "<span class=\"unknown\">User Suspended - Cannot View</span>"?></span></td>
	</tr>
</table>
</object><br />
<? if($user_data['status'] == "1" || $user_data['status'] == "2") { ?><object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading2" colspan="4" style="text-align: left"><span class="heading">User Map (Moderator-Only)</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Last Login IP Address</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="middle"><span class="normal"><? if(!myfile_exists("members/".$profile_data['id'].".information.txt") || $ipaddress == "") echo "<span class=\"unknown\">Unknown</span>"; else echo $ipaddress?></span></td>
		<td class="two" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>User's Total Posts</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="middle"><span class="normal"><? if($profile_data['posts'] == "") echo "<span class=\"unknown\">Unknown</span>"; else echo $profile_data['posts']?></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Last Page Accessed</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="top"><span class="normal"><?
		$lastpageaccessed = str_replace("&", "&amp;", $lastpageaccessed);
		if(!myfile_exists("members/".$profile_data['id'].".information.txt") || $lastpageaccessed == "") echo "<span class=\"unknown\">Unknown</span>"; else echo trim($lastpageaccessed)?></span></td>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>User's Moderations</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="top"><span class="normal"><? if(sizeof($moderations) == 0 || sizeof($moderations) >= 2 || !myfile_exists("members/".$profile_data['id'].".moderations.txt")) $pluralmod = "Moderations"; else $pluralmod = "Moderation";?><? if(sizeof($moderations) == 0 || !myfile_exists("members/".$profile_data['id'].".moderations.txt")) echo "<a href=\"index.php?page=moderations&amp;moderations_id=".$profile_data['id']."\">0 ".$pluralmod."</a>";
		else echo "<a href=\"index.php?page=moderations&amp;moderations_id=".$profile_data['id']."\">".sizeof($moderations)." ".$pluralmod."</a>";?> (Click to view)</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>User's Time Zone</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="top"><span class="normal">GMT<? if($profile_data['timezone'] != "" && $profile_data['status'] != "4" && $profile_data['status'] != "6" && $profile_data['status'] != "7") echo $profile_data['timezone']; ?><? if($profile_data['status'] == "4" || $profile_data['status'] == "6" || $profile_data['status'] == "7") echo $profile_data['timezone']; ?></span></td>
		<td class="two" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>User's Note Box</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="top"><span class="normal"><? if(sizeof($notebox) == 0 || sizeof($notebox) >= 2 || !myfile_exists("members/".$profile_data['id'].".notebox.txt")) $plural = "Notes"; else $plural = "Note";?><? if(sizeof($notebox) == 0 || !myfile_exists("members/".$profile_data['id'].".notebox.txt")) echo "<a href=\"index.php?page=note_box&amp;note_box_id=".$profile_data['id']."\">0 ".$plural."</a>";
		else echo "<a href=\"index.php?page=note_box&amp;note_box_id=".$profile_data['id']."\">".sizeof($notebox)." ".$plural."</a>";?> (Click to view)</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>User's Agent</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="top"><span class="normal"><? if(!myfile_exists("members/".$profile_data['id'].".information.txt") || $remoteagent == "") echo "<span class=\"unknown\">Unknown</span>"; else echo trim($remoteagent)?></span></td>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Registration E-mail</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="top"><span class="normal"><? if($profile_data['email'] == "") echo "<span class=\"unknown\">Unknown</span>"; else echo $profile_data['email']?></span></td>
	</tr>
</table>
</object><br />
<?
	}

	break;

	case "preferences":
	$htmltitle = " - User Preferences";
	include("board_top.php");
	if($user_data['status'] == "4") {
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=profile&amp;method=preferences\">User Preferences</a>\tAccess Denied");
	echo get_message('Banned','<br /><br />'.sprintf($txt['Links']['User_Control_Panel'],"<a href=\"index.php?page=user_cp\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['status'] == "6") {
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=profile&amp;method=preferences\">User Preferences</a>\tAccess Denied");
	echo get_message('Suspended','<br /><br />'.sprintf($txt['Links']['User_Control_Panel'],"<a href=\"index.php?page=user_cp\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_logged_in != 1) { echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=profile&amp;method=preferences\">User Preferences</a>\t".$txt['Navigation']['Restricted']['0']); echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	elseif($user_id != $id && $user_data['status'] != 1) { echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=profile&amp;method=preferences\">User Preferences</a>\t".$txt['Navigation']['Restricted']['0']); echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	else {

	$displaypage = 1;
	$error = "";
	$update_text = "";

	isset($_POST['new_publicemail']) ? $n_publicemail = nlbr(trim(mutate($_POST['new_publicemail']))) : $n_publicemail = $profile_data['publicemail'];
	isset($_POST['new_posts']) ? $n_posts = nlbr($_POST['new_posts']) : $n_posts = $profile_data['posts'];
	isset($_POST['new_possiblekarma']) ? $n_possiblekarma = nlbr($_POST['new_possiblekarma']) : $n_possiblekarma = $profile_data['possiblekarma'];
	isset($_POST['new_regdat']) ? $n_regdat = nlbr($_POST['new_regdat']) : $n_regdat = $profile_data['6'];
	isset($_POST['new_aim']) ? $n_aim = nlbr($_POST['new_aim']) : $n_aim = $profile_data['aim'];
	isset($_POST['new_msn']) ? $n_msn = nlbr($_POST['new_msn']) : $n_msn = $profile_data['msn'];
	isset($_POST['new_yahoo']) ? $n_yahoo = nlbr($_POST['new_yahoo']) : $n_yahoo = $profile_data['yahoo'];
	isset($_POST['new_icq']) ? $n_icq = nlbr($_POST['new_icq']) : $n_icq = $profile_data['icq'];
	isset($_POST['new_signature']) ? $n_signature = nlbr(mutate($_POST['new_signature'])) : $n_signature = $profile_data['signature'];
	isset($_POST['new_karma']) ? $n_karma = nlbr($_POST['new_karma']) : $n_karma = $profile_data['karma'];
	isset($_POST['new_quote']) ? $n_quote = nlbr(mutate($_POST['new_quote'])) : $n_quote = $profile_data['quote'];
	isset($_POST['new_timezone']) ? $n_timezone = nlbr($_POST['new_timezone']) : $n_timezone = $profile_data['timezone'];
	isset($_POST['new_pw1']) ? $n_pw1 = nlbr($_POST['new_pw1']) : $n_pw1 = "";
	isset($_POST['new_pw2']) ? $n_pw2 = nlbr($_POST['new_pw2']) : $n_pw2 = "";

	$n_display1 = $profile_data['showemail'];
	$n_display2 = $profile_data['showsignatures'];
	$n_display3 = $profile_data['showtitles'];
	$n_display4 = $profile_data['showsmilies'];
	$posts = 0; $topics = 0;
	$category = myfile("objects/categories.txt"); $category_size = sizeof($category);
	$boards = myfile("objects/boards.txt"); $boards_number = sizeof($boards);
	for($k = 0; $k < $category_size; $k++) {
	$current_category = myexplode($category[$k]); 
	$x = FALSE;
	while($act_value = each($boards)) {
	$current_board = myexplode($act_value[1]);
	$posts += $current_board[4]; $topics += $current_board[3];
	unset($boards[$act_value[0]]);
	}
	reset($boards);
	}

	if($change == 1) {
	if(isset($close)) {
	$displaypage = 0;
	$old_pw = mycrypt(mysslashes($old_pw));
	if(isset($confirm)) {
	if($old_pw != killnl($user_data[2])) header("Location: index.php?page=profile&method=preferences");
	elseif($id == $user_id) {
	$user_file = myfile("members/$id.txt"); $user_file_size = sizeof($user_file);
	$user_file[4] = "7\n";
	myfwrite("members/$id.txt",$user_file,"w");
	}
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=profile&amp;method=preferences\">User Preferences</a>\tAccount Closed");
	echo get_message('Account_Closed','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>').'<br />'.sprintf($txt['Links']['User_Control_Panel'],"<a href=\"index.php?page=user_cp\">",'</a>'));
	}
	else {
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=profile&amp;method=preferences\">User Preferences</a>\tClose Account");
?>

<form action="index.php?page=profile&amp;method=preferences" method="post"><input name="change" type="hidden" value="1" /><input name="close" type="hidden" value="1" /><input name="confirm" type="hidden" value="1" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Close Account</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><strong><br />Closing your account will permanently revoke your posting privileges.<br /><br />Are you <ins>sure</ins> you want to close your account? Once you close it, it cannot be recovered under <ins>any</ins> circumstances.<br /><br />To confirm, enter your password below</strong><br /><br /><input class="textbox" maxlength="16" name="old_pw" size="20" type="password" /><br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><span class="normal"><input class="button" type="submit" value="Close Account" /></span></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}
	else {
	isset($_POST['new_display1']) ? $n_display1 = 1 : $n_display1 = 0;
	isset($_POST['new_display2']) ? $n_display2 = 1 : $n_display2 = 0;
	isset($_POST['new_display3']) ? $n_display3 = 1 : $n_display3 = 0;
	isset($_POST['new_display4']) ? $n_display4 = 1 : $n_display4 = 0;

	$n_pw1 = mysslashes($n_pw1);
	$n_pw2 = mysslashes($n_pw2);
	$old_pw = mycrypt(mysslashes($old_pw));
	$n_quote = mysslashes($n_quote);
	$n_signature = mysslashes($n_signature);
	if($n_publicemail == "") $error = "An e-mail address is required.";
	elseif(!verify_email_address($n_publicemail)) $error = "You must enter a valid e-mail address.";
	elseif($n_pw1 != "" && $n_pw1 != $n_pw2) $error = "The new password and password confirmation do not match.";
	elseif($n_pw1 != "" && strlen($n_pw1) < 6) $error = "Your password must be at least 6 characters.";
	elseif($n_pw1 != "" && $old_pw != killnl($user_data[2])) $error = "Your old password is incorrect.";
	elseif(strlen($new_signature) > 351) $error = "Your signature is too many characters.";
	elseif(strlen($new_quote) > 701) $error = "Your quote is too many characters.";
	elseif(stristr($n_signature, 'bitch')) $error = "Your signature contains a banned word. Banned word: 'bitch'.";
	elseif(stristr($n_signature, 'cock')) $error = "Your signature contains a banned word. Banned word: 'cock'.";
	elseif(stristr($n_signature, 'dildo')) $error = "Your signature contains a banned word. Banned word: 'dildo'.";
	elseif(stristr($n_signature, 'fag')) $error = "Your signature contains a banned word. Banned word: 'fag'.";
	elseif(stristr($n_signature, 'fuck')) $error = "Your signature contains a banned word. Banned word: 'fuck'.";
	elseif(stristr($n_signature, 'gay')) $error = "Your signature contains a banned word. Banned word: 'gay'.";
	elseif(stristr($n_signature, 'goatse')) $error = "Your signature contains a banned word. Banned word: 'goatse'.";
	elseif(stristr($n_signature, 'nigga')) $error = "Your signature contains a banned word. Banned word: 'nigga'.";
	elseif(stristr($n_signature, 'nigger')) $error = "Your signature contains a banned word. Banned word: 'nigger'.";
	elseif(stristr($n_signature, 'penis')) $error = "Your signature contains a banned word. Banned word: 'penis'.";
	elseif(stristr($n_signature, 'shit')) $error = "Your signature contains a banned word. Banned word: 'shit'.";
	elseif(stristr($n_signature, 'slut')) $error = "Your signature contains a banned word. Banned word: 'slut'.";
	elseif(stristr($n_quote, 'tubgirl')) $error = "Your quote contains a banned word. Banned word: 'tubgirl'.";
	elseif(stristr($n_quote, 'bitch')) $error = "Your quote contains a banned word. Banned word: 'bitch'.";
	elseif(stristr($n_quote, 'cock')) $error = "Your quote contains a banned word. Banned word: 'cock'.";
	elseif(stristr($n_quote, 'dildo')) $error = "Your quote contains a banned word. Banned word: 'dildo'.";
	elseif(stristr($n_quote, 'fag')) $error = "Your quote contains a banned word. Banned word: 'fag'.";
	elseif(stristr($n_quote, 'fuck')) $error = "Your quote contains a banned word. Banned word: 'fuck'.";
	elseif(stristr($n_quote, 'gay')) $error = "Your quote contains a banned word. Banned word: 'gay'.";
	elseif(stristr($n_quote, 'goatse')) $error = "Your quote contains a banned word. Banned word: 'goatse'.";
	elseif(stristr($n_quote, 'nigga')) $error = "Your quote contains a banned word. Banned word: 'nigga'.";
	elseif(stristr($n_quote, 'nigger')) $error = "Your quote contains a banned word. Banned word: 'nigger'.";
	elseif(stristr($n_quote, 'penis')) $error = "Your quote contains a banned word. Banned word: 'penis'.";
	elseif(stristr($n_quote, 'shit')) $error = "Your quote contains a banned word. Banned word: 'shit'.";
	elseif(stristr($n_quote, 'slut')) $error = "Your quote contains a banned word. Banned word: 'slut'.";
	elseif(stristr($n_quote, 'tubgirl')) $error = "Your quote contains a banned word. Banned word: 'tubgirl'.";
	elseif($new_aim != "" && !preg_match("/^[A-Z0-9 ]+$/i",$new_aim)) $error = "Your AIM handle can only contain alphanumeric characters and spaces.";
	elseif($new_icq != "" && !preg_match("/^[0-9]+$/i",$new_icq)) $error = "Your ICQ handle can only contain numeric characters.";
	elseif($new_msn != "" && !verify_email_address($new_msn)) $error = "Your MSN handle contains invalid characters.";
	elseif($new_yahoo != "" && !preg_match("/^[A-Z0-9 _-]+$/i",$new_yahoo)) $error = "Your Yahoo handle can only contain alphanumeric characters, spaces, and underscores.";
	elseif($n_pw1 == "123456") $error = "'123456' is an unacceptable password. Please choose a password that is harder to guess.";
	elseif($n_pw1 == "abcdef") $error = "'abcdef' is an unacceptable password. Please choose a password that is harder to guess.";
	elseif($n_pw1 == "dragon") $error = "'dragon' is an unacceptable password. Please choose a password that is harder to guess.";
	elseif($n_pw1 == "password") $error = "'password' is an unacceptable password. Please choose a password that is harder to guess.";
	elseif($n_pw1 == "pikachu") $error = "'pikachu' is an unacceptable password. Please choose a password that is harder to guess.";
	elseif($n_pw1 == "pokemon") $error = "'pokemon' is an unacceptable password. Please choose a password that is harder to guess.";
	elseif($n_pw1 == "qwerty") $error = "'qwerty' is an unacceptable password. Please choose a password that is harder to guess.";
	elseif($n_pw1 == $profile_data['nick']) $error = "You cannot make your password the same as your user name.";
	else {
	$displaypage = 0;

	if($n_pw1 == '') $n_pw = $profile_data['pw'];
	else $n_pw = mycrypt($n_pw1);

	$user_file = myfile("members/$id.txt"); $user_file_size = sizeof($user_file);
	$user_file[2] = $n_pw."\n";
	$user_file[5] = $n_posts."\n";
	$user_file[6] = $n_regdat."\n";
	$user_file[7] = $n_timezone."\n";
	$user_file[8] = $n_signature."\n";
	$user_file[9] = $n_aim."\n";
	$user_file[10] = $n_display1.','.$n_display2.','.$n_display3.','.$n_display4."\n";
	$user_file[12] = $n_msn."\n";
	$user_file[13] = $n_yahoo."\n";
	$user_file[14] = $n_possiblekarma."\n";
	$user_file[15] = $new_stylesheet."\n";
	$user_file[16] = $n_karma."\n";
	$user_file[17] = $n_quote."\n";
	$user_file[18] = $n_icq."\n";
	$user_file[20] = $n_publicemail."\n";
	myfwrite("members/$id.txt",$user_file,"w");

	if($new_pw1 != "") {
	if($user_data[status] != 1 || $user_id == $id) {
	$session_user_pw = $new_pw; session_register("session_user_pw");
	if($cookie_xbbuser) {
	$cookie_data = "$user_id\t$new_pw";
	}
	}
	}
	$logging = explode(',',$config['record_options']);
	if(in_array(9,$logging)) {
	record("10","%1: User Preferences Updated [IP: %2]");
	}

	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t".$txt['Navigation']['User_Preferences_Updated'][0]);
	echo get_message('User_Preferences_Updated','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>').'<br />'.sprintf($txt['Links']['User_Control_Panel'],"<a href=\"index.php?page=user_cp\">",'</a>').'<br />'.sprintf($txt['Links']['View_Profile'],"<a href=\"index.php?page=profile\">",'</a>'));
	}
	}
	}

	if($displaypage == 1) {
	$l_user_id = $profile_data['id'];
	include("last_activity.php");
	$profile_data['regdate'] ="".makeregdate($profile_data['regdate'])."";
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\tUser Preferences");
?>

<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
cntfield.value = maxlimit - field.value.length;
}
</script>
<form action="index.php?page=profile&amp;method=preferences" method="post" name="profileform"><input name="change" type="hidden" value="1" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4" style="text-align: left"><span class="heading">User Preferences</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4" style="text-align: left"><span class="heading">Please note that the Terms of Service apply to all fields in your profile.</span></td>
	</tr>
	<tr>
		<td class="heading3" colspan="2" style="text-align: left"><span class="heading">Contact Information</span></td>
		<td class="heading3" colspan="2" style="text-align: left"><span class="heading">Change Password/Stylesheet/Time Zone</span></td>
	</tr>
<? if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"4\"><span class=\"heading\">Error: $error</span></td>
	</tr>
"; ?>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>E-mail Address</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="middle"><input class="textbox" maxlength="100" name="new_publicemail" size="30" tabindex="1" type="text" value="<?=$n_publicemail?>" /></td>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>Old Password</strong></span></td>
		<td class="one" style="text-align: left" valign="middle"><input class="textbox" maxlength="16" name="old_pw" size="20" tabindex="6" type="password" /></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>AIM Handle</strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><input class="textbox" maxlength="16" name="new_aim" size="30" tabindex="2" type="text" value="<?=$n_aim?>" /></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>New Password</strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><input class="textbox" maxlength="16" name="new_pw1" size="20" tabindex="7" type="password" /></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>ICQ Handle</strong></span></td>
		<td class="one" style="text-align: left" valign="middle"><input class="textbox" maxlength="16" name="new_icq" size="30" tabindex="3" type="text" value="<?=$n_icq?>" /></td>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>New Password Confirmation</strong></span></td>
		<td class="one" style="text-align: left" valign="middle"><input class="textbox" maxlength="16" name="new_pw2" size="20" tabindex="8" type="password" /></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>MSN Handle</strong></span></td>
		<td class="two" style="text-align: left" valign="middle"><input class="textbox" maxlength="100" name="new_msn" size="30" tabindex="4" type="text" value="<?=$n_msn?>" /></td>
		<td class="two" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Stylesheet</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="middle"><select class="textbox" name="new_stylesheet" tabindex="9"><?
	$dir = "stylesheets/";
	$handle = @opendir($dir);
	while ($file = @readdir ($handle))
	{
	if(eregi("^\.{1,2}$",$file))
	{
	continue;
	}

	if(!is_dir($dir.$file))

	{
	if($user_data['stylesheet'] == $dir.$file) echo '<option selected="selected" value="'.$dir.$file.'">'.substr($file, 0, -4).'</option>';
	elseif(substr($file, 0, 5) == "board" or substr($file, 0, 3) == "cat") echo "";
	else echo '<option value="'.$dir.$file.'">'.substr($file, 0, -4).'</option>';
	}
	}
	@closedir($handle);
?>
</select> <span class="normal"><a href="javascript:void(0)" onclick="window.open('stylesheets/previewer/previewer.php','Stylesheet Previewer','height=350, width=550,scrollbars=no')">Previewer</a></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>Yahoo! Handle</strong></span></td>
		<td class="one" style="text-align: left" valign="middle"><input class="textbox" maxlength="20" name="new_yahoo" size="30" tabindex="5" type="text" value="<?=$n_yahoo?>" /></td>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>Time Zone</strong></span></td>
		<td class="one" style="text-align: left" valign="middle"><select class="textbox" name="new_timezone" tabindex="10"><?
	for($i = 0; $i < sizeof($txt['Time_Zone']); $i++) {
	if($user_data['timezone'] == $txt['Time_Zone'][$i][1]) $x = " selected";
	else $x = "";
	echo "<option value=\"".$txt['Time_Zone'][$i][1]."\"$x>".$txt['Time_Zone'][$i][0]."</option>";
	}
?>
		</select></td>
	</tr>
	<tr>
		<td class="heading3" colspan="4" style="text-align: left"><span class="heading">Options</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>Display Signatures</strong> <acronym title="Signatures will be displayed underneath posts.">(?)</acronym></span></td>
		<td class="two" style="text-align: left" valign="middle"><input<? if($n_display2 == 1) echo " checked=\"checked\"" ?> name="new_display2" tabindex="11" type="checkbox" value="1" /></td>
		<td class="two" style="text-align: left" valign="middle"><span class="normal"><strong>Display Smilies</strong> <acronym title="Graphical smilies will be displayed on posts.">(?)</acronym></span></td>
		<td class="two" style="text-align: left" valign="middle"><input<? if($n_display4 == 1) echo " checked=\"checked\"" ?> name="new_display4" tabindex="12" type="checkbox" value="1" /></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>Display E-mail Address</strong> <acronym title="Your e-mail address will be shown on your profile page.">(?)</acronym></span></td>
		<td class="one" style="text-align: left" valign="top"><input<? if($n_display1 == 1) echo " checked=\"checked\"" ?> name="new_display1" tabindex="13" type="checkbox" value="1" /></td>
		<td class="one" style="text-align: left" valign="middle"><span class="normal"><strong>Display Titles</strong> <acronym title="Titles will be displayed after user names on posts.">(?)</acronym></span></td>
		<td class="one" colspan="3" style="text-align: left" valign="middle"><input<? if($n_display3 == 1) echo " checked=\"checked\"" ?> name="new_display3" tabindex="14" type="checkbox" value="1" /></td>
	</tr>
	<tr>
		<td class="heading3" colspan="4" style="text-align: left"><span class="heading">Miscellaneous</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong>Signature</strong> <acronym title="This will be added to the bottom of each of your posts.">(?)</acronym><br /><acronym title="&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;">Basic HTML</acronym> <strong>Enabled<br />5 Lines/350 Chars</strong> Max<br /><input class="textbox" maxlength="3" name="remLen1" readonly="readonly" size="4" style="text-align: center" type="text" value="350" /> characters left</span></td>
		<td class="one" rowspan="3" style="text-align: left" valign="top"><textarea class="textbox" cols="35" rows="6" name="new_signature" onKeyDown="textCounter(document.profileform.new_signature,document.profileform.remLen1,350)" onKeyUp="textCounter(document.profileform.new_signature,document.profileform.remLen1,350)" tabindex="15"><?=brnl($n_signature)?></textarea></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong>Quote</strong> <acronym title="Put any information here that you want displayed on your profile page.">(?)</acronym><br /><acronym title="&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;">Basic HTML</acronym> <strong>Enabled<br />10 Lines/700 Chars</strong> Max<br /><input class="textbox" maxlength="3" name="Infor1" readonly="readonly" size="4" style="text-align: center" type="text" value="700" /> characters left</span></td>
		<td class="one" rowspan="3" style="text-align: left" valign="top"><textarea class="textbox" cols="35" name="new_quote" rows="6" tabindex="16" onKeyDown="textCounter(document.profileform.new_quote,document.profileform.Infor1,700)" onKeyUp="textCounter(document.profileform.new_quote,document.profileform.Infor1,700)"><?=brnl($n_quote)?></textarea></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><span class="normal"><input class="button" tabindex="17" type="submit" value="Update User Preferences" /><? if($user_data['status'] != "7") { ?> <input class="button" name="close" tabindex="18" type="submit" value="Close Account" /><? } ?></span></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}
	}
	}
?>