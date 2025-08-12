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
require("engine_admin.inc.php");
if ($page == ""){
if ($nl == ""){
$page = "select";
}
else {
$page = "display";
}
}

?>
<html>
<head>
<title>CalendarNow</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><img src="media/top.gif" width="750" height="73" border="0"></td>
  </tr>
  <tr valign="top"> 
    <td width="170"> <br>
      <table width="150" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td><p><strong><font size="2" face="Arial, Helvetica, sans-serif">Welcome, 
              <? print $row_admin["name"]; ?></font></strong><font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
              </strong> <a href="index.php?val=logout"><font size="1">Logout</font></a><strong><br>
              <br>
              Current Calendar:<br>
              <font color="#990000"> 
              <?
			  if ($nl != ""){
$listnamefinder = mysql_query ("SELECT * FROM cnpLists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
$listnamefinder2 = mysql_fetch_array($listnamefinder);
print $listnamefinder2["name"];
}
else {
print "Unselected";
}
			  ?>
              <br>
              </font></strong><font color="#990000"> <font size="1"><a href="main.php">Switch</a></font><strong> 
              </strong></font></font></p>
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Your 
              Account<br>
              </strong><a href="main.php?page=account_details&nl=<? print $nl; ?>">Account 
              Details</a></font></p>
            <?
		   if ($nl != "" AND $page != list_del){
		   ?>
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Calendar 
              Settings</strong><br>
              <a href="main.php?page=settingsm&nl=<? print $nl; ?>">General 
              Settings</a><br>
              <a href="main.php?page=list_del&nl=<? print $nl; ?>">Remove Calendar</a> 
              </font></p>
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Events 
              / Entrys</strong><br>
              <a href="main.php?page=add1&nl=<? print $nl; ?>">Add</a><br>
              <a href="main.php?page=display&nl=<? print $nl; ?>">View / Modify</a><br>
              <a href="main.php?page=import&nl=<? print $nl; ?>">Import</a><br>
              <a href="main.php?page=export&nl=<? print $nl; ?>">Export</a> 
              </font></p>
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>
              <?
			  }
			  ?>
              </strong></font></p>
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Administrator 
              Settings</strong><br>
              <a href="main.php?page=admin&nl=<? print $nl; ?>">Admin Users / 
              Settings</a></font></p>
			  
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Configuration<br>
              </strong> <a href="main.php?page=software_details&nl=<? print $nl; ?>">Software 
              Details</a><br>
              <a href="main.php?page=software_license&nl=<? print $nl; ?>">License 
              Agreement</a></font></p></td>
        </tr>
      </table>
      <p><strong><font size="2" face="Arial, Helvetica, sans-serif"> </font></strong></p>
      <p><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/spacer.gif" width="170" height="1"><br>
        <br>
        </font></p></td>
    <td width="1" background="media/dot.gif"><img src="media/dot.gif" width="1" height="12"></td>
    <td width="579"> <br>
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td> 
            <? include("$page.php");
			?>
          </td>
        </tr>
      </table>
      <p align="left">&nbsp;</p>
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/spacer.gif" width="579" height="8"></font></p></td>
  </tr>
  <tr valign="top"> 
    <td colspan="3"><img src="media/bot.gif" width="750" height="9"></td>
  </tr>
  <tr valign="top">
    <td colspan="3"><div align="right"><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><font size="3"><font size="3"><font size="2" face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><font size="1" face="Arial, Helvetica, sans-serif" color="#336699"> 
        <?php
		  $versionfinder = mysql_query ("SELECT * FROM cnpBackend
                         WHERE valid LIKE '1'
						 limit 1
                       ");
$version = mysql_fetch_array($versionfinder);
print $version["version"];
?>
        </font></font></font></font></font></font></font></div></td>
  </tr>
</table>
</body>
</html>