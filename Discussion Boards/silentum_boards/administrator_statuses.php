<?
	/*
	Silentum Boards v1.4.3
	administrator_statuses.php copyright 2005 "HyperSilence"
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
	default:
	include("board_top.php");
	$status_file = myfile("objects/statuses.txt"); $status_file_size = sizeof($status_file);
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tStatuses");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="6"><span class="heading">Statuses</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="6"><span class="heading"><a href="administrator_statuses.php?method=new">Add Status</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 35%"><span class="heading">Status</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Minimum Karma</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Maximum Karma</span></td>
		<td class="heading3" style="text-align: center; width: 10%"><span class="heading">Stars</span></td>
		<td class="heading3" style="text-align: center; width: 10%"><span class="heading">Move</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Options</span></td>
	</tr>
<?
	if($status_file_size == 0) echo "	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no statuses.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < $status_file_size; $i++) {
	$act_status = myexplode($status_file[$i]);

	if($i == 0 && $i == $status_file_size) $moving = "";
	elseif($i == 0) $moving = "<a href=\"administrator_statuses.php?method=movedown&amp;id=$act_status[0]\">&or;</a>";
	elseif($i == ($status_file_size - 1)) $moving = "<a href=\"administrator_statuses.php?method=moveup&amp;id=$act_status[0]\">&and;</a>";
	else $moving = "<a href=\"administrator_statuses.php?method=movedown&amp;id=$act_status[0]\">&or;</a>";

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
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_status[1]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_status[2]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_status[3]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_status[4]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$moving?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><a href="administrator_statuses.php?method=edit&amp;id=<?=$act_status[0]?>">Edit</a> - <a href="administrator_statuses.php?method=delete&amp;id=<?=$act_status[0]?>">Delete</a></span></td>
	</tr>
<?
	}
	}
	echo "</table>
</object><br />
";
	break;

	case "edit":
	$status_file = myfile("objects/statuses.txt"); $status_file_size = sizeof($status_file);
	if($edit != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_statuses.php\">Statuses</a>\tEdit Status");
?>

<form action="administrator_statuses.php?id=<?=$id?>&amp;method=edit" method="post"><input name="edit" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Statuses</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Edit Status</span></td>
	</tr>
<?
	for($i = 0; $i < $status_file_size; $i++) {
	$act_status = myexplode($status_file[$i]);
	if($act_status[0] == $id) {
?>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Status</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="status_name" size="30" type="text" value="<?=$act_status[1]?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Minimum Karma</strong></span></td>
		<td class="two"><input class="textbox" maxlength="6" name="minimum_karma" size="6" type="text" value="<?=$act_status[2]?>" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Maximum Karma</strong></span></td>
		<td class="one"><input class="textbox" maxlength="6" name="maximum_karma" size="6" type="text" value="<?=$act_status[3]?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Stars</strong></span></td>
		<td class="two"><select class="textbox" name="stars"><option value="<?=$act_status[4]?>"><?=$act_status[4]?> - Current</option><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select></td>
	</tr>
<?
	break;
	}
	}
	echo "</table>
</object><br />
<object>
<table cellspacing=\"$cellspacing\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: $twidth\">
	<tr>
		<td><input class=\"button\" type=\"submit\" value=\"Edit Status\" /></td>
	</tr>
</table>
</object>
</form>
";
	}

	else {
	for($i = 0; $i < $status_file_size;$i++) {
	$act_status = myexplode($status_file[$i]);
	if($act_status[0] == $id) {
	$act_status[1] = mutate($status_name);
	$act_status[2] = $minimum_karma;
	$act_status[3] = $maximum_karma;
	$act_status[4] = $stars;
	$status_file[$i] = myimplode($act_status);
	$edit = "yes"; break;
	}
	}

	if($edit == "yes") {
	myfwrite("objects/statuses.txt",$status_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Status Edited (ID: $id) [IP: %2]");
	}
	header("Location: administrator_statuses.php");
	exit;
	}
	else echo "An error has occurred.";
	}
	break;


	case "delete":
	$status_file = myfile("objects/statuses.txt");
	for($i = 0; $i < sizeof($status_file); $i++) {
	$act_status = myexplode($status_file[$i]);
	if($act_status[0] == $id) {
	$status_file[$i] = "";
	$delete = "yes"; break;
	}
	}

	if($delete == "yes") {
	myfwrite("objects/statuses.txt",$status_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Status Deleted (ID: $id) [IP: %2]");
	}
	header("Location: administrator_statuses.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "moveup":
	$status_file = myfile("objects/statuses.txt"); $status_file_size = sizeof($status_file);
	for($i = 0; $i < $status_file_size;$i++) {
	$act_status = myexplode($status_file[$i]);
	if($act_status[0] == $id) {
	$status_file_backup = $status_file[$i];
	$status_file[$i] = $status_file[($i - 1)];
	$status_file[($i - 1)] = $status_file_backup;
	$moveup = "yes"; break;
	}
	}

	if($moveup == "yes") {
	myfwrite("objects/statuses.txt",$status_file,"w");
	header("Location: administrator_statuses.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "movedown":
	$status_file = myfile("objects/statuses.txt");
	for($i = 0; $i < sizeof($status_file); $i++) {
	$act_status = myexplode($status_file[$i]);
	if($act_status[0] == $id) {
	$status_file_backup = $status_file[$i];
	$status_file[$i] = $status_file[($i + 1)];
	$status_file[($i + 1)] = $status_file_backup;
	$movedown = "yes"; break;
	}
	}

	if($movedown == "yes") {
	myfwrite("objects/statuses.txt",$status_file,"w");
	header("Location: administrator_statuses.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "new":
	if($new != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_statuses.php\">Statuses</a>\tAdd Status");
?>

<form action="administrator_statuses.php?method=new" method="post"><input name="new" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Statuses</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Status</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Status</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="status_name" size="30" type="text" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Minimum Karma</strong></span></td>
		<td class="two"><input class="textbox" maxlength="6" name="minimum_karma" size="6" type="text" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Maximum Karma</strong></span></td>
		<td class="one"><input class="textbox" maxlength="6" name="maximum_karma" size="6" type="text" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Stars</strong></span></td>
		<td class="two"><select class="textbox" name="stars"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Status" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {
	$new_id = myfile("objects/id_statuses.txt"); $new_id = $new_id[0]+1;

	myfwrite("objects/id_statuses.txt",$new_id,"w");

	$towrite = $new_id."\t$status_name\t$minimum_karma\t$maximum_karma\t$stars\t\n";
	myfwrite("objects/statuses.txt",$towrite,"a");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Status Added (ID: $new_id) [IP: %2]");
	}
	header("Location: administrator_statuses.php");
	exit;
	}
	break;
	}

	include("board_bottom.php");

	}
?>