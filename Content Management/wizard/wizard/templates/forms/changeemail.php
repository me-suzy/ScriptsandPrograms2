<?php

// Change Email Form
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

<body id="bd">


<!--Outside Table-->
	<table align="center" cellspacing="0" id="alertTable">
      <tr><td>
	  <table width="100%" border="0" cellpadding="5" cellspacing="0" >
      <tr>
        <td colspan="2" id="alertHeader">Change Email Address</td>
      </tr>
      <tr><td>
	  <table align="center" width="80%" border="0" >
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>
       

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
			echo "<font color=\"red\" >!</font>" . " <span class=\"smallText\" >The new email address will be used to confirm this change.</span>";
			echo "&nbsp;<br /><br /></td></tr>"; 
		  
		  }
 
 
 $username= $_GET['username'];
 $email = $_GET['email'];
	
 ?>	
  
  <FORM METHOD=post ACTION="changeemailPro.php">
  <FIELDSET style="width: 350px; ">
    
   
	
      <TR>
        <TD class="smallText" >
          <LABEL FOR=username ACCESSKEY=U><?php echo  CUSERNAME; ?>:</LABEL>
        </TD>
        <TD>
          <input type="text" name="change_user_name" value="<? echo $username; ?>" size="25" ID="username" maxlength="15">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <LABEL FOR=password ACCESSKEY=P><?php echo  CPASSWORD; ?>:</LABEL>
        </TD>
        <TD>
          <input type="password" name="password1" size="25" ID="password">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <LABEL FOR=email ACCESSKEY=E><?php echo  EMAIL; ?>:</LABEL>
        </TD>
        <TD>
          <input type="text" name="new_email" value="<? echo $email; ?>" size="40" ID="email">
        </TD>
      </TR>
	  
    
  </FIELDSET>
  <tr> <td>&nbsp;</td><td>

    <INPUT TYPE=submit VALUE="<?php echo  SUBMIT; ?>">
    <INPUT TYPE=reset VALUE="<?php echo  RESET; ?>">

 </FORM>
 </td></tr><tr><td class="smallText" colspan="2" >
 	<?php $sitename = SITE_NAME;
      $siteaddress = CMS_WWW;
	?>
    <A HREF="<?php echo CMS_WWW; ?>">Home</A><br />
	<A HREF="register_form.php"><?php echo  REGISTER; ?></A><br />
	<A HREF="login.php"><?php echo  LOGIN; ?></A><br />
	<A HREF="logout.php"><?php echo  LOGOUT; ?></A><br />
	<A HREF="changepass.php"><?php echo  CHANGEPASS; ?></A><br />
	<A HREF="lostpass.php"><?php echo  LOST; ?></A><br />

</td></tr></table>
	</td></tr></table>
</body>
</html>