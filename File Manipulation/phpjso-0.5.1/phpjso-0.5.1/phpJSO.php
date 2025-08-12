<?php
/**
 * phpJSO - The Javascript Obfuscator written in Javascript. Although
 * it effectively obfuscates Javascript code, it is meant to compress
 * code to save disk space rather than hide code from end-users.
 *
 * @started: Mon, May 23, 2005
 * @copyright: Copyright (c) 2004, 2005 JPortal, All Rights Reserved
 * @website: www.jportalhome.com
 * @license: Free, zlib/libpng license - see LICENSE
 * @version: 0.5.1
 * @subversion: $Id: phpJSO.php 36 2005-10-05 18:37:09Z josh $
 */

// Only do HTML output if UNIT_TEST constant is not present
if (!defined('UNIT_TEST'))
{
	// Uncomment to profile using APD
	//apd_set_pprof_trace();

	$phpJSO_version = '0.5.1';

	// Compress javascript from a submitted form
	$compressed_js = 'Compressed code will be placed here';
	$code = 'Place your code here.';
	$messages = array();
	if (isset($_REQUEST['jscode']))
	{
		// Get JS code
		$code = $_REQUEST['jscode'];
		// Strip slashes from input?
		if (get_magic_quotes_gpc())
		{
			$code = stripslashes($code);
		}
		// Compress
		$compressed_js = phpJSO_compress($code, $messages, (isset($_REQUEST['fast_decompress']) ? true : false));
	}
	$compressed_js = htmlspecialchars($compressed_js);
	$code = htmlspecialchars($code);

	// Format compression messages, if any
	$message = '';
	if (count($messages))
	{
		$message = '<b>Successfully compressed code.</b><br /><ul>';
		foreach ($messages as $k=>$m)
		{
			$message .= "<li>$m</li>";
		}
		$message .= '</ul><br /><br />';
	}

	// Get HTML value of fast_decompress checkbox
	$fast_decompress_value = (isset($_REQUEST['fast_decompress']) ? 'checked="checked"' : '');

	// Show forms, including any compressed JS
	print("
		<html>
			<head>
				<title>phpJSO version $phpJSO_version</title>
				<style type=\"text/css\">
					body {
						margin: 0px;
						padding: 20px;
						background-color: #ffffff;
						color: #000000;
						font-family: Verdana, Arial, Sans;
						font-size: 11px;
					}
					textarea {
						background-color: #dddddd;
						width: 100%;
						height: 40%;
						padding: 5px;
						color: #000000;
						font-family: Verdana, Arial, Sans;
						font-size: 11px;
					}
				</style>
				<script>
				</script>
			</head>
			<body>
				<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
					$message
					
					<b>Compressed Code:</b><br />
					<textarea rows=\"20\" cols=\"30\">$compressed_js</textarea><br /><br />
		
					<b>Place Your Code Here:</b><br />
					<textarea rows=\"20\" cols=\"30\" name=\"jscode\">$code</textarea><br /><br />
					
					<b><input type=\"checkbox\" name=\"fast_decompress\" id=\"id_fast_decompress\" $fast_decompress_value /><label for=\"id_fast_decompress\">Fast Decompression</label></b><br />
					This option is <i>highly</i> recommended for large Javascript files; the larger
					a script is, the longer it will take to decompress. You won't notice much of a speed
					difference with smaller scripts. Note, however, that this option also makes the
					compressed code <i>slightly</i> larger.<br /><br />
					
					<input type=\"submit\" value=\"Compress Code\" />
				</form>
			</body>
		</html>
	");
}


/**
 * Main phpJSO compression function. Pass Javascript code to it, and it will
 * return compressed code.
 */
function phpJSO_compress ($code, &$messages, $fast_decompress)
{
	// Start timer
	$start_time = phpJSO_microtime_float();
	
	// Array of tokens - alphanumeric
	$tokens = array();
	
	// Array of only numeric tokens, that are only inserted to prevent being
	// wrongly replaced with another token. For example: the integer 0 will
	// be replaced with whatever is at token index 0.
	$numeric_tokens = array();
	
	// Save original code length
	$original_code_length = strlen($code);
	
	// Remove strings and multi-line comments from code before performing operations
	$str_array = array();
	phpJSO_strip_strings_and_comments($code, $str_array, substr(md5(time()), 10, 2));
	
	// Strip junk from JS code
	phpJSO_strip_junk($code);
	
	// Add strings back into code
	phpJSO_restore_strings($code, $str_array);
	
	// Compressed code
	$compressed_code = $code;
	
	// Find all tokens in code
	phpJSO_get_tokens($compressed_code, $numeric_tokens, $tokens);
	
	// Insert numeric tokens into token array
	phpJSO_merge_token_arrays($tokens, $numeric_tokens);

	// Replace all tokens with their token index
	phpJSO_replace_tokens($tokens, $compressed_code);
	
	// We have to sort the array because it can end up looking like this:
	// (
	//   [0] => var
	//   ...
	//   [5] => opera
	//   [7] => 
	//   [6] => domLib_isSafari
	//   [8] => domLib_isKonq
	// )
	ksort($tokens);
	reset($tokens);
	
	// Insert decompression code
	$compressed_code_double_slash = '"'.preg_replace(array('#\\\\#si', '#"#si'), array('\\\\\\\\', '\\"'), $compressed_code).'"';
	$compressed_code_single_slash = "'".preg_replace(array('#\\\\#si', "#'#si"), array('\\\\\\\\', "\\'"), $compressed_code)."'";
	$compressed_code = (strlen($compressed_code_double_slash) < strlen($compressed_code_single_slash) ? $compressed_code_double_slash : $compressed_code_single_slash);
	if ($fast_decompress)
	{
		$messages[] = 'Fast decompression mode.';
		$compressed_code = "eval(function(a,b,c,d,e){if(!''.replace(/^/,String)){d=function(e){return c[e]&&typeof(c[e])=='string'?c[e]:e};b=1}while(b--)if(c[b]||d)a=a.replace(new RegExp(e+(d?'\\\\w+':b)+e,'g'),d||c[b]);return a}($compressed_code,".count($tokens).",'".implode('|',$tokens)."'.split('|'),0,'\\\\b'))";
	}
	else
	{
		$compressed_code = "eval(function(a,b,c,d){while(b--)if(c[b])a=a.replace(new RegExp(d+b+d,'g'),c[b]);return a}($compressed_code,".count($tokens).",'".implode('|',$tokens)."'.split('|'),'\\\\b'))";
	}
	
	// Which is smaller: compressed code or uncompressed code?
	if (strlen($code) < strlen($compressed_code))
	{
		$messages[] = 'The uncompressed code (with only comments and whitespace removed)
			was smaller than the fully compressed code.';
		$compressed_code = $code;
	}
	
	// End timer
	$execution_time = phpJSO_microtime_float() - $start_time;
	
	// Message about how long compression took
	$messages[] = "Compressed code in $execution_time seconds.";
	
	// Message reporting compression sizes
	$compressed_length = strlen($compressed_code);
	$ratio = $compressed_length / $original_code_length;
	$messages[] = "Original code length: $original_code_length.
		<br />Compressed code length: $compressed_length.
		<br />Compression ratio: $ratio.";
	
	return $compressed_code;
}

/**
 * Strip strings and comments from code
 */
function phpJSO_strip_strings_and_comments (&$str, &$strings, $comment_delim)
{
	// Find all occurances of comments and quotes. Then loop through them and parse.
	$quotes_and_comments = &phpJSO_sort_occurances($str, array('//', '/*', '*/', '"', "'"));

	// Loop through occurances of quotes and comments
	$in_string = $last_quote_pos = $in_comment = false;
	$removed = 0;
	$num_strings = count($strings);
	foreach ($quotes_and_comments as $location => $token)
	{
		if ($in_string !== false)
		{
			if ($token == $in_string && $str[$location - $removed - 1] != '\\')
			{
				// First, we'll pull out the string and save it, and replace it with a number.
				$replacement = '`' . $num_strings . '`';
				$string_start_index = $last_quote_pos - $removed;
				$string_length = ($location - $last_quote_pos) + 1;
				$strings[$num_strings] = &substr($str, $string_start_index, $string_length);
				++$num_strings;

				// Remove the string completely
				$str = substr_replace($str, $replacement, $string_start_index, $string_length);

				// Clean up time...
				$removed += $string_length - strlen($replacement);
				$in_string = $last_quote_pos = false;
			}
		}
		else if ($in_comment !== false)
		{
			if ($token == '*/')
			{
				$comment_start_index = $in_comment - $removed;
				$comment_length = ($location - $in_comment) + 2;
				$str = substr_replace($str, '', $comment_start_index, $comment_length);
				$removed += $comment_length;
				$in_comment = false;
			}
		}
		else
		{
			// Make sure string hasn't been extracted by another operation...
			if (substr($str, $location - $removed, strlen($token)) != $token)
			{
				continue;
			}
			
			// This string shouldn't have been escaped...
			if ($location && $str[$location - $removed - 1] == '\\')
			{
				continue;
			}
			
			// See what this token is ...
			// Start of multi-line comment?
			if ($token == '/*')
			{
				$in_comment = $location;
			}
			// Start of a string?
			else if ($token == '"' || $token == "'")
			{
				$in_string = $token;
				$last_quote_pos = $location;
			}
			// A single-line comment?
			else if ($token == '//')
			{
				$comment_start_position = $location - $removed;
				$newline_pos = strpos($str, "\n", $comment_start_position);
				$comment_length = ($newline_pos !== false ? $newline_pos - $comment_start_position : $comment_start_position);
				$str = substr_replace($str, '', $comment_start_position, $comment_length);
				$removed += $comment_length;
			}
		}
	}
}

/**
 * Strips junk from code
 */
function phpJSO_strip_junk (&$str)
{
	// Remove unneeded spaces and semicolons
	$find = array
	(
		'/([^a-zA-Z0-9\s]|^)\s+([^a-zA-Z0-9\s]|$)/s', // Unneeded spaces between tokens
		'/([^a-zA-Z0-9\s]|^)\s+([a-zA-Z0-9]|$)/s', // Unneeded spaces between tokens
		'/([a-zA-Z0-9]|^)\s+([^a-zA-Z0-9\s]|$)/s', // Unneeded spaces between tokens
		'/([^a-zA-Z0-9]|^)\s+([^a-zA-Z0-9]|$)/s', // Unneeded spaces between tokens
		'/([^a-zA-Z0-9]|^)\s+([a-zA-Z0-9]|$)/s', // Unneeded spaces between tokens
		'/([a-zA-Z0-9]|^)\s+([^a-zA-Z0-9]|$)/s', // Unneeded spaces between tokens
		'/[\r\n]/s', // Unneeded newlines
		'/;(\}|$)/si' // unneeded semicolons
	);
	$replace = array
	(
		'$1$2',
		'$1$2',
		'$1$2',
		'$1$2',
		'$1$2',
		'$1$2',
		'',
		'$1'
	);
	$str = preg_replace($find, $replace, $str);
}

/**
 * Get all the tokens in code and put them in two arrays - one array
 * for just numeric tokens, and another array for all the rest.
 */
function phpJSO_get_tokens ($code, &$numeric_tokens, &$tokens)
{
	preg_match_all('#([a-zA-Z0-9\_\$]+)#s', $code, $match);
	$matched_tokens = array_values(array_unique($match[0]));
	phpJSO_count_duplicates($duplicates, $match[0]);
	foreach ($matched_tokens as $token)
	{
		// If token is an integer, we do replacements differently
		if (is_numeric($token))
		{
			$numeric_tokens[] = $token;
		}
		// We can place token in the array normally (but it's only worth doing
		// a replacement if the token isn't just one character).
		// It's also only worth doing a replacement if the token appears more than once in code.
		else if (isset($token{1}) && $duplicates[$token] != 1)
		{
			phpJSO_insert_token($tokens, $token, -1);
		}
	}
}

/**
 * Merges the two token arrays: numeric tokens and regular tokens.
 * Specifically this function will take all the numeric tokens and
 * POSSIBLY put them in the token array if that's necessary.
 */
function phpJSO_merge_token_arrays (&$tokens, &$numeric_tokens)
{
	// Sort numeric token array
	sort($numeric_tokens);
	reset($numeric_tokens);

	// Loop through all numeric tokens
	foreach ($numeric_tokens as $int)
	{
		if (count($tokens) < $int)
		{
			// We may not need to consider ANY more numeric tokens, if this
			// one is higher than the number of tokens, since the numeric tokens
			// are sorted already. This can potentially save a lot of time.
			if (strlen(sprintf('%s',count($tokens))) >= strlen(sprintf('%s',$int)))
			{
				break;
			}
			else
			{
				phpJSO_insert_token($tokens, $int, -1);
				continue;
			}
		}
		phpJSO_insert_token($tokens, '', $int);
	}	
}

/**
 * Inserts a token into the token array. Either places the token
 * on the end of the array, or shifts all the other tokens and puts
 * it somewhere in the middle.
 */
function phpJSO_insert_token (&$token_array, $token, $token_index)
{
	// Insert token at end of array
	if ($token_index == -1)
	{
		$token_array[] = $token;
	}
	// Insert token at certain index
	else
	{
		// Loop through array and shift all indexes up one spot until we reach the
		// index we are inserting at
		$jump = 1;
		for ($i = count($token_array) - 1; $i > ($token_index - 1); --$i)
		{
			if ($token_array[$i] == '')
			{
				$jump++;
				continue;
			}
			$token_array[$i+$jump] = $token_array[$i];
			$jump = 1;
		}
		$token_array[$token_index] = $token;
	}
}

/**
 * Place stripped strings back into code
 */
function phpJSO_restore_strings (&$str, &$strings)
{
	do
	{
		$str = preg_replace('#`([0-9]+)`#e', 'isset($strings["$1"]) ? $strings["$1"] : "`$1`"', $str);
	} while (preg_match('#`([0-9]+)`#', $str));
}

/**
 * Count duplicate values in an array
 */
function phpJSO_count_duplicates (&$dupes, $ary)
{
	foreach ($ary as $v)
	{
		if (isset($dupes[$v]))
		{
			++$dupes[$v];
		}
		else
		{
			$dupes[$v] = 1;
		}
	}	
}

/**
 * Replaces tokens in code with the corresponding token index.
 */
function phpJSO_replace_tokens (&$tokens, &$code)
{
	$find = array();
	$replace = array();
	foreach ($tokens as $k=>$v)
	{
		if ($v)
		{
			$find[] = "#\b$v\b#";
			$replace[] = $k;
		}
	}
	$code = preg_replace($find, $replace, $code);
}

/**
 * For timing compression
 */
function phpJSO_microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

/**
 * Finds all occurances of different strings in the first passed string and sorts
 * them by location. Returns array of locations. The key of each array element is the string
 * index (location) where the string was found; the value is the actual string, as seen below.
 *
 * [18] => "
 * [34] => "
 * [56] => /*
 * [100] => '
 */
function phpJSO_sort_occurances (&$haystack, $needles)
{
	$locations = array();
	
	foreach ($needles as $needle)
	{
		$pos = -1;
		while (($pos = @strpos($haystack, $needle, $pos+1)) !== false)
		{
			$locations[$pos] = $needle;
		}
	}
	
	ksort($locations);
	
	return $locations;
}
?>
