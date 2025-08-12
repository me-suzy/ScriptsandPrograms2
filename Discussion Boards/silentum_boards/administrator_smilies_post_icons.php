<?
	/*
	Silentum Boards v1.4.3
	administrator_smilies_post_icons.php copyright 2005 "HyperSilence"
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
	$smilie_file = myfile("objects/smilies.txt"); $smilie_file_size = sizeof($smilie_file);
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tSmilies &amp; Post Icons");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="5"><span class="heading">Smilies &amp; Post Icons</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="5"><span class="heading"><a href="administrator_smilies_post_icons.php?method=new">Add Smilie</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 10%"><span class="heading">Smilie</span></td>
		<td class="heading3" style="text-align: center; width: 50%"><span class="heading">Smilie URL</span></td>
		<td class="heading3" style="text-align: center; width: 10%"><span class="heading">Text</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Move</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Options</span></td>
	</tr>
<?
	if($smilie_file_size == 0) echo "	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no smilies.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < $smilie_file_size; $i++) {
	$act_smilie = myexplode($smilie_file[$i]);

	if($i == 0 && $i == $smilie_file_size) $moving = "";
	elseif($i == 0) $moving = "<a href=\"administrator_smilies_post_icons.php?method=movedown&amp;id=$act_smilie[0]\">&or;</a>";
	elseif($i == ($smilie_file_size - 1)) $moving = "<a href=\"administrator_smilies_post_icons.php?method=moveup&amp;id=$act_smilie[0]\">&and;</a>";
	else $moving = "<a href=\"administrator_smilies_post_icons.php?method=movedown&amp;id=$act_smilie[0]\">&or;</a>";

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
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><img alt="<?=$act_smilie[1]?>" class="icon" src="<?=trim($act_smilie[2])?>" title="<?=$act_smilie[1]?>" /></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=trim($act_smilie[2])?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_smilie[1]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$moving?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><a href="administrator_smilies_post_icons.php?method=edit&amp;id=<?=$act_smilie[0]?>">Edit</a> - <a href="administrator_smilies_post_icons.php?method=delete&amp;id=<?=$act_smilie[0]?>">Delete</a></span></td>
	</tr>
<?
	}
	}
?>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading2" colspan="4"><span class="heading"><a href="administrator_smilies_post_icons.php?method=new2">Add Post Icon</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 10%"><span class="heading">Post Icon</span></td>
		<td class="heading3" style="text-align: center; width: 50%"><span class="heading">Post Icon URL</span></td>
		<td class="heading3" style="text-align: center; width: 25%"><span class="heading">Move</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Options</span></td>
	</tr>
<?
	$post_icon_file = myfile("objects/post_icons.txt"); $post_icon_file_size = sizeof($post_icon_file);
	if($post_icon_file_size == 0) echo "	<tr>
		<td class=\"one\" colspan=\"6\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no post icons.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < $post_icon_file_size; $i++) {
	$act_post_icon = myexplode($post_icon_file[$i]);

	if($i == 0 && $i == $post_icon_file_size) $moving = "";
	elseif($i == 0) $moving = "<a href=\"administrator_smilies_post_icons.php?method=movedown2&amp;id=$act_post_icon[0]\">&or;</a>";
	elseif($i == ($post_icon_file_size - 1)) $moving = "<a href=\"administrator_smilies_post_icons.php?method=moveup2&amp;id=$act_post_icon[0]\">&and;</a>";
	else $moving = "<a href=\"administrator_smilies_post_icons.php?method=movedown2&amp;id=$act_post_icon[0]\">&or;</a>";

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
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><img alt="Post Icon" class="icon" src="<?=$act_post_icon[1]?>" title="Post Icon" /></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_post_icon[1]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$moving?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><a href="administrator_smilies_post_icons.php?method=edit2&amp;id=<?=$act_post_icon[0]?>">Edit</a> - <a href="administrator_smilies_post_icons.php?method=delete2&amp;id=<?=$act_post_icon[0]?>">Delete</a></span></td>
	</tr>
<?
	}
	}
	echo "</table>
</object><br />
";
	break;

	case "edit":
	$smilie_file = myfile("objects/smilies.txt"); $smilie_file_size = sizeof($smilie_file);
	if($edit != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_smilies_post_icons.php\">Smilies &amp; Post Icons</a>\tEdit Smilie");
?>

<form action="administrator_smilies_post_icons.php?method=edit&amp;id=<?=$id?>" method="post"><input name="edit" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Smilies &amp; Post Icons</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Edit Smilie</span></td>
	</tr>
<?
	for($i = 0; $i < $smilie_file_size; $i++) {
	$act_smilie = myexplode($smilie_file[$i]);
	if($act_smilie[0] == $id) {
?>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Smilie</strong></span></td>
		<td class="one" style="width: 80%"><img class="icon" src="<?=$act_smilie[2]?>" title="Smilie" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>URI to Smilie</strong></span></td>
		<td class="two"><input class="textbox" maxlength="100" name="uri" size="30" type="text" value="<?=$act_smilie[2]?>" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Text</strong></span></td>
		<td class="one"><input class="textbox" maxlength="10" name="text" size="6" type="text" value="<?=$act_smilie[1]?>" /></td>
	</tr>
<?
	break;
	}
	}
	echo "
</table>
</object><br />
<object>
<table cellspacing=\"$cellspacing\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: $twidth\">
	<tr>
		<td><input class=\"button\" type=\"submit\" value=\"Edit Smilie\" /></td>
	</tr>
</table>
</object>
</form>
";
	}
	else {
	for($i = 0; $i < $smilie_file_size; $i++) {
	$act_smilie = myexplode($smilie_file[$i]);
	if($act_smilie[0] == $id) {
	$act_smilie[1] = $text;
	$act_smilie[2] = $uri;
	$edit = "yes";
	$smilie_file[$i] = myimplode($act_smilie); break;
	}
	}

	if($edit = "yes") {
	myfwrite("objects/smilies.txt",$smilie_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Smilie Edited (ID: $id) [IP: %2]");
	}
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	}
	break;

	case "edit2":
	$post_icon_file = myfile("objects/post_icons.txt"); $post_icon_file_size = sizeof($post_icon_file);
	if($edit2 != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_smilies_post_icons.php\">Smilies &amp; Post Icons</a>\tEdit Post Icon");
?>

<form action="administrator_smilies_post_icons.php?method=edit2&amp;id=<?=$id?>" method="post"><input name="edit2" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Smilies &amp; Post Icons</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Edit Post Icon</span></td>
	</tr>
<?
	for($i = 0; $i < $post_icon_file_size; $i++) {
	$act_post_icon = myexplode($post_icon_file[$i]);
	if($act_post_icon[0] == $id) {
?>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Post Icon</strong></span></td>
		<td class="one" style="width: 80%"><img class="icon" src="<?=$act_post_icon[1]?>" title="Post Icon" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>URI to Post Icon</strong></span></td>
		<td class="two"><input class="textbox" name="uri" size="30" type="text" value="<?=$act_post_icon[1]?>" /></td>
	</tr>
<?
	break;
	}
	}
	echo "
</table>
</object><br />
<object>
<table cellspacing=\"$cellspacing\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: $twidth\">
	<tr>
		<td><input class=\"button\" type=\"submit\" value=\"Edit Post Icon\" /></td>
	</tr>
</table>
</object>
</form>
";
	}
	else {
	for($i = 0; $i < $post_icon_file_size; $i++) {
	$act_post_icon = myexplode($post_icon_file[$i]);
	if($act_post_icon[0] == $id) {
	$act_post_icon[1] = $uri;
	$edit2 = "yes";
	$post_icon_file[$i] = myimplode($act_post_icon);
	break;
	}
	}

	if($edit2 = "yes") {
	myfwrite("objects/post_icons.txt",$post_icon_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Post Icon Edited (ID: $id) [IP: %2]");
	}
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	}
	break;

	case "delete":
	if($id && $id != "") {
	$smilie_file = myfile("objects/smilies.txt");
	for($i = 0; $i < sizeof($smilie_file); $i++) {
	$act_smilie = myexplode($smilie_file[$i]);
	if($act_smilie[0] == $id) {
	$smilie_file[$i] = "";
	$delete = "yes"; break;
	}
	}

	if($delete == "yes") {
	myfwrite("objects/smilies.txt",$smilie_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Smilie Deleted (ID: $id) [IP: %2]");
	}
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	else echo "An error has occurred.";
	}
	break;

	case "delete2":
	if($id && $id != "") {
	$post_icon_file = myfile("objects/post_icons.txt");
	for($i = 0; $i < sizeof($post_icon_file); $i++) {
	$act_post_icon = myexplode($post_icon_file[$i]);
	if($act_post_icon[0] == $id) {
	$post_icon_file[$i] = "";
	$delete2 = "yes"; break;
	}
	}

	if($delete2 == "yes") {
	myfwrite("objects/post_icons.txt",$post_icon_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Post Icon Deleted (ID: $id) [IP: %2]");
	}
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	else echo "An error has occurred.";
	}
	break;

	case "moveup":
	$smilie_file = myfile("objects/smilies.txt");
	for($i = 0; $i < sizeof($smilie_file); $i++) {
	$act_smilie = myexplode($smilie_file[$i]);
	if($act_smilie[0] == $id) {
	$smilie_file_backup = $smilie_file[$i];
	$smilie_file[$i] = $smilie_file[($i - 1)];
	$smilie_file[($i - 1)] = $smilie_file_backup;
	$moveup = "yes"; break;
	}
	}

	if($moveup == "yes") {
	myfwrite("objects/smilies.txt",$smilie_file,"w");
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "movedown":
	$smilie_file = myfile("objects/smilies.txt");
	for($i = 0; $i < sizeof($smilie_file); $i++) {
	$act_smilie = myexplode($smilie_file[$i]);
	if($act_smilie[0] == $id) {
	$smilie_file_backup = $smilie_file[$i];
	$smilie_file[$i] = $smilie_file[($i + 1)];
	$smilie_file[($i + 1)] = $smilie_file_backup;
	$movedown = "yes"; break;
	}
	}

	if($movedown == "yes") {
	myfwrite("objects/smilies.txt",$smilie_file,"w");
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "moveup2":
	$post_icon_file = myfile("objects/post_icons.txt");
	for($i = 0; $i < sizeof($post_icon_file); $i++) {
	$act_post_icon = myexplode($post_icon_file[$i]);
	if($act_post_icon[0] == $id) {
	$post_icon_file_backup = $post_icon_file[$i];
	$post_icon_file[$i] = $post_icon_file[($i - 1)];
	$post_icon_file[($i - 1)] = $post_icon_file_backup;
	$moveup2 = "yes"; break;
	}
	}

	if($moveup2 == "yes") {
	myfwrite("objects/post_icons.txt",$post_icon_file,"w");
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "movedown2":
	$post_icon_file = myfile("objects/post_icons.txt");
	for($i = 0; $i < sizeof($post_icon_file); $i++) {
	$act_post_icon = myexplode($post_icon_file[$i]);
	if($act_post_icon[0] == $id) {
	$post_icon_file_backup = $post_icon_file[$i];
	$post_icon_file[$i] = $post_icon_file[($i + 1)];
	$post_icon_file[($i + 1)] = $post_icon_file_backup;
	$movedown2 = "yes"; break;
	}
	}

	if($movedown2 == "yes") {
	myfwrite("objects/post_icons.txt",$post_icon_file,"w");
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "new":
	if($new != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_smilies_post_icons.php\">Smilies &amp; Post Icons</a>\tAdd Smilie");
?>

<form action="administrator_smilies_post_icons.php?method=new" method="post"><input name="new" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Smilies &amp; Post Icons</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Smilie</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>URI to Smilie</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="100" name="uri" size="30" type="text" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Text</strong></span></td>
		<td class="two"><input class="textbox" maxlength="10" name="text" size="6" type="text" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Smilie" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {
	$newid = myfile("objects/id_smilies.txt");
	$newid = $newid[0]+1;
	$towrite = "$newid\t$text\t$uri\t\r\n";
	myfwrite("objects/smilies.txt",$towrite,"a"); myfwrite("objects/id_smilies.txt",$newid,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Smilie Added (ID: $newid) [IP: %2]");
	}
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	break;

	case "new2":
	if($new2 != "yes") {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_smilies_post_icons.php\">Smilies &amp; Post Icons</a>\tAdd Post Icon");
?>

<form action="administrator_smilies_post_icons.php?method=new2" method="post"><input name="new2" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="5"><span class="heading">Smilies &amp; Post Icons</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Post Icon</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>URI to Post Icon</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="100" name="uri" size="30" type="text" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Post Icon" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {
	$newid = myfile("objects/id_post_icons.txt");
	$newid = $newid[0]+1;
	$towrite = "$newid\t$uri\t\r\n";
	myfwrite("objects/post_icons.txt",$towrite,"a"); myfwrite("objects/id_post_icons.txt",$newid,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Post Icon Added (ID: $newid) [IP: %2]");
	}
	header("Location: administrator_smilies_post_icons.php");
	exit;
	}
	break;
	}
	include("board_bottom.php");
	}
?>