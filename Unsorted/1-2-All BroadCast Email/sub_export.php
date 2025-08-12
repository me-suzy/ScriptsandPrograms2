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
?>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_371; ?> 
  </strong></font></p>
<table width="570" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="175"><div align="center"> 
        <table width="100%" height="29" border="0" cellpadding="3" cellspacing="0" background="media/h_n1.gif">
          <tr> 
            <td><div align="center"><font color="#003366" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_536; ?></strong></font></div></td>
          </tr>
        </table>
      </div></td>
    <td width="20" bgcolor="#FFFFFF"><div align="center"><font size="2"><font face="Arial, Helvetica, sans-serif"></font></font></div></td>
    <td width="175"><div align="center"> 
        <table width="100%" height="29" border="0" cellpadding="3" cellspacing="0" background="media/h_n1.gif">
          <tr> 
            <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="sub_export_csv.php?nl=<?PHP print $nl; ?>"><strong><?PHP print $lang_537; ?></strong></a></font></div></td>
          </tr>
        </table>
      </div></td>
    <td width="200" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr valign="top" bgcolor="#FFFFFF"> 
    <td colspan="4"><img src="media/h_b.gif" width="560" height="1"></td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <p> 
    <textarea name="textfield" cols="65" rows="12"><?PHP 
$result = mysql_query ("SELECT * FROM ListMembers
						 WHERE email != ''
						 AND nl LIKE '$nl'
                       	ORDER BY email
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
print $row["email"];
if ($row["name"] != ""){
print ";";
print $row["name"];
}
else {
print "; ";
}
if ($row["field1"] != ""){
print ";";
print $row["field1"];
}
else {
print "; ";
}
if ($row["field2"] != ""){
print ";";
print $row["field2"];
}
else {
print "; ";
}
if ($row["field3"] != ""){
print ";";
print $row["field3"];
}
else {
print "; ";
}
if ($row["field4"] != ""){
print ";";
print $row["field4"];
}
else {
print "; ";
}
if ($row["field5"] != ""){
print ";";
print $row["field5"];
}
else {
print "; ";
}
if ($row["field6"] != ""){
print ";";
print $row["field6"];
}
else {
print "; ";
}
if ($row["field7"] != ""){
print ";";
print $row["field7"];
}
else {
print "; ";
}
if ($row["field8"] != ""){
print ";";
print $row["field8"];
}
else {
print "; ";
}
if ($row["field9"] != ""){
print ";";
print $row["field9"];
}
else {
print "; ";
}
if ($row["field10"] != ""){
print ";";
print $row["field10"];
}
else {
print "; ";
}
if ($row["sdate"] != ""){
print ";";
print $row["sdate"];
}
else {
print "; ";
}
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
  <ul>
    <li><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_372; ?></font></li>
    <li><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_373; ?></font></li>
  </ul>
  </form>
<div align="center"> 
  <p>&nbsp;</p>
</div>
