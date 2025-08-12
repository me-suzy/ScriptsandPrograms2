<?
include "./config.php";

if ($pass!=$adminpass){
	echo "<center><form name='form1' method='get'>Enter Administrator's Password: <input type='text' name='pass'><input type=submit value='Submit'><br></form></center>";
	exit;
}

if ($btnadd){
	$sql = "insert into $adstable values('', '$html')";
	$result = mysql_query($sql) or die("Failed: $sql");
}

if ($btndelete && $id){
	$sql = "delete from $adstable where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
}

if ($btnupdate && $id){
	$sql = "update $adstable set html='$html' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
}

$sql = "select * from $adstable";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$html = $resrow[1];
	$editbanners .= "<form name='' method='post' action='adminbanners.php'><input type='text' name='html' size='60' value='$html'><input type='hidden' name='pass' value='$pass'><input type='hidden' name='id' value='$id'><input type='submit' value='Update' name='btnupdate'><input type='submit' value='Delete' name='btndelete'></form>";
}
if (!$editbanners) $editbanners = "There are zero banners in the database. You need to add at least one before you use GuestBookHost!";

print $editbanners;
print "<form name='' method='post' action='adminbanners.php'><b>Add New Banner</b> You must enter the full HTML code to be displayed for this banner. This can be an image banner, text links, or both. <br><input type='text' name='html' size='60'><input type='hidden' name='pass' value='$pass'><input type='submit' value='Add' name='btnadd'></form>";

print "<center>[<a href='admin.php?pass=$pass'>Back to Users List</a>]</center>"; 

?>