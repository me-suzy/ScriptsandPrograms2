<?
	/*
	Silentum Boards v1.4.3
	administrator_announcement.php copyright 2005 "HyperSilence"
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

	if($save != "yes") {
	$announcement_file = myfile("objects/announcement.txt"); $announcement_config = myexplode($announcement_file[0]);
	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tAnnouncement");
?>

<form action="administrator_announcement.php?save=yes" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Announcement</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">Current Announcement</span></td>
	</tr>
<?
	if($announcement_file[0] == "") echo "	<tr>
		<td class=\"one\" colspan=\"2\" style=\"text-align: center\"><span class=\"normal\"><br /><strong>There is no announcement.</strong><br /><br /></span></td>
	</tr>";
	elseif($announcement_config[0] == 1) echo "	<tr>
		<td class=\"one\" colspan=\"2\"><span class=\"normal\">$announcement_file[1]
		</span></td>
	</tr>";
?>

	<tr>
		<td class="heading2" colspan="2"><span class="heading">Display Announcement</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><input class="textbox" name="type" type="hidden" value="1" /><select class="textbox" name="displaytime"><option selected="selected" value="-1">Always</option><option value="720">12 hours</option><option value="1440">1 day</option><option value="2880">2 days</option><option value="4320">3 days</option><option value="5760">4 days</option><option value="7200">5 days</option><option value="8640">6 days</option><option value="10080">1 week</option><option value="20160">2 weeks</option><option value="30240">3 weeks</option><option value="44640">1 month</option><option value="89280">2 months</option><option value="133920">3 months</option></select></td>
	</tr>
	<tr>
		<td class="heading2" colspan="2"><span class="heading">New Announcement</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left"><textarea class="textbox" cols="85" name="announcement" rows="10"><?=$announcement_file[1]?></textarea><br /><span class="normal"> Use enters or <strong>&lt;br /&gt;</strong> as line breaks<br /> All HTML <strong>Enabled</strong></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Edit Announcement" /></td>
	</tr>
</table>
</object>
</form>
<?
	}

	else {
	if($announcement == "") $towrite = "";
	else {
	if($displaytime != -1) $displaytime = time()+60*$displaytime;
	if($type == 1) {
	$towrite = "$type\t$displaytime\t\r\n".nlbr(trim(mysslashes($announcement)))."\r";
	}
	}
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Announcement Updated [IP: %2]");
	}
	myfwrite("objects/announcement.txt",$towrite,"w"); header("Location: administrator_announcement.php");
	exit;
	}
	}

	include("board_bottom.php");
?>