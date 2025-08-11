<?php
$recent=date("U")-900;
$getusersonline="SELECT userID,username from b_users where lasttime>'$recent'"; //grab from sql users on in last 15 minutes
$getusersonline2=mysql_query($getusersonline) or die("Could not get users");
$num=mysql_num_rows($getusersonline2);
$countguests="SELECT DISTINCT guestip from guestsonline where time>'$recent'";
$countguests2=mysql_query($countguests) or die("Could not count guests");
$thecount=mysql_num_rows($countguests2);
print "<table class='maintable' cellspacing='1'>";
print "<tr class='headline'><td colspan='2'><b>There have been $num members and $thecount guests online in the last 15 minutes</td></tr>";
print "<tr class='forumrow'><td>";
while($getusersonline3=mysql_fetch_array($getusersonline2))
{
  print "<A href='profile.php?userID=$getusersonline3[userID]'>$getusersonline3[username]</a>,";
}
print "</td></tr></table><br><br>";
?>