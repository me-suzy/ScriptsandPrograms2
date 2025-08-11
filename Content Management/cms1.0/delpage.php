<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}?>

<html>
<head>
<title>Delete Page</title>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">                                    
</head>
<body>
<?php
include ("connect.php");
  $result = mysql_query ("DELETE from pages where name ='$var' ") or die(mysql_error());
//  $result = mysql_query ("DELETE from links where page='$var'") or die(mysql_error());
   print "<p class='headingcenter'>succesfully deleted</p>";            
?>

<p class="headingcenter"><a href="options.php">Back to Options</a></p>
</body>
</html>


