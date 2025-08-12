<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/SYSTEM/AUTHORIZATION.PHP > 03-11-2005

$checkauth = 0;
$userdata = "";
$user = "";
$pass = "";

$user = read_cookie("mobsuser");
$pass = read_cookie("mobspass");
if (!$user || !$pass) {
	$checkauth = 0;
} else {
	loaduser($user);
	if ($userdata['username']) {
		if ($pass == $userdata['password']) $checkauth = 1;
		if ($pass != $userdata['password']) $checkauth = 0;
	}
}
?>