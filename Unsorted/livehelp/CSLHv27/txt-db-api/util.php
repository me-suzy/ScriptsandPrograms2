<?php
/**********************************************************************
						 Php Textfile DB API
						Copyright 2003 by c-worker.ch
						  http://www.c-worker.ch
***********************************************************************/
/**********************************************************************
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
***********************************************************************/

include_once(API_HOME_DIR . "const.php");

/**********************************************************************
							Global vars 
***********************************************************************/

$g_txtdbapi_errors=array();

/**********************************************************************
							Util Functions
***********************************************************************/

/***********************************
	 	Version Functions
************************************/
function txtdbapi_version() {
	return TXTDBAPI_VERSION;
}



/***********************************
	 	Debug Functions
************************************/
function debug_printb($str) {
	if(TXTDBAPI_DEBUG) {
		echo "<b>" . $str . "</b>";
	}
}

function debug_print($str) {
	if(TXTDBAPI_DEBUG) {
		echo $str;
	}
}

function verbose_debug_print($str) {
	if(TXTDBAPI_VERBOSE_DEBUG) {
		echo $str;
	}
}


/***********************************
	 	Char Functions
************************************/
function last_char($string) {
	return $string{strlen($string)-1};
}

function remove_last_char(&$string) {
	$string=substr($string,0,strlen($string)-1);
}


/***********************************
	 	String Functions
************************************/
// returns $length chars from the right side of $string
function substr_right($string,$length) {
	return substr($string, strlen($string)-$length);
}



/***********************************
	 	Array Functions
************************************/
function array_walk_trim(&$value, &$key) {
	$value=trim($value);
}

function create_array_fill($size, $value) {
	$arr=array();
	for($i=0;$i<$size;++$i)
		$arr[]=$value;
	return $arr;
}

// searches the first n chars of $string in $array
// where n is the length of reach $array element
// returns the value of $array if found or false
function array_search_str_start($string, $array) {
	for($i=0;$i<count($array);++$i) {
		//debug_print("Searching " . $array[$i] . " in " . $string . "<br>");
		if(strncmp($array[$i],$string, strlen($array[$i]))==0)
			return $array[$i];
	}
	return false;
}

// as above but case insenitive
function array_search_stri_start($string, $array) {
    for($i=0;$i<count($array);++$i) {
		//debug_print("Searching " . $array[$i] . " in " . $string . "<br>");
		if(strncmp(strtoupper($array[$i]),strtoupper($string), strlen($array[$i]))==0)
			return $array[$i];
	}
	return false;
}

/***********************************
	 	Type Functions
************************************/
function dump_retval_type($var) {
  if(is_bool($var) && !$var) 
    echo "The value is FALSE<br>"; 
  if(is_int($var) && !$var) 
    echo "The value is 0<br>"; 
  if(!isset($var)) 
    echo "The value is NULL<br>"; 
  if(is_string($var) && $var=="") 
    echo "The value is \"\"<br>"; 
  if(is_string($var) && $var=="0") 
    echo "The value is \"0\"<br>"; 
  if($var)
  	echo "The value is a TRUE or something other then 0 or FALSE<br>"; 
} 

function is_false($var) {
	return (is_bool($var) && !$var);
}
function is_0($var) {
	return (is_int($var) && !$var);
}
// _ at the front, cause is_null exists
function _is_null($var) {
	return (!isset($var)) ;
}
function is_empty_str($var) {
	return (is_string($var) && $var=="");
}

/***********************************
	 	SQL Util Functions
************************************/
// compares 2 values by $operator, and returns true or false
function compare($value1,$value2,$operator) {
	
	if($operator=="=")
		return ($value1 == $value2);

	if($operator==">")
		return ($value1 > $value2);
		
	if($operator=="<")
		return ($value1 < $value2);
		
	if($operator=="<>" || $operator=="!=")
		return ($value1 != $value2);
		
	if($operator==">=")
		return ($value1 >= $value2);
		
	if($operator=="<=")
		return ($value1 <= $value2);
		
	if(trim(strtoupper($operator))=="LIKE") {
		return compare_like($value1,$value2);
	}
		
	return false;
}


