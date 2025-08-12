<?php

/*  
 *  Contact Us Form
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


$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$organization = $_GET['organization'];
$email = $_GET['email'];
   ?>
 <!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/Dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contact Form</title>
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
			<td colspan="2">
	  			<table align="center" width="100%" border="0" cellpadding="5" cellspacing="0">
      				<tr>
        				<td id="alertHeader">Contact Us</td>
      				</tr>
				</table>
			</td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>

<!-- Message -->
			<?php
				$message = $_GET['message'];
 				if ($message) {
        
			echo "<tr>";
				echo "<td colspan=\"2\" height=\"16\" class=\"message\" valign=\"top\">&nbsp;&nbsp;&nbsp;";
				echo $message . "<br />&nbsp;<br /></td></tr>";  
	          
	      }
		  
 ?>
 
<!-- Form input column -->

  <tr>
  	<td class="smallText">
  	<table align="left" style="margin-left: 20px" width="300px" >
  		<tr>
			<td width="100%" colspan="2">
  				<FORM METHOD=post ACTION="contact_formPro.php">
    			<h3>Contact Information</h3><br />
			</td>
		</tr>
   
	  <tr>
	  	<td>
          <?php echo FIRSTNAME; ?>:<font color="red">*</font>
		</td>
        <td >
          <input type="text" name="first_name" value="<? echo $first_name; ?>" size="15" ID="first_name" maxlength="15">
        </td>
      </tr>
      <tr>
        <td>
          <?php echo LASTNAME; ?>:<font color="red">*</font>
        </td>
        <td>
          <input type="text" name="last_name" value="<? echo $last_name; ?>" size="20" ID="last_name" maxlength="20">
        </td>
      </tr>
      <tr>
        <td>
          <?php echo EMAIL; ?>:<font color="red">*</font>
        </td>
        <td>
          <input type="text" name="email" value="<? echo $email; ?>" size="25" ID="email">
        </td>
      </tr>
	  <tr>
        <td>
          <?php echo "Email Again"; ?>:<font color="red">*</font>
        </td>
        <td>
          <input type="text" name="email2" value="<? echo $email2; ?>" size="25" ID="email">
        </td>
      </tr>
      <tr>
        <td>
          <?php echo ORGANIZATION; ?>:
        </td>
        <td>
          <input type="text" name="organization" value="<? echo $organization; ?>" size="25" ID="organization">
        </td>
      </tr>
  
  		<tr>
			<td colspan="2"><br />
			Enter your message:
    		</td>
		</tr>
      	<tr>
        	<td colspan="2">
			<span class="smallText"><textarea name="comment" rows="5" cols="67"><? echo $comment; ?></textarea></span>        
			</td>
      	</tr>	  
  
  		<tr>
			<td colspan="2">
    		<INPUT TYPE=submit VALUE="<?php echo SUBMIT; ?>">
    		<INPUT TYPE=reset VALUE="<?php echo RESET; ?>">
			</FORM>
			</td>
		</tr>
	
		
		<tr>
			<td colspan="2"><a href="<?php echo CMS_WWW; ?>">Home</a></td>
		</tr>
		
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		
	</table>
	</td>
	
<!-- Our Coordinates Column -->
	
<td width="220" align="left" valign="top" class="smallText">

		<h3><?php echo CF_COORDINATES; ?></h3><br />
		<?php 	echo $config['siteAdmin'] . "<br />";
				echo $config['email'] .  "<br />";
		 		if ($config['phone']) {
					echo "tel " . $config['phone'];} 
				if ($config['fax']) {
				echo "<br />fax " . $config['fax'];} 

				echo "<br /><br /><b>" . $config['company'] . "</b><br />";
				echo $config['address'] . "<br />";
				echo $config['city'] . "<br />";
				echo $config['state'] . "<br />";
				echo $config['country'] . "<br />";
				echo $config['postal'] . "<br />";
        ?>
		<br /><img border="0" src="<?php echo CMS_WWW; ?>/images/common/vcard.gif" vspace="-8" width="19" height="15"> <a href="<?php echo "vcard.php"; ?>">vCard</a>

</td></tr>
</table>
</td></tr>
</table>
</body>
</html>