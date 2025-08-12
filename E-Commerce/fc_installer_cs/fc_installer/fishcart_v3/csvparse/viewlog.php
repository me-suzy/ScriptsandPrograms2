<?php 
/* Copyright by NZSERVERS, authored by Simon Weller (simon@nzservers.com). All rights reserved.
By using this web application you are "leasing it" from NZSERVERS, at all times NZSERVERS holds
the copyright and has the rights to terminate use of this application without warning should
NZSERVERS see this applications use as unlawful */
?>	
<html>
<head>
	<title>CSV-Parse Setup</title>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="98%" height="11">&nbsp;</td>
    <td colspan="2" height="11" width="2%" bgcolor="#000000">&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td width="98%" height="62" valign="bottom"><img src="images/cvsparselogo.gif" width="242" height="52"></td>
    <td colspan="2" height="231" rowspan="2" bgcolor="#CCCCCC" width="2%"> 
      <p>&nbsp;</p>
      <p>&nbsp; </p>
    </td>
  </tr>
  <tr valign="top"> 
    <td width="98%" height="170"> 
      <p><br>
 <?
	  
 /** open file for reading
	*/
	$logfile = fopen("./includes/csvparse.log","r");

	/*
	** make sure the open was successful
	*/
	if(!($logfile))
	{
		print("Error:");
		print("'csvparse.log' could not be read, please check your file permissions\n");
		print "</tr>\n";
print "</table>\n";
print "</body>\n";
print "</html>";
		exit;
	}

	while(!feof($logfile))
	{
		// read a line from the file 
		$Line = fgets($logfile, 255);
	
		print("$Line <BR>\n");
	}

	fclose($logfile); // close the file

?>
<p><a href="index.php">Back to the menu</a></p>
  </tr>
</table>
	
</body>
</html>
