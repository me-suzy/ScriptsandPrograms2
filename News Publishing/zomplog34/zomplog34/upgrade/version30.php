<link rel="stylesheet" href="../admin/style.css" type="text/css" />
<?php
include ("../admin/functions.php");
include ("../admin/config.php");

// first, let's update an existing table
$query2 = "ALTER TABLE $table ADD fullwidth int (10) NOT NULL , ADD mediafile varchar (100)  NOT NULL , ADD mediatype int (10) NOT NULL, ADD imagewidth text NOT NULL, ADD imageheight text NOT NULL";

echo "<br>updating table <b>$table</b>... ";
$q2 = mysql_query($query2) or die (mysql_error());
echo "<div class='good'>succeeded</div>";


$query4 = "ALTER TABLE $table_settings ADD weblog_title text NOT NULL, ADD pages int(11) NOT NULL default 0, ADD use_mediafile int(50) NOT NULL default 0,
ADD admin_welcome text NOT NULL, ADD site_welcome text NOT NULL, ADD rss text NOT NULL, ADD use_join int(50) NOT NULL default 0";


echo "<br>updating table <b>$table_settings</b>... ";
$q4 = mysql_query($query4) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

// now we're going to add some tables  

$query9 = "CREATE TABLE $table_pages (
     id int (10 )  NOT NULL auto_increment ,
     title varchar (100 )  NOT NULL ,
     text text NOT NULL ,
	 use_form int(15) NOT NULL default 0,
	 form_email varchar (100 )  NOT NULL ,
     PRIMARY KEY  (id )
)";

echo "<br>creating table <b>$table_pages</b>... ";
$q9 = mysql_query($query9) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

// -------------------- script below updates from version 3.0 to the current one ------------------------------- // 


// first, let's update an existing table
$query10 = "ALTER TABLE $table_comments ADD ip varchar(25) NOT NULL";

echo "<br>updating table <b>$table_comments</b>... ";
$q10 = mysql_query($query10) or die (mysql_error());
echo "<div class='good'>succeeded</div>";


$query11 = "ALTER TABLE $table_settings ADD img_width text NOT NULL, ADD img_fullwidth text NOT NULL";
$query12 = "INSERT INTO $table_settings (img_width, img_fullwidth) VALUES ('150', '450')";
echo "<br>updating table <b>$table_settings</b>... ";

$q11 = mysql_query($query11) or die (mysql_error());
$q12 = mysql_query($query12) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

$query13 = "CREATE TABLE $table_banned (
     id int (10 )  NOT NULL auto_increment ,
     ip varchar (100 )  NOT NULL ,
     PRIMARY KEY  (id )
)";

echo "<br>creating table <b>$table_banned</b>... ";
$q13 = mysql_query($query13) or die (mysql_error());
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
		mail("zomplog@zomp.nl", "Zomplog 3.0 to 3.4 upgrade $name", "$body", "From: zomplog@zomp.nl");
		
echo "<br /> Zomplog has been succesfully upgraded!";

?>