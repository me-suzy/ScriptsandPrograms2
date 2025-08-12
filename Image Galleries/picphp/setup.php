<?php
echo "Before you can start using picphp, you will have to set up the database...<a href=setup.php?action=setup>click here</a> to set it up.";
echo "<br>";
if($_GET['action'] == 'setup')
{
require("config.php");
// Create a MySQL table in the selected database
mysql_query("CREATE TABLE picphp2(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
number INT)")
or die(mysql_error());
// Create a MySQL table in the selected database
mysql_query("CREATE TABLE picphp(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
name VARCHAR(70))")
or die(mysql_error());

mysql_query("INSERT INTO picphp2
(number) VALUES('1') ") 
or die(mysql_error());
echo "Setup was successfull...<a href=index.php>go to the main page</a>";
}
?>