<?php
include '../format.css';



echo "<table class=\"black\" width=\"100%\"><tr><td><br><br><br></td></tr></table>";


echo "<table width=\"40%\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\" class=\"black\">";
echo "<tr>";
echo "<td align=\"center\"><img src=\"taskdriverinstall.jpg\"></td>";
echo "</tr>";
echo "<tr>";
echo "<td><br><br><b>Step 1:</b> Create your database. Name it anything you'd like.
<br><br> <b>Step 2:</b> Open config.php and modify the settings there.
<br><br> <b>Step 3:</b> Click on the Begin Installation button.
<br><br> <b>Step 4:</b> After install has completed go to your TaskDriver URL (yoursite.com/taskdriver)
<br><br> <b>Step 5:</b> Your administrative login is admin. Your password is admin.

<br><br><br> TaskDriver will be setup with a testuser account. This account does not have admin access. It is an account that will allow you to assign tasks to for testing. Feel free to delete this account at anytime.
</td>";
echo "</tr>";
echo "<tr><td><br><br><center><form name=\"form1\" method=\"post\" action=\"install.php\"><input type=\"submit\" value=\"Begin Installation\">";
echo "</form></center></td></tr>";
echo "</table>";
?>