<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/SYSTEM/INCLUDE.PHP > 03-11-2005

if ($start) {
	include("../config.php");
	include("cookies.php");
	include("system/error.php");
	include("system/functions.php");
	include("system/authorization.php");
	include("$skindir/header.php");
} else {
	if (!$checkauth) {
		$login = TRUE;
		include("login.php");
	}
	include("$skindir/footer.php");
}
?>