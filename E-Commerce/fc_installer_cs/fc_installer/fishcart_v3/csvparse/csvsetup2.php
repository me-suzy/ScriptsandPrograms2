<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2002  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972
   
   CSVParse version 2.04 created by Chris Carroll
   ctcarroll@mindspring.com
   Completely modified version of CSVParse based on Simon Weller's original work.
*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

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
 /* 
	** open config file for writing 
	*/
	$config = fopen("./includes/csvconfig.php","w+");
	
	/*
	** make sure the open was successful
	*/
	if(!($config))
	{
		print("Error: ");
		print("'csvconfig.php' could not be opened, please check your file/directory permissions\n");
		
		print "</tr>\n";
print "</table>\n";
print "</body>\n";
print "</html>";
		exit;
		
	}

	$configstuff=stripslashes($configstuff); 
	// write config data to file 
	fputs($config, $configstuff);
	fclose($config); // close the config file
?>
The config file has been updated, thank you.
<p><a href="index.php">Back to the menu</a></p>
<p><a href="csvsetup.php">Back to the CSV-Parse Setup</a> Don't forget to run CSV-Parse Field Upload Selection if you haven't set it already.</p>
  </tr>
</table>
</body>
</html>
