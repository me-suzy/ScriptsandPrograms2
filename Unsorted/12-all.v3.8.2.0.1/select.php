<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250"><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_320; ?></strong></font></td>
    <td><div align="right"><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><?PHP print $lang_423; ?>,
        <?PHP print $row_admin["name"]; ?></font></div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#BFD2E8">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center" bgcolor="#FFFFFF">
        <tr bgcolor="#D5E2F0">
          <td bordercolor="#CCCCCC" bgcolor="#D5E2F0"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_25; ?> </b></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>#
              <?PHP print $lang_321; ?></b></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_322; ?> (#)</b></font></div></td>
        </tr>
        <?PHP
$result = mysql_query ("SELECT * FROM Lists
                         WHERE name != ''
                               ORDER BY name
                                                ");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
$selid = $row["id"];
                                        $selid = " , $selid ";
$seluser = $row_admin["user"];
                                        $selector = mysql_query ("SELECT * FROM Admin
                WHERE user LIKE '$seluser'
                AND lists LIKE '%$selid%'
                                                ");

if ($seld = mysql_fetch_array($selector))
{


?>
        <tr <?PHP if ($cpick == 0){ ?>bgcolor="#F3F3F3"<?PHP } else{ ?>bgcolor="#E9E9E9"<?PHP } ?>>
          <td bordercolor="#CCCCCC"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                          $today = date("Y-m-d");
                          if ($row["a_ep"] == "0000-00-00" OR $row["a_ep"] >= $today){
                          ?>
                          <a href="main.php?nl=<?PHP print $row["id"]; ?>">
              <font size="2"> <font color="#000000"> <?PHP print $row["name"]; ?>
              </font></font></a>
                          <?PHP
                          }
                          else {
                          ?>
                          <font size="2"> <font color="#000000"> <?PHP print $row["name"]; ?>  - Expired
              </font></font>
                          <?PHP
                          }
                          ?>
                                                    </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                          $nlid = $row["id"];
                                          $findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nlid'
                                                 AND active = '0'
                                                AND email != ''
                       ");
                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;
?>
              </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                                                    $nlid = $row["id"];

                                          $findcount = mysql_query ("SELECT COUNT(nl) FROM Messages
                         WHERE nl LIKE '$nlid'
                       ");

                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;
?>
              </font></div></td>
        </tr>
        <?PHP
                                   if ($cpick == 0){
  $cpick = 1;
  }
  else {
  $cpick = 0;
  }
  }
}

} else {
?>
        <tr bgcolor="#FFFFFF">
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif">
              <font size="2"> <font color="#000000"><?PHP print $lang_323; ?></font></font></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              </font></div></td>
        </tr>
        <?PHP
                                   if ($cpick == 0){
  $cpick = 1;
  }
  else {
  $cpick = 0;
  }
}
?>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<?PHP if ($row_admin["m_cre_del"] == 1){ ?>
<table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
  <tr>
    <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
      <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
        <tr>
          <td bgcolor="#D5E2F0"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_324; ?></strong></font></div></td>
        </tr>
        <tr>
          <td height="27" bgcolor="#FFFFFF"><form action="main.php" method="post" name="" id="">
              <br>
              <table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr valign="top">
                  <td width="100"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_325; ?> <a href="#"  onclick="MM_openBrWindow('thjdsfgjheklfhklsdjfhklsdfhlksjdfhkljsd_min/index.php?page=index_v2&id=76&c=7','Help','scrollbars=yes,width=350,height=375')">[?]</a></font></td>
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
                  </td>
                </tr>
              </table>
            </form>

          </td>
        </tr>
      </table></td>
  </tr>
</table>
<?PHP
}
?>