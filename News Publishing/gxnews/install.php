<?
include("config.php");
include("connect.php");
// Make sure you have edit 'config.php' before running this file or it will not work!

if ($submit == "") {
echo "Please make sure you have edited the config file before pressing 'Submit', or the install will fail." ?>
<form method="post" action="install.php" name="check">
<input type="submit" name="submit" value="Submit">
</form>
<? }
if ($submit == "Submit") {
// Create a MySQL table in the selected database
mysql_query("CREATE TABLE news(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
title TEXT, 
date TEXT,
body LONGTEXT)")
or die(mysql_error());
echo "Table '<b>News</b>', created successfully! You should now delete this file immediately!";
}
?>
