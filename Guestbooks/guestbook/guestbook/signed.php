<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                       signed.php file                        */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/
$phphg_real_path = "./";
include($phphg_real_path . 'common.php');

$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

$sql = "SELECT * FROM ".$prefix."_banned WHERE ip='$ip'";
$result = $db->query($sql);

$num = $db->num($result);

if($num > 0) {
	include($phphg_real_path . 'includes/page_header.php');
	$display = "You have been banned. Please contact the admin.";
	$template->getFile(array(
			   'error' => 'error.tpl')
	);
	$template->add_vars(array(
                           'L_ERROR' => $lang['error'],
			   'DISPLAY' => $display)
	);
	$template->parse("error");
	include($phphg_real_path . 'includes/page_footer.php');
	exit();
} else {
	if((!$HTTP_POST_VARS['name']) || (!$HTTP_POST_VARS['email']) || (!$HTTP_POST_VARS['location']) || (!$HTTP_POST_VARS['message'])) {
        $display = "You forgot to fill in these required fields.<br>";
        if(!$HTTP_POST_VARS['name']) {
        	$display .= "Name<br>";
        }
        if(!$HTTP_POST_VARS['email']) {
        	$display .= "Email Address<br>";
        }
        if(!$HTTP_POST_VARS['location']) {
        	$display .= "Location<br>";
        }
        if(!$HTTP_POST_VARS['message']) {
        	$display .= "Message<br>";
        }
        include($phphg_real_path . 'includes/page_header.php');
        $template->getFile(array(
			   'error' => 'error.tpl')
	);
	$template->add_vars(array(
                           'L_ERROR' => $lang['error'],
			   'DISPLAY' => $display)
	);
	$template->parse("error");
	include($phphg_real_path . 'includes/page_footer.php');
	exit();
    }
    if(!eregi("[0-9a-z]{3,10}$", $HTTP_POST_VARS['name'])) {
    	include($phphg_real_path . 'includes/page_header.php');
    	$display = "Your name must be atleast 3 characters long.";
    	$template->getFile(array(
			   'error' => 'error.tpl')
	);
        $template->add_vars(array(
                           'L_ERROR' => $lang['error'],
			   'DISPLAY' => $display)
        );
	$template->parse("error");
	include($phphg_real_path . 'includes/page_footer.php');
	exit();
     }
     if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['email'])) {
     	include($phphg_real_path . 'includes/page_header.php');
     	$display = "That is not a valid email address.";
     	$template->getFile(array(
			   'error' => 'error.tpl')
        );
	$template->add_vars(array(
                           'L_ERROR' => $lang['error'],
			   'DISPLAY' => $display)
	);
	$template->parse("error");
	include($phphg_real_path . 'includes/page_footer.php');
	exit();
    }
    
    $smilie_dir = "images/smilies";
    
    $agent = array('msie','opera','netscape');

	foreach($agent as $useragent) {
		if(stristr(getenv('HTTP_USER_AGENT') , $useragent)) {
		   $user_agent = $useragent;
        }
    }
    
    $sql = "INSERT INTO ".$prefix."_message (date, username, location, ip, browser, email, website, message) VALUES (now(), '$HTTP_POST_VARS[name]', '$HTTP_POST_VARS[location]', '$HTTP_SERVER_VARS[REMOTE_ADDR]', '$user_agent', '$HTTP_POST_VARS[email]', '$HTTP_POST_VARS[website]', '$HTTP_POST_VARS[message]')";
    $result = $db->query($sql);
    
    if(!$result) {
    	include($phphg_real_path . 'includes/page_header.php');
    	$display = "Could not add your guestbook entry." . mysql_error();
    	$template->getFile(array(
			   'error' => 'error.tpl')
	);
	$template->add_vars(array(
                           'L_ERROR' => $lang['error'],
			   'DISPLAY' => $display)
	);
	$template->parse("error");
	include($phphg_real_path . 'includes/page_footer.php');
	exit();
     } else {
     	include($phphg_real_path . 'includes/page_header.php');
     	$display = "Your entry has been added.";
        $link = "index.php";
     	$template->getFile(array(
			   'success' => 'success.tpl')
        );
	$template->add_vars(array(
                           'L_SUCCESS' => $lang['success'],
			   'DISPLAY' => $display,
                           'LINK' => $link)
	);
	$template->parse("success");
	include($phphg_real_path . 'includes/page_footer.php');
	exit();
     }
}
?>