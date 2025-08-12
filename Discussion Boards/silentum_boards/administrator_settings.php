<?
	/*
	Silentum Boards v1.4.3
	administrator_settings.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");
	administrator();

	if($user_logged_in != 1 || $user_data['id'] != 1) {
	record("2","%1: Control Panel Access Attempt [IP: %2]");
	header("Location: index.php");
	exit;
	}
	else {
	switch($method) {
	default:
	if(isset($save)) {
	$settings[0] = mutate($settings[0]);
	$settings[22] = nlbr(mysslashes($settings[22]));
	$settings[26] = mutate($settings[26]);
	$settings[27] = mutate($settings[27]);
	$settings[28] = mutate($settings[28]);
	$settings[29] = mutate($settings[29]);
	$settings[30] = mutate($settings[30]);
	$settings[31] = mutate($settings[31]);
	for($i = 56;$i<42;$i++){
	$settings[$i] = "0";
	}
	if(!isset($settings[23])) $settings[23] = "";
	else $settings[23] = implode(',',$settings[23]);
	ksort($settings);
	$settings = implode("\n",array_pad($settings,5,''))."\n";
	myfwrite("objects/settings.txt",$settings,'w');
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("2","%1: Settings Edited [IP: %2]");
	}

	include("board_top.php");
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\t<a href=\"administrator_settings.php\">Edit Settings</a>\t".$txt['Navigation']['Settings_Saved'][0]);
	echo get_message('Settings_Saved');
	}
	else {
	$logging = explode(',',$config['record_options']);
	include('board_top.php');
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tEdit Settings");
?>

<form action="administrator_settings.php?method=editsettings" method="post"><input name="save" type="hidden" value="1" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4"><span class="heading">Edit Settings</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading">General Settings</span></td>
	</tr>
	<tr>
		<td class="heading3" colspan="4"><span class="heading">Board Settings</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%"><span class="normal"><strong>Board Name</strong></span></td>
		<td class="one" colspan="3" style="width: 80%" valign="top"><input class="textbox" maxlength="100" name="settings[0]" size="30" type="text" value="<?=$config['board_name']?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Board URL</strong></span></td>
		<td class="two" colspan="3" valign="top"><input class="textbox" maxlength="100" name="settings[1]" size="50" type="text" value="<?=$config['board_url']?>" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Default Time Zone</strong></span></td>
		<td class="one" colspan="3" valign="top"><select class="textbox" name="settings[2]"><?
	for($i = 0; $i < sizeof($txt['Time_Zone']); $i++) {
	if($config['default_timezone'] == $txt['Time_Zone'][$i][1]) $x = " selected";
	else $x = "";
	echo "<option value=\"".$txt['Time_Zone'][$i][1]."\"$x>".$txt['Time_Zone'][$i][0]."</option>";
	}
?>
		</select></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Default Directory</strong></span></td>
		<td class="two" colspan="3" valign="top"><input class="textbox" maxlength="30" name="settings[3]" size="30" type="text" value="<?=$config['default_directory']?>" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Default Stylesheet</strong></span></td>
		<td class="one" colspan="3" valign="top"><input class="textbox" maxlength="30" name="settings[4]" size="30" type="text" value="<?=$config['default_stylesheet']?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Webmaster's E-mail Address</strong></span></td>
		<td class="two" colspan="3" valign="top"><input class="textbox" maxlength="150" name="settings[5]" size="30" type="text" value="<?=$config['webmasters_email_address']?>" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Table Cellspacing</strong></span></td>
		<td class="one" colspan="3" valign="top"><input class="textbox" size="3" type="text" maxlength="2" name="settings[6]" value="<?=$cellspacing?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Table Width</strong></span></td>
		<td class="two" colspan="3" valign="top"><input class="textbox" size="5" type="text" maxlength="4" name="settings[7]" value="<?=$twidth_old?>" /></td>
	</tr>
	<tr>
		<td class="heading3" colspan="4"><span class="heading">Board Options</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 30%"><span class="normal"><strong>Show Online Users</strong></span></td>
		<td class="one" style="width: 20%" valign="top"><select class="textbox" name="settings[8]"><option value="1"<? if($config['show_online_users'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['show_online_users'] != 1) echo " selected"; ?>>No</option></select></td>
		<td class="one" style="width: 30%"><span class="normal"><strong>Guests Must Enter A Name To Post</strong></span></td>
		<td class="one" style="width: 20%" valign="top"><select class="textbox" name="settings[9]"><option value="1"<? if($config['guests_must_enter_a_name'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['guests_must_enter_a_name'] != 1) echo " selected"; ?>>No</option></select></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Online Users Timeout</strong></span></td>
		<td class="two" valign="top"><input class="textbox" size="3" type="text" maxlength="3" name="settings[10]" value="<?=$config['online_users_timeout']?>" /><span class="normal"> Minutes</span></td>
		<td class="two"><span class="normal"><strong>Must Be Logged In To Access Boards</strong></span></td>
		<td class="two" valign="top"><select class="textbox" name="settings[11]"><option value="1"<? if($config['must_be_logged_in'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['must_be_logged_in'] != 1) echo " selected"; ?>>No</option></select></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Enable Categories</strong></span></td>
		<td class="one" valign="top"><select class="textbox" name="settings[12]"><option value="1"<? if($config['enable_categories'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['enable_categories'] != 1) echo " selected"; ?>>No</option></select></td>
		<td class="one"><span class="normal"><strong>Show Page Execution Time</strong></span></td>
		<td class="one" valign="top"><select class="textbox" name="settings[13]"><option value="1"<? if($config['show_page_execution_time'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['show_page_execution_time'] != 1) echo " selected"; ?>>No</option></select></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Enable Censor</strong></span></td>
		<td class="two" valign="top"><select class="textbox" name="settings[14]"><option value="1"<? if($config['enable_censor'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['enable_censor'] != 1) echo " selected"; ?>>No</option></select></td>
		<td class="two"><span class="normal"><strong>Announcement Position</strong></span></td>
		<td class="two" valign="top"><select class="textbox" name="settings[15]"><option value="1"<? if($config['announcement_position'] == 1) echo " selected"; ?>>Top</option><option value="2"<? if($config['announcement_position'] == 2) echo " selected"; ?>>Bottom</option></select></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Enable Search</strong></span></td>
		<td class="one" valign="top"><select class="textbox" name="settings[16]"><option value="1"<? if($config['enable_search'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['enable_search'] != 1) echo " selected"; ?>>No</option></select></td>
		<td class="one"><span class="normal"><strong>Topics Per Page</strong></span></td>
		<td class="one" valign="top"><input class="textbox" size="3" type="text" maxlength="3" name="settings[17]" value="<?=$config['topics_per_page']?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Enable Top 10 Users</strong></span></td>
		<td class="two" valign="top"><select class="textbox" name="settings[18]"><option value="1"<? if($config['enable_top_10'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['enable_top_10'] != 1) echo " selected"; ?>>No</option></select></td>
		<td class="two"><span class="normal"><strong>Posts Per Page</strong></span></td>
		<td class="two" valign="top"><input class="textbox" size="3" type="text" maxlength="3" name="settings[19]" value="<?=$config['posts_per_page']?>" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Show Private Boards</strong></span></td>
		<td class="one" valign="top"><select class="textbox" name="settings[20]"><option value="1"<? if($config['show_private_boards'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['show_private_boards'] != 1) echo " selected"; ?>>No</option></select></td>
		<td class="one"></td>
		<td class="one" valign="top"></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading">Offline Mode Settings</span></td>
	</tr>
	<tr>
		<td class="two" colspan="4"><span class="normal">When Offline Mode is turned on, only Administrators will be able to access the boards. All other users will receive the offline mode message.</span></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Offline Mode</strong></span></td>
		<td class="one" colspan="3" valign="top"><select class="textbox" name="settings[21]"><option value="1"<? if($config['offline'] == 1) echo " selected"; ?>>On</option><option value="0"<? if($config['offline'] != 1) echo " selected"; ?>>Off</option></select></td>
	</tr>
	<tr>
		<td class="two" valign="top"><span class="normal"><strong>Offline Mode Message</strong></span><br /><span class="normal">All HTML <strong>Enabled</strong></span></td>
		<td class="two" colspan="3" valign="top"><textarea class="textbox" name="settings[22]" cols="35" rows="4"><?=brnl($config['offline_message'])?></textarea></td>
	</tr>	
	<tr>
		<td class="heading2" colspan="4"><span class="heading">Record Settings</span></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>FAQs/Search/Top 10 Users/TOS Views</strong></span></td>
		<td class="one"><span class="normal"><select class="textbox" name="settings[23][0]" size="1"><option value="">Don't Record</option><option <? if(in_array(1,$logging)) echo "selected=\"selected\" " ?>value="1">Record</option></select></span></td>
		<td class="one"><span class="normal"><strong>Administrator CP Access Attempts</strong></span></td>
		<td class="one"><span class="normal"><select class="textbox" name="settings[23][1]" size="1"><option value="">Don't Record</option><option <? if(in_array(2,$logging)) echo "selected=\"selected\" " ?>value="2">Record</option></select></span></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Failed Logins</strong></span></td>
		<td class="two"><span class="normal"><select class="textbox" name="settings[23][2]" size="1"><option value="">Don't Record</option><option <? if(in_array(3,$logging)) echo "selected=\"selected\" " ?>value="3">Record</option></select></span></td>
		<td class="two"><span class="normal"><strong>Polls/Topics/Replies Posted</strong></span></td>
		<td class="two"><span class="normal"><select class="textbox" name="settings[23][3]" size="1"><option value="">Don't Record</option><option <? if(in_array(4,$logging)) echo "selected=\"selected\" " ?>value="4">Record</option></select></span></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Moderator Activity</strong></span></td>
		<td class="one"><span class="normal"><select class="textbox" name="settings[23][4]" size="1"><option value="">Don't Record</option><option <? if(in_array(5,$logging)) echo "selected=\"selected\" " ?>value="5">Record</option></select></span></td>
		<td class="one"><span class="normal"><strong>Users Connected</strong></span></td>
		<td class="one"><span class="normal"><select class="textbox" name="settings[23][5]" size="1"><option value="">Don't Record</option><option <? if(in_array(6,$logging)) echo "selected=\"selected\" " ?>value="6">Record</option></select></span></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Logins/Logouts</strong></span></td>
		<td class="two"><span class="normal"><select class="textbox" name="settings[23][6]" size="1"><option value="">Don't Record</option><option <? if(in_array(7,$logging)) echo "selected=\"selected\" " ?>value="7">Record</option></select></span></td>
		<td class="two"><span class="normal"><strong>Administrator Activity</strong></span></td>
		<td class="two"><span class="normal"><select class="textbox" name="settings[23][7]" size="1"><option value="">Don't Record</option><option <? if(in_array(8,$logging)) echo "selected=\"selected\" " ?>value="8">Record</option></select></span></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Updated User Preferences</strong></span></td>
		<td class="one"><span class="normal"><select class="textbox" name="settings[23][8]" size="1"><option value="">Don't Record</option><option <? if(in_array(9,$logging)) echo "selected=\"selected\" " ?>value="9">Record</option></select></span></td>
		<td class="one"><span class="normal"><strong>New Registrations</strong></span></td>
		<td class="one"><span class="normal"><select class="textbox" name="settings[23][9]" size="1"><option value="">Don't Record</option><option <? if(in_array(10,$logging)) echo "selected=\"selected\" " ?>value="10">Record</option></select></span></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Posts Reported</strong></span></td>
		<td class="two"><span class="normal"><select class="textbox" name="settings[23][10]" size="1"><option value="">Don't Record</option><option <? if(in_array(11,$logging)) echo "selected=\"selected\" " ?>value="11">Record</option></select></span></td>
		<td class="two"><span class="normal"><strong>Notes Deleted</strong></span></td>
		<td class="two"><span class="normal"><select class="textbox" name="settings[23][11]" size="1"><option value="">Don't Record</option><option <? if(in_array(12,$logging)) echo "selected=\"selected\" " ?>value="12">Record</option></select></span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading">Registration Settings</span></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Enable Registration</strong></span></td>
		<td class="one" colspan="3" valign="top"><select class="textbox" name="settings[24]"><option value="1"<? if($config['enable_registration'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['enable_registration'] != 1) echo " selected"; ?>>No</option></select></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Registration Limit</strong></span></td>
		<td class="two" colspan="3" valign="top"><input class="textbox" size="8" type="text" maxlength="8" name="settings[25]" value="<?=$config['max_registrations']?>" /> <span class="normal">(Enter -1 for unlimited registrations)</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading">Status Settings</span></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Status for Hosts</strong></span></td>
		<td class="one" valign="top"><input class="textbox" type="text" maxlength="30" name="settings[26]" value="<?=$config['status_host']?>" /></td>
		<td class="one"><span class="normal"><strong>Status for Closed Accounts</strong></span></td>
		<td class="one" valign="top"><input class="textbox" type="text" maxlength="30" name="settings[27]" value="<?=$config['status_closed']?>" /></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Status for Administrators</strong></span></td>
		<td class="two" valign="top"><input class="textbox" type="text" maxlength="30" name="settings[28]" value="<?=$config['status_administrator']?>" /></td>
		<td class="two"><span class="normal"><strong>Status for Suspended Users</strong></span></td>
		<td class="two" valign="top"><input class="textbox" type="text" maxlength="30" name="settings[29]" value="<?=$config['status_suspended']?>" /></td>
	</tr>
	<tr>
		<td class="one"><span class="normal"><strong>Status for Moderators</strong></span></td>
		<td class="one" valign="top"><input class="textbox" type="text" maxlength="30" name="settings[30]" value="<?=$config['status_moderator']?>" /></td>
		<td class="one"><span class="normal"><strong>Status for Banned Users</strong></span></td>
		<td class="one" valign="top"><input class="textbox" type="text" maxlength="30" name="settings[31]" value="<?=$config['status_banned']?>" /></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading">Cache Settings</span></td>
	</tr>
	<tr>
		<td class="two"><span class="normal"><strong>Enable File Caching</strong></span></td>
		<td class="two" valign="top"><select class="textbox" name="settings[32]"><option value="1"<? if($config['use_file_caching'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['use_file_caching'] != 1) echo " selected"; ?>>No</option></select></td>
		<td class="two"><span class="normal"><strong>Enable Output Caching</strong></span></td>
		<td class="two" valign="top"><select class="textbox" name="settings[33]"><option value="1"<? if($config['use_output_caching'] == 1) echo " selected"; ?>>Yes</option><option value="0"<? if($config['use_output_caching'] != 1) echo " selected"; ?>>No</option></select></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Save Settings" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	break;
	}
	}

	include("board_bottom.php");
?>