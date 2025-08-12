<?php

/*  
 *  Registration Form
 *  (c) 2006 Philip Shaddock All rights reserved.
 *      www.ragepictures.com 
*/ 	

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
$password = $_GET['password'];
$password2 = $_GET['password2'];
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$organization = $_GET['organization'];
$email = $_GET['email'];
$phone = $_GET['phone'];
$address = $_GET['address'];
$address2 = $_GET['address2'];
$city = $_GET['city'];
$state = $_GET['state'];
$country = $_GET['country'];
$postal = $_GET['postal'];
$subscribe = $_GET['subscribe'];
$gid = $_GET['gid'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Registration Form</title>
<link href="<?php echo CMS_WWW; ?>/templates/css/basestyles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/ico" href="<?php echo CMS_WWW; ?>/favicon.ico" />
</head>

<body id="bd">

<!--Outside Table-->
	<table align="center" cellspacing="0" id="alertTable">
      <tr><td>
	  <table align="center" width="100%" border="0" cellpadding="5" cellspacing="0" >
      <tr>
        <td colspan="2" id="alertHeader">Register</td>
      </tr>
      <tr><td>
	  <table align="center" width="80%" border="0" cellpadding="3" cellspacing="0" >
	  
	  
       
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
			echo "<font color=\"red\" >!</font>" . " <span class=\"smallText\" >A valid email address is required.</span>";
			echo "&nbsp;<br /><br /></td></tr>";  
		  
		  }
 ?>
 
 
<FORM METHOD=post ACTION="register_formPro.php">

  
  <tr><td class="normalText" colspan="2"><b>Username and Password</b></td></tr> 
	
      <TR>
        <TD class="smallText" >
         <?php echo  CUSERNAME; ?>:<font color="red">*</font>
        </TD>
        <TD>
          <input type="text" name="username" value="<? echo $username; ?>" size="25" ID="usernameame" maxlength="15">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo CPASSWORD; ?>:<font color="red">*</font>
        </TD>
        <TD>
          <input type="password" name="password" value="<? echo $password; ?>" size="25" ID="password" maxlength="15">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo  PASSAGAIN; ?>:<font color="red">*</font>
        </TD>
        <TD class="smallText" >
          <input type="password" name="password2" value="<? echo $password2; ?>" size="25" ID="password2" maxlength="15">
        </TD>
      </TR>
   
 
<br />
  
  <tr><td colspan="2">
    <tr><td class="normalText" colspan="2"><b>Contact Information</b></td></tr>
    </td></tr>
	  <TR>
        <TD class="smallText" >
          <?php echo  FIRSTNAME; ?>:<font color="red">*</font>
        </TD>
        <TD>
          <input type="text" name="first_name" value="<? echo $first_name; ?>" size="25" ID="first_name" maxlength="15">
        </TD>
      </TR>
      <TR>
        <TD class="smallText" >
          <?php echo  LASTNAME; ?>:<font color="red">*</font>
        </TD>
        <TD>
          <input type="text" name="last_name" value="<? echo $last_name; ?>" size="25" ID="last_name" maxlength="20">
        </TD>
      </TR>
      <TR>
        <TD class="smallText" >
          <?php echo  EMAIL; ?>:<font color="red">*</font>
        </TD>
        <TD>
          <input type="text" name="email" value="<? echo $email; ?>" size="40" ID="email">
        </TD>
      </TR>
      <TR>
        <TD class="smallText" >
          <?php echo  PHONE; ?>:
        </TD>
        <TD>
          <input type="text" name="phone" value="<? echo $phone; ?>" size="25" ID="phone">
        </TD>
      </TR>
      <TR>
        <TD class="smallText" >
          <?php echo  ORGANIZATION; ?>:
        </TD>
        <TD>
          <input type="text" name="organization" value="<? echo $organization; ?>" size="50" ID="organization">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo  ADDRESS; ?>:
        </TD>
        <TD>
          <input type="text" name="address" value="<? echo $address; ?>" size="50" ID="address">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo  ADDRESS; ?>:
        </TD>
        <TD>
          <input type="text" name="address2" value="<? echo $address2; ?>" size="50" ID="address2">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo  CITY; ?>:
        </TD>
        <TD>
          <input type="text" name="city" value="<? echo $city; ?>" size="50" ID="city">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo STATE; ?>:
        </TD>
        <TD>
          <input type="text" name="state" value="<? echo $state; ?>" size="50" ID="state">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo COUNTRY; ?>:
        </TD>
        <TD>
          <input type="text" name="country" value="<? echo $country ?>" size="50" ID="country">
        </TD>
      </TR>
	  <TR>
        <TD class="smallText" >
          <?php echo POSTAL; ?>:
        </TD>
        <TD>
          <input type="text" name="postal" value="<? echo $postal; ?>" size="15" ID="postal">
        </TD>
      </TR>
    
 


  <tr> <td>&nbsp;</td><td>
    <INPUT TYPE=submit VALUE="<?php echo SUBMIT; ?>">
    <INPUT TYPE=reset VALUE="<?php echo RESET; ?>">
  </td></tr>
  <tr><td class="smallText" colspan="2" >&nbsp;
  
  </td></tr>
</FORM>

  <tr><td class="smallText">
    <A HREF="<?php echo CMS_WWW; ?>">Home</A><br />
	<A HREF="login.php"><?php echo LOGIN; ?></A><br />
	<A HREF="changepass.php"><?php echo CHANGEPASS; ?></A><br />
	<A HREF="changeemail.php"><?php echo CHANGEEMAIL; ?></A><br />
	<A HREF="lostpass.php"><?php echo  LOST; ?></A>

<br />&nbsp;</td></tr></table>
	</td></tr></table>

</body>
</html>