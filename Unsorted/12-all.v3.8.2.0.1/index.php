<?PHP
        if ($useracp != "" AND $passacp != ""){
                $l = "main.php";
                header("Location: $l");
        }
        if ($val == logout){
                setcookie ("passacp","");
        }
        require("lang_select.php");
        if ($db_link == ""){
                print "Please run <a href=\"install.php\">install.php</a> to setup your software.";
                die();
        }
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
    <td width="597" valign="bottom"><table width="597" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="11"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd1.gif" width="11" height="21"></font></td>
          <td width="55" background="media/h_rd2.gif"><div align="center"></div></td>
          <td width="9"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd3.gif" width="9" height="21"></font></td>
          <td width="95" background="media/h_rl1.gif">&nbsp;</td>
          <td width="5"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl2.gif" width="5" height="21"></font></td>
          <td width="55" background="media/h_rl1.gif">&nbsp; </td>
          <td width="7"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl3.gif" width="7" height="21"></font></td>
          <td>&nbsp;</td>
          <td width="11"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd1.gif" width="11" height="21"></font></td>
          <td width="105" background="media/h_rd2.gif">&nbsp;</td>
          <td width="9"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd3.gif" width="9" height="21"></font></td>
          <td width="65" background="media/h_rl1.gif">&nbsp;</td>
          <td width="5"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl2.gif" width="5" height="21"></font></td>
          <td width="120" background="media/h_rl1.gif">&nbsp;</td>
          <td width="7"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl3.gif" width="7" height="21"></font></td>
        </tr>
      </table></td>
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
                <?PHP if ($brand_links == "0"){ ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td background="media/h_n1.gif">&nbsp;</td>
          <td height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="http://www./support" target="_blank"><?PHP print $lang_130; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
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
          <td height="29" background="media/h_n1.gif"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><a href="http://www./12all" target="_blank"><?PHP print $lang_131; ?></a></font><font color="#336699" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
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
          <td width="50%"> <form action="main.php" method="post" name="" id="">
              <blockquote>
                <p align="left"> <font size="2" face="Arial, Helvetica, sans-serif">
                  <?PHP if ($val == invalid){
                                  ?>
                  <strong><?PHP print $lang_132; ?></strong></font></p>
                <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_133; ?>:
                  <br>
                  <br>
                  <?PHP
                                  }
                                  if ($val == logout){
                                  ?>
                  <strong><?PHP print $lang_134; ?><br>
                  </strong></font><font size="2" face="Arial, Helvetica, sans-serif"><br>
                  <?PHP
                                  }
                                  ?>
                  <strong><?PHP print $lang_549; ?>:</strong></font></p>
                <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_2; ?><br>
                  <input name="username" type="text" id="username" size="18" maxlength="50">
                  <br>
                  <?PHP print $lang_3; ?><br>
                  <input name="password" type="password" id="password" size="18" maxlength="50">
                  </font></p>
                <p align="left"> <font size="2" face="Arial, Helvetica, sans-serif">
                  <input type="submit" value="<?PHP print $lang_135; ?>">
                  <input name="login" type="hidden" id="login" value="1">
                  </font></p>
                <font size="2" face="Arial, Helvetica, sans-serif"><img src="media/line_mblue.gif" width="130" height="2"></font>
                <table width="130" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
                  <tr>
                    <td valign="bottom"> <table width="100%" height="25" border="0" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
                        <tr>
                          <td><input name="rm" type="checkbox" id="rm" value="1"></td>
                          <td> <p><font color="#999999" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_550; ?></font> </p></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>

              </blockquote>
            </form></td>
          <td width="50%"><p><strong><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_136; ?>,
              </font></strong><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_137; ?></font></p>
                        <?PHP if ($brand_links == "0"){ ?>
            <p><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_138; ?>
              <a href="http://www./support" target="_blank"><?PHP print $lang_130; ?></a>.</font></p>
                        <?PHP } ?>
                        </td>
        </tr>
      </table>
      <p><img src="media/invis.gif" width="597" height="1"></p></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><img src="media/h_b.gif" width="765" height="22"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><font color="#666666" size="1" face="Arial, Helvetica, sans-serif"><?PHP if($brand_copyright == "0"){ print "&copy; 2004 ActiveCampaign. All rights reserved &nbsp;&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;&nbsp;"; } ?>Version:
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