function compare_like($value1,$value2) { 
	static $patterns = array(); 

	// Lookup precomputed pattern 
	if(isset($patterns[$value2])) { 
		$pat = $patterns[$value2]; 
	} else { 
		// Calculate pattern 
		$rc=0; 
		$mod = ""; 
		$prefix = "/^"; 
		$suffix = "$/"; 
       
		// quote regular expression characters 
		$str=preg_quote($value2,"/"); 
       
		// unquote \ 
		$str=str_replace ("\\\\", "\\",$str); 
       
		// Optimize leading/trailing wildcards 
		if(substr($str, 0, 1) == '%') { 
			$str = substr($str, 1); 
			$prefix = "/"; 
		} 
		if(substr($str, -1) == '%' && substr($str, -2, 1) != '\\') { 
			$str = substr($str, 0, -1); 
			$suffix = "/"; 
		} 
       
		// case sensitive ? 
		if(!LIKE_CASE_SENSITIVE) 
			$mod="i"; 
          
		// setup a StringParser and replace unescaped '%' with '.*' 
		$sp=new StringParser(); 
		$sp->setConfig(array() ,"\\",array()); 
		$sp->setString($str); 
		$str=$sp->replaceCharWithStr("%",".*"); 
		// replace unescaped '_' with '.' 
		$sp->setString($str); 
		$str=$sp->replaceCharWithStr("_","."); 
		$pat = $prefix . $str . $suffix . $mod; 

		// Stash precomputed value 
		$patterns[$value2] = $pat; 
	} 
       
	return preg_match ($pat, $value1); 
}

// splits a full column name into its subparts (name, table, function)
// return true or false on error
function split_full_colname($fullColName,&$colName,&$colTable,&$colFunc) {
	
	$colName="";
	$colTable="";
	$colFunc="";
	
	// direct value ?
	if(is_numeric($fullColName) || has_quotes($fullColName)) {
		$colName=trim($fullColName);
		return true;
	}
	
	if(!is_false ($pos=strpos($fullColName,"(")) ) {
		$colFunc=substr($fullColName,0,$pos);
		$fullColName=substr($fullColName,$pos+1);
	}
	
	if(!is_false ($pos=strpos($fullColName,".")) ) {
		$colTable=substr($fullColName,0,$pos);
		$colName=substr($fullColName,$pos+1);
	}  else {
		$colName=$fullColName;
	}

	$colName=trim($colName);
	if($colFunc) {
		if(last_char($colName)==")") {
			remove_last_char($colName);
		} else {
			print_error_msg(") expected after $colName!");
			return false;
		}
	}
	$colName=trim($colName);
	$colTable=trim($colTable);
	$colFunc=strtoupper(trim($colFunc));
	return true;
}


function execFunc($func, $param) {
	switch($func) {
		case "MD5":
			return doFuncMD5($param);
		case "NOW":
			return doFuncNOW();
		case "UNIX_TIMESTAMP":
			return doFuncUNIX_TIMESTAMP();
		case "ABS":
			return doFuncABS($param);
		case "LCASE":
		case "LOWER":
			return doFuncLCASE($param);
		case "UCASE":
		case "UPPER":
			return doFuncUCASE($param);
		default:
			print_error_msg("function '$func' not supported!");
			return $param;
	}
	return $col;
}

function doFuncMD5($param) {
	return md5($param) ;
}

function doFuncNOW() {
	return date("Y-m-d H:i:s",get_static_timestamp());
}

function doFuncUNIX_TIMESTAMP() {
	return get_static_timestamp();
}

function doFuncABS($param) {
	return abs($param);
}

function doFuncLCASE($param) {
	return strtolower($param);
}

function doFuncUCASE($param) {
	return strtoupper($param);
}

