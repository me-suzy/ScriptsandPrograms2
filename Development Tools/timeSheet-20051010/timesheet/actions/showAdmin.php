<?php

if ($_SESSION[id] == 1)
	$_SESSION['showAdmin'] = !$_SESSION['showAdmin'];

header("Location: index.php");

?>
