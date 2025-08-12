<?                                                                               

include "config.php";

$t_stamp = time();                                                                                            
$timeout = $t_stamp - $to_secs; 

mysql_connect($server, $db_user, $db_pass) or die ("Useronline Database CONNECT Error");                                                                   
mysql_db_query($db, "INSERT INTO CJ_UsersOnline VALUES ('$t_stamp','$REMOTE_ADDR','$PHP_SELF')") or die("Database INSERT Error"); 
mysql_db_query($db, "DELETE FROM CJ_UsersOnline WHERE timestamp<$timeout") or die("Database DELETE Error");
$result = mysql_db_query($db, "SELECT DISTINCT ip FROM CJ_UsersOnline WHERE file='$PHP_SELF'") or die("Database SELECT Error");
$user = mysql_num_rows($result);                                                                  
mysql_close();                                                                                                

if ($user == 1){
	echo "<b>$user</b> User Online";
} 
else{
	echo "<b>$user</b> Users Online";
}
?>