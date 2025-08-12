<html>
<head>
<title>DreamHost Installer Step 1</title>
</head>
<body>
<?
/* This software is developed & licensed by Dreamcost.com.
Unauthorized distribution, sales, or use of any of the code, in part or in whole, is
strictly prohibited and will be prosecuted to the full extent of the law. */

require("setup.php");
define("DB_HOST", "$host");
define("DB_NAME", "$database");
define("DB_USER", "$user");
define("DB_PWD", "$pass");
require("db.conf");


$connection = mysql_connect("$host","$user","$pass") or die("Couldn't make connection.");
$sql = "CREATE DATABASE $database";
$sql_result = mysql_query($sql,$connection) or die("Sorry, DreamHost Installer Could Not Create A MySQL DATABASE NAMED $database. <BR> Please Double check the information in your setup.php file against your actual MySQL INFO.");


 ?>
<p><font face="Verdana, Arial, Helvetica, sans-serif"><b>The database for DreamHost 
  has been installed.</b></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif">Please close this window 
  and proceed to Installation Step 2.</font></p>
</body>
</html>