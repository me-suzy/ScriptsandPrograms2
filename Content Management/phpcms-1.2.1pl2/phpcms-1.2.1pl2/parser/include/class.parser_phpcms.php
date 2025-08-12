<?php
/* $Id: class.parser_phpcms.php,v 1.11.2.18 2004/08/18 14:58:34 bjmg Exp $ */
/*
   +----------------------------------------------------------------------+
   | phpCMS Content Management System - Version 1.2.0
   +----------------------------------------------------------------------+
   | phpCMS is Copyright (c) 2001-2003 by Michael Brauchl
   | and Contributing phpCMS Team Members
   +----------------------------------------------------------------------+
   | This program is free software; you can redistribute it and/or modify
   | it under the terms of the GNU General Public License as published by
   | the Free Software Foundation; either version 2 of the License, or
   | (at your option) any later version.
   |
   | This program is distributed in the hope that it will be useful, but
   | WITHOUT ANY WARRANTY; without even the implied warranty of
   | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   | General Public License for more details.
   |
   | You should have received a copy of the GNU General Public License
   | along with this program; if not, write to the Free Software
   | Foundation, Inc., 59 Temple Place - Suite 330, Boston,
   | MA  02111-1307, USA.
   +----------------------------------------------------------------------+
   | Original Author: Michael Brauchl (mcyra)
   | Contributors:
   |    Martin Jahn (mjahn)
   |    Henning Poerschke (hpoe)
   |    Markus Richert (e157m369)
   |    Wolfgang Ulmer (wulmer)
   |    Thilo Wagner (ignatius0815)
   +----------------------------------------------------------------------+
*/


if(!defined("_PAX_")) {
	include($PHPCMS_INCLUDEPATH.'/class.pax_phpcms.php');
}

include($PHPCMS_INCLUDEPATH.'/class.parser_file_phpcms.php');
include($PHPCMS_INCLUDEPATH.'/class.parser_menu_phpcms.php');
include($PHPCMS_INCLUDEPATH.'/class.parser_page_phpcms.php');
include($PHPCMS_INCLUDEPATH.'/class.parser_template_phpcms.php');

class helper {
	var $parm = array();

	function helper() {
		return;
	}

	function SplitLine($Line, $StartValue, $EndValue) {
		$PosStartValue = strpos($Line, $StartValue);

		$Result[0] = substr($Line, 0, $PosStartValue);
		$temp = substr($Line, $PosStartValue + strlen($StartValue));
		$PosEndValue = strpos($temp, $EndValue);
		$Result[1] = substr($temp, 0, $PosEndValue);
		$Result[2] = substr($temp, $PosEndValue + strlen($EndValue));

		return $Result;
	}

	function tag_value_replace($key, $value, $line) {
		global $DEFAULTS;
		$oldval = $value;
		// set Tag-Begin
		$Tag_Start = substr($key, 0, strpos($key, '"'));
		// set Tag-Variables
		$Tag_Vars = substr($key, strpos($key, '"') + 1);
		// set Tag-End
		$Tag_End = substr($Tag_Vars, strpos($Tag_Vars, '"') + 1);
		$Tag_Vars = substr($Tag_Vars, 0, strpos($Tag_Vars, '"'));

		if(strpos(strtoupper($line), strtoupper($Tag_Start.'"')) === false) {
			return $line;
		}

		// find Tag-begin in Line
		// put all before the Tag in temp-variable $PartOne
		$PartOne = substr($line, 0, strpos($line, $Tag_Start.'"'));
		$PartTwo = substr($line, strpos($line, $Tag_Start.'"') + strlen($Tag_Start.'"') + 1);

		// put all after the Tag in temp-variable $PartTwo
		$PartTwo = substr($PartTwo, strpos($PartTwo, $Tag_End) + strlen($Tag_End));
		$ToReplace = substr($line, strpos($line, $Tag_Start) + strlen($Tag_Start) + 1);

		$ToReplace = substr($ToReplace, 0, strpos($ToReplace, $Tag_End) - 1);
		$RepArray = explode($DEFAULTS->TAG_DELIMITER, $ToReplace);
		// catch value from Tag-Begin to Tag-End from the Line and set the Tag-Variables $1 - $x
		$VarArray = explode($DEFAULTS->TAG_DELIMITER, $Tag_Vars);
		for($i = 0; $i < count($VarArray); $i++) {
			$var = trim ( $VarArray[$i] );
			$rep = trim ( $RepArray[$i] );
			$value = str_replace($var, $rep, $value);
		}
		// replace values in Tag-Value
		// check for further Tags in Temp-Variable
		if(strpos($PartTwo, $Tag_Start) !== false) {
			$PartTwo = $this->tag_value_replace($key, $oldval, $PartTwo);
		}

		// supporting Tag-Variables to PAX
		$tempStart = strtr(trim($Tag_Start), ' <=>\\$\'','_______');
		if(!isset($GLOBALS['myTagVars'][$tempStart]['counter'])) {
			$GLOBALS['myTagVars'][$tempStart]['counter'] = 0;
		}
		for($i = 0; $i < count($VarArray); $i++) {
			$tempVar = strtr(trim($VarArray[$i]), ' <=>\\$\'', '_______');
			$GLOBALS['myTagVars'][$tempStart][$GLOBALS['myTagVars'][$tempStart]['counter']][$tempVar] = $RepArray[$i];
		}
		$GLOBALS['myTagVars'][$tempStart]['counter']++;

		// put the output-line together
		return $PartOne.$value.$PartTwo;
	}

