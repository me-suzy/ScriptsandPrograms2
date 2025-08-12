<?php

	header("Content-type: application/x-javascript"); 
	include('./include/connection.php');
	include('banner.php');
	new Banner($id,$con,$site);
?>

