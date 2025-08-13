<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.functions.php - part of getID3()                     //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

if (!function_exists('PrintHexBytes')) {
	function PrintHexBytes($string) {
		$returnstring = '';
		for ($i = 0; $i < strlen($string); $i++) {
			$returnstring .= str_pad(dechex(ord(substr($string, $i, 1))), 2, '0', STR_PAD_LEFT).' ';
		}
		return $returnstring;
	}
}

if (!function_exists('PrintTextBytes')) {
	function PrintTextBytes($string) {
		$returnstring = '';
		for ($i = 0; $i < strlen($string); $i++) {
			if (ord(substr($string, $i, 1)) <= 31) {
				$returnstring .= '   ';
			} else {
				$returnstring .= ' '.substr($string, $i, 1).' ';
			}
		}
		return $returnstring;
	}
}

if (!function_exists('FixTextFields')) {
	function FixTextFields($text) {
		$text = stripslashes($text);
		$text = str_replace('\'', '&#39;', $text);
		$text = str_replace('"', '&quot;', $text);
		return $text;
	}
}

if (!function_exists('table_var_dump')) {
	function table_var_dump($variable) {
		$returnstring = '';
		switch (gettype($variable)) {
			case 'array':
				$returnstring .= '<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">';
				foreach ($variable as $key => $value) {
					$returnstring .= '<TR><TD VALIGN="TOP"><B>'.str_replace(chr(0), ' ', $key).'</B></TD>';
					$returnstring .= '<TD VALIGN="TOP">'.gettype($value);
					if (is_array($value)) {
						$returnstring .= '&nbsp;('.count($value).')';
					} else if (is_string($value)) {
						$returnstring .= '&nbsp;('.strlen($value).')';
					}
					if (($key == 'data') && isset($variable['image_mime']) && isset($variable['dataoffset'])) {
						//$returnstring .= '</TD><TD><IMG SRC="getid3.thumbnail.php?filename='.rawurlencode($_REQUEST['filename']).'&frameoffset='.$variable['dataoffset'].'"></TD>';

						include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
						$imagechunkcheck = GetDataImageSize($value);
						$DumpedImageSRC = $_REQUEST['filename'].'.'.$variable['dataoffset'].'.'.ImageTypesLookup($imagechunkcheck[2]);
						if ($tempimagefile = fopen($DumpedImageSRC, 'wb')) {
							fwrite($tempimagefile, $value);
							fclose($tempimagefile);
						}
						$returnstring .= '</TD><TD><IMG SRC="'.$DumpedImageSRC.'" WIDTH="'.$imagechunkcheck[0].'" HEIGHT="'.$imagechunkcheck[1].'"></TD>';
					} else {
						$returnstring .= '</TD><TD>'.table_var_dump($value).'</TD>';
					}
				}
				$returnstring .= '</TABLE>';
				break;

			case 'boolean':
				$returnstring .= ($variable ? 'TRUE' : 'FALSE');
				break;

			case 'integer':
			case 'double':
			case 'float':
				$returnstring .= $variable;
				break;

			case 'object':
			case 'null':
				$returnstring .= string_var_dump($variable);
				break;

			case 'string':
				$variable = str_replace(chr(0), ' ', $variable);
				$varlen = strlen($variable);
				for ($i = 0; $i < $varlen; $i++) {
					if (ereg('['.chr(0x0A).chr(0x0D).' -;A-z]', $variable{$i})) {
						$returnstring .= $variable{$i};
					} else {
						$returnstring .= '&#'.str_pad(ord($variable{$i}), 3, '0', STR_PAD_LEFT).';';
					}
				}
				$returnstring = nl2br($returnstring);
				break;

			default:
				include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
				$imagechunkcheck = GetDataImageSize(substr($variable, 0, FREAD_BUFFER_SIZE));

				if (($imagechunkcheck[2] >= 1) && ($imagechunkcheck[2] <= 3)) {
					$returnstring .= '<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">';
					$returnstring .= '<TR><TD><B>type</B></TD><TD>'.ImageTypesLookup($imagechunkcheck[2]).'</TD></TR>';
					$returnstring .= '<TR><TD><B>width</B></TD><TD>'.number_format($imagechunkcheck[0]).' px</TD></TR>';
					$returnstring .= '<TR><TD><B>height</B></TD><TD>'.number_format($imagechunkcheck[1]).' px</TD></TR>';
					$returnstring .= '<TR><TD><B>size</B></TD><TD>'.number_format(strlen($variable)).' bytes</TD></TR></TABLE>';
				} else {
					$returnstring .= nl2br(htmlspecialchars(str_replace(chr(0), ' ', $variable)));
				}
				break;
		}
		return $returnstring;
	}
}

