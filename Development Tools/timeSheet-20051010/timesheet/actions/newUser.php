<?php

$Q="INSERT INTO users 
	SET email='".addslashes($_REQUEST['email'])."',
	password='".addslashes($_REQUEST['password'])."',
	firstname='".addslashes($_REQUEST['firstname'])."',
	lastname='".addslashes($_REQUEST['lastname'])."',
	dateAdded=NOW()";

mysql_query($Q);

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF])."?msg=".base64_encode('white|green|User Added'));
}
else header("Location: $_SERVER[HTTP_REFERER]?msg=".base64_encode('white|green|User Added'));

?>
