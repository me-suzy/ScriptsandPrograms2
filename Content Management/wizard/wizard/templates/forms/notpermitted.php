<?php

// Page Permission
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
<title>Access Denied</title>
<link href="<?php echo CMS_WWW; ?>/templates/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo CMS_WWW; ?>/templates/css/basestyles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/ico" href="<?php echo CMS_WWW; ?>/favicon.ico" />
</head>

<body id="bd">


<!--Outside Table-->
	<table align="center" cellspacing="0" id="alertTable">
	<tr>
	<td>
	<table width="600px" align="center" border="0" cellpadding="1" cellspacing="0">
      	<tr>
			<td >
	  			<table align="center" width="100%" border="0" cellpadding="5" cellspacing="0">
      				<tr>
        				<td id="alertHeader">Access Denied</td>
      				</tr>
				</table>
			</td>
		</tr>
		
		<tr><td >&nbsp;</td></tr>
      <tr>
        <td >      
        <p align="center">You do not belong to a group permitted to see this page.</p>
          <p class="smallText">
		  <div align="center">
		  <a  href="<?php echo CMS_WWW; ?>">Home</a><br />
		      <?php 
		  //only allow to register if the site is configured (register field in config table)
		  

		  if ($config['register'] == "on") {
          echo "<a href=\"register_form.php\">Register</a><br />";
		  
		  }
		  
		  ?>
		  	<a href="login.php">Login</a><br />&nbsp;
	    	
			</div></p>
		  </td></tr>
    </table>    
    
    </td>
    
  </tr>
</table>

</body>
</html>