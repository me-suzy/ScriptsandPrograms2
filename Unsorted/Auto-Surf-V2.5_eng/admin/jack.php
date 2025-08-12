<?
include("header.inc.php");

$result5 = mysql_query("SELECT gampoints, gamchance, gampointszu FROM `demo_a_gambleadmin`");
$myrow5 = mysql_fetch_row($result5);

$punkt = $myrow5[0];
$chance = $myrow5[1];
$punktzu = $myrow5[2];

?>
<?
include("../templates/admin-header.txt");
?>
<form method="post" action="jack2.php">
<TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="95%" align="center">
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Jackpot starts at:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="punktneu" value="<? echo "$punkt"; ?>"></TD>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Jackpot appears after:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="chanceneu" value="<? echo "$chance"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">If a user doesn't notice jackpot it is added:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="punktzuneu" value="<? echo "$punktzu"; ?>"></TD>
</TR>
</TABLE><br><br>
<center><input type="submit" value="Update"></form></center>
<?
include("../templates/admin-footer.txt");
?>