<?
session_start();
if(!session_is_registered("client_id"))
{
header("Location: index.htm");
exit;
}
if ($client_name !== 'admin')
{
header("Location: index.htm");
exit;
}
?>
<html>
<head>
<title>My Invoice</title>
<link rel="stylesheet" href="inc/style.css" type="text/css">
</head>
<body>

  <p><img src="inc/title.gif" width="308" height="82"></p>
  <blockquote> 
  <h1>Clients</h1>
  
<?php 

include "inc/dbconnect.php";

echo "<table border=1 cellspacing=3 cellpadding=2 bordercolor=cccccc>";
echo "<tr><td><b>login name</b></td><td><b>email</b></td><td><b>title / name</b></td><td><b>invoice reference</b></td><td>&nbsp;</td><td>&nbsp;</td></tr>";
$result = mysql_query("SELECT * FROM clients",$db);

while ($row = mysql_fetch_array($result)) 
{
	
	$id = $row["clientid"]; 
	$name = $row["name"]; 
	$email = $row["email"];
	$title = $row["title"];
	$ref = $row["ref"];
	
	if ($alternate == "1") { 
	$color = "#ffffff"; 
	$alternate = "2"; 
	} 
	else { 
	$color = "#dedede"; 
	$alternate = "1"; 
	} 
	echo "<tr valign=top bgcolor=$color><td><b>$name</b></td><td>$email</td><td>$title</td><td>$ref</td>";
	echo "<td>[ <a href=editclient.php?id=$id>edit</a> ]</td><td>[ <a href=deleteclient.php?id=$id onClick=\"return confirm('Are you sure?')\">delete</a> ]</td></tr>"; 
} 
echo "</table>";

?>

  <p><a href="editclient.php">Add a new client</a></p>
</blockquote>
<?
include "inc/nav.inc";
include "inc/footer.inc";
?>
</body>
</html>