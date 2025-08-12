<?php
// functions for URL encoding
function d1($code){
	$code = str_replace("_", " ", $code);
	return $code;
}

function d2($code){
	$code = str_replace(" ", "_", $code);
	$code = str_replace("/", "%252F", $code);
	$code = str_replace("&", "%2526", $code);
	return $code;
}
?>