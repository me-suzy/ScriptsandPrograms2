<?php
require("config.php");
echo "Before you can start using loginphp, you will need to setup the database...<a href=setup.php?action=create>Set it up</a>";
if($_GET['action'] == 'create')
{
// Create a MySQL table in the selected database
mysql_query("CREATE TABLE loginphp(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
Uname VARCHAR(30), 
Email VARCHAR(30),
Fname VARCHAR(30),
Lname VARCHAR(30),
Pword VARCHAR(30))")
or die(mysql_error());

mysql_query("INSERT INTO loginphp
(Uname, Email, Pword) VALUES('$ADMINUNAME', '$ADMINEMAIL', '$ADMINPWORD') ")
or die(mysql_error());

echo "<br>Setup was successfull .. <a href=login.php>Go to login</a>";
echo "<br><br>If you have any questions, you can email me at <a href=mailto:artem9@gmail.com>artem9@gmail.com</a>";
}
?>
