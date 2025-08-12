<?php
/***************************************************************************
 *                                OpenMailer Advanced 1.11
 *                            -------------------
 *   created:                : Friday, July 2nd, 2004
 *   copyright               : (C) 2004 Blue-Networks
 *   email                   : admin@blue-networks.net
 *   web                     : http://blue-networks.net
 *
 * This is a complex script, so PLEASE read the readme. If any of the comments
 * Below confuse you, or you have trouble, support is always available at 
 * The forum on http://blue-networks.net or email admin@blue-networks.net
 * If you follow the way everything is currently laid out, you should be OK!
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
?>

<?php if (!isset($submit)):
//This html is the actual form
?>
<form name="form1" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>">
<HTML>                   
  <p><strong><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Mail 
    to: <span class="subhead"> 
    <?php if(isset($destination)){
echo($destination);
printf('<input type="hidden" name="destination" value="%s">',$destination);
}
// you must add in more option values in the same format when adding more recipients
// (you must also modify code below - more details there)
else {
echo('
<select name="destination" id="destination">
<option>Select Recipient
<option>PersonOne
<option>PersonTwo
<option>PersonThree
</select>
');
}
?>
    </span> </font></strong></p>
  <table width="528" border="0" cellspacing="1" cellpadding="0">
    <tr> 
      <td width="109"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Subject:</strong></font></td>
      <td width="416"><font color="#000000" size="1"> 
        <input name="subject" type="text" id="subject">
        </font></td>
    </tr>
    <tr> 
      <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Your 
        Name: </strong></font></td>
      <td><font color="#000000" size="1"> 
        <input name="namefrom" type="text" id="namefrom">
        </font></td>
    </tr>
    <tr> 
      <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Your 
        Email Address:</strong></font></td>
      <td><font color="#000000" size="1"> 
        <input name="emailfrom" type="text" id="emailfrom">
        </font></td>
    </tr>
    <tr> 
      <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Message:</strong></font></td>
      <td><font color="#000000" size="1"> 
        <textarea name="message" cols="50" rows="3" wrap="VIRTUAL" id="message"></textarea>
        </font></td>
    </tr>
  </table>
  <p> <font color="#000000" size="1"> 
    <input type="submit" name="submit" value="Submit">
    </font><font color="#000000" size="2"> </font> </p>
                      </form>
<!-- please, don't remove this line, not only is it copyright infringement, but is unfair for all the time
put into this
-->
<p><font size="1" face="Arial, Tahoma, Verdana">Powered By OpenMail Advanced 1.1 <a href="Http://blue-networks.net">Http://blue-networks.net</a></font></p>
</html>
<?php else:

/* error function */

function error($msg) {    
	?>
	<html>
	<head>    
	<script language="JavaScript">    
	<!--        
		alert("<?=$msg?>");        
		history.back();    
	//-->    
	</script>    
	</head>    
	<body>    
	</body>    
	</html>    
	<?    
	exit;
}



/* Check for blanks start */
// these 4 lines check for blanks and display an error if they are found. You will need to add or remove items
// from this block to add or remove required fields, please follow the format already laid out.

	if ($namefrom=="" or $emailfrom=="" or $message=="" or $subject=="" or strtolower($destination)=="select recipient") {
		error("Not Sent: One or more required fields were left blank.\\n".
			"Please fill them in and try again.");
	}
/* check for blank end */

//email blocker start - to block from emails. add more as shown.
if ($emailfrom=="fake@fake.com" or $emailfrom=="addmore@here.com"){
		error("Not Sent: You are not authorised.\\n".
			"xxxxxxxxxxx");
	}
//end

//ip address blocker start - simply add more like this to expand
if ($_SERVER["REMOTE_ADDR"]=="10.10.10.10"){
		error("Not Sent: You are not authorised.\\n".
			"xxxxxxxxxxx");
}
//end
	
/* email validation start - checks correct name@host.ext form*/
// if you do not wish to use email validation or email is not a required field (above),
// comment out the following 4 code lines by adding a // at the beginning as has been
// done here.

	if ((!ereg(".+\@.+\..+", $emailfrom)) || (!ereg("^[a-zA-Z0-9_@.-]+$", $emailfrom))) {
		error("Not Sent: Invalid email address.\\n".
			"Please correct this and try again.");
	}
	
/* email validation end */
	
/* Start recipient selection */
// This block takes the recipient name (only visible info on form) and translates
// it to an email address, which is stored in the $demail variable. This is never shown
// to the visitor. To add more, simply copy the code from if to } and customise them, using the
// shown format. You must also add the appropriate select option where indicated in the html
// above. Just follow how it is already laid out, it all corresponds to the html above.

if (strtolower($destination)=="personone"){
$demail="neil@blue-networks.net";
}

if (strtolower($destination)=="persontwo"){
$demail="person2@email.com";
}

if (strtolower($destination)=="personthree"){
$demail="person3@email.com";
}
// leave the following line, it prevents users doing mail.php?destination=fakeperson
if (!isset($demail)){
error("Not Sent: Unknown recipient.");
}

//finally, send our email and show confirmation.
$body=sprintf("%s%s%s%s",$message,"\n\nIP Logged:",$REMOTE_ADDR,"\n\n"); 
mail($demail, $subject, $body, "From: $namefrom <$emailfrom>\n");

//this html is the success notice
?>
<HTML>
<HEAD>
<TITLE>Success!</TITLE>
</HEAD>
<BODY>
<font face="arial" size="2">Thankyou. Your feedback was sent to the recipient selected.</font>
</BODY>
</HTML>
<?php
//end the if clause
endif;
?>