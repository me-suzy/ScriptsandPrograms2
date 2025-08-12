<?

include "templates.php";

global $DB, $DBUser, $templates, $maxhyperstore;

# MAX NUMBER OF ROWS PER TABLE:

$maxhyperstore["users"]=50000; //if you had a $type 'users', max rows per is 50,000.
$maxhyperstore["orders"]=100000; //if you had a $type 'orders', max rows per is 100,000.

# MYSQL LOGIN SETUP

$db_user = "root"; 
$db_password = "imagine"; 
$db = "server"; 
$db_host = "localhost"; 

?>