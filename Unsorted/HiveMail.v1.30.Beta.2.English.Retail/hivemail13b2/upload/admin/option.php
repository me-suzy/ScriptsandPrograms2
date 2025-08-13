<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: option.php,v $ - $Revision: 1.26 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
if ($cmd == 'gateway' or $_POST['cmd'] == 'updatetype') {
	cp_header(' &raquo; Email Gateway');
	cp_nav('emailgateway');
} else {
	cp_header(' &raquo; Program Options');
	cp_nav('hiveop');
}

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

// ############################################################################
// Update options
if ($_POST['cmd'] == 'updatevalues') {
	foreach ($options as $varname => $value) {
		if ($value != getop($varname)) {
			$updated_options .= ",$varname";
			$DB_site->query("
				UPDATE hive_setting
				SET value = '".addslashes($value)."'
				WHERE variable = '".addslashes($varname)."'
			");
		}
	}

	adminlog(0, true, 'updatevalues', 'Updated options: '.substr($updated_options, 1));
	cp_redirect('The options were successfully updated.', 'option.php?'.iif($gateway, 'cmd=gateway'));
}

// ############################################################################
// Display settings
if ($cmd == 'modify') {
	adminlog();

	$getgroups = $DB_site->query('
		SELECT *
		FROM hive_settinggroup
		'.iif(!defined('HIVE_DEV') or HIVE_DEV != true, 'WHERE display > 0').'
		ORDER BY display
	');

	starttable('HiveMail Options', '350');
	$i = 1;
	while ($group = $DB_site->fetch_array($getgroups)) {
		if ($group['description'] != 'hivepop' or HIVEPOP_RUNNING) {
			$labels[] = "$i. <a href=\"option.php#group$group[settinggroupid]\">$group[title]</a>";
			if ($i++%2 == 0) {
				tablerow($labels, true);
				$labels = array();
			}
		}
	}
	if (!empty($labels)) {
		$labels[] = '&nbsp';
		tablerow($labels, true);
	}
	endtable();

	if (defined('HIVE_DEV') and HIVE_DEV == true) {
		echo "<div align=\"center\" style=\"font-weight: bold; font-size: 12px;\"><a href=\"option.php?cmd=edit\">Create new setting</a></div>\n";
	}
	echo '<br /><br />';

	$lastgroupid = -1;
	$settings = $DB_site->query('
		SELECT setting.*, settinggroup.title AS grouptitle
		FROM hive_setting AS setting
		LEFT JOIN hive_settinggroup AS settinggroup USING (settinggroupid)
		'.iif(!defined('HIVE_DEV') or HIVE_DEV != true, 'WHERE settinggroup.display > 0').'
		ORDER BY settinggroup.display, settinggroup.settinggroupid, setting.display
	');

	startform('option.php', 'updatevalues');
	$i = 1;
	while ($setting = $DB_site->fetch_array($settings)) {
		if ($group['description'] == 'hivepop' and !HIVEPOP_RUNNING) {
			continue;
		}
		if ($lastgroupid != $setting['settinggroupid']) {
			if ($lastgroupid != -1) {
				tablehead(array('<input class="button" type="submit" value="  Update Options  " />'), 2);
				endtable();
				echo '<br /><br />';
			}
			echo '<a name="group'.$setting['settinggroupid'].'"></a>';
			starttable($i.'. '.$setting['grouptitle']);
			$i++;
		}
		settingfield($setting);
		$lastgroupid = $setting['settinggroupid'];
	}
	endform('Update Options');
	endtable();
}

// ############################################################################
// Update options
if ($_POST['cmd'] == 'updatetype') {
	$DB_site->query("
		UPDATE hive_setting
		SET value = '".addslashes($type)."'
		WHERE variable = 'gatewaytype'
	");
	$gatewaytype = $_options['gatewaytype'] = $type;

	adminlog(0, true, 'updatetype', 'New gateway: '.$type);
	$cmd = 'gateway';
}

// ############################################################################
// Email gateway options
if ($cmd == 'gateway') {
	adminlog();

	$gatewaytype = getop('gatewaytype');
	startform('option.php', 'updatetype', 'Are you SURE you want to change the type of email gateway?');
	starttable('Switch Email Gateway', '450');
	selectbox('Type of gateway:', 'type', array('pop3' => 'POP3 Gateway', 'pipe' => 'Pipe Gateway'), $gatewaytype);
	endform('Change Gateway');
	endtable();
	echo '<br />';

	$settings = $DB_site->query('
		SELECT setting.*, settinggroup.title AS grouptitle
		FROM hive_setting AS setting
		LEFT JOIN hive_settinggroup AS settinggroup USING (settinggroupid)
		WHERE settinggroup.description = "'.addslashes($gatewaytype).'"
		ORDER BY settinggroup.display, setting.display
	');

	getclass();
	if ($gatewaytype == 'pipe') {
		starttable('Gateway Settings', '450');
		textrow('There are no settings for this type of gateway. For information on installing and conifguring the pipe gateway.');
		endtable();
	} else {
		startform('option.php', 'updatevalues');
		hiddenfield('gateway', '1');
		starttable('Gateway Settings');
		while ($setting = $DB_site->fetch_array($settings)) {
			settingfield($setting);
		}
		endform('Save Changes');
		endtable();
		echo '<div align="center"><b>Please see the installation guide for help regarding these settings.</b></div>';
	}
}

// ############################################################################
// Off limits!
if (!defined('HIVE_DEV') or HIVE_DEV != true) {
	cp_footer();
	exit;
}

// ############################################################################
// Remove setting
if ($_POST['cmd'] == 'kill') {
	$setting = getinfo('setting', $settingid);

	$query = "
		DELETE FROM hive_setting
		WHERE settingid = $settingid
	";
	$DB_site->query($query);

	adminlog($settingid, true);
	cp_error('The setting has been removed. Query:<br /><pre>'.$query.'</pre>', 'option.php');
}

// ############################################################################
// Remove setting
if ($cmd == 'remove') {
	$setting = getinfo('setting', $settingid);

	adminlog($settingid);
	startform('option.php', 'kill', 'Are you sure you want to remove this setting?');
	starttable('Remove setting "'.$setting['title'].'" (ID: '.$settingid.')', '450');
	textrow('Are you <b>sure</b> you want to remove this setting? This procedure <b>cannot</b> be reveresed!');
	hiddenfield('settingid', $settingid);
	endform('Remove Setting', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new setting or update an existing one
if ($_POST['cmd'] == 'update') {
	if ($settingid == 0) {
		$setting['display'] = $DB_site->get_field("SELECT MAX(display) FROM hive_setting WHERE settinggroupid = $setting[settinggroupid]") + 1;
		$query = $DB_site->build_query('setting', $setting);
		$DB_site->query($query);
		adminlog($settingid, true);
		$msg = 'The setting has been created';
	} else {
		$oldsetting = unserialize($oldsetting);
		foreach ($oldsetting as $key => $value) {
			if ($setting["$key"] == $value) {
				unset($setting["$key"]);
			}
		}
		$query = $DB_site->build_query('setting', $setting, "settingid = $settingid");
		$DB_site->query($query);
		adminlog($settingid, true);
		$msg = 'The setting has been updated';
	}
	echo '<br />';
	starttable('', '400');
	textrow('<div align="center"><br />'.$msg.'. Query:<br /><pre>'.$query.'</pre>'.makelink('proceed', 'option.php').'</div>');
	endtable();
}

// ############################################################################
// Create a new setting or update an existing one
if ($cmd == 'edit') {
	startform('option.php', 'update');

	$setting = getinfo('setting', $settingid, false, false);
	adminlog($settingid);
	if ($setting === false) {
		$settingid = 0;
		starttable('Create new setting', '450');
	} else {
		starttable('Update setting "'.$setting['title'].'" (ID: '.$settingid.')', '450');
	}

	hiddenfield('settingid', $settingid);
	hiddenfield('oldsetting', serialize($setting));
	inputfield('Title:', 'setting[title]', $setting['title']);
	tableselect('Setting group:', 'setting[settinggroupid]', 'settinggroup', $setting['settinggroupid']);
	textarea('Description:', 'setting[description]', $setting['description'], 4);
	inputfield('Variable name:', 'setting[variable]', $setting['variable']);
	inputfield('Setting value:', 'setting[value]', $setting['value']);
	inputfield('Option type:', 'setting[type]', $setting['type']);
	if ($settingid != 0) {
		inputfield('Display order:', 'setting[display]', $setting['display'], 4);
	}

	if ($settingid == 0) {
		endform('Create setting');
	} else {
		endform('Update setting');
	}
	endtable();
}

cp_footer();
?>