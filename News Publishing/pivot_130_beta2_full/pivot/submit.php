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

include_once("pv_core.php");
include_once("modules/module_userreg.php");

$message = "";

// some global initialisation stuff
$Pivot_Vars = array_merge($_GET , $_POST, $_SERVER);
if(!isset($Pivot_Vars['piv_spkey']))  {
	die();
} else if($Pivot_Vars['piv_spkey'] != md5($Cfg['server_spam_key'].$Pivot_Vars['piv_code']))  {
	die();
}

add_hook("sumbit", "pre");

// functions
function generate_last_comments($tempcomm) {
	global $my_weblog, $db;

	// if it exists, load it
	$lastcomm =	load_serialize("db/ser_lastcomm.php", true, true);

	$lastcomm[] = array(
		'name' => $tempcomm['name'],
		'email' => $tempcomm['email'],
		'url' => $tempcomm['url'],
		'date' => $tempcomm['date'],
		'comment' => trimtext($tempcomm['comment'],250),
		'code' => $db->entry['code'],
		'title' => trimtext($db->entry['title'],50),
		'category' => $db->entry['category'],
		'ip' => $tempcomm['ip'],

	);



	if (count($lastcomm)>60) {
		array_shift ($lastcomm);
	}

	save_serialize("db/ser_lastcomm.php", $lastcomm );

}


function fill_comment_form($name, $email, $url, $cookie, $comm, $notify, $discreet) {

	// allow for multiline comments in preview
	$comm = str_replace("\n", "\\n", $comm);
	$comm = str_replace("\r", "", $comm);


	echo "<script>\n";
	if ($name!="") {
		echo "document.getElementById('form').piv_name.value='".($name)."';\n";
	}
	if ($email!="") {
		echo "document.getElementById('form').piv_email.value='".($email)."';\n";
	}
	if ($url!="") {
		echo "document.getElementById('form').piv_url.value='".($url)."';\n";
	}
	if ($comm!="") {
		echo "document.getElementById('form').piv_comment.value='".($comm)."';\n";
	}

	if ($cookie=="yes") {
		echo "document.getElementById('form').piv_rememberinfo[0].checked=true;\n";
	} else {
		echo "document.getElementById('form').piv_rememberinfo[1].checked=true;\n";
	}

	if ($notify!="") {
		echo "document.getElementById('form').piv_notify.checked=true;\n";
	}
	if ($discreet!="") {
		echo "document.getElementById('form').piv_discreet.checked=true;\n;";
	}

	echo "</script>\n";

}


