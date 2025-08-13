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
							Util Functions
***********************************************************************/

/***********************************
	 	Public Functions
************************************/
function txtdbapi_version() {
	return TXTDBAPI_VERSION;
}



/***********************************
	 	Debug Functions
************************************/
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
    
    debug_print("compare() called: $value1, $value2, $operator<br>");
	
	if(trim(strtoupper($operator))=="LIKE") {
		return compareLike($value1,$value2);
	}
	if($operator=="<>" || $operator=="!=")
		return ($value1 != $value2);
		
	if($operator=="=")
		return ($value1 == $value2);
	
	if($operator==">")
		return ($value1 > $value2);
	if($operator=="<")
		return ($value1 < $value2);
		
	if($operator==">=")
		return ($value1 >= $value2);
	if($operator=="<=")
		return ($value1 <= $value2);
		
	return false;
}

function compareLike($value1,$value2) {
	$rc=0;
	$mod="";
	$useStrstrCmp=false;

	
	// quote regular expression characters
	$str=preg_quote($value2,"/");
	//debug_print("STR: $str<br>");
	
	// unquote \
	$str=str_replace ("\\\\", "\\",$str);
	//debug_print("STR: $str<br>");
	
	// is there only a % at the start and at the end ?
	// => use strstr its faster
	if(preg_match("/^%[^%]*%$/",$str)) {
		$useStrstrCmp=true;
		//debug_print("Using str(i)str to compare<br>");
	} else {
		//debug_print("Using preg_match to compare<br>");
	}
	
	// case sensitive ?
	if(!LIKE_CASE_SENSITIVE)
		$mod="i";
		
	//debug_print("MOD: $mod<br>");
		
	if($useStrstrCmp) {
		$str=substr($str,1,strlen($str)-2);
		//debug_print("STR (useStrstrCmp): $str<br>");
		if($mod=="i") {
			$rc=stristr($value1,$str);
		} else {
			$rc=strstr($value1,$str);
		}
	} else {
		// setup a StringParser and replace unescaped % with .*
		$sp=new StringParser();
		$sp->setConfig(array() ,"\\",array()); 
		$sp->setString($str);
    	$str=$sp->replaceCharWithStr("%",".*");
		//debug_print("STR (preg_match): $str<br>");
	
		$rc=preg_match ("/^" . $str . "$/" . $mod, $value1);
	}
		
	//debug_print("RC: $rc<br>");
	return $rc;
}

/***********************************
	 	Error Functions
************************************/
function print_error($text, $nr=-1) {
	if(!PRINT_ERRORS)
		return;

	if($nr==-1)
		echo "<br><b>Php-Txt-Db-Access Error:</b><br>";
	else
		echo "<br> Php-Txt-Db-Access Error Nr $nr:<br>";
	echo $text . "<br>";	
}

function print_warning($text, $nr=-1) {
	if(!PRINT_WARNINGS)
		return;
		
	if($nr==-1)
		echo "<br><b>Php-Txt-Db-Access Warning:</b><br>";
	else
		echo "<br> Php-Txt-Db-Access Warning Nr $nr:<br>";
	echo $text . "<br>";	
}

/***********************************
	 	Quote Functions
************************************/
function has_quotes($str) {
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


?>