if (!function_exists('string_var_dump')) {
	function string_var_dump($variable) {
		ob_start();
		var_dump($variable);
		$dumpedvariable = ob_get_contents();
		ob_end_clean();
		return $dumpedvariable;
	}
}

if (!function_exists('fileextension')) {
	function fileextension($filename) {
		if (strstr($filename, '.')) {
			return substr(basename($filename), strrpos(basename($filename), '.') + 1);
		}
		return '';
	}
}

if (!function_exists('RemoveAccents')) {
	function RemoveAccents($string) {
		return strtr($string, "¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
	}
}

if (!function_exists('MoreNaturalSort')) {
	function MoreNaturalSort($ar1, $ar2) {
		if ($ar1 === $ar2) {
			return 0;
		}
		$len1 = strlen($ar1);
		$len2 = strlen($ar2);
		if (substr($ar1, 0, min($len1, $len2)) === substr($ar2, 0, min($len1, $len2))) {
			// the shorter argument is the beginning of the longer one, like "str" and "string"
			if ($len1 < $len2) {
				return -1;
			} else if ($len1 > $len2) {
				return 1;
			}
			return 0;
		}
		$ar1 = RemoveAccents(strtolower(trim($ar1)));
		$ar2 = RemoveAccents(strtolower(trim($ar2)));
		$translatearray = array('\''=>'', '"'=>'', '_'=>' ', '('=>'', ')'=>'', '-'=>' ', '  '=>' ', '.'=>'', ','=>'');
		foreach ($translatearray as $key => $val) {
			$ar1 = str_replace($key, $val, $ar1);
			$ar2 = str_replace($key, $val, $ar2);
		}

		if ($ar1 < $ar2) {
			return -1;
		} else if ($ar1 > $ar2) {
			return 1;
		}
		return 0;
	}
}

if (!function_exists('trunc')) {
	function trunc($floatnumber) {
		// truncates a floating-point number at the decimal point
		// returns int (if possible, otherwise double)
		if ($floatnumber >= 1) {
			$truncatednumber = floor($floatnumber);
		} else if ($floatnumber <= -1) {
			$truncatednumber = ceil($floatnumber);
		} else {
			$truncatednumber = 0;
		}
		if ($truncatednumber <= pow(2, 30)) {
			$truncatednumber = (int) $truncatednumber;
		}
		return $truncatednumber;
	}
}

if (!function_exists('CastAsInt')) {
	function CastAsInt($doublenum) {
		// convert a double to type int, only if possible
		if (trunc($doublenum) == $doublenum) {
			// it's not floating point
			if ($doublenum <= pow(2, 30)) {
				// it's within int range
				$doublenum = (int) $doublenum;
			}
		}
		return $doublenum;
	}
}

if (!function_exists('getmicrotime')) {
	function getmicrotime() {
		list($usec, $sec) = explode(' ', microtime());
		return ((float) $usec + (float) $sec);
	}
}

if (!function_exists('DecimalBinary2Float')) {
	function DecimalBinary2Float($binarynumerator) {
		$numerator   = Bin2Dec($binarynumerator);
		$denominator = Bin2Dec(str_repeat('1', strlen($binarynumerator)));
		return ($numerator / $denominator);
	}
}

if (!function_exists('NormalizeBinaryPoint')) {
	function NormalizeBinaryPoint($binarypointnumber, $maxbits=52) {
		// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/binary.html
		if (strpos($binarypointnumber, '.') === FALSE) {
			$binarypointnumber = '0.'.$binarypointnumber;
		} else if ($binarypointnumber{0} == '.') {
			$binarypointnumber = '0'.$binarypointnumber;
		}
		$exponent = 0;
		while (($binarypointnumber{0} != '1') || (substr($binarypointnumber, 1, 1) != '.')) {
			if (substr($binarypointnumber, 1, 1) == '.') {
				$exponent--;
				$binarypointnumber = substr($binarypointnumber, 2, 1).'.'.substr($binarypointnumber, 3);
			} else {
				$pointpos = strpos($binarypointnumber, '.');
				$exponent += ($pointpos - 1);
				$binarypointnumber = str_replace('.', '', $binarypointnumber);
				$binarypointnumber = $binarypointnumber{0}.'.'.substr($binarypointnumber, 1);
			}
		}
		$binarypointnumber = str_pad(substr($binarypointnumber, 0, $maxbits + 2), $maxbits + 2, '0', STR_PAD_RIGHT);
		return array('normalized'=>$binarypointnumber, 'exponent'=>(int) $exponent);
	}
}

if (!function_exists('Float2BinaryDecimal')) {
	function Float2BinaryDecimal($floatvalue) {
		// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/binary.html
		$maxbits=128; // to how many bits of precision should the calculations be taken?
		$intpart   = trunc($floatvalue);
		$floatpart = abs($floatvalue - $intpart);
		$pointbitstring = '';
		while (($floatpart != 0) && (strlen($pointbitstring) < $maxbits)) {
			$floatpart *= 2;
			$pointbitstring .= (string) trunc($floatpart);
			$floatpart -= trunc($floatpart);
		}
		$binarypointnumber = decbin($intpart).'.'.$pointbitstring;
		return $binarypointnumber;
	}
}

if (!function_exists('Float2String')) {
	function Float2String($floatvalue, $bits) {
		// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/ieee-expl.html
		if (($bits != 32) && ($bits != 64)) {
			return FALSE;
		} else if ($bits == 32) {
			$exponentbits = 8;
			$fractionbits = 23;
		} else if ($bits == 64) {
			$exponentbits = 11;
			$fractionbits = 52;
		}
		if ($floatvalue >= 0) {
			$signbit = '0';
		} else {
			$signbit = '1';
		}
		$normalizedbinary = NormalizeBinaryPoint(Float2BinaryDecimal($floatvalue), $fractionbits);
		$biasedexponent = pow(2, $exponentbits - 1) - 1 + $normalizedbinary['exponent']; // (127 or 1023) +/- exponent
		$exponentbitstring = str_pad(decbin($biasedexponent), $exponentbits, '0', STR_PAD_LEFT);
		$fractionbitstring = str_pad(substr($normalizedbinary['normalized'], 2), $fractionbits, '0', STR_PAD_RIGHT);

		return BigEndian2String(Bin2Dec($signbit.$exponentbitstring.$fractionbitstring), $bits % 8, FALSE);
	}
}

if (!function_exists('LittleEndian2Float')) {
	function LittleEndian2Float($byteword) {
		return BigEndian2Float(strrev($byteword));
	}
}

if (!function_exists('BigEndian2Float')) {
	function BigEndian2Float($byteword) {
		// ANSI/IEEE Standard 754-1985, Standard for Binary Floating Point Arithmetic
		// http://www.psc.edu/general/software/packages/ieee/ieee.html
		// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/ieee.html

		$bitword = BigEndian2Bin($byteword);
		$signbit = $bitword{0};
		if (strlen($byteword) == 4) { // 32-bit DWORD
			$exponentbits = 8;
			$fractionbits = 23;
		} else if (strlen($byteword) == 8) { // 64-bit QWORD
			$exponentbits = 11;
			$fractionbits = 52;
		} else {
			return FALSE;
		}
		$exponentstring = substr($bitword, 1, $exponentbits);
		$fractionstring = substr($bitword, 9, $fractionbits);
		$exponent = Bin2Dec($exponentstring);
		$fraction = Bin2Dec($fractionstring);
		if (($exponent == (pow(2, $exponentbits) - 1)) && ($fraction != 0)) {
			// Not a Number
			$floatvalue = FALSE;
		} else if (($exponent == (pow(2, $exponentbits) - 1)) && ($fraction == 0)) {
			if ($signbit == '1') {
				$floatvalue = '-infinity';
			} else {
				$floatvalue = '+infinity';
			}
		} else if (($exponent == 0) && ($fraction == 0)) {
			if ($signbit == '1') {
				$floatvalue = -0;
			} else {
				$floatvalue = 0;
			}
			$floatvalue = ($signbit ? 0 : -0);
		} else if (($exponent == 0) && ($fraction != 0)) {
			// These are 'unnormalized' values
			$floatvalue = pow(2, (-1 * (pow(2, $exponentbits - 1) - 2))) * DecimalBinary2Float($fractionstring);
			if ($signbit == '1') {
				$floatvalue *= -1;
			}
		} else if ($exponent != 0) {
			$floatvalue = pow(2, ($exponent - (pow(2, $exponentbits - 1) - 1))) * (1 + DecimalBinary2Float($fractionstring));
			if ($signbit == '1') {
				$floatvalue *= -1;
			}
		}
		return (float) $floatvalue;
	}
}

if (!function_exists('BigEndian2Int')) {
	function BigEndian2Int($byteword, $synchsafe=FALSE, $signed=FALSE) {
		$intvalue = 0;
		$bytewordlen = strlen($byteword);
		for ($i = 0; $i < $bytewordlen; $i++) {
			if ($synchsafe) { // disregard MSB, effectively 7-bit bytes
				$intvalue = $intvalue | (ord($byteword{$i}) & 0x7F) << (($bytewordlen - 1 - $i) * 7);
			} else {
				$intvalue += ord($byteword{$i}) * pow(256, ($bytewordlen - 1 - $i));
			}
		}
		if ($signed && !$synchsafe) {
			// synchsafe ints are not allowed to be signed
			switch ($bytewordlen) {
				case 1:
				case 2:
				case 3:
				case 4:
					$signmaskbit = 0x80 << (8 * ($bytewordlen - 1));
					if ($intvalue & $signmaskbit) {
						$intvalue = 0 - ($intvalue & ($signmaskbit - 1));
					}
					break;

				default:
					die('ERROR: Cannot have signed integers larger than 32-bits in BigEndian2Int()');
					break;
			}
		}
		return CastAsInt($intvalue);
	}
}

if (!function_exists('LittleEndian2Int')) {
	function LittleEndian2Int($byteword, $signed=FALSE) {
		return BigEndian2Int(strrev($byteword), FALSE, $signed);
	}
}

if (!function_exists('BigEndian2Bin')) {
	function BigEndian2Bin($byteword) {
		$binvalue = '';
		$bytewordlen = strlen($byteword);
		for ($i = 0; $i < $bytewordlen; $i++) {
			$binvalue .= str_pad(decbin(ord($byteword{$i})), 8, '0', STR_PAD_LEFT);
		}
		return $binvalue;
	}
}

if (!function_exists('BigEndian2String')) {
	function BigEndian2String($number, $minbytes=1, $synchsafe=FALSE, $signed=FALSE) {
		if ($number < 0) {
			return FALSE;
		}
		$maskbyte = (($synchsafe || $signed) ? 0x7F : 0xFF);
		$intstring = '';
		if ($signed) {
			if ($minbytes > 4) {
				die('ERROR: Cannot have signed integers larger than 32-bits in BigEndian2String()');
			}
			$number = $number & (0x80 << (8 * ($minbytes - 1)));
		}
		while ($number != 0) {
			$quotient = ($number / ($maskbyte + 1));
			$intstring = chr(ceil(($quotient - floor($quotient)) * $maskbyte)).$intstring;
			$number = floor($quotient);
		}
		return str_pad($intstring, $minbytes, chr(0), STR_PAD_LEFT);
	}
}

if (!function_exists('Dec2Bin')) {
	function Dec2Bin($number) {
		while ($number >= 256) {
			$bytes[] = (($number / 256) - (floor($number / 256))) * 256;
			$number = floor($number / 256);
		}
		$bytes[] = $number;
		$binstring = '';
		for ($i = 0; $i < count($bytes); $i++) {
			$binstring = (($i == count($bytes) - 1) ? decbin($bytes["$i"]) : str_pad(decbin($bytes["$i"]), 8, '0', STR_PAD_LEFT)).$binstring;
		}
		return $binstring;
	}
}

if (!function_exists('Bin2Dec')) {
	function Bin2Dec($binstring) {
		$decvalue = 0;
		for ($i = 0; $i < strlen($binstring); $i++) {
			$decvalue += ((int) substr($binstring, strlen($binstring) - $i - 1, 1)) * pow(2, $i);
		}
		return CastAsInt($decvalue);
	}
}

if (!function_exists('Bin2String')) {
	function Bin2String($binstring) {
		// return 'hi' for input of '0110100001101001'
		$string = '';
		$binstringreversed = strrev($binstring);
		for ($i = 0; $i < strlen($binstringreversed); $i += 8) {
			$string = chr(Bin2Dec(strrev(substr($binstringreversed, $i, 8)))).$string;
		}
		return $string;
	}
}

if (!function_exists('LittleEndian2String')) {
	function LittleEndian2String($number, $minbytes=1, $synchsafe=FALSE) {
		while ($number > 0) {
			if ($synchsafe) {
				$intstring = $intstring.chr($number & 127);
				$number >>= 7;
			} else {
				$intstring = $intstring.chr($number & 255);
				$number >>= 8;
			}
		}
		return $intstring;
	}
}

if (!function_exists('Bool2IntString')) {
	function Bool2IntString($intvalue) {
		if ($intvalue) {
			return '1';
		} else {
			return '0';
		}
	}
}

if (!function_exists('IntString2Bool')) {
	function IntString2Bool($char) {
		if ($char == '1') {
			return TRUE;
		} else if ($char == '0') {
			return FALSE;
		}
	}
}

if (!function_exists('DeUnSynchronise')) {
	function DeUnSynchronise($data) {
		return str_replace(chr(0xFF).chr(0x00), chr(0xFF), $data);
	}
}

if (!function_exists('Unsynchronise')) {
	function Unsynchronise($data) {
		// Whenever a false synchronisation is found within the tag, one zeroed
		// byte is inserted after the first false synchronisation byte. The
		// format of a correct sync that should be altered by ID3 encoders is as
		// follows:
		// 	 %11111111 111xxxxx
		// And should be replaced with:
		// 	 %11111111 00000000 111xxxxx
		// This has the side effect that all $FF 00 combinations have to be
		// altered, so they won't be affected by the decoding process. Therefore
		// all the $FF 00 combinations have to be replaced with the $FF 00 00
		// combination during the unsynchronisation.

		$data = str_replace(chr(0xFF).chr(0x00), chr(0xFF).chr(0x00).chr(0x00), $data);
		$unsyncheddata = '';
		for ($i = 0; $i < strlen($data); $i++) {
			$thischar = $data{$i};
			$unsyncheddata .= $thischar;
			if ($thischar == chr(255)) {
				$nextchar = ord(substr($data, $i + 1, 1));
				if (($nextchar | 0xE0) == 0xE0) {
					// previous byte = 11111111, this byte = 111?????
					$unsyncheddata .= chr(0);
				}
			}
		}
		return $unsyncheddata;
	}
}

if (!function_exists('is_hash')) {
	function is_hash($var) {
		// written by dev-null@christophe.vg
		// taken from http://www.php.net/manual/en/function.array-merge-recursive.php
		if (is_array($var)) {
			$keys = array_keys($var);
			$all_num = true;
			for ($i = 0; $i < count($keys); $i++) {
				if (is_string($keys["$i"])) {
					return true;
				}
			}
		}
		return false;
	}
}

if (!function_exists('array_join_merge')) {
	function array_join_merge($arr1, $arr2) {
		// written by dev-null@christophe.vg
		// taken from http://www.php.net/manual/en/function.array-merge-recursive.php
		if (is_array($arr1) && is_array($arr2)) {
			// the same -> merge
			$new_array = array();

			if (is_hash($arr1) && is_hash($arr2)) {
				// hashes -> merge based on keys
				$keys = array_merge(array_keys($arr1), array_keys($arr2));
				foreach ($keys as $key) {
					$new_array["$key"] = array_join_merge($arr1["$key"], $arr2["$key"]);
				}
			} else {
				// two real arrays -> merge
				$new_array = array_reverse(array_unique(array_reverse(array_merge($arr1,$arr2))));
			}
			return $new_array;
	 	} else {
			// not the same ... take new one if defined, else the old one stays
			return $arr2 ? $arr2 : $arr1;
		}
	}
}

if (!function_exists('RoughTranslateUnicodeToASCII')) {
	function RoughTranslateUnicodeToASCII($rawdata, $frame_textencoding) {
		// rough translation of data for application that can't handle Unicode data

		$tempstring = '';
		switch ($frame_textencoding) {
			case 0: // ISO-8859-1. Terminated with $00.
				$asciidata = $rawdata;
				break;

			case 1: // UTF-16 encoded Unicode with BOM. Terminated with $00 00.
				$asciidata = $rawdata;
				if (substr($asciidata, 0, 2) == chr(0xFF).chr(0xFE)) {
					$asciidata = substr($asciidata, 2);                       // remove BOM, only if present (it should be, but...)
				}
				if (substr($asciidata, strlen($asciidata) - 2, 2) == chr(0).chr(0)) {
					$asciidata = substr($asciidata, 0, strlen($asciidata) - 2); // remove terminator, only if present (it should be, but...)
				}
				for ($i = 0; $i < strlen($asciidata); $i += 2) {
					if ((ord($asciidata{$i}) <= 0x7F) || (ord($asciidata{$i}) >= 0xA0)) {
						$tempstring .= $asciidata{$i};
					} else {
						$tempstring .= '?';
					}
				}
				$asciidata = $tempstring;
				break;

			case 2: // UTF-16BE encoded Unicode without BOM. Terminated with $00 00.
				$asciidata = $rawdata;
				if (substr($asciidata, strlen($asciidata) - 2, 2) == chr(0).chr(0)) {
					$asciidata = substr($asciidata, 0, strlen($asciidata) - 2); // remove terminator, only if present (it should be, but...)
				}
				for ($i = 0; $i < strlen($asciidata); $i += 2) {
					if ((ord($asciidata{$i}) <= 0x7F) || (ord($asciidata{$i}) >= 0xA0)) {
						$tempstring .= $asciidata{$i};
					} else {
						$tempstring .= '?';
					}
				}
				$asciidata = $tempstring;
				break;

			case 3: // UTF-8 encoded Unicode. Terminated with $00.
				$asciidata = utf8_decode($rawdata);
				break;

			default:
				// shouldn't happen, but in case $frame_textencoding is not 1 <= $frame_textencoding <= 4
				// just pass the data through unchanged.
				$asciidata = $rawdata;
				break;
		}
		if (substr($asciidata, strlen($asciidata) - 1, 1) == chr(0)) {
			// remove null terminator, if present
			$asciidata = NoNullString($asciidata);
		}
		return $asciidata;
		// return str_replace(chr(0), '', $asciidata); // just in case any nulls slipped through
	}
}

if (!function_exists('PlaytimeString')) {
	function PlaytimeString($playtimeseconds) {
		$contentseconds = round((($playtimeseconds / 60) - floor($playtimeseconds / 60)) * 60);
		$contentminutes = floor($playtimeseconds / 60);
		return number_format($contentminutes).':'.str_pad($contentseconds, 2, 0, STR_PAD_LEFT);
	}
}

if (!function_exists('CloseMatch')) {
	function CloseMatch($value1, $value2, $tolerance) {
		return (abs($value1 - $value2) <= $tolerance);
	}
}

if (!function_exists('ID3v1matchesID3v2')) {
	function ID3v1matchesID3v2($id3v1, $id3v2) {

		$requiredindices = array('title', 'artist', 'album', 'year', 'genre', 'comment');
		foreach ($requiredindices as $requiredindex) {
			if (!isset($id3v1["$requiredindex"])) {
				$id3v1["$requiredindex"] = '';
			}
			if (!isset($id3v2["$requiredindex"])) {
				$id3v2["$requiredindex"] = '';
			}
		}

		if (trim($id3v1['title']) != trim(substr($id3v2['title'], 0, 30))) {
			return FALSE;
		}
		if (trim($id3v1['artist']) != trim(substr($id3v2['artist'], 0, 30))) {
			return FALSE;
		}
		if (trim($id3v1['album']) != trim(substr($id3v2['album'], 0, 30))) {
			return FALSE;
		}
		if (trim($id3v1['year']) != trim(substr($id3v2['year'], 0, 4))) {
			return FALSE;
		}
		if (trim($id3v1['genre']) != trim($id3v2['genre'])) {
			return FALSE;
		}
		if (isset($id3v1['track'])) {
			if (!isset($id3v1['track']) || (trim($id3v1['track']) != trim($id3v2['track']))) {
				return FALSE;
			}
			if (trim($id3v1['comment']) != trim(substr($id3v2['comment'], 0, 28))) {
				return FALSE;
			}
		} else {
			if (trim($id3v1['comment']) != trim(substr($id3v2['comment'], 0, 30))) {
				return FALSE;
			}
		}
		return TRUE;
	}
}

if (!function_exists('FILETIMEtoUNIXtime')) {
	function FILETIMEtoUNIXtime($FILETIME, $round=TRUE) {
		// FILETIME is a 64-bit unsigned integer representing
		// the number of 100-nanosecond intervals since January 1, 1601
		// UNIX timestamp is number of seconds since January 1, 1970
		// 116444736000000000 = 10000000 * 60 * 60 * 24 * 365 * 369 + 89 leap days
		if ($round) {
			return round(($FILETIME - 116444736000000000) / 10000000);
		} else {
			return ($FILETIME - 116444736000000000) / 10000000;
		}
	}
}

if (!function_exists('GUIDtoBytestring')) {
	function GUIDtoBytestring($GUIDstring) {
		// Microsoft defines these 16-byte (128-bit) GUIDs in the strangest way:
		// first 4 bytes are in little-endian order
		// next 2 bytes are appended in little-endian order
		// next 2 bytes are appended in little-endian order
		// next 2 bytes are appended in big-endian order
		// next 6 bytes are appended in big-endian order

		// AaBbCcDd-EeFf-GgHh-IiJj-KkLlMmNnOoPp is stored as this 16-byte string:
		// $Dd $Cc $Bb $Aa $Ff $Ee $Hh $Gg $Ii $Jj $Kk $Ll $Mm $Nn $Oo $Pp

		$hexbytecharstring  = chr(hexdec(substr($GUIDstring,  6, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring,  4, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring,  2, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring,  0, 2)));

		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 11, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring,  9, 2)));

		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 16, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 14, 2)));

		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 19, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 21, 2)));

		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 24, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 26, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 28, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 30, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 32, 2)));
		$hexbytecharstring .= chr(hexdec(substr($GUIDstring, 34, 2)));

		return $hexbytecharstring;
	}
}

