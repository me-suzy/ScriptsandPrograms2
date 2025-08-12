<?
	/*
	Silentum Boards v1.4.3
	administrator_purge_topics.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");
	administrator();

	if($user_logged_in != 1 || $user_data['status'] != 1) {
	$logging = explode(',',$config['record_options']);
	if(in_array(2,$logging)) {
	record("2","%1: Control Panel Access Attempt [IP: %2]");
	}
	header("Location: index.php");
	exit;
	}
	else {
	switch($method) {
	case "kill":
	$new_space_counter = 0; $killed_topics_counter = 0; $killed_posts_counter = 0;
	if(!myfile_exists("boards/$target_board.topics.txt") && $target_board != "all") header("Location: administrator_purge_topics.php");
	elseif($target_board == "all") {
	$boards = myfile("objects/boards.txt");
	for($a = 0; $a < sizeof($boards); $a++) {
	$act_board = myexplode($boards[$a]);
	$act_board_topics = myfile("boards/$act_board[0].topics.txt");
	for($b = 0; $b < sizeof($act_board_topics); $b++) {
	$act_board_topics_file = killnl($act_board_topics[$b]);
	$act_topic = myfile("boards/$act_board[0].$act_board_topics_file.txt");
	$act_topic_lpost = myexplode($act_topic[sizeof($act_topic) - 1]);
	if(round(((time() - mktime(substr($act_topic_lpost[2],8,2),substr($act_topic_lpost[2],10,2),0,substr($act_topic_lpost[2],4,2),substr($act_topic_lpost[2],6,2),substr($act_topic_lpost[2],0,4))) / 60 / 60 / 24)) > $topic_age) {
	$act_topic_file_info = stat("boards/$act_board[0].$act_board_topics_file.txt"); $new_space_counter = $new_space_counter + $act_topic_file_info[7];
	$killed_topics_counter++; $killed_posts_counter = $killed_posts_counter + sizeof($act_topic) - 1;
	decrease_topic_amount($act_board[0]); decrease_post_amount($act_board[0],sizeof($act_topic)); unlink("boards/$act_board[0].$act_board_topics_file.txt");
	$act_board_topics[$b] = "";
	}
	}
	myfwrite("boards/$act_board[0].topics.txt",$act_board_topics,"w");
	}
	}
	else {
	$target_board_topics = myfile("boards/$target_board.topics.txt");
	for($b = 0; $b < sizeof($target_board_topics); $b++) {
	$target_board_topics_file = killnl($target_board_topics[$b]);
	$act_topic = myfile("boards/$target_board.$target_board_topics_file.txt");
	$act_topic_lpost = myexplode($act_topic[sizeof($act_topic) - 1]);
	if(round(((time() - mktime(substr($act_topic_lpost[2],8,2),substr($act_topic_lpost[2],10,2),0,substr($act_topic_lpost[2],4,2),substr($act_topic_lpost[2],6,2),substr($act_topic_lpost[2],0,4))) / 60 / 60 / 24)) > $topic_age) {
	$act_topic_file_info = stat("boards/$target_board.$target_board_topics_file.txt"); $new_space_counter = $new_space_counter + $act_topic_file_info[7];
	$killed_topics_counter++; $killed_posts_counter = $killed_posts_counter + sizeof($act_topic) - 1;
	decrease_topic_amount($target_board); decrease_post_amount($target_board,(sizeof($act_topic) - 1)); unlink("boards/$target_board.$target_board_topics_file.txt");
	$target_board_topics[$b] = "";
	}
	}
	myfwrite("boards/$target_board.topics.txt",$target_board_topics,"w");
	}

	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Topics Purged From Board $target_board [IP:%2]");
	}

	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_purge_topics.php\">Purge Topics</a>\t".$txt['Navigation']['Topics_Purged'][0]);
	echo get_message('Topics_Purged',"<br />[$killed_posts_counter posts, $killed_topics_counter topics]",round($new_space_counter / 1024,2));
	break;

	default:
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tPurge Topics");
?>

<form action="administrator_purge_topics.php" method="post"><input name="method" type="hidden" value="kill" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Purge Topics</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><br /><select class="textbox" name="topic_age"><option value="1">Older than 1 day</option><option value="2">Older than 2 days</option><option value="3">Older than 3 days</option><option value="4">Older than 4 days</option><option value="7">Older than 1 week</option><option value="14">Older than 2 weeks</option><option value="21">Older than 3 weeks</option><option value="30">Older than 1 month</option><option value="60">Older than 2 months</option><option value="90">Older than 3 months</option><option value="120">Older than 4 months</option><option value="150">Older than 5 months</option><option value="180">Older than 6 months</option><option selected="selected" value="365">Older than 1 year</option><option value="730">Older than 2 years</option><option value="1095">Older than 3 years</option></select> <select class="textbox" size="1" name="target_board"><option value="all">Purge From All Boards</option><?
	$boards = myfile("objects/boards.txt"); $category = myfile("objects/categories.txt");
	for($j = 0; $j < sizeof($category); $j++) {
	$purge_category = myexplode($category[$j]);
	echo "";
	for($i = 0; $i < sizeof($boards); $i++) {
	$purge_board = myexplode($boards[$i]);
	if($purge_board[5] == $purge_category[0]) {
	echo "<option value=\"$purge_board[0]\">$purge_board[1]</option>";
	}
	}
	}
?>
</select><br /><br /><img alt="Warning" class="icon" src="images/important.png" title="Warning" /> <img alt="Warning" class="icon" src="images/important.png" title="Warning" /> <img alt="Warning" class="icon" src="images/important.png" title="Warning" /> <strong>This action is irreversible and all posts and topics will be permanently removed.</strong> <img alt="Warning" class="icon" src="images/important.png" title="Warning" /> <img alt="Warning" class="icon" src="images/important.png" title="Warning" /> <img alt="Warning" class="icon" src="images/important.png" title="Warning" /><br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Purge Topics (No Confirmation)" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;
	}
	}

	include("board_bottom.php");
?>