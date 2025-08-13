<?

$dbhost = "localhost";
$dbuser = "username";
$dbpass = "password";
$dbname = "dbname";
$time = time();
$today = date("m/d/Y");

 mysql_connect("$dbhost","$dbuser", "$dbpass") or die("** FATAL ERROR ** Could not connect to DB.");
 mysql_select_db("$dbname") or die("** FATAL ERROR ** Could not select DB.");
 $control=mysql_fetch_array(mysql_query("select * from control order by id desc limit 1"));

?>