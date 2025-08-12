<?
	/*
	Silentum Boards v1.4.3
	administrator_censored_words.php copyright 2005 "HyperSilence"
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

	$censored = myfile("objects/censored_words.txt");
	$save = 0;
	$error = "";

	switch($method) {

	case "new":
	$displaypage = 1;
	$error = "";

	if($replacement == "" || !$replacement) $replacement = "!@%*";

	if($create == 1) {
	if(trim($word) == "") $error = 'You must enter a word to censor.';
	else {
	$new_id = myexplode($censored[sizeof($censored)-1]); $new_id = $new_id[0]+1;
	$towrite = "$new_id\t$word\t$replacement\t\r\n";
	myfwrite("objects/censored_words.txt",$towrite,"a"); $displaypage = 0;
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Censored Word Added (ID: $newid) [IP: %2]");
	}
	header("Location: administrator_censored_words.php");
	exit;
	}
	}

	if($displaypage == 1) {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_censored_words.php\">Censored Words</a>\tAdd Word");
?>

<form action="administrator_censored_words.php?method=new" method="post"><input name="create" type="hidden" value="1" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="3"><span class="heading">Censored Words</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Censored Word</span></td>
	</tr>
<? if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"2\"><span class=\"heading\">Error: $error</span></td>
	</tr>
"; ?>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Word</strong> (Case insensitive)</span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="word" type="text" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Censor With</strong></span></td>
		<td class="two"><input class="textbox" maxlength="50" name="replacement" type="text" value="<?=$replacement?>" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Word" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	break;

	case "edit":
	if($update == 1) {
	for($i = 0; $i < sizeof($censored); $i++) {
	$act_cword = myexplode($censored[$i]);
	if($act_cword[0] == $id) {
	$act_cword[1] = $word; $act_cword[2] = $replacement; $act_cword[3] = "\r\n";
	$censored[$i] = myimplode($act_cword); $save = 1; break;
	}
	}

	if($save == 1) {
	myfwrite("objects/censored_words.txt",$censored,"w");
	header("Location: administrator_censored_words.php");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Censored Word Edited (ID: $id) [IP: %2]");
	}
	exit;
	}
	else echo "An error has occurred.";
	}
	else {
	for($i = 0; $i < sizeof($censored); $i++) {
	$act_cword = myexplode($censored[$i]);
	if($act_cword[0] == $id) {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_censored_words.php\">Censored Words</a>\tEdit Word");
?>

<form action="administrator_censored_words.php?method=edit" method="post"><input name="update" type="hidden" value="1" /><input name="id" type="hidden" value="<?=$id?>" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Censored Words</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Edit Censored Word</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Word</strong> (Case insensitive)</span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="50" name="word" type="text" value="<?=$act_cword[1]?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Censor With</strong></span></td>
		<td class="two"><input class="textbox" maxlength="50" name="replacement" type="text" value="<?=trim($act_cword[2])?>" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Edit Word" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;
	}
	}
	}
	break;

	case "moveup":
	$censored_file = myfile("objects/censored_words.txt"); $censored_file_size = sizeof($censored_file);
	for($i = 0; $i < $censored_file_size;$i++) {
	$act_cword = myexplode($censored_file[$i]);
	if($act_cword[0] == $id) {
	$censored_file_backup = $censored_file[$i];
	$censored_file[$i] = $censored_file[($i - 1)];
	$censored_file[($i - 1)] = $censored_file_backup;
	$save = "yes"; break;
	}
	}

	if($save == "yes") {
	myfwrite("objects/censored_words.txt",$censored_file,"w");
	header("Location: administrator_censored_words.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "movedown":
	$censored_file = myfile("objects/censored_words.txt");
	for($i = 0; $i < sizeof($censored_file); $i++) {
	$act_cword = myexplode($censored_file[$i]);
	if($act_cword[0] == $id) {
	$censored_file_backup = $censored_file[$i];
	$censored_file[$i] = $censored_file[($i + 1)];
	$censored_file[($i + 1)] = $censored_file_backup;
	$save = "yes"; break;
	}
	}

	if($save == "yes") {
	myfwrite("objects/censored_words.txt",$censored_file,"w");
	header("Location: administrator_censored_words.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "kill":
	for($i = 0; $i < sizeof($censored); $i++) {
	$act_cword = myexplode($censored[$i]);
	if($act_cword[0] == $id) {
	$save = 1; $censored[$i] = ""; break;
	}
	}

	if($save == 1) {
	myfwrite("objects/censored_words.txt",$censored,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Censored Word Deleted (ID: $id) [IP: %2]");
	}
	header("Location: administrator_censored_words.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	default:
	include("board_top.php");
	$censored_file = myfile("objects/censored_words.txt"); $censored_file_size = sizeof($censored_file);
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tCensored Words");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4"><span class="heading">Censored Words</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading"><a href="administrator_censored_words.php?method=new">Add Censored Word</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 33%"><span class="heading">Word</span></td>
		<td class="heading3" style="text-align: center; width: 34%"><span class="heading">Censored With</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Move</span></td>
		<td class="heading3" style="text-align: center; width: 18%"><span class="heading">Options</span></td>
	</tr>
<?
	if(sizeof($censored) == 0) echo "	<tr>
		<td class=\"one\" colspan=\"4\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no censored words.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < sizeof($censored); $i++) {
	$act_cword = myexplode($censored[$i]);
	if($i == 0 && $i == $censored_file_size) $moving = "";
	elseif($i == 0) $moving = "<a href=\"administrator_censored_words.php?method=movedown&amp;id=$act_cword[0]\">&or;</a>";
	elseif($i == ($censored_file_size - 1)) $moving = "<a href=\"administrator_censored_words.php?method=moveup&amp;id=$act_cword[0]\">&and;</a>";
	else $moving = "<a href=\"administrator_censored_words.php?method=movedown&amp;id=$act_cword[0]\">&or;</a>";

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
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=htmlspecialchars($act_cword[1])?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=htmlspecialchars(trim($act_cword[2]))?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$moving?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><a href="administrator_censored_words.php?method=edit&amp;id=<?=$act_cword[0]?>">Edit</a> - <a href="administrator_censored_words.php?method=kill&amp;id=<?=$act_cword[0]?>">Delete</a></span></td>
	</tr>
<?
	}
	}
	echo "</table>
</object><br />
";
	break;
	}
	}

	include("board_bottom.php");
?>