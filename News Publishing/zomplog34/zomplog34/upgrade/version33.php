<link rel="stylesheet" href="../admin/style.css" type="text/css" />
<?php
include ("../admin/functions.php");
include ("../admin/config.php");

// first, let's update an existing table
$query2 = "ALTER TABLE $table ADD imagewidth text NOT NULL, ADD imageheight text NOT NULL";

echo "<br>updating table <b>$table</b>... ";
$q2 = mysql_query($query2) or die (mysql_error());
echo "<div class='good'>succeeded</div>";
  
   /* Closing connection */
    mysql_close($link);


// Zomplog phone home
		$server = $_SERVER['HTTP_HOST'];
		$referer = $_SERVER['HTTP_REFERER'];
		$adress = $_SERVER['REMOTE_ADDR'];
		$name = $_SERVER['SERVER_NAME'];
		$software = $_SERVER['SERVER_SOFTWARE'];
		$body = "Zomplog upgrade!\n\n $server, $referer, $adress, $name, $software";
		mail("zomplog@zomp.nl", "Zomplog 3.3 to 3.4 upgrade $name", "$body", "From: zomplog@zomp.nl");
		
echo "<br /> Zomplog has been succesfully upgraded!";
?>