 <p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_460; ?></strong></font></p>
  <?PHP if ($action != "go"){ ?>
<form name="form1" method="post" action="main.php">
  <table width="500" border="0" cellspacing="0" cellpadding="0" bgcolor="#BFD2E8">
    <tr> 
      <td> <table width="100%" border="0" cellspacing="1" cellpadding="4">
          <tr bgcolor="#ECECFF"> 
            <td bgcolor="#BFD2E8"> <div align="center"><font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><strong><?PHP print $lang_461; ?></strong></font></font></div></td>
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


		$finder = mysql_query ("SELECT * FROM ListMembers
		WHERE email != ''
		AND active LIKE '0'
		AND nl LIKE '$nl'
                       	ORDER BY email 
						");

if ($c1 = mysql_num_rows($finder))
{
while($find = mysql_fetch_array($finder)) {

?>
                    <td width="288" bgcolor="#<?PHP if ($nl == $find["id"]){ print D5E2F0; } else { print F3F3F3; } ?>"> 
                      <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
                        <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>">
                        <?PHP print $find["email"]; ?> </font></div></td>
                    <?PHP
$count1 = $count1 + 1;
$numbox = $numbox + 1;

if ($count1 == 2){
?>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <?PHP
$count1 = 0;

}
}
while($count1 != 2 AND $count1 != 0) {
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
  <table width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr> 
      <td colspan="3"><br> <font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_4; ?></strong><br>
        <font size="1"><?PHP print $lang_185; ?></font> </font> </td>
    </tr>
    <tr> 
      <td colspan="3" bgcolor="#F3F3F3"><input name="fname" type="text" id="fname" size="35"></td>
    </tr>
    <tr> 
      <td height="30" colspan="3"><font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
        <?PHP print $lang_294; ?></strong><br>
        <font size="1"><?PHP print $lang_295; ?><br>
        </font><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="univers" value="<?PHP print $row_admin["user"]; ?>" <?PHP $utemp = $row_admin["user"]; if ($row["uni"] == "$utemp"){ print "checked";} ?>>
        <?PHP print $lang_296; ?> <?PHP print $row_admin["user"]; ?> <?PHP print $lang_297; ?>.<br>
        <input type="radio" name="univers" value="all" <?PHP if ($row["uni"] == "all"){ print "checked";} ?>>
        <?PHP print $lang_298; ?><br>
        <input name="univers" type="radio" value="" <?PHP if ($row["uni"] == ""){ print "checked";} ?>>
        <?PHP print $lang_299; ?></font><font size="1"> </font></font></td>
    </tr>
  </table>
  <p> </p>
  <p> 
    <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>">
    <input type="hidden" name="page" value="list_filter_ae">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
    <input name="action" type="hidden" id="action" value="go">
  </p>
</form>
<?PHP } 
else { 
		$cucc = 1; 
		foreach ($nlbox as $something) 
		{
		if ($something != "")
		{ 
		if ($cucc == "1"){
			$nquery = "id LIKE '$something'\n DIVIN ";
		}
		else {
			$nquery = "$nquery OR id LIKE '$something'\n DIVIN ";
		}
		$cucc = $cucc + 1;
		} 
		}
	$nquery = addslashes($nquery);
	$name = addslashes($fname);
	mysql_query ("INSERT INTO Templates (nl, name, content, type, uni) VALUES ('$nl' ,'$name' ,'$nquery' ,'FILTER' ,'$univers')");  
	print "$name, $lang_181.<p><br>";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=main.php?page=list_filter&nl=$nl\">";
}
?>