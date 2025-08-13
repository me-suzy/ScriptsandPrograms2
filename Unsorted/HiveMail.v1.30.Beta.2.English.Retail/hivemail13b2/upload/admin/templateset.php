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
// | $RCSfile: templateset.php,v $ - $Revision: 1.17 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Template Sets');
cp_nav('skinset');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');
if ($cmd == 'add') {
	$cmd = 'edit';
}

// ############################################################################
// Remove template set
if ($_POST['cmd'] == 'kill') {
	$templateset = getinfo('templateset', $templatesetid);
	if ($templatesetid == 1) {
		adminlog($templatesetid, false);
		cp_error('You cannot remove the original template set.');
	} elseif (!$DB_site->query_first("SELECT templatesetid FROM hive_templateset WHERE templatesetid <> $templatesetid")) {
		adminlog($templatesetid, false);
		cp_error('You cannot remove the last template set.');
	}

	$DB_site->query("
		DELETE FROM hive_templateset
		WHERE templatesetid = $templatesetid
	");
	$DB_site->query("
		DELETE FROM hive_template
		WHERE templatesetid = $templatesetid
	");
	$DB_site->query("
		UPDATE hive_skin
		SET templatesetid = $moveto
		WHERE templatesetid = $templatesetid
	");

	adminlog($templatesetid, true);
	cp_redirect('The template set has been removed.', 'templateset.php', 'kill', "Skins will now be using template set: $moveto");
}

// ############################################################################
// Remove template set
if ($cmd == 'remove') {
	$templateset = getinfo('templateset', $templatesetid);
	if ($templatesetid == 1) {
		adminlog($templatesetid, false);
		cp_error('You cannot remove the original template set.');
	} elseif (!$DB_site->query_first("SELECT templatesetid FROM hive_templateset WHERE templatesetid <> $templatesetid")) {
		adminlog($templatesetid, false);
		cp_error('You cannot remove the last template set.');
	}

	adminlog($templatesetid);
	startform('templateset.php', 'kill', 'Are you sure you want to remove this template set?');
	starttable('Remove template set "'.$templateset['title'].'" (ID: '.$templatesetid.')');
	textrow('Are you <b>sure</b> you want to remove this set? This procedure <b>cannot</b> be reveresed!');
	tableselect('Have skins that use this template set use:', 'moveto', 'templateset', -1, "templatesetid <> $templatesetid");
	hiddenfield('templatesetid', $templatesetid);
	endform('Remove Template Set', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new templateset or update an existing one
if ($_POST['cmd'] == 'update') {
	if (empty($templateset['title'])) {
		adminlog($templatesetid, false);
		cp_error('The template set must have a title.');
	} else {
		if ($templatesetid == 0) {
			$DB_site->auto_query('templateset', $templateset);
			if ($copyfrom != -1) {
				$templatesetid = $DB_site->insert_id();
				$templates = $DB_site->query('
					SELECT *
					FROM hive_template
					WHERE templatesetid = '.intval($copyfrom).'
				');
				while ($template = $DB_site->fetch_array($templates)) {
					unset($template['templateid']);
					$template['templatesetid'] = $templatesetid;
					$DB_site->auto_query('template', $template);
				}
			}
			adminlog($templatesetid, true);
			cp_redirect('The template set has been created.', 'templateset.php');
		} else {
			$DB_site->auto_query('templateset', $templateset, "templatesetid = $templatesetid");
			adminlog($templatesetid, true);
			cp_redirect('The template set has been updated.', 'templateset.php');
		}
	}
}

// ############################################################################
// Create a new templateset or update an existing one
if ($cmd == 'edit') {
	startform('templateset.php', 'update', '', array('templateset_title' => 'title'));

	$templateset = getinfo('templateset', $templatesetid, false, false);
	adminlog($templatesetid);
	if ($templateset === false) {
		$templateset = array(
			'title' => ''
		);
		$templatesetid = 0;
		starttable('Create new template set');
	} else {
		starttable('Update template set "'.$templateset['title'].'" (ID: '.$templatesetid.')');
	}

	hiddenfield('templatesetid', $templatesetid);
	inputfield('Template set title:', 'templateset[title]', $templateset['title']);

	if ($templatesetid == 0) {
		tableselect('Make this set a copy of...<br /><span class="cp_small">You can copy all templates from another set using this option.<br />Useful for making copies of a template set so the original is safe.</span>', 'copyfrom', 'templateset', -1, '1 = 1', 'no set');
		endform('Create template set');
	} else {
		endform('Update template set');
	}
	endtable();
}

// ############################################################################
// List the templatesets
if ($cmd == 'modify') {
	adminlog();

	$skins = table_to_array('skin', 'title', '1 = 1', 'templatesetid');
	starttable('', '450');
	$cells = array(
		'ID',
		'Title',
		'Used for Skins',
		'Options'
	);
	tablehead($cells);
	$sets = $DB_site->query('
		SELECT *
		FROM hive_templateset
	');
	if ($DB_site->num_rows($sets) < 1) {
		textrow('No sets', count($cells), 1);
	} else {
		while ($set = $DB_site->fetch_array($sets)) {
			$usedfor = '';
			foreach ($skins as $title => $info) {
				if ($set['templatesetid'] == $info['templatesetid']) {
					$usedfor .= "<a href=\"skin.php?cmd=edit&askinid=$info[skinid]\">$title</a><br />";
				}
			}
			$usedfor = substr($usedfor, 0, -6);
			if (empty($usedfor)) {
				$usedfor = '(none)';
			}
			$cells = array(
				$set['templatesetid'],
				"<a href=\"templateset.php?cmd=edit&templatesetid=$set[templatesetid]\">$set[title]</a>",
				$usedfor,
				makelink('edit', "templateset.php?cmd=edit&templatesetid=$set[templatesetid]") . iif($set['templatesetid'] != 1, '-' . makelink('remove', "templateset.php?cmd=remove&templatesetid=$set[templatesetid]"))
			);
			tablerow($cells);
		}
	}
	emptyrow(count($cells));
	endtable();

	startform('templateset.php', 'update', '', array('templateset_title' => 'title'));
	$templateset = array(
		'title' => ''
	);
	$templatesetid = 0;
	starttable('Create new template set', '450');
	hiddenfield('templatesetid', $templatesetid);
	inputfield('Template set title:', 'templateset[title]', $templateset['title']);
	tableselect('Make this set a copy of...<br /><span class="cp_small">You can copy all templates from another set using this option.<br />Useful for making copies of a template set so the original is safe.</span>', 'copyfrom', 'templateset', -1, '1 = 1', 'no set');
	endform('Create template set');
	endtable();

	echo '<br /><br />';
	starttable('', '450');
	textrow('A <b>template set</b> is a set of templates that can be used in <b><a href="skin.php">skins</a></b>. Every set has its own templates, so you can have more than one set and edit each one individually without causing any collision with the others.<br /><br />
	Each template set you have can be used for one or more skins. To change the template set a skin uses, click <a href="skin.php">here</a>, and edit the skin you want to change.<br /><br />
	When you are making vast changes to the site\'s templates, it\'s recommended that you create another template set and work on it. Then, change the appropirate skin and make it use the new template set. That way, people that are currently using the system won\'t be effected by any mistakes you may make while editing the templates.<br /><br />
	(The original template set (ID: 1) cannot be removed.)');
	endtable();
}

cp_footer();
?>