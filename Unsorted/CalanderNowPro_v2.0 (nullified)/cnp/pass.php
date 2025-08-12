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
          <td><p align="left"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="index.php">Login</a></font></p>
		<br>
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
          <td>
		  <?
		  if ($val != go){
		  ?>
		  <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Look 
              up by E-mail:</strong></font></p>
            <form name="form1" method="post" action="pass.php">
              <p> <font size="2" face="Arial, Helvetica, sans-serif"> 
                <input name="email" type="text" id="email">
                E-mail Address </font></p>
              <p> <font size="2" face="Arial, Helvetica, sans-serif"> 
                <input type="submit" name="Submit" value="Look Up">
                <input name="type" type="hidden" id="type" value="email">
                <input name="val" type="hidden" id="val" value="go">
                </font></p>
            </form>
            <hr width="100%" size="1" noshade>
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Look 
              up by Username:</strong></font></p>
            <form name="form1" method="post" action="pass.php">
              <p> <font size="2" face="Arial, Helvetica, sans-serif"> 
                <input name="username" type="text" id="username">
                Username</font></p>
              <p> <font size="2" face="Arial, Helvetica, sans-serif"> 
                <input type="submit" name="Button2" value="Look Up">
                <input name="type" type="hidden" id="type" value="username">
                <input name="val" type="hidden" id="val" value="go">
                </font></p>
            </form>
			<?
			}
			else {
			?>
            <p>
              <?
			if ($type == email){
			$findcount = mysql_query ("SELECT * FROM cnpAdmin
                         WHERE email LIKE '$email'
						 LIMIT 1
                       ");
$countdata = mysql_num_rows($findcount);	
if ($countdata == 0)
{
print "The e-mail address, $email, is not in relation to any admin users.";
die();
}
else {
$finder = mysql_fetch_array($findcount);
$lines = "------------------------------";
$subject = "User information for CalendarPro Software";
$email = $finder["email"];
$password = $finder["pass"];
$password=base64_decode($password);
$serial = $finder["user"];
$message = "User Information:\n\r$lines\n\r\n\rUsername: $serial\n\rPassword: $password\n\r\n\r$lines\n\r\n\rThis information is for logging into your calendar software.";
mail("$email", "$subject", "$message","");
print "User information has been sent to $email";
}


			}
			if ($type == username){
			$findcount = mysql_query ("SELECT * FROM cnpAdmin
                         WHERE user LIKE '$username'
						 LIMIT 1
						 ");
$countdata = mysql_num_rows($findcount);	
if ($countdata == 0)
{
print "The username, $username, is not valid.  Please try again.";
die();
}
else {
$finder = mysql_fetch_array($findcount);
$lines = "------------------------------";
$subject = "User information for CalendarNow";
$email = $finder["email"];
$password = $finder["pass"];
$password=base64_decode($password);
$serial = $finder["user"];
$message = "User Information:\n\r$lines\n\r\n\rUsername: $serial\n\rPassword: $password\n\r\n\r$lines\n\r\n\rThis information is for logging into your calendar software.";
mail("$email", "$subject", "$message","");
print "User information has been sent to user $username";
}
			}
			?>
            </p>
			<? 
			}
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
</table>
</body>
</html>