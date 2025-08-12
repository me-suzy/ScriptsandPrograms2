<?PHP
if ($login == "1"){
        require("engine.inc.php");
        $cpa3=base64_encode($password);
        $sql_admin = "SELECT * FROM Admin WHERE user='$username' and pass='$cpa3'";
        $result_admin = mysql_query($sql_admin);
        $numc = mysql_numrows($result_admin);
        if ($numc == 0){
        header("Location: index.php?val=invalid");
        exit;
        }
        $cus2=base64_encode($username);
        $cpa2=base64_encode($password);
        if ($rm != "1"){
                setcookie ("useracp","$cus2");
                setcookie ("passacp","$cpa2");
        }
        else{
                setcookie ("useracp", "$cus2",time()+1296000);
                setcookie ("passacp", "$cpa2",time()+1296000);
        }
        $useracp = $cus2;
        $passacp = $cpa2;
}
require("engine_admin.inc.php");
if ($mval != ""){
        if ($f == "c"){
                $row_admin_menu[$mval] = "1";
        }
        if ($f == "e"){
                $row_admin_menu[$mval] = "0";
        }
        $menu_new = $row_admin_menu[0].','.$row_admin_menu[1].','.$row_admin_menu[2].','.$row_admin_menu[3].','.$row_admin_menu[4].','.$row_admin_menu[5].','.$row_admin_menu[6].','.$row_admin_menu[7].','.$row_admin_menu[8];
        $menu_id = $row_admin["id"];
        mysql_query("UPDATE Admin SET menu='$menu_new' WHERE (id='$menu_id')");
}
if ($page == ""){
        if ($nl == ""){
                $page = "select";
        }
        else {
                $page = "startup";
        }
}
$seluser = $row_admin["user"];
if ($nl != "" AND $seluser != "admin"){
        $selnl = " , $nl ";
        $selector = mysql_query ("SELECT * FROM Admin
                        WHERE user LIKE '$seluser'
                        AND lists LIKE '%$selnl%'
                                                        ");

        if ($seld = mysql_fetch_array($selector))
        {
        }
        else{
                print "Invalid permissions.  Use $seluser does not have rights to this list.";
                print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=main.php\">";
                die();
        }
}
?>
<html>
<head>
<title><?PHP print $brand_name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
<LINK REL="STYLESHEET" TYPE="text/css" HREF="cstyles.css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#333333" link="#3871A9" vlink="#336699" alink="#336699">
<table width="764" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="167" height="47" valign="top"><p align="center"><a href="main.php?nl=<?PHP print $nl; ?>"><img src="<?PHP print $brand_logo; ?>" border="0"></a></p></td>
    <td width="597" valign="bottom">
<table width="597" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="11"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd1.gif" width="11" height="21"></font></td>
          <td width="55" background="media/h_rd2.gif"><div align="center"><a href="main.php"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_544; ?></font></a></div></td>
          <td width="9"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd3.gif" width="9" height="21"></font></td>
          <td width="95" background="media/h_rl1.gif"><?PHP if ($nl != ""){ ?>
                  <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_settingsm&nl=<?PHP print $nl; ?>"><?PHP print $lang_447; ?></a></font></div><?PHP } ?>
                  </td>
          <td width="5"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl2.gif" width="5" height="21"></font></td>
          <td width="55" background="media/h_rl1.gif"><?PHP if ($usernow == "admin"){ ?><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=admin&nl=<?PHP print $nl; ?>"><?PHP print $lang_545; ?></a></font></div><?PHP } ?></td>
          <td width="7"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl3.gif" width="7" height="21"></font></td>
          <td>&nbsp;</td>
          <td width="11"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd1.gif" width="11" height="21"></font></td>
          <td width="105" background="media/h_rd2.gif"><?PHP if ($nl != ""){ ?><div align="center"><a href="main.php?page=list_send&nl=<?PHP print $nl; ?>"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_546; ?></font></a></div><?PHP } ?></td>
          <td width="9"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rd3.gif" width="9" height="21"></font></td>
          <td width="65" background="media/h_rl1.gif"><?PHP if ($nl != ""){ ?><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_archive&nl=<?PHP print $nl; ?>"><?PHP print $lang_547; ?></a></font></div><?PHP } ?></td>
          <td width="5"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl2.gif" width="5" height="21"></font></td>
          <td width="120" background="media/h_rl1.gif"><?PHP if ($nl != ""){ ?><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_queue&nl=<?PHP print $nl; ?>"><?PHP print $lang_221; ?></a></font></div><?PHP } ?></td>
          <td width="7"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_rl3.gif" width="7" height="21"></font></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="167" valign="top"><img src="media/h_l1.gif" width="167" height="36"></td>
    <td width="597" height="36" background="media/h_r2.gif"><form name="switch" method="post" action="main.php" style="margin-bottom: 0px">
        <table width="100%" height="36" border="0" cellpadding="0" cellspacing="0" background="media/invis.gif">
          <tr background="media/invis.gif">
            <td width="520"><table width="520" height="25" border="0" cellpadding="0" cellspacing="0" background="media/invis.gif">
                <tr background="media/invis.gif">
                  <td width="70">&nbsp;</td>
                  <td width="208" valign="bottom"><font color="#003366" size="4" face="Arial, Helvetica, sans-serif">
                    <a href="main.php?nl=<?PHP print $nl; ?>">
                    <?PHP
                                        if ($nl != ""){
                                                $listnamefinder = mysql_query ("SELECT * FROM Lists
                                                                                                                WHERE id LIKE '$nl'
                                                                                                                limit 1
                                                                                                                ");
                                                $listnamefinder2 = mysql_fetch_array($listnamefinder);
                                                $listnameprint = $listnamefinder2["name"];
                                                $listnameprint=substr($listnameprint,0,19);
                                                print $listnameprint;
                                        }
                                        else {
                                                print "$lang_426";
                                        }
                                        ?>
                    </a></font></td>
                  <td width="242"> <div align="right">
                      <select name="nl" id="nl">
                        <option value=""><?PHP print "$lang_427 $lang_425"; ?></option>
                        <?PHP
                                                        $result = mysql_query ("SELECT * FROM Lists
                                                                                                         WHERE name != ''
                                                                                                        ORDER BY name
                                                                                                        ");
                                                        if ($c1 = mysql_num_rows($result)) {
                                                                while($row = mysql_fetch_array($result)) {
                                                                        $selid = $row["id"];
                                                                        $selname = $row["name"];
                                                                        $selnum = $row["id"];
                                                                        $selname=substr($selname,0,19);
                                                                        $selid = " , $selid ";
                                                                        $seluser = $row_admin["user"];
                                                                        $selector = mysql_query ("SELECT * FROM Admin
                                                                                                                                WHERE user LIKE '$seluser'
                                                                                                                                AND lists LIKE '%$selid%'
                                                                                                                                ");
                                                                        if ($seld = mysql_fetch_array($selector))
                                                                        {
                                                                                print "<option value=\"$selnum\">$selname</option>";
                                                                        }
                                                                }
                                                        }
                                                ?>
                      </select>
                      &nbsp;&nbsp; </div></td>
                </tr>
              </table></td>
            <td width="77"><div align="center">
                <input type="image" src="media/h_s.gif" width="63" height="36" border="0" alt="Switch">
              </div></td>
          </tr>
        </table>
      </form></td>
  </tr>
  <tr>
    <td width="167" valign="top"><table width="167" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="30" background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[0] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=0&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=0&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td width="136" height="29" background="media/h_n1.gif"><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_428; ?></strong></font></td>
          <?PHP if ($row_admin_menu[0] == "0"){ ?>
          <td width="1" rowspan="91" background="media/h_line.gif"><img src="media/invis.gif" width="1" height="25"></td>
        </tr>
        <tr>
          <td width="30"><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=22&c=6','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td width="136"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=account_details&nl=<?PHP print $nl; ?>"><?PHP print $lang_1; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td width="30"><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=23&c=6','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td width="136"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=permissions&nl=<?PHP print $nl; ?>"><?PHP print $lang_429; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=24&c=6','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="index.php?val=logout"><?PHP print $lang_424; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/h_line2.gif" width="166" height="1"></font></td>
        </tr>
        <?PHP
                }
                if ($nl != "" AND page != "list_del"){
                ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[1] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=1&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=1&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_447; ?></strong></font></td>
          <?PHP if ($row_admin_menu[1] == "0"){ ?>
        </tr>
        <?PHP if ($row_admin["m_lists"] == 1){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=25&c=7','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_settingsm&nl=<?PHP print $nl; ?>">
            <?PHP print $lang_430; ?></a> </font></td>
        </tr>
        <?PHP
                $check = mysql_query ("SELECT * FROM Lists
                                         WHERE id LIKE '$nl'
                                                                 limit 1
                                               ");
                $chk = mysql_fetch_array($check);
                if ($chk["a_co"] == "0") {
                ?>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                $check = mysql_query ("SELECT * FROM Lists
                                         WHERE id LIKE '$nl'
                                                                limit 1
                                             ");
                $chk = mysql_fetch_array($check);
                if ($chk["a_op"] == "0") {
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=27&c=7','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=list_opt&nl=<?PHP print $nl; ?>"><?PHP print $lang_195; ?></a>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=28&c=7','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_fields&nl=<?PHP print $nl; ?>"><?PHP print $lang_432; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                $check = mysql_query ("SELECT * FROM Lists
                                         WHERE id LIKE '$nl'
                                                                 limit 1
                                               ");
                $chk = mysql_fetch_array($check);
                if ($chk["a_bn"] == "0") {
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=29&c=7','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=list_blocked&nl=<?PHP print $nl; ?>"><?PHP print $lang_158; ?></a>
            </font></td>
        </tr>
        <?PHP
                }
                ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                }
                if ($row_admin["m_cre_del"] == 1){
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=30&c=7','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=list_del&nl=<?PHP print $nl; ?>"><?PHP print $lang_433; ?></a>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td height="1" colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[2] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=2&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=2&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_434; ?></strong></font></td>
        </tr>
        <?PHP
                if ($row_admin_menu[2] == "0"){
                if ($row_admin["m_users"] == 1){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=31&c=8','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=sub_add&nl=<?PHP print $nl; ?>"><?PHP print $lang_436; ?></a>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=32&c=8','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_view&nl=<?PHP print $nl; ?>"><?PHP print $lang_435; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=33&c=8','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_search&nl=<?PHP print $nl; ?>"><?PHP print $lang_391; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP if ($row_admin["m_users"] == 1){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=34&c=8','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_import&nl=<?PHP print $nl; ?>"><?PHP print $lang_437; ?></a></font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=35&c=8','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_export&nl=<?PHP print $nl; ?>"><?PHP print $lang_438; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[3] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=3&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=3&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_26; ?></strong></font></td>
        </tr>
        <?PHP
                if ($row_admin_menu[3] == "0"){
                if ($row_admin["send"] == 1){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=36&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=list_send&nl=<?PHP print $nl; ?>"><?PHP print $lang_439; ?></a></font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=37&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_send_sp&nl=<?PHP print $nl; ?>"><?PHP print $lang_450; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=38&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_queue&nl=<?PHP print $nl; ?>"><?PHP print $lang_221; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=39&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_inqueue&nl=<?PHP print $nl; ?>"><?PHP print $lang_504; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=40&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_archive&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><?PHP print $lang_23; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                $check = mysql_query ("SELECT * FROM Lists
                                         WHERE id LIKE '$nl'
                                                                 limit 1
                                               ");
                $chk = mysql_fetch_array($check);
                if ($chk["a_lt"] == "0") {
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=41&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
            <?PHP
                  @include("lt/links.php");
                  ?>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[4] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=4&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=4&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_566; ?></strong></font></td>
        </tr>
        <?PHP if ($row_admin_menu[4] == "0"){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=41&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=list_respond_add&nl=<?PHP print $nl; ?>"><?PHP print $lang_436; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=41&c=9','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=list_respond_manage&nl=<?PHP print $nl; ?>"><?PHP print $lang_435; ?></a></font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[5] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=5&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=5&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_440; ?></strong></font></td>
        </tr>
        <?PHP
                if ($row_admin_menu[5] == "0"){
                $check = mysql_query ("SELECT * FROM Lists
                                         WHERE id LIKE '$nl'
                                                                 limit 1
                                               ");
                $chk = mysql_fetch_array($check);
                if ($chk["a_tp"] == "0") {
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=42&c=10','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=list_templates&nl=<?PHP print $nl; ?>"><?PHP print $lang_285; ?></a>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=43&c=10','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_headfoot&nl=<?PHP print $nl; ?>"><?PHP print $lang_179; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=44&c=10','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_filter&nl=<?PHP print $nl; ?>"><?PHP print $lang_457; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=45&c=10','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_bodytags&nl=<?PHP print $nl; ?>"><?PHP print $lang_462; ?>
            <?PHP print $lang_285; ?></a> </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[6] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=6&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=6&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_441; ?></strong></font></td>
        </tr>
        <?PHP
                if ($row_admin_menu[6] == "0"){
                if ($row_admin["m_dusers"] == 1){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=46&c=11','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_batch&nl=<?PHP print $nl; ?>"><?PHP print $lang_152; ?></a></font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                }
                if ($row_admin["m_cre_del"] == 1){
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=47&c=11','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_settingsm&nl=<?PHP print $nl; ?>#c"><?PHP print $lang_488; ?></a></font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                $bback2 = mysql_query ("SELECT * FROM Backend
                                                                WHERE valid LIKE '1'
                                                                limit 1
                                                                ");
                $bdback2 = mysql_fetch_array($bback2);
                $btype2 = $bdback2["btype"];
                if ($btype2 == "pop"){
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=48&c=11','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <a href="main.php?page=sendmailPop&nl=<?PHP print $nl; ?>"><?PHP print $lang_442; ?></a>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=49&c=11','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_flush&nl=<?PHP print $nl; ?>"><?PHP print $lang_175; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP if ($row_admin["m_dusers"] == 1){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=50&c=11','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_purgenc&nl=<?PHP print $nl; ?>"><?PHP print $lang_205; ?></a>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=51&c=11','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_unsubd&nl=<?PHP print $nl; ?>"><?PHP print $lang_443; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                $check = mysql_query ("SELECT * FROM Lists
                                         WHERE id LIKE '$nl'
                                                                 limit 1
                                               ");
                $chk = mysql_fetch_array($check);
                if ($chk["a_gc"] == "0"){
                ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=52&c=11','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"> <strong><a href="main.php?page=engine_code&nl=<?PHP print $nl; ?>"><?PHP print $lang_101; ?></a></strong>
            </font></td>
        </tr>
        <?PHP } ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                }
                }
                if ($row_admin["user"] == admin){ ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[7] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=7&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=7&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><p><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong>
              <?PHP print $lang_448; ?></strong></font></p></td>
        </tr>
        <?PHP if ($row_admin_menu[7] == "0"){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=53&c=12','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=admin&nl=<?PHP print $nl; ?>"><?PHP print $lang_444; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/support/12all_kb_min/index.php?page=index_v2&id=54&c=12','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=engine_settings&nl=<?PHP print $nl; ?>"><?PHP print $lang_126; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/support/12all_kb_min/index.php?page=index_v2&id=55&c=12','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=engine_redirects&nl=<?PHP print $nl; ?>"><?PHP print $lang_122; ?></a>
            </font></td>
        </tr>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=56&c=12','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><p><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=engine_bsettings&nl=<?PHP print $nl; ?>"><?PHP print $lang_88; ?></a>
              </font></p></td>
        </tr>
        <?PHP
                }
                ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                }
                ?>
        <tr>
          <td background="media/h_n1.gif"><div align="center">
              <?PHP if ($row_admin_menu[8] == "0"){ print "<a href=\"main.php?page=$page&nl=$nl&mval=8&f=c\"><img src=\"media/h_mc.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } else { print "<a href=\"main.php?page=$page&nl=$nl&mval=8&f=e\"><img src=\"media/h_me.gif\" width=\"17\" height=\"29\" border=\"0\"></a>"; } ?>
            </div></td>
          <td height="29" background="media/h_n1.gif"><p><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_445; ?></strong></font></p></td>
        </tr>
        <?PHP if ($row_admin_menu[8] == "0"){ ?>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('/hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=57&c=13','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=engine_backup&nl=<?PHP print $nl; ?>"><?PHP print $lang_446; ?></a>
            </font></td>
        </tr>
        <?PHP if ($brand_serial == "1"){ ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <tr>
          <td><a href="#"  onclick="MM_openBrWindow('hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support/12all_kb_min/index.php?page=index_v2&id=58&c=13','Help','scrollbars=yes,width=350,height=375')"><img src="media/h_q2.gif" width="30" height="20" border="0"></a></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=software_details&nl=<?PHP print $nl; ?>"><?PHP print $lang_333; ?></a>
            </font></td>
        </tr>
        <?PHP
                }
                if ($brand_links == "0"){
                ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                }
                if ($brand_links == "0"){
                ?>
        <tr>
          <td colspan="2"><img src="media/h_line2.gif" width="166" height="1"></td>
        </tr>
        <?PHP
                }
                }
                ?>
        <tr>
          <td>&nbsp;</td>
          <td height="29">&nbsp;</td>
        </tr>
        <tr>
          <td background="media/h_n1.gif">&nbsp;</td>
          <td height="29" background="media/h_n1.gif"><p><font size="2" face="Arial, Helvetica, sans-serif"><strong><a href="hXXXXXXXXXXXXtp://XXXX.activecampaign.XXX/support" target="_blank">
              <?PHP if ($brand_links == "0"){ ?>
              <?PHP print $lang_130; ?></a></strong></font><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP } ?>
              </font></p></td>
        </tr>
      </table>
      <table width="167" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="30">&nbsp;</td>
          <td>&nbsp;</td>
          <td width="1"><img src="media/h_line3.gif" width="1" height="75"></td>
        </tr>
      </table>
      <br>
        <img src="media/invis.gif" width="167" height="1"></td>
    <td width="597" valign="top"><br>
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <?PHP @include("$page.php");
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
      <?PHP if($brand_copyright == "0"){ print "&copy; 2004 [GTT] =). All rights reserved &nbsp;&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
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