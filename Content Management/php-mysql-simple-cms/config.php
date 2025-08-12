<?php 
//MODIFY THIS.

$host = "localhost";       //mostly this is localhost if mysql server is running on the same machine as script.
$user = "root";        //database username here
$password = "password";   //database password here 
$database = "phpcms1"; //the name of the database where you want the script installed in. 

//this are the logins for the admin part. Change them for security. 
$login = "test";  //your login for the admin section.
$pass = "1234";   //your login for the admin section.

//set the page color here.
$backcolor = "steelblue";

//NO MODIFIYNG BELOW THIS

function database_connect(){
    global $host, $database, $user, $password;

    $link = @mysql_connect("$host","$user","$password"); 
	$sql_error = mysql_error();

    if (!$link) { 
        echo "Connection with the database couldn't be made.<br>";
		echo "$sql_error"; 
        exit;
    }
  
   if (!@mysql_select_db("$database")) {; 
        echo "The database couldn't be selected.";
        exit;
    }
   return $link;
}
?>