<?
	/*
	Silentum Boards v1.4.3
	mod_suspended_users.php copyright 2005 "HyperSilence"
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
	$suspended = myfile("objects/suspended_users.txt");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("10","%1: Suspended Users Viewed [IP: %2]");
	}
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=user_cp\">User Control Panel</a>\tSuspended Users");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="3"><span class="heading">Suspended Users</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="3"><span class="heading">Please allow at least 72 hours (3 days) to pass before unsuspending any user.</span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 33%"><span class="heading">User</span></td>
		<td class="heading3" style="text-align: center; width: 34%"><span class="heading">Suspended Since</span></td>
		<td class="heading3" style="text-align: center; width: 33%"><span class="heading">Unsuspend</span></td>
	</tr>
<?
	if(sizeof($suspended) == 0) echo "	<tr>
		<td class=\"one\" colspan=\"3\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no suspended users.</strong><br /><br /></span></td>
	</tr>
</table>
</object><br />
";
	for($i = 0; $i < sizeof($suspended); $i++) {
	$act_suspended = myexplode($suspended[$i]);
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
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><a href="index.php?page=profile&amp;id=<?=$act_suspended[0]?>"><?=get_user_name($act_suspended[0])?></a></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_suspended[1]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><a href="administrator_actions.php?id=<?=$act_suspended[0]?>&amp;status=6&amp;unsuspend=yes">Unsuspend</a></span></td>
	</tr>
<?
	}
	}

	include("board_bottom.php");

	}
?>