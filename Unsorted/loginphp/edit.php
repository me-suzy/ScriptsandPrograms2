<?php
ob_start();
//include the header
require("top.php");
if($_SESSION['Uname'] != $ADMINUNAME || $_SESSION['lp'] == '')
{
header("Location: login.php");
exit;
}
echo "<center><br><a href=admincp.php>go back to the admin control panel</a><br><br>";
echo "<form method=post action=edit.php?action=edit><textarea cols=60 rows=20 name=edit>";
$file = "main.php";
$fh = fopen($file, 'r');
$theData = fread($fh, filesize($file));
echo $theData;
fclose($fh);
echo "</textarea><input type=submit value=Edit></form>";
if($_GET['action'] == 'edit')
{
$fh = fopen($file, 'w') or die("can't open file");

$stringData = $_POST['edit'];
fwrite($fh,stripslashes($stringData));
fclose($fh);
header("Location: edit.php");
}
echo "</center>";
?>
