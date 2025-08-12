<?php
include('configure.php'); 

?>

<html>
<head>
<title>SFLinks delete a link</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<?php

print "<h1>Links Currently in the Database</h1>";

print "<p>(Note, this may not be up-to-date if you just added or deleted a link.  <a href=\"javascript:window.location.reload()\">REFRESH IT</a>.)</p>";

print "<table border='1' cellpadding='2' cellspacing='2'>";
print "<tr><th>Link ID</th><th>Link Name</th><th>Link Url</th><th>Owner's Email</th><th>Image</th><th>Image Name</th></tr>";

$query = "SELECT * FROM $table WHERE id>0 ORDER BY id";

$results = mysql_query($query) or die (mysql_error());

while ($rows = mysql_fetch_array($results))
	{
		extract($rows);
		
			if($imagelinks == yes)

			{
			print "<tr><td>$id</td><td>$name</td></td><td><a href='$url' target='_blank'>$url</a></td><td>$email</td><td><img src='$imagefolder$img'></td><td>$img</td></tr>";
			}
		
		else
		
			{
			print "<tr><td>$id</td><td>$name</td></td><td><a href='$url' target='_blank'>$url</a></td><td>$email</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			}

}

print "<tr><th>Link ID</th><th>Link Name</th><th>Link Url</th><th>Owner's Email</th><th>Image</th><th>Image Name</th></tr></table>";

?>