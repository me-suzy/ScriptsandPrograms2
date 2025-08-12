<? 
//Script created by Razore.co.uk under the GNU GPL
//Contact us at http://www.razore.co.uk/pages/contact-us.php

//Database variables
$server         	= "localhost";  		// Your MySQL Server address. This is usually localhost                 
$db_user        	= "username"; 		// Your MySQL Username                                        
$db_pass        	= "password";		// Your MySQL Password                                        
$database       	= "database";	      // Database Name                                              

//Customizations 
$timeoutseconds 	= "300";			// How long it it boefore the user is no longer online
$showlink         = "1";                  // Link to us? 1 = Yes  0 = No

//Only one person is online
$oneperson1       = "There is curently";  //Change the text that will be displayed
$oneperson2       = "Person online.";     //Change the text that will be displayed

//Two or more people online
$twopeople1       ="There are currently"; //Change the text that will be displayed
$twopeople2       ="people online.";      //Change the text that will be displayed



                                                                                                     

//The following should only be modified if you know what you are doing
$timestamp=time();                                                                                            
$timeout=$timestamp-$timeoutseconds;  
mysql_connect($server, $db_user, $db_pass) or die ("online Database CONNECT Error");                                                                   
mysql_db_query($database, "INSERT INTO online VALUES ('$timestamp','$REMOTE_ADDR','$PHP_SELF')") or die("online Database INSERT Error"); 
mysql_db_query($database, "DELETE FROM online WHERE timestamp<$timeout") or die("online Database DELETE Error");
$result=mysql_db_query($database, "SELECT DISTINCT ip FROM online WHERE file='$PHP_SELF'") or die("online Database SELECT Error");
$user  =mysql_num_rows($result);                                                                              
mysql_close();                                                                                                
if ($user==1) {echo"<font size=1>$oneperson1 $user $oneperson2</font>";} else {echo"<font size=1>$twopeople1 $user $twopeople2";}

//If you have chosen to support us.
switch ($showlink) {
case 0:
   echo "";
   break;
case 1:
   echo "<br><br><font size=\"1\">PHP How many Online by
<a target=\"_blank\" href=\"http://www.razore.co.uk\">Razore</a></font>";
   break;
}
?>