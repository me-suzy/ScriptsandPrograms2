<?php

/*********************************************************
 * Name: lang.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Class declaration for languages
 * Version: 3.20
 * Last edited: 18th August, 2003
 *********************************************************/

class language
{
	var $lang,
		$langSet; 
	
	function language($set = "1")
    {
        global $rwdInfo;

		$this->langSet = $set;
		require_once $rwdInfo->path."/lang/".$set."/lang.php";
		// strip slashes
		foreach ($this->lang as $l=>$v) 
		{
			$this->lang[$l] = stripslashes($v);
		} 
			
	}
}

function GETLANG($element)
{
	global $rwdInfo;
	if (!$rwdInfo->lang[$element])
		return "#LANG.".strtoupper($element)."#";
	else
		return $rwdInfo->lang[$element];
}
?>