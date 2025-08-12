<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                     link_added.php file                      */
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
$phpht_real_path = "./";
include($phpht_real_path . 'common.php');

if($HTTP_POST_VARS['password'] !== $HTTP_POST_VARS['password2']) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "Your password dont match";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
   exit();
}

if((!$HTTP_POST_VARS['name']) || (!$HTTP_POST_VARS['url']) || (!$HTTP_POST_VARS['username']) || (!$HTTP_POST_VARS['email']) || (!$HTTP_POST_VARS['password']) || (!$HTTP_POST_VARS['password2']) || (!$HTTP_POST_VARS['desc'])) {
    $display = "Please fill in these required fields.<br>";
    if(!$HTTP_POST_VARS['name']) {
       $display .= "Website Name.<br>";
    }
    if(!$HTTP_POST_VARS['url']) {
       $display .= "Website URL.<br>";
    }
    if(!$HTTP_POST_VARS['username']) {
       $display .= "Username.<br>";
    }
    if(!$HTTP_POST_VARS['email']) {
       $display .= "Password.<br>";
    }
    if(!$HTTP_POST_VARS['password2']) {
       $display .= "Password Again.<br>";
    }
    if(!$HTTP_POST_VARS['desc']) {
       $display .= "Website Description.<br>";
    }
    include($phpht_real_path . 'includes/page_header.php');
    $template->getFile(array(
                       'error' => 'error.tpl')
    );
    $template->add_vars(array(
                       'L_ERROR' => $lang['error'],
                       'DISPLAY' => $display)
    );
    $template->parse("error");
    include($phpht_real_path . 'includes/page_footer.php');
    exit();
}

if(!eregi("[0-9a-z]{4,10}$", $HTTP_POST_VARS['username'])) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "Your username must be atleast four characters long.";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
   exit();
}

if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['email'])) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "That is not a valid email address.";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
   exit();
}

if($HTTP_POST_VARS['width'] > '468') {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "Your banner width is too big. It can be no longer than 468 pixels.";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
   exit();
}

if($HTTP_POST_VARS['height'] > '60') {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "Your banner height is too big. It can be no higher than 60 pixels.";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
   exit();
}

$sql = "SELECT name FROM ".$prefix."_links WHERE name='$HTTP_POST_VARS[name]'";
$result = $db->query($sql);
    
$sql2 = "SELECT url FROM ".$prefix."_links WHERE url='$HTTP_POST_VARS[url]'";
$result2 = $db->query($sql2);

$num = $db->num($result);
$num2 = $db->num($result2);
    
if(($num > 0) || ($num2 > 0)) {
    $display = "Please fix the following errors.<br>";
    if($num > 0) {
       $display .= "That website has already been submited.<br>";
       unset($HTTP_POST_VARS['name']);
    }
    if($num2 > 0) {
       $display .= "That website url has already been submited.<br>";
       unset($HTTP_POST_VARS['url']);
    }
    include($phpht_real_path . 'includes/page_header.php');
    $template->getFile(array(
                       'error' => 'error.tpl')
    );
    $template->add_vars(array(
                       'L_ERROR' => $lang['error'],
                       'DISPLAY' => $display)
    );
    $template->parse("error");
    include($phpht_real_path . 'includes/page_footer.php');
    exit();
}

$sql = "SELECT username FROM ".$prefix."_users WHERE username='$HTTP_POST_VARS[username]'";
$result = $db->query($sql);

$num3 = $db->num($result);

if($num3 > 0) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "That username is alread in use. Please choose another one.";
   unset($HTTP_POST_VARS['username']);
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
   exit();
}
        
if($activate == 'none') {
   $activated = 1;
} else {
   $activated = 0;
}     

$sql = "INSERT INTO ".$prefix."_links (name, url, banner, width, height, description, username, activated) VALUES ('$HTTP_POST_VARS[name]', '$HTTP_POST_VARS[url]', '$HTTP_POST_VARS[banner]', '$HTTP_POST_VARS[width]', '$HTTP_POST_VARS[height]', '$HTTP_POST_VARS[desc]', '$HTTP_POST_VARS[username]', '$activated')";
$result = $db->query($sql);

$db_password = md5($HTTP_POST_VARS['password']);
$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

