<?
	/*
	Silentum Boards v1.4.3
	board.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	switch($method) {

	default:
	$posts = 0; $topics = 0;
	$category = myfile("objects/categories.txt"); $category_size = sizeof($category);
	$boards = myfile("objects/boards.txt"); $boards_number = sizeof($boards);
?>

<object>
<table cellspacing="0" style="margin-left: auto; margin-right: auto; width: <?=$twidth?>">
	<tr>
		<td style="text-align: left"><h1>Board Index</h1></td>
	</tr>
</table>
</object><?
	if($config['announcement_position'] == 1) { $news_file = myfile("objects/announcement.txt"); $news_config = myexplode($news_file[0]);

	if((time() < $news_config[1] || $news_config[1] == -1) && $news_file[0] != "") {
	echo "
<object>
<table class=\"table\" cellspacing=\"$cellspacing\" style=\"width: $twidth\">
	<tr>
		<td class=\"heading1\"><span class=\"heading\">Announcement</span></td>
	</tr>";
	if($news_config[0] == 1) echo "
	<tr>
		<td class=\"one\" valign=\"top\"><span class=\"normal\">".trim($news_file[1])."</span></td>
	</tr>
</table>
</object><br />";
	}
	}
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" style="text-align: left; width: 45%"><span class="heading">Board</span></td>
		<td class="heading1" style="text-align: center; width: 5%"><span class="heading">Topics</span></td>
		<td class="heading1" style="text-align: center; width: 5%"><span class="heading">Posts</span></td>
		<td class="heading1" style="text-align: right; width: 45%"><span class="heading">Last Post</span></td>
	</tr>
<?
	if($boards_number == 0) echo "
	<tr>
		<td class=\"one\" colspan=\"4\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no boards.</strong><br /><br /></span></td>
	</tr>
"; 
	for($k = 0; $k < $category_size; $k++) {
	$current_category = myexplode($category[$k]); 
	$x = FALSE;
	while($act_value = each($boards)) {
	$current_board = myexplode($act_value[1]);
	if($current_board[5] == $current_category[0]) {
	if($config['show_private_boards'] == 1) $right = 1;
	else {
	$act_board_rights = explode(',',$current_board[10]);
	$right = 0;
	if($user_logged_in != 1) {
	if($act_board_rights[4] == 1) $right = 1;
	}
	else {
	if(check_right($current_board[0],0) == 1 || $user_data['status'] == "1" || $user_data['status'] == "2") $right = 1;
	}
	}

	if($right == 1) {
	if($x == FALSE) {
	if($config['enable_categories'] == 1) echo "	<tr>
		<td class=\"heading2\" colspan=\"4\"><span class=\"heading\">".trim($current_category[1])."</span></td>
	</tr>
";
	$x = TRUE;
	}
	$posts += $current_board[4]; $topics += $current_board[3];
	$rows_per_color = 1;
	switch($ctr++) {
	case 0:
	$bgcolor = "one";
	break;
	case ($rows_per_color):
	$bgcolor = "two";
	break;
	case ($rows_per_color * 2):
	$bgcolor = "one";
	$ctr = 1;
	break;
	}
	$act_board_rights = explode(',',$current_board[10]);
?>
	<tr>
		<td class="<?=$bgcolor?>"><span class="normal"><strong><a href="index.php?method=board&amp;board=<?=$current_board[0]?>" title="<? if($act_board_rights[1] == 1 || $act_board_rights[2] == 1 || $act_board_rights[3] == 1) echo "Users may post."; else echo "Users may not post.";?> <? if($act_board_rights[5] == 1 || $act_board_rights[6] == 1 || $act_board_rights[7] == 1) echo "Guests may post."; else echo "Guests may not post.";?>"><?=$current_board[1]?></a></strong><br /><?=$current_board[2]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><?=$current_board[3]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><?=$current_board[4]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: right" valign="middle"><span class="normal"><? if($current_board[3] == "0") echo "<strong>There are no topics.</strong>";?><? if($current_board[3] != "0") echo make_last_post($current_board[0],$current_board[9],$current_board[8]); ?></span></td>
	</tr>
<?
	}
	unset($boards[$act_value[0]]);
	}
	}
	reset($boards);
	}
	echo "</table>
</object><br />
";

	if($config['announcement_position'] == 2) { $news_file = myfile("objects/announcement.txt"); $news_config = myexplode($news_file[0]);

	if((time() < $news_config[1] || $news_config[1] == -1) && $news_file[0] != "") {
	echo "<object>
<table class=\"table\" cellspacing=\"$cellspacing\" style=\"width: $twidth\">
	<tr>
		<td class=\"heading1\"><span class=\"heading\">Announcement</span></td>
	</tr>";
	if($news_config[0] == 1) echo "
	<tr>
		<td class=\"one\" valign=\"top\"><span class=\"normal\">$news_file[1]</span></td>
	</tr>
</table>
</object><br />";
	}
	}
	break;

	case "board":
	$board_data = get_board_data($board);
	$right = 0;
	if(!myfile_exists("boards/$board.topics.txt")) {
	echo navigation($txt['Navigation']['Board_Does_Not_Exist'][0]);
	echo get_message('Board_Does_Not_Exist','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_logged_in != 1) {
	if($board_data['rights'][4] == 1) $right = 1;
	else {
	echo navigation($txt['Navigation']['Not_Logged_In'][0]);
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	}
	else {
	if(check_right($board,0) != 1 && $user_data['status'] != 2) {
	echo navigation($txt['Navigation']['Restricted_Board'][0]);
	echo get_message('Restricted_Board','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	else $right = 1;
	}

	if($right == 1) {

	$topics_file = myfile("boards/$board.topics.txt"); $topics_file_size = sizeof($topics_file);
	$topics_file = array_reverse($topics_file);
	$mark_file = myfile("boards/$board.marked.txt");
	if((ord($mark_file) > 0) && (sizeof($mark_file) != 0))
	foreach($mark_file as $mark) {
	$new = "";
	if($topics_file_size > 0) {
	for($i = 0; $i < $topics_file_size; $i++) {
	$x = killnl($topics_file[$i]);
	if($x==killnl($mark)) {
	if($new == "") $new[] = $x; else array_unshift($new,$x);
	}
	else {
	$new[] = $x; 
	}
	}
	$topics_file = $new;
	}
	}
	$sides_number = ceil($topics_file_size / $config['topics_per_page']); if(!$z) $z = 1; $j = $z * $config['topics_per_page']; $x = $j - $config['topics_per_page']; if($j > $topics_file_size) $j = $topics_file_size;

	if($sides_number == 1 || $sides_number == 0) $show_pages = "";
	else {
	for($i = 0; $i < $sides_number;$i++) {
	$i2 = $i + 1;
	if($i2 == $z) $pages[$i] = $i2;
	else $pages[$i] = "<a href=\"index.php?method=board&amp;board=$board&amp;z=$i2\">$i2</a>";
	}
	$show_pages = sprintf($txt['Pages'],implode(" ",$pages));
	}

	echo navigation("$board_data[name] $show_pages");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="two"<? if($sides_number < "2") echo " colspan=\"2\""; ?> style="text-align: left; width: 50%" valign="middle"><span class="normal"><strong><a href="index.php">Board Index</a><? if($board_data['rights'][3] == 1 && $user_logged_in == "1" || $user_data['status'] == "2" || $user_data['status'] == "1" || $board_data['rights'][7] == 1) { ?> - <a href="index.php?page=post_poll&amp;board=<?=$board?>">Post Poll</a><? } ?><? if($board_data['rights'][1] == 1 && $user_logged_in == "1" || $user_data['status'] == "2" || $user_data['status'] == "1" || $board_data['rights'][5] == 1) { ?> - <a href="index.php?page=post_topic&amp;board=<?=$board?>">Post Topic</a><? } ?></strong></span></td>
<? if($sides_number >= "2") { ?>
		<td class="two" style="text-align: right; width: 50%" valign="bottom"><span class="normal"><strong><?=$show_pages?></strong></span></td>
<? } ?>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" style="text-align: center; width: 2%"></td>
		<td class="heading1" style="text-align: left; width: 40%"><span class="heading">Topic</span></td>
		<td class="heading1" style="text-align: center; width: 18%"><span class="heading">Creator</span></td>
		<td class="heading1" style="text-align: center; width: 5%"><span class="heading">Posts</span></td>
		<td class="heading1" style="text-align: center; width: 5%"><span class="heading">Views</span></td>
		<td class="heading1" style="text-align: right; width: 30%"><span class="heading">Last Post</span></td>
	</tr>
<?
	if($topics_file_size != 0) {
	for($y = $x; $y < $j ; $y++) {
	$act_topic['id'] = killnl($topics_file[$y]);
	$act_topic_file = myfile("boards/$board.$act_topic[id].txt"); $act_topic_posts = sizeof($act_topic_file);
	$act_topic_data = myexplode($act_topic_file[0]);
	$act_topic_lpost = myexplode($act_topic_file[$act_topic_posts-1]);
	if($act_topic_data[0] == "open") $act_topic_data[0] = "1";
	elseif($act_topic_data[0] == "closed") $act_topic_data[0] = "2";

	if($act_topic_data[6] == "") $act_topic_data[6] = 0;

	$sidesnumber = ceil(($act_topic_posts-1) / $config['posts_per_page']);
	$pagedisplay = "";
	if($sidesnumber > 1) {
	for($ss = 0; $ss < $sidesnumber; $ss++) {
	$ss2 = $ss + 1;	$pagedisplay .= " <a href=\"index.php?method=topic&amp;board=$board&amp;thread=$act_topic[id]&amp;z=$ss2\">$ss2</a>";
	}
	$pagedisplay = sprintf($txt['Pages'],$pagedisplay);
	}

	if($config['enable_censor'] == 1) $act_topic_data[1] = censor($act_topic_data[1]);
	if($act_topic_data[7] != '') $poll_text = '<img alt="Poll" class="bottom" src="images/poll.png" title="Poll" /> ';
	else $poll_text = "";
	$found = 0;
	if($user_data['status'] == 1 || $user_data['status'] == 2) {
	if((ord($mark_file) > 0) && (sizeof($mark_file) != 0))
	foreach($mark_file as $mark) if($act_topic['id'] == killnl($mark)) $found=1;
	if($found) $markmessage = "<a href=\"index.php?page=topic&amp;method=unmark&amp;board=$board&amp;thread=$act_topic[id]\">Unmark</a> - <img alt=\"Important\" class=\"bottom\" src=\"images/important.png\" title=\"Important\" /> "; else $markmessage = "<a href=\"index.php?page=topic&amp;method=mark&amp;board=$board&amp;thread=$act_topic[id]\">Mark</a> - ";
	}
	if($user_data['status'] != 1 && $user_data['status'] != 2) {
	$found = 0;
	if((ord($mark_file) > 0) && (sizeof($mark_file) != 0))
	foreach($mark_file as $mark) if($act_topic['id'] == killnl($mark)) $found=1;
	if($found) $markmessage = "<img alt=\"Important\" class=\"bottom\" src=\"images/important.png\" title=\"Important\" /> "; else $markmessage = "";
	}
	$rows_per_color = 1;
	switch($ctr++) {
	case 0:
	$bgcolor = "one";
	break;
	case ($rows_per_color):
	$bgcolor = "two";
	break;
	case ($rows_per_color * 2):
	$bgcolor = "one";
	$ctr = 1;
	break;
	}
?>
	<tr>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><? if($act_topic_data[0] != "2") echo "<img alt=\"Post Icon\" class=\"icon\" src=\"".get_post_icon_address($act_topic_data[3])."\" title=\"Post Icon\" />"; else echo "<img alt=\"Locked Topic\" class=\"lock\" src=\"images/lock.png\" title=\"Locked Topic\" />"; ?></td>
		<td class="<?=$bgcolor?>" style="text-align: left" valign="middle"><span class="normal"><?=$markmessage?><?=$prefix?><?=$poll_text?><a class="topiclist" href="index.php?method=topic&amp;board=<?=$board?>&amp;thread=<?=$act_topic["id"]?>" title="<?=$act_topic_data[1]?>"><? if(strlen($act_topic_data[1]) > 40) $act_topic_data[1] = substr($act_topic_data[1],0,40)."..."; ?><?=$act_topic_data[1]?></a> <?=$pagedisplay?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><? if($act_topic_data[2] != "0".get_user_name($act_topic_data[2])."") echo "<a href=\"index.php?page=profile&amp;id=".$act_topic_data[2]."\">"; ?><?=get_user_name($act_topic_data[2])?><? if($act_topic_data[2] != "0".get_user_name($act_topic_data[2])."") echo "</a>"; ?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><?=$act_topic_posts-1?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><?=$act_topic_data[6]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: right" valign="middle"><span class="normal"><?=sprintf(makedate($act_topic_lpost[2]))?></span></td>
	</tr>
<?
	}
	}
	else echo "
	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no topics.</strong><br /><br /></span></td>
	</tr>";
	echo "
</table>
</object><br />";
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="two"<? if($sides_number < "2") echo " colspan=\"2\""; ?> style="text-align: left; width: 50%" valign="bottom"><span class="normal"><strong><a href="index.php">Board Index</a><? if($board_data['rights'][3] == 1 && $user_logged_in == "1" || $user_data['status'] == "2" ||  $user_data['status'] == "1" || $board_data['rights'][7] == 1) { ?> - <a href="index.php?page=post_poll&amp;board=<?=$board?>">Post Poll</a><? } ?><? if($board_data['rights'][1] == 1 && $user_logged_in == "1" || $user_data['status'] == "2" ||  $user_data['status'] == "1" || $board_data['rights'][5] == 1) { ?> - <a href="index.php?page=post_topic&amp;board=<?=$board?>">Post Topic</a><? } ?></strong></span></td>
<? if($sides_number >= "2") { ?>
		<td class="two" style="text-align: right; width: 50%" valign="bottom"><span class="normal"><strong><?=$show_pages?></strong></span></td>
<? } ?>
	</tr>
</table>
</object><br />
<?
	}
	break;

	case "topic":
	$board_data = get_board_data($board);

	$right = 0;
	if(!myfile_exists("boards/$board.$thread.txt")) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Topic_Does_Not_Exist'][0]);
	echo get_message('Topic_Does_Not_Exist','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_logged_in != 1) {
	if($board_data['rights'][4] == 1) $right = 1;
	else {
	echo navigation($txt['Navigation']['Not_Logged_In'][0]);
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	}
	else {
	if(check_right($board,0) != 1 && $user_data['status'] != 2) {
	echo navigation($txt['Navigation']['Restricted_Board'][0]);
	echo get_message('Restricted_Board','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	}
	else $right = 1;
	}

	if($right == 1) {
	$topic = myfile("boards/$board.$thread.txt");
	$topic_data = myexplode($topic[0]);

	if($config['enable_censor'] == 1) $topic_data[1] = censor($topic_data[1]);

	if($user_logged_in == 1) {
	if($user_data[status] == 2 || $user_data[status] == 1) {
	if($topic_data[0] == '1' || $topic_data[0] == 'open') $lock_unlock = " - <strong><a href=\"index.php?page=topic&amp;method=lock&amp;board=$board&amp;thread=$thread&amp;post_id=1\">Lock</a></strong>"; else $lock_unlock = " - <strong><a href=\"index.php?page=topic&amp;method=unlock&amp;board=$board&amp;thread=$thread&amp;post_id=1\">Unlock</a></strong>";
	$toolbar = "<span class=\"normal\"><strong><a href=\"index.php?page=topic&amp;method=delete&amp;board=$board&amp;thread=$thread&amp;post_id=1\">Delete</a></strong> $lock_unlock - <strong><a href=\"index.php?page=topic&amp;method=move&amp;board=$board&amp;thread=$thread&amp;post_id=1\">Move</a></strong></span>";
	}
	else $toolbar = " ";
	}
	else $toolbar = " ";

	$real_size = sizeof($topic)-1; $sides_number = ceil($real_size / $config['posts_per_page'] ); if($z == "last") $z = $sides_number;
	if(!isset($z)) $z = 1; $j = $z * $config['posts_per_page']; $x = $j - $config['posts_per_page']; if($j > $real_size) $j = $real_size;

	if($sides_number == 1) $show_pages = "";
	else {
	for($i = 0; $i < $sides_number; $i++) {
	$i2 = $i + 1;
	if($i2 == $z) $pages[$i] = $i2;
	else $pages[$i] = "<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread&amp;z=$i2\">$i2</a>";
	}
	$show_pages = sprintf($txt['Pages'],implode(" ",$pages));
	}

	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$topic_data[1]." ".$show_pages);

	if($topic_data[7] != '') {
	if(myfile_exists("boards/polls/$topic_data[7].1.txt")) {
	$poll_file = myfile("boards/polls/$topic_data[7].1.txt");
	$poll_data = myexplode($poll_file[0]);
	$poll_voters = myfile("boards/polls/$topic_data[7].2.txt"); $poll_voters = explode(',',$poll_voters[0]);

	$temp_var = "session_poll_$topic_data[7]";
	$temp_var2 = "cookie_poll_$topic_data[7]";
	$voted = 1;

	if($poll_data[0] > 2) {
	$button = "<span class=\"normal\"><strong>This poll has been locked. No more votes may be added.</strong></span>";
	}
	elseif(isset($$temp_var) || isset($$temp_var2) || ($user_logged_in == 1 && in_array($user_id,$poll_voters))) {
	$button = "<span class=\"normal\"><strong>You have already voted.</strong></span>";
	$voted = 0;
	}
	elseif($user_logged_in == 1 || $poll_data[0] == 1) {
	$button = "<input class=\"button\" type=\"submit\" value=\"Vote\" />";
	}
	else $button = "<span class=\"normal\"><strong>To vote, you must be logged in. <a href=\"index.php?page=register\">Register</a> or <a href=\"index.php?page=login\">login</a>.</strong></span>";

	if($user_data['status'] == 1 || ($user_id == $poll_data[1] && $user_logged_in == 1)) {
	$button .= "";
	}
?>

<form action="index.php?page=poll_vote&amp;board=<?=$board?>&amp;thread=<?=$thread?>&amp;poll_id=<?=$topic_data[7]?>" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="3"><span class="heading">Poll</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="3"><span class="heading"><? if($config['enable_censor'] == "1") echo censor($poll_data[3]); else echo $poll_data[3]?></span></td>
	</tr>
	<tr>
		<td class="heading3" colspan="3"><span class="heading"><?=$poll_data[4]?> Total <? if($poll_data[4] == "0" || $poll_data[4] >= 2) echo "Votes"; else echo "Vote"; ?></span></td>
	</tr>
<?
	for($i = 1; $i < sizeof($poll_file); $i++) {
	$rows_per_color = 1;
	switch($ctr++) {
	case 0:
	$bgcolor = "one";
	break;
	case ($rows_per_color):
	$bgcolor = "two";
	break;
	case ($rows_per_color * 2):
	$bgcolor = "one";
	$ctr = 1;
	break;
	}
	$act_poll = myexplode($poll_file[$i]);
	if($act_poll[2] == 0) $votes = '0';
	else $votes = round(($act_poll[2]/$poll_data[4])*100,1);
	if($act_poll[2] == "0") $plural = "Votes";
	if($act_poll[2] == "1") $plural = "Vote";
	if($act_poll[2] >= "2") $plural = "Votes";
	if($i == 1) $selected = " checked=\"checked\"";
	else $selected = "";
	if($act_poll[2] < "1") $poll = "";
	if($act_poll[2] >= "1") $poll = "<img alt=\"".round($votes)."%\" height=\"10\" src=\"images/blue.png\" title=\"".round($votes)."%\" width=\"".round($votes)."\" />";
	echo "	<tr>
		<td class=\"$bgcolor\"><span class=\"normal\">";
	if(($user_logged_in == 1 || $poll_data[0] == 1) && $poll_data[0] < 3 && $voted == 1) echo "<input$selected name=\"vote_id\" type=\"radio\" value=\"$act_poll[0]\" /> ";
?>
<?if($config['enable_censor'] == "1") echo censor($act_poll[1]); else echo $act_poll[1];?></span></td>
		<td class="<?=$bgcolor?>" style="width: 65%"><span class="normal">(Votes: <?=$act_poll[2]?>) <?=$poll?> <?=$votes?>%</span></td>
	</tr>
<?
	}
	echo "	<tr>
		<td class=\"heading3\" colspan=\"3\">$button</td>
	</tr>
</table>
</object>
</form>";
	}
	}
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="two"<? if($user_data['status'] != "2" || $user_data['status'] != "1") echo " colspan=\"2\""; ?> style="text-align: left; width: 50%" valign="bottom"><span class="normal"><strong><a href="index.php">Board Index</a> - <a href="index.php?method=board&amp;board=<?=$board?>">Topic Index</a><? if($board_data['rights'][2] == 1 && $user_logged_in == "1" || $user_data['status'] == "2" ||  $user_data['status'] == "1" || $board_data['rights'][6] == 1) { ?> - <a href="index.php?page=post_reply&amp;board=<?=$board?>&amp;thread=<?=$thread?>">Post Reply</a><? } ?></strong></span></td>
<? if($user_data['status'] == "2" || $user_data['status'] == "1") { ?>
		<td class="two" style="text-align: right; width: 50%" valign="middle"><span class="normal"><?=$toolbar?></span></td>
<? } ?>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"<? if($sides_number < "2") echo " colspan=\"2\""; ?> style="text-align: left; width: 50%"><span class="heading"><?=$topic_data[1]?></span></td>
<? if($sides_number >= "2") { ?>
		<td class="heading1" style="text-align: right; width: 50%"><span class="heading"><?=$show_pages?></span></td>
<? } ?>
	</tr>
<?
	for($i = $x + 1; $i < $j + 1; $i++) {
	$current_contribution = myexplode($topic[$i]);

	if(strncmp($current_contribution[1],'0',1) == 0) {
	$answer_creator['nick'] = substr($current_contribution[1],1,strlen($current_contribution[1]));
	$answer_creator['title_name'] = "";
	$answer_creator['status'] = "Guest";

	$posts_text = "";
	$id = "";
	$signature = "";
	}
	elseif(!$answer_creator = get_user_data($current_contribution[1])) {
	$answer_creator['nick'] = "Deleted";
	$answer_creator['title_name'] = "";
	$answer_creator['status'] = "Deleted";

	$posts_text = "";
	$id = "";
	$signature = "";
	}
	else {
	if($answer_creator['title'] != "" && $user_data['showtitles'] == 1) {
	$title_data = get_title_data($answer_creator['title']);
	$answer_creator['title_name'] = $title_data['name'];

	$answer_creator['nick'] = "<a href=\"index.php?page=profile&amp;id=$answer_creator[id]\">$answer_creator[nick]</a> ($answer_creator[title_name])";

	}
	else {
	$answer_creator['title_name'] = "";

	$answer_creator['nick'] = "<a href=\"index.php?page=profile&amp;id=$answer_creator[id]\">$answer_creator[nick]</a>";
	}

	if(($current_contribution[5] == 1 || $current_contribution[5] == "yes") && $answer_creator['signature'] != "") {
	if($config['enable_censor'] == 1) $temp_sig = censor($answer_creator['signature']);
	else $temp_sig = $answer_creator['signature'];
	$signature = "<br />---<br />$temp_sig";
	}
	else $signature = "";

	}

	if($current_contribution[7] == 1 && $user_data['showsmilies'] == 1 || $current_contribution[7] == "yes" && $user_data['showsmilies'] == 1 || $user_logged_in == 0) $current_contribution[3] = make_smilies($current_contribution[3]);
	if(($current_contribution[8] == 1 || $current_contribution[8] == "yes") && $board_data['basic_html'] == 1) $current_contribution[3] = basic_html($current_contribution[3]);
	if($config['enable_censor'] == 1) $current_contribution[3] = censor($current_contribution[3]);
?>
	<tr>
		<td class="two" style="text-align: left; width: 50%" valign="middle"><img align="left" alt="Post Icon" class="icon" src="<? if($current_contribution[6] == "" || get_post_icon_address($current_contribution[6]) == "") echo "images/post_icons/icon_1.png"; else echo get_post_icon_address($current_contribution[6]); ?>" title="Post Icon" /><span class="normal">&nbsp;By <strong><?=$answer_creator['nick']?></strong> on <?=makedate($current_contribution[2])?></span></td>
		<td class="two" style="text-align: right; width: 50%" valign="middle"><span class="normal"><strong><a id="post<?=str_pad($i,4,"0",str_pad_left)?>"></a><a href="#post<?=str_pad($i,4,"0",str_pad_left)?>">Post #<?=str_pad($i,4,"0",str_pad_left)?></a></strong><? if($board_data['rights'][2] == 1 && $user_logged_in == "1" || $user_data['status'] == "2" ||  $user_data['status'] == "1" || $board_data['rights'][6] == 1) { ?> - <a href="index.php?page=post_reply&amp;board=<?=$board?>&amp;thread=<?=$thread?>&amp;quote=<?=$current_contribution[0]?>">Quote</a><? } ?><? if($user_logged_in == 1 && $user_data['karma'] >= 1 && $user_data['status'] != "4" && $user_data['status'] != "6" || $user_data['status'] == 1 || $user_data['status'] == 2) { ?> - <a href="index.php?page=report_post&amp;board=<?=$board?>&amp;thread=<?=$thread?>&amp;post=<?=$i?>">Report</a><? } ?></span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left" valign="top"><span class="normal"><?=$current_contribution[3]?><? if($user_data['displayoptions'][2] == 1 || $user_logged_in != 1) { ?> <?=basic_html_profile($signature)?><? } ?><br /><br /></span></td>
	</tr>
<?
	if($sides_number < "2") $colspan = " colspan=\"2\"";
	}
	echo "	<tr>
		<td class=\"heading1\"$colspan style=\"text-align: left; width: 50%\"><span class=\"heading\">$topic_data[1]</span></td>"; if($sides_number >= "2") { ?>

		<td class="heading1" style="text-align: right; width: 50%"><span class="heading"><?=$show_pages?></span></td>
<? } ?>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="two"<? if($user_data['status'] != "2" || $user_data['status'] != "1") echo " colspan=\"2\""; ?> style="text-align: left; width: 50%" valign="bottom"><span class="normal"><strong><a href="index.php">Board Index</a> - <a href="index.php?method=board&amp;board=<?=$board?>">Topic Index</a><? if($board_data['rights'][2] == 1 && $user_logged_in == "1" || $user_data['status'] == "2" ||  $user_data['status'] == "1" || $board_data['rights'][6] == 1) { ?> - <a href="index.php?page=post_reply&amp;board=<?=$board?>&amp;thread=<?=$thread?>">Post Reply</a><? } ?></strong></span></td>
<? if($user_data['status'] == "2" || $user_data['status'] == "1") { ?>
		<td class="two" style="text-align: right; width: 50%" valign="middle"><span class="normal"><?=$toolbar?></span></td>
<? } ?>
	</tr>
</table>
</object><br />
<?
	}
	break;
	}
?>