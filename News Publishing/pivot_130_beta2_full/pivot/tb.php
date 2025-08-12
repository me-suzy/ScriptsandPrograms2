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


// functions
function generate_last_trackbacks($temptrack) {
	global $entry;

	// if it exists, load it
	$lasttrack =	load_serialize("db/ser_lasttrack.php", true, true);

	$lasttrack[] = array(
		'title' => $temptrack['title'],
		'excerpt' => trimtext($temptrack['excerpt'],250),
		'name' => $temptrack['name'],
		'url' => $temptrack['url'],
		'date' => $temptrack['date'],
		'code' => $entry['code'],
		'category' => $entry['category'],
		'ip' => $temptrack['ip'],
	);

	if (count($lasttrack)>60) {
		array_shift ($lasttrack);
	}

	save_serialize("db/ser_lasttrack.php", $lasttrack );
}


function send_mail() {
	global $Cfg, $entry, $PIV_PARA, $my_trackback, $Weblogs, $Current_weblog, $Paths, $i18n_use;

	debug ("send mail");

	$cat_weblogs = find_weblogs_with_cat($entry['category']);

	$addr_arr= array();

	foreach ($cat_weblogs as $this_weblog) {
		$Current_weblog = $this_weblog;
                // Using the same settings as for comments
		if ($Weblogs[$this_weblog]['comment_sendmail'] == 1) {
			$addr_arr = array_merge($addr_arr, explode(",", $Weblogs[$this_weblog]['comment_emailto']));
		}
	}

	// Make the array of user that want to be notified..
	$notify_arr = array();

    $title=$entry['title'];
	if (!$i18n_use) { $title = utf8_encode($title); }

	// maybe send some mail to authors..
	if ((count($addr_arr)>0)&&(!isset($PIV_PARA['f_comm_ip']))) {

		$id = format_date($trackback["date"], "%ye%%month%%day%%hour24%%minute%");
		$editlink =  $Paths['host'].$Paths['pivot_url']."index.php?menu=entries&func=edittrackbacks&id=".$entry['code'];
		$blocklink =  $Paths['host'].$Paths['pivot_url']."index.php?menu=entries&func=edittrackbacks&id=".
									$entry['code']."&blocksingle=".$my_trackback['ip'];

		$body=sprintf("'%s' sent you the following trackback:", unentify($my_trackback['name']));
		$body.=sprintf("\n\n-------------\n");
		$body.=sprintf(lang('weblog_text','title').": %s\n", $my_trackback['title']);
		$body.=sprintf("%s", unentify($my_trackback['excerpt']));
		$body.=sprintf("\n-------------\n");
		$body.=sprintf(lang('weblog_text','ip').": %s\n", $my_trackback['ip']);
		$body.=sprintf(lang('weblog_text','date').": %s\n", $my_trackback['date']);
		$body.=sprintf(lang('weblog_text','url').": %s\n", $my_trackback['url']);
		$body.=sprintf("\nThis is a trackback on entry '%s'\n", $title);

		if (count($notify_arr)>0) {
			$body.=sprintf("notifications: %s\n", implode(", ", $notify_arr));
		}

		$body.=sprintf("-------------\n");
		$body.=sprintf("View this entry:\n%s%s\n", $Paths['host'],  make_filelink($PIV_PARA['f_comm_code'], "", $id));
		$body.=sprintf("\nEdit this trackback:\n%s\n",  $editlink );
		$body.=sprintf("\nBlock this IP:\n%s\n",  $blocklink );
		if (!$i18n_use) $body = utf8_encode($body);

		$name = $my_trackback['name'];
		if (!$i18n_use) $name = utf8_encode($name);
		$trackback_name = '=?UTF-8?B?'.base64_encode($name).'?=';
		$add_header = sprintf("From: \"'%s'\" <%s>\n", $trackback_name, $addr_arr[0]);
		$add_header.="MIME-Version: 1.0\n";
		$add_header.="Content-Type: text/plain; charset=UTF-8; format=flowed\n";
		$add_header.="Content-Transfer-Encoding: 8bit\n";

		$subject = lang('trackback','email_subject')." ". $title;
		$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';

		foreach($addr_arr as $addr) {
			$addr = trim($addr);
			@mail($addr, $subject, $body, $add_header);
			add_log("Sent Mail to $addr from '".$my_trackback['name']."'");
		}
	}



}

/**
* Print result of trackback posting and exit
*
* @param   bool    $error     print error
* @param   string  $msg       addtional text to display
* @param   bool
*/
function respondExit($msg = "", $error = false) {
    header("Content-Type: text/xml\n\n");
    print "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";

    print "<response>\n";
    if($error) printf("<error>1</error>\n%s\n",xml("message", $msg));
    else printf("<error>0</error>\n%s", $msg);
    print "</response>\n";

    exit;
    return false;
}

