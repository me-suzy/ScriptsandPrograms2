 <script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="4" face="Arial, Helvetica, sans-serif">
  <?PHP
                  $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'

                                                 limit 1
                       ");
$row = mysql_fetch_array($result);
print $row["name"];
?>
  </font></b></font></b></font></b></font></b></font><font size="2"><?PHP print $lang_59; ?>
  </font></font></p>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
        <tr>
          <td bgcolor="#D5E2F0"><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_338; ?> </font></strong></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td width="50%"> <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr bgcolor="#ECF8FF">
          <td> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_339; ?> </font></b></font></div></td>
        </tr>
      </table>
      <table width="250" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
        <tr>
          <td width="125"> <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><?PHP print $lang_340; ?></font></div></td>
          <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                          $findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nl'
                                                 AND email != ''
                                                  AND active = '0'
                       ");
                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;

?>
              </font></div></td>
        </tr>
        <tr>
          <td width="125"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_341; ?></font></div></td>
          <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                                                    $findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nl'
                                                 AND email != ''
                                                  AND active = '0'
                       ");
                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;

?>
              </font></div></td>
        </tr>
        <tr>
          <td width="125"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_342; ?> <font size="2"><a href="#"  onclick="MM_openBrWindow('dfgdfgdfgdfg','Help','scrollbars=yes,width=316,height=350')">[?]</a></font></font></div></td>
          <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                                                    $findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nl'
                                                 AND email != ''
                                                  AND active = '1'
                       ");
                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;
?>
              </font></div></td>
        </tr>
        <tr>
          <td width="125"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_343; ?></font></div></td>
          <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                                                    $findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nl'
                                                 AND email != ''
                                                  AND bounced != '0'
                       ");
                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;

?>
              </font></div></td>
        </tr>
        <tr>
          <td width="125"> <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">#
              <?PHP print $lang_344; ?> </font></div></td>
          <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                                        $today = date("Y-m-d");
                          $findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nl'
                                                 AND email != ''
                                                  AND active LIKE '0'
                                                 AND sdate LIKE '$today'
                       ");
                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;
