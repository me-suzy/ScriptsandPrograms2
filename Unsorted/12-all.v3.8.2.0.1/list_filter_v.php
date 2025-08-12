<?PHP
        @ini_set('max_execution_time', '950*60');
        @set_time_limit (950*60);
        $msgCounter == 0;

if ($p == ""){
$p = 1;
}
if ($cort == ""){
$cort = email;
}
$result = mysql_query ("SELECT * FROM Templates
                                                WHERE id LIKE '$q'
                                                AND type LIKE 'FILTER'
                                                ");
$row = mysql_fetch_array($result);
$q = $row["content"];
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_457; ?>
  </strong></font></p>
<table width="100%" height="40" border="0" cellpadding="1" cellspacing="0" bgcolor="#D5E2F0">
  <tr>
    <td><table width="100%" height="23" border="0" cellpadding="8" cellspacing="0">
        <tr bgcolor="#ECF8FF">
          <td> <div align="left">
              <p><font color="#FF0000" size="2" face="Arial, Helvetica, sans-serif">Matching
                Addresses for:</font></p>
            </div>
            <blockquote>
              <div align="left"><font size="1" face="Arial, Helvetica, sans-serif">WHERE
                <?PHP print stripslashes($q); ?></font></div>
            </blockquote></td>
        </tr>
      </table></td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <p>
    <textarea name="textfield" cols="65" rows="12"><?PHP
$q = stripslashes($q);
                $prefilter = "AND nl LIKE '$nl'
                                        AND email != ''
                                        AND active LIKE '0'";
                $filterdata = "AND $q";
                $filterdata = str_replace (" DIVIN", "$prefilter", $filterdata);

$result = mysql_query ("SELECT * FROM ListMembers
                                                                        WHERE active LIKE '0'
                                                                        AND email != ''
                                                                        AND nl LIKE '$nl'
                                                                        $filterdata
                                                                       ORDER BY email
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
print $row["email"];
print "\n";
                        if ($msgCounter % 40 == 0){
                                @mysql_close($db_link);
                                require("engine.inc.php");
                                $msgCounter == 0;
                        }
                        $msgCounter++;
                        flush();

}
} else {print "$lang_32.
          ";} ?></textarea>
  </p>
  <p>&nbsp; </p>
  </form>
<div align="center">

    <?PHP if ($row_admin["m_cre_del"] == 1){ ?>

  <div align="left">
    <table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
      <tr>
        <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
          <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
            <tr>
              <td bgcolor="#D5E2F0"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_324; ?>
                  Containg These Users</strong></font></div></td>
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
                        <input name="qpadd" type="hidden" id="qpadd" value="<?PHP print $q; ?>">
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
</div>