if (!function_exists('BytestringToGUID')) {
	function BytestringToGUID($Bytestring) {
		$GUIDstring  = strtoupper(str_pad(dechex(ord($Bytestring{3})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{2})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{1})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{0})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= '-';
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{5})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{4})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= '-';
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{7})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{6})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= '-';
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{8})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{9})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= '-';
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{10})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{11})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{12})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{13})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{14})), 2, '0', STR_PAD_LEFT));
		$GUIDstring .= strtoupper(str_pad(dechex(ord($Bytestring{15})), 2, '0', STR_PAD_LEFT));

		return $GUIDstring;
	}
}

if (!function_exists('BitrateColor')) {
	function BitrateColor($bitrate) {
		$bitrate--;
		$bitrate = max($bitrate, 0);
		$bitrate = min($bitrate, 255);
		//$bitrate = max($bitrate, 32);
		//$bitrate = min($bitrate, 143);
		//$bitrate = ($bitrate * 2) - 32;

		$Rcomponent = max(255 - ($bitrate * 2), 0);
		$Gcomponent = max(($bitrate * 2) - 255, 0);
		if ($bitrate > 127) {
			$Bcomponent = max((255 - $bitrate) * 2, 0);
		} else {
			$Bcomponent = max($bitrate * 2, 0);
		}
		return str_pad(dechex($Rcomponent), 2, '0', STR_PAD_LEFT).str_pad(dechex($Gcomponent), 2, '0', STR_PAD_LEFT).str_pad(dechex($Bcomponent), 2, '0', STR_PAD_LEFT);
	}
}