/**
* Build xml tag
*
* @param   string   $tag     xml tag name
* @param   string   $value   value of tag
* @return  string
*/
function xml($tag, $value) {
    return sprintf("<%s>%s</%s>\n",
                    $tag,
                    htmlspecialchars($value),
                    $tag
            );
}

include_once("pv_core.php");
include_once("modules/module_userreg.php");

/**
* Transform id: remove special chars
*
* @param  string $tb_id     trackback id
* @return string            modified trackback id
*/
function mungeTbId($tb_id) {
    if(empty($tb_id)) return false;
    else return $tb_id = preg_replace("#\W#", "_", $tb_id);
}


/* START OF MAIN TRACKBACK HANDLING CODE */

$message = "";

// some global initialisation stuff
$Pivot_Vars = array_merge($_GET , $_POST, $_SERVER);

add_hook("trackback", "pre");
execute_hook("trackback", "pre", $Pivot_Vars);

// convert encoding to UTF-8
i18n_array_to_utf8($Pivot_Vars, $dummy_variable);

$Current_weblog = (key($Weblogs)); // FIXME - copied from search.php. Does this work for a second blog?
LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

// checking if IP address of trackbacking site is blocked
if (ip_check_block($Pivot_Vars['REMOTE_ADDR'])) {
	add_log("Blocked user from ".$Pivot_Vars['REMOTE_ADDR']." tried to trackback");
	respondExit("Your IP-address has been blocked, so you are not".
                " allowed to leave trackbacks on this site. We know IP-adresses can easily be faked,".
		" but it helps." ,1);

}

// Exit if no trackback (entry) ID is given
if(!$Pivot_Vars['tb_id'] = mungeTbId($Pivot_Vars['tb_id'])) respondExit(lang('trackback','noid'), 1);

// Open database
$db = new db();

// Exit if non-existing ID supplied
if(!$db->entry_exists($Pivot_Vars['tb_id'])) respondExit('Non-existing ID', 1);

$my_trackback = array(
        'name' => entify(urldecode(stripslashes($Pivot_Vars['blog_name']))),
        'title' => entify(urldecode(stripslashes($Pivot_Vars['title']))),
        'url' => urldecode($Pivot_Vars['url']),
        'ip' => $Pivot_Vars['REMOTE_ADDR'],
        'date' => format_date("", "%year%-%month%-%day%-%hour24%-%minute%"),
        'excerpt' => strip_trailing_space(urldecode(stripslashes($Pivot_Vars['excerpt']))),
        );

// Exit if no URL is given - need to know URL to foreign entry that
// trackbacked us.
if(empty($my_trackback['url'])) respondExit(lang('trackback','nourl'), 1);

// load an entry
$entry = $db->read_entry($Pivot_Vars['tb_id']);

//here we do a check to prevent double entries...
$duplicate=FALSE;

if (isset($entry['trackbacks']) && (count($entry['trackbacks']) > 0 ) ) {
    foreach($entry['trackbacks'] as $loop_trackback) {
        $diff =  1 / ( min( strlen($loop_trackback['excerpt']), 200) /
                (levenshtein( substr($loop_trackback['excerpt'],-200) , substr($my_trackback['excerpt'],-200) )+1) );
        if ( ($diff < 0.25) && ($loop_trackback['ip'] == $my_trackback['ip']) ) {
            $duplicate=TRUE;
        }
    }
}

if (!$duplicate)  {
    // update the current entry
    $entry['trackbacks'][] = $my_trackback;
    $Pivot_Vars['post'] = TRUE;
} else {
    $message = lang('trackback','duplicate');
    unset($Pivot_Vars['post']);
}

// check num of hyperlinks by loweblog.com
if ( isset($Cfg['maxhrefs']) && ($Cfg['maxhrefs'] > 0) ) {
    $low_trackback = strtolower(trackback_format($my_trackback['excerpt']));
    if ( substr_count($low_trackback, "href=") > 2 ) {
        $message = lang('trackback','too_many_hrefs');
        unset($Pivot_Vars['post']);
    }
}

if (isset($Pivot_Vars['post'])) {
    $db->set_entry($entry);

    // send mail..
    send_mail();

    // switch to weblog's language (it might be changed in the meantime)
    LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

    add_log("trackback from '".$Pivot_Vars['piv_name']."' added.");
    $db->save_entry(FALSE); // do not update the index.

    //update the 'last trackbacks' file
    if (isset($my_trackback)) {
        generate_last_trackbacks($my_trackback);
    }

    // remove it from cache, to make sure the latest one is used.
    $db->unread_entry($entry['code']);

    // regenerate entry, frontpage and archive..
    generate_pages($Pivot_Vars['tb_id'], TRUE, TRUE, TRUE, FALSE, FALSE);

    add_hook("trackback", "post");
	execute_hook("trackback", "post", $Pivot_Vars, $entry);

    respondExit();
} else {

	add_hook("trackback", "post");
	execute_hook("trackback", "post", $Pivot_Vars, "");
    respondExit($message,1);
}

?>
