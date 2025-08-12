<?php

set_time_limit (0);

session_start();

include_once ("admin/config.php");
include_once ("admin/settings.inc.php");
include_once ("includes/settings.inc.php");
include_once ("includes/template.inc.php");
include_once ("includes/shoppingcart.php");
include_once ("includes/functions.php");

// Check if enabled
	
foreach ( $_languages as $key=>$val ) {
	
	$enabled = 0;
	$sql_lng = "select enabled from `".$prefix."store_language_charsets` WHERE language='$val' limit 1";
	if ( $tbl_lng = mysql_query ($sql_lng) )
		if ( $res_lng = mysql_fetch_assoc ($tbl_lng) )
			$enabled = $res_lng['enabled'];
	
	if ( !$enabled )
		unset ( $_languages[$key] );
	
}

if ( sizeof ( $_languages ) == 1 ) {
	
	foreach ( $_languages as $l )
		$_SESSION['selected_lng'] = $sys_lng = $l;
}


if ( empty ( $_languages ) ) {
	
	$_languages['EN'] = 'EN';
	$_SESSION['selected_lng'] = $sys_lng = 'EN';
}

?>