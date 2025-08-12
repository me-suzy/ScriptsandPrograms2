<?PHP
        $cus2=base64_encode($username);
        $cpa2=base64_encode($password);
        setcookie ("useracp","$cus2");
        setcookie ("passacp","$cpa2");
        require("lang_select.php");
?>
<html>
<head>
<title>1-2-All Broadcast E-mail Software</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
<META HTTP-EQUIV="Refresh" CONTENT="1; URL=main.php">
</head>

<body bgcolor="#FFFFFF" text="#333333" link="#3871A9" vlink="#3871A9" alink="#3871A9">
<table width="764" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="167" height="47" valign="top"><p align="center"><img src="media/h_l.gif" width="150" height="40" border="0"></p></td>
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
          <td width="136" height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="pass.php"><?PHP print $lang_129; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td width="1" rowspan="10" background="media/h_line.gif"><img src="media/invis.gif" width="1" height="25"></td>
        </tr>
        <tr>
          <td width="30">&nbsp;</td>
          <td width="136">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td background="media/h_n1.gif">&nbsp;</td>
          <td height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="hXXXXXXXXp://XXX.activecampaign.com/support" target="_blank"><?PHP print $lang_130; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
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
          <td height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="hXXXXXXXXp://XXX.activecampaign.com/12all" target="_blank"><?PHP print $lang_131; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
        </tr>
      </table>
      <table width="167" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>&nbsp;</td>
          <td width="1"><img src="media/h_line3.gif" width="1" height="75"></td>
        </tr>
      </table>
      <br> <img src="media/invis.gif" width="167" height="1"></td>
    <td width="597" valign="top"><br>
      <p align="center"><b><font size="3" face="Arial, Helvetica, sans-serif"><?PHP print $lang_406; ?><img src="media/h_t.gif" width="16" height="4"></font></b></p>
      <p align="center"><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"><?PHP print $lang_407; ?></font></p>
      <p align="center"><font color="#999999" size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_408; ?>
        <a href="main.php"><?PHP print $lang_409; ?></a>.&nbsp;</font></p>
      <p><img src="media/invis.gif" width="597" height="8"></p></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><img src="media/h_b.gif" width="765" height="22"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><font color="#666666" size="1" face="Arial, Helvetica, sans-serif">&copy;
      2004 [GTT] =). All rights reserved &nbsp;&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;&nbsp;Version:
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