<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}?>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">
<span class="heading">CHOOSE FROM THE OPTIONS</span><span class="help"><a href="stylehelp.htm" target="_blank">HELP</a></span>
<table width="80%"  border="0" align="center">
  
  <tr>
    <td class="text-design1"><a href="style.php?choice2=insertstyle">Insert</a></td>
  </tr>
  <tr>
    <td height="21" class="text-design1"><a href="style.php?choice2=settings">Change Stylesheet Settings</a></td>
  </tr>
 
</table>

<p class="headingcenter"><a href="options.php">Back to Options</a></p>

