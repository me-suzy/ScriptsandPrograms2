<?
	/*
	Silentum Boards v1.4.3
	moderations.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");

	if($page == "moderations") {

	if(!isset($moderations_id)) $moderations_id = $user_id;

	if($user_logged_in != 1) {
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=moderations\">Moderations</a>\tAccess Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	elseif($moderations_id != $user_id && $user_data['status'] != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=moderations\">Moderations</a>\t".$txt['Navigation']['Restricted']['0']);
	echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	else {
	$save = "";
	if(!$method || $method == "") $method = "overview";

	if($moderations_id != $user_id) $foruser = "for ".get_user_name($moderations_id);

	if($method == "overview") {
	$moderations = myfile("members/".$moderations_id.".moderations.txt");
	record("10","%1: Moderations Viewed [IP: %2]");
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\tModerations $foruser");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="5"><span class="heading">Moderations</span></td>
	</tr>
	<tr>
		<td class="heading2" style="text-align: center; width: 5%"><span class="heading">ID</span></td>
		<td class="heading2" style="text-align: center; width: 20%"><span class="heading">Date</span></td>
		<td class="heading2" style="text-align: center; width: 20%"><span class="heading">Board</span></td>
		<td class="heading2" style="text-align: center; width: 45%"><span class="heading">Topic</span></td>
		<td class="heading2" style="text-align: center; width: 10%"><span class="heading">Karma Loss</span></td>
	</tr>
<?
	if($moderations_id != $user_id) $you = get_user_name($moderations_id); else $you = "You";
	if($moderations_id != $user_id) $have = "has"; else $have = "have";
	if(sizeof($moderations) == 0 || !myfile_exists("members/".$moderations_id.".moderations.txt")) echo "	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center;\"><span class=\"normal\"><br /><strong>$you $have no moderations.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < sizeof($moderations); $i++) {
	$act_moderations = myexplode($moderations[$i]);
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
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal"><?=$i+1?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal"><?=$act_moderations[1]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal"><? if($act_moderations[2] != "Suspension" && $act_moderations[2] != "Ban") echo "<a href=\"index.php?method=board&amp;board=".$act_moderations[2]."\">".get_board_name($act_moderations[2])."</a>"; else echo $act_moderations[2]; ?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal">
		<? if(myfile_exists("boards/".$act_moderations[2].".".$act_moderations[3].".txt")) echo "<a href=\"index.php?method=topic&amp;board=".$act_moderations[2]."&amp;thread=".$act_moderations[3]."\">".get_thread_name($act_moderations[2],$act_moderations[3])."</a>"; elseif(!myfile_exists("boards/".$act_moderations[2].".".$act_moderations[3].".txt")) echo $act_moderations[3];
		if($act_moderations[3] == "") echo "Deleted";?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal"><? if($act_moderations[4] != "1" && $act_moderations[4] != "5" && $act_moderations[4] != "10") echo "0"; else echo $act_moderations[4]?></span></td>
	</tr>
	<tr>
		<td class="<?=$bgcolor?>" colspan="5" style="text-align: left" valign="top"><span class="normal">Post - <strong><?=htmlspecialchars($act_moderations[5])?></strong></span></td>
	</tr>
<?
	}
	}
	}

	echo "</table>
</object><br />
";
	include("board_bottom.php");

	}
	}
	if($page == "note_box") {

	if(!isset($note_box_id)) $note_box_id = $user_id;

	if($user_logged_in != 1) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=note_box\">Note Box</a>\tAccess Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	elseif($note_box_id != $user_id && $user_data['status'] != "1" && $user_data['status'] != "2") {
	include("board_top.php"); echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=note_box\">Note Box</a>\t".$txt['Navigation']['Restricted']['0']);
	echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	else {

	$save = "";
	if(myfile_exists("members/$note_box_id.notebox.txt")) {
	$notes = myfile("members/$note_box_id.notebox.txt"); $note_number = sizeof($notes);
	}

	switch($method) {

	default:
	include("board_top.php");
	if(myfile_exists("members/$note_box_id.notebox.txt")) {
	$notes = array_reverse($notes);
	}
	if($note_box_id != $user_id) $foruser = "for ".get_user_name($note_box_id);
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\tNote Box $foruser");
?>

<form action="index.php?page=note_box&amp;method=delete" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">Note Box</span></td>
	</tr>
<?
	if($note_box_id != $user_id) $your = get_user_name($note_box_id)."'s"; else $your = "Your";
	if($note_box_id == $user_id) $deletechecked = "</table>
</object><br />
<object>
<table cellspacing=\"$cellspacing\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: $twidth\">
	<tr>
		<td><input class=\"button\" type=\"submit\" value=\"Delete Checked Note(s)\" /></td>
	</tr>
</table>
</object>
</form>
";
	else $deletechecked = "</table>
</object>
</form>
";
	if($note_number != 0) {
	for($i = 0; $i < $note_number; $i++) {
	$ak_note = myexplode( $notes[$i]);
	if($note_box_id != $user_id) $box_id = "&amp;note_box_id=$note_box_id";
	if($ak_note[7] == 1) $ak_note[1] = "<a href=\"index.php?page=note_box".$box_id."&amp;method=view&amp;note_id=$ak_note[0]\"><strong>$ak_note[1]</strong></a>";
	else $ak_note[1] = "<a href=\"index.php?page=note_box".$box_id."&amp;method=view&amp;note_id=$ak_note[0]\">$ak_note[1]</a>";
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
		<td class="<?=$bgcolor?>" style="width: 1%"><input name="deletenote[<?=$ak_note[0]?>]" type="checkbox" value="1" /></td>
		<td class="<?=$bgcolor?>"><span class="normal"><?=$ak_note[4]?> - <?=$ak_note[1]?></span></td>
	</tr>
<?
	}
	echo $deletechecked;
	}
	else echo "	<tr>
		<td class=\"one\" colspan=\"2\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>$your note box is empty.</strong><br /><br /></span></td>
	</tr>
</table>
</object>
</form>
";
	break;

	case "delete":
	for($i = 0; $i < $note_number; $i++) {
	$act_note = myexplode($notes[$i]);
	if($deletenote[$act_note[0]] == 1) {
	$notes[$i] = "";
	}
	}
	$logging = explode(',',$config['record_options']);
	if(in_array(12,$logging)) {
	record("10","%1: Note Deleted [IP: %2]");
	}
	myfwrite("members/$note_box_id.notebox.txt",$notes,'w');
	header("Location: index.php?page=note_box");
	break;

	case "view":
	include("board_top.php");
	for($i = 0; $i < $note_number; $i++) {
	$current_note = myexplode($notes[$i]);
	if($current_note[0] == $note_id) {
	if($current_note[7] == 1) make_note_read($note_box_id,$note_id);
	if($current_note[5] == 1) $current_note[2] = make_smilies($current_note[2]);
	if($current_note[6] == 1) $current_note[2] = basic_html($current_note[2]);
	if($note_box_id != $user_id) $foruser = "for ".get_user_name($note_box_id);
	if($note_box_id != $user_id) $link = "&amp;note_box_id=$note_box_id";
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\t<a href=\"index.php?page=note_box".$link."\">Note Box ".$foruser."</a>\t$current_note[1]");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Note Box</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading"><?=$current_note[4]?> - <?=$current_note[1]?></span></td>
	</tr>
	<tr>
		<td class="one" colspan="2"><span class="normal"><?=$current_note[2]?></span></td>
	</tr>
</table>
</object><br />
<?
	break;
	}
	}
	break;

	for($i = 0; $i < $note_number; $i++) {
	$current_note = myexplode($notes[$i]);
	if($current_note[0] == $note_id) {
	$notes[$i] = "";
	$save = 1;
	break;
	}
	if($save == 1) {
	myfwrite("members/$note_box_id.notebox.txt",$notes,"w");
	header("Location: index.php?page=note_box");
	exit;
	}
	else echo "An error has occurred.";
	}
	break;

	}
	}
	}
?>