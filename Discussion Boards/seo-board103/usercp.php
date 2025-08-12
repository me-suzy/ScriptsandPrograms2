<?php

require ('smilies/smilies.php');

if(!defined('SEO-BOARD'))
{
  die($lang['fatal_error']);
}

if ($user_id == 0)
  die($lang['fatal_error']);

$errormessage = null;
$username = $user_name;
if (isset($_POST['usercp']))
{
  $userbio = stripslashes($userbio);
  if (!preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#i',$useremail)) $errormessage = get_error($lang['invalid_email']);
  
  if (is_null($errormessage) && (strlen($newpass) != 0))
  {
    if (strlen($newpass) < 4) $errormessage = get_error($lang['pass_short']);
    else
    if (strlen($newpass) > 30) $errormessage = get_error($lang['pass_long']);
    else
    if ($newpass != $newpassconfirm) $errormessage = get_error($lang['pass_diff']);    
  }
  
  //convert URLs into links
  if (is_null($errormessage) && isset($makeurls) && isset($bbcodes))
    $userbio = makeURLs($userbio);
    
  if (is_null($errormessage))
    $errormessage = validate_text($userbio, isset($bbcodes));
  
  if (!is_null($errormessage)) //show error
  {
    $title = $forumtitle.' &raquo; '.$lang['user_options'];
    require('forumheader.php');
  
    $htmlzones = get_timezone_select($usertimezone);
    $emailpublic_check = isset($emailpublic) ? 'checked' : null;
    $viewonline_check = isset($viewonline) ? 'checked' : null;
    $bbcodes_check = isset($bbcodes) ? 'checked' : null;
    $emoticons_check = isset($emoticons) ? 'checked' : null;
    $makeurls_check = isset($makeurls) ? 'checked' : null;
    $smiliesbar = generate_smilies_bar('document.PForm.userbio');    
    print eval(get_template('userpanel'));
  }
  else
  {
    if (!isset($emailpublic)) 
      $emailpublic = 0;
    
    if (!isset($viewonline))
      $viewonline = 0;

    $user_bio_status = 0;
    if (isset($bbcodes))
    {
      $user_bio_status |= 1;
      //are there any bbcodes to format?
      if (format_bbcodes($userbio) != $userbio)
        $user_bio_status |= 2;
    }
    if (isset($emoticons)) 
    {
      $user_bio_status |= 4;
      //are there any smilies to convert?
      if (str_replace($sm_search, $sm_replace, $userbio) != $userbio)
        $user_bio_status |= 8;
    }
      
    $userbio = addslashes($userbio);
    
    if (strlen($newpass) != 0)
    {
      $newpass = sha1($shaprefix.$newpass);
      mysql_query("UPDATE {$dbpref}users SET user_email='$useremail', user_timezone='$usertimezone', user_email_public='$emailpublic', user_allowviewonline='$viewonline', user_bio='$userbio', user_bio_status='$user_bio_status', user_pass='$newpass' WHERE user_id='$user_id'");
      //set new cookie
      setcookie($cookiename, serialize(array($user_id, $newpass)), 0, $cookiepath, $cookiedomain, $cookiesecure);
    }
    else
      mysql_query("UPDATE {$dbpref}users SET user_email='$useremail', user_timezone='$usertimezone', user_email_public='$emailpublic', user_allowviewonline='$viewonline', user_bio='$userbio', user_bio_status='$user_bio_status' WHERE user_id='$user_id'");
    header("Location: {$forumscript}?a=member&m={$user_id}");      
  }
}
else
{
  $title = $forumtitle.' &raquo; '.$lang['user_options'];
  require('forumheader.php');
  
  $smiliesbar = generate_smilies_bar('document.PForm.userbio');
  $result = mysql_query("SELECT user_email, user_timezone, user_email_public, user_allowviewonline, user_bio, user_bio_status FROM {$dbpref}users WHERE user_id = '$user_id'");
  list($useremail, $tz, $emailpublic, $viewonline, $userbio, $userbio_status) = mysql_fetch_row($result);
  $userbio = format_html($userbio);
  $htmlzones = get_timezone_select($tz);
  $emailpublic_check = ($emailpublic == 1) ? 'checked' : null;
  $viewonline_check = ($viewonline == 1) ? 'checked' : null;
  $bbcodes_check = (($userbio_status & 1) != 0) ? 'checked' : null;
  $emoticons_check = (($userbio_status & 4) != 0) ? 'checked' : null;
  $makeurls_check = 'checked';
  print eval(get_template('userpanel'));
}

?>
