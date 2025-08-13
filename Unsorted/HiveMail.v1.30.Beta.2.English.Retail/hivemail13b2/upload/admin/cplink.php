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
// | $RCSfile: cplink.php,v $ - $Revision: 1.9 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Quick Links');
cp_nav('cplinkmodify');

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

// ############################################################################
// Remove quick link
if ($_POST['cmd'] == 'kill') {
	$cplink = getinfo('cplink', $cplinkid);

	$DB_site->query("
		DELETE FROM hive_cplink
		WHERE cplinkid = $cplinkid
	");

	adminlog($cplinkid, true);
	cp_redirect('The link has been removed.', 'cplink.php');
}

// ############################################################################
// Remove quick link
if ($cmd == 'remove') {
	$cplink = getinfo('cplink', $cplinkid);

	adminlog($cplinkid);
	startform('cplink.php', 'kill', 'Are you sure you want to remove this link?');
	starttable('Remove quick link "'.$cplink['title'].'" (ID: '.$cplinkid.')', '450');
	textrow('Are you <b>sure</b> you want to remove this link? This procedure <b>cannot</b> be reveresed!');
	hiddenfield('cplinkid', $cplinkid);
	endform('Remove Link', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new cplink or update an existing one
if ($_POST['cmd'] == 'update') {
	if (empty($cplink['title'])) {
		adminlog($cplinkid, false);
		cp_error('The link must have a title.');
	} else {
		unset($cplink['sep']);
		if ($cplinkid == 0) {
			$DB_site->auto_query('cplink', $cplink);
			adminlog($cplinkid, true);
			cp_redirect('The link has been created.', 'cplink.php');
		} else {
			$DB_site->auto_query('cplink', $cplink, "cplinkid = $cplinkid");
			adminlog($cplinkid, true);
			cp_redirect('The link has been updated.', 'cplink.php');
		}
	}
}

// ############################################################################
// Create a new cplink or update an existing one
if ($cmd == 'edit') {
	startform('cplink.php', 'update', '', array('cplink_title' => 'title'));

	$cplink = getinfo('cplink', $cplinkid, false, false);
	adminlog($cplinkid);
	if ($cplink === false) {
		$cplink = array(
			'display' => $DB_site->get_field('SELECT MAX(display) FROM hive_cplink') + 1,
		);
		$cplinkid = 0;
		starttable('Create new quick link', '450');
	} else {
		starttable('Update quick link "'.$cplink['title'].'" (ID: '.$cplinkid.')', '450');
	}

	hiddenfield('cplinkid', $cplinkid);
	inputfield('Title:', 'cplink[title]', $cplink['title']);
	inputfield('Link to:', 'cplink[url]', $cplink['url']);
	yesno('Open link in new window:', 'cplink[newwin]', $cplink['newwin']);
	inputfield('Display order:', 'cplink[display]', $cplink['display'], 4);

	if ($cplinkid == 0) {
		endform('Create quick link');
	} else {
		endform('Update quick link');
	}
	endtable();
}

// ############################################################################
// List the cplinks
if ($cmd == 'modify') {
	adminlog();

	$links = $DB_site->query('
		SELECT *
		FROM hive_cplink
		ORDER BY display
	');
	if ($DB_site->num_rows($links) > 0) {
		startform('cplink.php', 'display');
	}
	starttable('', '450');
	$cells = array(
		'Title',
		'Options'
	);
	tablehead($cells);
	if ($DB_site->num_rows($links) < 1) {
		textrow('No links', count($cells), 1);
		emptyrow(count($cells));
	} else {
		while ($link = $DB_site->fetch_array($links)) {
			$cells = array(
				'<input type="text" name="displays['.$link['cplinkid'].']" value="'.$link['display'].'" class="bginput" size="2" /> '."<a href=\"$link[url]\" target=\"_blank\">$link[title]</a>",
				makelink('edit', "cplink.php?cmd=edit&cplinkid=$link[cplinkid]") . '-' . makelink('remove', "cplink.php?cmd=remove&cplinkid=$link[cplinkid]"),
			);
			tablerow($cells);
		}
		endform('Update Link Orders', '', '', '', count($cells));
	}
	endtable();

	$cplink = array(
		'display' => $DB_site->get_field('SELECT MAX(display) FROM hive_cplink') + 1,
	);
	startform('cplink.php', 'update', '', array('cplink_title' => 'title'));
	starttable('Create new quick link', '450');
	hiddenfield('cplinkid', $cplinkid);
	inputfield('Title:', 'cplink[title]', $cplink['title']);
	inputfield('Link to:', 'cplink[url]', $cplink['url']);
	yesno('Open link in new window:', 'cplink[newwin]', $cplink['newwin']);
	inputfield('Display order:', 'cplink[display]', $cplink['display'], 4);
	endform('Create quick link');
	endtable();
}

// ############################################################################
// Update display orders
if ($_POST['cmd'] == 'display') {
	if (!is_array($displays)) {
		adminlog(0, false);
		cp_error('Invalid information specified.');
	} else {
		foreach ($displays as $cplinkid => $display) {
			$DB_site->query('
				UPDATE hive_cplink
				SET display = '.intval($display).'
				WHERE cplinkid = '.intval($cplinkid).'
			');	
			adminlog($cplinkid, true);
		}
		cp_redirect('The links have been updated.', 'cplink.php');
	}
}

cp_footer();
?>