function send_mail() {
	global $Cfg, $db, $PIV_PARA, $my_comment, $ip, $date, $Weblogs, $Current_weblog, $Paths, $i18n_use;

	$cat_weblogs = find_weblogs_with_cat($db->entry['category']);

	$addr_arr= array();

	foreach ($cat_weblogs as $this_weblog) {
		$Current_weblog = $this_weblog;
		if ($Weblogs[$this_weblog]['comment_sendmail'] == 1) {
			$addr_arr = array_merge($addr_arr, explode(",", $Weblogs[$this_weblog]['comment_emailto']));
		}
	}

	// Make the array of user that want to be notified..
	$notify_arr = array();

	foreach($db->entry['comments'] as $temp_comm) {
		if (($temp_comm['notify']==1) && (isemail($temp_comm['email'])))	{
			$notify_arr[ $temp_comm['email'] ] = 1;
		}
		if (($temp_comm['notify']==0) && (isemail($temp_comm['email'])))	{
			unset( $notify_arr[ $temp_comm['email'] ] );
		}
	}

	// don't send to the user that did the comment...
	if (isset($notify_arr[ $my_comment['email'] ])) {
		unset( $notify_arr[ $my_comment['email'] ] );
	}



	// make a nice title for the mail..
	if (strlen($db->entry['title'])>2) {
		$title=$db->entry['title'];
	} else {
		$title=substr($db->entry['introduction'],0,300);
		$title=strip_tags($title);
		$title=str_replace("\n","",$title);
		$title=str_replace("\r","",$title);
		$title=substr($title,0,60);
	}

	if (!$i18n_use) { $title = utf8_encode($title); }

	// maybe send some mail to authors..
	if ((count($addr_arr)>0)&&(!isset($PIV_PARA['f_comm_ip']))) {

		$id = format_date($comment["date"], "%ye%%month%%day%%hour24%%minute%");
		$editlink =  $Paths['host'].$Paths['pivot_url']."index.php?menu=entries&func=editcomments&id=".$db->entry['code'];
		$blocklink =  $Paths['host'].$Paths['pivot_url']."index.php?menu=entries&func=editcomments&id=".
									$db->entry['code']."&blocksingle=".$my_comment['ip'];

		$comment = ($my_comment['comment']);

		// $comment = unentify($comment);

		$body=sprintf("'%s' posted the following comment:\n\n", unentify($my_comment['name']));
		$body.=sprintf("%s", $comment);
		$body.=sprintf("\n\n-------------\n");
		$body.=sprintf(lang('weblog_text','name').": %s\n", unentify($my_comment['name']));
		$body.=sprintf(lang('weblog_text','ip').": %s\n", $my_comment['ip']);
		$body.=sprintf(lang('weblog_text','date').": %s\n", $my_comment['date']);
		$body.=trim(sprintf(lang('weblog_text','email').": %s", $my_comment['email']))."\n";
		$body.=trim(sprintf(lang('weblog_text','url').": %s\n", $my_comment['url']))."\n";
		$body.=sprintf("\nThis is a comment on entry '%s'\n", $title);

		if (count($notify_arr)>0) {
			$body.=sprintf("notifications: %s\n", implode(", ", $notify_arr));
		}

		$body.=sprintf("-------------\n");
		$body.=sprintf("View this entry:\n%s%s\n", $Paths['host'],  make_filelink($PIV_PARA['f_comm_code'], "", $id));
		$body.=sprintf("\nEdit this comment:\n%s\n",  $editlink );
		$body.=sprintf("\nBlock this IP:\n%s\n",  $blocklink );

		if (!$i18n_use) $body = utf8_encode($body);

		$name = $my_comment['name'];
		if (!$i18n_use) $name = utf8_encode($name);
		$comment_name = '=?UTF-8?B?'.base64_encode($name).'?=';
		if (isemail($my_comment['email'])) {
			$add_header=sprintf("From: \"%s\" <%s>\n", $comment_name, $my_comment['email']);
		} else {
			$add_header=sprintf("From: \"'%s'\" <%s>\n", $comment_name, $addr_arr[0]);
		}
		$add_header.="MIME-Version: 1.0\n";
		$add_header.="Content-Type: text/plain; charset=UTF-8; format=flowed\n";
		$add_header.="Content-Transfer-Encoding: 8bit\n";

		$subject = lang('comment','email_subject')." ".$title;
		$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';

		$addr_arr = array_unique($addr_arr);

		foreach($addr_arr as $addr) {
			$addr = trim($addr);
			@mail($addr, $subject, $body, $add_header);
			add_log("Send Mail to $addr from '".$my_comment['name']."'");
		}

	}


	// maybe send mail to those on the 'notify me' list..
	if (count($notify_arr)>0) {

		$body=sprintf("'%s' posted the following comment:\n\n", unentify($my_comment['name']));
		$body.=sprintf("%s", unentify($my_comment['comment']));
		$body.=sprintf("\n\n-------------\n");
		$body.=sprintf("name: %s\n", unentify($my_comment['name']));
		$body.=sprintf("This is a comment on entry '%s'\n", $title);
		$body.=sprintf("\nView this entry:\n%s%s\n", $Paths['host'],  make_filelink($PIV_PARA['f_comm_code'], "", $id));

		$comment_name = '=?UTF-8?B?'.base64_encode($my_comment['name']).'?=';
		$add_header = sprintf("From: \"'%s'\" <%s>\n", $comment_name, $addr_arr[0]);
		$add_header.="MIME-Version: 1.0\n";
		$add_header.="Content-Type: text/plain; charset=UTF-8; format=flowed\n";
		$add_header.="Content-Transfer-Encoding: 8bit\n";

		$subject = "[Notification] Re: $title";
		$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';

		$notify_arr = array_unique($notify_arr);

		foreach($notify_arr as $addr => $val) {
			$addr = trim($addr);
			@mail($addr, $subject, $body, $add_header);
			add_log("Send Notify to $addr from '".$my_comment['name']."'");
		}
	}


}



// convert encoding to UTF-8
i18n_array_to_utf8($Pivot_Vars, $dummy_variable);

