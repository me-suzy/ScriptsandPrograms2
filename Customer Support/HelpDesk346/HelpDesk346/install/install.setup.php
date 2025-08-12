<html>
    <head>
        <title>Hep Desk MySQL Server Login and Account Setup Page</title>
    </head>
   <link href="style.css" rel="stylesheet" type="text/css"> 
    <body>
        
<table align="center" bgcolor="#CCCCCC" cellspacing="1" cellpadding="1">
  <form method="post" action="process.php">
    <tr> 
      <td colspan="2" align="center" valign="middle" style="color: black; font-family: Arial; font-size: 16pt; font-weight: bold"> 
        Please Enter Your MySQL Login Information For Help Desk Setup </td>
    </tr>
    <tr> 
      <td style="font-weight:bold; font-family:Arial; font-size:12pt; color:blue"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        Host (Server Name): </font></td>
      <td><input type="text" name="server" size="30" maxlength="155"/></td>
    </tr>
    <tr> 
      <td style="font-weight:bold; font-family:Arial; font-size:12pt; color:blue"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        Database Name (If you do not have one,<br>
        make one up, example "helpdeskONE": </font></td>
      <td><input type="text" name="databaseName" size="30" maxlength="155"/></td>
    </tr>
    <tr> 
      <td style="font-weight:bold; font-family:Arial; font-size:12pt; color:blue"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        Database Username: </font></td>
      <td><input type="text" name="database" size="30" maxlength="155"/></td>
    </tr>
    <tr> 
      <td style="font-weight:bold; font-family:Arial; font-size:12pt; color:blue"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        Database Password: </font></td>
      <td><input type="password" name="databasePassword" size="30" maxlength="155"/></td>
    </tr>
    <tr> 
      <td style="font-weight:bold; font-family:Arial; font-size:12pt; color:blue"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        Database Prefix: </font></td>
      <td><input type="text" name="databasePrefix" size="30" maxlength="155"/></td>
    </tr>
    <tr> 
      <td colspan="2" align="center"> <input type="submit" value="Submit" class="button"/> 
      </td>
    </tr>
    <tr> 
      <td colspan="2" align="center" style="color:red; font-family:Verdana; font-size:8pt; font-weight:bold"> 
        <?php
              if (isset($msg)) echo $msg;
         ?>
      </td>
    </tr>
  </form>
</table>
    
<div align="center"><br>
  <a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a> 
</div>
</body>
</html>