<?php

// Lost Password Form
// Copyright 2006 Philip Shaddock www.ragepictures.com

// main configuration file
	include_once '../../inc/config_cms/configuration.php';
// database class
	include_once '../../inc/db/db.php';
// language translation
	include_once '../../inc/languages/' . $language . '.public.php';
	// configuration parameters
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "config");
	$config = $db->next_record();
	$db->close();	
// authentication
	include_once '../../inc/functions/user.php';
	
	
if (user_isloggedin()) {
        user_logout();
        $username='';
}	
$username = $_GET['username'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Lost Password</title>
<link href="<?php echo CMS_WWW; ?>/templates/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo CMS_WWW; ?>/templates/css/basestyles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/ico" href="<?php echo CMS_WWW; ?>/favicon.ico" />
</head>

<body id="bd">


<!--Outside Table-->
	<table align="center" cellspacing="0" id="alertTable">
      <tr><td>
	  <table width="100%" border="0" cellpadding="5" cellspacing="0" >
      <tr>
        <td colspan="2" id="alertHeader">Lost Password</td>
      </tr>
      <tr><td>
	  <table align="center" width="80%" border="0" cellspacing="0" >
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>
       
<!--Inside Table-->
 <?php
		
	
 $message = $_GET['message'];
 if ($message) {
        
		echo "<tr>";
			echo "<td colspan=\"2\" height=\"16\" class=\"message\" valign=\"top\"><span class=\"message\">";
			echo $message; 
			echo "</span>&nbsp;<br /><br /></td></tr>";  
	          
	      }
		  else {
		  echo "<tr>";
			echo "<td colspan=\"2\" height=\"16\" valign=\"top\">";
			echo "<font color=\"red\" >!</font>" . " <span class=\"smallText\" >Your password will be changed and sent to your email address.</span>";
			echo "&nbsp;<br /><br /></td></tr>";  
		  
		  }
 ?>
  <FORM METHOD=post ACTION="lostpassPro.php">
  <FIELDSET style="width: 350px;">
   
      <TR>
        <TD class="smallText" >
          <LABEL FOR=usernameame ACCESSKEY=U><?php echo CUSERNAME; ?>:</LABEL>
        </TD>
        <TD>
          <input type="text" name="username" value="<? echo $username; ?>" size="25" ID="username" maxlength="15">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <LABEL FOR=email ACCESSKEY=pP><?php echo EMAIL; ?>:</LABEL>
        </TD>
        <TD>
          <input type="text" name="email" value="<? echo $email; ?>" size="40" ID="email">
        </TD>
      </TR>
	  
     
  </FIELDSET>
  <tr> <td>&nbsp;</td><td>
  
    <INPUT TYPE=submit VALUE="<?php echo SUBMIT; ?>">
    <INPUT TYPE=reset VALUE="<?php echo RESET; ?>">
 
 </FORM>
 </td></tr><tr><td class="smallText" colspan="2" >
 	<?php $sitename = SITE_NAME;
      $siteaddress = CMS_WWW;
	?>
    <A HREF="<?php echo CMS_WWW; ?>">Home</A><br />
	<A HREF="register_form.php"><?php echo REGISTER; ?></A><br />
	<A HREF="login.php"><?php echo LOGIN; ?></A><br />
	<A HREF="logout.php"><?php echo LOGOUT; ?></A><br />
	<A HREF="changeemail.php"><?php echo CHANGEEMAIL; ?></A><br />

<br />&nbsp;</td></tr></table>
	</td></tr></table>
</body>
</html>