<?php

/*  
   User input validation
   (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function checkLengthValue($string, $min, $max) {
  $length = strlen ($string);
 
  if (($length < $min) || ($length > $max)) {
    return FALSE;
  } else {
    return TRUE;
  }
} //checkLength

//how many characters too long
function tooLongNameValue ($string, $max) {
	$length = strlen ($string);
	$toolong = $length - $max;
    return $toolong;
}

// letters only
function isLetters($string) {
  return !preg_match ("/[^A-z]/", $element);
}

// check mailcode
function checkMailCode($code, $country) {
  
  $code = preg_replace("/[\s|-]/", "", $code);
  $length = strlen ($code);

  switch (strtoupper ($country)) {
    case 'US':
      if (($length <> 5) && ($length <> 9)) {
        return FALSE;
      }
      return isDigits($code);
    case 'CA':  // Canada?
      if ($length <> 6) {
        return FALSE;
      }
      return preg_match ("/([A-z][0-9]){3}/", $code);
  }
} //checkMailCode

function checkURL($url_val) {
		if ($url_val == "") {
				return false;
		} else {
			$url_pattern = "http\:\/\/[[:alnum:]\-\.]+(\.[[:alpha:]]{2,4})+";
			$url_pattern .= "(\/[\w\-]+)*"; // folders like /val_1/45/
			$url_pattern .= "((\/[\w\-\.]+\.[[:alnum:]]{2,4})?"; // filename like index.html
			$url_pattern .= "|"; // end with filename or ?
			$url_pattern .= "\/?)"; // trailing slash or not
			$error_count = 0;
			if (strpos($url_val, "?")) {
				$url_parts = explode("?", $url_val);
				if (!preg_match("/^".$url_pattern."$/", $url_parts[0])) {
					$error_count++;
				}
				if (!preg_match("/^(&?[\w\-]+=\w*)+$/", $url_parts[1])) {
					$error_count++;
				}
			} else {
				if (!preg_match("/^".$url_pattern."$/", $url_val)) {
					$error_count++;
				}
			}
			if ($error_count > 0) {
					return false;
			} else {
				return true;
			}
		}
	}


//check URL and try to connect to it to make sure it is real
function checkURLandConnect($url) {  
  if (!preg_match ("/http:\/\/(.*)\.(.*)/i", $url)) {
    return FALSE;
  }
  $parts = parse_url($url);
  $fp = fsockopen($parts['host'], 80, $errno, $errstr, 10);
  if(!$fp) {
    return FALSE;
  }
  fclose($fp);
  return TRUE;
}


//check password
function checkPassword($password) {
  $length = strlen ($password);
  if ($length < 8) {
    return FALSE;
  }
  $unique = strlen (count_chars ($password, 3));
  $difference = $unique / $length;
  echo $difference;
  if ($difference < .60) {
    return FALSE;
  }
  return preg_match ("/[A-z]+[0-9]+[A-z]+/", $password);
}

//check slashes
function checkSlashes($message){
			if (!get_magic_quotes_gpc()) { 
			$message = addslashes($message); 
			$message = str_replace('$', '\$', $message ); 
			} 
			
			return $message;
}

//check slashes when displaying
function checkAddSlashes($message){
			if (!get_magic_quotes_gpc()) { 
			$message = stripslashes($message); 
			$message = str_replace('\$', '$', $message ); 
			} 
			
			return $message;
}

//check string for internationally valid letters
function checkString($string) {
	if (strspn($string,";?:@&=+$,#%-_.!~*'() abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZßäÄüÜöÖ")
		!= strlen($string)) {
	return FALSE;
	}
    return TRUE;
}
//removes apostrophe and backslashes from email messages
function clean($nom){
		$nom=str_replace("\\\\","",$nom); $nom=str_replace("\\'","",$nom); $nom=strip_tags($nom);
return $nom; } 		

//cleanup htmlspecialchars messiness
function cleanhtml($nom){
		$nom=str_replace("\\","",$nom); $nom=str_replace("&quot;","",$nom); $nom=str_replace("&lt;/p&gt;","",$nom); $nom=str_replace("&lt;p&gt;","",$nom); $nom=str_replace("&lt;/strong&gt;","",$nom); $nom=str_replace("&lt;strong&gt;","",$nom); $nom=str_replace("&lt;b&gt;","",$nom); $nom=str_replace("&lt;/b&gt; ","",$nom); $nom=str_replace("&gt;","",$nom); $nom=strip_tags($nom);
return $nom; } 		


//reform users filename to be legal
function reform($nom) { $nom=stripslashes($nom); $nom=str_replace("'","",$nom); $nom=str_replace("\"","",$nom); $nom=str_replace("\"","",$nom); $nom=str_replace("&","",$nom); $nom=str_replace(",","",$nom); $nom=str_replace(";","",$nom); $nom=str_replace("\\","",$nom); $nom=str_replace("`","",$nom); $nom=str_replace("<","",$nom); $nom=str_replace(">","",$nom); $nom=str_replace(" ","_",$nom); $nom=str_replace(":","",$nom); $nom=str_replace("*","",$nom); $nom=str_replace("|","",$nom); $nom=str_replace("?","",$nom); $nom=str_replace("é","e",$nom); $nom=str_replace("è","e",$nom); $nom=str_replace("ç","c",$nom); $nom=str_replace("","",$nom); $nom=str_replace("â","a",$nom); $nom=str_replace("ê","e",$nom); $nom=str_replace("î","i",$nom); $nom=str_replace("ô","o",$nom); $nom=str_replace("û","u",$nom); $nom=str_replace("ù","u",$nom); $nom=str_replace("à","a",$nom); $nom=str_replace("!","",$nom); $nom=str_replace("§","",$nom); $nom=str_replace("+","",$nom); $nom=str_replace("^","",$nom); $nom=str_replace("(","",$nom); $nom=str_replace(")","",$nom); $nom=str_replace("#","",$nom); $nom=str_replace("=","",$nom); $nom=str_replace("$","",$nom); $nom=str_replace("%","",$nom); $nom=str_replace("ä","ae",$nom); $nom=str_replace("Ä","Ae",$nom); $nom=str_replace("ö","oe",$nom); $nom=str_replace("Ö","Oe",$nom); $nom=str_replace("ü","ue",$nom); $nom=str_replace("Ü","Ue",$nom); $nom=str_replace("ß","ss",$nom); return $nom; } 






?>