<?php

/*
print "<pre>";
print_r($_SESSION);
print_r($_REQUEST);
print "</pre>";
*/

//..... For some funky PHP reason ... I have to do this
$user_id = $_SESSION['id'];

foreach($_REQUEST['client'] as $client_id=>$value)
{
	$client_id=intval($client_id);

	$Q="UPDATE clients SET clientDesc='".addslashes($value)."' WHERE id='$client_id' AND user_id='$user_id'";
	mysql_query($Q);
}

header("Location: index.php?page=index");

?>
