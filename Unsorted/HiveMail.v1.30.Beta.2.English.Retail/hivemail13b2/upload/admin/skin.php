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
// | $RCSfile: skin.php,v $ - $Revision: 1.38 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
if ($_POST['cmd'] != 'serve') {
	cp_header(' &raquo; Skins');
}

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');
if ($cmd == 'add') {
	$cmd = 'edit';
}

// ############################################################################
// Default skin settings
$defskin = array(
  'doctype' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
  'images' => 'images',
  'body' => 'topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"',
  'fontface' => 'Verdana, sans-serif',
  'normalsize' => '12px',
  'smallsize' => '10px',
  'timecolor' => '#42517A',
  'linkhovercolor' => '#999999',
  'highcolor' => 'red',
  'linkcolor' => '#000020',
  'border_normal_horizonal_width' => '1px',
  'border_normal_vertical_width' => '0px',
  'border_normal_edges_width' => '1px',
  'border_normal_color' => '#E1E1E1',
  'border_normal_style' => 'solid',
  'border_header_horizonal_width' => '1px',
  'border_header_vertical_width' => '0px',
  'border_header_edges_width' => '1px',
  'border_header_color' => '#80B4DB',
  'border_header_style' => 'solid',
  'tableheadfontcolor' => '#447892',
  'tableheadbgcolor' => '#E9F3F8 url(\'images/tableheadbg.gif\')',
  'firstalt' => '#FAFAFA',
  'secondalt' => '#F0F0F0',
  'formbackground' => '#E1E4E5',
  'pagebgcolor' => '#FFFFFF',
  'folder_inbox' => 'Inbox',
  'folder_sentitems' => 'Sent Items',
  'folder_trashcan' => 'Trash Can',
  'folder_junkmail' => 'Junk Mail',
  'cal_sun_long' => 'Sunday',
  'cal_mon_long' => 'Monday',
  'cal_tue_long' => 'Tuesday',
  'cal_wed_long' => 'Wednesday',
  'cal_thu_long' => 'Thursday',
  'cal_fri_long' => 'Friday',
  'cal_sat_long' => 'Saturday',
  'cal_sun_short' => 'S',
  'cal_mon_short' => 'M',
  'cal_tue_short' => 'T',
  'cal_wed_short' => 'W',
  'cal_thu_short' => 'T',
  'cal_fri_short' => 'F',
  'cal_sat_short' => 'S',
  'cal_jan_long' => 'January',
  'cal_feb_long' => 'February',
  'cal_mar_long' => 'March',
  'cal_apr_long' => 'April',
  'cal_may_long' => 'May',
  'cal_jun_long' => 'June',
  'cal_jul_long' => 'July',
  'cal_aug_long' => 'August',
  'cal_sep_long' => 'September',
  'cal_oct_long' => 'October',
  'cal_nov_long' => 'November',
  'cal_dec_long' => 'December',
  'cal_jan_short' => 'Jan',
  'cal_feb_short' => 'Feb',
  'cal_mar_short' => 'Mar',
  'cal_apr_short' => 'Apr',
  'cal_may_short' => 'May',
  'cal_jun_short' => 'Jun',
  'cal_jul_short' => 'Jul',
  'cal_aug_short' => 'Aug',
  'cal_sep_short' => 'Sep',
  'cal_oct_short' => 'Oct',
  'cal_nov_short' => 'Nov',
  'cal_dec_short' => 'Dec',
);

// ############################################################################
// Download skin
if ($_POST['cmd'] == 'serve') {
	$askin = getinfo('skin', $askinid, false, false);
	if ($askin === false) {
		adminlog($askinid, false);
		cp_header();
		cp_nav('skindownup');
		cp_error('Invalid skin specified.');
	}
	eval('$askin[\'vars\'] = '.$askin['vars'].';');
	verify_skin_language($askin['vars']);

	adminlog($askinid, true);
	header('Content-disposition: attachment; filename=hivemail_skin_'.date('m_d_Y').'.skin');
	header('Content-type: unknown/unknown');
	while ($template = $DB_site->fetch_array($gettemplates, "SELECT * FROM hive_template WHERE templatesetid = $askin[templatesetid] ORDER BY title")) {
		$title = $template['title'];
		unset($template['title'], $template['templatesetid'], $template['templateid'], $template[0], $template[1], $template[2], $template[3], $template[4], $template[5], $template[6], $template[7], $template[8]);
		$askin['templates']["$title"] = $template;
	}
	$askin = array('vars' => $askin['vars'], 'templates' => $askin['templates']);
	echo preg_replace("#\r?\n#", "\n", export_array($askin));
	exit;
}

