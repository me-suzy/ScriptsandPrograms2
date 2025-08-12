<?php
include("config.php");
?>

<?php
mysql_query("CREATE TABLE pb (
id int(5) NOT NULL auto_increment,
url text NOT NULL,
button text NOT NULL,
ip text NOT NULL,
KEY id (id)
) TYPE=MyISAM AUTO_INCREMENT=44");

mysql_query("INSERT INTO pb VALUES (1, 'http://www.plug-world.net', 'http://www.plug-world.net/plug.png', '1.1.1.1')");

mysql_query("CREATE TABLE banned( 
ip TEXT NOT NULL 
)");

echo "Table Created! Please delete this file!";

?>



