<?php

/*********************************************

Go Redirector PHP Configuration Script
Version 0.4
Copyright (c) 2003-2004, StudentPlatinum.com and
the Edvisors Network

Provided under BSD license located at
http://www.studentplatinum.com/scripts/license.php

It is a violation of the license to distribute
this file without the accompanying license and
copyright information.

You may obtain the latest version of this software
at http://www.studentplatinum.com/scripts/

Please visit our corporate page at:
http://www.edvisorsnetwork.com/

*********************************************/

require("../goconfig.php");

/*********************************************
database connection section
*********************************************/
dbinit();

/********************************************
basic index query section
*********************************************/
$sqlview="SELECT * from redirs ORDER BY redirect ASC";
$result=mysql_query($sqlview) or die(mysql_error());

$sqldetail="SELECT id from redirs ORDER BY id ASC";
$detailresult=mysql_query($sqldetail) or die(mysql_error());
/********************************************
time variables query section

The variables in this section determine the 
intervals displayed on the web page
*********************************************/
$past24=time()-86400;
$past7=time()-604800;
$past30=time()-2592000;
$yesterday=date("Y-m-d",$past24);
$thisweek=date("Y-m-d",$past7);
$thismonth=date("Y-m-d",$past30);
$today=date("Y-m-d",time()); // not used but provided for future use

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Go! Redirector Statistics page</title>
</head>
<body> 
<p><strong>Edvisors Network Link Redirector Manager</strong></p> 
<p class="main">This is the master referral table for managing links, <font color="#FF0000">sorted alphabetically by domain name</font>. To use the link manager, send a request to the development department via e-mail that your domain be set up with the go script. Then use the syntax http://www.yourdomain.com/go.php?id=xx where xx is the ID below. (e.g. to link to <a href="http://www.studentplatinum.com">StudentPlatinum.com</a> from <a href="http://www.ParentPLUSLoan.com">ParentPLUSLoan.com</a>, use http://www.parentplusloan.com/go.php?id=2)</p> 
<table width="100%" border="1" cellspacing="0" cellpadding="3"> 
  <tr> 
    <td width="50%"><a href="add.php">&gt;&gt; Add a new link</a></td> 
    <td width="50%"><a href="delete.php">&gt;&gt; Delete a link</a></td> 
  </tr> 
</table> 
<h2> Monthly Detail Reporting Section</h2> 
<form action="report.php" method="post" name="report" id="report"> 
  <table width="100%" border="1" cellspacing="0" cellpadding="3"> 
    <tr> 
      <td>Link ID: </td> 
      <td><select name="linkid" id="linkid"> 
          <?php 
		while ($row=mysql_fetch_assoc($detailresult))
		{
			$therow=$row['id'];
			echo "<option value=\"".$therow."\">".$therow."</option>";
		}
	 ?> 
        </select></td> 
    </tr> 
    <tr> 
      <td>Month:</td> 
      <td><select name="month" id="month"> 
          <option value="1" selected>1</option> 
          <option value="2">2</option> 
          <option value="3">3</option> 
          <option value="4">4</option> 
          <option value="5">5</option> 
          <option value="6">6</option> 
          <option value="7">7</option> 
          <option value="8">8</option> 
          <option value="9">9</option> 
          <option value="10">10</option> 
          <option value="11">11</option> 
          <option value="12">12</option> 
        </select></td> 
    </tr> 
    <tr> 
      <td>Year:</td> 
      <td><select name="year" id="year"> 
          <option value="2004" selected>2004</option> 
          <option value="2003">2003</option> 
          <option value="2002">2002</option> 
        </select></td> 
    </tr> 
    <tr> 
      <td colspan="2"><div align="center"> 
          <input type="submit" name="Submit" value="Show me the detailed report! "> 
        </div></td> 
    </tr> 
  </table> 
</form> 
<p class="main">&nbsp; </p> 
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#999999" class="main"> 
  <tr> 
    <td width="10%"><div align="left"><strong>ID</strong></div></td> 
    <td width="70%"><div align="left"><strong>Link URL</strong></div></td> 
    <td width="5%"> <div align="center"><strong>24 hrs</strong></div></td> 
    <td width="5%"> <div align="center"><strong>7 days</strong></div></td> 
    <td width="5%"> <div align="center"><strong>30 days</strong></div></td> 
    <td width="5%"><div align="right"><strong>Total</strong></div></td> 
  </tr> 
  <?php 
		while ($row=mysql_fetch_assoc($result))
		{
			echo "<tr><td width=\"10%\">";
			echo $row['id'];
			$rownum=$row['id'];
			echo "</td><td width=\"80%\">";
			echo $row['redirect'];
			echo "</td><td width=\"5%\" align=\"center\">";
			// previous 24 hours' count
			$sql24="select count(*) from stats where linkid=$rownum and date >= '$yesterday'";
			$result24=mysql_query($sql24);
			$row24=mysql_fetch_array($result24);
			echo $row24[0];
			echo "</td><td width=\"5%\" align=\"center\">";
			// previous 7 days count
			$sql7="select count(*) from stats where linkid=$rownum and date >= '$thisweek'";
			$result7=mysql_query($sql7);
			$row7=mysql_fetch_array($result7);
			echo $row7[0];
			echo "</td><td width=\"5%\" align=\"center\">";

			// previous 30 days count
			$sql30="select count(*) from stats where linkid=$rownum and date >= '$thismonth'";
			$result30=mysql_query($sql30);
			$row30=mysql_fetch_array($result30);
			echo $row30[0];
			echo "</td><td width=\"5%\" align=\"center\">";
			//total count
			$sqlcount="select count(*) from stats where linkid=$rownum";
			$resultcount=mysql_query($sqlcount);
			$rowcount=mysql_fetch_array($resultcount);
			echo $rowcount[0];
			echo "</td></tr>";
		}
		?> 
</table> 
</body>
</html>
