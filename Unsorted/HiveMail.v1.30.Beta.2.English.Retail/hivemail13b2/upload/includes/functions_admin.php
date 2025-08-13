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
// | $RCSfile: functions_admin.php,v $ - $Revision: 1.90 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
function get_sort_string($sortbyvalue) {
	global $sortby, $sortorder;

	if ($sortbyvalue == $sortby) {
		$order = iif($sortorder == 'desc', 'asc', 'desc');
	} else {
		$order = iif($sortorder == 'desc', 'desc', 'asc');
	}
	return "sortby=$sortbyvalue&sortorder=$order";
}

// ############################################################################
function check_admin_perm($perm) {
	global $hiveuser, $admin_perms;

	return in_array($hiveuser['userid'], explode(',', $admin_perms["$perm"]));
}

// ############################################################################
function adminlog($id = 0, $success = -1, $logdo = '', $notes = '') {
	global $cmd, $DB_site, $hiveuser;

	if (empty($logdo)) {
		$logdo = $cmd;
	}
	$DB_site->query("
		INSERT INTO hive_adminlog
		SET adminlogid = NULL, dateline = ".TIMENOW.", do = '".addslashes($logdo)."', filename = '".addslashes(basename($_SERVER['PHP_SELF']))."', userid = $hiveuser[userid], id = ".intval($id).", success = ".intval($success).", notes = '".addslashes($notes)."', ipaddress = '".addslashes(IPADDRESS)."'
	");
}

// ############################################################################
function reform_field_name($name) {
	return str_replace(array('[', ']'), array('_', ''), $name);
}

// ############################################################################
function output_dump($buffer) {
	global $session_url, $session_ampersand, $_cp_nav;

	return gzipdata(str_replace(array('.p'.md5(TIMENOW).'hp', md5('cpnav'.TIMENOW)), array('.php', $_cp_nav), iif(defined('ALLOW_LOGGED_OUT'), $buffer, preg_replace('#\.php(\?)?#ies', '\'.php'.$session_url.'\'.((\'\1\' != \'\') ? (\''.$session_ampersand.'\') : (\'\'))', $buffer))), getop('gzip_level'));
}
ob_start('output_dump');

// ############################################################################
function thetime() {
	global $pagestarttime;

	$pageendtime = microtime();
	$starttime = explode(' ', $pagestarttime);
	$endtime = explode(' ', $pageendtime);
	$totaltime = $endtime[0] - $starttime[0] + $endtime[1] - $starttime[1];

//	echo "<br /><br />It took me $totaltime seconds.";
	ob_end_flush();
}
$pagestarttime = microtime();
//register_shutdown_function('thetime');

// ############################################################################
function cp_header($title = '', $shownav = true, $showbody = true, $headextra = '', $nomargin = false, $bodyextra = '') {
	// Get width settings from cookie
	if (isset($_COOKIE['layer13Width']) and !defined('CP_IGNORE_COOKIES')) {
		$maincell = $_COOKIE['layer13Width'];
	} else {
		$maincell = '824px';
	}
	if (isset($_COOKIE['layer2Width']) and !defined('CP_IGNORE_COOKIES')) {
		$contentcell = $_COOKIE['layer2Width'];
	} else {
		$contentcell = '799px';
	}
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>HiveMail&trade; Control Panel$title</title>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
	echo '<meta http-equiv="MSThemeCompatible" content="yes" />';
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../misc/cp.css\" />\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../misc/calendar/popcalendar.css\" />";
	echo "<script language=\"JavaScript\" src=\"../misc/common.js\"></script>\n";
	echo "<script language=\"JavaScript\" src=\"../misc/calendar/popcalendar.js\"></script>";
	echo "<script language=\"JavaScript\" src=\"../misc/hover.js\"></script>\n";
	if (getop('cp_plus')) {
		echo "<script language=\"JavaScript\">\n";
		echo "<!--\n";
		echo "function showhidetable(id, img) {\n";
		echo "\te = document.getElementById(id).style;\n";
		echo "\tif (e.display == '') {\n";
		echo "\t\te.display = 'none'; img.src = '../misc/minus.gif';\n";
		echo "\t} else {\n";
		echo "\t\te.display = ''; img.src = '../misc/plus.gif';\n";
		echo "\t}\n";
		echo "\n}";
		echo "//-->\n";
		echo "</script>\n";
	}
	if (!$showbody) {
		echo "<style type=\"text/css\">\n";
		echo "body {\n";
		echo "	background: #F1F3F5;\n";
		echo "}\n";
		echo "</style>\n";
	}
	echo $headextra;
	echo "</head>\n";
	echo "<body text=\"#465362\" link=\"#465362\" vlink=\"#465362\" alink=\"#465362\" style=\"width: 100%; height: 100%\"$bodyextra>\n";
	if ($showbody) {
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%; height: 100%\"><tr style=\"height: 30px\"><td colspan=\"3\">\n";
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"main\" id=\"top\">\n";
		echo "    <tr>\n";
		echo "        <td height=\"14\" colspan=\"7\" class=\"toptop\"><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"1\" height=\"14\" /></td>\n";
		echo "    </tr>\n";
		echo "    <tr>\n";
		echo "        <td width=\"158\" height=\"7\" rowspan=\"2\" class=\"topmiddle\"><img src=\"../misc/cp_images/top.left.gif\" alt=\"HiveMail&trade; Control Panel\" width=\"158\" height=\"50\" /></td>\n";
		echo "        <td width=\"75\" class=\"topmiddle\"><a href=\"../index.php\" target=\"_blank\"><img src=\"../misc/cp_images/topnav.home.gif\" alt=\"Application Home\" width=\"75\" height=\"25\" border=\"0\" /></a></td>\n";
		echo "        <td width=\"283\" rowspan=\"2\" class=\"topmiddle\"><a href=\"index.php\"><img src=\"../misc/cp_images/top.center.gif\" alt=\"HiveMail&trade; Control Panel\" width=\"283\" height=\"50\" border=\"0\" /></a></td>\n";
		echo "        <td width=\"75\" class=\"topmiddle\"><!--CyKuH [WTN]--><img src=\"../misc/cp_images/topnav.support.gif\" alt=\"\" width=\"75\" height=\"25\" border=\"0\" /></td>\n";
		echo "        <td width=\"158\" rowspan=\"2\" class=\"topmiddle\"><img src=\"../misc/cp_images/top.right.gif\" alt=\"HiveMail&trade; Control Panel\" width=\"158\" height=\"50\" /></td>\n";
		echo "        <td colspan=\"2\" rowspan=\"2\" valign=\"top\" class=\"toprightmiddle\">&nbsp;</td>\n";
		echo "    </tr>\n";
		echo "    <tr>\n";
		echo "        <td width=\"75\" class=\"topmiddle\"><a href=\"../user.logout.php\"><img src=\"../misc/cp_images/topnav.logout.gif\" alt=\"Log Out\" width=\"75\" height=\"25\" border=\"0\" /></a></td>\n";
		echo "        <td width=\"75\" class=\"topmiddle\"><!--CyKuH [WTN]--><img src=\"../misc/cp_images/topnav.forums.gif\" width=\"75\" height=\"25\" border=\"0\" /></td>\n";
		echo "    </tr>\n";
		echo "    <tr>\n";
		echo "        <td height=\"11\" colspan=\"7\" class=\"toprightbottom\"><img src=\"../misc/cp_images/top.bottom.gif\" alt=\"\" width=\"749\" height=\"11\" /></td>\n";
		echo "    </tr>\n";
		echo "</table>\n";
		if ($shownav) {
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"main\" id=\"middle\">\n";
			echo "    <tr>\n";
			echo "        <td width=\"749\">\n";
			echo "        <table style=\"width: $maincell\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"maincell\">\n";
			echo "            <tr>\n";
			echo "                <td rowspan=\"2\" valign=\"top\" class=\"col\">\n";
			echo "                <table width=\"175\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			echo "						".md5('cpnav'.TIMENOW)."\n";
			echo "                </table>\n";
			echo "                </td>\n";
			echo "                <td style=\"width: 100%\" bgcolor=\"#f1f3f5\" align=\"center\" valign=\"top\" id=\"contentcell\">\n";
			echo "                <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			echo "					<tr>\n";
			echo "						<td valign=\"top\" class=\"location\">\n";
			echo "						<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"99%\">\n";
			echo "							<tr>\n";
			echo "								<td width=\"20\"><img src=\"../misc/cp_images/main.round.gif\" alt=\"\" width=\"20\" height=\"17\" /></td>\n";
			echo "								<td valign=\"middle\" class=\"FFFFFF75\">HiveMail&trade; CP$title</td>\n";
			echo "								<td valign=\"middle\" class=\"FFFFFF75\" align=\"right\">&nbsp;</td>\n";
			echo "							</tr>\n";
			echo "						</table>\n";
			echo "						</td>\n";
			echo "					</tr>\n";
			echo "                </table><br />\n";
		} else {
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"main\" id=\"middle\">\n";
			echo "    <tr>\n";
			echo "        <td width=\"749\">\n";
			echo "        <table style=\"width: $maincell\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"maincell\">\n";
			echo "            <tr>\n";
			echo "                <td rowspan=\"2\" valign=\"top\" class=\"col\">\n";
			echo "                </td>\n";
			echo "                <td width=\"624\" valign=\"top\" class=\"location\">\n";
			echo "                <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			echo "                    <tr>\n";
			echo "                        <td width=\"20\"><img src=\"../misc/cp_images/main.round.gif\" alt=\"\" width=\"20\" height=\"17\" /></td>\n";
			echo "                        <td valign=\"middle\" class=\"FFFFFF75\">HiveMail&trade; CP$title</td>\n";
			echo "                    </tr>\n";
			echo "                </table>\n";
			echo "                </td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "                <td style=\"width: 624px\" bgcolor=\"#f1f3f5\" align=\"center\" valign=\"top\" id=\"contentcell\"><br />\n";
		}
	}
	echo "<br />\n";
}

// ############################################################################
function cp_footer($showbody = true, $showcopy = true) {
	// $showcopy only applies when $showbody is set to flase!

	// Get width settings from cookie
	if (isset($_COOKIE['layer13Width']) and !defined('CP_IGNORE_COOKIES')) {
		$bottomcell = $_COOKIE['layer13Width'];
	} else {
		$bottomcell = '824px';
	}
	if (isset($_COOKIE['layer2Width']) and !defined('CP_IGNORE_COOKIES')) {
		$contentcell = $_COOKIE['layer2Width'];
	} else {
		$contentcell = '799px';
	}

	if ($showbody) {
		echo "<!-- FOOTER -->\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "        </table>\n";
		echo "        </td>\n";
		echo "        <td width=\"3\" valign=\"top\" class=\"rightseparator\" style=\"cursor: e-resize;\" onmousedown=\"return startResize();\" onmouseup=\"endResize();\"><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"3\" height=\"1\" /></td>\n";
		echo "        <td valign=\"top\" class=\"right\">&nbsp;</td>\n";
		echo "    </tr>\n";
		echo "</table>\n";
		echo "</td></tr>\n";
		echo "<tr style=\"height: 100%\">\n";
		echo "    <td style=\"width: $bottomcell; padding: 0px 0px 5px 0px; height: 100%; background: #f1f3f5 url('../misc/cp_images/col.bg.jpg') repeat-y;\" id=\"bottomcell\" align=\"center\" valign=\"bottom\"><span class=\"copyright\" style=\"padding: 0px 0px 0px 150px\">Powered by: <b>HiveMail&trade;</b> version ".HIVEFULLVERSION.", copyright &copy;2002-2003 HiveMail</span></td>\n";
		echo "    <td width=\"1\" style=\"cursor: e-resize;\" onmousedown=\"return startResize();\" onmouseup=\"endResize();\" valign=\"top\" class=\"rightseparator\"><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    </tr>\n";
		echo "</table>\n";
	}
	echo "</body>\n";
	echo "</html>\n";
}

// ############################################################################
function cp_nav($keyword = '') {
	global $cp_menus, $cp_items, $DB_site, $allow_bold, $module, $open_menu, $_cp_nav, $_cp_keyword;

	// Prevent overwriting
	if (!empty($_cp_nav)) {
		return;
	}

	$_cp_keyword = $keyword;
	ob_start();

	$allow_bold = true;
	cp_startmenu('HiveMail&trade;');
	cp_navitem(	'Main Settings',	'option.php',					'hiveop');
	cp_endmenu();

	cp_startmenu('Email Processing');
	cp_navitem(	'Email Gateway',		'option.php?cmd=gateway',		'emailgateway',
				'Message Storage',		'storage.php?cmd=modify',		'emailstorage',
				'Distribution Lists',	'distlist.php?cmd=modify',		'emaildist',
				'Spam Reports',			'report.php?cmd=list',			'emailspam',
				'POP3 Email Browser',	'pop.php?cmd=getinfo',			'emailbrowse');
	cp_endmenu();

	cp_startmenu('Users Management');
	cp_navitem(	'Create New Account',	'user.php?cmd=add',				'useradd',
				'Search and Edit Users','user.php?cmd=search',			'usermanage',
				'List All Users',		'user.php?cmd=results',			'userall',
				'Groups Manager',		'usergroup.php?cmd=modify',		'usergroup',
				'Default User Options',	'user.php?cmd=defoptions',		'userop',
				'Validation Queue',		'user.php?cmd=validate',		'uservalidate',
				'Suspended Users',		'ban.php?cmd=modify',			'userban',
				'Custom Profile Fields','field.php?cmd=modify',			'userfield',
				'Global Address Book',	'addbook.php?cmd=modify',		'userbook');
	cp_endmenu();

	cp_startmenu('Subscriptions');
	cp_navitem(	'Plans Manager',		'plan.php',						'subplans',
				'Subscriptions',		'subscription.php',				'subsubs',
				'Payments',				'payment.php',					'subpayments');
	cp_endmenu();

	cp_startmenu('Skins Management');
	cp_navitem(	'Skins Manager',		'skin.php?cmd=modify',			'skinmodify',
				'Templates Manager',	'template'.iif(defined('HIVE_DEV') and HIVE_DEV == true, '_dev').'.php?cmd=modify', 'skintemplate',
				'Template Sets',		'templateset.php?cmd=modify',	'skinset',
				'Download and Upload',	'skin.php?cmd=downup',			'skindownup');
	cp_endmenu();

	// cp_startmenu('Custom Fields');
	// We've prepared the system to allow custom fields in address book entries as well
	// and this will be included in the next version, but if you're bold enough -- give
	// it a shot yourself and try to implement it.
	// cp_navitem(	'User Profile',		'field.php?cmd=modify&module=user', array('bold_cond' => ($module == 'user')));
	// cp_navitem(	'Address Book',		'field.php?cmd=modify&module=book', array('bold_cond' => ($module == 'book')));
	// cp_endmenu();

	cp_startmenu('Sound Files');
	cp_navitem(	'Add',					'sound.php?cmd=add',			'soundadd',
				'Modify',				'sound.php?cmd=modify',			'soundmodify');
	cp_endmenu();

	cp_startmenu('Maintenance');
	cp_navitem(	'Backup Database',		'database.php?cmd=backup',		'databackup',
				'Restore Database',	'database.php?cmd=restore',		'datarestore',
				'Optimize Database',	'database.php?cmd=optimize',		'dataoptimize',
				'Nullification info',	'nullified.php?cmd=null',		'null');
	cp_endmenu();

	cp_startmenu('Activity Logs');
	if (check_admin_perm('viewlog')) {
		cp_navitem(	'Admin Log',		'adminlog.php?cmd=intro',		'logadmin');
	}
	cp_navitem(	'Event Log',			'eventlog.php?cmd=intro',		'logevent',
				'IP Log',				'iplog.php?cmd=intro',			'logip');
	cp_endmenu();

	if (getop('cp_links')) {
		$links = $DB_site->query('
			SELECT *
			FROM hive_cplink
			ORDER BY display
		');
		$allow_bold = false;
		cp_startmenu('Quick Links');
		while ($link = $DB_site->fetch_array($links)) {
			// #######################################################
			// ################# REQUIRES ATTENTION ##################
			// #######################################################
			cp_navitem($link['title'], $link['url'], $link['newwin']);
			// #######################################################
			// #######################################################
		}
		$allow_bold = true;
		cp_navitem(	'Quick Links Manager',	'cplink.php?cmd=modify',		'cplinkmodify');
		cp_endmenu();
	}

	echo "					<tr>\n";
	echo "						<td><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"1\" height=\"2\" /></td>\n";
	echo "					</tr>\n";
	echo '<script>var openedMenu = '.intval($open_menu).";</script>";

	$_cp_nav = ob_get_clean();
}

// ############################################################################
function cp_startmenu($title) {
	global $cp_menus, $cp_items, $menu_title;
	$menu_title = $title;
}

// ############################################################################
function cp_navitem() {
	global $cp_items, $cmd, $allow_bold, $high_row, $menu_title, $_cp_keyword;

	$arguments = func_get_args();
	if ((count($arguments) % 3) == 1) {
		extract(array_pop($arguments));
	}
	
	// Go through the arguments and show the links
	$go_bold = $newwin = false;
	$links = $line = $style_bold = '';
	while (!empty($arguments)) {
		$title = array_shift($arguments);
		$link = array_shift($arguments);
		$keyword = array_shift($arguments);

		// Are we there?
		if (!is_numeric($keyword)) {
			$go_bold = ($allow_bold and $_cp_keyword == $keyword);
			$style_bold = 'style="font-weight: bold;"';
		} else {
			// Open in a new window?
			$newwin = (bool) $keyword;
		}
		if (!$high_row) {
			$high_row = ($go_bold or (infile('index.php') and $link == 'option.php'));
		}

		$cp_items .= "                    <tr>\n";
		$cp_items .= "                        <td nowrap=\"nowrap\" class=\"hovertd".iif($go_bold, 'on', 'off')."\"><a href=\"$link\"><img src=\"../misc/cp_images/spacer.gif\" border=\"0\" alt=\"\" width=\"".iif($go_bold and false, '1', '10')."\" height=\"1\" />".iif($go_bold and false, '[&nbsp;').$title.iif($go_bold and false, '&nbsp;]')."</a></td>\n";
		$cp_items .= "                    </tr>\n";
	}
}

// ############################################################################
function cp_endmenu() {
	global $cp_menus, $cp_items, $menu_title, $high_row, $open_menu;
	static $donefirst, $menu_num = 1;

	if (!$donefirst) {
		echo "                    <tr>\n";
		echo "                        <td colspan=\"3\"><img src=\"../misc/cp_images/col.top.jpg\" alt=\"\" width=\"175\" height=\"17\" /></td>\n";
		echo "                    </tr>\n";
	} else {
		echo "                    <tr>\n";
		echo "                        <td><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"1\" height=\"6\" /></td>\n";
		echo "                    </tr>\n";
		echo "                    <tr>\n";
		echo "                        <td><img src=\"../misc/cp_images/col.sec.top.gif\" alt=\"\" width=\"155\" height=\"4\" /></td>\n";
		echo "                    </tr>\n";
	}
	echo "                    <tr>\n";
	if (!$donefirst) {
		echo "                        <td width=\"10\" rowspan=\"300\"><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"10\" height=\"1\" /></td>\n";
	}
	echo "                        <td id=\"menuTitleOpen$menu_num\" style=\"display: ".iif(!$high_row, 'none')."\" class=\"colsec\"><a href=\"#\" onClick=\"closeMenu($menu_num); return false;\" style=\"display: block; text-decoration: none\"><img src=\"../misc/cp_images/col.sec.on.gif\" alt=\"\" width=\"10\" height=\"9\" border=\"0\" />&nbsp;<strong>$menu_title</strong></a></td>\n";
	echo "                        <td id=\"menuTitleClosed$menu_num\" style=\"display: ".iif($high_row, 'none')."\" class=\"colsec\"><a href=\"#\" onClick=\"openMenu($menu_num); return false;\" style=\"display: block; text-decoration: none\"><img src=\"../misc/cp_images/col.sec.off.gif\" alt=\"\" width=\"10\" height=\"9\" border=\"0\" />&nbsp;<strong>$menu_title</strong></a></td>\n";
	if (!$donefirst) {
		echo "                        <td width=\"10\" rowspan=\"300\"><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"10\" height=\"1\" /></td>\n";
	}
	echo "                    </tr>\n";
	echo "                    <tr id=\"menuRow$menu_num\" style=\"display: ".iif(!$high_row, 'none')."\">\n";
	echo "                        <td><table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">$cp_items</table></td>\n";
	echo "                    </tr>\n";
	echo "                    <tr>\n";
	echo "                        <td><img src=\"../misc/cp_images/col.sec.bottom.gif\" alt=\"\" width=\"155\" height=\"4\" /></td>\n";
	echo "                    </tr>\n";
	if ($high_row) {
		$open_menu = $menu_num;
	}
	$cp_items = '';
	$menu_num++;
	$donefirst = true;
	$high_row = false;
}

// ############################################################################
function cp_redirect($message, $url, $delay = 1, $showfooterbody = true) {
	echo '<meta http-equiv="refresh" content="'.$delay.'; url='.$url.'" /><br />';
	starttable('Please wait while you are redirected...', '400');
	textrow("<br />$message<br /><br /><font size=\"1\"><a href=\"$url\">Click here if you do not want to wait any longer<br />(or if your browser does not automatically forward you)</a></font><br />&nbsp;", 2, true);
	endtable();
	cp_footer($showfooterbody, $showfooterbody);
	exit;
}

// ############################################################################
function cp_error($message, $exit = true, $goback = true, $center = true, $showfooterbody = true) {
	echo '<br />';
	starttable('An error occurred:', '400');
	textrow("<br />$message".iif($goback, '<br /><br /><font size="1">'.makelink('back', '#" onClick="history.back(1);').'</font>').'<br />&nbsp;', 2, $center);
	endtable();

	if ($exit) {
		cp_footer($showfooterbody, $showfooterbody);
		exit;
	}
}

// ############################################################################
// Very tired of losing all changes due to one missing <%endif%> or whatnot...
// Save the template data that was "bad" and offer the user the chance to reedit
// it. history.back(1) always destroys it for me.
function cp_template_error($message, $templatedata, $exit = true, $center = true, $showfooterbody = true) {
	global $DB_site, $title;
	if (!defined('HIVE_DEV')) {
		cp_error($message);
	} else {
		$DB_site->query("
			INSERT INTO hive_temp
			SET tempdata = '".addslashes($templatedata)."'
		");
		$tempid = $DB_site->insert_id();
		echo '<br />';
		startform('template_dev.php', 'edit');
		hiddenfield('tempid', $tempid);
		hiddenfield('title', $title);
		starttable('An error occurred:', '400');
		textrow("<br />$message", 2, $center);
		endtable();
		endform('Continuing editing template');

		if ($exit) {
			cp_footer($showfooterbody, $showfooterbody);
			exit;
		}
	}
}


// ############################################################################
function startform($file, $cmd = '', $confirm = '', $required = array(), $enctype = false, $onSubmit = '', $target = '_self') {
	echo "<form action=\"$file\" method=\"post\" target=\"$target\" name=\"form\"".iif($enctype, ' enctype="multipart/form-data"');
	if (!empty($confirm) or count($required) > 0) {
		if (!empty($confirm) and count($required) == 0) {
			echo " onSubmit=\"if (confirm('$confirm')) { $onSubmit return true; } else { return false; }\"";
		} elseif (empty($confirm) and count($required) > 0) {
			echo ' onSubmit="';
			foreach ($required as $field => $name) {
				echo "if (this.".reform_field_name($field).".value == '') { alert('The $name field is required. Please fill it in.'); return false; } else ";
			}
			echo "{ $onSubmit return true; }\"";
		} elseif (!empty($confirm) and count($required) > 0) {
			echo ' onSubmit="';
			foreach ($required as $field => $name) {
				echo "if (this.".reform_field_name($field).".value == '') { alert('The $name field is required. Please fill it in.'); return false; } else ";
			}
			echo "if (confirm('$confirm')) { $onSubmit return true; } else { return false; }\"";
		}
	}
	echo ">\n";
	if (!empty($cmd)) {
		echo "<input type=\"hidden\" name=\"cmd\" value=\"$cmd\" />\n";
	}
	if ($enctype) {
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.get_max_upload().'" />'."\n";
	}
}

// ############################################################################
function endform($submit = '', $reset = '', $back = '', $confirm = '', $colspan = 2, $endform = true) {
	if (!empty($submit)) {
		echo "	<tr>\n";
		echo "		<td class=\"maincell\" colspan=\"$colspan\" nowrap=\"nowrap\"><input class=\"button\" type=\"submit\" value=\"  $submit  \" />";
		if (!empty($reset)) {
			echo " <input type=\"reset\" class=\"button\" value=\"  $reset  \" />";
		}
		if (!empty($back)) {
			echo " <input type=\"button\" class=\"button\" value=\"  $back  \" onClick=\"history.back(1);\" />";
		}
		echo "</td>\n";
		echo "	</tr>\n";
	}
	if ($endform) {
		echo "</form>\n";
	}
}
// ############################################################################
function starttable($title = '', $width = '90%', $addtable = true, $colspan = 2, $center = false, $cellpadding = '4', $addtab = true) {
	global $cp_table_added;
	static $tableindex = 0;
	$tableindex++;

	if ($addtable) {
		if ($addtab) {
			echo "<table width=\"$width\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tab\">\n";
			echo "	<tr>\n";
            echo "          <td width=\"1\" rowspan=\"2\"class=\"FFFFFF75\"><nobr><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"2\" height=\"8\" /> ".iif(empty($title), 'HiveMail&nbsp;CP', $title)."</nobr></td>\n";
			echo "		<td width=\"12\" rowspan=\"2\"><img src=\"../misc/cp_images/main.tab.gif\" alt=\"\" width=\"12\" height=\"16\" /></td>\n";
			echo "		<td class=\"cutouttab\"><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"1\" height=\"8\" /></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td><img src=\"../misc/cp_images/spacer.gif\" alt=\"\" width=\"1\" height=\"4\" /></td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>\n";
			$cp_table_added = true;
		}
		echo "<table width=\"".iif($addtab, '100%', $width)."\"".iif($center, 'align="center"')." border=\"0\" cellpadding=\"$cellpadding\" cellspacing=\"1\" class=\"contenttable\">\n";
	}
	echo "<tbody id=\"table$tableindex\">";
}

// ############################################################################
function endtable($title = '', $colspan = 2) {
	global $cp_table_added;

	if (!empty($title)) {
		echo "<tr>\n";
		echo "	<td colspan=\"$colspan\" nowrap=\"nowrap\" align=\"center\" valign=\"middle\" class=\"maincell\">$title</td>\n";
		echo "</tr>\n";
	}
	echo "</tbody></table>\n";
	if ($cp_table_added) {
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		$cp_table_added = false;
	}
	echo "<br /><br />\n";
}

// ############################################################################
function tablehead($array, $colspan = 1, $center = false) {
	echo "	<tr>\n";
	foreach ($array as $title) {
		echo "		<td nowrap=\"nowrap\" colspan=\"$colspan\"".iif($center, ' align="center"')." class=\"maincell\">$title</td>\n";
	}
	echo "	</tr>\n";
}

// ############################################################################
function tablerow($array, $samecolor = false, $top = false, $centernums = false, $nowrap = true, $width = false) {
	echo "	<tr>\n";
	if ($samecolor === true) {
		$class = getclass();
	} elseif (is_string($samecolor)) {
		$class = $samecolor;
	}
	foreach ($array as $key => $title) {
		echo "		<td".iif($width, " width=\"$width\"").iif($top, ' valign="top"').iif(substr($key, 0, 6) == 'center' or ($centernums and count($array) > 2 and ((is_numeric($title{0}) and is_numeric($title{strlen($title)-1})) or $title == 'N/A')),' align="center"')." class=\"".iif($samecolor, $class, getclass(iif(count($array)%2 == 1 and $array[0] == $title, 1, 0)))."\" ".iif($nowrap and substr($key, 0, 4) != 'wrap', 'nowrap="nowrap"').">$title</td>\n";
	}
	echo "	</tr>\n";
}

// ############################################################################
function inputfield($title, $fieldname, $value = '', $size = '35', $extra = '', $html = true, $addID = true, $isPass = false, $datefield = false, $invisible = false) {
	echo "	<tr class=\"".($class = getclass())."\">\n";
	echo "		<td width=\"60%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\" nowrap=\"nowrap\">".iif($datefield, "<script language=\"JavaScript\"><!--\nif (!document.layers) {\ndocument.write(\"<a href=\\\"#\\\" onclick=\\\"popUpCalendar(this, document.getElementById('".reform_field_name($fieldname)."'), 'yyyy-mm-dd'); return false;\\\"><img src=\\\"../misc/calendar/open.gif\\\" valign=\\\"middle\\\" border=\\\"0\\\"></a>\");\n}\n//-->\n</script> ")."<input type=\"".iif($isPass, 'password', 'text')."\" ".iif($invisible, 'class="invsibginput_'.$class.'" onFocus="this.className = \'bginput\';" onBlur="this.className = \'invsibginput_'.$class.'\';"', 'class="bginput"')." name=\"$fieldname\" value=\"".iif($html, htmlchars($value), $value)."\" size=\"$size\"".iif($addID, ' id="'.reform_field_name($fieldname).'"')." />$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function inputfield_inv($title, $fieldname, $value = '', $size = '35', $extra = '', $html = true, $addID = true, $isPass = false) {
	inputfield($title, $fieldname, $value, $size, $extra, $html, $addID, $isPass, false, true);
}

// ############################################################################
function datefield($title, $fieldname, $value = '', $size = '31', $extra = '', $html = true, $addID = true, $isPass = false) {
	inputfield($title, $fieldname, $value, $size, $extra, $html, $addID, $isPass, true);
}

// ############################################################################
function datefield_inv($title, $fieldname, $value = '', $size = '31', $extra = '', $html = true, $addID = true, $isPass = false) {
	inputfield($title, $fieldname, $value, $size, $extra, $html, $addID, $isPass, true, true);
}

// ############################################################################
function limitfield($title, $fieldname, $value = '', $size = '31', $nolimitValue = 0, $forceInt = true, $nolimitName = 'Unlimited', $extra = '') {
	if ($forceInt) intme($value);
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"60%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\" nowrap=\"nowrap\"><input type=\"radio\" name=\"".reform_field_name($fieldname)."_nolimit\" id=\"".reform_field_name($fieldname)."_nolimit_false\" value=\"0\"".iif($value != $nolimitValue, ' checked="checked"')." /> <input type=\"text\" class=\"bginput\" name=\"$fieldname\" onClick=\"this.form.".reform_field_name($fieldname)."_nolimit_false.checked = true;\" value=\"$value\" size=\"$size\" id=\"".reform_field_name($fieldname)."\" />$extra<br /><input type=\"radio\" name=\"".reform_field_name($fieldname)."_nolimit\" id=\"".reform_field_name($fieldname)."_nolimit_true\" value=\"1\"".iif($value == $nolimitValue, ' checked="checked"')." /> <label for=\"".reform_field_name($fieldname)."_nolimit_true\">$nolimitName</label></td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function filefield($title, $fieldname, $size = '25', $extra = '', $addID = true) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"60%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\"><input type=\"file\" class=\"bginput\" name=\"$fieldname\" size=\"$size\"".iif($addID, ' id="'.reform_field_name($fieldname).'"')." />$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function textarea($title, $fieldname, $value = '', $rows = '7', $cols = '35', $html = true) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"60%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\"><textarea name=\"$fieldname\" ".iif(is_numeric($cols), "cols=\"$cols\"", "style=\"width: $cols;\"")." rows=\"$rows\" id=\"".reform_field_name($fieldname)."\">".iif($html, htmlchars($value), $value)."</textarea></td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function selectbox($title, $fieldname, $array, $selected = -1, $showline = false, $extra = '', $size = 1, $tagextra = '', $valueasname = false) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"60%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\">\n";
	echo "		<select name=\"$fieldname\" id=\"".reform_field_name($fieldname)."\" size=\"$size\"$tagextra>\n";
	if ($showline and !empty($showline)) {
		if (is_string($showline)) {
			$line = str_pad(" $showline ", 35, '-', STR_PAD_BOTH);
		} else {
			$line = '----------------------------------';
		}
		echo "			<option value=\"-1\"".iif($selected == -1, ' selected="selected"').">$line</option>\n";
	}
	foreach ($array as $value => $name) {
		if ($valueasname) {
			$value = $name;
		}
		echo "			<option value=\"$value\"".iif((!is_array($selected) and $selected == $value) or (is_array($selected) and array_contains($value, $selected)), ' selected="selected"').">$name</option>\n";
	}
	echo "		</select>$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function tableselect($title, $name, $tablename, $selected = -1, $where = '1 = 1', $showline = false, $extra = '', $fieldname = 'title') {
	global $DB_site;

	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"60%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\">\n";
	echo "		<select name=\"$name\"id=\"".reform_field_name($fieldname)."\">\n";
	if ($showline and !empty($showline)) {
		if (is_string($showline)) {
			$line = str_pad(" $showline ", 35, '-', STR_PAD_BOTH);
		} else {
			$line = '----------------------------------';
		}
		echo "			<option value=\"-1\"".iif($selected == -1, ' selected="selected"').">$line</option>\n";
	}
	$rows = $DB_site->query("SELECT * FROM hive_$tablename AS $tablename WHERE $where");
	while ($row = $DB_site->fetch_array($rows)) {
		echo "			<option value=\"".$row[$tablename.'id']."\"".iif($selected == $row[$tablename.'id'], ' selected="selected"').">".$row["$fieldname"]."</option>\n";
	}
	echo "		</select>$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function yesno($title, $fieldname, $default = true) {
	$thisclass = getclass();
	$thisname = md5(microtime());
	echo "	<tr class=\"$thisclass\">\n";
	echo "		<td width=\"60%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\"><input class=\"radio_$thisclass\" type=\"radio\" name=\"$fieldname\" id=\"$thisname"."yes\" value=\"1\"".iif($default, ' checked="checked"')." /> <label for=\"$thisname"."yes\">Yes</label>&nbsp;&nbsp;&nbsp;<input class=\"radio_$thisclass\" type=\"radio\" name=\"$fieldname\" id=\"$thisname"."no\" value=\"0\"".iif(!$default, ' checked="checked"')." /> <label for=\"$thisname"."no\">No</label></td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function hiddenfield($fieldname, $value = '', $addID = true, $html = true) {
	echo "	<input type=\"hidden\" name=\"$fieldname\" value=\"".iif($html, htmlchars($value), $value)."\"".iif($addID, ' id="'.reform_field_name($fieldname).'"')." />\n";
}

// ############################################################################
function textrow($text, $colspan = 2, $center = false) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td colspan=\"$colspan\">".iif($center, "<center>$text</center>", $text)."</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function settingfield($setting) {
	$inputtype = substr($setting['type'], 0, 4);
	$setting['value'] = str_replace('.php', '.p'.md5(TIMENOW).'hp', $setting['value']);
	if (defined('HIVE_DEV') and HIVE_DEV == true) {
		$setting['title'] = "($setting[display]) $setting[title] <span class=\"cp_small\">".makelink('edit', "option.php?cmd=edit&settingid=$setting[settingid]").' '.makelink('remove', "option.php?cmd=remove&settingid=$setting[settingid]").'</span>';
	}
	switch ($inputtype) {
		case 'area':
			textarea("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[variable]]", $setting['value']);
			break;
		case 'text':
			inputfield("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[variable]]", $setting['value']);
			break;
		case 'yesn':
			yesno("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[variable]]", $setting['value']);
			break;
		case 'sele':
			$tablename = substr($setting['type'], 7);
			tableselect("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[variable]]", $tablename, $setting['value']);
			break;
		case 'note':
			textrow($setting['value'], 2, true);
			break;
		case 'time':
			$tzsel = array(iif(getop('timeoffset') >= 0, getop('timeoffset') * 10, 'n'.abs(getop('timeoffset') * 10)) => 'selected="selected"');
			$tztime = array();
			$fieldname = "options[$setting[variable]]";
			eval(makeeval('timezone', 'options_timezone'));
			tablerow(array("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", $timezone), true, false, false, false);
			break;
		case 'mbox':
			selectbox("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", "options[$setting[variable]]", array('mbox' => 'mbox', 'maildir' => 'Maildir', 'mh' => 'MH'), $setting['value']);
			break;
		case 'alte':
			$radioops = array(
				'1' => 'Always require',
				'-1' => 'Only if needed',
				'0' => 'Never require',
			);
			$radios = '';
			foreach ($radioops as $value => $name) {
				$radios .= '<input type="radio" name="options['.$setting['variable'].']" value="'.$value.'"'.iif($setting['value'] == $value, 'checked="checked"').' id="alte'.$value.'" /> <label for="alte'.$value.'">'.$name.'</label><br />';
			}
			tablerow(array("<b>$setting[title]</b><br /><span class=\"cp_small\">$setting[description]</span>", $radios), true, false, false, false);
			break;
	}
}

// ############################################################################
function emptyrow($colspan = 2) {
	echo "	<tr>\n";
	echo "		<td class=\"maincell\" colspan=\"$colspan\">&nbsp;</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function paginate($table, $link, $where = '') {
	global $limitlower, $limitupper, $totalitems, $DB_site, $pagenumber, $perpage;

	if (!isset($page) and isset($pagenumber)) {
		$page = $pagenumber;
	}
	if (intme($perpage) < 1) {
		$perpage = 30;
	}
	if (intme($page) < 1) {
		$page = 1;
	}
	$totalitems = $DB_site->get_field("
		SELECT COUNT(*) AS count
		FROM hive_$table AS $table $where
	");
	$totalpages = ceil($totalitems / $perpage);
	if (($page - 1) * $perpage > $totalitems) {
		 if ((($totalitems / $perpage) - intval(($totalitems / $perpage))) == 0) {
			 $page = $totalitems / $perpage;
		 } else {
			 $page = intval($totalitems / $perpage) + 1;
		 }
	}
	$limitlower = ($page - 1) * $perpage + 1;
	$limitupper = ($page) * $perpage;
	if ($limitupper > $totalitems) {
		$limitupper = $totalitems;
		if ($limitlower > $totalitems) {
			$limitlower = $totalitems - $perpage;
		}
	}
	if ($limitlower <= 0) {
		$limitlower = 1;
	}

	return getpagenav($totalitems, "$link&perpage=$perpage");
}

// ############################################################################
function makelink($text, $url, $new = false) {
	return "[<a href=\"$url\"".iif($new, ' target="_blank"').">$text</a>]";
}

// ############################################################################
function getclass($reset = false) {
	static $classcount = 1;

	if (!$reset) {
		$classcount++;
	}

	if ($classcount % 2 == 0) {
		return 'firstalt';
	} else {
		return 'secondalt';
	}
}

// ############################################################################
// Finds the LCS for $A and $B
function findLCS(&$A, &$B, &$s, &$x) {
	$L = $s = $x = array();
	$m = count($A);
	$n = count($B);
	for ($i = $m; $i >= 0; $i--) {
		for ($j = $n; $j >= 0; $j--) {
			if ($i >= $m or $j >= $n) {
				$L[$i][$j] = 0;
			} elseif ($A[$i] == $B[$j]) {
				$L[$i][$j] = 1 + $L[$i+1][$j+1];
			} else {
				$L[$i][$j] = max($L[$i+1][$j], $L[$i][$j+1]);
			}
		}
	}
	for ($i = $j = 0; count($s) < $L[0][0] and $i < $m and $j < $n; ) {
		if ($A[$i] == $B[$j]) {
			$s[] = "$i-$j";
			$i++;
			$j++;
		} elseif ($L[$i+1][$j] >= $L[$i][$j+1]) {
			$x[] = "$i-$j";
			$i++;
		} else {
			$x[] = "$i-$j";
			$j++;
		}
	}
}

// ############################################################################
// Formats a difference table between $A and $B
// Consider this a prime example of how code should NOT be written if you
// want to still understand it 10 hours after it was done. But it works :)
function formatDiff(&$A, &$B) {
	$A = preg_split("#\r?\n#", $A);
	$B = preg_split("#\r?\n#", $B);
	findLCS($A, $B, $LCS_array, $misMatch);
	echo "<table cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" align=\"center\" style=\"border-width: 0px;\">";
	$i_ = $j_ = -1;
	$g = 1;
	//print_R($misMatch);
	//print_R($LCS_array);
	foreach ($LCS_array as $location) {
		list($i, $j) = explode('-', $location);
		if ($i - $i_ > 1 and $j - $j_ > 1) {
			$maxdiff = max($i - $i_, $j - $j_);
			for ($k = 1; $k < $maxdiff; $k++) {
				echo "	<tr>\n";
				if ($k < $i - $i_) {
					echo "		<td width=\"50%\" class=\"diff-changed\">".format_difftext($A[$i_+$k]).iif(!$doneanchor and $k == 1 and $doneanchor = true, "<a name=\"diff$g\" id=\"diff$g\"> </a>")."</td>\n";
				} else {
					echo "		<td width=\"50%\" class=\"diff-changed-missing\">&nbsp;</td>\n";
				}
				if ($k < $j - $j_) {
					echo "		<td width=\"50%\" class=\"diff-changed\">".format_difftext($B[$j_+$k]).iif(!$doneanchor and $k == 1 and $doneanchor = true, "<a name=\"diff$g\" id=\"diff$g\"> </a>")."</td>\n";
				} else {
					echo "		<td width=\"50%\" class=\"diff-changed-missing\">&nbsp;</td>\n";
				}
				echo "	</tr>\n";
			}
			$g++;
			$doneanchor = false;
		} elseif ($i - $i_ > 1 or $j - $j_ > 1) {
			for ($k = 1; $k < $maxdiff = max($i - $i_, $j - $j_); $k++) {
				echo "	<tr>\n";
				if ($i - $i_ > 1) {
					echo "		<td width=\"50%\" class=\"diff-removed\">".format_difftext($A[$i_+$k])."</td>\n";
				}
				echo "		<td width=\"50%\" class=\"diff-empty\">&nbsp;".iif($k == 1, "<a name=\"diff$g\" id=\"diff$g\"> </a>")."</td>\n";
				if ($j - $j_ > 1) {
					echo "		<td width=\"50%\" class=\"diff-added\">".format_difftext($B[$j_+$k])."</td>\n";
				}
				echo "	</tr>\n";
			}
			$g++;
		}
		echo "	<tr class=\"diff-same-".getclass()."\">\n";
		echo "		<td width=\"50%\">".format_difftext($A[$i])."</td>\n";
		echo "		<td width=\"50%\">".format_difftext($B[$j])."</td>\n";
		echo "	</tr>\n";
		$i_ = $i;
		$j_ = $j;
	}
	if ($i != count($A) or $j != count($B)) {
		$i = count($A);
		$j = count($B);
		$maxdiff = max($i - $i_, $j - $j_);
		for ($k = 1; $k < $maxdiff; $k++) {
			echo "	<tr>\n";
			if ($k < $i - $i_) {
				echo "		<td width=\"50%\" class=\"diff-changed\">".format_difftext($A[$i_+$k])."</td>\n";
			} else {
				echo "		<td width=\"50%\" class=\"diff-changed-missing\">&nbsp;".iif(!$doneanchor and $k == 1 and $doneanchor = true and $g++, "<a name=\"diff$g\" id=\"diff$g\"> </a>")."</td>\n";
			}
			if ($k < $j - $j_) {
				echo "		<td width=\"50%\" class=\"diff-changed\">".format_difftext($B[$j_+$k])."</td>\n";
			} else {
				echo "		<td width=\"50%\" class=\"diff-changed-missing\">&nbsp;".iif(!$doneanchor and $k == 1 and $doneanchor = true and $g++, "<a name=\"diff$g\" id=\"diff$g\"> </a>")."</td>\n";
			}
			echo "	</tr>\n";
		}
	}
	$g--;
	echo "</table>\n";
	echo "<script language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "totalDiffs = $g;\n";
	echo "currentScroll = 0;\n";
	echo "diffLocations = new Array();\n";
	for ($i = 1; $i <= $g; $i++) {
		echo "diffLocations[$i] = getAnchorPosition('diff$i').y;\n";
	}
	echo "//-->\n";
	echo "</script>\n";
}

// ############################################################################
// Instead of using <pre>, we do this
function format_difftext($text) {
	if (trim($text) == '') {
		return '&nbsp;';
	} else {
		return str_replace(array("\t", '  ', '  '), array('&nbsp; &nbsp; &nbsp; &nbsp; ', '&nbsp; ', ' &nbsp;'), htmlchars($text));
	}
}

?>
