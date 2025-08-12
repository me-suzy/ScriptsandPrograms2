<?php

/***************************************************************************

 module.php
 -----------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 31 - 08 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com */
 
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/
 
require_once ("./rootdatapath.php");
require_once ($GLOBALS["rootdp"]."include/content.php");
includeLanguageFiles('admin','main');

$GLOBALS["subject"] = "articel from ".$GLOBALS["websiteurl"];
$GLOBALS["articleurl"] = sprintf("%s%s%s","http://",$HTTP_HOST,$_GET["article"]);

if ($_POST["submitted"] == "yes") {
	//	User has submitted the data
	$error = ValidateMessage();
	if ($error == '') {
		SendMessage();
		exit;
	}
}

frmMailForm($error);


function frmMailForm($error='')
{
	global $_SERVER, $_GET;

	HTMLHeader('mailform');
	StyleSheet();
	?>
	</head>
	<body marginwidth="0" marginheight="0" leftmargin="0" rightmargin="0" topmargin="0" class="BackgroundContent">

	<form name="tellafriend" action="<?php echo $_SERVER["PHP_SELF"]; if ($_SERVER["QUERY_STRING"] != '') { echo '?'.$_SERVER["QUERY_STRING"]; } ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="submitted" value="yes">
	<table>
		<?php
		if ($error != '') {
			echo '<tr><td colspan="2"';
			echo $error;
			echo '</td></tr>';
		}
		?>
		<tr>
			<td valign="top" align="right"><?php echo $GLOBALS["tMailTo"]; ?></td>
			<td valign="top"><input name="Name" size="40" value=""></td>
		</tr><tr>
			<td valign="top" align="right"><?php echo $GLOBALS["tMailFromName"]; ?></td>
			<td valign="top"><input name="fromName" size="40" value=""></td>
		</tr><tr>
			<td valign="top" align="right"><?php echo $GLOBALS["tMailFromEMail"]; ?></td>
			<td valign="top"><input name="fromEMail" size="40" value=""></td>
		</tr><tr>
			<td valign="top" align="right"><?php echo $GLOBALS["tMailSubject"]; ?></td>
			<td valign="top"><input name="Subject" size="60" value="<?php echo $GLOBALS["subject"]; ?>" disabled></td>
		</tr><tr>
			<td valign="top" align="right"><?php echo $GLOBALS["tMailMessage"]; ?></td>
			<td valign="top"><textarea name="Message" rows="8" cols="60"></textarea></td>
		</tr><tr>
			<td colspan="2" align="center"><input type="submit" value="<?php echo $GLOBALS["tMailSend"]; ?>" name="submitform">
			<input type="reset" value="<?php echo $GLOBALS["tMailReset"]; ?>" name="reset"></td>
		</tr>
	</table>
	</form>

</body>
</html>
<?php
}

function ValidateMessage($error='')
{
	global $_POST;
	
	$toEMail = $_POST["Name"];
	$fromName = $_POST["fromName"];
	$fromEMail = $_POST["fromEMail"];
	$Message = $_POST["Message"];

	// check required fields
	$require = "toEMail,fromName,fromEMail,Message";
	$dcheck = explode(",",$require);
	while(list($check) = each($dcheck)) {
		if(!$$dcheck[$check]) {
			$error .= "Please enter $dcheck[$check]<br>";
		}
	}

	// check email address
	if ((!ereg(".+\@.+\..+", $fromEMail)) || (!ereg("^[a-zA-Z0-9_@.-]+$", $fromEMail))) {
		$error .= "Invalid From-email address<br>";
	}
	if ((!ereg(".+\@.+\..+", $toEMail)) || (!ereg("^[a-zA-Z0-9_@.-]+$", $toEMail))) {
		$error .= "Invalid To-email address<br>";
	}

	return $error;
} // function ValidateMessage()


function SendMessage()
{
	global $_POST;
	$charset="iso-8859-1";
	
	
	$toEMail = $_POST["Name"];
	$toAddress = $toEMail;
	$Subject = $GLOBALS["subject"];
	$fromName = $_POST["fromName"];
	$fromEMail = $_POST["fromEMail"];
	$fromAddress = $fromName.' <'.$fromEMail.'>';
	$headers = 'From: '.$fromAddress."\n";
	$header .= "X-Mailer: PHP/".phpversion()."\n";
	$header .= "Content-Type: text/plain";
	$message_header = $fromName." has send you an article from ".$GLOBALS["websiteurl"]." please click ".$GLOBALS["articleurl"]." to visit the site\n\n"; 
	$Message = $message_header.$_POST["Message"];
	

	if(mail($toAddress,$Subject,$Message,$headers)){
		echo "Message send sucessfully";
	}
	else{ echo "Could not send message";}
} // function SendMessage()
?>