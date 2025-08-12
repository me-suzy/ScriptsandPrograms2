<?php
/*
BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com
*/

//######################################
if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

function getPostDetails($id) {
if(!$id) { return; }
$arr=getSpost($id);
return $arr;
}

?>