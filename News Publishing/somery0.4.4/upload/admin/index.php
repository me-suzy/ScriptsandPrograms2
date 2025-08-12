<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/INDEX.PHP > 03-11-2005

$start = TRUE; 
include("system/include.php"); 
if ($checkauth) { 

	echo "<strong>Welcome!</strong><br />Welcome to the Somery admin panel. If you want more information about Somery or get support on it, visit <a href='http://somery.danwa.net'>the somery site</a> and visit the forum.<br/><br /><strong>Checking for new versions</strong><br />";

	$fd=fopen("http://somery.danwa.net/ver.php", "r");
	while (!feof($fd)) {
		$remotever=fgets($fd, 128);
	}
	fclose ($fd);

	if ($localver == $remotever) {
		echo "This Somery $localver install is up to date.";
	} elseif ($localver < $remotever) {
		echo "<strong>This Somery $localver install is outdated! Somery $remotever can be downloaded from <a href='http://somery.danwa.net'>the somery site</a></strong>";
	} else {
		echo "This Somery $localver install seems to be newer than the currently available version ($remotever).";
	}

 }; $start = FALSE; include("system/include.php"); ?>