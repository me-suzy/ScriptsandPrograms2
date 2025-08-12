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

$query="SELECT * FROM currentdj WHERE dj = '$ddj'";
$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$ddj = "$row[dj]";
		$dname = "$row[name]";
    }
?>
<p><strong>DJ Management > Delete DJ</strong>
</p><form name="delDJ2" action="delDJ3.php" method="post">
Are you sure you want to delete DJ <?php echo "$dname"; ?> from the database?<br>
<input type="hidden" name="ddj" value="<?php echo "$ddj"; ?>">
  <input type="hidden" name="pass" value="<?php echo "$pass"; ?>">
  <input type="submit" name="Submit" value="Yes">&nbsp;<a href="main.php?pass=<?php echo "$pass"; ?>">No</a>
</form>	
<br><br><a href="main.php?pass=<?php echo "$pass"; ?>">Main</a>
<?php
	
/////////////////////	
	
	}
include ("footer.inc");
?>