	function checkTags($tags) {
		global $DEFAULTS;
		/*
		created: 2003-03-05 (ignatius0815)
		changed: --
		purpose: checks the syntax of the tags in the tag-array
		*/
		$this->temp1 = count($tags);
		for($j = 0; $j < $this->temp1; $j++) {
			if(strlen($tags[$j][0]) == 0) {
				ExitError(15,$tags[$j][1]);
			}
			if($DEFAULTS->TAGS_ERROR == 'on' AND $tags[$j][1] === '' AND $tags[$j][0] != '$home') {
				ExitError(16, $tags[$j][0]);
			}
			if(strpos($tags[$j][0], '"') === false) {
				$this->parm[$j] = true;
			} else {
				$this->parm[$j] = false;
			}
		}
	}

	function ChangeTags($line, $tags) {
		global $DEFAULTS;

		// check syntax of tags if REREAD_TAGS ist true
		// (that's the case in the first time ChangeTags is running and
		// after a plugin or the search-function was executed)
		if(!isset($DEFAULTS->REREAD_TAGS) OR $DEFAULTS->REREAD_TAGS == true) {
			$this->checkTags($tags);
			$DEFAULTS->REREAD_TAGS = false;
		}
		if(!isset($this->temp1)) {
			$this->temp1 = 0;
		}
		// make changes
		for($j = 0; $j < $this->temp1; $j++) {
			if($this->parm[$j]) {
				if(strpos($line, $tags[$j][0]) === false) {
					continue;
				}
				if($tags[$j][1] === false) {
					$line = str_replace($tags[$j][0], '', $line);
				} else {
					$line = str_replace($tags[$j][0], $tags[$j][1], $line);
				}
			} else {
				$line = $this->tag_value_replace($tags[$j][0], $tags[$j][1], $line);
			}
		}
		$line = $this->ChangeURL($line);
		return $line;
	}

