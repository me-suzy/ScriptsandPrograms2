<?php
	include('configure.php');

$query = "SELECT * FROM $table WHERE id>0 ORDER BY RAND() LIMIT $numlinks";

$results = mysql_query($query) or die (mysql_error());

while ($rows = mysql_fetch_array($results))
	{
	
			extract($rows);
		
		if($imagelinks == yes)

			{
			print "$before<a href='$url' target='_blank' title='$name'><img src='$imagefolder$img' alt='$name' border='0'></a>$after";
			}
		
		else
		
			{
			print "$before<a href='$url' target='_blank' title='$name'>$name</a>$after";
			}
}

print "$before<a href='$showallurl'><b>more?</b></a>$after";

?>