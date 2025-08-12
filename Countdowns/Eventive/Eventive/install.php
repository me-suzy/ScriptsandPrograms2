<?

//---------------------------------------------------------
// EVENTIVE
// Version v0.1
//
// Written by Andrew Whitehead
// (c) Andrew Whitehead 2004
//
// THIS TAG MUST NOT BE REMOVED
//---------------------------------------------------------

require('config.php');

//Connect and select db
mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname) or die("Unable to select database");


$query = "CREATE TABLE events (
id int(6) NOT NULL auto_increment,
PRIMARY KEY (id),
event varchar(40),
date varchar(255)
)";
mysql_query($query) or die(mysql_error());

if(mysql_error() == NULL)
{
     echo "<h3>Installation complete!</h3>";
     echo "In the interest of safety please delete this file.";
}

mysql_close();

?>
