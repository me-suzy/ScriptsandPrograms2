<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
session_start();
$USER = unp_getUser();
unp_getsettings();

isset($_GET['action']) ? $action = $_GET['action'] : $action = '';

// +------------------------------------------------------------------+
// | Check Logged In User Has Permission To Access This               |
// +------------------------------------------------------------------+
if ($USER['groupid'] != 1)
{
	// Permission denied - only administrators should be able to access this
	unp_msgBox($gp_permserror);
	exit;
}
// +------------------------------------------------------------------+
// | Process Settings Submission                                      |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_POST['submitsettings']))
	{
		$value1 = addslashes($_POST['1']);
		$value2 = addslashes($_POST['2']);
		$value4 = addslashes($_POST['4']);
		$value5 = addslashes($_POST['5']);
		$value6 = addslashes($_POST['6']);
		$value7 = addslashes($_POST['7']);
		$value8 = addslashes($_POST['8']);
		$value9 = addslashes($_POST['9']);
		$value10 = addslashes($_POST['10']);
		$value11 = addslashes($_POST['11']);
		$value12 = addslashes($_POST['12']);
		$value13 = addslashes($_POST['13']);
		$value14 = addslashes($_POST['14']);
		$value15 = addslashes($_POST['15']);
		$value16 = addslashes($_POST['16']);
		$value17 = addslashes($_POST['17']);
		$value18 = addslashes($_POST['18']);
		// VALIDATION
		if (unp_isempty($value1))
		{
			unp_msgBox('You have entered an invalid site title.');
			exit;
		}
		if (strlen($value2) < 10)
		{
			unp_msgBox('You have entered an invalid site URL.');
			exit;
		}
		if (!preg_match('/^[\d]+$/', $value5))
		{
			unp_msgBox('You have entered an invalid news display number.');
			exit;
		}
		if ($value5 < 1)
		{
			unp_msgBox('You have entered an invalid news display number.');
			exit;
		}
		if (!eregi('^[-_./a-zA-Z0-9!&%#?+,\'=:~]+$', $value9))
		{
			unp_msgBox('You have entered an invalid UNP URL.');
			exit;
		}
		if (!preg_match('/^[\d]+$/', $value13))
		{
			unp_msgBox('You have entered an invalid headlines display number.');
			exit;
		}
		if ($value13 < 1)
		{
			unp_msgBox('You have entered an invalid headlines display number.');
			exit;
		}
		if (!eregi('^(\+|-){0,1}[0-9]{1,2}(\.[0-9]){0,1}$', $value15))
		{
			unp_msgBox('You have entered an invalid time zone.');
			exit;
		}
		if (!preg_match('/^[\d]+$/', $value17))
		{
			unp_msgBox('You have entered an invalid avatar dimension.');
			exit;
		}
		if (!preg_match('#^/[a-zA-Z0-9./]+/$#', $value18))
		{
			unp_msgBox('You have entered an invalid UNP directory.');
			exit;
		}
		// VALIDATION

		// UPDATE SETTINGS QUERIES
		$DB->query("UPDATE `unp_setting` SET value='$value1' WHERE id='1'");
		$DB->query("UPDATE `unp_setting` SET value='$value2' WHERE id='2'");
		$DB->query("UPDATE `unp_setting` SET value='$value4' WHERE id='4'");
		$DB->query("UPDATE `unp_setting` SET value='$value5' WHERE id='5'");
		$DB->query("UPDATE `unp_setting` SET value='$value6' WHERE id='6'");
		$DB->query("UPDATE `unp_setting` SET value='$value7' WHERE id='7'");
		$DB->query("UPDATE `unp_setting` SET value='$value8' WHERE id='8'");
		$DB->query("UPDATE `unp_setting` SET value='$value9' WHERE id='9'");
		$DB->query("UPDATE `unp_setting` SET value='$value10' WHERE id='10'");
		$DB->query("UPDATE `unp_setting` SET value='$value11' WHERE id='11'");
		$DB->query("UPDATE `unp_setting` SET value='$value12' WHERE id='12'");
		$DB->query("UPDATE `unp_setting` SET value='$value13' WHERE id='13'");
		$DB->query("UPDATE `unp_setting` SET value='$value14' WHERE id='14'");
		$DB->query("UPDATE `unp_setting` SET value='$value15' WHERE id='15'");
		$DB->query("UPDATE `unp_setting` SET value='$value16' WHERE id='16'");
		$DB->query("UPDATE `unp_setting` SET value='$value17' WHERE id='17'");
		$DB->query("UPDATE `unp_setting` SET value='$value18' WHERE id='18'");
		
		unp_autoBuildCache();
		unp_redirect('settings.php?action=edit','Settings successfully updated!<br />You will now be taken back to the settings page.');
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
}

