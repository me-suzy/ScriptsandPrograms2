<?php 
/*	banning management for develooping flash chat       */
/*	version 1.6.5 Created by Juan Carlos Pos	            */
/*	juancarlos@develooping.com	                        */

require ('required/config.php');
$users_file = "required/users.txt";
$banned_file = "required/banned_ip.txt";

if (($name==$admin_name) and ($password==$admin_password)){

$lines = file($users_file);
$a = count($lines);

//inhabilitation for ip
//---------------------

$text_string = join ('', file ($users_file));
$new_list= ereg_replace ("$username\n $user_password", "$username\n banned", $text_string);
$fpbu = fopen($users_file, "w");
$fwbu = fwrite($fpbu, $new_list);
fclose($fpbu);

//add ip to banned_ip.txt
//-----------------------

$fpbu1 = fopen($banned_file, "a");
$user_password= trim ($user_password);
$fwbu1 = fwrite($fpbu1, "$user_password\n");
fclose($fpbu1);



echo "<script>";
echo "location.replace('adminusers.php?name=$name&password=$password')";
echo "</script>";

}

?>