if (!function_exists('BitrateText')) {
	function BitrateText($bitrate) {
		return '<SPAN STYLE="color: #'.BitrateColor($bitrate).'">'.round($bitrate).' kbps</SPAN>';
	}
}

if (!function_exists('image_type_to_mime_type')) {
	function image_type_to_mime_type($imagetypeid) {
		// only available in PHP v4.?.?+
		static $image_type_to_mime_type = array();
		if (count($image_type_to_mime_type) < 1) {
			$image_type_to_mime_type[1]  = 'image/gif';
			$image_type_to_mime_type[2]  = 'image/jpeg';
			$image_type_to_mime_type[3]  = 'image/png';
			$image_type_to_mime_type[4]  = 'application/x-shockwave-flash';
			//$image_type_to_mime_type[5]  = 'image/psd';
			//$image_type_to_mime_type[6]  = 'image/bmp';
			$image_type_to_mime_type[7]  = 'image/tiff'; // little-endian (Intel)
			$image_type_to_mime_type[8]  = 'image/tiff'; // big-endian (Motorola)
			//$image_type_to_mime_type[9]  = 'image/jpc';
			//$image_type_to_mime_type[10] = 'image/jp2';
			//$image_type_to_mime_type[11] = 'image/jpx';
			//$image_type_to_mime_type[12] = 'image/jb2';
			//$image_type_to_mime_type[13] = 'image/swc';
			//$image_type_to_mime_type[14] = 'image/iff';
		}
		return (isset($image_type_to_mime_type["$imagetypeid"]) ? $image_type_to_mime_type["$imagetypeid"] : '');
	}
}