$Pivot_Vars['piv_name'] = strip_tags($Pivot_Vars['piv_name']);
$Pivot_Vars['piv_email'] = strip_tags($Pivot_Vars['piv_email']);
$Pivot_Vars['piv_url'] = strip_tags($Pivot_Vars['piv_url']);

$Current_weblog = $Pivot_Vars['piv_weblog'];
// switch to weblog's language
LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);


if (ip_check_block($Pivot_Vars['REMOTE_ADDR'])) {
	add_log("Blocked user from $ip tried to comment");
	piv_error("You are Blocked", "Your IP-address has been blocked, so you are not".
		" allowed to leave comments on this site. We know IP-adresses can easily be faked,".
		" but it helps. Have a nice day<br /><br />Go <a href='javascript:history.go(-1)'>".
		"back</a> to the last page, and do something else.",0);

}

// set cookies (or delete) only if explicitely told so..
if ($Pivot_Vars['piv_rememberinfo']=="yes") {
	setcookie("piv_name", stripslashes($Pivot_Vars['piv_name']), time()+2592000, "/");
	setcookie("piv_email", stripslashes($Pivot_Vars['piv_email']), time()+2592000, "/");
	setcookie("piv_url", stripslashes($Pivot_Vars['piv_url']), time()+2592000, "/");
	setcookie("piv_rememberinfo", "yes", time()+2592000, "/");
	//debug ("cookiezet: ". stripslashes($Pivot_Vars['piv_name']));
} else if ($Pivot_Vars['piv_rememberinfo']=="no") {
	setcookie("piv_name", "", time()-1000, "/");
	setcookie("piv_email", "", time()-1000, "/");
	setcookie("piv_url", "", time()-1000, "/");
	setcookie("piv_rememberinfo", "", time()-1000, "/");
	//debug("delcookie: " );
}


