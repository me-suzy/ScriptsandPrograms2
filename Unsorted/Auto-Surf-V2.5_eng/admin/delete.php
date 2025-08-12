<?php

if($id)
{
	require('../prepend.inc.php');
	account_delete($id);
}
	

header("Location: ./new.php");

?>