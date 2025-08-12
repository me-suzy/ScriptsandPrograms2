<?php
/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

//######################################
if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

function getPostDetails($id) {
global $c_dir;
if(strpos("--$id","[]")) { errd("Restricted!", "The article which you were trying to access has been<br>frozen by the Administrator."); }

$fpr=@fopen("$c_dir/$id","r") or die(header("Location: index.php")); $fp=@fgets($fpr); fclose($fpr);


list($title,$date,$file,$frmt, $a_name, $a_email, $a_site, $keyw, $msg, $smr) = explode("||", $fp);
$ar=array();
$ar[title]=$title;
$ar[a_name]=$a_name;
$ar[a_email]=$a_email;
$ar[a_url]=$a_site;

return $ar;
}
?>