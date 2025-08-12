<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("../config.php");	
include ("header.inc");
if (!empty($_GET['pass'])) {
	$pass = $_GET['pass'];
} else {
	$pass = $_POST['pass'];
}
if ($pass != $adminpass) {
echo "<strong>Incorrect password</strong>";
} else {

$filename = "install/index.php";

if (file_exists($filename))  {
echo "<strong>Please delete the 'install' folder, or if you haven't installed the script yet, please do that <a href=\"install\">now</a>. Thank you.</strong>";
} else {
?>	
	<p><strong>DJ Management</strong></p>
    <p><a href="addDJ.php?pass=<?php echo "$pass" ?>">Add</a><br>
      <a href="editDJ.php?pass=<?php echo "$pass" ?>">Edit</a><br>
    <a href="delDJ.php?pass=<?php echo "$pass" ?>">Delete</a></p><br>
	<p><strong>Settings</strong></p>
	<p><a href="editSettings.php?pass=<?php echo "$pass" ?>">Edit</a></p>
	<br><a href="index.php">Logout</a>
<?php
}
}
include ("footer.inc");
 ?>