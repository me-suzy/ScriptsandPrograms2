<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created:  2nd August 2005                        #||
||#     Filename: common.php                             #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package all
	@todo Complete section
*/

if (!defined('wbnews'))
	die('Hacking Attempt');


/* Check if the get_magic_quotes_gpc() is on we need to remove slashes */
if (get_magic_quotes_gpc())
{
	function remove_slashes($arr)
	{
		if (is_array($arr))
		{
			foreach($arr AS $_arrykey => $_arryval)
			{
				if (is_string($_arryval))
					$arr["$_arrykey"] = stripslashes($_arryval);
				else if (is_array($_arryval))
					$arr["$_arrykey"] = remove_slashes($_arryval);
			}
		}
		return $arr;
	}
	
	$_GET = remove_slashes($_GET);
	$_POST = remove_slashes($_POST);
	$_COOKIE = remove_slashes($_COOKIE);
	$_FILES = remove_slashes($_FILES);
	$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
}

?>
