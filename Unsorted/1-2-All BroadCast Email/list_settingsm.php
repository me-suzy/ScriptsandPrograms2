<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_282; ?> </strong></font><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699">
  <?PHP
                  $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font> </p>
<p> </p>
  <?PHP
if ($action != save){
?>
<form name="adminForm" method="post" action="main.php">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="150" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_283; ?></font></div></td>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="name" type="text" id="name" value="<?PHP        print $row["name"];        ?>" size="35">
        </font></td>
    </tr>
    <tr>
      <td width="150"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_284; ?></font></div></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="email" type="text" id="email" value="<?PHP        print $row["email"];        ?>" size="35">
        </font></td>
    </tr>
  </table>
  <?PHP
  if ($row_admin["user"] == "admin"){
  ?>
  <br>
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td colspan="2" bgcolor="#BFD2E8"><font size="2"><font face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_470; ?></strong></font></font><strong><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></strong></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_471; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_ui" value="0" <?PHP        if ($row["a_ui"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_ui" value="1" <?PHP        if ($row["a_ui"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr>
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_472; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_ua" value="0" <?PHP        if ($row["a_ua"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_ua" value="1" <?PHP        if ($row["a_ua"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_473; ?></font></div></td>
      <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="a_is" type="text" id="a_is" value="<?PHP        if ($row["a_is"] == "0"){        print "100000";
                        }
                        else{
        print $row["a_is"];        } ?>" size="10">
        <font color="#666666">bytes</font></font></td>
    </tr>
    <tr>
      <td width="200"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_474; ?></font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="a_as" type="text" id="a_as" value="<?PHP        if ($row["a_as"] == "0"){        print "100000";
                        }
                        else{
        print $row["a_as"];        } ?>" size="10">
        <font color="#666666">bytes</font> </font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_475; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="a_ff" type="text" id="a_ff" value="<?PHP if ($row["a_ff"] == ""){ print "gif,jpg,pdf,zip"; } else { print $row["a_ff"]; } ?>">
        <br>
        <font size="1"><?PHP print $lang_476; ?></font></font></td>
    </tr>
    <tr>
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_477; ?></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="a_nm" type="text" id="a_nm" value="<?PHP        print $row["a_nm"];        ?>" size="10">
        </font> <select name="a_pt" id="a_pt">
          <option value="day" <?PHP if ($row["a_pt"] == "" OR $row["a_pt"] == "day"){ print "selected"; } ?>>Per
          Day</option>
          <option value="week" <?PHP if ($row["a_pt"] == "week"){ print "selected"; } ?>>Per
          Week</option>
          <option value="month" <?PHP if ($row["a_pt"] == "month"){ print "selected"; } ?>>Per
          Month</option>
        </select> <br> <font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_478; ?></font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_479; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_gc" value="0" <?PHP        if ($row["a_gc"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_gc" value="1" <?PHP        if ($row["a_gc"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr>
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_480; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_tp" value="0" <?PHP        if ($row["a_tp"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_tp" value="1" <?PHP        if ($row["a_tp"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_493; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_lt" value="0" <?PHP        if ($row["a_lt"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_lt" value="1" <?PHP        if ($row["a_lt"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr>
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_494; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_pz" value="0" <?PHP        if ($row["a_pz"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_pz" value="1" <?PHP        if ($row["a_pz"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_495; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_bn" value="0" <?PHP        if ($row["a_bn"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_bn" value="1" <?PHP        if ($row["a_bn"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr>
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_496; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_op" value="0" <?PHP        if ($row["a_op"] == 0){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_197; ?></font></font><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_op" value="1" <?PHP        if ($row["a_op"] == 1){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_198; ?></font></font><font face="Arial, Helvetica, sans-serif">
        </font></font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_497; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_co" value="0" <?PHP        if ($row["a_co"] == 0){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_197; ?></font></font><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_co" value="1" <?PHP        if ($row["a_co"] == 1){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_198; ?></font></font><font face="Arial, Helvetica, sans-serif">
        </font></font></td>
    </tr>
    <tr>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_511; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_rq" value="0" <?PHP        if ($row["a_rq"] == 0){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_197; ?></font></font><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_rq" value="1" <?PHP        if ($row["a_rq"] == 1){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_198; ?></font></font><font face="Arial, Helvetica, sans-serif">
        </font></font></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_512; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_sc" value="0" <?PHP        if ($row["a_sc"] == 0){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_197; ?></font></font><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_sc" value="1" <?PHP        if ($row["a_sc"] == 1){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_198; ?></font></font><font face="Arial, Helvetica, sans-serif">
        </font></font></td>
    </tr>
    <tr>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_513; ?><br>
        <?PHP print $lang_514; ?>:
        <?PHP        print $row["a_ep"];        ?>
        </font></td>
      <td> <select name="ep3" id="ep3">
          <option value="0000" selected>0000</option>
          <option value="2003">2003</option>
          <option value="2004">2004</option>
          <option value="2005">2005</option>
          <option value="2006">2006</option>
          <option value="2007">2007</option>
          <option value="2008">2008</option>
          <option value="2009">2009</option>
          <option value="2010">2010</option>
        </select> <select name="ep1" id="ep1">
          <option value="00" selected >00</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        </select> <select name="ep2" id="ep2">
          <option value="00" selected>00</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select> <br> <font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_216; ?>&nbsp;&nbsp;&nbsp;
        <?PHP print $lang_214; ?> &nbsp; &nbsp;<?PHP print $lang_215; ?></font><br> <font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_516; ?>
        </font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_482; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="a_mx" type="text" id="a_mx" value="<?PHP        print $row["a_mx"];        ?>" size="10">
        <br>
        </font><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_478; ?></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;
        </font></td>
    </tr>
    <tr>
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_483; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input name="a_s1" type="checkbox" id="a_s1" value="0" <?PHP if ($row["a_s1"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_484; ?>&nbsp;&nbsp;&nbsp; </font><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input name="a_s2" type="checkbox" id="a_s2" value="0" <?PHP if ($row["a_s2"] == 0){ print "checked"; } ?>>
        </font></font><font face="Arial, Helvetica, sans-serif"> <?PHP print $lang_485; ?></font><font size="2"><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;
        </font><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input name="a_s3" type="checkbox" id="a_s3" value="0" <?PHP if ($row["a_s3"] == 0){ print "checked"; } ?>>
        </font></font><font face="Arial, Helvetica, sans-serif"> <?PHP print $lang_486; ?></font></font></font></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_487; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="a_em" type="text" id="a_em" value="<?PHP        print $row["a_em"];        ?>">
        </font></td>
    </tr>
    <tr>
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_538; ?><br>
        <font size="1"><?PHP print $lang_539; ?></font></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif">
        <input type="radio" name="a_priv" value="1" <?PHP        if ($row["a_priv"] == 1){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_197; ?></font></font><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;
        <input type="radio" name="a_priv" value="0" <?PHP        if ($row["a_priv"] == 0){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_198; ?></font></font><font face="Arial, Helvetica, sans-serif">
        </font></font></td>
    </tr>
    <tr bgcolor="#F3F3F3">
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_548; ?></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="a_atch" type="text" id="a_atch" value="<?PHP        print $row["a_atch"];        ?>">
        </font></td>
    </tr>
  </table>
  <?PHP } ?>
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="350"><p><font size="2"><font face="Arial, Helvetica, sans-serif">
          <br>
          <input name="Submit" type="submit" id="Submit" value="<?PHP print $lang_98; ?>">
          </font><font size="2" face="Arial, Helvetica, sans-serif">
          <input name="page" type="hidden" id="page" value="list_settingsm">
          <input name="action" type="hidden" id="action" value="save">
          <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
          </font><font face="Arial, Helvetica, sans-serif"> </font></font></p></td>
    </tr>
  </table>
</form>
<p>
  <?PHP if ($row_admin["m_cre_del"] == 1){ ?>
</p>
<div align="left">
  <table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
    <tr>
      <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
        <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
          <tr>
            <td bgcolor="#D5E2F0"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_488; ?><a name="c"></a></strong></font></div></td>
          </tr>
          <tr>
            <td height="27" bgcolor="#FFFFFF"><form action="main.php" method="post" name="" id="">
                <br>
                <table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
                  <tr valign="top">
                    <td height="30">&nbsp;</td>
                    <td height="30"><font size="2" face="Arial, Helvetica, sans-serif">
                      <input name="clusers" type="checkbox" id="clusers3" value="1" checked>
                      <?PHP print $lang_489; ?> &nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="clsettings" type="checkbox" id="clsettings" value="1" checked>
                      <?PHP print $lang_490; ?></font></td>
                  </tr>
                  <tr valign="top">
                    <td width="100"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_325; ?>
                      <a href="#"  onclick="MM_openBrWindow('asdasdasdjaosdfkupport/docs/12all/view.php?id=3152','Help','scrollbars=yes,width=316,height=350')">[?]</a></font></td>
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
                    <td><br> <input type="submit" name="Submit2" value="<?PHP print $lang_21; ?>">
                      <input name="page" type="hidden" id="page" value="list_add">
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
</div>
<p>&nbsp;</p>
<p>
  <?PHP
}
else {
?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_99; ?></font>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000">
  <?PHP
if ($a_s1 == ""){
$a_s1 = "1";
}
if ($a_s2 == ""){
$a_s2 = "1";
}
if ($a_s3 == ""){
$a_s3 = "1";
}
$a_ep = ''.$ep3.'-'.$ep1.'-'.$ep2;
mysql_query("UPDATE Lists SET name='$name',email='$email',a_ui='$a_ui',a_ua='$a_ua',a_is='$a_is',a_as='$a_as',a_ff='$a_ff',a_nm='$a_nm',a_pt='$a_pt',a_tp='$a_tp',a_gc='$a_gc',a_ed='$a_ed',a_mx='$a_mx',a_s1='$a_s1',a_s2='$a_s2',a_s3='$a_s3',a_em='$a_em',a_lt='$a_lt',a_pz='$a_pz',a_bn='$a_bn',a_co='$a_co',a_op='$a_op',a_rq='$a_rq',a_ep='$a_ep',a_sc='$a_sc',a_priv='$a_priv',a_atch='$a_atch' WHERE (id='$nl')");
?>
  </font>
  <?PHP
}
?>
</p>