// +------------------------------------------------------------------+
// | Process Settings Page Content                                    |
// +------------------------------------------------------------------+
if ($action == 'edit')
{
	include('header.php');
	unp_openbox();
	echo '
	<form action="settings.php" method="post">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td width="60%"><strong>Edit Settings</strong>&nbsp;';
		unp_faqLink(4);
		echo '
		</td>
		<td width="40%">&nbsp;</td>
		</tr>
	';
	$getsettings = $DB->query("SELECT * FROM `unp_setting` ORDER BY `display` ASC");
	while($settings = $DB->fetch_array($getsettings))
	{
		$id = $settings['id'];
		$title = $settings['title'];
		$varname = $settings['varname'];
		$settingvalue = $settings['value'];
		$description = $settings['description'];
		$optioncode = $settings['optioncode'];
		if ($optioncode != 'timezone')
		{
			echo '
			<tr>
				<td width="60%">'.$title.'<br />
				<span class="smallfont">'.$description.'</span></td>
				<td width="40%">';
		}
		// start generate HTML for option types
		if ($optioncode == 'text')
		{
			echo '<input type="text" value="'.$settingvalue.'" name="'.$id.'" size="35" />';
		}
		elseif ($optioncode == 'yesno')
		{
			if ($settingvalue == '0')
			{
				echo '<input type="radio" name="'.$id.'" value="1" />Yes <input type="radio" name="'.$id.'" checked="checked" value="0" />No';
			}
			else
			{
				echo '<input type="radio" name="'.$id.'" checked="checked" value="1" />Yes <input type="radio" name="'.$id.'" value="0" />No';
			}
		}
		elseif ($optioncode == 'textarea')
		{
			echo '<textarea name="'.$id.'" cols="10" rows="3">'.$settingvalue.'</textarea>';
		}
		elseif ($optioncode == 'timezone')
		{
			echo '
			<tr>
				<td width="100%" colspan="2">'.$title.'<br />
				<span class="smallfont">'.$description.'</span><br />';
			echo '
			<select name="'.$id.'">
				<option value="-12" '.unp_iif($settingvalue=="-12",'selected="selected"','').'>(GMT -12:00 hours) Eniwetok, Kwajalein</option>
				<option value="-11" '.unp_iif($settingvalue=="-11",'selected="selected"','').'>(GMT -11:00 hours) Midway Island, Samoa</option>
				<option value="-10" '.unp_iif($settingvalue=="-10",'selected="selected"','').'>(GMT -10:00 hours) Hawaii</option>
				<option value="-9" '.unp_iif($settingvalue=="-9",'selected="selected"','').'>(GMT -9:00 hours) Alaska</option>
				<option value="-8" '.unp_iif($settingvalue=="-8",'selected="selected"','').'>(GMT -8:00 hours) Pacific Time (US &amp; Canada)</option>

				<option value="-7" '.unp_iif($settingvalue=="-7",'selected="selected"','').'>(GMT -7:00 hours) Mountain Time (US &amp; Canada)</option>
				<option value="-6" '.unp_iif($settingvalue=="-6",'selected="selected"','').'>(GMT -6:00 hours) Central Time (US &amp; Canada), Mexico City</option>
				<option value="-5" '.unp_iif($settingvalue=="-5",'selected="selected"','').'>(GMT -5:00 hours) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>
				<option value="-4" '.unp_iif($settingvalue=="-4",'selected="selected"','').'>(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz</option>
				<option value="-3.5" '.unp_iif($settingvalue=="-3.5",'selected="selected"','').'>(GMT -3:30 hours) Newfoundland</option>

				<option value="-3" '.unp_iif($settingvalue=="-3",'selected="selected"','').'>(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown</option>
				<option value="-2" '.unp_iif($settingvalue=="-2",'selected="selected"','').'>(GMT -2:00 hours) Mid-Atlantic</option>
				<option value="-1" '.unp_iif($settingvalue=="-1",'selected="selected"','').'>(GMT -1:00 hours) Azores, Cape Verde Islands</option>
				<option value="0" '.unp_iif($settingvalue=="0",'selected="selected"','').'>(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia</option>
				<option value="+1" '.unp_iif($settingvalue=="+1",'selected="selected"','').'>(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris</option>

				<option value="+2" '.unp_iif($settingvalue=="+2",'selected="selected"','').'>(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa</option>
				<option value="+3" '.unp_iif($settingvalue=="+3",'selected="selected"','').'>(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg, Volgograd, Nairobi</option>
				<option value="+3.5" '.unp_iif($settingvalue=="+3.5",'selected="selected"','').'>(GMT +3:30 hours) Tehran</option>
				<option value="+4" '.unp_iif($settingvalue=="+4",'selected="selected"','').'>(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi</option>
				<option value="+4.5" '.unp_iif($settingvalue=="+4.5",'selected="selected"','').'>(GMT +4:30 hours) Kabul</option>

				<option value="+5" '.unp_iif($settingvalue=="+5",'selected="selected"','').'>(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
				<option value="+5.5" '.unp_iif($settingvalue=="+5.5",'selected="selected"','').'>(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi</option>
				<option value="+6" '.unp_iif($settingvalue=="+6",'selected="selected"','').'>(GMT +6:00 hours) Almaty, Dhaka, Colombo</option>
				<option value="+7" '.unp_iif($settingvalue=="+7",'selected="selected"','').'>(GMT +7:00 hours) Bangkok, Hanoi, Jakarta</option>
				<option value="+8" '.unp_iif($settingvalue=="+8",'selected="selected"','').'>(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei</option>

				<option value="+9" '.unp_iif($settingvalue=="+9",'selected="selected"','').'>(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
				<option value="+9.5" '.unp_iif($settingvalue=="+9.5",'selected="selected"','').'>(GMT +9:30 hours) Adelaide, Darwin</option>
				<option value="+10" '.unp_iif($settingvalue=="+10",'selected="selected"','').'>(GMT +10:00 hours) EAST(East Australian Standard), Guam, Papua New Guinea, Vladivostok</option>
				<option value="+11" '.unp_iif($settingvalue=="+11",'selected="selected"','').'>(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia</option>
				<option value="+12" '.unp_iif($settingvalue=="+12",'selected="selected"','').'>(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island</option>
			</select>
			';
		}
		else
		{
			echo $optioncode;
		}
		// end generate HTML for option types
		echo '</td></tr>';
		echo '<tr><td><hr /></td><td><hr /></td></tr>'; // create a horizontal rule between options
	}
	echo '
	</table>
	<table border="0" cellpadding="0" cellspacing="0" align="center" valign="top">
	<tr>
		<td width="100%">
		<center><input type="submit" name="submitsettings" value="Submit Settings" accesskey="s" /> <input type="reset" value="Reset Settings" /></center>
		</td>
	</tr>
	</table></form>';
	unset($settings); // drop potentially large variable from memory before continuing
	unp_closebox();
	include('footer.php');
}
?>