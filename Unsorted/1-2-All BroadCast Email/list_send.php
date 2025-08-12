<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="160"><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_232; ?></strong></font></td>
    <td><div align="right"><font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#000000" size="1"><?PHP print $lang_233; ?></font></font></font><font color="#999999" size="1">
        </font> <font color="#999999" size="1">&gt;</font><font color="#<?PHP if ($val == "compose"){ print "333333"; } else { print "999999"; } ?>" size="1">
        <?PHP print $lang_234; ?> </font><font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#999999" size="1">&gt;</font></font></font><font color="#<?PHP if ($val == "preview"){ print "333333"; } else { print "999999"; } ?>" size="1">
        <?PHP print $lang_235; ?> <font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#999999" size="1">&gt;</font></font></font><font color="#999999">
        <?PHP print $lang_236; ?></font></font></font><font color="#666666" size="1">
        </font></font></div></td>
  </tr>
</table>
<br>
<table width="500" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFFFF">
        <tr>
          <td> <p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_237; ?></font></p></td>
        </tr>
      </table></td>
  </tr>
</table><form name="form1" method="post" action="main.php"><table width="500" border="0" cellspacing="0" cellpadding="0" bgcolor="#BFD2E8">
    <tr>
      <td> <table width="100%" border="0" cellspacing="1" cellpadding="4">
          <tr valign="top" bgcolor="#BFD2E8">
            <td colspan="3"> <div align="center"><font size="2"><b><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_238; ?> </font></b><font size="2" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('http://www./support/12all_kb_min/index.php?page=index_v2&id=77&c=9','Help','scrollbars=yes,width=350,height=375')">[?]</a></font></font></div></td>
          </tr>
          <tr valign="top">
                        <?PHP
                        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_s1"] == 0){
                        ?>
            <td bgcolor="#D5E2F0" width="33%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>
                <input type="radio" name="format" value="multi" checked>
                <br>
                <?PHP print $lang_239; ?><br>
                </b> <font size="1"><?PHP print $lang_240; ?></font></font></div></td>
                        <?PHP
                        }
                        if ($chk["a_s2"] == 0){
                        ?>
            <td bgcolor="#F3F3F3" width="33%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>
                <input type="radio" name="format" value="html" <?PHP if ($chk["a_s1"] == 1){ print "checked"; } ?>>
                <br>
                <?PHP print $lang_241; ?> </b></font></div></td>
                        <?PHP
                        }
                        if ($chk["a_s3"] == 0){
                        ?>
            <td bgcolor="#F3F3F3" width="33%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>
                <input type="radio" name="format" value="text" <?PHP if ($chk["a_s1"] == 1 AND $chk["a_s2"] == 1){ print "checked"; } ?>>
                <br>
                <?PHP print $lang_242; ?></b></font></div></td>
                        <?PHP } ?>
          </tr>
        </table></td>
    </tr>
  </table>
  <br>
  <?PHP
                                                        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_tp"] == "0") {
                        ?>
  <table width="500" border="0" cellspacing="0" cellpadding="0" bgcolor="#BFD2E8">
    <tr>
      <td> <table width="100%" border="0" cellspacing="1" cellpadding="4">
          <tr bgcolor="#BFD2E8">
            <td width="50%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_243; ?>
                </b></font></div></td>
            <td width="50%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_244; ?>
                </b></font></div></td>
          </tr>
          <tr bgcolor="#F3F3F3">
            <td width="50%" bgcolor="#F3F3F3"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <select name="htmltemp" size=1>
                  <option selected><font color="#000000"><?PHP print $lang_245; ?></font></option>
                  <?PHP
