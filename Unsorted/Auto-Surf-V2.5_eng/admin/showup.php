<?php

if($id)
{
	require('../prepend.inc.php');
	showup($id);
}

header("Location: ./new.php");

?>