<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>- - - Baal Smart Form - - -</title>
<link rel="STYLESHEET" type="text/css" href="inc/style2.css">
</head>
<!--<body bgcolor="#E5E5E5" leftmargin="3" topmargin="0">-->
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0">
<table border="0" cellspacing="0" width="100%">
<tr>
    <td bgcolor="#6C9CFF" valign="top">
        <table border="0" cellpadding="2" cellspacing="0" height="100%">
        <tr><td align="right" valign="bottom">
                <font size="3" face="Comic Sans MS, Trebuchet MS, Verdana, Tahoma, Arial" color="#FFFFFF"><h2>&nbsp;Baal Smart Form</h2></font>
        </td></tr>
        </table>
    </td>
</tr>
</table>
<form name = "f0" action="installroutine.php" method="post">
<h2>&nbsp;Database Setup</font></h2>
<table border="0" cellpadding="3">
<tr>
    <td width="10">&nbsp;
        
    </td>
    <td>
<table class="global" align="left" width="400" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td bgcolor="#D2D2FF"><strong>Database Server :</strong></td>
     <td><input name="dbservername" type="text"></td>
  </tr>
  <tr>
    <td bgcolor="#D2D2FF">Database User Name :</td>
    <td><input name="dbusername" type="text"></td>
  </tr>
  <tr>
     <td bgcolor="#D2D2FF">Database Password :</td>
     <td><input name="dbpassword" type="password"></td>
   </tr>
   <tr>
     <td bgcolor="#D2D2FF"><strong>Database Name :</strong></td>
      <td><input name="dbname" type="text"></td>
   </tr>
    
  <!-- New Database Installation Stuff -->
  
  <tr>
     <td bgcolor="#D2D2FF">Table Prefix : </td>
     <td><input name="tableprefix" type="text"></td>
  </tr>
  <tr>
     <td bgcolor="#D2D2FF">Old Table Prefix : </td>
     <td><input name="pre_tableprefix" type="text"></td>
  </tr>
  <tr>
      <td bgcolor="#D2D2FF">Installation Type : </td>
     <td>
            <select name="installtype">
         <option value="freshinstall" />Fresh Install
         <option value="upgrade" />Upgrade Install
         </select>    
     </td>
  </tr>
    <tr><td><br /></td></tr>
       
  <!-- End New Database Installation Stuff -->
  
  
  <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
  </tr>
  <tr>
    &nbsp;&nbsp;<td align="Right"><input class="buttonclass" name="" type="submit" value="Submit"></td>
  </tr>
</table>
<iframe src="listdb.php" width="200" height="150" frameborder="no" ></iframe>
   </td>
</tr>
</table>
</form>
<br /><br />

<table style="color: #800000; margin-left: 20px">
    <tr><td width="800">
        <ul>
        <li>Old Table Prefix field used for specifying previous table prefix for existing tables.
        You can use it with Upgrade Install option only.
</li>
    </ul>
    </td></tr>
</table>

<br>

<table style="color: #800000; margin-left: 20px">
    <tr><td width="800">
        <ul>
        <li>Choose Fresh Install to install a blank copy of the forum.  This create a new database if one isn't already there.
        It will add an installation to the same database if it already exists.  Remember to use a table prefix if adding an
        installation to the same database.<br />
</li>
<li>
        Choose Upgrade Install to install the new forum with data from the old database.
</li>
<li>
        Choose Update to update the database references.
    </li>
    </ul>
    </td></tr>
</table>
</body>
</html>

