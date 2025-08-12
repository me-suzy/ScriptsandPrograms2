<?
	/*
	Silentum Boards v1.4.3
	user_control_panel.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("permission.php");
	require_once("settings.php");
	require_once("text.php");

	if($user_logged_in != 1) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\tAccess Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	$notes = 0;
	$user_notes = myfile("members/$user_id.notebox.txt");
	$user_notes_number = sizeof($user_notes);
	for($i = 0; $i < $user_notes_number; $i++) {
	$current_note = myexplode($user_notes[$i]);
	if($current_note[7] == 1) $notes++;
	}
	if($notes == 0) $notes = "<span class=\"normal\">You have ".$notes." new notes.</span>";
	elseif($notes == 1) $notes = "<span class=\"normal\">You have ".$notes." new note.</span>";
	else $notes = "<span class=\"normal\">You have ".$notes." new notes.</span>";

	include("board_top.php");

	echo navigation("User Control Panel");
	$moderations = myfile("members/".$user_data[1].".moderations.txt");
	if(sizeof($moderations) == 0 || sizeof($moderations) >= 2) $moderationplural = "moderations"; else $moderationplural = "moderation";
	$number_of_users = myfile("objects/id_users.txt");
	$totalusers = $number_of_users[0];
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
		<td class="heading1" colspan="4" style="text-align: left"><span class="heading">User Control Panel - <a href="index.php?page=profile"><?=$user_data['nick']?></a> <? if($user_data['id'] != "1") echo "".morph_status($user_data['status'],$user_data['karma']).""; else echo $config['status_host']; ?></span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2" style="text-align: left; width: 50%"><span class="heading">User Statistics</span></td>
		<td class="heading2" colspan="2" style="text-align: left; width: 50%"><span class="heading">Board Statistics</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Karma</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="middle"><span class="normal"><?=$user_data['karma']?> (<?=$user_data['possiblekarma']?> Possible)</span></td>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Total Board Posts</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="middle"><span class="normal"><?=$posts?></span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Total Posts</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="middle"><span class="normal"><?=$user_data['posts']?></span></td>
		<td class="two" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Total Board Topics</strong></span></td>
		<td class="two" style="text-align: left; width: 30%" valign="middle"><span class="normal"><?=$topics?></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Registration Email Address</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="middle"><span class="normal"><?=$user_data['email']?></span></td>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Total Users</strong></span></td>
		<td class="one" style="text-align: left; width: 30%" valign="middle"><span class="normal"><?=$totalusers?></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">User Options</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 50%"><span class="normal"><strong><a href="index.php?page=profile&amp;method=preferences">User Preferences</a></strong> - Change your signature, stylesheet, password, and other information.</span></td>
		<td class="one" style="text-align: left; width: 50%"><span class="normal"><strong><a href="index.php?page=logout">Logout</a></strong> - Logout from your current user name.</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left"><span class="normal"><strong><a href="index.php?page=moderations">Moderations</a></strong> - You have <? if(myfile_exists("members/".$user_data[1].".moderations.txt")) echo "".sizeof($moderations)." ".$moderationplural.""; else echo "0 moderations";?>.</span></td>
		<td class="two" style="text-align: left"><span class="normal"><strong><a href="index.php?page=note_box">Note Box</a></strong> - <?=sprintf($notes,'</a>')?></span></td>
	</tr>
</table>
</object><br /><?
	if($user_data['status'] == "1" || $user_data['status'] == "2") { $suspendedusersindex = "<a href=\"index.php?page=suspended_users\">Suspended Users</a>";

	if($user_data['status'] == 2 || $user_data['status'] == 1) $modqueue = "<a href=\"index.php?page=queue\">Queue</a>";

	$queue = myfile("objects/queue.txt");
	$suspend = myfile("objects/suspended_users.txt");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Moderator Options</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 50%"><span class="normal"><strong><?=$modqueue?> - <?=sizeof($queue)?></strong> - View a list of the reported messages.</span></td>
		<td class="one" style="width: 50%"><span class="normal"><strong><?=$suspendedusersindex?> - <?=sizeof($suspend)?></strong> - View a list of the suspended users.</span></td>
	</tr>
</table>
</object><br /><? if($user_data['status'] == "2" && $user_data['status'] != "1") echo "
"; ?><? } ?><? if($user_data['status'] == "1") { ?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Administrator Options</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 50%" valign="top"><span class="normal"><strong><a href="administrator_announcement.php">Announcement</a></strong> - Edit the announcement displayed on the Board Index.</span></td>
		<td class="one" style="text-align: left; width: 50%" valign="top"><span class="normal"><strong><a href="administrator_boards_categories.php?method=boardview">Boards</a></strong> - Add, edit, or delete boards.</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="top"><span class="normal"><strong><a href="administrator_boards_categories.php?method=viewcategory">Categories</a></strong> - Add, edit, or delete categories.</span></td>
		<td class="two" style="text-align: left" valign="top"><span class="normal"><strong><a href="administrator_censored_words.php">Censored Words</a></strong> - Add, edit, or delete censored words.</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align:left" valign="top"><span class="normal"><strong><a href="administrator_directory.php">Directory</a></strong> - Displays information about each individual user on the boards.</span></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong><a href="administrator_suspend.php">IP Suspensions</a></strong> - Add or delete IP suspensions.</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left" valign="top"><span class="normal"><strong><a href="administrator_purge_topics.php">Purge Topics</a></strong> - Delete old topics from the boards.</span></td>
		<td class="two" style="text-align: left" valign="top"><span class="normal"><strong><a href="administrator_smilies_post_icons.php">Smilies &amp; Post Icons</a></strong> - Add, edit, or delete smilies and post icons.</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong><a href="administrator_statuses.php">Statuses</a></strong> - Add, edit, or delete statuses.</span></td>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong><a href="administrator_titles.php">Titles</a></strong> - Add, edit, or delete titles.</span></td>
	</tr>
</table>
</object><br />
<?
	if($user_data['id'] == "1") {
?>
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">Host Options</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 50%" valign="top"><span class="normal"><strong><a href="administrator_actions.php?increase=1&amp;password=H72kVmal091jGu43">Distribute 1 Karma Manually</a></strong> - Recommended if you don't have access to cron jobs.</span></td>
		<td class="one" style="text-align: left; width: 50%" valign="top"><span class="normal"><strong><a href="administrator_settings.php">Settings</a></strong> - Edit the board settings.</span></td>	
	</tr>
<? } ?>
</table>
</object><br />
<? } ?>
<?
	include("board_bottom.php");
?>