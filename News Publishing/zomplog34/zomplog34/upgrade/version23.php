<link rel="stylesheet" href="../admin/style.css" type="text/css" />
<?php
include ("../admin/functions.php");
include ("../admin/config.php");

	 
	 
	 
// first, let's update an existing table
$query2 = "ALTER TABLE $table ADD fullwidth int (10) NOT NULL , ADD mediafile varchar (100)  NOT NULL , ADD mediatype int (10) NOT NULL, ADD imagewidth text NOT NULL, ADD imageheight text NOT NULL";

echo "<br>updating table <b>$table</b>... ";
$q2 = mysql_query($query2) or die (mysql_error());
echo "<div class='good'>succeeded</div>";


$query3 = "ALTER TABLE $table_users CHANGE permissions admin INT(10) DEFAULT '0' NOT NULL";
$query4 = "ALTER TABLE $table_users ADD name VARCHAR(50) NOT NULL, ADD email VARCHAR(50) NOT NULL, ADD about TEXT NOT NULL";

echo "<br>updating table <b>$table_users</b>... ";
$q3 = mysql_query($query3) or die (mysql_error());
$q4 = mysql_query($query4) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

// now we're going to add some tables  
  $query5 = "CREATE TABLE $table_settings (
  name text NOT NULL,
  weblog_title text NOT NULL,
  image int(11) NOT NULL default 0,
  comments int(11) NOT NULL default 0,
  categories int(11) NOT NULL default 0,
  pages int(11) NOT NULL default 0,
  max int(11) NOT NULL default 0,
  scroll int(15) NOT NULL default 0,
  date text NOT NULL,
  skin text NOT NULL,
  language text NOT NULL,
  use_upload int(15) NOT NULL default 0,
  max_upload int(50) NOT NULL default 0,
  
  use_mediafile int(50) NOT NULL default 0,
  admin_welcome text NOT NULL,
  site_welcome text NOT NULL,
  rss text NOT NULL,
  use_join int(50) NOT NULL default 0,
  PRIMARY KEY  (image)
)";

$query6 = "INSERT INTO $table_settings (name, image, comments, categories, max, scroll, date, skin, language, use_upload, max_upload, use_mediafile, admin_welcome, rss) VALUES ('default', '1', '1', '1', '5', '5', 'M-d-y', 'default', 'english', '1', '30000', '1', 'Welcome to the Zomplog Dashboard!', 'http://zomplog.zomp.nl/xml.php')";

echo "<br>creating table <b>$table_settings</b>... ";
$q5 = mysql_query($query5) or die (mysql_error()); 
$q6 = mysql_query($query6) or die (mysql_error()); 
echo "<div class='good'>succeeded</div>";

$query7 = "CREATE TABLE $table_moblog (
  id int(11) NOT NULL auto_increment,
  email varchar(50) NOT NULL default 0,
  server varchar(150) NOT NULL default 0,
  user varchar(50) NOT NULL default 0,
  password varchar(50) NOT NULL default 0,
  shared text NOT NULL,
  use_moblog int(15) NOT NULL default 0,
  PRIMARY KEY  (id)
)";

$query8 = "INSERT INTO $table_moblog (id, email, server, user, password, shared, use_moblog) VALUES ('1', 'name@email.com', 'localhost', 'name', 'password', 'FALSE', '0')";

echo "<br>creating table <b>$table_moblog</b>... ";
$q7 = mysql_query($query7) or die (mysql_error());
$q8 = mysql_query($query8) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

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


// -------------------- script below updates from version 2.3 to the current one ------------------------------- // 


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
		mail("zomplog@zomp.nl", "Zomplog upgrade version 2.3 to 3.4 $name", "$body", "From: zomplog@zomp.nl");
		
echo "<br /> Zomplog has been succesfully upgraded!";

?>