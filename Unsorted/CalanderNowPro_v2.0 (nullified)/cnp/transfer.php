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
$cus2=base64_encode($username);
$cpa2=base64_encode($password);
setcookie ("usercnp","$cus2"); 
setcookie ("passcnp","$cpa2"); 
?>
<html>
<head>
<title>CalendarNow</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Refresh" CONTENT="1; URL=main.php">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><img src="media/top.gif" width="750" height="73"></td>
  </tr>
  <tr valign="top"> 
    <td width="170"> <br>
      <br>
      <table width="150" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td><p align="right"><font size="1" face="Arial, Helvetica, sans-serif">If 
              this page does not automatically refresh, please click <a href="main.php">here</a>.</font><font size="2"><br>
              </font> </p>
            </td>
        </tr>
      </table>
      <p><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/spacer.gif" width="170" height="1"><br>
        <br>
        </font></p></td>
    <td width="1" background="media/dot.gif"><img src="media/dot.gif" width="1" height="12"></td>
    <td width="579"><p align="center"><br>
        <br>
        <b><font color="#FF9900" size="2" face="Arial, Helvetica, sans-serif"> Verifying your 
        account...</font></b></p>
      <p align="center"><font size="2" face="Arial, Helvetica, sans-serif" color="#999999">Please 
        wait.</font></p>
      <p align="center">&nbsp;</p>
      <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/spacer.gif" width="579" height="8"></font></p></td>
  </tr>
  <tr valign="top"> 
    <td colspan="3"><img src="media/bot.gif" width="750" height="9"></td>
  </tr>
</table>
</body>
</html>