$sql = "INSERT INTO ".$prefix."_users (username, password, email, ip, activated) VALUES ('$HTTP_POST_VARS[username]', '$db_password', '$HTTP_POST_VARS[email]', '$ip', '$activated')";
$result = $db->query($sql);

if(!$result) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "Could not add your website.";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
   exit();
} else {
   $link_id = mysql_insert_id();
   if($mail == "yes") {
      if($activate == 'none') {
         $in = "in.php?link_id=$link_id";
         $subject = "Your link at $site_title";
         $message = "Dear $HTTP_POST_VARS[username],
                         Thank you for submitting your website at $site_title,
                     You have been activated and can edit your account with the info below.

                     You can edit your link at any time after activation with 
                     the login details below.

                     Username: $HTTP_POST_VARS[username]
                     Password: $HTTP_POST_VARS[password]

                     if you wish for people to vote for your site to get a higher ranking
                     Please post this link someware in your site.

                     <a href=\"http://$domain$dir$in\">Vote For Us</a>.

                     Thank you
                     Admin at $site_title.";
         mail($HTTP_POST_VARS[email],$subject,$message,"FROM: $site_title Admin<$uemail>");
         include($phpht_real_path . 'includes/page_header.php');
         $link = "index.php";
         $display = "Your website has been added. Your login information has been sent to $HTTP_POST_VARS[email].";
         $template->getFile(array(
                            'success' => 'success.tpl')
         );
         $template->add_vars(array(
                            'L_SUCCESS' => $lang['success'],
                            'DISPLAY' => $display,
                            'LINK' => $link)
         );
         $template->parse("success");
         include($phpht_real_path . 'includes/page_footer.php');
         exit();
      }

      if($activate == 'user') {
         $in = "in.php?link_id=$link_id";
         $act = "activate.php?id=$link_id&code=$db_password";
         $subject = "Your link at $site_title";
         $message = "Dear $HTTP_POST_VARS[username],
                         Thank you for submitting your website at $site_title,
                    Your one step away from displaying your site Please activate your account below.
                    http://$domain$dir$act

                    After you activate your account you may use the info below to edit it.

                    Username: $HTTP_POST_VARS[username]
                    Password: $HTTP_POST_VARS[password]

                    if you wish for people to vote for your site to get a higher ranking
                    Please post this link someware in your site.

                    <a href=\"http://$domain$dir$in\">Vote For Us</a>.

                    Thank you
                    Admin at $site_title.";
         mail($HTTP_POST_VARS['email'],$subject,$message,"FROM: $site_title Admin<$uemail>");
         include($phpht_real_path . 'includes/page_header.php');
         $display = "Your website has been added. Your login information has been sent to $_HTTP_POST_VARS[email] And the instructions on to activate your account.";
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
         include($phpht_real_path . 'includes/page_footer.php');
         exit();
      }
      if($activate == 'admin') {
         $in = "in.php?link_id=$link_id";
         $subject = "Your link at $site_title";
         $message = "Dear $HTTP_POST_VARS[username],
                         Thank you for submitting your website at $site_title,
                    An admin is reviewing your submission and will activate your account asap

                    Once an admin activates your account you may use the info below to edit it.

                    Username: $HTTP_POST_VARS[username]
                    Password: $HTTP_POST_VARS[password]

                    if you wish for people to vote for your site to get a higher ranking
                    Please post this link someware in your site.

                    <a href=\"http://$domain$dir$in\">Vote For Us</a>.

                    Thank you
                    Admin at $site_title.";
         mail($HTTP_POST_VARS['email'],$subject,$message,"FROM: $site_title Admin<$uemail>");
         include($phpht_real_path . 'includes/page_header.php');
         $link = "index.php";
         $display = "Your website has been added.Your login information has been sent to $HTTP_POST_VARS[email]. You will recieve another email once the admin activated your account.";
         $template->getFile(array(
                            'success' => 'success.tpl')
         );
         $template->add_vars(array(
                            'L_SUCCESS' => $lang['success'],
                            'DISPLAY' => $display,
                            'LINK' => $link)
         );
         $template->parse("success");
         include($phpht_real_path . 'includes/page_footer.php');
         exit();
      } 
   } else {
      include($phpht_real_path . 'includes/page_header.php');
      $display = "Your website has been added.";
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
      include($phpht_real_path . 'includes/page_footer.php');
      exit();
   }  
}
?>          