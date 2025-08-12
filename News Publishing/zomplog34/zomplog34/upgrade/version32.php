<link rel="stylesheet" href="../admin/style.css" type="text/css" />
<?php
include ("../admin/functions.php");
include ("../admin/config.php");

// first, let's update an existing table
$query1 = "ALTER TABLE $table ADD imagewidth text NOT NULL, ADD imageheight text NOT NULL";

echo "<br>updating table <b>$table</b>... ";
$q1 = mysql_query($query1) or die (mysql_error());
echo "<div class='good'>succeeded</div>";


$query2 = "ALTER TABLE $table_comments ADD ip varchar(25) NOT NULL";

echo "<br>updating table <b>$table_comments</b>... ";
$q2 = mysql_query($query2) or die (mysql_error());
echo "<div class='good'>succeeded</div>";




$query3 = "ALTER TABLE $table_settings ADD img_width text NOT NULL, ADD img_fullwidth text NOT NULL";
$query4 = "INSERT INTO $table_settings (img_width, img_fullwidth) VALUES ('150', '450')";
echo "<br>updating table <b>$table_settings</b>... ";

$q3 = mysql_query($query3) or die (mysql_error());
$q4 = mysql_query($query4) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

$query5 = "CREATE TABLE $table_banned (
     id int (10 )  NOT NULL auto_increment ,
     ip varchar (100 )  NOT NULL ,
     PRIMARY KEY  (id )
)";

echo "<br>creating table <b>$table_banned</b>... ";
$q5 = mysql_query($query5) or die (mysql_error());
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
		mail("zomplog@zomp.nl", "Zomplog 3.2 to 3.4 upgrade $name", "$body", "From: zomplog@zomp.nl");
		
echo "<br /> Zomplog has been succesfully upgraded!";
?>