<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// First line defense.
if (file_exists(dirname(__FILE__)."/first_defense.php")) {
	include_once(dirname(__FILE__)."/first_defense.php");
	block_refererspam();
	block_postedspam();
}


define('__SILENT__', TRUE);
define('LIVEPAGE', TRUE);

include_once("pv_core.php");
include_once($pivot_path. "modules/module_userreg.php");


$direct_output = TRUE;

// some global initialisation stuff
$Pivot_Vars = array_merge($_GET , $_POST, $_SERVER);

add_hook("display_entry", "pre");





// convert encoding to UTF-8
i18n_array_to_utf8($Pivot_Vars, $dummy_variable);

if (isset($Pivot_Vars['uid'])) {
	$Pivot_Vars['uid'] = str_replace("standard-", "", $Pivot_Vars['uid']);
	$Pivot_Vars['id'] = $Pivot_Vars['uid'];
}

$override_weblog="";

// check if we need to override the template.
if (isset($Pivot_Vars['t'])) {
	// explicitly defined template is most important.
	$Pivot_Vars['t'] = basename($Pivot_Vars['t']);
	$override_template = $Pivot_Vars['t'];
} else if (isset($Pivot_Vars['w'])) {
	// then, explicitly defined weblog
	list($override_weblog) = (explode("#", $Pivot_Vars['w']));
} else {
	// Else, we need to check if we can determine which weblog this entry belongs
	// to. it might belong to multiple weblogs, and here we check to see it the
	// referer can be matched to a weblog's homepage. If so, we use _that_
	// template instead of the one that would be chosen otherwise.
	$referer = str_replace("http://".$Pivot_Vars['HTTP_HOST'], "", $Pivot_Vars['HTTP_REFERER']);
	if ($referer!="") {
		foreach($Weblogs as $weblogkey => $weblog) {
			$filename = fixpath($Paths['pivot_url'] . $weblog['front_path'] . $weblog['front_filename']);
			if ( ($referer == $filename) || ($referer == str_replace("/index.php", "/", $filename)) ||
			($referer == str_replace("/index.php", "/", $filename) ) ) {
				$override_weblog = $weblogkey;
			}
		}
	}
}



// load an entry
if (isset($Pivot_Vars['id'])) {


	execute_hook("display_entry", "pre", $Pivot_Vars['id'], $override_weblog);

	$output = parse_entry($Pivot_Vars['id'], $override_weblog);

	// If registered user, override the other settings..
	if (isset($_COOKIE['piv_reguser'])) {

		list($reg_name, $reg_hash) = explode("|", 	$Pivot_Cookies['piv_reguser']);

		if (check_user_hash($reg_name, $reg_hash)) {
			$reg_user = load_user($reg_name);
			if ($reg_user['show_address']==1) {
				$_COOKIE['piv_email'] = $reg_user['email'];
			} else {
				$_COOKIE['piv_email'] = "";
			}
			$_COOKIE['piv_name'] = $reg_user['name'];
			$_COOKIE['piv_url'] = $reg_user['url'];
		}
	}

	if (count($_COOKIE)>0) {
		$cookie = @fill_comment_form($_COOKIE['piv_name'], $_COOKIE['piv_email'], $_COOKIE['piv_url'], $_COOKIE['piv_rememberinfo'], $_COOKIE['piv_comment']);
		$cookie = i18n_str_to_utf8($cookie);
		$output = str_replace("</body>", $cookie."</body>", $output);
	}

	add_hook("display_entry", "post");
	execute_hook("display_entry", "post", $db->entry, $output);

	echo $output;

} else if (isset($Pivot_Vars['f_title'])) {

	// This shows the entry in preview mode.

	$db = new db();

	$entry = get_entry_from_post();

	$entry['status'] = 'publish';
	$entry = $db->set_entry($entry);

	execute_hook("display_entry", "pre");

	$output = parse_entry($entry['code'], "");

	add_hook("display_entry", "post");
	execute_hook("display_entry", "post", $output);

	echo $output;

} else {

	add_hook("display_entry", "post");
	execute_hook("display_entry", "post", $output);

	echo $output;

}




// ------------

function fill_comment_form($name, $email, $url, $cookie, $comm) {

	$output = "<script language=\"JavaScript\" type=\"text/javascript\">\n";

	//$output .= "alert(document.getElementById('form'));\n";

	if ($name!="") {
		$output .=  "document.getElementById('form').piv_name.value='".($name)."';\n";
	}
	if ($email!="") {
		$output .=  "document.getElementById('form').piv_email.value='".($email)."';\n";
	}
	if ($url!="") {
		$output .=  "document.getElementById('form').value='".($url)."';\n";
	}
	if ($comm!="") {
		$output .=  "document.getElementById('form').piv_comment.value='".($comm)."';\n";
	}

	if ($cookie=="yes") {
		$output .=  "document.getElementById('form').piv_rememberinfo[0].checked=true;\n";
	} else {
		$output .=  "document.getElementById('form').piv_rememberinfo[1].checked=true;\n";
	}

	$output .=  "</script>\n";
	return $output;

}


?>
