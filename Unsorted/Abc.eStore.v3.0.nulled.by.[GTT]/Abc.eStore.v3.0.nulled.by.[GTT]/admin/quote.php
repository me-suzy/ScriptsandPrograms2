<?php

//------------------------------------------------------------------------
// This script is a part of library.
//------------------------------------------------------------------------

function StripMagicQuotes($arr)
{
	reset($arr);
	while( list($key,$value) = each($arr) )
	{
		if( is_array($value) )
			$arr[$key] = StripMagicQuotes($value);
		elseif( is_string($value) )
			$arr[$key] = stripslashes($value);
	}
	return $arr;
}

if( get_magic_quotes_gpc() > 0 )
{
	$_POST = StripMagicQuotes($_POST);
	$_GET = StripMagicQuotes($_GET);
}

?>