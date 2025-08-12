<?
	/*
	Silentum Boards v1.4.3
	administrator_titles.php copyright 2005 "HyperSilence"
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
	$titles_file = myfile('objects/titles.txt');
	include('board_top.php');
	$title_file = myfile("objects/titles.txt"); $title_file_size = sizeof($title_file);
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tTitles");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4"><span class="heading">Titles</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading"><a href="administrator_titles.php?method=new">Add Title</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 33%"><span class="heading">Title</span></td>
		<td class="heading3" style="text-align: center; width: 34%"><span class="heading">User</span></td>
		<td class="heading3" style="text-align: center; width: 15%"><span class="heading">Move</span></td>
		<td class="heading3" style="text-align: center; width: 18%"><span class="heading">Options</span></td>
	</tr>
<?
	if(sizeof($titles_file) == 0) echo "	<tr>
		<td class=\"one\" colspan=\"4\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no titles.</strong><br /><br /></span></td>
	</tr>
";
	else {
	for($i = 0; $i < sizeof($titles_file); $i++) {
	$act_title = myexplode($titles_file[$i]);
	if($title_file_size == 1) $moving = "";
	elseif($i == 0) $moving = "<a href=\"administrator_titles.php?method=movedown&amp;id=$act_title[0]\">&or;</a>";
	elseif($i == ($title_file_size - 1)) $moving = "<a href=\"administrator_titles.php?method=moveup&amp;id=$act_title[0]\">&and;</a>";
	else $moving = "<a href=\"administrator_titles.php?method=movedown&amp;id=$act_title[0]\">&or;</a>";

	if($act_title[2] == '') $title_members = '(No Users)';
	else {
	$title_members = explode(',',$act_title[2]);
	for($j = 0; $j < sizeof($title_members); $j++) {
	$title_members[$j] = get_user_name($title_members[$j]);
	}
	$title_members = implode(', ',$title_members);
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
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal"><?=$act_title[1]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal"><a href="index.php?page=profile&amp;id=<?=$act_title[2]?>"><?=get_user_name($act_title[2])?></a> (<?=$act_title[2]?>)</span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$moving?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center" valign="top"><span class="normal"><a href="administrator_titles.php?method=edit&amp;title_id=<?=$act_title[0]?>">Edit</a> - <a href="administrator_titles.php?method=kill&amp;title_id=<?=$act_title[0]?>">Delete</a></span></td>
	</tr>
<?
	}
	}
	echo "</table>
</object><br />
";
	break;

	case 'new':
	if($create) {
	$title = mutate($title);
	$title_members = array_unique(explode(',',$title_members));
	$title_file = myfile("objects/titles.txt");
	$new_id = myfile("objects/id_titles.txt"); $new_id = $new_id[0]+1;

	myfwrite("objects/id_titles.txt",$new_id,"w");

	while($act_value = each($title_members)) {
	if(!$act_member = myfile("members/$act_value[1].txt")) unset($title_members[$act_value[0]]);
	elseif(killnl($act_member[11]) != "") unset($title_members[$act_value[0]]);
	else {
	$act_member[11] = "$new_id\n";
	myfwrite("members/$act_value[1].txt",$act_member,"w");
	}
	}
	$title_members = implode(',',$title_members);
	$towrite = "$new_id\t$title\t$title_members\t\n";
	myfwrite('objects/titles.txt',$towrite,'a');
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Title Added [IP: %2]");
	}
	header("Location: administrator_titles.php");
	exit;
	}

	include('board_top.php');
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_titles.php\">Titles</a>\tAdd Title");
?>

<form action="administrator_titles.php?method=new" method="post"><input name="create" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Titles</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add Title</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>User ID</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="7" name="title_members" size="6" type="text" value="<?=$title_members?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Title</strong></span></td>
		<td class="two"><input class="textbox" maxlength="50" name="title" size="30" type="text" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add Title" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;

	case "moveup":
	$title_file = myfile("objects/titles.txt"); $title_file_size = sizeof($title_file);
	for($i = 0; $i < $title_file_size;$i++) {
	$act_title = myexplode($title_file[$i]);
	if($act_title[0] == $id) {
	$title_file_backup = $title_file[$i];
	$title_file[$i] = $title_file[($i - 1)];
	$title_file[($i - 1)] = $title_file_backup;
	$save = "yes"; break;
	}
	}

	if($save == "yes") {
	myfwrite("objects/titles.txt",$title_file,"w");
	header("Location: administrator_titles.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case "movedown":
	$title_file = myfile("objects/titles.txt");
	for($i = 0; $i < sizeof($title_file); $i++) {
	$act_title = myexplode($title_file[$i]);
	if($act_title[0] == $id) {
	$title_file_backup = $title_file[$i];
	$title_file[$i] = $title_file[($i + 1)];
	$title_file[($i + 1)] = $title_file_backup;
	$save = "yes"; break;
	}
	}

	if($save == "yes") {
	myfwrite("objects/titles.txt",$title_file,"w");
	header("Location: administrator_titles.php");
	exit;
	}
	else echo "An error has occurred.";
	break;

	case 'kill':
	$titles_file = myfile('objects/titles.txt');
	for($i = 0; $i < sizeof($titles_file); $i++) {
	$act_title = myexplode($titles_file[$i]);
	if($act_title[0] == $title_id) {
	if($kill) {

	$title_members = explode(',',$act_title[2]);
	for($j = 0; $j < sizeof($title_members); $j++) {
	change_user_information($title_members[$j],11,'');
	}

	$title_boards = explode(',',$act_title[5]);
	for($j = 0; $j < sizeof($title_boards); $j++) {
	$act_board_rfile = myfile("boards/$title_boards[$j].rights.txt");
	for($k = 0; $k < sizeof($act_board_rfile); $k++) {
	$act_right = myexplode($act_board_rfile[$k]);
	if($act_right[1] == 2 && $act_right[2] == $act_title[0]) {
	$act_board_rfile[$k] = "";
	myfwrite("boards/$title_boards[$j].rights.txt",$act_board_rfile,'w');
	break;
	}
	}
	}

	$titles_file[$i] = "";
	myfwrite('objects/titles.txt',$titles_file,'w');
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Title Deleted [IP: %2]");
	}
	header("Location: administrator_titles.php");
	exit;
	}

	include('board_top.php');
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_titles.php\">Titles</a>\tDelete Title");
?>

<form action="administrator_titles.php?method=kill&amp;title_id=<?=$title_id?>" method="post"><input name="kill" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Titles</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Delete Title</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><br /><span class="normal">Are you sure you want to delete <em><?=$act_title[1]?></em>?<br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Delete Title" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;
	}
	}
	break;

	case 'edit':
	$titles_file = myfile('objects/titles.txt');
	if($update) {
	for($i = 0; $i < sizeof($titles_file); $i++) {
	$act_title = myexplode($titles_file[$i]);
	if($act_title[0] == $title_id) {
	$title = mutate($title);
	$title_members = array_unique(explode(',',$title_members));
	$act_title_members = explode(',',$act_title[2]);
	for($j = 0; $j < sizeof($act_title_members); $j++) {
	change_user_information($act_title_members[$j],11,'');
	}
	while($act_value = each($title_members)) {
	if(!$act_member = myfile("members/$act_value[1].txt")) unset($title_members[$act_value[0]]);
	elseif(killnl($act_member[11]) != '') unset($title_members[$act_value[0]]);
	else {
	$act_member[11] = "$title_id\n";
	myfwrite("members/$act_value[1].txt",$act_member,'w');
	}
	}
	$title_members = implode(',',$title_members);
	$act_title[1] = $title;
	$act_title[2] = $title_members;
	$titles_file[$i] = myimplode($act_title);
	myfwrite('objects/titles.txt',$titles_file,'w');
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Title Edited [IP: %2]");
	}
	header("Location: administrator_titles.php");
	exit;
	break;
	}
	}
	}


	for($i = 0; $i < sizeof($titles_file); $i++) {
	$act_title = myexplode($titles_file[$i]);
	if($act_title[0] == $title_id) {
	include('board_top.php');
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_titles.php\">Titles</a>\tEdit Title");
?>

<form action="administrator_titles.php?method=edit&amp;title_id=<?=$title_id?>" method="post"><input name="update" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Titles</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Edit Title</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>User ID</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="7" name="title_members" size="6" type="text" value="<?=$act_title[2]?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Title</strong></span></td>
		<td class="two"><input class="textbox" maxlength="50" name="title" size="30" type="text" value="<?=$act_title[1]?>" /></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Edit Title" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;
	}
	}
	break;
	}
	}

	include('board_bottom.php');
?>