	function ChangeURL($line) {
		/*
		create author: mcyra
		create date:
		change author: hpoe
		change date: 2003-01-11, 2003-06-08
		purpose:
		*/
		global $DEFAULTS;

		$line_uc = strtoupper($line);
		if(strpos($line_uc, '<HTML') !== false) {
			$line = $this->addIdentTag($line); //	Add phpCMS IdentTag
		}
		if($DEFAULTS->STEALTH == 'on') {
			return $line;
		}
		if(strpos($line_uc, '<IMG SRC=') !== false) {
			$line = $this->ChangeIMG($line, '<IMG SRC=');
		}
		if(strpos($line_uc, 'BACKGROUND=') !== false) {
			$line = $this->ChangeIMG($line, 'BACKGROUND=');
		}
		if(strpos($line_uc, '<A HREF=') !== false) {
			$line = $this->ChangeHREF($line, '<A HREF=');
		}
		if(strpos($line_uc, '<FORM') !== false) {
			$line = $this->ChangeFORM($line);
		}
		if(strpos($line_uc, '@IMPORT') !== false) {
			$line = $this->ChangeCSS($line, $line_uc, '@IMPORT');
		}
		return $line;
	}

//BOF	Add phpCMS IdentTag via RegExp
	function addIdentTag($line) {
		/*
		create author: hpoe
		create date: 2003-01-11
		change author:
		change date:
		purpose: Adds a "parsed by phpCMS" comment to html all files
		*/
		global $DEFAULTS;

		if(preg_match("/<html[^>]*?.*?[^<>]*?>/si",$line,$htmltag )) { //<?
			if($DEFAULTS->PAX == 'on') {
				$line = $htmltag[0]."\n".'<!-- parsed by phpCMS '.$DEFAULTS->VERSION.' and preparsed with PAX. Get phpCMS at http://phpcms.de -->'."\n";
			} else {
				$line = $htmltag[0]."\n".'<!-- parsed by phpCMS '.$DEFAULTS->VERSION.'. Get phpCMS at http://phpcms.de -->'."\n";
			}
		}
	return $line;
	}
// EOF Add phpCMS IdentTag

	function ChangeCSS($line, $line_uc, $tag ) {
		global $CHECK_PAGE;

		if(strpos($line_uc, $tag.' "') !== false) {
			$tag_a = $tag.' "';
			$tag_o = '"';
		}
		elseif(strpos($line_uc, $tag.' URL("') !== false) {
			$tag_a = $tag.' URL("';
			$tag_o = '")';
		}
		elseif(strpos($line_uc, $tag.' URL(') !== false) {
			$tag_a = $tag.' URL(';
			$tag_o = ')';
		}
		if(strpos($line_uc, $tag_a) !== false) {
			
			$TagPos = strpos($line_uc, $tag_a);
			$PartOne = substr($line, 0, $TagPos + strlen($tag_a));
			$temp = substr($line, $TagPos + strlen($tag_a));
			$PartTwo = substr($temp, strpos($temp, $tag_o));
			
			$PartTwo_uc = strtoupper($PartTwo);
			if(strpos($PartTwo_uc, $tag) !== false) {
				$PartTwo = $this->ChangeCSS($PartTwo, $PartTwo_uc, $tag);
			}
			$url = trim(substr($temp, 0, strpos($temp, $tag_o)));

			if(strtoupper(substr($url, 0, 4)) == 'HTTP') {
				return $line;
			}
			if(substr($url, 0, 1) != "/") {
				$url = $CHECK_PAGE->path.'/'.$url;
			}
			return $PartOne.$url.$PartTwo;
		} else {
			return $line;
		}
	}

	function ChangeFORM($line) {
		global $CHECK_PAGE, $DEFAULTS;

		$tag = 'ACTION=';
		$PreUrl = $DEFAULTS->SCRIPT_PATH.'/'.$DEFAULTS->SCRIPT_NAME.'?file=';
		$TagPos = strpos(strtoupper($line), $tag.'"');
		if($TagPos === false) {
			return $line;
		} else {
			$PartOne = substr($line, 0, $TagPos + strlen($tag.'"'));
			$temp = substr($line, $TagPos + strlen($tag.'"'));
			$PartTwo = substr($temp, strpos($temp, '"'));
			if(strpos(strtoupper($PartTwo), $tag) !== false) {
				$PartTwo = $this->ChangeFORM($PartTwo, $tag);
			}
			$url = substr($temp, 0, strpos($temp, '"'));
			$url_uc = strtoupper($url);

			if(substr($url_uc, 0, 10) == 'JAVASCRIPT') {
				return $PartOne.$url.$PartTwo;
			}
			if(substr($url_uc, 0, 4) == 'HTTP') {
				return $PartOne.$url.$PartTwo;
			}
			if(substr($url_uc, 0, 7) == 'MAILTO:') {
				return $PartOne.$url.$PartTwo;
			}

			$bforbid = false;
			$forbids = explode(";", $DEFAULTS->NOLINKCHANGE);
			$ii = 0; 
			$c = count($forbids);
			for($ii; $ii < $c; $ii++) {
				if(strpos($url_uc, strtoupper($forbids[$ii])) !== false) {
					$bforbid = true;
				}
			}
			if($bforbid) {
				if(substr($url, 0, 1) == '/') {
					return $PartOne.$url.$PartTwo;
				} else {
					return $PartOne.$CHECK_PAGE->path.'/'.$url.$PartTwo;
				}
			}

			if(substr($url, 0, 1) == '/') {
				if(strpos($url, $PreUrl) === false) {
					$url = $PreUrl.$url;
				}
				return $PartOne.$url.$PartTwo;
			} else {
				return $PartOne.$PreUrl.$CHECK_PAGE->path.'/'.$url.$PartTwo;
			}
		}
	} // function ChangeFORM($line)

