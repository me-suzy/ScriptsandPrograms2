<?
include("header.inc.php");

?>
<?
include("../templates/admin-header.txt");
?>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="1" width="99%" align="center">
<TR>
  <TD><center><b>ID</b></TD>
  <TD><center><b>Name</b></TD>
  <TD><center><b>E-mail</b></TD>
  <TD><center><b>URL</b></TD>
  <TD><center><b>Websites visited</b></TD>
  <TD><center><b>Visits got</b></TD>
</TR>

<? $result = mysql_query("SELECT name, id, email, url, points, views, hits, savepoints FROM `demo_a_accounts` prev ORDER by id");
   while ($myrow = mysql_fetch_row($result)){
 echo"
   <TR>
  <TD><center><b> $myrow[1] </b></TD>
  <TD><center><b> $myrow[0] </b></TD>
  <TD><center><b><A href=mailto:$myrow[2]>$myrow[2]</b></A></TD>
  <TD><center><b><a href=./frame.php?url=$myrow[3] target=_blank>Website</b></a></TD>
  <TD><center><b> $myrow[5] </b></TD>
  <TD><center><b> $myrow[6] </b></TD>
</TR>";
$direkt = mysql_num_rows(mysql_query("SELECT id FROM `demo_a_accounts` WHERE `refererid` = '$myrow[1]'"));
if ($myrow[7] == 1) {$sparen = "ja";} else {$sparen = "nein";};
   $resultb = mysql_query("SELECT name, id, email, url, points, views, hits FROM `demo_a_accounts`  WHERE id='$myrow[1]'");
   while ($myrowb = mysql_fetch_row($resultb)) {
 echo"
 <TR>
  <TD><center><b>$myrow[1]</b></TD>
  <TD><center><b>&nbsp;</b></TD>
  <TD><center><b>Refs: $direkt </b></TD>
  <TD><center><b>&nbsp;</b></TD>
  <TD><center><b>Points: $myrow[4]</b></TD>
  <TD><center><b>save : $sparen</b></TD>
</TR>
 <TR>
  <TD><center><b><HR noshade color=red size=3></b></TD>
  <TD><center><b><HR noshade color=red size=3></b></TD>
  <TD><center><b><HR noshade color=red size=3></b></TD>
  <TD><center><b><HR noshade color=red size=3></b></TD>
  <TD><center><b><HR noshade color=red size=3></b></TD>
  <TD><center><b><HR noshade color=red size=3></b></TD>";
};
};
?>
</table>
<?
include("../templates/admin-footer.txt");
?>