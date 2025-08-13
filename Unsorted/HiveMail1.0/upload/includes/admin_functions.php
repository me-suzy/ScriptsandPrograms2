<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: admin_functions.php,v $
// | $Date: 2002/11/12 15:16:53 $
// | $Revision: 1.44 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
function reform_field_name($name) {
	return str_replace(array('[', ']'), array('_', ''), $name);
}

// ############################################################################
function output_dump($buffer) {
	global $session_url, $session_ampersand;

	return iif(defined('ALLOW_LOGGED_OUT'), $buffer, preg_replace('#\.php(\?)?#ies', '\'.php'.$session_url.'\'.((\'\1\' != \'\') ? (\''.$session_ampersand.'\') : (\'\'))', $buffer));
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
function cp_header($title = '', $shownav = true, $showbody = true, $headextra = '') {
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>HiveMail$title</title>\n";
	echo '<meta http-equiv="MSThemeCompatible" content="yes" />';
	echo "<link rel=\"stylesheet\" href=\"../misc/cp.css\">\n";
	echo "<script language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "function oc(id, img) {\n";
	echo "\te = document.getElementById(id).style;\n";
	echo "\tif (e.display == '') {\n";
	echo "\t\te.display = 'none'; if (img) img.src = '../misc/plus.gif';\n";
	echo "\t} else {\n";
	echo "\t\te.display = ''; if (img) img.src = '../misc/minus.gif';\n";
	echo "\t}\n";
	echo "\n}";
	echo "//-->\n";
	echo "</script>\n";
	echo $headextra;
	echo "</head>\n";
	echo "<body>\n";
	if ($showbody) {
		if (getop('cp_topmenu') and $shownav) {
			echo "<link href=\"../misc/menu.css\" rel=\"stylesheet\" type=\"text/css\">\n";
			echo "<script language=\"JavaScript\" src=\"../misc/menu.js\"></script>\n";
			echo "<script language=\"JavaScript\">\n";
			echo "<!--\n";
			echo cp_nav();
			echo "//-->\n";
			echo "</script>\n";
			echo "<br /><br />\n";
		}
		echo "<table width=\"100%\" style=\"border-width: 0px;\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\"><b>Welcome to HiveMail version ".HIVEVERSION." Control Panel</b><br /><hr noshade=\"noshade\" size=\"1\" /></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		if (!getop('cp_topmenu') and $shownav) {
			echo "        <td valign=\"top\">";
			cp_nav();
			echo "</td>\n";
		}
		echo "		<td  width=\"100%\" align=\"center\" valign=\"top\">\n<!-- END OF HEADER -->\n";
	}
}

// ############################################################################
function cp_footer($showbody = true) {
	if ($showbody) {
		echo "<!-- START OF FOOTER -->\n";
		echo "		<br /></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\"><font size=\"1\"><hr noshade=\"noshade\" size=\"1\" />Powered by: <b>HiveMail</b> copyright &copy;2002 <!--CyKuH [WTN]--></font></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	echo "</body>\n";
	echo "</html>";
}

// ############################################################################
function cp_nav() {
	global $cp_menus, $cp_items;

	if (getop('cp_topmenu')) {
		$cp_menus = "document.write('<div id=\"menuBar\">');\n";
		$cp_items = '';
	} else {
		starttable('', '125', 1, 1);
	}

	cp_startmenu('HiveMail');
	cp_navitem('Home', 'index.php');
	cp_navitem('Options', 'option.php');
	cp_navitem('Nullification info', 'nullification.php');
	cp_endmenu();

	cp_startmenu('Users');
	cp_navitem('Add', 'user.php?do=edit');
	cp_navitem('Find & Edit', 'user.php?do=search');
	cp_navitem('Validation Queue', 'user.php?do=validate');
	cp_endmenu();

	cp_startmenu('User Groups');
	cp_navitem('Add', 'usergroup.php?do=edit');
	cp_navitem('Modify', 'usergroup.php?do=modify');
	cp_endmenu();

	cp_startmenu('Skins');
	cp_navitem('Add', 'skin.php?do=edit');
	cp_navitem('Modify', 'skin.php?do=modify');
	cp_navitem('Export', 'skin.php?do=export');
	cp_navitem('Import', 'skin.php?do=import');
	cp_endmenu();

	cp_startmenu('Template Sets');
	cp_navitem('Add', 'templateset.php?do=edit');
	cp_navitem('Modify', 'templateset.php?do=modify');
	cp_endmenu();

	cp_startmenu('Templates');
	cp_navitem('Add', 'template.php?do=add');
	cp_navitem('Modify', 'template.php?do=modify');
	cp_navitem('Search', 'template.php?do=search');
	cp_endmenu();

	cp_startmenu('Database');
	cp_navitem('Backup', 'database.php?do=backup');
	cp_navitem('Restore', 'database.php?do=restore');
	cp_navitem('Optimize', 'database.php?do=optimize');
	cp_endmenu();

	if (getop('cp_topmenu')) {
		$cp_menus .= "document.write('</div>');";
	} else {
		endtable();
	}

	return "$cp_menus\n$cp_items\n";
}

// ############################################################################
function cp_startmenu($title) {
	global $cp_menus, $cp_items;

	if (getop('cp_topmenu')) {
		$codename = strtolower(str_replace(' ', '_', $title));
		$cp_menus .= "document.write('<a class=\"menuButton\" href=\"\" onclick=\"return buttonClick(this, \'$codename\');\" onmouseover=\"buttonMouseover(this, \'$codename\');\" onmouseout=\"buttonMouseout(this, \'$codename\');\"><span style=\"color: white;\">&nbsp; $title &nbsp;</span></a>');\n";
		$cp_items .= "document.write('<div id=\"$codename\" class=\"menu\">');\n";
	} else {
		tablehead(array($title));
	}
}

// ############################################################################
function cp_navitem($title, $link) {
	global $cp_items;

	if (getop('cp_topmenu')) {
		$cp_items .= "document.write('<a style=\"color: white;\" class=\"menuItem\" href=\"$link\">$title</span></a>');\n";
	} else {
		echo "    <tr class=\"".getclass(1)."\">\n"; 
		echo "        <td><a href=\"$link\" class=\"navlink\">$title</a></td>\n"; 
		echo "    </tr>"; 
	}
}

// ############################################################################
function cp_endmenu() {
	global $cp_menus, $cp_items;

	if (getop('cp_topmenu')) {
		$cp_items .= "document.write('</div>');\n";
	}
}

// ############################################################################
function cp_redirect($message, $url, $delay = 1) {
	echo '<meta http-equiv="refresh" content="'.$delay.'; url='.$url.'" /><br />';
	starttable('Please wait while you are redirected...', '400');
	textrow("<br />$message<br /><br /><font size=\"1\"><a href=\"$url\">Click here if you do not want to wait any longer<br />(or if your browser does not automatically forward you)</a></font><br />&nbsp;", 2, true);
	endtable();
	cp_footer();
	exit;
}

// ############################################################################
function cp_error($message, $exit = true, $goback = true) {
	echo '<br />';
	starttable('An error occurred:', '400');
	textrow("<br />$message".iif($goback, '<br /><br /><font size="1">'.makelink('back', '#" onClick="history.back(1);').'</font>').'<br />&nbsp;', 2, true);
	endtable();

	if ($exit) {
		cp_footer();
		exit;
	}
}

// ############################################################################
function startform($file, $do = '', $confirm = '', $required = array(), $enctype = false) {
	echo "<form action=\"$file\" method=\"post\" name=\"form\"".iif($enctype, ' enctype="multipart/form-data"');
	if (!empty($confirm) or count($required) > 0) {
		if (!empty($confirm) and count($required) == 0) {
			echo " onSubmit=\"return confirm('$confirm');\"";
		} elseif (empty($confirm) and count($required) > 0) {
			echo ' onSubmit="';
			foreach ($required as $field => $name) {
				echo "if (this.".reform_field_name($field).".value == '') { alert('The $name field is required. Please fill it in.'); return false; } else ";
			}
			echo '{ return true; }"';
		} elseif (!empty($confirm) and count($required) > 0) {
			echo ' onSubmit="';
			foreach ($required as $field => $name) {
				echo "if (this.".reform_field_name($field).".value == '') { alert('The $name field is required. Please fill it in.'); return false; } else ";
			}
			echo "{ return confirm('$confirm'); }\"";
		}
	}
	echo ">\n";
	if (!empty($do)) {
		echo "<input type=\"hidden\" name=\"do\" value=\"$do\" />\n";
	}
	if ($enctype) {
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.get_max_upload().'" />'."\n";
	}
}

// ############################################################################
function endform($submit = '', $reset = '', $back = '', $confirm = '', $colspan = 2) {
	if (!empty($submit)) {
		echo "	<tr class=\"thead\">\n";
		echo "		<td colspan=\"$colspan\" nowrap=\"nowrap\"><input class=\"bginput\" type=\"submit\" value=\"  $submit  \" />";
		if (!empty($reset)) {
			echo " <input type=\"reset\" class=\"bginput\" value=\"  $reset  \" />";
		}
		if (!empty($back)) {
			echo " <input type=\"button\" class=\"bginput\" value=\"  $back  \" onClick=\"history.back(1);\" />";
		}
		echo "</td>\n";
		echo "	</tr>\n";
	}
	echo "</form>\n";
}

// ############################################################################
function starttable($title = '', $width = '90%', $addtable = true, $colspan = 2, $center = false) {
	if ($addtable) {
		$tablename = substr(md5(microtime()), 0, 10);
		echo "<table id=\"fake$tablename\" cellpadding=\"6\" cellspacing=\"0\" width=\"$width\"".iif($center, ' align="center"')."";
	}
	if (!empty($title)) {
		if (getop('cp_plus') and $addtable) {
			echo " style=\"display: none;\">\n";
			echo "	<tr class=\"thead\">\n";
			echo "		<td colspan=\"$colspan\" nowrap=\"nowrap\"><img src=\"../misc/plus.gif\" onClick=\"oc('fake$tablename'); oc('real$tablename');\" /> $title</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "<table id=\"real$tablename\" cellpadding=\"6\" cellspacing=\"0\" width=\"$width\">\n";
		} else {
			echo ">\n";
		}
		echo "	<tr class=\"thead\">\n";
		echo "		<td colspan=\"$colspan\" nowrap=\"nowrap\">".iif(getop('cp_plus') and $addtable, "<img src=\"../misc/minus.gif\" onClick=\"oc('fake$tablename'); oc('real$tablename');\" /> ")."$title</td>\n";
		echo "	</tr>\n";
	} else {
		echo ">\n";
	}
}

// ############################################################################
function endtable() {
	echo "</table>\n";
}

// ############################################################################
function tablehead($array, $colspan = 1) {
	echo "	<tr class=\"thead\">\n";
	foreach ($array as $title) {
		echo "		<td nowrap=\"nowrap\" colspan=\"$colspan\">$title</td>\n";
	}
	echo "	</tr>\n";
}

// ############################################################################
function tablerow($array, $samecolor = false, $top = false) {
	echo "	<tr>\n";
	if ($samecolor === true) {
		$class = getclass();
	} elseif (is_string($samecolor)) {
		$class = $samecolor;
	}
	foreach ($array as $title) {
		echo "		<td".iif($top, ' valign="top"')." class=\"".iif($samecolor, $class, getclass(iif(count($array)%2 == 1 and $array[0] == $title, 1, 0)))."\" nowrap=\"nowrap\">$title</td>\n";
	}
	echo "	</tr>\n";
}

// ############################################################################
function inputfield($title, $fieldname, $value = '', $size = '35', $extra = '', $html = true, $addID = true, $isPass = false) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"70%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\" nowrap=\"nowrap\"><input type=\"".iif($isPass, 'password', 'text')."\" class=\"bginput\" name=\"$fieldname\" value=\"".iif($html, htmlspecialchars($value), $value)."\" size=\"$size\"".iif($addID, ' id="'.reform_field_name($fieldname).'"')." />$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function filefield($title, $fieldname, $size = '25', $extra = '', $addID = true) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"70%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\"><input type=\"file\" class=\"bginput\" name=\"$fieldname\" size=\"$size\"".iif($addID, ' id="'.reform_field_name($fieldname).'"')." />$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function textarea($title, $fieldname, $value = '', $rows = '7', $cols = '35', $html = true) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"70%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\"><textarea name=\"$fieldname\" cols=\"$cols\" rows=\"$rows\" id=\"".reform_field_name($fieldname)."\">".iif($html, htmlspecialchars($value), $value)."</textarea></td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function selectbox($title, $fieldname, $array, $selected = -1, $showline = false, $extra = '') {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"70%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\">\n";
	echo "		<select name=\"$fieldname\" id=\"".reform_field_name($fieldname)."\">\n";
	if ($showline) {
		echo "			<option value=\"-1\"".iif($selected == -1, ' selected="selected"').">----------------------------------</option>\n";
	}
	foreach ($array as $value => $name) {
		echo "			<option value=\"$value\"".iif($selected == $value, ' selected="selected"').">$name</option>\n";
	}
	echo "		</select>$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function tableselect($title, $name, $tablename, $selected = -1, $where = '1 = 1', $showline = false, $extra = '') {
	global $DB_site;

	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td width=\"70%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\">\n";
	echo "		<select name=\"$name\"id=\"".reform_field_name($fieldname)."\">\n";
	if ($showline) {
		echo "			<option value=\"-1\"".iif($selected == -1, ' selected="selected"').">----------------------------------</option>\n";
	}
	$rows = $DB_site->query("SELECT * FROM $tablename WHERE $where");
	while ($row = $DB_site->fetch_array($rows)) {
		echo "			<option value=\"".$row[$tablename.'id']."\"".iif($selected == $row[$tablename.'id'], ' selected="selected"').">$row[title]</option>\n";
	}
	echo "		</select>$extra</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function yesno($title, $fieldname, $default = 1) {
	$thisclass = getclass();
	$thisname = md5(microtime());
	echo "	<tr class=\"$thisclass\">\n";
	echo "		<td width=\"70%\" valign=\"top\">$title</td>\n";
	echo "		<td width=\"30%\"><input class=\"radio_$thisclass\" type=\"radio\" name=\"$fieldname\" id=\"$thisname"."yes\" value=\"1\"".iif($default, ' checked="checked"')." /> <label for=\"$thisname"."yes\">Yes</label>&nbsp;&nbsp;&nbsp;<input class=\"radio_$thisclass\" type=\"radio\" name=\"$fieldname\" id=\"$thisname"."no\" value=\"0\"".iif(!$default, ' checked="checked"')." /> <label for=\"$thisname"."no\">No</label></td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function hiddenfield($fieldname, $value) {
	echo "	<input type=\"hidden\" name=\"$fieldname\" value=\"$value\" />\n";
}

// ############################################################################
function textrow($text, $colspan = 2, $center = false) {
	echo "	<tr class=\"".getclass()."\">\n";
	echo "		<td colspan=\"$colspan\">".iif($center, "<center>$text</center>", $text)."</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function emptyrow($colspan = 2) {
	echo "	<tr class=\"thead\">\n";
	echo "		<td colspan=\"$colspan\">&nbsp;</td>\n";
	echo "	</tr>\n";
}

// ############################################################################
function paginate($table, $link, $where = '') {
	global $limitlower, $limitupper, $totalitems, $DB_site, $pagenumber, $perpage;

	if (intme($perpage) < 1) {
		$perpage = 30;
	}
	if (intme($page) < 1) {
		$page = 1;
	}
	$totalitems = $DB_site->get_field("
		SELECT COUNT(*) AS count
		FROM $table $where
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
$classcount = 1;
function getclass($reset = false) {
	global $classcount;

	if (!$reset) {
		$classcount++;
	}

	if ($classcount % 2 == 0) {
		return 'firstalt';
	} else {
		return 'secondalt';
	}
}

?>