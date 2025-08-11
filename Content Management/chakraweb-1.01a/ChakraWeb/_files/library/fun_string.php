<?php
// ----------------------------------------------------------------------
// ModName: fun_string.php
// Purpose: String Manipulation
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_string.php] file directly...");

function StrSelectLongPart($str1, $str2)
{
	if (strlen($str1) > strlen($str2))
		return $str1;
	else
		return $str2;
}

function StrIsStartWith($str, $what)
{
    return preg_match('/^'.$what.'/', $str);
}

function StrIsEndWith($str, $what)
{
    return preg_match('/'.$what.'$/', $str);
}

function Str2Charset($str)
{
	$chset = array();
	$len = strlen($str);

	for ($i=0; $i<$len; $i++)
		$chset[] = substr($str, $i, 1);

	return $chset;
}

function StrTrimLeft($str, $trimmer=" \t\x0B")
{
	$len = strlen($str);
	if ($len == 0)
		return $str;

	$chset = Str2Charset($trimmer);
	
	$pos = 0;
	while ($pos < $len && (in_array(substr($str,$pos,1),$chset)))
		$pos++;

	if ($pos > 0)
		return substr($str, $pos);
	else
		return $str;
}

function StrTrimRight($str, $trimmer=" \t\x0B")
{
	$len = strlen($str);
	if ($len == 0)
		return $str;

	$chset = Str2Charset($trimmer);

	$pos = $len-1;
	while ($pos>0 && (in_array(substr($str,$pos,1),$chset)))
		$pos--;

	if ($pos == $len-1)
		return $str;	
	else
		return substr($str, 0, $pos+1);
}

function StrTrim($str, $trimmer=" \t\x0B")
{
	$len = strlen($str);
	if ($len == 0)
		return $str;
	
	$chset = Str2Charset($trimmer);
	
	$start_pos = 0;
	while ($start_pos < $len && (in_array(substr($str,$start_pos,1),$chset)))
		$start_pos++;

	$end_pos = $len-1;
	while ($end_pos>0 && (in_array(substr($str,$end_pos,1),$chset)))
		$end_pos--;

	//$result = substr($str, $start_pos, $end_pos-$start_pos+1);
	//print "Source string: [$str]<br>\r\n";
	//print "Trimmer: [$trimmer]<br>\r\n";
	//print "Result: [$result]<br>\r\n<br>\r\n";

	return substr($str, $start_pos, $end_pos-$start_pos+1);
}

//simplify the params for preg_replace
function PRegReplace($params, $text)
{
	$search = array();
	$replace = array();
	
    reset($params);
	foreach($params as $s => $r)
	{
		//print "$s\t$r\r\n";
		$search[] = $s;
		$replace[] = $r;
	}

	reset ($search);
	reset ($replace);
	return preg_replace($search, $replace, $text);
}


/**
 * string strip slashes, taken from PostNuke
 *
 * stripslashes on multidimensional arrays.
 * Used in conjunction with pnVarCleanFromInput
 * @access private
 * @param any variables or arrays to be stripslashed
 */
function StrStripSlashes(&$value) 
{
    if(!is_array($value)) {
        $value = stripslashes($value);
    } else {
        array_walk($value,'StrStripslashes');
    }
}

function StrStripWhiteSpaces($value) 
{
    $param_spc = array(
					'|\r\n|msi' => '',
					'|\s\s|msi' => ' ',
					'|\t|msi' => ''
					);


	$value = PRegReplace($param_spc, $value);
	$value = PRegReplace($param_spc, $value);

	return $value;
}

function StrUnEscapeCallBack($match)
{
    $dec = hexdec(substr($match[0], 1));

    //PrintArray($match);
    //PrintLine($dec, "DEC");

    return chr($dec);
}


function StrUnEscape($str)
{
    $str = preg_replace_callback("/%[a-f]\d|%\d{2}/i", 'StrUnEscapeCallBack', $str);
    return $str;
}

function StrEscape($str)
{
    $str = str_replace('?', '%3F', $str);
    $str = str_replace('/', '%2F', $str);
    $str = str_replace('&', '%26', $str);
    $str = str_replace('=', '%3D', $str);
    $str = str_replace(' ', '%20', $str);

    return $str;
}

function SmileyEscape($str)
{
    $str = str_replace('(', '&#40;', $str);
    $str = str_replace(')', '&#41;', $str);
    //$str = str_replace(';', '&#59;', $str);
    $str = str_replace(':', '&#58;', $str);

    return $str;
}

?>
