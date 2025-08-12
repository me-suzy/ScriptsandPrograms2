<html>
<head><title>Comus TGP Preferred Submitter Page</title>
</head>
<body>
<?
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");
$session = SessionID(5);

echo "<center><a href=\"index.php\"><b><font size=-1 face=arial>Return to main page</font></b></a></center>
<br>
<table width=650 border=0 cellspacing=2 cellpadding=2 align=center>
  <tr> 
    <td colspan=2 bgcolor=#$bgcolor> 
      <div align=center><b><font face=Arial size=-1 color=white>Preferred Submitter</font></b></div>
    </td>
  </tr>
</table>
<form method=post action=preferred.php><center><br><font color=red><h2>$message3</h2></font><br>
  <table width=600 border=0 cellspacing=3 cellpadding=3>
    <tr>
      <td>
      </td>
    </tr>
    <tr>
      <td>Preferred Submitters Email address: 
        <input type=text name=\"preferred_email\">
          <input type=submit name=preferred value=\"Add Preferred\">
      </td>
    </tr>
  </table>
</form><br></center>";

/* Delete Preferred Submitter */

if (isset($zap)) {

	$Query = "DELETE FROM tblPreferred WHERE id='$id'";
		$result = mysql_query($Query, $conn);
}

/* ADD Preferred submitter */

if (isset($preferred)) {

        //update someone to the preferred list 
	mysql_query("INSERT into tblPreferred (email, pass) VALUES ('$preferred_email', '$session')");
			$message3 = "$preferred_email added to Preferred";

/* Send confirmation email to submitter	*/

$recipient = "$preferred_email";
$subject = "Re: Preferred Submitter";
$message = "You have been added to the $sitename Preferred Submitters list.\n\nPlease be sure to use the password below each time you post a gallery.\n\nPassword: $session \n\nRegards,\n$siteowner\n";
$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\n";
	
	mail ($recipient, $subject, $message, $extra);
}

/* View all of Preferred */

	$query = "SELECT * FROM tblPreferred order by id DESC";
	$result = mysql_query ($query)
        or die ("Query failed");

echo "<center><table width=300 border=0 cellspacing=3 cellpadding=3><tr>";
	
	if ($result) {
	while ($r = mysql_fetch_array($result)) { 

	$id = $r["id"];
	$email1 = $r["email"];

	echo "<tr> 
    <td width=61> 
      <div align=left><a href=\"preferred.php?view=all&zap=yes&id=$id\">Delete</a></div>
    </td>
    <td colspan=2>$email1</td>
    <td width=90>&nbsp;</td>
  </tr>";
		
		} //end of while loop
	echo "</table></center>";
	
	}	// end of result

?>
 </body>
</html>