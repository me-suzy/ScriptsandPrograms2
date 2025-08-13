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
// | $RCSfile: functions.php,v $ - $Revision: 1.155 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Takes a list of stuff (banned emails, ignored addresses, etc.) and returns
// an array of regular expressions that can be matched against. The reason we
// do this is because large lists will cause regex errors and this solves it.
function verify_domain($domain) {
	if (!array_contains($domain, getop('domainnames'))) {
		return getop('domainname');
	} else {
		return $domain;
	}
}

// ############################################################################
// Takes a list of stuff (banned emails, ignored addresses, etc.) and returns
// an array of regular expressions that can be matched against. The reason we
// do this is because large lists will cause regex errors and this solves it.
function parse_regex_list($list, $separate = '\s') {
	if (strlen($list) > 1500) {
		$list = preg_split('#(.{1500,1600})\s#', trim($list), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	} else {
		$list = array(trim($list));
	}
	$regex = array();
	foreach ($list as $item) {
		$regex[] = '/^(('.preg_replace('#'.$separate.'#', ')|(', str_replace('\*', '.*', preg_quote($item, '/'))).'))$/i';
	}
	return $regex;
}

// ############################################################################
// Sends welcome email to the user (places the mail directly in database)
// Please remember to include mime_functions.php before doing this!
function send_welcome($userid, $username, $domain, $realname) {
	global $DB_site;
	$appname = getop('appname');

	// Eval templates
	eval(makeevalsystem('body', 'signup_welcome_message'));
	eval(makeevalsystem('subject', 'signup_welcome_subject'));

	// Get the sender's email
	$fromemail = extract_email(getop('smtp_errorfrom'));
	$fromname = trim(substr(getop('smtp_errorfrom'), 0, strrpos(getop('smtp_errorfrom'), ' ') - strlen(getop('smtp_errorfrom'))), " \r\n\t\0\x0b\"'");
	if (empty($fromname)) {
		$fromname = $fromemail;
	}

	// Generate headers
	$mime_boundary = '==Multipart_Boundary_x'.md5(TIMENOW).'x';
	$headers  =	'Reply-To: <'.$fromemail.'>'.CRLF.									// Reply-To
				'From: "'.$fromname.
				'" <'.$fromemail.'>'.CRLF.											// From who
				//'To: '.$to.CRLF.													// To email
				'MIME-Version: 1.0'.CRLF.											// Mime version
				'X-Mailer: HiveMail '.HIVEVERSION.CRLF.								// The mailer
				'Date: '.date('r').CRLF.											// The date
				'X-Priority: 3'.CRLF.												// Priority
				'Content-Type: multipart/mixed;'.CRLF.								// Content type
				'              boundary="'.$mime_boundary.'"';						// Boundary

	// And the message itself
	$fullbody = 'This is a multi-part message in MIME format.'.CRLF.CRLF.'--'.$mime_boundary.CRLF.
				'Content-Type: text/plain; charset="iso-8859-1"'.CRLF.
				'Content-Transfer-Encoding: 7bit'.CRLF.CRLF.
				$body.CRLF.
				'--'.$mime_boundary.'--'.CRLF;

	// Add message to database
	$message = $headers."\n\n".$fullbody;
	$dirname = get_dirname();
	$filename = make_filename($dirname);
	insert_mail("(NULL, $userid, -1, ".TIMENOW.", '".addslashes($fromemail)."', '".addslashes($fromname)."', '".addslashes($subject)."', '".addslashes($body)."', '".addslashes($username.$domain)."', 0, 0, 0, '".addslashes(md5(microtime().IPADDRESS))."', '".((getop('flat_use')) ? ($dirname.'/'.$filename) : (addslashes($message)))."', 3, ".strlen($message).", 0, 0, '', '')");

	// Create the message file
	if (getop('flat_use')) {
		$filepath = getop('flat_path', true).'/'.$dirname.'/'.getop('flat_prefix').$filename.'.dat';
		if ($dirname != getop('flat_curfolder')) {
			mkdir(getop('flat_path', true).'/'.$dirname, 0777);
			chmod(getop('flat_path', true).'/'.$dirname, 0777);
		}
		writetofile($filepath, $message);
		chmod($filepath, 0777);
		$DB_site->query('
			INSERT INTO hive_messagefile
			SET filename = "'.addslashes($dirname.'/'.$filename).'", messages = 1
		');
		if ($dirname != getop('flat_curfolder')) {
			$DB_site->query('
				UPDATE hive_setting
				SET value = "'.addslashes($dirname).'"
				WHERE variable = "flat_curfolder"
			');
			$DB_site->query('
				UPDATE hive_setting
				SET value = 1
				WHERE variable = "flat_curcount"
			');
		} else {
			$DB_site->query('
				UPDATE hive_setting
				SET value = value + 1
				WHERE variable = "flat_curcount"
			');
		}
	}
}

// ############################################################################
// Applies the given rule to the user's mailbox
function apply_rule($rule, $folderids) {
	global $DB_site, $hiveuser, $_rules;

	// Extract the data
	list($condtype, $cond, $condextra) = split('~', $rule['cond']);
	$condsubject = substr($cond, 0, 1);
	$condhow = substr($cond, 1);
	if (($condpos = strrpos($rule['cond'], '~')) !== false) {
		$condextra = substr($rule['cond'], $condpos + 1);
	} else {
		$condextra = '';
	}
	list($action, $folderaction, $respondaction, $coloraction) = split('~', $rule['action']);

	// WHERE clause
	$where = "WHERE userid = $hiveuser[userid]";

	// Where folder is..
	if (!is_array($folderids)) {
		eval(makeerror('error_nofolderselected'));
	}
	if (!in_array('0', $folderids)) {
		$wherefolderidin = '0';
		foreach ($folderids as $folderid) {
			switch ($folderid) {
				case -1:
				case -2:
				case -3:
				case -4:
					$wherefolderidin .= ",$folderid";
					break;
				default:
					if (getinfo('folder', $folderid, true, false)) {
						$wherefolderidin .= ",$folderid";
					}
			}
		}
		if ($wherefolderidin == '0') {
			eval(makeerror('error_nofolderselected'));
		}
		$where .= " AND folderid IN ($wherefolderidin)";
	}

	// Decode conditions
	if ($condtype == 1) {
		switch ($condsubject) {
			case substr($_rules['conds']['emaileq'], 0, 1):
				$where .= ' AND email ';
				break;

			case substr($_rules['conds']['msgeq'], 0, 1):
				$where .= ' AND message ';
				break;

			case substr($_rules['conds']['recipseq'], 0, 1):
				$where .= ' AND recipients ';
				break;

			case substr($_rules['conds']['subjecteq'], 0, 1):
				$where .= ' AND subject ';
				break;
		}

		$condextra = str_replace(array('%', '_', '*'), array('\%', '\_', '%'), addslashes($condextra));
		switch ($condhow) {
			case substr($_rules['conds']['emaileq'], 1, 1):
				$where .= ' LIKE \''.$condextra.'\'';
				break;

			case substr($_rules['conds']['emailcon'], 1, 1):
				$where .= ' LIKE \'%'.$condextra.'%\'';
				break;

			case substr($_rules['conds']['emailnotcon'], 1, 1):
				$where .= ' NOT LIKE \'%'.$condextra.'%\'';
				break;

			case substr($_rules['conds']['emailstars'], 1, 1):
				$where .= ' LIKE \''.$condextra.'%\'';
				break;

			case substr($_rules['conds']['emailends'], 1, 1):
				$where .= ' LIKE \'%'.$condextra.'\'';
				break;
		}
	} else {
		$where .= ' AND popid = '.intval($condextra);
	}

	// SET clause
	$set = "SET userid = userid";

	// Decode actions
	$special = '';
	if ($action & $_rules['actions']['copy']) {
		$msgs = $DB_site->query("
			SELECT *
			FROM hive_message
			$where
		");
		$valuelist = '';
		$filenames = array();
		while ($msg = $DB_site->fetch_array($msgs)) {
			if ($action & $_rules['actions']['read'] and !($mail['status'] & MAIL_READ)) {
				$msg['status'] += MAIL_READ;
			}
			if ($action & $_rules['actions']['flag']) {
				$msg['flagged'] = 1;
			}
			if (!empty($valuelist)) {
				$valuelist .= ',';
			}
			$valuelist .= "(NULL, $msg[userid], $folderaction, $msg[dateline], '".addslashes($msg['email'])."', '".addslashes($msg['name'])."', '".addslashes($msg['subject'])."', '".addslashes($msg['message'])."', '".addslashes($msg['recipients'])."', $msg[attach], $msg[flagged], $msg[status], '".addslashes($msg['emailid'])."', '".addslashes($msg['source'])."', $msg[priority], $msg[size], $msg[popid], $msg[popsize], '".addslashes($msg['notes'])."', '".addslashes($msg['uniquestr']).", '".addslashes($msg['bgcolor'])."')";
			if (getop('flat_use')) {
				$filenames["$msg[source]"]++;
			}
		}
		$DB_site->query("
			INSERT INTO hive_message
			(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, flagged, status, emailid, source, priority, size, popid, popsize, notes, uniquestr, bgcolor)
			VALUES
			$valuelist
		");
		if (getop('flat_use')) {
			foreach ($filenames as $filename => $count) {
				$updates[$count][] = $filename;
			}
			foreach ($updates as $count => $filenames) {
				if (empty($filenames)) {
					continue;
				}
				$DB_site->query("
					UPDATE hive_messagefile
					SET messages = messages + $count
					WHERE filename IN ('".implode("', '", $filenames)."'
				");
			}
		}
	} else {
		if ($action & $_rules['actions']['delete']) {
			$set .= ', folderid = -3';
		}
		if ($action & $_rules['actions']['move']) {
			$set .= ", folderid = $folderaction";
		}
		if ($action & $_rules['actions']['flag']) {
			$set .= ', flagged = 1';
		}
		if ($action & $_rules['actions']['read']) {
			$set .= ', status = status + IF(status & '.MAIL_READ.', 0, '.MAIL_READ.')';
		}
		if ($action & $_rules['actions']['color']) {
			$set .= ', bgcolor = "'.addslashes($coloraction).'"';
		}
		$msgs = $DB_site->query("
			UPDATE hive_message
			$set
			$where
		");
	}
}

// ############################################################################
// Returns $array[$key] and unsets it; returns $nullRet if key doesn't exist
function array_extract(&$array, $key, $nullRet = '') {
	if (!isset($array[$key])) {
		return $nullRet;
	}
	$val = $array[$key];
	unset($array[$key]);
	return $val;
}

// ############################################################################
// Returns $text upto $length characters followed by three dots
function trimtext($text, $length) {
    global $vboptions;

	if (strlen($text) > $length) {
		$space = strrpos(substr($text, 0, $length ), ' ');
		$text = trim(substr($text, 0, iif($space === false, $length, $space))).'...';
	}
	return $text;
}

// ############################################################################
// Creates <option>'s for folders
function build_folder_select($folders, &$foundit, $parentid = 0, $selected = 0, $deep = 0) {
	$options = array();
	if (is_array($folders[$parentid])) {
		foreach ($folders[$parentid] as $folder) {
			$options[] = '<option value="'.$folder['folderid'].'"'.iif($selected == $folder['folderid'], ' selected="selected"').'>'.$folder['title'].'</option>';
			//$options[] = '<option value="'.$folder['folderid'].'"'.iif($selected == $folder['folderid'], ' selected="selected"').'>'.str_repeat('&nbsp; ', $deep).'- '.$folder['title'].'</option>';
			if ($selected == $folder['folderid']) {
				$foundit = true;
			}
			$options = array_merge($options, build_folder_select($folders, $foundit, $folder['folderid'], $selected, $deep + 1));
		}
	}
	return $options;
}

// ############################################################################
// For PHP < 4.2.0
if (!function_exists('ob_flush')) {
	function ob_flush() {
		ob_end_flush();
		ob_start();
	}
}
if (!function_exists('ob_clean')) {
	function ob_clean() {
		ob_end_clean();
		ob_start();
	}
}

// ############################################################################
// For PHP < 4.3.0
if (!function_exists('ob_get_clean')) {
	function ob_get_clean() {
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}

// ############################################################################
// Checks if the values of the $array are all empty
function array_empty($array) {
	foreach ($array as $value) {
		if (!empty($value)) {
			return false;
		}
	}
	return true;
}

// ############################################################################
// A version of in_array() that allows case-insensitive matching (by default!)
function array_contains($needle, $haystack, $strict = false, $matchcase = false) {
	if (!is_array($haystack)) {
		return false;
	}

	if (!$matchcase and is_string($needle)) {
		$needle = strtolower($needle);
	}
	foreach ($haystack as $element) {
		if (!$matchcase and is_string($needle)) {
			$element = strtolower($element);
		}
		if (($strict and $needle === $element) or (!$strict and $needle == $element)) {
			return true;
		}
	}

	return false;
}

// ############################################################################
// A version of array_contains() that uses binary sort to find the element
// Only use on sorted arrays!!!
function array_contains_binary($needle, &$haystack) {
	if (!is_array($haystack)) {
		return false;
	}

	$low = 0;
	$high = count($haystack);
	$needle = strtolower($needle);
	while ($low <= $high) {
		$mid = floor(($low + $high) / 2);
		if (strcasecmp($needle, $haystack[$mid]) == 0 ) {
			return true;
			break;
		} elseif (strcasecmp($needle, $haystack[$mid]) < 0 ) {
			$high = $mid - 1;
		} else {
			$low = $mid + 1;
		}
	}

	return false;
}

// ############################################################################
// Produce gzipped content
function gzipdata($output, $level = 1) {
	// Determine which encoding to use
	preg_match('#((x-)?gzip)#i', $_SERVER['HTTP_ACCEPT_ENCODING'], $findEnc);

	// Make sure we can or should go ahead with this
	if (!getop('gzip_use') or empty($findEnc) or headers_sent() or !function_exists('gzcompress') or !function_exists('crc32')) {
		return $output;
	}

	// Since I *KNOW* people will set this to 100 to get "good compression":
	if ($level > 9) {
		$level = 9;
	} elseif ($level < 0) {
		$level = 0;
	}

	// Set right encoding
	list($encoding) = $findEnc;
	header('Content-Encoding: '.$encoding);

	// Compress the data
	//$output = 'gzipped at level '.$level.' '.$output;
	return	"\x1f\x8b\x08\x00\x00\x00\x00\x00".
			substr(gzcompress($output, $level), 0, -4).
			pack('V', crc32($output)).
			pack('V', strlen($output));
}

// ############################################################################
// Returns a "unique" string in the length of $length characters
function uniquestring($length = 32) {
	return substr(md5(uniqid(mt_rand(), true)), 0, $length);
}

// ############################################################################
// Returns the number of times $needle appears in $array and the appropriate keys
// Stops after $maxmatches if it is different than 0
function array_advanced_search($needle, $array, $strict = false, $maxmatches = 0) {
	if (!is_array($array)) {
		return false;
	}

	$matches = 0;
	$keys = array();
	foreach ($array as $key => $value) {
		if ((!$strict and $value == $needle) or ($strict and $value === $needle)) {
			$matches++;
			$keys[] = $key;
			if ($maxmatches > 0 and $matches >= $minmatches) {
				break;
			}
		}
	}

	return array('matches' => $matches, 'keys' => $keys);
}

// ############################################################################
// In case the data was base64 encoded it decodes it first
function unserialize_base64($ser_data) {
	if ($data = unserialize($ser_data)) {
		return $data;
	} elseif ($data = unserialize(base64_decode($ser_data))) {
		return $data;
	} else {
		return false;
	}
}

// ############################################################################
// Creates a random string of letters and numbers
list($usec, $sec) = explode(' ', microtime());
mt_srand((float) $sec + ((float) $usec * 100000));
function rand_string($len = 8, $maxletters = 5) {
	$string = '';
	$ltrs = 0;
	for ($i = 0; $i < $len; $i++) {
		if (mt_rand(1, 2) == 2 and $ltrs++ < $maxletters) {
			$string .= chr(rand(97, 122));
		} else {
			$string .= mt_rand(0, 9);
		}
	}
	return $string;
}

// ############################################################################
// Takes $text and returns an email address from it
// Set $multiple to true to retrieve all found addresses
// Returns false if no addresses were found
function extract_email($text, $multiple = false, $allowOnlyDomain = false) {
	if ($allowOnlyDomain) {
		$pattern = '/(('.REGEX_EMAIL_USER.'@)?'.REGEX_EMAIL_DOMAIN.')/i';
	} else {
		$pattern = '/('.REGEX_EMAIL_USER.'@'.REGEX_EMAIL_DOMAIN.')/i';
	}
	if ($multiple) {
		if (preg_match_all($pattern, $text, $findemail) >= 1) {
			return $findemail[1];
		} else {
			return false;
		}
	} else {
		if (preg_match($pattern, $text, $findemail) == 1) {
			return $findemail[1];
		} else {
			return false;
		}
	}
}

// ############################################################################
// Returns the name part of the header, sets to $email if name is empty
function extract_name($text, $email = '') {
	$lastspace = strrpos($text, ' ');
	$name = trim(substr($text, 0, $lastspace - strlen($text)), " \r\n\t\0\x0b\"'");
	if (empty($name)) {
		$name = $email;
	}
	return $name;
}

// ############################################################################
// Replaces the value of $var1 with $var2 and vice-versa
function exchange(&$var1, &$var2) {
	$temp = $var1;
	$var1 = $var2;
	$var2 = $temp;
}

// ############################################################################
// Validates an email address
function is_email($address) {
	return (bool) preg_match('#^'.REGEX_EMAIL_USER.'@'.REGEX_EMAIL_DOMAIN.'$#i', $address);
}

// ############################################################################
// Returns a representation of $array in PHP code
// (basically like var_export() but only covers arrays)
function export_array($array, $level = 1) {
	if (!is_array($array)) {
		return $array;
	}

	$tabs = str_repeat("\t", $level);
    $code = "array(";
    foreach ($array as $key => $value) {
        $code .= "\n${tabs}'".str_replace("'","\'",$key)."' => ";
		if (is_array($value)) {
			$code .= export_array($value, $level+1);
		} else {
			$value = str_replace("\'","'",$value);
			$code .= "'".str_replace("'","\\'",$value)."'";
		}
		$code .= ",";
    }
    $code .= "\n${tabs})";

    return $code;
}

// ############################################################################
// Return the float value of $number, if possible
// I've had it with floatval() and doubleval(), argh!
function floatme(&$number) {
	if (function_exists('floatval')) {
		return ($number = floatval($number));
	} elseif (function_exists('doubleval')) {
		return ($number = doubleval($number));
	} else {
		return ($number = ($number + 0.0));
	}
}

// ############################################################################
// This function returns the integer value of $number, but also changes the
// original variable so we only need to call it once. Can take arrays too!
function intme(&$number, $allowarray = false, $allowrecursivearray = false) {
	if ($allowarray and is_array($number)) {
		foreach ($number as $key => $val) {
			intme($number[$key], $allowrecursivearray);
		}
		return $number;
	} else {
		return $number = intval($number);
	}
}

// ############################################################################
// You should be able to figure this one out...
function getop($name, $striptrailingslash = false) {
	if ($name == 'appurl') {
		$striptrailingslash = true;
	}
	while ($striptrailingslash and in_array(substr($GLOBALS['_options']["$name"], -1), array('/', '\\'))) {
		$GLOBALS['_options']["$name"] = substr($GLOBALS['_options']["$name"], 0, -1);
	}
	return $GLOBALS['_options']["$name"];
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
// Welcomes you with a warm "Please log in" screen
function show_login() {
	global $_options, $skinid, $cmd, $hiveuser, $css, $header, $footer, $youarehere, $skin, $appname, $domainname_options, $DB_site;

	if (infile('user.lostpw.php')) {
		return;
	}

	$skin = sort_skin();

	$_getvars = htmlchars(serialize($_GET));
	$_postvars = htmlchars(serialize($_POST));

	if (!INADMIN) {
		cachetemplates();
		// Skin select
		if (getop('skinonlogin')) {
			$groupid = getop('skinonlogingroup');
			$skinoptions = '';
			$skingroup = getinfo('usergroup', $groupid);
			$allskins = $DB_site->query("
				SELECT *
				FROM hive_skin
				WHERE skinid IN ($skingroup[allowedskins])
				ORDER BY title
			");
			while ($thisskin = $DB_site->fetch_array($allskins)) {
				$skinoptions .= "<option value=\"$thisskin[skinid]\">$thisskin[title]</option>\n";
			}
		}

		eval(makeeval('header'));
		eval(makeeval('footer'));
		eval(makeeval('css'));
		$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Log In';
		eval(makeeval('echo', 'login'));
	} else {
		define('CP_IGNORE_COOKIES', true);
		cp_header('');
		echo '<div align="center">';
		startform($_SERVER['PHP_SELF']);
		starttable('You are not logged in as a valid administrator', '400');
		textrow("<br />Please login to proceed:<br />&nbsp;\n\t\t<table cellspacing=\"0\" cellpadding=\"2\" style=\"border-width: 0px;\">\n\t\t\t<tr>\n\t\t\t\t<td><input type=\"text\" class=\"bginput\" name=\"username\" value=\"".htmlchars($hiveuser['username'])."\" /><br /><font size=\"1\">Username</font></td>\n\t\t\t\t<td><input type=\"password\" class=\"bginput\" name=\"password\" /><br /><font size=\"1\">Password</font></td>\n\t\t\t\t<td valign=\"top\"><input type=\"submit\" class=\"button\" value=\"Login\" /></td>\n\t\t\t</tr>\n\t\t</table>", 2, true);
		hiddenfield('login', '1');
		hiddenfield('_getvars', $_getvars);
		hiddenfield('_postvars', $_postvars);
		endform();
		endtable('&nbsp');
		echo '</div>';
		cp_footer();
		exit;
	}
}

// ############################################################################
// Fetches all data from $tablename and stores it in $data with $fieldname
// being the key
function table_to_array($tablename, $keyname, $where = '1 = 1', $onlyfield = array(), $join = '') {
	global $DB_site;

	if (!empty($onlyfield)) {
		if (is_array($onlyfield)) {
			$select = implode(', ', $onlyfield).', '.$keyname;
		} else {
			$select = "$onlyfield, $keyname";
		}
	} else {
		$select = '*';
	}

	$result = $DB_site->query("SELECT $select FROM hive_$tablename AS $tablename $join WHERE $where");
	for ($data = array(); $row = $DB_site->fetch_array($result); $data["$row[$keyname]"] = iif(!empty($onlyfield) and !is_array($onlyfield), $row["$onlyfield"], $row));
	return $data;
}

// ############################################################################
// Sets $variable to $defvalue if it's not set or $empty
function default_var(&$variable, $defvalue, $empty = '') {
	if ($variable == $empty) {
		$variable = $defvalue;
	}
}

// ############################################################################
// Sets a cookie... believe it or not
function hivecookie($cookiename, $value = '', $permanent = true) {
	if (is_numeric($permanent) and $permanent > TIMENOW) {
		$expire = $permanent;
	} elseif ($permanent) {
		$expire = TIMENOW + (60*60*24*365);
	} else {
		$expire = 0;
	}

	if (SHOWSQL) {
		return @setcookie($cookiename, $value, $expire, iif(getop('cookiepath'), getop('cookiepath'), '/'), iif(getop('cookiedomain'), getop('cookiedomain'), ''), $_SERVER['SERVER_PORT'] == '443');
	} else {
		return setcookie($cookiename, $value, $expire, iif(getop('cookiepath'), getop('cookiepath'), '/'), iif(getop('cookiedomain'), getop('cookiedomain'), ''), $_SERVER['SERVER_PORT'] == '443');
	}
}

// ############################################################################
// Returns $true if $eval is true, $false if it is false
function iif($eval, $true, $false = '') {
	return (($eval == true) ? ($true) : ($false));
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
function getpagenav($results, $address, $linkclass = true) {
	global $perpage, $pagenumber, $pagenavpages;

	if (intme($pagenumber) < 1) {
		$pagenumber = 1;
	}

	if ($results <= $perpage) {
		return '';
	}
	$totalpages = ceil($results / $perpage);

	if ($pagenumber > 1) {
		$prevpage = $pagenumber - 1;
		if (INADMIN) {
			$prevlink = " <a href=\"$address&pagenumber=$prevpage\" title=\"previous page\"><span".iif($linkclass, ' class="theadlink"')." style=\"text-decoration: underline;\">&laquo;</span></a> ";
		} else {
			eval(makeeval('prevlink', 'pagenav_prevlink'));
		}
	} else {
		$prevlink = '';
	}
	if ($pagenumber < $totalpages) {
		$nextpage = $pagenumber + 1;
		if (INADMIN) {
			$nextlink = "<a href=\"$address&pagenumber=$nextpage\" title=\"next page\"><span".iif($linkclass, ' class="theadlink"')." style=\"text-decoration: underline;\">&raquo;</span></a>";
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
					$firstlink = " <a href=\"$address&pagenumber=$curpage\" title=\"first page\"><span".iif($linkclass, ' class="theadlink"')." style=\"text-decoration: underline;\">&laquo; First</span></a> ... ";
				} else {
					eval(makeeval('firstlink', 'pagenav_firstlink'));
				}
			}
		    if ($curpage == $totalpages) {
				if (INADMIN) {
					$lastlink = "... <a href=\"$address&pagenumber=$curpage\" title=\"last page\"><span".iif($linkclass, ' class="theadlink"')." style=\"text-decoration: underline;\">Last &raquo;</span></a>";
				} else {
					eval(makeeval('lastlink', 'pagenav_lastlink'));
				}
			}
		} else {
			if ($curpage == $pagenumber) {
				eval(makeeval('pagenav', 'pagenav_curpage', true));
			} else {
				if (INADMIN) {
					$pagenav .= " <a href=\"$address&pagenumber=$curpage\"><span".iif($linkclass, ' class="theadlink"')." style=\"text-decoration: underline;\">$curpage</span></a> ";
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
// A version of htmlspecialchars() that allows unicode to function, can take an array of strings
function htmlchars($text) {
	if (is_array($text)) {
		foreach ($text as $key => $val) {
			$text[$key] = htmlchars($val);
		}
		return $text;
	} else {
		$text = preg_replace('#&((?!\#[0-9]+;)|(\#0*36))#si', '&amp;$1', $text);
		$text = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $text);
		return $text;
	}
}
function unhtmlchars($text) {
	if (is_array($text)) {
		foreach ($text as $key => $val) {
			$text[$key] = unhtmlchars($val);
		}
		return $text;
	} else {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($text, $trans_tbl);
	}
}

// ############################################################################
// Function to create navigation for options
function makeoptionnav($selected = '', $perline = 1) {
	global $DB_site, $hiveuser, $appname;

	$menuCodes = array(
		'personal' => true,
		'password' => $hiveuser['canchangepass'],
		'general' => true,
		'folderview' => true,
		'read' => true,
		'compose' => true,
		'folders' => $hiveuser['canfolder'],
		'signature' => true,
		'autoresponses' => true,
		'aliases' => $hiveuser['canalias'],
		'pop' => $hiveuser['canpop'],
		'rules' => $hiveuser['canrule'],
		'calendar' => $hiveuser['cancalendar'],
		'subscription' => ($DB_site->get_field('SELECT COUNT(*) AS count FROM hive_plan') > 0),
	);
	$menus = '<tr>';
	$i = 0;
	foreach ($menuCodes as $name => $doit) {
		if (!$doit) {
			continue;
		}

		if ($i != 0 and $i%$perline == 0) {
			$menus .= '</tr><tr>';
		}
		$cellType = 'high';
		if ($perline == 1) {
			$cellType .= 'Both';
		} elseif ($i%$perline == 0) {
			$cellType .= 'Left';
		} elseif ($i%$perline == $perline - 1) {
			$cellType .= 'Right';
		}
		$cellType .= 'Cell';
		$sel = ($selected == $name);
		eval(makeeval('menus', "options_menu_$name", true));
		if ($i%$perline != $perline - 1) {
			$menus .= '<td class="highCell"><span class="normalfont">&middot;</span></td>';
		}
		$i++;
	}
	if ($i != 1) {
		while ($i%$perline != 0) {
			$cellType = 'high';
			if ($i%$perline == 0) {
				$cellType .= 'Left';
			} elseif ($i%$perline == $perline - 1) {
				$cellType .= 'Right';
			}
			$cellType .= 'Cell';
			$menus .= '<td class="'.$cellType.'">&nbsp;</td>';
			if ($i%$perline != $perline - 1) {
				$menus .= '<td class="highCell"><span class="normalfont">&nbsp;</span></td>';
			}
			$i++;
		}
	}
	$menus .= '</tr>';

	return $menus;
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
		6 => '',
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
// Gets information for $name and $id, also matching user ID
function getinfo($name, &$id, $verifyonly = false, $showerror = true, $checkuser = null, $cache = true) {
	global $DB_site, $hiveuser;
	static $_data_cache = array('info' => array(), 'valid' => array());

	if ($checkuser === null) {
		switch ($name) {
			case 'adminlog':
			case 'announcement':
			case 'distlist':
			case 'cplink':
			case 'field':
			case 'eventlog':
			case 'iplog':
			case 'messagefile':
			case 'plan':
			case 'setting':
			case 'settinggroup':
			case 'skin':
			case 'template':
			case 'templategroup':
			case 'templateset':
			case 'user':
			case 'usergroup':
				$checkuser = false;
				break;
			default:
				$checkuser = true;
		}
	}

	// Don't take intval() out of the if-block please
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
			$dataarray = 'info';
		} else {
			$selid = $name.'id';
			$dataarray = 'valid';
		}

		if (isset($_data_cache["$dataarray"]["$name"][$id])) {
			$check = $_data_cache["$dataarray"]["$name"][$id];
		} else {
			$check = $DB_site->query_first("
				SELECT $selid
				FROM hive_$name
				WHERE {$name}id = $id".iif($checkuser, " AND userid = $hiveuser[userid]")."
			");
			$_data_cache["$dataarray"]["$name"][$id] = $check;
		}
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
			$id = $check[$name.'id'];
			if ($verifyonly) {
				return $check["$selid"];
			} else {
				return $check;
			}
		}
	}
}

// ############################################################################
// Makes sure $name isn't a forbidden name
function reserved_name($name) {
	global $DB_site;

	$reserved_names = getop('reservedtext');

	// Ban the central POP3 username
	if (getop('pop3_username')) {
		$pop3_username_parts = preg_split('#(@|\+|%)#', getop('pop3_username'));
		$reserved_names .= "\n$pop3_username_parts[0]";
	}
	// Ban distribution lists names
	$lists = $DB_site->query('SELECT toalias FROM hive_distlist');
	while ($list = $DB_site->fetch_array($lists)) {
		$reserved_names .= "\n$list[toalias]";
	}

	$reserved_names = str_replace('\*', '.*', preg_quote($reserved_names, '#'));
	$reserved_names = preg_replace('#\s#', '|', $reserved_names);
	$reserved_names = '#^('.$reserved_names.')$#i';
	return preg_match($reserved_names, $name);
}

// ############################################################################
// Log an event
function log_event($level = EVENT_NOTICE, $event = 1, $infoarray = array(), $dberror = true) {
	global $DB_site, $hiveuser;

	// Small hack to solve CP problems
	if ($event == 302 and INADMIN) {
		return;
	}

	// Silence DB errors if needed
	$DB_site->showerror = $dberror;

	// Don't need to count these queries
	$DB_site->skipcount = true;

	// Add event to log
	$debugarray = addslashes(serialize($infoarray));
	$DB_site->query("
		INSERT INTO hive_eventlog
		VALUES (NULL, ".TIMENOW.", ".iif($hiveuser['userid'], intme($hiveuser['userid']), 0).", $level, $event, '$debugarray')
	");

	// Clear old events
	if (getop('auto_clear_events') > 0) {
		$DB_site->query('DELETE FROM hive_eventlog WHERE dateline < '.(TIMENOW - (getop('auto_clear_events') * 86400)));
	}

	// Bring back DB errors and query count
	$DB_site->showerror = true;
	$DB_site->skipcount = false;
}

// ############################################################################
// Checks an IP against DNSbl
function dnsblcheck($ip) {
	$ipparts = explode('.', $ip);
	$checkip = implode('.', array_reverse($ipparts));
	$dnsbls = preg_split("#\r?\n#", getop('dnsbls'));
	foreach ($dnsbls as $dnsbl) {
		if (gethostbyname("$checkip.$dnsbl") != "$checkip.$dnsbl") {
			return $dnsbl;
		}
	}
	return false;
}

// ############################################################################
// Designed to stop people abusing forward/notify functions
function is_self($userinfo, $email) {
	if (is_array($userinfo['aliases'])) {
		$userinfo['aliases'] = implode(' ', $userinfo['aliases']);
	}
	$userinfo['aliases'] .= " $userinfo[username]";
	$checkdomains = str_replace('\|', ')|(', preg_quote(implode('|', getop('domainnames')), '#'));
	$checkusers = str_replace('\|', ')|(', preg_quote(str_replace(' ', '|', $userinfo['aliases']), '#'));
	return preg_match('#(('.$checkusers.'))(('.$checkdomains.'))#i', $email); // (( and )) isn't a typo
}

// ############################################################################
// Send dud image to respond to image.src = 'xxx.php'... dud image?!
function send_dud_image () {
	// There should be NO WAY to cache this
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-type: image/gif");

	$image = fopen("misc/cp_blank.jpg", "r");
	fpassthru ($image);
	fclose($image);
	exit;
}

// ############################################################################
// Wrapper for strtolower for array_walk
function str2lower(&$value, $key) {
	$value = strtolower($value);
}

// ############################################################################
// Take an array of email addresses and return an array of alias IDs for them.
// Additionally returns an array of aliases that do not exist locally; the
// program should determine what to do with that list.
function return_aliasids($aliases, $cal = false, $returnuids = true) {
	global $DB_site;

	if ($cal) {
		$where = 'AND hive_user.options2 & '.USER_CALSHARESOK;
	}
	foreach ($aliases as $email) {
		$emails[] = substr($email, 0, strpos($email, '@'));
	}
	$usernames = "'".implode("','", $emails)."'";
	$users = $DB_site->query("
		SELECT hive_alias.userid AS uid, aliasid, alias, options2
		FROM hive_alias
		LEFT JOIN hive_user ON hive_alias.userid = hive_user.userid
		WHERE alias IN ($usernames)
	");
	$c = $DB_site->num_rows($users);
	while ($u = $DB_site->fetch_array($users)) {
		if (!($u['options2'] & USER_CALSHARESOK)) {
			$return['cant'][$u['alias']] = iif($returnuids, $u['uid'], $u['aliasid']);
		} else {
			$return['valid'][$u['alias']] = iif($returnuids, $u['uid'], $u['aliasid']);
		}
		$validuser[] = $u['alias'];
	}
	if (count($emails) > $c) {
		$missing = array_intersect($emails, $validuser);
		foreach ($missing as $alias) {
			// These aliases are missing and we return them as bad
			$return['bad'][] = $alias;
		}
	}
	return $return;
}

?>