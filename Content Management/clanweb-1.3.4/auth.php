<?php
//	-----------------------------------------
// 	$File: auth.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-04-10
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
	// Check if cfg.php exists, if not, die
	if (!file_exists('cfg.php'))
	{
		die("FATAL ERROR: Config file is missing");
	}

require('cfg.php');
    
	 	$sql = $db->query("SELECT * FROM " .$db_prefix. "online 
					 	    WHERE cookiesum = '".$_COOKIE['catcookie']."' 
						    LIMIT 1");
		$read = $db->fetch_array($sql); 
	
		if(!$_COOKIE['catcookie'])
        {
         	header ("Location: index.php?denied");
            exit;
        }
          		    
		if($_COOKIE['catcookie'] == ''.$read['cookiesum'].'')
		{
		 echo"\n";
		}
		else
		{
		    header ("Location: index.php?error=denied");
            exit;
        }

?>