// ############################################################################
// Upload skin
if ($_POST['cmd'] == 'upload') {
	cp_nav('skindownup');
	if (!upload_file($filename, $filedata)) {
		adminlog(0, false);
		cp_error('There has been error while trying to upload the file.<br />Please go back and try again.');
	}

	$filedata = str_replace('\\\\\\\'', '\\\\\'', $filedata);
	$filedata = str_replace('\\\\\'', '\\\\\\\'', $filedata);
	eval('$askin = '.$filedata.';');
	
	$DB_site->query("
		INSERT INTO hive_templateset
		(templatesetid, title)
		VALUES (NULL, '".addslashes($newname)."')
	");
	$templatesetid = $DB_site->insert_id();
	$values = '';
	foreach ($askin['templates'] as $title => $tempinfo) {
		$values .= ",(NULL, $templatesetid, $tempinfo[templategroupid], '".addslashes($title)."', '".addslashes($tempinfo['user_data'])."', '".addslashes($tempinfo['parsed_data'])."', '".addslashes($tempinfo['backup_data'])."')";
	}
	$values = substr($values, 1);
	$DB_site->query("
		INSERT INTO hive_template
		(templateid, templatesetid, templategroupid, title, user_data, parsed_data, backup_data)
		VALUES
		$values
	");
	
	// Create new skin
	$DB_site->query("
		INSERT INTO hive_skin
		(skinid, templatesetid, title, vars)
		VALUES (NULL, $templatesetid, '".addslashes($newname)."', '".addslashes(iif(is_array($askin['vars']), export_array($askin['vars']), $askin['vars']))."')
	");
	$askinid = $DB_site->insert_id();

	// Make it available for all users by default
	$DB_site->query("
		UPDATE hive_usergroup
		SET allowedskins = CONCAT(allowedskins, ',$askinid')
	");

	adminlog($askinid, true);
	cp_redirect('The skin has been created.', "skin.php");
}

// ############################################################################
// Export skin
if ($cmd == 'downup') {
	cp_nav('skindownup');
	adminlog();

	startform('skin.php', 'serve');
	starttable('Export skin');
	textrow('Use this to download a skin file of the select skin and save it to your computer.<br />This file can be later used to import the skin back into the system.');
	tableselect('Skin to download:', 'askinid', 'skin');
	endform('Export Skin');
	endtable();

	startform('skin.php', 'upload', '', array(), true);
	starttable('Import skin');
	textrow('Use this form to upload a skin file and create a new skin based on it.');
	filefield('Skin file:<br /><span class="cp_small">Click Browse and select the backup file from your computer. When you are done, click Open.</span>', 'file');
	inputfield('Name of new skin:<br /><span class="cp_small">The new skin that will be created from this file will have this name.</span>', 'newname');
	endform('Import Skin');
	endtable();
}

// ############################################################################
// Remove skin
if ($_POST['cmd'] == 'kill') {
	cp_nav('skinmodify');
	$askin = getinfo('skin', $askinid);
	if ($askinid == 1) {
		adminlog($askinid, false);
		cp_error('You cannot remove the original skin.');
	}

	$DB_site->query("
		DELETE FROM hive_skin
		WHERE skinid = $askinid
	");
	$DB_site->query("
		UPDATE hive_user
		SET skinid = $newskinid
		WHERE skinid = $askinid
	");

	adminlog($askinid, true, 'kill', "Users will now be using skin $newskinid");
	cp_redirect('The skin has been removed.', 'skin.php');
}

// ############################################################################
// Remove skin
if ($cmd == 'remove') {
	cp_nav('skinmodify');
	$askin = getinfo('skin', $askinid);
	if ($askinid == 1) {
		adminlog($askinid, false);
		cp_error('You cannot remove the original skin.');
	}

	adminlog($askinid);
	startform('skin.php', 'kill', 'Are you sure you want to remove this skin?');
	starttable('Remove skin "'.$askin['title'].'" (ID: '.$askinid.')');
	textrow('Are you <b>sure</b> you want to remove this skin? This procedure <b>cannot</b> be reveresed!');
	tableselect('New skin for users that are using this skin:', 'newskinid', 'skin', -1, "skinid <> $askinid");
	hiddenfield('askinid', $askinid);
	endform('Remove Skin', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new skin or update an existing one
if ($_POST['cmd'] == 'update') {
	cp_nav('skinmodify');
	if (empty($askin['title'])) {
		adminlog($askinid, false);
		cp_error('The skin must have a title.');
	} else {
		$askin['vars'] = export_array($askin['vars']);

		if ($askinid == 0) {
			// Create new template set
			if ($askin['templatesetid'] == -1) {
				$DB_site->auto_query('templateset', array('title' => $askin['title']));
				$askin['templatesetid'] = $DB_site->insert_id();
			}

			// Create the skin
			$DB_site->auto_query('skin', $askin);
			$askinid = $DB_site->insert_id();

			// Make it available for all users by default
			$DB_site->query("
				UPDATE hive_usergroup
				SET allowedskins = CONCAT(allowedskins, ',$askinid')
			");

			adminlog($askinid, true);
			cp_redirect('The skin has been created.', 'skin.php');
		} else {
			$DB_site->auto_query('skin', $askin, "skinid = $askinid");
			adminlog($askinid, true);
			cp_redirect('The skin has been updated.', 'skin.php');
		}
	}
}

// ############################################################################
// Create a new skin or update an existing one
if ($cmd == 'edit') {
	cp_nav('skinmodify');
	startform('skin.php', 'update', '', array('askin_title' => 'title'));

	$askin = getinfo('skin', $askinid, false, false);
	adminlog($askinid);
	if ($askin === false) {
		$askin = array(
			'title' => '',
			'templatesetid' => 'huzaah',
			'vars' => $defskin,
		);
		$askinid = 0;
		starttable('Create new skin');
	} else {
		eval('$askin["vars"] = '.$askin['vars'].';');
		starttable('Update skin "'.$askin['title'].'" (ID: '.$askinid.')');
	}
	verify_skin_language($askin['vars']);

	$vars = array(
		'Settings' => array(
			'doctype' => array('HTML document type:<br /><span class="cp_small">The document type declaration that will be used on all pages.</span>', false),
			'images' => array('Images folder:<br /><span class="cp_small">(Without preceding or following slashes.)</span>', false),
			'body' => array('Extra attributes for the &lt;body&gt; tag:', false),
		),
		'Text and Fonts' => array(
			'note1' => 'This section controls the font, sizes and colors of text that are used in all pages.',
			'fontface' => array('Font face of text everywhere:', false),
			'note2' => 'Sizes:',
			'normalsize' => array('Size of regular text:', false),
			'smallsize' => array('Size of small text:', false),
			'note3' => 'Colors:',
			'tableheadfontcolor' => array('Color of text in table headers:', true),
			'timecolor' => array('Color of times-stamps:', true),
			'linkhovercolor' => array('Color of links when being hovered:', true),
			'highcolor' => array('Color of highlighted text:', true),
			'linkcolor' => array('Color of links:', true),
		),
		'Table Border Options' => array(
			'note1' => 'Normal table border (surrounds normal cells inside the table):',
			'border_normal_horizonal_width' => array('Width of horizontal borders (between rows):', false),
			'border_normal_vertical_width' => array('Width of vertical borders (between cells):', false),
			'border_normal_edges_width' => array('Width of edge borders (leftmost and rightmost):', false),
			'border_normal_color' => array('Color of borders:', true),
			'border_normal_style' => array('Borders style (solid, dotted, etc.):', false),
			'note2' => 'Table header border (surrounds header cells with table titles):',
			'border_header_horizonal_width' => array('Width of horizontal borders (between rows):', false),
			'border_header_vertical_width' => array('Width of vertical borders (between cells):', false),
			'border_header_edges_width' => array('Width of edge borders (leftmost and rightmost):', false),
			'border_header_color' => array('Color of borders:', true),
			'border_header_style' => array('Borders style (solid, dotted, etc.):', false),
		),
		'Table Background Colors' => array(
			'note1' => 'This section controls the background colors of table cells:',
			'tableheadbgcolor' => array('Background color of the table headers:', true),
			'firstalt' => array('First alternating color:', true),
			'secondalt' => array('Second alternating color:', true),
			'note2' => '</b>To use an image in the background, use this syntax: url(\'images/bg.gif\')<br />For more information on this subject please see <a href="http://www.w3schools.com/css/pr_background.asp" target="_blank">this page</a>.<b>',
		),
		'Other Colors' => array(
			'formbackground' => array('Background color of form elements:', true),
			'pagebgcolor' => array('Background color of the page:', true),
			'note2' => '</b>To use an image in the background, use this syntax: url(\'images/bg.gif\')<br />For more information on this subject please see <a href="http://www.w3schools.com/css/pr_background.asp" target="_blank">this page</a>.<b>',
		),
		'Language Options' => array(
			'folder_inbox' => array('Title of the "Inbox" folder:', false, false),
			'folder_sentitems' => array('Title of the "Sent Items" folder:', false, false),
			'folder_trashcan' => array('Title of the "Trash Can" folder:', false, false),
			'folder_junkmail' => array('Title of the "Junk Mail" folder:', false, false),
			'cal_sun_long' => array('Full name of first day of the week:', false, false),
			'cal_mon_long' => array('Full name of first day of the week:', false, false),
			'cal_tue_long' => array('Full name of first day of the week:', false, false),
			'cal_wed_long' => array('Full name of first day of the week:', false, false),
			'cal_thu_long' => array('Full name of first day of the week:', false, false),
			'cal_fri_long' => array('Full name of first day of the week:', false, false),
			'cal_sat_long' => array('Full name of first day of the week:', false, false),
			'cal_sun_short' => array('Short name of first day of the week:', false, false),
			'cal_mon_short' => array('Short name of first day of the week:', false, false),
			'cal_tue_short' => array('Short name of first day of the week:', false, false),
			'cal_wed_short' => array('Short name of first day of the week:', false, false),
			'cal_thu_short' => array('Short name of first day of the week:', false, false),
			'cal_fri_short' => array('Short name of first day of the week:', false, false),
			'cal_sat_short' => array('Short name of first day of the week:', false, false),
			'cal_jan_long' => array('Full name of first month:', false, false),
			'cal_feb_long' => array('Full name of second month:', false, false),
			'cal_mar_long' => array('Full name of third month:', false, false),
			'cal_apr_long' => array('Full name of fourth month:', false, false),
			'cal_may_long' => array('Full name of fifth month:', false, false),
			'cal_jun_long' => array('Full name of sixth month:', false, false),
			'cal_jul_long' => array('Full name of seventh month:', false, false),
			'cal_aug_long' => array('Full name of eighth month:', false, false),
			'cal_sep_long' => array('Full name of ninth month:', false, false),
			'cal_oct_long' => array('Full name of tenth month:', false, false),
			'cal_nov_long' => array('Full name of eleventh month:', false, false),
			'cal_dec_long' => array('Full name of twelfth month:', false, false),
			'cal_jan_short' => array('Short name of first month:', false, false),
			'cal_feb_short' => array('Short name of second month:', false, false),
			'cal_mar_short' => array('Short name of third month:', false, false),
			'cal_apr_short' => array('Short name of fourth month:', false, false),
			'cal_may_short' => array('Short name of fifth month:', false, false),
			'cal_jun_short' => array('Short name of sixth month:', false, false),
			'cal_jul_short' => array('Short name of seventh month:', false, false),
			'cal_aug_short' => array('Short name of eighth month:', false, false),
			'cal_sep_short' => array('Short name of ninth month:', false, false),
			'cal_oct_short' => array('Short name of tenth month:', false, false),
			'cal_nov_short' => array('Short name of eleventh month:', false, false),
			'cal_dec_short' => array('Short name of twelfth month:', false, false),
		),
	);

	inputfield('Title:', 'askin[title]', $askin['title']);
	hiddenfield('askinid', $askinid);
	if ($askinid == 0) {
		selectbox('Template set:', 'askin[templatesetid]', array('-1' => 'Create New Set') + table_to_array('templateset', 'templatesetid', '1 = 1', 'title'), -1);
	} else {
		tableselect('Template set: '.makelink('create new', 'templateset.php?cmd=edit', true), 'askin[templatesetid]', 'templateset', $askin['templatesetid']);
	}

	echo "<script language=\"JavaScript\">
<!--
var defskin = new Array();
var skin = new Array();

function changeInput(num) {
	if (eval('document.forms.form.skin'+num+'.value') != defskin[num]) {
		eval('document.forms.form.skin'+num).style.color = '#085858';
		if (eval('document.forms.form.color'+num)) {
			eval('document.forms.form.color'+num).style.background = defskin[num];
		}
		eval('document.forms.form.change'+num).value = 'Redo';
		return defskin[num];
	} else {
		eval('document.forms.form.skin'+num).style.color = '#cc3300';
		if (eval('document.forms.form.color'+num)) {
			eval('document.forms.form.color'+num).style.background = skin[num];
		}
		eval('document.forms.form.change'+num).value = 'Undo';
		return skin[num];
	}
}
// -->
</script>
";
	$i = 1;
	foreach ($vars as $catname => $itsvars) {
		tablehead(array($catname), 2);
		foreach ($itsvars as $varname => $varinfo) {
			if (substr($varname, 0, 4) == 'note') {
				textrow("<b>$varinfo</b>", 2);
				continue;
			}

			$description = $varinfo[0];
			$dobox = $varinfo[1];
			$dorevert = iif(isset($varinfo[2]), $varinfo[2], true);
			if ($dorevert) {
				echo "
				<script language=\"JavaScript\">
				<!--
				defskin[$i] = '".addslashes($defskin["$varname"])."';
				skin[$i] = '".addslashes($askin['vars']["$varname"])."';
				// -->
				</script>";
			}
			inputfield($description, "askin[vars][$varname]\" onFocus=\"this.select();\" tabindex=\"$i\"".iif($dobox, " onChange=\"this.form.color$i.style.background = this.value;\"")." style=\"color: ".iif($askin['vars']["$varname"] != $defskin["$varname"], '#cc3300', '#085858').";\" id=\"skin$i", $askin['vars']["$varname"], iif($dobox, 27, 35) - iif($dorevert and $askin['vars']["$varname"] != $defskin["$varname"], 10, 0), iif($dorevert and $askin['vars']["$varname"] != $defskin["$varname"], ' <input class="button" type="button" value="Undo" style="width: 56px; height: 17px; padding: 0px;" id="change'.$i.'" onClick="this.form.skin'.$i.'.value = changeInput('.$i.');">').iif($dobox, ' <input class="bginput" style="background: '.$askin['vars']["$varname"].'" type="button" value="       " disabled="disabled" style="width: 45px; height: 17px; padding: 0px;" id="color'.$i.'">'), true, false);
			$i++;
		}
	}

	if ($askinid == 0) {
		endform('Create skin', 'Clear Fields');
	} else {
		endform('Update skin', 'Reset Fields');
	}
	endtable();
}

// ############################################################################
// List the skins
if ($cmd == 'modify') {
	cp_nav('skinmodify');
	adminlog();
	starttable('', '450');
	$cells = array(
		'ID',
		'Title',
		'Used By',
		'Options'
	);
	tablehead($cells);
	$askins = $DB_site->query('
		SELECT skin.*, COUNT(user.userid) AS users
		FROM hive_skin AS skin
		LEFT JOIN hive_user AS user USING (skinid)
		GROUP BY skin.skinid
	');
	if ($DB_site->num_rows($askins) < 1) {
		textrow('No skins', count($cells), 1);
	} else {
		while ($askin = $DB_site->fetch_array($askins)) {
			$cells = array(
				$askin['skinid'],
				"<a href=\"skin.php?cmd=edit&askinid=$askin[skinid]\">$askin[title]</a>",
				iif($askin['users'] > 0, "<a href=\"user.php?cmd=results&find[eskinid]=$askin[skinid]\">$askin[users] user".iif($askin['users'] != 1, 's').'</a>', "$askin[users] users"),
				makelink('view', "../".INDEX_FILE."?skinid=$askin[skinid]", 1) . '-' . makelink('edit', "skin.php?cmd=edit&askinid=$askin[skinid]") . iif($askin['skinid'] != 1, '-' . makelink('remove', "skin.php?cmd=remove&askinid=$askin[skinid]"))
			);
			tablerow($cells);
		}
	}
	emptyrow(count($cells));
	endtable();

	startform('skin.php', 'edit');
	echo '<div aling="center"><input class="button" type="submit" value="  Create new skin  " /></div><br />';
	endform();

	starttable('', '450');
	textrow('A <b>skin</b> for HiveMail&trade; consists of two parts: variables and templates.<br /><br />
	Each skin has a set of variables you can change. These variables control text colors, font face, table borders and other options. To edit these variables, click the <i>[edit]</i> link next to the appropriate skin.<br /><br />
	Each skin also has a set of templates that are used to display content. That set can be defined in the skin options, and it points to a <b><a href="templateset.php">template set</a></b> you have created already.<br /><br />
	This way, two skins can use the same template sets, but each one with different variables. Both skins will look the same, but the same, except the colors, borders, etc. will be different, creating two unique skins with only one template set.<br /><br />
	(The original skin (ID: 1) cannot be removed.)');
	endtable();
}

cp_footer();
?>