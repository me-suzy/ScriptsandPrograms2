<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/COOKIES.PHP > 03-11-2005
//
// Credits: Plix Devil

function install_cookie ($name, $value) {
	destroy_cookie($name);
	setcookie("$name", $value,time()+24000, "$cookiesite", "", 0); 
}

function read_cookie ($name, $value="") {
	global $HTTP_COOKIE_VARS;
	if ($value) {
		if ($HTTP_COOKIE_VARS[$name]==$value) {
			return true;
		} else {
			return false;
		}
	} else {
		return $HTTP_COOKIE_VARS[$name];
	}
}

function destroy_cookie ($name) {
	$t=strftime("%A, %d-%b-%Y %H:%M:%S MST",time()-3600);
	setcookie($name,"",time()-2400,$cookiesite);
}


function expire () {
	$time = 10;
	return $time; // expires in 2 years
}
?>