<?
	/*
	Silentum Boards v1.4.3
	mod_queue.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");

	if($user_logged_in != 1 || $user_data['status'] != 1 && $user_data['status'] != 2) {
	record("2","%1: Control Panel Access Attempt [IP: %2]");
	header("Location: index.php");
	exit;
	}
	else {
	$save = "";
	if(!$method || $method == "") $method = "overview";

	if($method == "overview") {
	$queue = myfile("objects/queue.txt");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("10","%1: Queue Viewed [IP: %2]");
	}
	$message = myfile("boards/$act_queue[2].$act_queue[1].txt");
	$message_data = myexplode($message[0]);
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\tQueue");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="3"><span class="heading">Queue</span></td>
	</tr>
<?
	if(sizeof($queue) == 0) echo "	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center;\"><span class=\"normal\"><br /><strong>There are no reported messages.<br /><br />Actioned messages are automatically deleted from the Queue.</strong><br /><br /></span></td>
	</tr>
";
	for($i = 0; $i < sizeof($queue); $i++) {
	$act_queue = myexplode($queue[$i]);
	if(myfile_exists("members/".$act_queue[4].".moderations.txt")) {
	$usermoderations = myfile("members/".$act_queue[4].".moderations.txt");
	}
	else $user_moderations = "0";
	$moderation_data = get_user_data($act_queue[4]);
?>
	<tr>
		<td class="heading2" colspan="3" style="text-align: left"><span class="heading">Queue ID <?=$act_queue[0]?></span></td>
	</tr>
	<tr>
		<td class="<? if($moderation_data[4] == "6" || $moderation_data[4] == "4") echo "error"; else echo "heading3"?>" style="text-align: left; width: 10%"><span class="normal">Posted By</span></td>
		<td class="<? if($moderation_data[4] == "6" || $moderation_data[4] == "4") echo "error"; else echo "heading3"?>" style="text-align: left; width: 10%"><span class="normal"><? if($moderation_data[4] != "1" && $moderation_data[4] != "2" && $moderation_data[4] != "3" && $moderation_data[4] != "4" && $moderation_data[4] != "6" && $moderation_data[4] != "7") echo "Guest (".get_post_ip($act_queue[2],$act_queue[1],$act_queue[3]).")"; else echo "<a href=\"index.php?page=profile&amp;id=".$act_queue[4]."\">".get_user_name($act_queue[4])."</a>" ?><? if($user_data['status'] == "1" && $moderation_data[4] != "4" && $moderation_data[4] == "6" && $moderation_data[1] != "1" && $moderation_data[0] != "") echo " - <a href=\"administrator_actions.php?id=".$moderation_data[1]."&amp;ban=yes\">Ban</a>"; ?><? if($user_data['status'] == "1" && $moderation_data[4] == "4" && $moderation_data[0] != "") echo " <strong>(Banned)</strong>"; ?></span></td>
		<td class="two" style="text-align: left; width: 80%"><span class="normal"><strong>Actions</strong></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="normal">Reason</span></td>
		<td class="heading3" style="text-align: left; width: 20%"><span class="normal"><strong><?=$act_queue[6]?><? if($act_queue[6] == "Other") echo " - ".$act_queue[7]?></strong></span></td>
		<td class="two" style="text-align: left"><span class="normal"><a href="index.php?page=queue&amp;method=delete&amp;id=<?=$act_queue[0]?>">Delete Queue ID <?=$act_queue[0]?> From Queue</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: left"><span class="normal">User's Moderations</span></td>
		<td class="heading3" style="text-align: left; width: 20%"><span class="normal"><strong><? if($act_queue[4] == "Guest") echo "0"; else echo sizeof($usermoderations)?></strong></span></td>
		<td class="two" style="text-align: left"><span class="normal"><a href="index.php?page=mod_post&amp;method=delete&amp;board=<?=$act_queue[2]?>&amp;thread=<?=$act_queue[1]?>&amp;post_id=<?=$act_queue[3]?>&amp;queue_id=<?=$act_queue[0]?>">Delete Post</a></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Reported By</span></td>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><a href="index.php?page=profile&amp;id=<?=$act_queue[5]?>"><?=get_user_name($act_queue[5])?></a></span></td>
		<td class="two" style="text-align: left"><span class="normal"><a href="index.php?page=mod_post&amp;board=<?=$act_queue[2]?>&amp;thread=<?=$act_queue[1]?>&amp;post_id=<?=$act_queue[3]?>&amp;queue_id=<?=$act_queue[0]?>">Edit Post</a> (Does <ins>not</ins> count as a moderation)</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal">Board</span></td>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><a href="index.php?method=board&amp;board=<?=$act_queue[2]?>"><?=get_board_name($act_queue[2])?></a></span></td>
		<td class="two" style="text-align: left"><span class="normal"><? if($user_data[id] == "1") echo "<a href=\"index.php?page=mod_post&amp;method=remove&amp;board=$act_queue[2]&amp;thread=$act_queue[1]&amp;post_id=$act_queue[3]\">Remove Post</a> (Does <ins>not</ins> count as a moderation)" ?></span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 15%"><span class="normal">Topic</span></td>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><a href="index.php?method=topic&amp;board=<?=$act_queue[2]?>&amp;thread=<?=$act_queue[1]?>#post<?=str_pad($act_queue[3],4,"0",str_pad_left)?>">Go to topic</a></span></td>
		<td class="two" style="text-align: left" valign="top"><span class="normal"><? if($user_data['status'] == "1") echo "<a href=\"administrator_suspend.php?method=new&amp;board=$act_queue[2]&amp;thread=$act_queue[1]&amp;post_id=$act_queue[3]\">IP Suspend User</a>"; ?></span></td>
	</tr>
	<tr>
		<td class="two" colspan="3" style="text-align: left; width: <?=$twidth?>"><span class="normal"><strong>Post</strong> - <?=get_post($act_queue[2],$act_queue[1],$act_queue[3])?></span></td>
	</tr>
<?
	}
	}

	if($method == "delete") {
	$queue = myfile("objects/queue.txt");
	for($i = 0; $i < sizeof($queue); $i++) {
	$act_queue = myexplode($queue[$i]);
	if($id == $act_queue[0]) {
	$save = 1; $queue[$i] = ""; break;
	}
	}

	if($save == 1) {
	myfwrite("objects/queue.txt",$queue,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("8","%1: Queue Item Deleted [IP: %2]");
	}
	header("Location: index.php?page=queue");
	exit;
	}
	else echo "An error has occurred.";
	}

	echo "</table>
</object><br />
";
	include("board_bottom.php");

	}
?>