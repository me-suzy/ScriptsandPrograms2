<?php

ini_set("max_execution_time",0);

$die_hdr = <<<DHD
<style>
<!--
body {font-size: 11px; font-weight: bold; font-family: Tahoma, Verdana, MS sans serif, Arial, Helvetica, sans-serif}
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" topmargin=5 leftmargin=5 marginwidth=0 marginheight=0>
<h2>Error</h2><br>
<h3>
DHD;

$die_ftr = '</h3></body></html>';

$sesspath = getcwd().'/sessdata/xlssess';
$sesstimeout = 30;
$uploaddir = getcwd().'/uploads/up';

require 'sessions.php';
require '../../ExcelExplorer.php';

$self_url = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS['PHP_SELF'].'?'.$HTTP_SERVER_VARS['QUERY_STRING'];
$self_plain_url = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS['PHP_SELF'];

function StripMagicQuotes($arr) {
	reset($arr);
	while( list($key,$value) = each($arr) ) {
		if( is_array($value) )
			$arr[$key] = StripMagicQuotes($value);
		else {
			if( is_string($value) )
				$arr[$key] = stripslashes($value);
		}
	}
	return $arr;
}

if( get_magic_quotes_gpc() > 0 ) {
	$HTTP_POST_VARS = StripMagicQuotes($HTTP_POST_VARS);
	$HTTP_GET_VARS = StripMagicQuotes($HTTP_GET_VARS);
}

?>