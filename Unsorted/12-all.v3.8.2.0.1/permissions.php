<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_312; ?></strong></font></p>
<table width="450" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td width="0" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/circ_<?PHP if ($row_admin["m_users"] == 1){ print "yes"; } else{ print "no"; } ?>.gif" width="27" height="27"></font></div></td>
    <td width="400" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_313; ?></font></td>
  </tr>
  <tr> 
    <td width="0"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/circ_<?PHP if ($row_admin["m_lists"] == 1){ print "yes"; } else{ print "no"; } ?>.gif" width="27" height="27"></font></div></td>
    <td width="400"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_314; ?></font></td>
  </tr>
  <tr bgcolor="#F3F3F3"> 
    <td width="0"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/circ_<?PHP if ($row_admin["m_cre_del"] == 1){ print "yes"; } else{ print "no"; } ?>.gif" width="27" height="27"></font></div></td>
    <td width="400" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_315; ?></font></td>
  </tr>
  <tr> 
    <td width="0"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/circ_<?PHP if ($row_admin["send"] == 1){ print "yes"; } else{ print "no"; } ?>.gif" width="27" height="27"></font></div></td>
    <td width="400"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_316; ?></font></td>
  </tr>
  <tr bgcolor="#F3F3F3">
    <td><img src="media/circ_yes.gif" width="27" height="27"></td>
    <td><p><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_317; ?>:</strong></font></p>
      <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#BFD2E8">
        <tr> 
          <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center" bgcolor="#FFFFFF">
              <?PHP 
$result = mysql_query ("SELECT * FROM Lists
                         WHERE name != ''
                       	ORDER BY name
						");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
$selid = $row["id"];
$seluser = $row_admin["user"];
					$selid = " , $selid ";

					$selector = mysql_query ("SELECT * FROM Admin
		WHERE user LIKE '$seluser'
		AND lists LIKE '%$selid%'
						");

if ($seld = mysql_fetch_array($selector))
{


?>
              <tr <?PHP if ($cpick == 0){ ?>bgcolor="#F3F3F3"<?PHP } else{ ?>bgcolor="#E9E9E9"<?PHP } ?>> 
                <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
                    <font size="2"> <font color="#000000"> <?PHP print $row["name"]; ?> 
                    </font></font></font></div></td>
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
                    <font size="2"> <font color="#000000"><?PHP print $lang_32; ?></font></font></font></div></td>
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
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#333333">
  <tr>
    <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_143; ?></strong></font></div>
      <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
        <tr> 
          <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/circ_yes.gif" width="27" height="27"><br>
              <?PHP print $lang_318; ?></font></div></td>
          <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/circ_no.gif" width="27" height="27"><br>
              <?PHP print $lang_319; ?></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<p><strong><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"></font></strong></p>
