<?php

// About Us
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
<title>Confirmation</title>
<link href="<?php echo CMS_WWW; ?>/templates/css/basestyles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/ico" href="<?php echo CMS_WWW; ?>/favicon.ico" />
</head>

<body id="bd">


<!--Outside Table-->
	<table align="center" border="1" style="margin-top: 20px" cellpadding="1" cellspacing="0" id="alertTable">

      <tr><td>
	  <table width="100%" border="0" cellpadding="5" cellspacing="0" >
      <tr>
        <td colspan="2" id="alertHeader">About Us</td>
      </tr>
      <tr><td>
	  <table align="center" width="90%" border="0" cellpadding="5" cellspacing="0" >
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>
       
<!--Inside Table-->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td >
<p >
			Rage Pictures has 
            designed and produced software applications and games for the past 15 years for projects in the Middle East, Europe, North America and Asia.</p>
		<p class="normaltext" >From a server side framework, a morph program, and numerous interactive games for private and public institutions, Rage has blended art and science. </p>
        <p class="normaltext" style="margin-left: 20; margin-right: 20; margin-top: 3; text-align:justify">We have published books on interactive technologies, written articles, appeared on TV and spoken at industry conferences. </p>
          <p class="normaltext" style="margin-left: 20; margin-right: 20; margin-top: 3; text-align:justify">Rage is currently developing downloadable content for mobile handsets. </p>
		  <p align="center">Return to <a href="<?php echo CMS_WWW; ?>">Home</a></p>
<p>&nbsp;</p>


<!-- End inside table -->
	</td>
    
  </tr>
</table>
</td>
    
  </tr>
</table>