<?php

// Authentication Authentication Form
// Copyright 2006 Philip Shaddock www.ragepictures.com

// main configuration file
	include_once '../../inc/config_cms/configuration.php';
// database class
	include_once '../../inc/db/db.php';
// language translation
	include_once '../../inc/languages/' . $language . '.public.php';
// authentication
	include_once '../../inc/functions/user.php';
// configuration parameters
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "config");
	$config = $db->next_record();
	$db->close();		
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Change Email Form</title>
<link href="<?php echo CMS_WWW; ?>/templates/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo CMS_WWW; ?>/templates/css/basestyles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/ico" href="<?php echo CMS_WWW; ?>/favicon.ico" />
</head>

<body>
<!--Outside Table-->
<table align="center" cellspacing="0" id="alertTable">
      <tr><td>
	  <table width="100%" border="0" cellpadding="5" cellspacing="0" >
      <tr>
        <td colspan="2" id="alertHeader"><?php echo SITE_NAME; ?></td>
      </tr>
      <tr><td>
	  <table align="center" width="80%" border="0" cellspacing="0" >
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>
       
<!--Inside Table-->

<?php
   	$hash = $_GET['hash'];
	$email = $_GET['email'];
	if ($hash && $email) {
		$worked=user_confirm($hash,$email);
	} else {
	    // the secret hash was wrong
		$message = MISCONFIRM;
		
	}
	
if ($config['user_approve'] == "on") {

    $message = $message . "<br />Registration requires approval by the administrator.";

}

if ($message) {
    
	echo "<p><span class=\"message\">" . $message . "</span></p>";
	
}




$siteaddress = CMS_WWW;
$sitename = SITE_NAME;

echo "
	<p class=\"smallText\"><A HREF=\"".CMS_WWW."\">Home</A><br />
	<A HREF=\"" . CMS_WWW.   "/templates/forms/login.php\">". LOGIN ."</A></p>";
	echo "<p>&nbsp;</p>";
?>
	</td>
    
  </tr>
</table>
</td>
    
  </tr>
</table>
</td>
    
  </tr>
</table>