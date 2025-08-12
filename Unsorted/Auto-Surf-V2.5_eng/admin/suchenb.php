<?php
include("header.inc.php");
global $email;
?>
<?
include("../templates/admin-header.txt");
?>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="1" width="99%" align="center" cellspacing="2" cellpadding="2">
<TR>
  <TD><center><b>&nbsp;</b></TD>
  <TD><center><b>ID</b></TD>
  <TD><center><b>Name</b></TD>
  <TD><center><b>E-Mail</b></TD>
  <TD><center><b>URL</b></TD>
  <TD><center><b>Points</b></TD>
  <TD><center><b>Websites visited</b></TD>
  <TD><center><b>Visits got</b></TD>
</TR>

<?php
   $result = mysql_query("SELECT name, id, email, url, points, views, hits FROM `demo_a_accounts` WHERE email='$email'");
   while ($myrow = mysql_fetch_row($result)) {
 echo"
   <TR>
  <TD><center><b>Auto</b></TD>
  <TD><center><b> $myrow[1] </b></TD>
  <TD><center><b> $myrow[0] </b></TD>
  <TD><center><b><A href=mailto:$myrow[2]>$myrow[2]</b></A></TD>
  <TD><center><b><a href=./frame.php?url=$myrow[3] target=_blank>Website</b></a></TD>
  <TD><center><b> $myrow[4] </b></TD>
  <TD><center><b> $myrow[5] </b></TD>
  <TD><center><b> $myrow[6] </b></TD>
</TR>";
};
?>
</TABLE>