$utemp = $row_admin["user"];
$setter = mysql_query ("SELECT * FROM Templates
                         WHERE nl LIKE '$nl'
                                                 AND type LIKE 'html'
                                                 OR uni LIKE 'all'
                                                 AND type LIKE 'html'
                                                 OR uni LIKE '$utemp'
                                                 AND type LIKE 'html'
                                                 ORDER BY name
                       ");

if ($c1 = mysql_num_rows($setter)) {

while($set = mysql_fetch_array($setter)) {
?>
                  <option value="<?PHP
                                print $set["id"];
                                  ?>"><font color="#000000"> <?PHP print $set["name"]; ?> </font></option>
                  <?PHP
}

} else {print "DB LINK ERROR
          ";} ?>
                </select>
                </font></div></td>
            <td width="50%" bgcolor="#F3F3F3"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <select name="texttemp" size=1>
                  <option selected><font color="#000000"><?PHP print $lang_245; ?></font></option>
                  <?PHP

$setter = mysql_query ("SELECT * FROM Templates
                         WHERE nl LIKE '$nl'
                                                 AND type LIKE 'text'
                                                 OR uni LIKE 'all'
                                                 AND type LIKE 'text'
                                                 OR uni LIKE '$utemp'
                                                 AND type LIKE 'text'
                                                 ORDER BY name
                       ");

if ($c1 = mysql_num_rows($setter)) {

while($set = mysql_fetch_array($setter)) {
?>
                  <option value="<?PHP print $set["id"]; ?>"><font color="#000000">
                  <?PHP print $set["name"]; ?> </font></option>
                  <?PHP
} ;

} else {print "DB LINK ERROR
          ";} ?>
                </select>
                </font></div></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <br>
  <?PHP } ?>
  <table width="500" border="0" cellspacing="0" cellpadding="0" bgcolor="#BFD2E8">
    <tr>
      <td> <table width="100%" border="0" cellspacing="1" cellpadding="4">
          <tr bgcolor="#BFD2E8">
            <td width="50%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_462; ?> <?PHP print $lang_285; ?></strong><b> </b></font></div></td>
            <td width="50%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_457; ?> </b></font></div></td>
          </tr>
          <tr bgcolor="#F3F3F3">
            <td width="50%" bgcolor="#F3F3F3"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <select name="btag" size=1 id="select3">
                  <option value="" selected>Default</option>
                  <?PHP

$setter = mysql_query ("SELECT * FROM Templates
                         WHERE nl LIKE '$nl'
                                                 AND type LIKE 'BODYTAG'
                                                 OR uni LIKE 'all'
                                                 AND type LIKE 'BODYTAG'
                                                 OR uni LIKE '$utemp'
                                                 AND type LIKE 'BODYTAG'
                                                 ORDER BY name
                       ");

if ($c1 = mysql_num_rows($setter)) {

while($set = mysql_fetch_array($setter)) {
?>
                  <option value="<?PHP print $set["id"]; ?>"><font color="#000000">
                  <?PHP print $set["name"]; ?> </font></option>
                  <?PHP
}

} else {print "DB LINK ERROR
          ";} ?>
                </select>
                </font></div></td>
            <td width="50%" bgcolor="#F3F3F3"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <select name="filter" size=1 id="filter">
                  <option value="" selected><font color="#000000"><?PHP print $lang_245; ?></font></option>
                  <?PHP
$utemp = $row_admin["user"];
$setter = mysql_query ("SELECT * FROM Templates
                         WHERE nl LIKE '$nl'
                                                 AND type LIKE 'FILTER'
                                                 OR uni LIKE 'all'
                                                 AND type LIKE 'FILTER'
                                                 OR uni LIKE '$utemp'
                                                 AND type LIKE 'FILTER'
                                                 ORDER BY name
                       ");

if ($c1 = mysql_num_rows($setter)) {

while($set = mysql_fetch_array($setter)) {
?>
                  <option value="<?PHP
                                print $set["id"];
                                  ?>"><font color="#000000"> <?PHP print $set["name"]; ?> </font></option>
                  <?PHP
}

} else {print "DB LINK ERROR
          ";} ?>
                </select>
                </font></div></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <br>
  <table width="500" border="0" cellspacing="0" cellpadding="0" bgcolor="#BFD2E8">
    <tr>
      <td> <table width="100%" border="0" cellspacing="1" cellpadding="4">
          <tr bgcolor="#ECECFF">
            <td bgcolor="#BFD2E8"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_246; ?></b></font></div></td>
          </tr>
        </table>
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="#FFFFFF">
            <td> <div align="center"> <font size="2" face="Arial, Helvetica, sans-serif">
                </font></div>
              <div align="center">
                <table width="500" border="0" cellspacing="1" cellpadding="3" align="center">
                  <tr bgcolor="#FFFFFF">
                    <?PHP
                $numbox = 1;
                if (empty($offset)) {
    $offset=0;
}
                $count1 = 0;


                $finder = mysql_query ("SELECT * FROM Lists
                WHERE name != ''
                               ORDER BY name
                                                ");

if ($c1 = mysql_num_rows($finder))
{
while($find = mysql_fetch_array($finder)) {
$selid = $find["id"];
$seluser = $row_admin["user"];
                                        $selid = " , $selid ";
                                        $selector = mysql_query ("SELECT * FROM Admin
                WHERE user LIKE '$seluser'
                AND lists LIKE '%$selid%'
                                                ");

if ($seld = mysql_fetch_array($selector))
{

?>
                    <td width="144" bgcolor="#<?PHP if ($nl == $find["id"]){ print D5E2F0; } else { print F3F3F3; } ?>">
                      <div align="left"><font size="2" face="Arial, Helvetica, sans-serif">
                        <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>" <?PHP if ($nl == $find["id"]){ print checked; } ?>>
                        <?PHP print $find["name"]; ?> </font></div></td>
                    <?PHP
$count1 = $count1 + 1;
$numbox = $numbox + 1;

if ($count1 == 4){
?>
                  </tr>
                  <tr bgcolor="#FFFFFF">
                    <?PHP
$count1 = 0;
}
}
}
while($count1 != 4 AND $count1 != 0) {
if ($count1 != 0){
?>
                    <td width="144" bgcolor="#F3F3F3" >&nbsp; </td>
                    <?PHP

$count1 = $count1 + 1;
}
}
}
else {
?>
                    <font size="2" face="Arial, Helvetica, sans-serif">
                    <?PHP
print "$lang_19";
?>
                    </font>
                    <?PHP
}
?>
                </table>
                <font size="2" face="Arial, Helvetica, sans-serif"> </font></div></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <br>
  <p>
    <input type="submit" name="Submit" value="<?PHP print $lang_247; ?>">
    <input type="hidden" name="page" value="list_send2">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
  </p>
</form>