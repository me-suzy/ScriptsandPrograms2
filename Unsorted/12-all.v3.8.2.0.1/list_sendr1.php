
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_232; ?></strong></font></p>
<p><font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><b><font color="#666666"><?PHP print $lang_233; ?></font></b> 
  <font color="#666666">&gt; <?PHP print $lang_234; ?> &gt; <?PHP print $lang_235; ?> 
  &gt; <?PHP print $lang_236; ?></font></font></font></p>
<form name="form1" method="post" action="main.php">
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
                        <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>" <?PHP 
						$nvid = $find["id"];
						$cnl = ", $nvid ,";
						$nlsearch = mysql_query ("SELECT * FROM Messages
						WHERE nl LIKE '%$cnl%'
						AND id LIKE '$previewalpha'
						");
						if ($nlsearchrow = mysql_fetch_array($nlsearch)) {
						print "checked";
						}
						else {
						if ($nl == $nvid){
						print "checked";
						}
						}
						?>>
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
  <table width="500" border="0" cellspacing="0" cellpadding="0" bgcolor="#BFD2E8">
    <tr> 
      <td> <table width="100%" border="0" cellspacing="1" cellpadding="4">
          <tr bgcolor="#BFD2E8"> 
            <td width="50%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Body 
                Tag Template</strong><b> </b></font></div></td>
            <td width="50%"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Sending 
                Filter </b></font></div></td>
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
  <p> 
    <input type="submit" name="Submit" value="<?PHP print $lang_247; ?>">
    <input type="hidden" name="page" value="list_send2">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
    <input name="pfrom" type="hidden" id="pfrom" value="<?PHP print $pfrom; ?>">
    <input name="psubject" type="hidden" id="psubject" value="<?PHP print $psubject; ?>">
    <input name="ptext" type="hidden" id="ptext" value="<?PHP print $ptext; ?>">
    <input name="format" type="hidden" id="format" value="<?PHP print $format; ?>">
    <input name="pcontent2" type="hidden" id="pcontent2" value="<?PHP print $previewalpha; ?>">
    <input name="savid" type="hidden" id="savid" value="<?PHP print $savid; ?>">
  </p>
</form>
