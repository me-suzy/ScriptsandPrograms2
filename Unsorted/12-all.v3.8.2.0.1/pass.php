<?PHP
require("engine.inc.php");
require("lang_select.php");
?>
<html>
<head>
<title><?PHP print $brand_name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
<LINK REL="STYLESHEET" TYPE="text/css" HREF="cstyles.css">
</head>

<body bgcolor="#FFFFFF" text="#333333" link="#3871A9" vlink="#3871A9" alink="#3871A9">
<table width="764" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="167" height="47" valign="top"><p align="center"><img src="<?PHP print $brand_logo; ?>" border="0"></p></td>
    <td width="597" valign="bottom"><img src="media/h_r1.gif" width="597" height="21" border="0"></td>
  </tr>
  <tr>
    <td width="167" valign="top"><img src="media/h_l1.gif" width="167" height="36"></td>
    <td width="597" height="36" background="media/h_r2.gif">&nbsp;</td>
  </tr>
  <tr>
    <td width="167" valign="top"><table width="167" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="30" background="media/h_n1.gif">&nbsp;</td>
          <td width="136" height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="index.php"><?PHP print $lang_311; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td width="1" rowspan="10" background="media/h_line.gif"><img src="media/invis.gif" width="1" height="25"></td>
        </tr>
        <tr>
          <td width="30">&nbsp;</td>
          <td width="136">&nbsp;</td>
        </tr>
                <?PHP if ($brand_links == "0"){ ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td background="media/h_n1.gif">&nbsp;</td>
          <td height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support" target="_blank"><?PHP print $lang_130; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td height="1" colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td background="media/h_n1.gif">&nbsp;</td>
          <td height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/12all" target="_blank"><?PHP print $lang_131; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
        </tr>
                <?PHP } ?>
      </table>
      <table width="167" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="30">&nbsp;</td>
          <td>&nbsp;</td>
          <td width="1"><img src="media/h_line3.gif" width="1" height="75"></td>
        </tr>
      </table>
      <br> <img src="media/invis.gif" width="167" height="1"></td>
    <td width="597" valign="top"><br>
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr valign="top">
          <td>
            <?PHP
                  if ($val != go){
                  ?>
            <p><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_302; ?>:</strong></font></p>
            <form name="form1" method="post" action="pass.php">
              <p> <font size="2" face="Arial, Helvetica, sans-serif">
                <input name="email" type="text" id="email">
                <?PHP print $lang_5; ?> </font></p>
              <p> <font size="2" face="Arial, Helvetica, sans-serif">
                <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>">
                <input name="type" type="hidden" id="type" value="email">
                <input name="val" type="hidden" id="val" value="go">
                </font></p>
            </form>
            <hr width="100%" size="1" noshade> <p><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_303; ?>:</strong></font></p>
            <form name="form1" method="post" action="pass.php">
              <p> <font size="2" face="Arial, Helvetica, sans-serif">
                <input name="username" type="text" id="username">
                <?PHP print $lang_304; ?></font></p>
              <p> <font size="2" face="Arial, Helvetica, sans-serif">
                <input type="submit" name="Button2" value="<?PHP print $lang_21; ?>">
                <input name="type" type="hidden" id="type" value="username">
                <input name="val" type="hidden" id="val" value="go">
                </font></p>
            </form>
            <?PHP
                        }
                        else {
                        ?>
            <p>
              <?PHP
                        if ($type == email){
                        $findcount = mysql_query ("SELECT * FROM Admin
                         WHERE email LIKE '$email'
                                                 LIMIT 1
                       ");
$countdata = mysql_num_rows($findcount);
if ($countdata == 0)
{
print "$lang_305, $email, $lang_306";
die();
}
else {
$finder = mysql_fetch_array($findcount);
$lines = "------------------------------";
$subject = "$lang_307";
$email = $finder["email"];
$password = $finder["pass"];
$password=base64_decode($password);
$serial = $finder["user"];
$message = "$lang_307:\n\r$lines\n\r\n\r$lang_304: $serial\n\r$lang_3: $password\n\r\n\r$lines";
mail("$email", "$subject", "$message","From:list");
print "$lang_309 $email";
}


                        }
                        if ($type == username){
                        $findcount = mysql_query ("SELECT * FROM Admin
                         WHERE user LIKE '$username'
                                                 LIMIT 1
                                                 ");
$countdata = mysql_num_rows($findcount);
if ($countdata == 0)
{
print "$lang_310, $username, $lang_306";
die();
}
else {
$finder = mysql_fetch_array($findcount);
$lines = "------------------------------";
$subject = "$lang_307";
$email = $finder["email"];
$password = $finder["pass"];
$password=base64_decode($password);
$serial = $finder["user"];
$message = "$lang_307:\n\r$lines\n\r\n\r$lang_2: $serial\n\r$lang_3: $password\n\r\n\r$lines";
mail("$email", "$subject", "$message","From:list");
print "$lang_309 $username";
}
                        }
                        ?>
            </p>
            <?PHP
                        }
                        ?>
          </td>
        </tr>
      </table>
      <p><img src="media/invis.gif" width="597" height="1"></p></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><img src="media/h_b.gif" width="765" height="22"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><font color="#666666" size="1" face="Arial, Helvetica, sans-serif">
      <?PHP if($brand_copyright == "0"){ print "&copy; 2004 [GTT] =) . All rights reserved &nbsp;&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
      Version:
      <?PHP
                $versionfinder = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
                                                 limit 1
                       ");
                $version = mysql_fetch_array($versionfinder);
                print $version["version"];
        ?>
      </font></td>
  </tr>
</table>
</body>
</html>