if (!function_exists('utf8_decode')) {
	// PHP has this function built-in if it's configured with the --with-xml option
	// This version of the function is only provided in case XML isn't installed
	function utf8_decode($utf8text) {
		// http://www.php.net/manual/en/function.utf8-encode.php
		// bytes  bits  representation
		//   1     7    0bbbbbbb
		//   2     11   110bbbbb 10bbbbbb
		//   3     16   1110bbbb 10bbbbbb 10bbbbbb
		//   4     21   11110bbb 10bbbbbb 10bbbbbb 10bbbbbb

		$utf8length = strlen($utf8text);
		$decodedtext = '';
		for ($i = 0; $i < $utf8length; $i++) {
			if ((ord($utf8text{$i}) & 0x80) == 0) {
				$decodedtext .= $utf8text{$i};
			} else if ((ord($utf8text{$i}) & 0xF0) == 0xF0) {
				$decodedtext .= '?';
				$i += 3;
			} else if ((ord($utf8text{$i}) & 0xE0) == 0xE0) {
				$decodedtext .= '?';
				$i += 2;
			} else if ((ord($utf8text{$i}) & 0xC0) == 0xC0) {
				//   2     11   110bbbbb 10bbbbbb
				$decodedchar = Bin2Dec(substr(Dec2Bin(ord($utf8text{$i})), 3, 5).substr(Dec2Bin(ord($utf8text{($i + 1)})), 2, 6));
				if ($decodedchar <= 255) {
					$decodedtext .= chr($decodedchar);
				} else {
					$decodedtext .= '?';
				}
				$i += 1;
			}
		}
		return $decodedtext;
	}
}

