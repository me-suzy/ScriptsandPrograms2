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
// | $RCSfile: hivevb3_install.php,v $ - $Revision: 1.2 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require('./global.php');
require_once('./includes/adminfunctions_language.php');
require_once('./includes/adminfunctions_template.php');

// ############################################################################
// Start
print_cp_header("<title>Install vBulletin-HiveMail&trade; integration - $vboptions[bbtitle] - vBulletin $vbphrase[control_panel]</title>");
echo '<b>Installing vBulletin-HiveMail&trade; integration</b><br /><br />';

// ############################################################################
// Define templates
$_errorgroup = 1000;
$_redirectgroup = 2000;
$_phrases = array(
	'hivemail_nameillegal' => array(
		'type' => 'error',
		'text' => 'The email address you requested was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter and must be a minimum length of 2 characters.',
	),
	'hivemail_nametaken' => array(
		'type' => 'error',
		'text' => 'We\'re sorry, email address you requested is already used by another member. Please go back and enter a different address.',
	),
	'hivemail_thankyou' => array(
		'type' => 'redirect',
		'text' => 'Thank you for signing up for our mail service, your password is the same one you use for this account.',
	),
);
$_templates = array(
	'hivemail_signup' => '$stylevar[htmldoctype]
<html dir="$stylevar[textdirection]" lang="$stylevar[languagecode]">
<head><title>$vboptions[bbtitle] - Email Account</title>
$headinclude
</head>
<body>
$header

<br />

<form action="register.php" method="post">
<input type="hidden" name="s" value="$session[sessionhash]" />
<input type="hidden" name="hive_signup" value="1" />
<input type="hidden" name="do" value="addmail" />

<table cellpadding="$stylevar[outerborderwidth]" cellspacing="0" border="0" class="tborder" width="$stylevar[tablewidth]" align="center"><tr><td>
<table cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" border="0" width="100%">
<tr>
	<td class="thead" colspan="2"><b>Sign Up for an Email Account</b></td>
</tr>
<tr>
	<td class="alt1"><b>Account name:</b></td>
	<td class="alt1"><input type="text" class="bginput" size="25" name="hive_username" value="$bbuserinfo[username]" /> <select name="hive_userdomain">$hive_domainname_options</select></td>
</tr>
<tr>
	<td class="alt2"><b>Password:</b></td>
	<td class="alt2"><input type="password" class="bginput" size="25" name="password" /></td>
</tr>
</table>
</td></tr></table>

<table cellpadding="2" cellspacing="0" border="0" width="$stylevar[tablewidth]" align="center">
<tr>
	<td align="center"><input type="submit" class="button" value="Submit Now" /></td>
</tr>
</table>

</form>

$footer

</body>
</html>',
);
$_templatetitles = '"'.implode('","', array_keys($_templates)).'"';
$_phrasetitles = '"'.implode('","', array_keys($_phrases)).'"';

// ############################################################################
// Check if the field has been created
echo 'Checking database structure ... ';
$user = $DB_site->query_first('
	SELECT *
	FROM '.TABLE_PREFIX.'user
	LIMIT 1
');
$create_field = !isset($user['hiveuserid']);
echo 'done!<br />';

// ############################################################################
// Create field is needed
if ($create_field) {
	echo 'Altering database structure ... ';
	$DB_site->query('
		ALTER TABLE '.TABLE_PREFIX.'user
		ADD hiveuserid int(10) unsigned NOT NULL default "0"
	');
	echo 'done!<br />';
}

// ############################################################################
// Check which templates already exist
echo 'Checking existing templates ... ';
$templates = $DB_site->query("
	SELECT *
	FROM ".TABLE_PREFIX."template
	WHERE title IN ($_templatetitles)
	AND styleid <> -1
");
$existing = array();
while ($template = $DB_site->fetch_array($templates)) {
	$existing[$template['styleid']]["$template[title]"] = true;
}
echo 'done!<br />';

// ############################################################################
// Get style ID's
echo 'Fetching styles ... ';
$styles = $DB_site->query('
	SELECT *
	FROM '.TABLE_PREFIX.'style
');
$_styleids = array();
while ($style = $DB_site->fetch_array($styles)) {
	$_styleids[] = $style['styleid'];
}
echo 'done!<br /><br />';

// ############################################################################
// Create new templates
echo '<b>Creating templates</b><br />';
foreach ($_templates as $title => $template) {
	$values = array();
	foreach ($_styleids as $styleid) {
		if ($existing[$styleid]["$title"] != true) {
			$values[] = "($styleid, '".addslashes($title)."', '".addslashes($template)."', '".addslashes(process_template_conditionals(addslashes($template)))."')";
		}
	}
	if (empty($values)) {
		continue;
	}
	echo 'Creating template <tt>'.$title.'</tt> ... ';
	$values = implode(', ', $values);
	$DB_site->query("
		INSERT INTO ".TABLE_PREFIX."template
		(styleid, title, template_un, template)
		VALUES $values
	");
	echo 'done!<br />';
}

// ############################################################################
// Check which phrases already exist
echo 'Checking existing phrases ... ';
$phrases = $DB_site->query("
	SELECT *
	FROM ".TABLE_PREFIX."phrase
	WHERE varname IN ($_phrasetitles)
	AND languageid <> -1
");
$existing = array();
while ($phrase = $DB_site->fetch_array($phrases)) {
	$existing[$phrase['languageid']]["$phrase[varname]"] = true;
}
echo 'done!<br />';

// ############################################################################
// Get language ID's
echo 'Fetching languages ... ';
$languages = $DB_site->query('
	SELECT *
	FROM '.TABLE_PREFIX.'language
');
$_languageids = array();
while ($language = $DB_site->fetch_array($languages)) {
	$_languageids[] = $language['languageid'];
}
echo 'done!<br /><br />';

// ############################################################################
// Create new phrases
echo '<b>Creating phrases</b><br />';
foreach ($_phrases as $varname => $info) {
	$values = array();
	foreach ($_languageids as $languageid) {
		if ($existing[$languageid]["$varname"] != true) {
			$values[] = "($languageid, '".addslashes($varname)."', '".addslashes($info['text'])."', ".${"_$info[type]group"}.")";
		}
	}
	if (empty($values)) {
		continue;
	}
	echo 'Creating phrase <tt>'.$varname.'</tt> ... ';
	$values = implode(', ', $values);
	$DB_site->query("
		INSERT INTO ".TABLE_PREFIX."phrase
		(languageid, varname, text, phrasetypeid)
		VALUES $values
	");
	echo 'done!<br />';
}

// ############################################################################
// Rebuild languages
echo 'Rebuilding languages ... ';
build_language(-1);
echo 'done!<br /><br />';

// ############################################################################
// All done
echo '<b>All done!</b>';
print_cp_footer();
?>