<?php

// $Id: convert.php 230 2005-11-20 10:50:32Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/*
Character Conversion file
This file helps convert possible error-causing
characters to equivalent non-error-causing ones
*/
if(!defined('WB_URL')) {
	header('Location: ../index.php');
}

$conversion_array = array(
'À'=>'A','�?'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'Ae', '&Auml;'=>'A',
'Å'=>'A','Ā'=>'A','Ą'=>'A','Ă'=>'A', 'Æ'=>'Ae',
'Ç'=>'C','Ć'=>'C','Č'=>'C','Ĉ'=>'C','Ċ'=>'C',
'Ď'=>'D','�?'=>'D','�?'=>'D',
'È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ē'=>'E',
'Ę'=>'E','Ě'=>'E','Ĕ'=>'E','Ė'=>'E',
'Ĝ'=>'G','Ğ'=>'G','Ġ'=>'G','Ģ'=>'G',
'Ĥ'=>'H','Ħ'=>'H',
'Ì'=>'I','�?'=>'I','Î'=>'I','�?'=>'I','Ī'=>'I', 'Ĩ'=>'I','Ĭ'=>'I','Į'=>'I','İ'=>'I',
'Ĳ'=>'IJ','Ĵ'=>'J','Ķ'=>'K',
'�?'=>'K','Ľ'=>'K','Ĺ'=>'K','Ļ'=>'K','Ŀ'=>'K',
'Ñ'=>'N','Ń'=>'N','Ň'=>'N','Ņ'=>'N','Ŋ'=>'N',
'Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'Oe',
'&Ouml;'=>'Oe', 'Ø'=>'O','Ō'=>'O','�?'=>'O','Ŏ'=>'O',
'Œ'=>'OE', 'Ŕ'=>'R','Ř'=>'R','Ŗ'=>'R',
'Ś'=>'S','Š'=>'S','Ş'=>'S','Ŝ'=>'S','Ș'=>'S',
'Ť'=>'T','Ţ'=>'T','Ŧ'=>'T','Ț'=>'T',
'Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'Ue','Ū'=>'U',
'&Uuml;'=>'Ue', 'Ů'=>'U','Ű'=>'U','Ŭ'=>'U','Ũ'=>'U','Ų'=>'U',
'Ŵ'=>'W', '�?'=>'Y','Ŷ'=>'Y','Ÿ'=>'Y', 'Ź'=>'Z','Ž'=>'Z','Ż'=>'Z',
'Þ'=>'T','Þ'=>'T', 'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'ae',
'&auml;'=>'ae', 'å'=>'a','�?'=>'a','ą'=>'a','ă'=>'a',
'æ'=>'ae', 'ç'=>'c','ć'=>'c','�?'=>'c','ĉ'=>'c','ċ'=>'c',
'�?'=>'d','đ'=>'d','ð'=>'d', 'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ē'=>'e',
'ę'=>'e','ě'=>'e','ĕ'=>'e','ė'=>'e', 'ƒ'=>'f',
'�?'=>'g','ğ'=>'g','ġ'=>'g','ģ'=>'g', 'ĥ'=>'h','ħ'=>'h',
'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ī'=>'i', 'ĩ'=>'i','ĭ'=>'i','į'=>'i','ı'=>'i',
'ĳ'=>'ij', 'ĵ'=>'j', 'ķ'=>'k','ĸ'=>'k', 'ł'=>'l','ľ'=>'l','ĺ'=>'l','ļ'=>'l','ŀ'=>'l',
'ñ'=>'n','ń'=>'n','ň'=>'n','ņ'=>'n','ŉ'=>'n', 'ŋ'=>'n',
'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'oe', '&ouml;'=>'oe',
'ø'=>'o','�?'=>'o','ő'=>'o','�?'=>'o', 'œ'=>'oe', 'ŕ'=>'r','ř'=>'r','ŗ'=>'r',
'š'=>'s', 'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'ue','ū'=>'u', '&uuml;'=>'ue',
'ů'=>'u','ű'=>'u','ŭ'=>'u','ũ'=>'u','ų'=>'u', 'ŵ'=>'w',
'ý'=>'y','ÿ'=>'y','ŷ'=>'y', 'ž'=>'z','ż'=>'z','ź'=>'z', 'þ'=>'t', 'ß'=>'ss', 'ſ'=>'ss',
'ä'=>'ae', 'ö'=>'oe', 'ü'=>'ue', 'Ä'=>'Ae', 'Ö'=>'Oe', 'Ü'=>'Ue'
);

?>