if (!function_exists('DateMac2Unix')) {
	function DateMac2Unix($macdate) {
		// Macintosh timestamp: seconds since 00:00h January 1, 1904
		// UNIX timestamp:      seconds since 00:00h January 1, 1970
		return CastAsInt($macdate - 2082844800);
	}
}


if (!function_exists('FixedPoint8_8')) {
	function FixedPoint8_8($rawdata) {
		return BigEndian2Int(substr($rawdata, 0, 1)) + (float) (BigEndian2Int(substr($rawdata, 1, 1)) / pow(2, 8));
	}
}


if (!function_exists('FixedPoint16_16')) {
	function FixedPoint16_16($rawdata) {
		return BigEndian2Int(substr($rawdata, 0, 2)) + (float) (BigEndian2Int(substr($rawdata, 2, 2)) / pow(2, 16));
	}
}


if (!function_exists('FixedPoint2_30')) {
	function FixedPoint2_30($rawdata) {
		$binarystring = BigEndian2Bin($rawdata);
		return Bin2Dec(substr($binarystring, 0, 2)) + (float) (Bin2Dec(substr($binarystring, 2, 30)) / pow(2, 30));
	}
}


if (!function_exists('Pascal2String')) {
	function Pascal2String($pascalstring) {
		// Pascal strings have 1 byte at the beginning saying how many chars are in the string
		return substr($pascalstring, 1);
	}
}

if (!function_exists('NoNullString')) {
	function NoNullString($nullterminatedstring) {
		// remove the single null terminator on null terminated strings
		return substr($nullterminatedstring, 0, strlen($nullterminatedstring) - 1);
	}
}

?>