	function ChangeHREF($line, $tag) {
		global $CHECK_PAGE, $DEFAULTS;

		$PreUrl = $DEFAULTS->SCRIPT_PATH.'/'.$DEFAULTS->SCRIPT_NAME.'?file=';
		$TagPos = strpos(strtoupper($line), $tag.'"');
		$PartOne = substr($line, 0, $TagPos + strlen($tag.'"'));
		$temp = substr($line, $TagPos + strlen($tag.'"'));
		$PartTwo = substr($temp, strpos($temp, '"'));
		if(strpos(strtoupper($PartTwo), $tag) !== false) {
			$PartTwo = $this->ChangeHREF($PartTwo, $tag);
		}
		$url = substr($temp, 0, strpos($temp, '"'));

		if(strtoupper(substr($url, 0, 10)) == 'JAVASCRIPT') {
			return $PartOne.$url.$PartTwo;
		}
		if(strtoupper(substr($url, 0, 4)) == 'HTTP') {
			return $PartOne.$url.$PartTwo;
		}
		if(strtoupper(substr($url, 0, 5)) == 'HTTPS') {
			return $PartOne.$url.$PartTwo;
		}
		if(strtoupper(substr($url, 0, 3)) == 'FTP') {
			return $PartOne.$url.$PartTwo;
		}
		if(strtoupper(substr($url, 0, 7)) == 'MAILTO:') {
			return $PartOne.$url.$PartTwo;
		}

		$bforbid = false;
		$forbids = explode(";", $DEFAULTS->NOLINKCHANGE);
		for($ii = 0; $ii < count($forbids); $ii++) {
			if(stristr($url, $forbids[$ii])) {
				$bforbid = true;
			}
			if($bforbid) {
				if(substr($url, 0, 1) == '/') {
					return $PartOne.$url.$PartTwo;
				} else {
					return $PartOne.$CHECK_PAGE->path.'/'.$url.$PartTwo;
				}
			}
		}

		if(substr($url, 0, 1) == '/') {
			if(!strstr($url, $PreUrl)) {
				$url = $PreUrl.$url;
			}
			return $PartOne.$url.$PartTwo;
		} else {
			if(substr($url, 0, 1) == '#') {
				return $PartOne.$url.$PartTwo;
			} else {
				return $PartOne.$PreUrl.$CHECK_PAGE->path.'/'.$url.$PartTwo;
			}
		}
	}

	function ChangeIMG($line, $tag) {
		global  $CHECK_PAGE;

		$TagPos = strpos(strtoupper($line), $tag.'"');
		$PartOne = substr($line, 0, $TagPos + strlen($tag.'"'));
		$temp = substr($line, $TagPos + strlen($tag.'"'));
		$PartTwo = substr($temp, strpos($temp, '"'));
		if(strpos(strtoupper($PartTwo), $tag) !== false) {
			$PartTwo = $this->ChangeIMG($PartTwo, $tag);
		}
		$url = trim(substr($temp, 0, strpos($temp, '"')));

		if(strtoupper(substr($url, 0, 4)) == 'HTTP') {
			return $line;
		}
		if(substr($url, 0, 1) != "/") {
			$url = $CHECK_PAGE->path.'/'.$url;
		}
		return $PartOne.$url.$PartTwo;
	}
}

?>