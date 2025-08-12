<?php //main module for random link script
include "admin/connect.php";
include "admin/var.php";
$day=date("U");
$seedval=$day%100000;
srand($seedval);
$getlinks="SELECT * from rl_links where validated='1' order by RAND() limit $numlinks ";
$getlinks2=mysql_query($getlinks) or die("Could not get links");
$s=1;
print "<table border='0' bordercolor='white' cellspacing='1' cellpadding='0'>";
print "<tr bgcolor=$titlecolor><td colspan='2'>$numlinks Random Links</td></tr>";
print "<tr bgcolor=$barcolor><td>#</td><td>Site title</td></tr>";
while ($getlinks3=mysql_fetch_array($getlinks2))
{
   print "<tr bgcolor=$rows><td>$s</td><td><A href='re.php?ID=$getlinks3[ID]' target='_blank'>$getlinks3[Title]</a></td></tr>";
   $s++;
}
if($submitlink='Yes')
{
  print "<tr bgcolor=$barcolor><td colspan='2'><center><A href='submit.php'><font color='white'>Submit link</font></a></center></td></tr>";
}
print "<tr bgcolor=$titlecolor><td colspan='2'><center><font size='1'>Powered by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Scripts</a></center></font></td></tr>";
print "</table>";



?>