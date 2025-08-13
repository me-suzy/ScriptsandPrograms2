<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


set_magic_quotes_runtime(0);

if(phpversion() < "4.2.0")
{
	if(is_array($HTTP_COOKIE_VARS))
	{
		while(list($key,$val)=each($HTTP_COOKIE_VARS))
		{
			$_REQUEST[$key]=$val;
			$_COOKIE[$key]=$val;
		}
	}

	if(is_array($HTTP_SESSION_VARS))
	{
		while(list($key,$val)=each($HTTP_SESSION_VARS))
		{
			$_SESSION[$key]=$val;
		}
	}

	if(is_array($HTTP_POST_VARS))
	{
		while(list($key,$val)=each($HTTP_POST_VARS))
		{
			$_REQUEST[$key]=$val;
			$_POST[$key]=$val;
		}
	}
	
	if(is_array($HTTP_GET_VARS))
	{
	  while(list($key,$val)=each($HTTP_GET_VARS)) {
	   $_REQUEST[$key]=$val;
	   $_GET[$key]=$val;
	  }
	}

	if(is_array($HTTP_POST_FILES))
	{
		 while(list($key,$val)=each($HTTP_POST_FILES))
		 {
			 $_FILES[$key]=$val;
		 }
	}

	if(is_array($HTTP_SERVER_VARS))
	{
		while(list($key,$val)=each($HTTP_SERVER_VARS))
		{
			$_SERVER[$key]=$val;
		}
	}
}

// removed from global.php
//$_GET = $admin->clean_code($_GET);
//$_POST = $admin->clean_code($_POST);

// ------------------------------------------------------//
if (!preg_match("#admin/#",$_SERVER['REQUEST_URI']) )
{
	define(OUT_FOLDER,"out/");
	define(LANG_FOLDER,"lang/");
	define(MISC_FOLDER,"misc/");
}
// ------------------------------------------------------//
?>
