<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: option.php,v $
// | $Date: 2002/11/06 20:37:52 $
// | $Revision: 1.15 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Options');

// ############################################################################
// Set the default do
if (!isset($do)) {
	$do = 'modify';
}

// ############################################################################
// Update options
if ($_POST['do'] == 'update') {
	foreach ($options as $settingid => $value) {
		$DB_site->query("
			UPDATE setting
			SET value = '".addslashes($value)."'
			WHERE settingid = $settingid
		");
	}

	cp_redirect('The options were successfully updated.', 'option.php');
}

// ############################################################################
// Display settings
if ($do == 'modify') {
	$getgroups = $DB_site->query('
		SELECT *
		FROM settinggroup
		WHERE display <> 0
		ORDER BY display
	');

	starttable();
	while ($group = $DB_site->fetch_array($getgroups)) {
		tablehead(array("[<a href=\"option.php#group$group[settinggroupid]\" class=\"navlink\"><span class=\"theadlink\">$group[title]</span></a>]"));
		textrow($group['description']);
	}
	endtable();
	echo '<br /><br />';

	$lastgroupid = -1;
	$settings = $DB_site->query('
		SELECT setting.*, settinggroup.title AS grouptitle
		FROM setting
		LEFT JOIN settinggroup USING (settinggroupid)
		ORDER BY settinggroup.display, setting.display
	');

	startform('option.php', 'update');
	while ($setting = $DB_site->fetch_array($settings)) {
		if ($lastgroupid != $setting['settinggroupid']) {
			if ($lastgroupid != -1) {
				endtable();
				echo '<br /><br />';
			}
			echo '<a name="group'.$setting['settinggroupid'].'"></a>';
			starttable($setting['grouptitle']);
		}
		switch (substr($setting['type'], 0, 4)) {
			case 'text':
				inputfield("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[settingid]]", $setting['value']);
				break;
			case 'yesn':
				yesno("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[settingid]]", $setting['value']);
				break;
			case 'sele':
				$tablename = substr($setting['type'], 7);
				tableselect("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[settingid]]", $tablename, $setting['value']);
				break;
			case 'note':
				textrow($setting['value'], 2, true);
				break;
			case 'time':
				$tzsel = array(iif(getop('timeoffset') >= 0, getop('timeoffset') * 10, 'n'.abs(getop('timeoffset') * 10)) => 'selected="selected"');
				$tztime = array();
				$fieldname = "options[$setting[settingid]]";
				eval(makeeval('timezone', 'options_timezone'));
				tablerow(array("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", $timezone), true);
				break;
		}
		$lastgroupid = $setting['settinggroupid'];
	}
	endtable();
	echo '<br /><br />';

	starttable();
	endform('Update Options');
	endtable();
}

cp_footer();
?>