function execGroupFunc($func, $params) {
	
	switch($func) {
		//case "":
		//	return $params[0];
		case "MAX":
			return doFuncMAX($params);
		case "MIN":
			return doFuncMIN($params);
		case "COUNT":
			return doFuncCOUNT($params);
		case "SUM":
			return doFuncSUM($params);
		case "AVG":
			return doFuncAVG($params);
			
		default:
			print_error_msg("Function '$func' not supported!!!");
	}
	return $col;
}

function doFuncMAX($params) {
	$maxVal=$params[0];
	for($i=1;$i<count($params);++$i) {
		$maxVal=max($maxVal,$params[$i]);
	}
	return $maxVal;
}

function doFuncMIN($params) {
	$minVal=$params[0];
	for($i=1;$i<count($params);++$i) {
		$minVal=min($minVal,$params[$i]);
	}
	return $minVal;
}

function doFuncCOUNT($params) {
	return count($params);
}

function doFuncSUM($params) {
	$sum=0;
	for($i=0;$i<count($params);++$i) {
		$sum+=$params[$i];
	}
	return $sum;
}

function doFuncAVG($params) {
	$sum=doFuncSUM($params);
	return $sum / count($params);
}

/***********************************
	 	Error Functions
************************************/
function print_error_msg($text, $nr=-1) {
	global $g_txtdbapi_errors;
	
	$g_txtdbapi_errors[]=$text;
	if(!PRINT_ERRORS)
		return;

	if($nr==-1)
		echo "<br><b>Php-Txt-Db-Access Error:</b><br>";
	else
		echo "<br> Php-Txt-Db-Access Error Nr $nr:<br>";
	echo $text . "<br>";	
}

function print_warning_msg($text, $nr=-1) {
	if(!PRINT_WARNINGS)
		return;
		
	if($nr==-1)
		echo "<br><b>Php-Txt-Db-Access Warning:</b><br>";
	else
		echo "<br> Php-Txt-Db-Access Warning Nr $nr:<br>";
	echo $text . "<br>";	
}

// returns true if errors occurred
function txtdbapi_error_occurred() {
	global $g_txtdbapi_errors;
	return (count($g_txtdbapi_errors)>0);
}

function txtdbapi_get_last_error() {
	global $g_txtdbapi_errors;
	if(!txtdbapi_error_occurred())
	    return "";
	return array_pop($g_txtdbapi_errors);
}

function txtdbapi_get_errors() {
	global $g_txtdbapi_errors;
	
	if(!txtdbapi_error_occurred())
	    return array();
	$arr=$g_txtdbapi_errors;
	$g_txtdbapi_errors=array();
	return $arr;
}

function txtdbapi_clear_errors() {
	global $g_txtdbapi_errors;
	$g_txtdbapi_errors=array();
}

// error handler function
function txtdbapi_error_handler ($errno, $errstr, $errfile, $errline) {
	$prefix="PHP Error: ";
	switch ($errno) {
  		case E_USER_ERROR:
    		print_error_msg($prefix . "FATAL [$errno] $errstr [Line: ".$errline."] [File: ". $errfile . "]");
    		break;
  		default:
    		print_error_msg($prefix . "[$errno] $errstr [Line: ".$errline."] [File: ". $errfile . "]");
    		break;
	}
}



/***********************************
	 	Quote Functions
************************************/
function has_quotes($str) {
	if(is_empty_str($str))
		return false;
	return ($str[0]=="'" || $str[0]=="\"") && (last_char($str)=="'" || last_char($str)=="\"");
}

function remove_quotes(&$str) {
	$str=substr($str,1);
	remove_last_char($str);
}

function array_walk_remove_quotes(&$value, &$key) {
	if(has_quotes($value))
		remove_quotes($value);
}


/***********************************
	 	Time Functions
************************************/
function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
} 

// ensures that all timestamp requests of one execution have the same time
function get_static_timestamp() {
	static $t = 0;
	if($t==0)
		$t=time();
	return $t;
}

?>