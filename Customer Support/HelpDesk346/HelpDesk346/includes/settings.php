<?php
	include_once "./classes/settings.php";
	if (!isset($_SESSION['obj']) || (time() - $_SESSION['storePoint']) > (60 * 5) ) {
		$_SESSION['obj'] = serialize(new Settings());	
		$_SESSION['storePoint'] = time();
	}
	
	$OBJ = unserialize($_SESSION['obj']);
?>