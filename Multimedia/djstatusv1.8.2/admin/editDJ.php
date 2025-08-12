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
<p><strong>DJ Management > Edit DJ</strong>
</p>
<form action="editDJ2.php" method="post" name="editDJ" id="editDJ">
  <select name="edj" id="edj">
    <option>--Select a DJ to edit--</option>
<?php

$query="SELECT * FROM currentdj ORDER BY `dj` ASC";
$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$edj = "$row[dj]";
		$ename = "$row[name]";
    	echo "<option value=\"$edj\">$ename</option>";
    }
?>
  </select>
  <input type="hidden" name="pass" value="<?php echo "$pass"; ?>">
  <input type="submit" name="Submit" value="Submit">
</form>	
<br><br><a href="main.php?pass=<?php echo "$pass"; ?>">Main</a>
<?php
/*	$query="SELECT * FROM currentdj ORDER BY `dj` ASC";
$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$edj = "$row[dj]";
		$ename = "$row[name]";
		$eaddress = "$row[address]";
		$eaim = "$row[aim]";
		$emsn = "$row[msn]";
		$eyim = "$row[yim]";
		$eicq = "$row[icq]";
		$eals1 = "$row[alias1]";
		$eals2 = "$row[alias2]";
		$eals3 = "$row[alias3]";
                $title = stripslashes($title2);
    	echo "<option value=\"$idhr\">$title</option>";
    } */
	
/////////////////////	
	
	}
include ("footer.inc");
?>