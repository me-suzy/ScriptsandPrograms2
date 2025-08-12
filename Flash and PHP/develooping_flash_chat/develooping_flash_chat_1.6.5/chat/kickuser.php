<?php 
/*	kicking management for develooping flash chat       */
/*	version 1.6.5 Created by Juan Carlos Pos	            */
/*	juancarlos@develooping.com	                        */

require ('required/config.php');
$users_file = "required/users.txt";

if (($name==$admin_name) and ($password==$admin_password)){

$lines = file($users_file);
$a = count($lines);

//kick the user
//-------------

$text_string = join ('', file ($users_file));
$new_list= ereg_replace ("$username\n $user_password", "$username\n kicked", $text_string);
$fpku = fopen($users_file, "w");
$fwku = fwrite($fpku, $new_list);
fclose($fpku);


echo "<script>";
echo "location.replace('adminusers.php?name=$name&password=$password')";
echo "</script>";

}

?>