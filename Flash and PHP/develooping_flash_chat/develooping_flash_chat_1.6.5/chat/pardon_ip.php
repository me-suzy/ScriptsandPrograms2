<?php 
/*	ip management for develooping flash chat            */
/*	version 1.6.5 Created by Juan Carlos Pos	            */
/*	juancarlos@develooping.com	                        */

require ('required/config.php');
$banned_file = "required/banned_ip.txt";

if (($name==$admin_name) and ($password==$admin_password)){

$lines = file($banned_file);
$a = count($lines);

//delete de banned ip
//-------------------

$text_string = join ('', file ($banned_file));
$new_list= str_replace ("$ip", "", $text_string);
$fpip = fopen($banned_file, "w");
$fwip = fwrite($fpip, $new_list);
fclose($fpip);

echo "<script>";
echo "location.replace('adminips.php?name=$name&password=$password')";
echo "</script>";

}

?>