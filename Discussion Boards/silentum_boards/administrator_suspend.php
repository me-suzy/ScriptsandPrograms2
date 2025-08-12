<?
	/*
	Silentum Boards v1.4.3
	administrator_suspend.php copyright 2005 "HyperSilence"
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
	$save = "";
	if(!$method || $method == "") $method = "overview";

	if($method == "overview") {
	$ips = myfile("objects/ip_suspensions.txt");
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tIP Suspensions");
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4"><span class="heading">IP Suspensions</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading"><a href="administrator_suspend.php?method=new">Add IP Suspension</a></span></td>
	</tr>
	<tr>
		<td class="heading3" style="text-align: center; width: 25%"><span class="heading">IP Address</span></td>
		<td class="heading3" style="text-align: center; width: 25%"><span class="heading">Remaining Suspension Time</span></td>
		<td class="heading3" style="text-align: center; width: 25%"><span class="heading">Suspended From</span></td>
		<td class="heading3" style="text-align: center; width: 25%"><span class="heading">Delete</span></td>
	</tr>
<?
	if(sizeof($ips) == 0) echo "	<tr>
		<td class=\"one\" colspan=\"4\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There are no IP suspensions.</strong><br /><br /></span></td>
	</tr>
";
	for($i = 0; $i < sizeof($ips); $i++) {
	$act_ip = myexplode($ips[$i]);
	if($act_ip[1] == -1) $act_ip[1] = 'Suspended Forever';
	elseif($act_ip[1] > time()) $act_ip[1] = round(($act_ip[1] - time()) / 60).' Minutes';
	else $act_ip[1] = 'Finished';
	if($act_ip[2] == -1) $act_ip[2] = "All Boards";
	else $act_ip[2] = get_board_name($act_ip[2]);
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
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_ip[0]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=number_format($act_ip[1]/60, 1, '.', '');?> Hours (<?=$act_ip[1]?>)</span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$act_ip[2]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><? if($user_data['status'] != 1) echo "Must be an Administrator to delete";
		else echo "<a href=\"administrator_suspend.php?method=kill&amp;id=$act_ip[3]\">Delete</a>"?>
		</span></td>
	</tr>
<?
	}
	echo "</table>
</object><br />
";
	}

	if($method == "kill") {
	$ips = myfile("objects/ip_suspensions.txt");
	for($i = 0; $i < sizeof($ips); $i++) {
	$act_ip = myexplode($ips[$i]);
	if($id == $act_ip[3]) {
	$save = 1; $ips[$i] = ""; break;
	}
	}

	if($save == 1) {
	myfwrite("objects/ip_suspensions.txt",$ips,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: IP Suspension Deleted [IP: %2]");
	}
	header("Location: administrator_suspend.php");
	exit;
	}
	else echo "An error has occurred.";
	}

	if($method == "new") {
	$displaypage = 1;
	if($create == "yes") {
	if(!myfile_exists("boards/$checkgoal.topics.txt") && $checkgoal != -1) {
	$error = "That board does not exist.";
	}
	if($ip == "") {
	$error = "You must enter an IP address.";
	}
	else {
	$ip = $ip;
	$displaypage = 0;
	$last_id = myfile("objects/ip_suspensions.txt"); $last_id = myexplode($last_id[sizeof($last_id) - 1]); $last_id = $last_id[3]+1;

	if($checktime != -1) $checktime = time() + ($checktime * 60);

	$towrite = "$ip\t$checktime\t$checkgoal\t$last_id\t\r\n";
	myfwrite("objects/ip_suspensions.txt",$towrite,"a");

	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: IP Suspension Added ($ip - $checkgoal - $checktime) [IP: %2]");
	}
	header("Location: administrator_suspend.php");
	exit;
	}
	}

	if($displaypage == 1) {
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_suspend.php\">IP Suspensions</a>\tAdd IP Suspension");
?>

<form action="administrator_suspend.php" method="post"><input name="method" type="hidden" value="new" /><input name="create" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">IP Suspensions</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Add IP Suspension</span></td>
	</tr>
<?
	if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"2\"><span class=\"heading\">Error: $error</span></td>
	</tr>
"; ?>
	<tr>
		<td class="one" style="width: 20"><span class="normal"><strong>IP Address</strong></span></td>
		<td class="one" style="width: 80%"><input class="textbox" maxlength="20" name="ip" type="text" value="<? if(get_post_ip($board,$thread,$post_id) != "") echo sprintf(get_post_ip($board,$thread,$post_id),"<a href=\"administrator_suspend.php?method=new&amp;board=$board&amp;thread=$thread&amp;post_id=$post_id\">",'</a>',"<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">",'</a>'); else echo ""; ?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Suspension Duration</strong></span></td>
		<td class="two"><select class="textbox" name="checktime" size="1"><option value="30">30 minutes</option><option value="60">1 hour</option><option value="120">2 hours</option><option value="360">6 hours</option><option value="720">12 hours</option><option value="1440" selected="selected">1 day</option><option value="2880">2 days</option><option value="4320">3 days</option><option value="5760">4 days</option><option value="7200">5 days</option><option value="8640">6 days</option><option value="10080">1 week</option><option value="20160">2 weeks</option><option value="30240">3 weeks</option><option value="40320">1 month</option><option value="-1">Forever</option></select></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Suspend From</strong></span></td>
		<td class="one"><select class="textbox" name="checkgoal" size="1"><option value="-1">All Boards</option><?
	$boards = myfile("objects/boards.txt");
	for($i = 0; $i < sizeof($boards); $i++) {
	$act_board = myexplode($boards[$i]);
	echo "<option value=\"$act_board[0]\">$act_board[1]</option>";
	}
?>
		</select></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Add IP Suspension" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}

	include("board_bottom.php");

	}
?>