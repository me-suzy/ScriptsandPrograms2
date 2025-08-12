<html>

<head>

<title>CalendarNow</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>



<body bgcolor="#FFFFFF" text="#000000">
<div align="left"><font size="4" face="Arial, Helvetica, sans-serif"><strong>View 
  <? print $dayd; ?></strong></font> <br>
  <br>
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="3" bordercolor="#FFFFFF" align="center">
  <tr> 
    <td width="150" bgcolor="#EAEAEA"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif" color="#333333"><b>Header</b></font></div></td>
    <td bgcolor="#EAEAEA"> <div align="left"><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"><b>Info</b></font></div></td>
    <td width="38" bordercolor="#CCCCCC">&nbsp;</td>
  </tr>
  <?php 
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
$result = mysql_query ("SELECT * FROM cnpCalendar

						 WHERE date LIKE '$dayd'
						 AND nl LIKE '$nl'

                       	ORDER BY header

");

if ($row = mysql_fetch_array($result)) {



do {

?>
  <tr> 
    <td width="150" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?php print $row["header"]; ?> </font></div></td>
    <td bordercolor="#CCCCCC"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?php print $row["info"]; ?> </font></div></td>
    <td width="38" bordercolor="#CCCCCC"> <div align="center"><font face="Arial, Helvetica, sans-serif" size="2"><a href="main.php?page=edit&id=<?php print $row["id"]; ?>&nl=<? print $nl; ?>"><img src="media/edit.gif" width="11" height="7" border="0"></a> 
        | <a href="main.php?nl=<? print $nl; ?>&page=del&id=<?php print $row["id"]; ?>"><img src="media/del.gif" width="11" height="7" border="0"></a></font></div></td>
  </tr>
  <tr> 
    <td colspan="3" bordercolor="#CCCCCC"> <div align="center">
        <hr width="100%" size="1" noshade>
      </div>
      <div align="center"></div></td>
  </tr>
  <?php

} while($row = mysql_fetch_array($result));



} else {print "Calendar for this date is currently empty.

          ";} ?>
</table>
  
  
<br>
<br>
<table width="50%" border="0" cellspacing="0" cellpadding="1" bgcolor="#EAEAEA">
  <tr> 
    <td bgcolor="#EAEAEA"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif">KEY</font></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
        <tr> 
          <td width="50%"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/del.gif" width="11" height="7" border="0"> 
              = Delete</font></div></td>
          <td width="50%"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/edit.gif" width="11" height="7" border="0"> 
              = Edit</font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>

</html>

