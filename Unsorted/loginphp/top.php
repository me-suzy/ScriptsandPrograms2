<?php
session_start();
//include the config.php file
require("config.php");
echo "<br><br><br><center><font size=2>";
//show the menu
//check if the user is logged in
if($_SESSION['Uname'] == '' || $_SESSION['lp'] == '')
{
echo "<a href=login.php><img src=images/login.gif border=0>&nbsp;Login</a>";
}
else
{
echo "<a href=logout.php><img src=images/logout.gif border=0>&nbsp;Logout</a>";
}
if($_SESSION['Uname'] == '' || $_SESSION['lp'] == '')
{
echo " | <a href=register.php><img src=images/register.gif border=0>&nbsp;Register</a>";
}
echo " | <a href=members.php><img src=images/members.gif border=0>&nbsp;Members</a> | <a href=help.php><img src=images/help.gif border=0>&nbsp;Help</a>";
if($_SESSION['Uname'] != '')
{
echo " |<a href=main.php> <img src=images/main.gif border=0>&nbsp;Main</a>";
}
if($_SESSION['Uname'] == $ADMINUNAME)
{
echo "<br><a href=admincp.php> <img src=images/admincp.gif border=0>&nbsp;Admin CP</a>";
}
echo "<br><br></font></center>";
?>
