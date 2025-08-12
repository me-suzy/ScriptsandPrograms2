<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 26th September 2005                     #||
||#     Filename: functions.php                          #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package install
*/


function checkUploadedFiles($path)
{
    
    static $files = array();
    
    if (is_dir($path))
	{
		if (substr($path, -1) == "/")
			$path = substr($path, 0, -1); //get rid of /
		$dh = opendir($path);
		while (($file = readdir($dh)) !== false)
		{
			if ($file != '.' && $file != '..')
			{
				if (is_dir($path."/".$file))
					checkUploadedFiles($path."/".$file);
				else
					$files[$path . "/". $file] = array("is_writable" => (is_writable($path . "/". $file) == true ? 1  : 0));
			}
		}
		return $files;
	}
	else
		return false; //no point in continuing its not a directory bad call somewhere
    
}

/**
    Checks that permissions are correct and all files are uploaded

    @param serialize reqSerial - The Required Serial
    @param serialize actSerial - The Actual Serial of files/directories
    @return boolean / array
*/
function checkAllFiles($reqSerial, $actSerial)
{
    $required   = unserialize($reqSerial);
    $actual     = unserialize($actSerial);
    
    $errors = array();
    $isTrue = true;
    foreach ($required as $key => $value)
    {
        if (array_key_exists($key, $actual))
        {
            if ($value["is_writable"] === $actual[$key]["is_writable"] || (stristr(php_uname('s'), "Win")))
                $isTrue = true;
            else
            {
                array_push($errors, "$key is " . ($value["is_writable"] == 1 ? "" : "not") . " suppose to be writable");
                $isTrue = false;
            }
        }
        else
        {
            array_push($errors, "$key doesnt exist.");
            $isTrue = false;
        }
    }
    
    return ($isTrue == true) ? true : $errors;
}

?>
