<?php
	include_once "./checksession.php"; 
	include_once "./includes/settings.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Help Desk Property Management Tracking</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="99%" border="0" cellpadding="0">
  <tr> 
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"> <p align="left"><strong>
	<?

if($OBJ->get('navigation'))
	include_once 'dataaccessheader.php';
else	
	include 'textnavsystem.php';
	?><br>
        
        OCM: Our Crap Management. <br>
        </strong></p></td>
  </tr>
  <tr> 
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="524" valign="top"> <p>&nbsp; 
      </p>
      <table width="75%" border="0">
        <tr> 
          <td height="118" colspan="2" align="left" valign="top"> <p><a href="ocm-main.php"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Report 
              New Excess I. T. Equipment</strong></font></a></p>
            <p><strong><font size="3" face="Arial, Helvetica, sans-serif"><a href="OCMreport.php">Generate 
              Printable Excess Report for property management staff.</a></font></strong></p>
            <p>&nbsp;</p></td>
          <td width="3%">&nbsp;</td>
        </tr>
        <tr> 
          <td width="41%">&nbsp;</td>
          <td width="56%"><div align="center"><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></div></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <p>&nbsp;</p>
      </td>
  </tr>
</table>
<map name="Map2">
  <area shape="rect" coords="3,128,67,178" href="reportproblem.php">
  <area shape="rect" coords="80,127,164,172" href="#">
  <area shape="rect" coords="169,127,281,172" href="helpDeskAccessAllCalls.php">
  <area shape="rect" coords="382,127,451,172" href="search.php">
  <area shape="rect" coords="457,125,555,175" href="DataAccess.php">
  <area shape="rect" coords="5,3,263,21" href="DataAccess.php">
</map>
</body>
</html>