?>
              </font></div></td>
        </tr>
      </table>
      <img src="media/line_mblue.gif" width="250" height="1"><font size="2" face="Arial, Helvetica, sans-serif"><strong>
      <?PHP if ($row_admin["user"] == admin){ ?>
      </strong></font><br>
      <br>
      <table width="250" border="0" cellspacing="0" cellpadding="1">
        <tr bgcolor="#ECF8FF">
          <td> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_345; ?><font size="1"><br>
              (<?PHP print $lang_346; ?>)</font></font></b></font></div></td>
        </tr>
      </table>
      <table width="250" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td bordercolor="#CCCCCC"> <div align="left"><font color="#CCCCCC" size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_2; ?></b></font></div></td>
          <td width="160" bordercolor="#CCCCCC"> <div align="center"><font color="#CCCCCC" size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_4; ?></b></font></div></td>
          <td width="13" bordercolor="#CCCCCC"> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"></font></div></td>
        </tr>
        <?PHP
$result312 = mysql_query ("SELECT * FROM Admin
                         WHERE user != ''
                               ORDER BY user
                                                ");
if ($c1 = mysql_num_rows($result312)) {

while($row312 = mysql_fetch_array($result312)) {
$selid = $row["id"];
$selid = " , $selid ";
$seluser = $row312["user"];
$selector = mysql_query ("SELECT * FROM Admin
                WHERE user LIKE '$seluser'
                AND lists LIKE '%$selid%'
                                                ");
if ($seld = mysql_fetch_array($selector))
{
?>
        <tr>
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif">
              <font size="2"> <font color="#000000"> <?PHP print $row312["user"]; ?>
              </font></font></font></div></td>
          <td width="160" bordercolor="#CCCCCC"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#000000"><?PHP print $row312["name"]; ?></font></font></font><font size="2" face="Arial, Helvetica, sans-serif">
              </font></div></td>
          <td width="13" bordercolor="#CCCCCC"> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">
              <a href="main.php?page=admin&id=<?PHP print $row312["id"]; ?>&nl=<?PHP print $nl; ?>&job=modify"><img src="media/edit.gif" width="11" height="7" border="0"></a></font></div></td>
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
        <tr>
          <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif">
              <font size="2"> <font color="#000000">ERROR. NO ADMIN USERS FOUND.
              <strong>SCRIPT ERROR - EINSTALL SOFTWARE</strong></font></font></font></div></td>
          <td width="160" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              </font></div></td>
          <td width="13" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">
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
      </table>
      <p> <font size="1" face="Arial, Helvetica, sans-serif"><a href="main.php?page=admin&nl=<?PHP print $nl; ?>"><?PHP print $lang_347; ?></a><br>
        </font><img src="media/line_mblue.gif" width="250" height="1"><font size="2" face="Arial, Helvetica, sans-serif"><strong>
        <?PHP
                          }
                          ?>
        </strong></font></p>
      </td>
    <td width="50%"> <div align="right">
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#ECF8FF">
            <td> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_348; ?> </font></b></font></div></td>
          </tr>
        </table>
        <table width="250" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFFFFF">
          <tr>
            <td> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_349; ?></font></div></td>
            <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <?PHP
                                $findcount = mysql_query ("SELECT COUNT(nl) FROM Messages
                         WHERE nl LIKE '$nl'
                       ");

                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;
?>
                </font></div></td>
          </tr>
          <tr>
            <td width="125"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_350; ?></font></div></td>
            <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <?PHP
                                        $today = date("Y-m-d");
                                $findcount = mysql_query ("SELECT COUNT(nl) FROM Messages
                         WHERE nl LIKE '$nl'
                                                 AND mdate LIKE '$today'
                       ");

                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;
?>
                </font></div></td>
          </tr>
          <tr>
            <td><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_351; ?></font></div></td>
            <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <a href="main.php?page=list_queue&nl=<?PHP print $nl; ?>">
                <?PHP
                                        $today = date("Y-m-d");
                                $findcount = mysql_query ("SELECT COUNT(nl) FROM Messages
                         WHERE nl LIKE '$nl'
                                                 AND completed LIKE '0'
                       ");

                                $num_email = mysql_result($findcount, 0, 0);
                                print $num_email;
?>
                </a></font></div></td>
          </tr>
        </table>
      </div>
      <p align="right"><img src="media/line_mblue.gif" width="250" height="1"></p>
      <div align="right">
        <table width="250" border="0" cellpadding="1" cellspacing="0">
          <tr bgcolor="#ECF8FF">
            <td> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_352; ?> </font></b></font></div></td>
          </tr>
        </table>
        <table width="250" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFFFFF">
          <tr>
            <td width="125"> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_353; ?></font></div></td>
            <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                <?PHP print $row["date"]; ?></font></div></td>
          </tr>
        </table>
        <p><br>
          <img src="media/line_mblue.gif" width="250" height="1"></p>
        <p><a href="main.php?page=engine_code&nl=<?PHP print $nl; ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_354; ?></font></a></p>
        <p><img src="media/line_mblue.gif" width="250" height="1"></p>
      </div>
      </td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
        <tr>
          <td bgcolor="#D5E2F0"><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_355; ?></font></strong></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr bgcolor="#ECF8FF">
    <td width="78"> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_29; ?></font></b></font></div></td>
    <td width="68"> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_30; ?></font></b></font></div></td>
    <td> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_31; ?></font></b></font></div></td>
    <td width="37">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td bgcolor="#D5E2F0"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
        <?PHP
$result = mysql_query ("SELECT * FROM Messages
                                                WHERE nl LIKE '$nl'
                               ORDER BY mdate DESC, mtime DESC, subject
                                                LIMIT 10
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
        <tr bgcolor="#FFFFFF">
          <td width="70" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP print $row["mdate"]; ?> </font></div></td>
          <td bordercolor="#CCCCCC" width="60"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP print $row["mtime"]; ?> </font></div></td>
          <td bordercolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                        if ($row["subject"] == ""){
                        print "$lang_32";
                        }
                        else {
                          $subject = ereg_replace ("[\]", "", $row["subject"]);

                        print $subject;
                        }
                        ?>
              </font></div></td>
          <td width="30" bordercolor="#CCCCCC"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_archive&val=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>&opt=view"><img src="media/info.gif" width="11" height="7" border="0"></a>
            &nbsp;</font></td>
        </tr>
        <?PHP
}

} else {print "$lang_356
          ";} ?>
      </table></td>
  </tr>
</table>
<br>
<br>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
        <tr>
          <td bgcolor="#D5E2F0"><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_357; ?></font></strong></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr bgcolor="#ECF8FF">
    <td width="78"> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_29; ?></font></b></font></div></td>
    <td width="176"> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></b></font></div></td>
    <td> <div align="center"><font color="#80A8D0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?> </font></b></font></div></td>
    <td width="37">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td bgcolor="#D5E2F0"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
        <?PHP
$result = mysql_query ("SELECT * FROM ListMembers
                                                WHERE nl LIKE '$nl'
                                                AND email != ''
                                                 AND active LIKE '0'
                               ORDER BY sdate DESC, email
                                                LIMIT 10
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
        <tr bgcolor="#FFFFFF">
          <td width="71" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["sdate"]; ?>
              </font></div></td>
          <td width="170" bordercolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                          $namclown = $row["name"];
                          if ($namclown == ""){
                          print "-";
                          }
                          else {
                           print $namclown;
                           }
                          ?>
              </font></div></td>
          <td bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["email"]; ?></font></div></td>
          <td width="30" bordercolor="#CCCCCC"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_details&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/info.gif" width="11" height="7" border="0"></a>
            &nbsp;</font></td>
        </tr>
        <?PHP
}

} else {print "$lang_358
          ";} ?>
      </table></td>
  </tr>
</table>