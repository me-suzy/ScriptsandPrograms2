<?
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
require("engine.inc.php");
?>
<html>
<head>
<title>Calendar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font size="4" face="Arial, Helvetica, sans-serif"><b><font color="#003366">Calendar 
  of Events [ <?php print $dayd; ?> ]</font></b></font></p>
<table width="100%" border="0" cellspacing="3" cellpadding="4" bordercolor="#FFFFFF" align="center">
  <tr> 
    <td width="125" bgcolor="#ECF2F9"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif" color="#333333"><b>Title 
        of Event</b></font></div></td>
    <td width="50" bgcolor="#ECF2F9"><div align="center"><strong><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Time</font></strong></div></td>
    <td bgcolor="#ECF2F9"> <div align="left"><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"><b>Event 
        Information</b></font></div></td>
  </tr>
  <?php 
$result = mysql_query ("SELECT * FROM cnpCalendar
						 WHERE date LIKE '$dayd'
						 AND nl LIKE '$nl'
                       	ORDER BY header
");
if ($row = mysql_fetch_array($result)) {

do {
?>
  <tr> 
    <td width="125" bordercolor="#CCCCCC"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?php print $row["header"]; ?> </font></div></td>
    <td width="50" bordercolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?php print $row["time"]; ?></font></div></td>
    <td bordercolor="#CCCCCC"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?php print $row["info"]; ?> </font></div></td>
  </tr>
  <?php
} while($row = mysql_fetch_array($result));

} else {print "Date is currently empty.
          ";} ?>
</table>
<br>
<hr width="100%" size="1" noshade>
<p> 
  <? include("events.php"); ?>
</p>
</body>
</html>
