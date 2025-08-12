<?php
function safe_php() {
/**
* Lets verify the PHP configuration.
*/
	$register_globals=@ini_get('register_globals');
/**
* Get super gloabals. We don't need $_SERVER, $_FILES and $_ENV. 
*/
	$superglobals = array('_GET', '_POST', '_COOKIE','_REQUEST'); 
/**
* Now, let's clean it up !
*/ 
	foreach ($superglobals as $superglobal)
	{
		if (is_array($superglobal))
		{
			foreach ($$superglobal as $key => $val)
			{
/**
* Unset global variables GET, POST and COOKIE if register_globals is on.
*/
				if ($register_globals)
				{
					unset($GLOBALS[$key]);
				}
			}
		}
	}
}
?>