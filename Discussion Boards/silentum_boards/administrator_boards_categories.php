<?
	/*
	Silentum Boards v1.4.3
	administrator_boards_categories.php copyright 2005 "HyperSilence"
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

	case "boardview":
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tBoards");
	$boards = myfile("objects/boards.txt"); $boards_number = sizeof($boards); $categories = myfile("objects/categories.txt");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4"><span class="heading">Boards</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading"><a href="administrator_boards_categories.php?method=newboard">Add Board</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 35%"><span class="heading">Board Name/Description</span></td>
		<td class="heading3" style="text-align: center; width: 35%"><span class="heading">Category</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Move</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Options</span></td>
	</tr>
<?
	if($boards_number == 0) echo "	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no boards.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < $boards_number; $i++) {
	$act_board = myexplode($boards[$i]);
	if($boards_number != 1) {
	if($i == 0) $moving = "<a href=\"administrator_boards_categories.php?method=moveboarddown&amp;id=$act_board[0]\">&or;</a>";
	elseif($i == $boards_number - 1) $moving = "<a href=\"administrator_boards_categories.php?method=moveboardup&amp;id=$act_board[0]\">&and;</a>";
	else $moving = "<a href=\"administrator_boards_categories.php?method=moveboarddown&amp;id=$act_board[0]\">&or;</a>";
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
		<td class="<?=$bgcolor?>" style="text-align: left" valign="middle"><span class="normal"><strong><?=$act_board[1]?></strong></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><?=get_category_name($act_board[5],$categories)?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><?=$moving?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="middle"><span class="normal"><a href="administrator_boards_categories.php?administrator_boardscats_id=<?=$act_board[0]?>&amp;method=change">Edit</a></span></td>
	</tr>
	<tr>
		<td class="<?=$bgcolor?>" colspan="4" style="text-align: left"><span class="normal"><?=$act_board[2]?></span></td>
	</tr>
<?
	}
	}
	echo "</table>
</object><br />
";
	break;

	case "newboard":
	if($create != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\tAdd Board");
?>

<form action="administrator_boards_categories.php?method=newboard&amp;create=yes" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Boards</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Board</span></td>
	</tr>
	<tr>
		<td class="heading3" colspan="2" style="text-align: left"><span class="heading">General Information</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Board Name</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="title" type="text" /></td>
	</tr>
	<tr>
		<td class="two" style="width: 20%"><span class="normal"><strong>Description</strong></span></td>
		<td class="two" style="width: 80%"><input class="textbox" maxlength="255" name="description" size="75" type="text" /></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Category</strong></span></td>
		<td class="one" style="width: 80%"><select class="textbox" name="category" size="1"><option selected="selected" value="-1">Choose a category</option><?
	$categories = myfile("objects/categories.txt");
	for($j = 0; $j < sizeof($categories); $j++) {
	$act_category = myexplode($categories[$j]);
	echo "<option value=\"$act_category[0]\">$act_category[1]</option>";
	}
?>
</select></td>
	</tr>
	<tr>
		<td class="heading3" colspan="2" style="text-align: left"><span class="heading">General Rights</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input checked="checked" name="basic_html" type="checkbox" value="1" /> Enable <acronym title="&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;">Basic HTML</acronym></span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input checked="checked" name="new_rights[0]" type="checkbox" value="1" /> Users can access the board</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input checked="checked" name="new_rights[1]" type="checkbox" value="1" /> Users can post topics</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input checked="checked" name="new_rights[2]" type="checkbox" value="1" /> Users can post replies</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input checked="checked" name="new_rights[3]" type="checkbox" value="1" /> Users can post polls</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input checked="checked" name="new_rights[4]" type="checkbox" value="1" /> Guests can access the board</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input name="new_rights[5]" type="checkbox" value="1" /> Guests can post topics</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input name="new_rights[6]" type="checkbox" value="1" /> Guests can post replies</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input name="new_rights[7]" type="checkbox" value="1" /> Guests can post polls</span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Board" /></td>
	</tr>
</table>
</object>
</form>
<?
	}

	else {

	$boards_ids = myfile("objects/id_boards.txt");
	$new_id = $boards_ids[0]+1;

	myfwrite("boards/$new_id.id.topics.txt","0","w");
	myfwrite("boards/$new_id.topics.txt","","w");

	$title = trim(mutate($title)); $description = trim(mutate($description));
	$towrite = "$new_id\t$title\t$description\t0\t0\t$category\t\t$basic_html\t\t\t$new_rights[0],$new_rights[1],$new_rights[2],$new_rights[3],$new_rights[4],$new_rights[5],$new_rights[6],$new_rights[7]\n";
	myfwrite("objects/boards.txt",$towrite,"a");

	myfwrite("objects/id_boards.txt",$new_id,"w");

	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Board Added (ID: $new_id) [IP: %2]");
	}

	header("Location: administrator_boards_categories.php?method=boardview");
	exit;
	}
	break;

	case 'new_user_right':
	$displaypage = 1;
	if($change == 'yes') {
	$displaypage = 0;
	$new_user_ids = explode(',',$new_user_ids);
	$rights_file = myfile("boards/$board.rights.txt");
	$new_user_ids = array_unique($new_user_ids);

	while($act_value = each($new_user_ids)) {
	if(!myfile_exists("members/$act_value[1].txt") || $act_value[1] == 0) unset($new_user_ids[$act_value[0]]);
	}
	reset($new_user_ids);

	for($i = 0; $i < sizeof($rights_file); $i++){
	$act_right = myexplode($rights_file[$i]);
	if($act_right[1] == 1) {
	while($act_value = each($new_user_ids)) {
	if($act_value[1] == $act_right[2]) unset($new_user_ids[$act_value[0]]);
	}
	reset($new_user_ids);
	}
	}

	if(sizeof($new_user_ids) != 0) {
	$new_id = myexplode($rights_file[sizeof($rights_file)-1]); $new_id = $new_id[0]+1;
	$towrite = "";
	while($act_value = each($new_user_ids)) {
	$towrite .= "$new_id\t1\t$act_value[1]\t$new_right[0]\t$new_right[1]\t$new_right[2]\t$new_right[3]\t$new_right[4]\t$new_right[5]\t\t\t\t\t\t\r\n";
	$new_id++;
	}
	myfwrite("boards/$board.rights.txt",$towrite,'a');
	}
	header("Location: administrator_boards_categories.php?method=edit_board_rights&board=$board");
	exit;
	}

	if($displaypage == 1) {
	$board_data = get_board_data($board);
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\t<a href=\"administrator_boards_categories.php?administrator_boardscats_id=$board&amp;method=change\">Edit Board</a>\t<a href=\"administrator_boards_categories.php?method=edit_board_rights&amp;board=$board\">Edit Rights</a>\tAdd User Right");
?>

<form action="administrator_boards_categories.php?method=new_user_right&amp;board=<?=$board?>" method="post"><input name="change" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Edit Rights</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add User Right</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>User ID</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="7" name="new_user_ids" size="6" type="text" /></td>
	</tr>
	<tr>
		<td class="one" valign="top"><span class="normal"><strong>Rights</strong></span></td>
		<td class="one"><span class="normal"><input<? if($board_data['rights'][0] == 1) echo " checked=\"checked\""; ?> name="new_right[0]" type="checkbox" value="1" /> Can access the board<br /><input<? if($board_data['rights'][1] == 1) echo " checked=\"checked\""; ?> name="new_right[1]" type="checkbox" value="1" /> Can post topics<br /><input<? if($board_data['rights'][2] == 1) echo " checked=\"checked\""; ?> name="new_right[2]" type="checkbox" value="1" /> Can post replies<br /><input<? if($board_data['rights'][3] == 1) echo " checked=\"checked\""; ?> name="new_right[3]" type="checkbox" value="1" /><input name="new_right[4]" type="hidden" value="0" /><input name="new_right[5]" type="hidden" value="0" /> Can post polls</span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add User Right" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	break;

	case 'delete_right':
	$rights_file = myfile("boards/$board.rights.txt");
	for($i = 0; $i < sizeof($rights_file); $i++) {
	$act_right = myexplode($rights_file[$i]);
	if($act_right[0] == $right_id) {
	switch($act_right[1]) {
	case '1':
	$rights_file[$i] = "";
	myfwrite("boards/$board.rights.txt",$rights_file,'w');
	header("Location: administrator_boards_categories.php?method=edit_board_rights&board=$board");
	exit;
	break;

	case '2':
	$titles_file = myfile('objects/titles.txt');
	for($j = 0; $j < sizeof($titles_file); $j++) {
	$act_title = myexplode($titles_file[$j]);
	if($act_title[0] == $act_right[2]) {
	$act_title_boards = explode(',',$act_title[5]);
	if(in_array($board,$act_title_boards)) { 
	unset($act_title_boards[array_search(board,$act_title_boards)]);
	$act_title[5] = implode(',',$act_title_boards);
	$titles_file[$j] = myimplode($act_title);
	myfwrite('objects/titles.txt',$titles_file,'w');
	}
	break;
	}
	}
	$rights_file[$i] = "";
	myfwrite("boards/$board.rights.txt",$rights_file,'w');
	header("Location: administrator_boards_categories.php?method=edit_board_rights&board=$board");
	exit;
	break;
	}
	break;
	}
	}
	break;

	case 'new_title_right':
	$board_file = myfile('objects/boards.txt'); $titles_file = myfile('objects/titles.txt');
	if(sizeof($titles_file) == 0) {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\t<a href=\"administrator_boards_categories.php?administrator_boardscats_id=$board&method=change\">Edit Board</a>\t<a href=\"administrator_boards_categories.php?method=edit_board_rights&board=$board\">Edit Rights</a>\t".$txt['Navigation']['No_Titles'][0]);
	echo get_message('No_Titles');
	}
	else {
	for($i = 0; $i < sizeof($board_file); $i++) {
	$act_board = myexplode($board_file[$i]);
	if($act_board[0] == $board) {
	$rights_file = myfile("boards/$board.rights.txt");
	$act_board_rights = explode(',',$act_board[10]);

	$title_counter = 0;
	$board_titles = array();
	for($j = 0; $j < sizeof($rights_file); $j++) {
	$act_right = myexplode($rights_file[$j]);
	if($act_right[1] == 2) {
	$title_counter++;
	$board_titles[] = $act_right[2];
	}
	}
	if(sizeof($titles_file) == $title_counter) {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\t<a href=\"administrator_boards_categories.php?administrator_boardscats_id=$board&method=change\">Edit Board</a>\t<a href=\"administrator_boards_categories.php?method=edit_board_rights&board=$board\">Edit Rights</a>\t".$txt['Navigation']['All_Titles_Have_Rights'][0]);
	echo get_message('All_Titles_Have_Rights');
	}
	else {
	$error = "";
	if($add) {
	if(in_array($new_title_id,$board_titles)) $error = $txt['administrator_Boards_Categories']['Error']['Title_Already_Has_Rights'];
	else {
	for($j = 0; $j < sizeof($titles_file); $j++) {
	$act_title = myexplode($titles_file[$j]);
	if($act_title[0] == $new_title_id) {
	if($act_title[5] == '') $act_title[5] = $board;
	else {
	$act_title_boards = explode(',',$act_title[5]);
	if(!in_array($board,$act_title_boards)) { 
	if($act_title[5] == '') $act_title[5] = $board;
	else {
	$act_title_boards[] = $board;
	$act_title[5] = implode(',',$act_title_boards);
	}
	}
	}
	$titles_file[$j] = myimplode($act_title);
	myfwrite('objects/titles.txt',$titles_file,'w');
	$new_id = myexplode($rights_file[sizeof($rights_file)-1]); $new_id = $new_id[0] + 1;
	$towrite = "$new_id\t2\t$new_title_id\t$new_right[0]\t$new_right[1]\t$new_right[2]\t$new_right[3]\t$new_right[4]\t$new_right[5]\t\t\t\t\t\t\r\n";
	myfwrite("boards/$board.rights.txt",$towrite,'a');
	header("Location: administrator_boards_categories.php?method=edit_board_rights&board=$board");
	exit;
	break;
	}
	}
	}
	}
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\t<a href=\"administrator_boards_categories.php?administrator_boardscats_id=$board&amp;method=change\">Edit Board</a>\t<a href=\"administrator_boards_categories.php?method=edit_board_rights&amp;board=$board\">Edit Rights</a>\tAdd Title Right");
?>

<form action="administrator_boards_categories.php?method=new_title_right&amp;board=<?=$board?>" method="post"><input name="add" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Edit Rights</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Title Right</span></td>
	</tr>
<?
	if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"2\" style=\"text-align: left\"><span class=\"heading\">$error</span></td>
	</tr>
"; ?>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Title</strong></span></td>
		<td class="one" style="width: 80%"><select class="textbox" name="new_title_id" size="1"><?
	for($j = 0; $j < sizeof($titles_file); $j++) {
	$act_title = myexplode($titles_file[$j]);;
	if(!in_array($act_title[0],$board_titles)) echo "<option value=\"$act_title[0]\">$act_title[1]</option>";
	}
?>
</select></td>
	</tr>
	<tr>
		<td class="one" valign="top"><span class="normal"><strong>Rights</strong></span></td>
		<td class="one"><span class="normal"><input<? if($act_board_rights[0] == 1) echo " checked=\"checked\""; ?> name="new_right[0]" type="checkbox" value="1" /> Can access the board<br /><input<? if($act_board_rights[1] == 1) echo " checked=\"checked\""; ?> name="new_right[1]" type="checkbox" value="1" /> Can post topics<br /><input<? if($act_board_rights[2] == 1) echo " checked=\"checked\""; ?> name="new_right[2]" type="checkbox" value="1" /> Can post replies<br /><input<? if($act_board_rights[3] == 1) echo " checked=\"checked\""; ?> name="new_right[3]" type="checkbox" value="1" /> Can post polls <input name="new_right[4]" type="hidden" value="0" /><input name="new_right[5]" type="hidden" value="0" /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Title Right" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	break;
	}
	}
	}
	break;

	case 'edit_board_rights':
	$rights = myfile("boards/$board.rights.txt");
	if(!is_array($rights)) $rights = array();
	if(isset($change)) {
	if(isset($new_rights)) {
	ksort($new_rights,SORT_NUMERIC);
	for($i = 0; $i < sizeof($rights); $i++) {
	$act_right = myexplode($rights[$i]);
	if(isset($new_rights[$act_right[0]][0])) {
	$act_right[3] = $new_rights[$act_right[0]][0];
	$act_right[4] = $new_rights[$act_right[0]][1];
	$act_right[5] = $new_rights[$act_right[0]][2];
	$act_right[6] = $new_rights[$act_right[0]][3];
	$act_right[7] = $new_rights[$act_right[0]][4];
	$act_right[8] = $new_rights[$act_right[0]][5];
	$rights[$i] = myimplode($act_right);
	}
	}
	myfwrite("boards/$board.rights.txt",$rights,'w');
	}
	}
	include('board_top.php');
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\t<a href=\"administrator_boards_categories.php?administrator_boardscats_id=$board&amp;method=change\">Edit Board</a>\tEdit Rights");
?>

<form action="administrator_boards_categories.php?method=edit_board_rights&amp;board=<?=$board?>" method="post"><input name="change" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="6"><span class="heading">Edit Rights</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="6"><span class="heading"><a href="administrator_boards_categories.php?method=new_user_right&amp;board=<?=$board?>">Add User Right</a> - <a href="administrator_boards_categories.php?method=new_title_right&amp;board=<?=$board?>">Add Title Right</a></span></td>
	</tr>
	<tr>
		<td class="heading3" colspan="6"><span class="heading">User Rights</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: center"><span class="heading">Title</span></td>
		<td class="two" style="text-align: center"><span class="heading">View Board</span></td>
		<td class="two" style="text-align: center"><span class="heading">Post Topics</span></td>
		<td class="two" style="text-align: center"><span class="heading">Post Replies</span></td>
		<td class="two" style="text-align: center"><span class="heading">Post Polls</span></td>
		<td class="two" style="text-align: center"><span class="heading">Delete</span></td>
	</tr>
<?
	$x = 0;
	while($act_value = each($rights)) {
	$act_right = myexplode($act_value[1]);
	if($act_right[1] == 1) {
	echo "<input name=\"new_rights[$act_right[0]][type]\" type=\"hidden\" value=\"$act_right[1]\" />";
	echo "<input name=\"new_rights[$act_right[0]][target]\" type=\"hidden\" value=\"$act_right[2]\" />";
	if($act_right[3] == 1) $checked[0] = " checked=\"checked\""; else $checked[0] = "";
	if($act_right[4] == 1) $checked[1] = " checked=\"checked\""; else $checked[1] = "";
	if($act_right[5] == 1) $checked[2] = " checked=\"checked\""; else $checked[2] = "";
	if($act_right[6] == 1) $checked[3] = " checked=\"checked\""; else $checked[3] = "";
	if($act_right[7] == 1) $checked[4] = " checked=\"checked\""; else $checked[4] = "";
	if($act_right[8] == 1) $checked[5] = " checked=\"checked\""; else $checked[5] = "";
?>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><?=get_user_name($act_right[2])?></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[0]?> name="new_rights[<?=$act_right[0]?>][0]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[1]?> name="new_rights[<?=$act_right[0]?>][1]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[2]?> name="new_rights[<?=$act_right[0]?>][2]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[3]?> name="new_rights[<?=$act_right[0]?>][3]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><a href="administrator_boards_categories.php?method=delete_right&board=<?=$board?>&right_id=<?=$act_right[0]?>">Delete</a></span></td>
	</tr>
<?
	$x++;
	unset($rights[$act_value[0]]);
	}
	}
	if($x == 0) echo "	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center\"><span class=\"normal\"><strong><br />There are no user rights.<br /><br /></strong></span></td>
	</tr>";
	echo "
	<tr>
		<td class=\"heading2\" colspan=\"6\"><span class=\"heading\">Title Rights</span></td>
	</tr>
	<tr>
		<td class=\"two\" style=\"text-align: center\"><span class=\"heading\">Title</span></td>
		<td class=\"two\" style=\"text-align: center\"><span class=\"heading\">View Board</span></td>
		<td class=\"two\" style=\"text-align: center\"><span class=\"heading\">Post Topics</span></td>
		<td class=\"two\" style=\"text-align: center\"><span class=\"heading\">Post Replies</span></td>
		<td class=\"two\" style=\"text-align: center\"><span class=\"heading\">Post Polls</span></td>
		<td class=\"two\" style=\"text-align: center\"><span class=\"heading\">Delete</span></td>
	</tr>";
	$x = 0;
	reset($rights); $titles_file = myfile('objects/titles.txt');
	while($act_value = each($rights)) {
	$act_right = myexplode($act_value[1]); $title_name = "";
	if($act_right[1] == 2) {
	while($act_value2 = each($titles_file)) {
	$act_title = myexplode($act_value2[1]);
	if($act_title[0] == $act_right[2]) {
	$title_name = $act_title[1];
	unset($titles_file[$act_value2[0]]);
	break;
	}
	}
	reset($titles_file);
	echo "<input name=\"new_rights[$act_right[0]][type]\" type=\"hidden\" value=\"$act_right[1]\" />";
	echo "<input name=\"new_rights[$act_right[0]][target]\" type=\"hidden\" value=\"$act_right[2]\" />";
	if($act_right[3] == 1) $checked[0] = " checked=\"checked\""; else $checked[0] = "";
	if($act_right[4] == 1) $checked[1] = " checked=\"checked\""; else $checked[1] = "";
	if($act_right[5] == 1) $checked[2] = " checked=\"checked\""; else $checked[2] = "";
	if($act_right[6] == 1) $checked[3] = " checked=\"checked\""; else $checked[3] = "";
	if($act_right[7] == 1) $checked[4] = " checked=\"checked\""; else $checked[4] = "";
	if($act_right[8] == 1) $checked[5] = " checked=\"checked\""; else $checked[5] = "";
?>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><?=$title_name?></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[0]?> name="new_rights[<?=$act_right[0]?>][0]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[1]?> name="new_rights[<?=$act_right[0]?>][1]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[2]?> name="new_rights[<?=$act_right[0]?>][2]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><input<?=$checked[3]?> name="new_rights[<?=$act_right[0]?>][3]" type="checkbox" value="1" /></span></td>
		<td class="one" style="text-align: center"><span class="normal"><a href="administrator_boards_categories.php?method=delete_right&amp;board=<?=$board?>&right_id=<?=$act_right[0]?>">Delete</a></span></td>
	</tr>
<?
	$x++;
	}
	}
	if($x == 0) echo "
	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center\"><span class=\"normal\"><strong><br />There are no title rights.<br /><br /></strong></span></td>
	</tr>";
	echo "
</table>
</object><br />
<object>
<table cellspacing=\"$cellspacing\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: $twidth\">
	<tr>
		<td><input class=\"button\" type=\"submit\" value=\"Edit Rights\" /></td>
	</tr>
</table>
</object>
</form>";
	break;

	case "change":
	$boards = myfile('objects/boards.txt'); $boards_size = sizeof($boards);
	if($change != "yes") {
	for($i = 0; $i < $boards_size; $i++) {
	$act_board = myexplode($boards[$i]);
	if($act_board[0] == $administrator_boardscats_id) {
	$act_board_rights = explode(',',$act_board[10]);
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\tEdit Board");
?>

<form action="administrator_boards_categories.php?method=change&amp;change=yes&amp;administrator_boardscats_id=<?=$act_board[0]?>" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Boards</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Edit Board</span></td>
	</tr>
	<tr>
		<td class="heading3" colspan="2" style="text-align: left"><span class="heading">General Information</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Board Name</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="title" type="text" value="<?=$act_board[1]?>" /></td>
	</tr>
	<tr>
		<td class="two" style="width: 20%"><span class="normal"><strong>Description</strong></span></td>
		<td class="two" style="width: 80%"><input class="textbox" maxlength="255" name="description" size="75" type="text" value="<?=$act_board[2]?>" /></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Category</strong></span></td>
		<td class="one" style="width: 80%"><select class="textbox" name="category" size="1"><option value="-1" <? if($act_board[5] == "-1") echo "selected"; ?>>No category (board will not be shown)</option><?
	$categories = myfile("objects/categories.txt");
	for($j = 0; $j < sizeof($categories); $j++) {
	$act_category = myexplode($categories[$j]);
	if($act_board[5] == $act_category[0]) {
	echo "<option value=\"$act_category[0]\" selected>$act_category[1]</option>";
	}
	else echo "<option value=\"$act_category[0]\">$act_category[1]</option>";
	}
?>
</select></td>
	</tr>
	<tr>
		<td class="heading3" colspan="2" style="text-align: left"><span class="heading">General Rights</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading"><a href="administrator_boards_categories.php?method=edit_board_rights&amp;board=<?=$act_board[0]?>">Edit User/Title Rights</a></span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board[7] == 1) echo " checked=\"checked\""; ?> name="basic_html" type="checkbox" value="1" /> Enable <acronym title="&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;">Basic HTML</acronym></span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[0] == 1) echo " checked=\"checked\""; ?> name="new_rights[0]" type="checkbox" value="1" /> Users can access the board</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[1] == 1) echo " checked=\"checked\""; ?> name="new_rights[1]" type="checkbox" value="1" /> Users can post topics</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[2] == 1) echo " checked=\"checked\""; ?> name="new_rights[2]" type="checkbox" value="1" /> Users can post replies</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[3] == 1) echo " checked=\"checked\""; ?> name="new_rights[3]" type="checkbox" value="1" /> Users can post polls</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[4] == 1) echo " checked=\"checked\""; ?> name="new_rights[4]" type="checkbox" value="1" /> Guests can access the board</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[5] == 1) echo " checked=\"checked\""; ?> name="new_rights[5]" type="checkbox" value="1" /> Guests can post topics</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[6] == 1) echo " checked=\"checked\""; ?> name="new_rights[6]" type="checkbox" value="1" /> Guests can post replies</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><input<? if($act_board_rights[7] == 1) echo " checked=\"checked\""; ?> name="new_rights[7]" type="checkbox" value="1" /> Guests can post polls</span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Edit Board" /> <input class="button" name="delete" type="submit" value="Delete Board" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;
	}
	}
	}
	else {
	if(isset($delete)) {
	$board_data = get_board_data($administrator_boardscats_id);
	if($confirm == "yes") {
	$free_space_counter = 0; $topics_file = myfile("boards/$board_data[id].topics.txt");

	$board_file = myfile("objects/boards.txt");
	for($i = 0; $i < sizeof($board_file); $i++) {
	$act_board = myexplode($board_file[$i]);
	if($act_board[0] == $board_data[id]) {
	$board_file[$i] = "";
	$save = 1;
	break;
	}
	}
	if($save == 1) myfwrite("objects/boards.txt",$board_file,"w");
	else die("An error has occurred."); 

	$title_save = 0;
	$rights_file = myfile("boards/$board_data[id].rights.txt"); $titles_file = myfile("objects/titles.txt");
	for($i = 0; $i < sizeof($rights_file); $i++) {
	$act_right = myexplode($rights_file[$i]);
	if($act_right[1] == 2) {
	for($j = 0; $j < sizeof($titles_file); $j++) {
	$act_title = myexplode($titles_file[$j]);
	if($act_title[0] == $act_right[2]) {
	$act_title_boards = explode(',',$act_title[5]);
	for($k = 0; $k < sizeof($act_title_boards); $k++) {
	if($act_title_boards[$k] == $board_data['id']) {
	unset($act_title_boards[$k]);
	$act_title[5] = implode(',',$act_title_boards);
	$titles_file[$j] = myimplode($act_title);
	$title_save = 0;
	break;
	}
	}
	break;
	}
	}
	}
	}

	$free_space_counter += filesize("boards/$board_data[id].topics.txt") + filesize("boards/$board_data[id].id.topics.txt") + 
	unlink("boards/$board_data[id].topics.txt"); unlink("boards/$board_data[id].id.topics.txt"); 

	for($i = 0; $i < sizeof($topics_file); $i++) {
	$act_topic = killnl($topics_file[$i]);
	$act_topic_file = myfile("boards/$board_data[id].$act_topic.txt");
	$act_topic_data = myexplode($act_topic_file[0]);
	$free_space_counter += filesize("boards/$board_data[id].$act_topic.txt");
	unlink("boards/$board_data[id].$act_topic.txt");
	}

	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record('8',"%1: Board Deleted (ID: $board_data[id]) [IP: %2]");
	}

	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\tBoard Deleted");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Board Deleted</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><strong><br />The board was successfully deleted.</strong><br /><br /><?=round($free_space_counter/1024)?>kb in <?=sizeof($topics_file)+1?> file(s).</span><br /><br /></td>
	</tr>
</table>
</object><br />
<?
	}
	else {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=boardview\">Boards</a>\tDelete Board #$administrator_boardscats_id");
?>

<form action="administrator_boards_categories.php?method=change&amp;change=yes&amp;administrator_boardscats_id=<?=$administrator_boardscats_id?>" method="post"><input name="confirm" type="hidden" value="yes" /><input name="delete" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Delete Board</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><br /><span class="normal">Do you really want to delete this board? Once this action is done, it cannot be reversed.</span><br /><br /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Delete Board" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}
	else {
	$title = trim(mutate($title)); $description = trim(mutate($description));
	for($i = 0; $i < $boards_size; $i++) {
	$act_board = myexplode($boards[$i]);
	if($act_board[0] == $administrator_boardscats_id) {
	$act_board[10] = "$new_rights[0],$new_rights[1],$new_rights[2],$new_rights[3],$new_rights[4],$new_rights[5],$new_rights[6],$new_rights[7]\n";
	$act_board[1] = $title;
	$act_board[2] = $description;
	$act_board[5] = $category;
	$act_board[7] = "$basic_html";
	$boards[$i] = myimplode($act_board);
	$save = 1;
	break;
	}
	}
	if($save == 1) {
	myfwrite("objects/boards.txt",$boards,"w");
	header("Location: administrator_boards_categories.php?method=boardview");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Board Edited (ID: $administrator_boardscats_id) [IP: %2]");
	}
	exit;
	}
	else echo "An error has occurred.";
	}
	}
	break;

	case "moveboardup":
	$boards = myfile("objects/boards.txt"); $boards_number = sizeof($boards);
	for($i = 0; $i < $boards_number; $i++) {
	$current_board = myexplode($boards[$i]);
	if($current_board[0] == $id) {
	$boards_backup = $boards[($i - 1)]; $boards[($i - 1)] = $boards[$i]; $boards[$i] = $boards_backup;
	$moveboardup = "yes"; break;
	}
	}

	if($moveboardup == "yes") {
	myfwrite("objects/boards.txt",$boards,"w");
	header ("Location: administrator_boards_categories.php?method=boardview");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "moveboarddown":
	$boards = myfile("objects/boards.txt"); $boards_number = sizeof($boards);
	for($i = 0; $i < $boards_number; $i++) {
	$current_board = myexplode($boards[$i]);
	if($current_board[0] == $id) {
	$boards_backup = $boards[($i + 1)]; $boards[($i + 1)] = $boards[$i]; $boards[$i] = $boards_backup;
	$moveboarddown = "yes"; break;
	}
	}

	if($moveboarddown == "yes") {
	myfwrite("objects/boards.txt",$boards,"w");
	header ("Location: administrator_boards_categories.php?method=boardview");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "viewcategory":
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tCategories");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="3"><span class="heading">Categories</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="3"><span class="heading"><a href="administrator_boards_categories.php?method=newcategory">Add Category</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 45%"><span class="heading">Category Name</span></td>
		<td class="heading3" style="text-align: center; width: 30%"><span class="heading">Move</span></td>
		<td class="heading3" style="text-align: center; width: 25%"><span class="heading">Options</span></td>
	</tr>
<?
	$category = myfile("objects/categories.txt"); $category_number = sizeof($category);
	if($category_number == 0) echo "	<tr>
		<td class=\"one\" colspan=\"7\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no categories.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < $category_number; $i++) {
	$current_category = myexplode($category[$i]);
	if($category_number != 1) {
	if($i == 0) $moving =  "<a href=\"administrator_boards_categories.php?method=movecategorydown&amp;id=$current_category[0]\">&or;</a>";
	elseif($i == $category_number - 1) $moving = "<a href=\"administrator_boards_categories.php?method=movecategoryup&amp;id=$current_category[0]\">&and;</a>";
	else $moving = "<a href=\"administrator_boards_categories.php?method=movecategorydown&amp;id=$current_category[0]\">&or;</a>";
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
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$current_category[1]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$moving?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><a href="administrator_boards_categories.php?method=editcategory&amp;id=<?=$current_category[0]?>">Edit</a> - <a href="administrator_boards_categories.php?method=deletecategory&amp;id=<?=$current_category[0]?>">Delete</a></span></td>
	</tr>
<?
	}
	}
	echo "</table>
</object><br />
";
	break;

	case "movecategoryup":
	$category = myfile("objects/categories.txt"); $category_number = sizeof($category);
	for($i = 0; $i < $category_number; $i++) {
	$current_category = myexplode($category[$i]);
	if($current_category[0] == $id) {
	$category_backup = $category[($i - 1)]; $category[($i - 1)] = $category[$i]; $category[$i] = $category_backup;
	$movecategoryup = "yes"; break;
	}
	}

	if($movecategoryup == "yes") {
	myfwrite("objects/categories.txt",$category,"w");
	header ("Location: administrator_boards_categories.php?method=viewcategory");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "movecategorydown":
	$category = myfile("objects/categories.txt"); $category_number = sizeof($category);
	for($i = 0; $i < $category_number; $i++) {
	$current_category = myexplode($category[$i]);
	if($current_category[0] == $id) {
	$category_backup = $category[($i + 1)]; $category[($i + 1)] = $category[$i]; $category[$i] = $category_backup;
	$movecategorydown = "yes"; break;
	}
	}

	if($movecategorydown == "yes") {
	myfwrite("objects/categories.txt",$category,"w");
	header("Location: administrator_boards_categories.php?method=viewcategory");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "newcategory":
	if($newcategory != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=viewcategory\">Categories</a>\tAdd Category");
?>

<form action="administrator_boards_categories.php?method=newcategory&amp;newcategory=yes" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Categories</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Category</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Category Name</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="name" type="text" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Category" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {
	$latter_id = myfile("objects/id_categories.txt");
	$new_id = $latter_id[0]+1;

	$name = trim(mutate($name));
	$towrite = "$new_id\t$name\t\r\n";
	myfwrite("objects/categories.txt",$towrite,"a");

	myfwrite("objects/id_categories.txt",$new_id,"w");

	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Category Added (ID: $new_id) [IP: %2]");
	}

	header ("Location: administrator_boards_categories.php?method=viewcategory");
	exit;
	}
	break;

	case "deletecategory":
	$category = myfile("objects/categories.txt"); $category_number = sizeof($category);
	for($i = 0; $i < $category_number; $i++) {
	$currentr_category = myexplode($category[$i]);
	if($currentr_category[0] == $id) {
	$category[$i] = "";
	$deletecategory = 1; break;
	}
	}

	if($deletecategory == 1) {
	myfwrite("objects/categories.txt",$category,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Category Deleted (ID: $id) [IP: %2]");
	}
	header ("Location: administrator_boards_categories.php?method=viewcategory");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "editcategory":
	$category = myfile("objects/categories.txt"); $category_number = sizeof($category);
	if($editcategory != "yes") {
	include("board_top.php");
	for($i = 0; $i < $category_number; $i++) {
	$current_category = myexplode($category[$i]);
	if($current_category[0] == $id) {
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_boards_categories.php?method=viewcategory\">Categories</a>\tEdit Category");
?>

<form action="administrator_boards_categories.php?method=editcategory&amp;editcategory=yes" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Categories</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Edit Category</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Category Name</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="name" type="text" value="<?=$current_category[1]?>" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Edit Category" /><input name="id" type="hidden" value="<?=$id?>" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;
	}
	}
	}

	else {
	for($i = 0; $i < $category_number; $i++) {
	$current_category = myexplode($category[$i]);
	if($current_category[0] == $id) {
	$current_category[1] = mutate($name);
	$current_category[2] = "\r\n";
	$category[$i] = myimplode($current_category);
	$editcategory = 1; break;
	}
	}

	if($editcategory == 1) {
	myfwrite("objects/categories.txt",$category,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Category Edited (ID: $id) [IP: %2]");
	}
	header ("Location: administrator_boards_categories.php?method=viewcategory");
	exit;
	}
	else echo "An error has occurred.";
	}
	break;
	}
	}

	include("board_bottom.php");
?>