<?
	/*
	Silentum Boards v1.4.3
	mod_topic.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	if($user_logged_in != 1 || $user_data['status'] != 1 && $user_data['status'] != 2) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Restricted'][0]);
	echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Topic'],"<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	elseif(!$topic_file = myfile("boards/$board.$thread.txt")) die('There was an error while loading the topic data.');
	else {

	$topic_data = myexplode($topic_file[0]);
	if($config['enable_censor'] == 1) $topic_data[1] = censor($topic_data[1]);
	$save = "";

	switch($method) {
	case "delete":;
	if($delete != "yes") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tDelete Topic");
?>

<form action="index.php?page=topic&amp;method=delete&amp;board=<?=$board?>&amp;thread=<?=$thread?>" method="post"><input name="delete" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Delete Topic</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><input name="deleted" type="hidden" value="<?=htmlspecialchars(get_post($board,$thread,$post_id))?>" /><input name="deletedtopic" type="hidden" value="<?=htmlspecialchars($topic_data[1])?>" /><span class="normal"><br /><strong>After deleting this topic...</strong><br /><br /><select class="textbox" name="karmayes"><option value="1">Subtract 1 Karma</option><option value="0">Do Not Subtract 1 Karma</option></select><br /><br /><strong>and...</strong><br /><br /><select class="textbox" name="suspendyes"><option value="0">Do Not Suspend User</option><option value="1">Suspend User</option></select><br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Delete Topic" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {

	if($topic_data[7] != '') {
	unlink("boards/polls/$topic_data[7].1.txt");
	unlink("boards/polls/$topic_data[7].2.txt");
	}

	$topic_size = sizeof($topic_file)-1;
	unlink("boards/$board.$thread.txt");
	$topics = myfile("boards/$board.topics.txt");
	for($i = 0; $i < sizeof($topics); $i++) {
	if($thread == killnl($topics[$i])) {
	$topics[$i] = ""; break;
	}
	}

	myfwrite("boards/$board.topics.txt",$topics,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("5","%1: Topic Deleted ($board,$thread) [IP: %2]");
	}
	if($suspendyes == "1") {
	suspend_user($topic_data[2]);
	}
	if($karmayes == "1" && $suspendyes != "1") {
	decrease_karma($topic_data[2]);
	$karmaloss = "1";
	}
	$deleted = killnl($deleted);
	$timesent = date("Y-F-d / h:i:sa");
	$moderations = myfile("members/".$topic_data[2].".moderations.txt");
	$new_id = sizeof($moderations)+1;
	$towrite = "$new_id\t$timesent\t$board\t$deletedtopic\t$karmaloss\t$deleted\t\n";
	myfwrite("members/".$topic_data[2].".moderations.txt",$towrite,"a");
	$reference = trim(mutate($reference)); $notebox = nlbr(trim(mutate($notebox)));
	$new_id2 = myfile("members/$topic_data[2].notebox.txt"); $new_id2 = myexplode($new_id2[sizeof($new_id2)-1]); $new_id2 = $new_id2[0]+1;
	$reference = "You have received a moderation";
	$notebox = "One of your topics has been deleted for violating the Terms of Service. Please review your <a href=\"index.php?page=moderations\">moderations</a> and re-read the Terms of Service if necessary.";
	$towrite2 = "$new_id2\t$reference\t$notebox\t$note_box_id\t$timesent\t1\t1\t1\t\r\n";
	myfwrite("members/$topic_data[2].notebox.txt",$towrite2,"a");
	decrease_topic_amount($board); decrease_post_amount($board,$topic_size);
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t".$txt['Navigation']['Topic_Deleted'][0]);
	echo get_message('Topic_Deleted','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	break;

	case "unmark":
	$mark_file = myfile("boards/$board.marked.txt");
	$new = "";
	if((ord($mark_file) > 0) && (sizeof($mark_file) != 0)) {
	foreach($mark_file as $mark) {
	if($thread != killnl($mark)) $new[] = $mark;
	}
	myfwrite("boards/$board.marked.txt",$new,"w");
	}
	header("Location: index.php?method=board&board=$board");
	break;

	case "mark":
	$mark_file = myfile("boards/$board.marked.txt");
	$found = 0;
	if((ord($mark_file) > 0) && (sizeof($mark_file) !=0)) {
	foreach($mark_file as $mark) {
	if($thread == killnl($mark)) $found = 1; }}
	if($found == 0) {
	myfwrite("boards/$board.marked.txt","\r\n$thread","a");
	}
	header("Location: index.php?method=board&board=$board");
	break;

	case "lock":
	if($lock != "yes") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tLock Topic");
?>

<form action="index.php?page=topic&amp;method=lock&amp;board=<?=$board?>&amp;thread=<?=$thread?>" method="post"><input name="lock" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Lock Topic</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><br />Do you really want to lock <a href="index.php?method=topic&amp;board=<?=$board?>&amp;thread=<?=$thread?>"><?=$topic_data[1]?></a>? Once it is locked, no one can post any more replies.<br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Lock Topic" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {
	$topic_data[0] = "2"; $topic_file[0] = myimplode($topic_data);
	myfwrite("boards/$board.$thread.txt",$topic_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("5","%1: Topic Locked ($board,$thread) [IP: %2]");
	}
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\t".$txt['Navigation']['Topic_Is_Locked'][0]);
	echo get_message('Topic_Is_Locked','<br /><br />'.sprintf($txt['Links']['Topic'],"<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">",'</a>').'<br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	break;

	case "unlock":
	if($unlock != "yes") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tUnlock Topic");
?>

<form action="index.php?page=topic&amp;method=unlock&amp;board=<?=$board?>&amp;thread=<?=$thread?>" method="post"><input name="unlock" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Unlock Topic</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><br />Do you really want to unlock <a href="index.php?method=topic&amp;board=<?=$board?>&amp;thread=<?=$thread?>"><?=$topic_data[1]?></a>? Once it is unlocked, new replies may be added.<br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Unlock Topic" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {
	$topic_data[0] = "1"; $topic_file[0] = myimplode($topic_data);
	myfwrite("boards/$board.$thread.txt",$topic_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("5","%1: Topic Unlocked ($board,$thread) [IP: %2]");
	}
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\t".$txt['Navigation']['Topic_Unlocked'][0]);
	echo get_message('Topic_Unlocked','<br /><br />'.sprintf($txt['Links']['Topic'],"<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">",'</a>').'<br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	break;

	case "move":
	$displaypage = "yes";
	if($move == "yes") {
	if(!myfile_exists("boards/$target_board.topics.txt")) $error = 'You cannot access this board.<br />';
	else {
	$new_id = myfile("boards/$target_board.id.topics.txt"); $new_id = $new_id[0]+1;
	$oldboard = myfile("boards/$board.topics.txt");
	$contribution_number = sizeof($topic_file)-1;

	for($i = 0; $i < sizeof($oldboard); $i++) {
	if(killnl($oldboard[$i]) == $thread) {
	$oldboard[$i] = "";	$save = 1;	break;
	}
	}
	if($save == 1) myfwrite("boards/$board.topics.txt",$oldboard,"w");
	else echo "An error has occurred.";

	myfwrite("boards/$target_board.topics.txt","$new_id\r\n","a");
	rename("boards/$board.$thread.txt","boards/$target_board.$new_id.txt");
	myfwrite("boards/$target_board.id.topics.txt",$new_id,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("5","%1: Topic Moved ($board,$thread) To ($target_board,$new_id) [IP: %2]");
	}
	decrease_post_amount($board,$contribution_number);
	decrease_topic_amount($board);
	increase_topic_amount($target_board);
	increase_post_amountx($target_board,$contribution_number);
	$displaypage = "no";
	echo navigation($txt['Navigation']['Topic_Moved'][0]);
	echo get_message('Topic_Moved','<br /><br />'.sprintf($txt['Links']['Moved_Topic'],"<a href=\"index.php?method=topic&amp;board=$target_board&amp;thread=$new_id\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	}

	if($displaypage == "yes") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tMove Topic");
?>

<form action="index.php?page=topic" method="post"><input name="board" type="hidden" value="<?=$board?>" /><input name="move" type="hidden" value="yes" /><input name="thread" type="hidden" value="<?=$thread?>" /><input name="method" type="hidden" value="move" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Move Topic</span></td>
	</tr>
<?
	if($error != "") echo "	<tr>
		<td class=\"one\"><span class=\"normal\">$error</span></td>
	</tr>
";
?>
	<tr>
		<td class="one" style="text-align: center"><br /><span class="normal">Where do you want to move <a href="index.php?method=topic&amp;board=<?=$board?>&amp;thread=<?=$thread?>"><?=$topic_data[1]?></a> to?</span><br /><br /><select class="textbox" name="target_board" size="1"><?
	$boards = myfile("objects/boards.txt"); $boards_number = sizeof($boards);
	$category = myfile("objects/categories.txt"); $category_number = sizeof($category);
	for($j = 0; $j < $category_number; $j++) {
	$act_category = myexplode($category[$j]);
	echo "";
	for($i = 0; $i < $boards_number; $i++) {
	$act_board = myexplode($boards[$i]);
	if($act_board[5] == $act_category[0] && $act_board[0] != $board) {
	echo "<option value=\"$act_board[0]\">$act_board[1]</option>";
	}
	}
	echo "";
	}
?>
</select><br /><br /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Move Topic" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	break;
	}
	}
?>