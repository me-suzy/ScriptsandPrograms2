<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: functions.php,v $
// | $Date: 2002/11/12 14:02:09 $
// | $Revision: 1.88 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Implodes an array, but recursively
function implode_headers_array($sep, $array, $add = '') {
	$string = '';
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$string .= implode_headers_array($sep, $value, iif(!empty($add), $add, $key));
		} else {
			$string .= iif(!empty($add), ucwords("$add: "), ucwords("$key: ")).$value.$sep;
		}
	}

	return substr($string, 0, -strlen($sep));
}

// ############################################################################
// Returns a representation of $array in PHP code
// (basically like var_export() but only covers arrays)
function export_array($array, $foo = true) {
    $code = "array(";
    foreach ($array as $key => $value) {
        $code .= "\n\t'".str_replace('\'', '\\\'', $key)."' => ";
		if (is_array($value)) {
			$code .= export_array($value);
		} else {
			$code .= "'".str_replace('\'', '\\\'', $value)."'";
		}
		$code .= ",";
    }
    $code .= "\n)";

    return $code;
}

// ############################################################################
// Validates an email address
function is_email($address) {
	if (preg_match('#^[a-z0-9]+([^[:space:]]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,6}$#i'	, $address)) {
		return true;
	} else {
		return false;
	}
}

// ############################################################################
// This function returns the integer value of $number, but also changes the
// original variable so we only need to call it once
function intme(&$number) {
	return $number = intval($number);
}

// ############################################################################
// You should be able to figure this one out...
function getop($name) {
	return $GLOBALS['_options']["$name"];
}

// ############################################################################
// Adds a header line to $advheaders, for reading emails
function show_header($headername, $headerinfo) {
	global $advheaders, $afterattach, $count;

	switch (substr($headername, 0, 3)) {
		case 'fro':
		case 'sub':
		case 'to':
		case 'cc':
			return;
	}

	if (is_array($headerinfo)) {
		foreach ($headerinfo as $headerinfo2) {
			show_header($headername, $headerinfo2);
		}
	} else {
		if ($count++ % 2 == 0) {
			$headerbgcolor = $afterattach['first'];
		} else {
			$headerbgcolor = $afterattach['second'];
		}
		$headername = ucwords($headername);
		$headerinfo = nl2br(htmlspecialchars($headerinfo));
		eval(makeeval('advheaders', 'read_header', 1));
	}
}

// ############################################################################
// Shows an "Access Denied" screen
function access_denied() {
	eval(makeerror('error_accessdenied'));
	exit;
}

// ############################################################################
// Adds the checked attribute to the right radio button
function radio_onoff($option) {
	$optionon = $option.'on';
	$optionoff = $option.'off';
	global $hiveuser, $$optionon, $$optionoff;

	$$optionon = '';
	$$optionoff = '';
	if ($hiveuser["$option"] > 0) {
		$$optionon = 'checked="checked"';
	} else {
		$$optionoff = 'checked="checked"';
	}
}

// ############################################################################
// Updates the $hiveuser[options] bitfield, adding or removing bits from it
function update_options($onoff, $bit, $greater = false) {
	global $hiveuser;

	if ($greater) {
		$onoff = (bool) ($onoff > 0);
	}

	if ($onoff and !($hiveuser['options'] & $bit)) {
		$hiveuser['options'] += $bit;
	} elseif(!$onoff and $hiveuser['options'] & $bit) {
		$hiveuser['options'] -= $bit;
	}
}

// ############################################################################
// Welcomes you with a warm "Please log in" screen
function show_login() {
	global $_GET, $_POST, $_FILES, $_SERVER, $do, $hiveuser, $ $css, $header, $footer, $youarehere, $skin, $appname;

	if (infile('user.lostpw.php')) {
		return;
	}

	$skin = sort_skin();

	$_getvars = htmlspecialchars(serialize($_GET));
	$_postvars = htmlspecialchars(serialize($_POST));

	if (!INADMIN) {
		eval(makeeval('header'));
		eval(makeeval('footer'));
		eval(makeeval('css'));
		$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Log In';
		eval(makeeval('echo', 'login'));
	} else {
		cp_header('', false, false);
		echo '<div align="center">';
		startform($_SERVER['PHP_SELF']);
		starttable('You are not logged in as a valid administrator', '400');
		textrow("<br />Please login to proceed:<br />&nbsp;\n\t\t<table cellspacing=\"0\" cellpadding=\"2\" style=\"border-width: 0px;\">\n\t\t\t<tr>\n\t\t\t\t<td><input type=\"text\" class=\"bginput\" name=\"username\" value=\"".htmlspecialchars($hiveuser['username'])."\" /><br /><font size=\"1\">Username</font></td>\n\t\t\t\t<td><input type=\"password\" class=\"bginput\" name=\"password\" /><br /><font size=\"1\">Password</font></td>\n\t\t\t</tr>\n\t\t</table>", 2, true);
		hiddenfield('login', '1');
		hiddenfield('_getvars', $_getvars);
		hiddenfield('_postvars', $_postvars);
		endform('Login');
		endtable();
		echo '</div>';
		cp_footer(false);
		exit;
	}
}

