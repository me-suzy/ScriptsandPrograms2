<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/**
 * @return void
 * @param array $code
 * @desc Debug function
*/
function d($i=false)
{ 
	global $rn; ?><pre style="font-size: 10px;  color: Maroon; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;"><?print_r($i)?></pre><?
}


function intofile($variable, $filename="log.")
{
			$fp=fopen($GLOBALS["root_path"]."/".$filename , "wb+" );
			fwrite($fp, $variable );
			fclose($fp);
}

/**
 * @return void
 * @desc Page timeloading monitor
*/
function getmicrotime(){ list($usec, $sec) = explode(" ",microtime());    return ((float)$usec + (float)$sec); }

$GLOBALS["time_global_start"]=getmicrotime();

?>