// load an entry
if (isset($Pivot_Vars['piv_code'])) {

	$db = new db();
	$entry = $db->read_entry($Pivot_Vars['piv_code']);

	execute_hook("submit", "pre", $Pivot_Vars);

	if (isset($Pivot_Vars['vote'])) {
		// we vote !!
		$ip = $Pivot_Vars['group'] . $Pivot_Vars['REMOTE_ADDR'];
		$value = $Pivot_Vars['vote'];

		if (isset($entry['votes'][$ip])) {
			$message = lang('karma','already');
			//add_log("vote '$value' from '".$ip."' not added (already voted).");
		} else {
			$entry['votes'][$ip] = $value;
			$message = lang('karma','register');
			add_log("vote '$value' from '".$ip."' added.");
			$myval = isset ($lang['karma'][$value]) ? lang('karma', $value) : $value;
			$message = str_replace('%val%', $myval , $message);
			// generating the page.
			$db->set_entry($entry);
			$db->save_entry(FALSE); // do not update the index.
			$db->unread_entry($entry['code']);

			generate_pages($Pivot_Vars['piv_code'], TRUE, TRUE, FALSE, FALSE, FALSE);

		}

		echo $message;

		echo "<script>self.focus(); </script>";

		echo "<br /><br /><div align=\"center\"><input type='button' value='ok' onclick='if (window.opener) { window.opener.location.reload(); } self.close();'></div>";



		die();


	} else {
		// we comment !!

		$registered = 0;
		// check if we are TEH REG USER..
		if (strlen($_COOKIE['piv_reguser'])>4) {
			list($reg_name, $reg_hash) = explode("|", $_COOKIE['piv_reguser']);

			debug("reg: $reg_name, $reg_hash");
			if ((check_user_hash($reg_name, $reg_hash)) && ($reg_name == $Pivot_Vars['piv_name'])) {
				$registered = 1;
			}
		}


		$my_comment = array(
			'name' => entify(stripslashes($Pivot_Vars['piv_name'])),
			'email' =>entify(stripslashes($Pivot_Vars['piv_email'])),
			'url' => entify(stripslashes($Pivot_Vars['piv_url'])),
			'ip' => $Pivot_Vars['REMOTE_ADDR'],
			'date' => format_date("", "%year%-%month%-%day%-%hour24%-%minute%"),
			'comment' => strip_trailing_space(stripslashes($Pivot_Vars['piv_comment'])),
			'registered' => $registered,
			'notify' => $Pivot_Vars['piv_notify'],
			'discreet' => $Pivot_Vars['piv_discreet'],
		);

		//here we do a check to prevent double entries...
		$duplicate=FALSE;

		if (isset($entry['comments']) && (count($entry['comments']) > 0 ) ) {
		foreach($entry['comments'] as $loop_comment) {
				$diff =  1 / ( min( strlen($loop_comment['comment']), 200) /
					(levenshtein( substr($loop_comment['comment'],-200) , substr($my_comment['comment'],-200) )+1) );
				if ( ($diff < 0.25) && ($loop_comment['ip'] == $my_comment['ip']) ) {
					$duplicate=TRUE;
				}
			}
		}

		// set the message and take proper action:
		if (isset($Pivot_Vars['preview'])) {

			// update the current entry
			$entry['comments'][] = $my_comment;
			$Pivot_Vars['message'] = lang('comment','preview');
			unset($Pivot_Vars['post']);
			$Pivot_Vars['preview'] = TRUE;

		} else if (!$duplicate)  {

			// update the current entry
			$entry['comments'][] = $my_comment;
			$message = lang('comment','register');
			$message = "&message=".urlencode($message);
			$Pivot_Vars['post'] = TRUE;

		} else {

			$Pivot_Vars['message'] = lang('comment','duplicate');
			//$message = "&message=".urlencode($message);
			unset($Pivot_Vars['post']);
			$Pivot_Vars['preview'] = TRUE;

		}
	}



	// if comment or name is missing, give a notice, and show the form again..
	if ( (!isset($Pivot_Vars['vote'])) && (strlen($my_comment['name'])<2) ) {
		$Pivot_Vars['message'] = lang('comment','no_name');
		unset($Pivot_Vars['post']);
		$Pivot_Vars['preview'] = TRUE;
	}

	if ( (!isset($Pivot_Vars['vote'])) && (strlen($my_comment['comment'])<3) ) {
		$Pivot_Vars['message'] = lang('comment','no_comment');
		unset($Pivot_Vars['post']);
		$Pivot_Vars['preview'] = TRUE;
	}

  // check num of hyperlinks by loweblog.com
  if ( isset($Cfg['maxhrefs']) && ($Cfg['maxhrefs'] > 0) ) {
    $low_comment = strtolower(comment_format($my_comment['comment']));
    if ( substr_count($low_comment, "href=") > 2 ) {
  	  $Pivot_Vars['message'] = lang('comment','too_many_hrefs');
		  unset($Pivot_Vars['post']);
		  $Pivot_Vars['preview'] = TRUE;
		}
  	}

	if (isset($Pivot_Vars['post'])) {
		$db->set_entry($entry);

		// send mail..
		if (!isset($Pivot_Vars['vote'])) {
			send_mail();
		}

		// switch to weblog's language (it might be changed in the meantime)
		LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

		add_log("comment from '".$Pivot_Vars['piv_name']."' added.");

		$db->save_entry(FALSE); // do not update the index.

		//update the 'last comments' file
		if (isset($my_comment)) {
			generate_last_comments($my_comment);
		}

		// remove it from cache, to make sure the latest one is used.
		$db->unread_entry($entry['code']);

		// first get the filename..
		$filename = make_filelink($Pivot_Vars['piv_code'], $Pivot_Vars['piv_weblog'], 'message', $message, TRUE);

		// regenerate entry, frontpage and archive..
		generate_pages($Pivot_Vars['piv_code'], TRUE, TRUE, TRUE, FALSE, FALSE);

		redirect( $filename);

	}



	if (isset($Pivot_Vars['preview'])) {
		$db->set_entry($entry);

		define('__SILENT__', TRUE);
		define('LIVEPAGE', TRUE);

		// only set the message if not done yet (otherwise the 'no_name' notice would be overwritten)
		if (!isset($Pivot_Vars['message'])) {
			$Pivot_Vars['message'] = lang('comment','preview');
		}

		echo parse_entry($Pivot_Vars['piv_code'], $Current_weblog);
		fill_comment_form($Pivot_Vars['piv_name'], $Pivot_Vars['piv_email'], $Pivot_Vars['piv_url'], $Pivot_Vars['piv_rememberinfo'], $Pivot_Vars['piv_comment'], $Pivot_Vars['piv_notify'], $Pivot_Vars['piv_discreet']);

	}

} else {
	echo "No id..";
}


?>
