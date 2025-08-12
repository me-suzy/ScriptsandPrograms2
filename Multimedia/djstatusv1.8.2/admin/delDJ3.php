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

/////////////////////

?>
<p><strong>DJ Management > Delete DJ</strong>
</p>
<?php
$query="SELECT * FROM currentdj WHERE dj = '$ddj'";
$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$ddj = "$row[dj]";
		$dname = "$row[name]";
    }
	
	$resultID = mysql_query("DELETE FROM currentdj WHERE dj = '$ddj'") or die(mysql_error());
	if ($resultID == TRUE) {
		print "DJ $dname been successfully deleted!";
	} else {
		print "Sorry, but DJ $dname was not deleted from the database. Please check your settings and try again.";
	}
?>
<br><br><a href="main.php?pass=<?php echo "$pass"; ?>">Main</a>
<?php
	
/////////////////////	
	
	}
include ("footer.inc");
?>