// ############################################################################
// Logs the user in
function log_user_in($username, $password, $showerror = true) {
	global $DB_site, $_POST, $toregister, $skin;

	$error = '';
	$toregister = array();
	$hiveuser = getuserinfo($username);

	if (!$skin) {
		$skin = sort_skin();
	}

	if (!$hiveuser) {
		if (!INADMIN) {
			$error = 'error_wrong_username';
		} else {
			$error = "The account name you have entered ($username) doesn't exist in our records. Please go back and try again.";
		}
	} elseif($hiveuser['password'] != md5($password)) {
		if (!INADMIN) {
			$error = 'error_wrong_password';
		} else {
			$error = 'The password you have entered is wrong. Please go back and try again.';
		}
	} else {
		// Success, register session vars
		$toregister['userid'] = $hiveuser['userid'];
		$toregister['ipaddress'] = md5(IPADDRESS);

		// If the user wants to stay logged in for a longer amount of time
		if ($_POST['staylogged'] == 'days') {
			hivecookie(session_name(), session_id(), TIMENOW + (60 * 60 * 24 * intval($_POST['days'])));
			$toregister['staylogged'] = intval($_POST['days']);
		} elseif ($_POST['staylogged'] == 'forever') {
			// Forever means 1 year
			hivecookie(session_name(), session_id(), TIMENOW + (60 * 60 * 24 * 365));
			$toregister['staylogged'] = 365;
		} else {
			$toregister['staylogged'] = 0;
		}

		// Mark this as an admin session
		if (INADMIN) {
			$toregister['inadmin'] = true;
		}

		// Empty trash can if needed
		if ($hiveuser['emptybin'] == USER_EMPTYBINONEXIT) {
			emptyfolder('-3', 1);
		}
	}

	if (!empty($error)) {
		if ($showerror) {
			if (!INADMIN) {
				eval(makeerror($error));
			} else {
				cp_header('', false, false);
				echo '<div align="center">';
				echo '<br />';
				cp_error($error, false, false);
				echo '</div>';
				cp_footer(false);
				exit;
			}
		}
		return false;
	} else {
		return true;
	}
}

