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
if ($val == logout){
setcookie ("passcnp",""); 
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
    <td colspan="3"><img src="media/top.gif" width="750" height="73"></td>
  </tr>
  <tr valign="top"> 
    <td width="170"> <br>
      <table width="150" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td><p align="left"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="pass.php">Forget 
              Password?</a></font></p><br>
              </font> </p>
            </td>
        </tr>
      </table>
      <p><strong><font size="2" face="Arial, Helvetica, sans-serif"> </font></strong></p>
      <p><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/spacer.gif" width="170" height="1"><br>
        <br>
        </font></p></td>
    <td width="1" background="media/dot.gif"><img src="media/dot.gif" width="1" height="12"></td>
    <td width="579"> <br>
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr valign="top"> 
          <td width="50%"> <form action="transfer.php" method="post" name="" id="">
              <blockquote>
                <p align="left"> <font size="2" face="Arial, Helvetica, sans-serif"> 
                  <? if ($val == invalid){
				  ?>
                  <strong>Invalid User / Pass or Session has timed out.</strong></font></p>
                <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">Please 
                  try to login again: <br>
                  <br>
                  <?
				  }
				  if ($val == logout){
				  ?>
                  <strong>You have successfully logged out.<br>
                  </strong></font><font size="2" face="Arial, Helvetica, sans-serif"><br>
                  <?
				  }
				  ?>
                  Username<br>
                  <input name="username" type="text" id="username" size="16" maxlength="50">
                  <br>
                  Password<br>
                  <input name="password" type="password" id="password" size="16" maxlength="50">
                  </font></p>
                <p align="left"> <font size="2" face="Arial, Helvetica, sans-serif"> 
                  <input type="submit" value="Login">
                  </font><br>
                </p>
              </blockquote>
            </form></td>
          <td width="50%"><p><strong><font color="#FF9900" size="2" face="Arial, Helvetica, sans-serif">Welcome</font><font color="#666666" size="2" face="Arial, Helvetica, sans-serif">, 
              </font></strong><font color="#666666" size="2" face="Arial, Helvetica, sans-serif">please 
              log in to your account using the form to the left.</font></p>
            </td>
        </tr>
      </table>
      <p align="left">&nbsp;</p>
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/spacer.gif" width="579" height="8"></font></p></td>
  </tr>
  <tr valign="top"> 
    <td colspan="3"><img src="media/bot.gif" width="750" height="9"></td>
  </tr>
</table>
</body>
</html>