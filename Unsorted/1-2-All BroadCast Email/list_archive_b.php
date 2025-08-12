<p><font size="2" face="Arial, Helvetica, sans-serif"><a href="javascript:window.history.go(-1);"><?PHP print $lang_417; ?></a>
  | <a href="javascript:window.print()"><?PHP print $lang_416; ?></a><font size="3"></font><b><font size="3"><br>
  <br>
  </font></b></font></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D5E2F0">
  <tr>
    <td> <div align="center"></div>
      <table width="100%" border="0" cellspacing="1" cellpadding="6">
        <tr valign="top">
          <td width="50%" bgcolor="#FFFFFF"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif">This
              mailing has bounced
              <?PHP
                                $resulttrack2 = mysql_query ("SELECT id FROM 12all_Bounce
                         WHERE mid LIKE '$lid'
                                                ");
                                $tracknum=mysql_num_rows($resulttrack2);
                                if ($tracknum == ""){
                                $tracknum = 0;
                                }
                                print $tracknum;
                                ?>
              <?PHP print $lang_149; ?>. (
              <?PHP
                            $result = mysql_query ("SELECT * FROM Messages
                         WHERE id LIKE '$lid'
                                                 limit 1
                       ");
                                $row = mysql_fetch_array($result);

                                $ntotal = $row["amt"];
                                @$nvs = round(($tracknum / $ntotal),4);
                                @$nvs = round(($nvs * 100),4);
                                print $nvs;
                                ?>
              % )<b><br>
              </b></font><font size="4" face="Arial, Helvetica, sans-serif"><b><font size="1">
              <?PHP if($nvs >= 5){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 10){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 15){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 20){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 25){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 30){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 35){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 40){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 45){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 50){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 55){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 60){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 65){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 70){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 75){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 80){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 85){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 90){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 95){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              <?PHP if($nvs >= 100){ ?>
              <img src="media/box1.gif" width="8" height="6">
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6">
              <?PHP } ?>
              </font></b></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr bgcolor="#D5E2F0">
    <td><font size="1" face="Arial, Helvetica, sans-serif">Email</font></td>
    <td width="115"><font size="1" face="Arial, Helvetica, sans-serif">Date</font></td>
    <td width="115"><font size="1" face="Arial, Helvetica, sans-serif">Time</font></td>
  </tr>
  <?PHP
  $result3 = mysql_query ("SELECT * FROM 12all_Bounce
                                                WHERE mid LIKE '$lid'
                               ORDER BY tdate DESC, email");
if ($c1 = mysql_num_rows($result3)) {

while($row3 = mysql_fetch_array($result3)) {
?>
  <tr>
    <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row3["email"]; ?></font></td>
    <td width="115"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row3["tdate"]; ?></font></td>
    <td width="115"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row3["ttime"]; ?></font></td>
  </tr>
  <?PHP
  }

}
?>
</table>
<p>
  <?PHP if ($row_admin["m_cre_del"] == 1){ ?>
</p>
<div align="left">
  <table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
    <tr>
      <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
        <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
          <tr>
            <td bgcolor="#D5E2F0"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_324; ?>
                Containing These Users</strong></font></div></td>
          </tr>
          <tr>
            <td height="27" bgcolor="#FFFFFF"><form action="main.php" method="post" name="" id="">
                <br>
                <table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
                  <tr valign="top">
                    <td width="100"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_325; ?>
                      <a href="#"  onclick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3152','Help','scrollbars=yes,width=316,height=350')">[?]</a></font></td>
                    <td><font size="2" face="Arial, Helvetica, sans-serif">
                      <input name="name" type="text" id="name">
                      </font></td>
                  </tr>
                  <tr valign="top">
                    <td width="100"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?></font></td>
                    <td><font size="2" face="Arial, Helvetica, sans-serif">
                      <input name="email" type="text" id="email">
                      <br>
                      <font size="1">^ <?PHP print $lang_284; ?></font></font></td>
                  </tr>
                  <tr>
                    <td width="100">&nbsp;</td>
                    <td><br> <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>">
                      <input name="page" type="hidden" id="page" value="list_add">
                      <input name="atadd" type="hidden" id="atadd" value="<?PHP print $lid; ?>">
                      <input name="nlold" type="hidden" id="nlold" value="<?PHP print $nl; ?>">
                    </td>
                  </tr>
                </table>
              </form></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <?PHP
}
?>
  <font face="Arial, Helvetica, sans-serif" size="2"> </font> </div>