// ############################################################################
// Gets user information
function getuserinfo($useridname) {
	global $DB_site, $_groupbits, $_SERVER;

	// Get user info
	$hiveuser = $DB_site->query_first('
		SELECT *
		FROM user
		LEFT JOIN usergroup USING (usergroupid)
		WHERE '.iif(is_numeric($useridname), 'userid = '.intval($useridname), 'username = "'.addslashes($useridname).'"').'
	');
	if (!$hiveuser) {
		return false;
	}

	// Columns for folder view
	$hiveuser['cols'] = unserialize($hiveuser['cols']);

	// Usergroup permissions
	foreach ($_groupbits as $conname => $devnul) {
		$permname = strtolower(substr($conname, 6));
		$hiveuser["$permname"] = $hiveuser['perms'] & constant($conname);
	}

	// Options
	$hiveuser = decode_user_options($hiveuser);

	return $hiveuser;
}

// ############################################################################
// Decodes the user options field and returns an updated array of the user info
function decode_user_options($hiveuser) {
	global $_SERVER;

	// User options
	$hiveuser['usebghigh'] = (int) ($hiveuser['options'] & USER_USEBGHIGH);
	$hiveuser['showhtml'] = (int) ($hiveuser['options'] & USER_SHOWHTML);
	$hiveuser['wysiwyg'] = (int) ($hiveuser['options'] & USER_WYSIWYG);
	$hiveuser['requestread'] = (int) ($hiveuser['options'] & USER_REQUESTREAD);
	$hiveuser['savecopy'] = (int) ($hiveuser['options'] & USER_SAVECOPY);
	$hiveuser['addrecips'] = (int) ($hiveuser['options'] & USER_ADDRECIPS);
	$hiveuser['includeorig'] = (int) ($hiveuser['options'] & USER_INCLUDEORIG);
	$hiveuser['showallheaders'] = (int) ($hiveuser['options'] & USER_SHOWALLHEADERS);
	$hiveuser['showfoldertab'] = (int) ($hiveuser['options'] & USER_SHOWFOLDERTAB);
	$hiveuser['autoaddsig'] = (int) ($hiveuser['options'] & USER_AUTOADDSIG);
	$hiveuser['playsound'] = (int) ($hiveuser['options'] & USER_PLAYSOUND);
	$hiveuser['dontaddsigonreply'] = (int) ($hiveuser['options'] & USER_DONTADDSIGONREPLY);
	$hiveuser['showtopbox'] = (int) ($hiveuser['options'] & USER_SHOWTOPBOX);
	$hiveuser['fixdst'] = (int) ($hiveuser['options'] & USER_FIXDST);

	// Only IE users get to use the WYSIWYG editor
	$hiveuser['cansendhtml'] = (int) ($hiveuser['cansendhtml'] and strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE') and !strstr($_SERVER['HTTP_USER_AGENT'], 'Opera')); // /me kicks Opera
	$hiveuser['wysiwyg'] = (int) ($hiveuser['wysiwyg'] and $hiveuser['cansendhtml']);

	return $hiveuser;
}

// ############################################################################
// Fetches all data from $tablename and stores it in $data with $fieldname
// being the key
function table_to_array($tablename, $fieldname, $where = '1 = 1', $join = '', $onlyfield = '') {
	global $DB_site;

	$result = $DB_site->query("SELECT * FROM $tablename $join WHERE $where");
	for ($data = array(); $row = $DB_site->fetch_array($result); $data["$row[$fieldname]"] = iif(!empty($onlyfield), $row["$onlyfield"], $row));
	return $data;
}

// ############################################################################
// Sets $variable to $defvalue if it's not set or empty
function default_var(&$variable, $defvalue) {
	if (!isset($variable)) {
		$variable = $defvalue;
	}
}

// ############################################################################
// Sets a cookie... believe it or not
function hivecookie($cookiename, $value = '', $permanent = true) {
	global $cookiepath, $cookiedomain, $_SERVER;

	if ($permanent) {
		$expire = TIMENOW + (60*60*24*365);
	} else {
		$expire = 0;
	}

	if ($_SERVER['SERVER_PORT'] == '443') {
		$secure = true;
	} else {
		$secure = false;
	}

	setcookie($cookiename, $value, $expire, $cookiepath, $cookiedomain, $secure);
}

// ############################################################################
// Returns $true if $eval is true, $false if it is false
function iif($eval, $true, $false = '') {
	return (($eval) ? ($true) : ($false));
}

// ############################################################################
// Adds <a> tags around URLs and email addresses
function addlinks($text) {
	$find = array(
		"/([^]_a-z0-9-=\"'\/])((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\^\$!`\"'\|\[\]\{\}<>]*)/si",
		"/^((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\^\$!`\"'\|\[\]\{\}<>]*)/si",
		"/([ \n\r\t])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4}))/si",
		"/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4}))/si"
	);
	$replace = array(
		'$1<a href="$2$4" target="_parent">$2$4</a>',
		'<a href="$1$3" target="_parent">$1$3</a>',
		'$1<a href="compose.email.php?email=$2" target="_parent">$2</a>',
		'<a href="compose.email.php?email=$0" target="_parent">$0</a>'
	);

	return preg_replace($find, $replace, $text);
}

// ############################################################################
// Runs all the shutdown queries we have registered
function shutdown_queries() {
	global $DB_site;

	if (is_array($DB_site->shutdown)) {
		foreach ($DB_site->shutdown as $query_string) {
			$DB_site->query($query_string);
		}
	}
}
if (!defined('NOSHUTDOWNFUNCS')) {
	register_shutdown_function('shutdown_queries');
}

// ############################################################################
// Create page navigation... used a lot
function getpagenav($results, $address) {
	global $perpage, $pagenumber, $pagenavpages;

	if ($results <= $perpage) {
		return '';
	}
	$totalpages = ceil($results / $perpage);

	if ($pagenumber > 1) {
		$prevpage = $pagenumber - 1;
		if (INADMIN) {
			$prevlink = " <a href=\"$address&pagenumber=$prevpage\" title=\"previous page\"><span class=\"theadlink\" style=\"text-decoration: underline;\">&laquo;</span></a> ";
		} else {
			eval(makeeval('prevlink', 'pagenav_prevlink'));
		}
	} else {
		$prevlink = '';
	}
	if ($pagenumber < $totalpages) {
		$nextpage = $pagenumber + 1;
		if (INADMIN) {
			$nextlink = "<a href=\"$address&pagenumber=$nextpage\" title=\"next page\"><span class=\"theadlink\" style=\"text-decoration: underline;\">&raquo;</span></a>";
		} else {
			eval(makeeval('nextlink', 'pagenav_nextlink'));
		}
	} else {
		$nextlink = '';
	}

	$pagenavpages = 3;

	while ($curpage++ < $totalpages) {
		if (($curpage <= ($pagenumber - $pagenavpages) or $curpage >= ($pagenumber + $pagenavpages)) and $pagenavpages != 0) {
			if ($curpage == 1) {
				if (INADMIN) {
					$firstlink = " <a href=\"$address&pagenumber=$curpage\" title=\"first page\"><span class=\"theadlink\" style=\"text-decoration: underline;\">&laquo; First</span></a> ... ";
				} else {
					eval(makeeval('firstlink', 'pagenav_firstlink'));
				}
			}
		    if ($curpage == $totalpages) {
				if (INADMIN) {
					$lastlink = "... <a href=\"$address&pagenumber=$curpage\" title=\"last page\"><span class=\"theadlink\" style=\"text-decoration: underline;\">Last &raquo;</span></a>";
				} else {
					eval(makeeval('lastlink', 'pagenav_lastlink'));
				}
			}
		} else {
			if ($curpage == $pagenumber) {
				eval(makeeval('pagenav', 'pagenav_curpage', true));
			} else {
				if (INADMIN) {
					$pagenav .= " <a href=\"$address&pagenumber=$curpage\"><span class=\"theadlink\" style=\"text-decoration: underline;\">$curpage</span></a> ";
				} else {
					eval(makeeval('pagenav', 'pagenav_pagelink', true));
				}
			}
		}
	}

	eval(makeeval('pagenav'));
	return $pagenav;
}

// ############################################################################
// Gets the maximum size we can upload through POST forms
function get_max_upload() {
	if (!ini_get('file_uploads')) {
		return false;
	}
	$upload_max_filesize = get_real_size(ini_get('upload_max_filesize'));
	$post_max_size = get_real_size(ini_get('post_max_size'));
	$memory_limit = round(get_real_size(ini_get('memory_limit')) / 2);
	if ($post_max_size < $upload_max_filesize) {
		$max = $post_max_size;
	} else {
		$max = $upload_max_filesize;
	}
	if (!empty($memory_limit) and $memory_limit < $max) {
		$max = $memory_limit;
	}
	return $max;
}
function get_real_size($size) {
	if (empty($size)) {
		return 0;
	}
	$scan['MB'] = 1048576;
	$scan['M'] = 1048576;
	$scan['KB'] = 1024;
	$scan['K'] = 1024;
	foreach ($scan as $name => $value) {
		if (strlen($size) > strlen($name) and substr($size, strlen($size) - strlen($name)) == $name) {
			$size = substr($size, 0, strlen($size) - strlen($name)) * $value;
			break;
		}
	}
	return $size;
}

// ############################################################################
// Checks if the file we are currently viewing is $filename
function infile($filename) {
	global $_SERVER;

	return (substr(basename($_SERVER['PHP_SELF']), 0, strlen($filename)) == $filename);
}

// ############################################################################
// Formats timestamps
function hivedate($timestamp = TIMENOW, $format = false, $timezoneoffset = false) {
	global $hiveuser;

	if ($format === false) {
		$format = getop('dateformat');
	}
	if ($timezoneoffset === false) {
		$timezoneoffset = $hiveuser['timezone'];
	}

	return date($format, $timestamp + ($timezoneoffset - getop('timeoffset')) * 3600);
}

// ############################################################################
// Gets the extension of $filename
function getextension($filename) {
	return substr(strrchr($filename, '.'), 1);
}

// ############################################################################
// Create a mail bit for $mail
function makemailbit($mail, $templatename = 'mailbit') {
	global $hiveuser, $rowjsbits, $skin, $sortby, $markallbg, $current;

	// Highlight the right column
	$bgcolors = array(
		'attach' => 'normal',
		'subject' => 'normal',
		'name' => 'normal',
		'dateline' => 'normal',
		'priority' => 'normal',
		'size' => 'normal',
	);
	switch ($sortby) {
		case 'attach':
		case 'subject':
		case 'name':
		case 'dateline':
		case 'priority':
		case 'size':
			$bgcolors["$sortby"] = 'high';
			break;
	}

	// Icon
	$mail['image'] = 'mail';

	// Fix the date...
	$mail['date'] = hivedate($mail['dateline']);
	$mail['time'] = hivedate($mail['dateline'], getop('timeformat'));

	// The sender's informationn...
	$mail['fromname'] = $mail['name'];
	$mail['fromemail'] = $mail['email'];
	if (empty($mail['fromname'])) {
		if (!empty($mail['fromemail'])) {
			$mail['fromname'] = $mail['fromemail'];
		} else {
			$mail['fromname'] = '&nbsp;';
		}
	}


	// Fix the subject
	while (substr($mail['subject'], -5) == '; Re:') {
		$mail['subject'] = 'Re: '.substr($mail['subject'], 0, -5);
	}
	while (substr($mail['subject'], -5) == '; Fw:') {
		$mail['subject'] = 'Fw: '.substr($mail['subject'], 0, -5);
	}

	// Flagged?
	if ($mail['status'] & MAIL_FLAGGED) {
		$mail['isflagged'] = 1;
		$mail['subject'] = '<span style="color: '.$skin['highcolor'].';">'.$mail['subject'].'</span>';
		$mail['linkstyle'] = ' style="color: '.$skin['highcolor'].';"';
	} else {
		$mail['isflagged'] = 0;
		$mail['linkstyle'] = '';
	}

	// Replied or forwarded?
	if ($mail['status'] & MAIL_REPLIED) {
		$mail['image'] .= '_replied';
	} elseif ($mail['status'] & MAIL_FORWARDED) {
		$mail['image'] .= '_forwarded';
	}

	// Old or new?
	if ($mail['status'] & MAIL_READ) {
		$mail['unreadstyle'] = '';
	} else {
		$mail['image'] .= '_new';
		$mail['unreadstyle'] = 'style="font-weight: bold;" ';
	}

	// Attachments Clip...
	if ($mail['attach'] > 0) {
		$mail['attach'] = '<img src="'.$skin['images'].'/paperclip.gif" alt="This message has '.$mail['attach'].' attachments" />';
	} else {
		$mail['attach'] = '&nbsp;';
	}

	// Priority...
	if ($mail['priority'] == 5) {
		$mail['priority'] = '<img src="'.$skin['images'].'/prio_high.gif" alt="This message is high priority" />';
	} elseif ($mail['priority'] == 1) {
		$mail['priority'] = '<img src="'.$skin['images'].'/prio_low.gif" alt="This message is low priority" />';
	} else {
		$mail['priority'] = '&nbsp;';
	}

	// Add to row array
	$rowjsbits .= 'rows['.($current-1)."] = $mail[messageid];\n";

	// Size
	$mail['kbsize'] = ceil($mail['size'] / 1024);

	// This is for the check all box
	$markallbg .= "getElement('row$mail[messageid]').className = ";

	// Link
	if ($mail['fromname'] != $mail['fromemail']) {
		$mail['link'] = urlencode("$mail[fromname] <$mail[fromemail]>");
	} else {
		$mail['link'] = urlencode($mail['fromemail']);
	}

	// Custom columns
	$columns = '';
	foreach ($hiveuser['cols'] as $column) {
		eval(makeeval('columns', "mailbit_$column", 1));
	}

	// Parse the template and return it
	eval(makeeval('mailbit', $templatename));
	return $mailbit;
}

// ############################################################################
// Takes a To or CC list and returns a formatted version of it
function decodelist($list, $addbr = false) {
	$array = explode(' ', str_replace(array('"', "'"), '', $list));

	$total = count($array);
	$last = 0;
	$return = '';
	for ($i = 0; $i < $total; $i++) {
		if (preg_match('#([-.a-z0-9_]+@[-.a-z0-9_)]+)#', $array["$i"], $getemail)) {
			if (!empty($output)) {
				$return .= "<br />\n";
			}
			for ($j = $last; $j < $i; $j++) {
				$return .= "$array[$j] ";
			}
			$return .= iif($last != $i, '(')."<a href=\"compose.email.php?email=".urlencode($getemail[1])."\">$getemail[1]</a>".iif($last != $i, ')').iif($addbr, "<br />\n", '; ');
			$last = $i + 1;
		}
	}

	return substr($return, 0, -2);
}

// ############################################################################
// Kind of the other way around
function encodelist($list) {
	$array = explode(' ', str_replace(array('"', "'"), '', $list));

	$total = count($array);
	$last = 0;
	$return = '';
	for ($i = 0; $i < $total; $i++) {
		if (preg_match('#([-.a-z0-9_]+@[-.a-z0-9_)]+)#', $array["$i"], $getemail)) {
			if ($j != $last - 1 and $last < $i) {
				$return .= '"';
				for ($j = $last; $j < $i; $j++) {
					$return .= addslashes("$array[$j] ");
				}
				$return = trim($return).'"';
				$return .= " <$getemail[1]>, ";
			} else {
				$return .= "$getemail[1], ";
			}
			$last = $i + 1;
		}
	}

	return substr($return, 0, -2);
}

// ############################################################################
// Function to create the navigation bar
function makemailnav($selected) {
	global $headimgs;
	$headimgs = array(
		1 => '',
		2 => '',
		3 => '',
		4 => '',
		5 => '',
	);
	$headimgs[$selected] = '_high';
}

// ############################################################################
// Function that makes an "Invalid $idname specified" error
function invalid($idname) {
	global $hiveuser;

	eval(makeerror('error_invalidid'));
}

// ############################################################################
// Used in forms to return 1 or 0 and nothing other than that
// 1 month later: This is so fucking stupid
function formcheck($value) {
	return (($value == false) ? (0) : (1));
}

// ############################################################################
// Gets information for $name and $id, also matching user ID
function getinfo($name, &$id, $verifyonly = false, $showerror = true) {
	global $DB_site, $hiveuser;

	switch ($name) {
		case 'setting':
		case 'settinggroup':
		case 'skin':
		case 'template':
		case 'templategroup':
		case 'templateset':
		case 'usergroup':
		case 'user':
			$checkuser = false;
			break;
		default:
			$checkuser = true;
	}

	if (!isset($id)) {
		$id = intval($id);
		if ($showerror) {
			if (INADMIN) {
				cp_error("No $name specified.");
			} else {
				eval(makeerror('error_noid'));
			}
		} else {
			return false;
		}
	} else {
		$id = intval($id);
		if (!$verifyonly) {
			$selid = '*';
		} else {
			$selid = $name . 'id';
		}
		$check = $DB_site->query_first("
			SELECT $selid
			FROM $name
			WHERE {$name}id = $id".iif($checkuser, " AND userid = $hiveuser[userid]")."
		");
		if (!$check) {
			if ($showerror) {
				if (INADMIN) {
					cp_error("Invalid $name specified.");
				} else {
					eval(makeerror('error_invalid'));
				}
			} else {
				return false;
			}
		} else {
			if ($verifyonly) {
				return $check["$selid"];
			} else {
				return $check;
			}
		}
	}
}

// ############################################################################
// Empties a folder either by deleting its messages or moving them to the Trash Can
// (Based on $fulldelete value)
function emptyfolder($folderid, $fulldelete) {
	global $DB_site, $hiveuser;

	// Either delete all messages or move them to the Trash Can
	$DB_site->query(
		iif($fulldelete, 'DELETE FROM message', 'UPDATE message SET folderid = -3')."
		WHERE folderid = $folderid AND userid = $hiveuser[userid]
	");

	// Update the folder's message count
	if ($folderid > 0) {
		$DB_site->query("
			UPDATE folder SET msgcount = 0
			WHERE folderid = $folderid AND userid = $hiveuser[userid]
		");
	}
}

// ############################################################################
// Returns a formatted version of $message
function messageparse($message, $html = true, $script = true) {
	if ($script) {
		$message = preg_replace("/<script[^>]*>[^<]+<\/script[^>]*>/is", '', $message);
	}
	if ($html) {
		$message = nl2br(addlinks($message));
	}

	return $message;
}

?>