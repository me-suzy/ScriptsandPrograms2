<?php
/* $Id: class.parser_file_phpcms.php,v 1.5.2.29 2004/10/22 13:40:51 bjmg Exp $ */
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
   |    Tobias Dönz (tobiasd)
   |    Martin Jahn (mjahn)
   |    Henning Poerschke (hpoe)
   |    Markus Richert (e157m369)
   |    Thilo Wagner (ignatius0815)
   +----------------------------------------------------------------------+
*/


class File {
	var $lines = Array();
	var $tags = Array();

	function File($filename, $strict = 'LOCAL') {
		global $DEFAULTS;

		if($strict == 'LOCAL') {
			$text = $this->read_includes($filename);
			$ctext = count($text);
			// replace win or mac LF or/and CR to *nix
			for($i = 0; $i < $ctext; $i++) {
				// for win
				$text[$i] = str_replace("\r\n", "\n", $text[$i]);
				// for mac
				$text[$i] = str_replace("\r", "\n", $text[$i]);
			}

			if($DEFAULTS->PAX == 'on') {
				$this->lines = PAXmain($text);
			} else {
				$this->lines = $text;
			}
			unset($text);
			unset($ctext);
			unset($i);
			unset($filename);
		} else {
			$this->lines = $this->read_includes($filename);
			// $this->lines = @file($filename);
		}
	}

	function read_includes($filename) {
		global $DEFAULTS;

		if(!file_exists($filename)) {
			ExitError(20, $filename);
		}
		$temp = @file($filename);
		for($i = 0; $i < count($temp); $i++) {
			$temp[$i] = rtrim($temp[$i])."\r\n";
		}
		$lines = trim(implode("", $temp));
		unset($temp);

		$lines = preg_replace ("'<phpcms:ignore>.*?</phpcms:ignore>'si", "", $lines);
		$lines = preg_replace ("'<(/?)phpcms:noindex>'si", "<!-- $1PHPCMS_NOINDEX -->", $lines);
		$lines = preg_replace ("'<(/?)phpcms:nofollow>'si", "<!-- $1PHPCMS_NOFOLLOW -->", $lines);

		// these were the old replacements:
		// $lines = preg_replace ("'<phpcms:noindex[^>]*?".">(.*?)</phpcms:noindex>'si", "<!-- PHPCMS_NOINDEX -->$1<!-- /PHPCMS_NOINDEX -->", $lines);
		// $lines = preg_replace ("'<phpcms:ignore[^>]*?".">.*?</phpcms:ignore>'si", "", $lines);
		// $lines = preg_replace ("'<phpcms:nofollow[^>]*?".">(.*?)</phpcms:nofollow>'si", "<!-- PHPCMS_NOFOLLOW -->$1<!-- /PHPCMS_NOFOLLOW -->", $lines);

		// Search for <PHPCMS_INCLUDE $filename> tag
		preg_match_all('/<\s?PHPCMS\_INCLUDE\s+\"([^>]+)\"\s?>/is', $lines, $matches); //<?
// Is this better/faster?
		//preg_match_all("/<PHPCMS_INCLUDE \"([^\>]+)\">/s", $lines, $matches);
		if((isset($matches)) AND (isset($matches[0][0])) AND (strlen(trim($matches[0][0])) > 0)) {
			foreach($matches[1] as $k=>$include_file)
			//while(list($k, $include_file) = each($matches[1]))
				{
				// prepare filename
				if(strtoupper(substr($include_file, 0, 5)) == '$HOME') {
					if(!isset($DEFAULTS->PROJECT_HOME)) {
						ExitError(20, $include_file, 'filename contains $home, but $home is not set!');
					}
					$include_file = $DEFAULTS->PROJECT_HOME.substr($include_file, 5);
				} elseif( strtoupper(substr($include_file, 0, 10)) == '$PLUGINDIR') {
					if(!isset($DEFAULTS->PLUGINDIR)) {
						ExitError(20, $include_file, 'filename contains $plugindir, but $plugindir is not set!');
					}
					$include_file = $DEFAULTS->PLUGINDIR.substr($include_file, 10);
				}

				if(strlen($include_file) != 0) {
					if(file_exists($DEFAULTS->DOCUMENT_ROOT.$include_file)) {
						$include_file = $DEFAULTS->DOCUMENT_ROOT.$include_file;
					} else {
						ExitError(20, $DEFAULTS->DOCUMENT_ROOT.$include_file, 'file to include does not exist');
					}
					// include file
					$to_insert = $this->read_includes($include_file);
					$to_insert = implode("\n", $to_insert);
					$lines = str_replace($matches[0][$k], $to_insert, $lines);
				}
			}
		}

		$retvar = explode("\n", $lines);
		return $retvar;
	}

	function SetParas() {
		// reading and importing the tags from the tagfile
		global
			$DEFAULTS,
			$CHECK_PAGE;

		$ArrayCount = count($this->lines);
		$j = count($this->tags) - 1;
		$i = 0;
		while($ArrayCount > 0) {
			$line = trim($this->lines[$i]);
			if(substr($line, 0, strlen($DEFAULTS->COMMENT)) == $DEFAULTS->COMMENT OR strlen($line) == 0) {
				$i++;
				$ArrayCount--;
				continue;
			}
			if(stristr($line, ':=')) {
				// create new tag
				$j++;
				$this->tags[$j] = split (':=', $line);
				$this->tags[$j][0] = trim($this->tags[$j][0]);
				$this->tags[$j][1] = trim($this->tags[$j][1]);
			} else {
				// add line
				if($j > -1) {
					$this->tags[$j][1] = $this->tags[$j][1].' '.$line;
				}
			}
			$i++;
			$ArrayCount--;
		}
		$j++;

/*-----------------------------------*/
// Add some hardcoded tags
/*-----------------------------------*/
/*
 * search highlighting isn't implemented yet
 * (maybe in 1.3.0)
 *
		$this->tags[$j][0] = '<!-- SEARCH_HILITE_INFO -->';
		if(isset($DEFAULTS->SEARCH_HILITE) && $DEFAULTS->SEARCH_HILITE == 'on') {
			$this->tags[$j][1] = '<script type="text/javascript"><!--//--><![CDATA[//><!--'."\n";
			$this->tags[$j][1].= 'if(document.createElement)'."\n";
			$this->tags[$j][1].= 'document.write(\'<script type="text/javascript" src="$home/js_search_hilite_info.js"><\/script>\')'."\n";
			$this->tags[$j][1].= '//--><!]]></script>';
		} else {
			$this->tags[$j][1] = $this->tags[$j][0];
			}
		$j++;
*/

		$this->tags[$j][0] = '<LOGO_S_W>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/plain/logo_white_100x39.gif" border="0" width="100" height="39" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO_B_W>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/plain/logo_white_150x59.gif" border="0" width="150" height="59" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO_B_B>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/plain/logo_black_150x59.gif" border="0" width="150" height="59" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO_S_B>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/plain/logo_black_100x39.gif" border="0" width="100" height="39" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO_B_G>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/plain/logo_grey_150x59.gif" border="0" width="150" height="59" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO_S_G>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/plain/logo_grey_100x39.gif" border="0" width="100" height="39" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO3D_S_W>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/3d/logo_white_100x39.gif" border="0" width="100" height="39" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO3D_B_W>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/3d/logo_white_150x59.gif" border="0" width="150" height="59" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO3D_B_B>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/3d/logo_black_150x59.gif" border="0" width="150" height="59" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO3D_S_B>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/3d/logo_black_100x39.gif" border="0" width="100" height="39" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO3D_B_G>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/3d/logo_grey_150x59.gif" border="0" width="150" height="59" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<LOGO3D_S_G>';
		$this->tags[$j][1] = '<a href="http://phpcms.de/" title="Go to the website of phpCMS!">';
		$this->tags[$j][1] = $this->tags[$j][1].'<img src="http://phpcms.de/parser/gif/logo/3d/logo_grey_100x39.gif" border="0" width="100" height="39" alt="phpCMS" /></a>';
		$j++;

		$this->tags[$j][0] = '<VERSION>';
		$this->tags[$j][1] = $DEFAULTS->VERSION;
		$j++;

		if(isset($DEFAULTS->PROJECT_HOME)) {
			$this->tags[$j][0] = '$home';
			$this->tags[$j][1] = $DEFAULTS->PROJECT_HOME;
			$j++;
		}

		if(isset($DEFAULTS->PLUGINDIR)) {
			$this->tags[$j][0] = '$plugindir';
			$this->tags[$j][1] = $DEFAULTS->PLUGINDIR;
			$j++;
		}

		if(isset($DEFAULTS->EDIT) AND $DEFAULTS->EDIT == 'on') {
			$this->tags[$j][0] = '<START_EDIT>';
			$this->tags[$j][1] = '<form method="post" name="phpcmsedit" action="$self">';
			$this->tags[$j][1] = $this->tags[$j][1].'<input type="HIDDEN" name="phpcmsaction" value="edit" />';
			$j++;
			$this->tags[$j][0] = '<END_EDIT>';
			if($DEFAULTS->DOEDIT != 'on') {
				$this->tags[$j][1] = '<input type="submit" name="EDITACTION" value="EDIT" />';
			} else {
				if (isset($this->tags[$j][1])) {
					$this->tags[$j][1] .= '<input type="submit" name="EDITACTION" value="VIEW" />';
				}
				else {
					$this->tags[$j][1] = '<input type="submit" name="EDITACTION" value="VIEW" />';
				}
			}
			$this->tags[$j][1] = $this->tags[$j][1].'<input type="submit" name="EDITACTION" value="SAVE" />';
			$this->tags[$j][1] = $this->tags[$j][1].'<input type="submit" name="EDITACTION" value="LOGOUT" />';
			if(isset($DEFAULTS->EDIT_FIELDS)) {
				for($k = 0; $k < count($DEFAULTS->EDIT_FIELDS); $k++) {
					$this->tags[$j][1] = $this->tags[$j][1].'<input type="HIDDEN" name="'.$DEFAULTS->EDIT_FIELDS[$k]['name'].'" value="'.$DEFAULTS->EDIT_FIELDS[$k]['value'].'">';
				}
			}
			$this->tags[$j][1] = $this->tags[$j][1].'</form>';
			$j++;
		} else {
			$this->tags[$j][0] = '<START_EDIT>';
			$this->tags[$j][1] = false;
			$j++;
			$this->tags[$j][0] = '<END_EDIT>';
			$this->tags[$j][1] = false;
			$j++;
		}
		$this->tags[$j][0] = '$self';
		if(trim($CHECK_PAGE->path) == '/') {
			$this->tags[$j][1] = '/'.$CHECK_PAGE->name;
		} else {
			$this->tags[$j][1] = $CHECK_PAGE->path.'/'.$CHECK_PAGE->name;
		}
		$j++;
	}

    function trimField($fieldname, $numberOfLines) {
	    // belongs to function ReadFields

	    // remove leading blank lines
	    $i = 0;
		foreach($this->{$fieldname} as $value) {
			if ($value == '') {
				$i++;
			} else break;
		}
	    if($i > 0) {
		    array_splice($this->{$fieldname},0,$i);
		    $numberOfLines = $numberOfLines - $i;
	    }

	    // remove trailing blank lines
	    $i = 0;
		while($numberOfLines >=0 AND isset($this->{$fieldname}[$numberOfLines-1]) AND $this->{$fieldname}[$numberOfLines-1] == '') {
			$i--;
			$numberOfLines--;
		}
	    if($i < 0) {
		    array_splice($this->{$fieldname},$i);
	    }

	    // check if the array is now empty. In this case we have to add at least
	    // one empty element to the array
		if($numberOfLines == 0) {
			$numberOfLines = 1;
			$this->{$fieldname}[0] = '';
		}

		// add linebreaks to the end of the lines (we stripped them with rtrim)
		// Don't add a linebreak to the last item of the array
	    for($i = 0; $i < ($numberOfLines-1); $i++) {
		    $this->{$fieldname}[$i] .= "\n";
	    }

    } // function

    function splitFieldLine($line, &$left, &$middle, &$right, &$nextfield, $startpos) {
	    // belongs to function ReadFields
	    global $DEFAULTS;

	    $startadd = strlen($DEFAULTS->START_FIELD);
	    $stopadd  = strlen($DEFAULTS->STOP_FIELD);

	    $stoppos  = strpos($line, $DEFAULTS->STOP_FIELD);

		if(!is_int($stoppos)) {
			ExitError(8);
		}

		$left   = substr($line, 0, $startpos);
		$middle = substr($line, $startpos+$startadd, $stoppos-$startpos-$startadd);
		$right  = substr($line, $stoppos+$stopadd);

		$startpos = strpos($right, $DEFAULTS->START_FIELD);
		if(is_int($startpos)) {
			$nextfield = substr($right, $startpos);
			$right = substr($right, 0, $startpos);
		} else {
			$nextfield = '';
		}
    }

	function ReadFields(&$PAGE) {
		global $DEFAULTS, $PHP;

		$fieldname = '';
		$aktlineField = -1;

		$numberOfLines = count($this->lines);
		for($aktline = 0; $aktline < $numberOfLines; $aktline++) {

			$line = $this->lines[$aktline];

			// check for static html file
			if(stristr(strtolower($line),'<html')) {
				return 'html';
			}

			// check for comment lines
			if(substr($line, 0, strlen($DEFAULTS->COMMENT)) == $DEFAULTS->COMMENT) {
				continue;
			}

			$startpos = strpos($line, $DEFAULTS->START_FIELD);

			if(!is_int($startpos)) {
				// there is no new field starting in this line, so the complete line
				// belongs to the previous field
				if($fieldname != '') {
					$aktlineField++;
					$this->{$fieldname}[$aktlineField] = rtrim($line);
				}
			} else {
				// there is a startfield delimeter in this line, so we have to
				// start a new field

				while(is_int($startpos)) {

					$this->splitFieldLine($line, $left, $middle, $right, $nextfield, $startpos);

					// before the new field is startet, the old one has to be completed
					if($fieldname != '' ) {
						if($left != '') {
							// everything which is left from the start delimeter
							// still belongs to this field
							$aktlineField++;
							$this->{$fieldname}[$aktlineField] = rtrim(substr($line, 0, $startpos));
						}
						$this->trimField($fieldname, $aktlineField+1);
					} // handling of previous field

					// the prevois field is now completely finished and the new
					// one can be startet

					$fieldname = $middle;
					$line      = $nextfield;

					$aktlineField = -1;

					// check for plugin
					if(stristr($fieldname, 'PLUGIN')) {
						if(isset($PAGE->PLUGIN)) {
							$number = count($PAGE->PLUGIN);
						} else {
							$number = 0;
						}
						$PAGE->PLUGIN[$number]['path_orig'] = $PHP->ExtractValue($fieldname, 'FILE');
						$PAGE->PLUGIN[$number]['path'] = $PHP->ExtractValue($fieldname, 'FILE');
						$PAGE->PLUGIN[$number]['type'] = $PHP->ExtractValue($fieldname, 'TYPE');
						$fieldname = 'CONTENT_PLUGIN_'.$number;
					} else {
						if(strstr($fieldname, ' ')) {
							$fieldname = substr($fieldname, 0, strpos($fieldname, ' '));
						}
					}

					$aktlineField++;
					$this->{$fieldname}[$aktlineField] = rtrim($right);

					$startpos = strpos($line, $DEFAULTS->START_FIELD);
				} // while
			} // else
		} // for

		// also trim the last field
		if($fieldname != '' ) {
			$this->trimField($fieldname, $aktlineField+1);
		}

	} // function

	function check_tag($tag) {
		if(strstr($tag, '.PRE')) {
			return true;
		}
		if(strstr($tag, '.NORMAL')) {
			return true;
		}
		if(strstr($tag, '.AKTIV')) {
			return true;
		}
		if(strstr($tag, '.ACTIVE')) {
			return true;
		}
		if(strstr($tag, '.SELF')) {
			return true;
		}
		if(strstr($tag, '.BETWEEN')) {
			return true;
		}
		if(strstr($tag, '.PAST')) {
			return true;
		}
		return false;
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

	function ReadLeftFields() {
		global $DEFAULTS;

		$temp = 'dummy';
		$ArrayCount = count($this->lines);
		$j = 0;
		$i = 0;
		$set = FALSE;
		$startadd = strlen($DEFAULTS->START_FIELD);
		$stopadd = strlen($DEFAULTS->STOP_FIELD);

		while($i < $ArrayCount) {
			$line = $this->lines[$i];
			// filter out comments
			$test = trim($line);
			if(substr($test, 0, strlen($DEFAULTS->COMMENT)) == $DEFAULTS->COMMENT) {
				$i++;
				continue;
			}
			// Check for FieldStart in Line
			// if false, add to the Array-Stack
			if(!strstr($line, $DEFAULTS->START_FIELD)) {
				$this->{$temp}[$j] = $line;
				$j++;
				$i++;
				continue;
			}
			// repeat while FieldStart in Line
			while(strstr($line, $DEFAULTS->START_FIELD)) {
				// FieldStart found. Split Line into the part before, the Startfield itself and the part after.
				list($PartOne, $FIELD, $PartTwo) = $this->SplitLine($line, $DEFAULTS->START_FIELD, $DEFAULTS->STOP_FIELD);
				// if $FIELD is not a valid TAG, add all up to $PartTwo to the actual Field.
				if(!$this->check_tag($FIELD)) {
					if(isset($this->{$temp}[$j])) {
						$this->{$temp}[$j] = $this->{$temp}[$j].$PartOne.$DEFAULTS->START_FIELD.$FIELD.$DEFAULTS->STOP_FIELD;
					} else {
						$this->{$temp}[$j] = $PartOne.$DEFAULTS->START_FIELD.$FIELD.$DEFAULTS->STOP_FIELD;
					}
					$line = $PartTwo;
					continue;
				}
				// if $FIELD is a valid TAG
				if(isset($this->{$temp}[$j])) {
					$this->{$temp}[$j] = $this->{$temp}[$j].$PartOne;
				} else {
					$this->{$temp}[$j] = $PartOne;
				}
				// remove trailing blank lines
				while ($j>0 && trim($this->{$temp}[$j]) == '')
				{
					unset($this->{$temp}[$j]);
					$j--;
				}
				$j = 0;
				$temp = $FIELD;
				$this->{$temp}[$j] = '';
				$PartTwo = trim($PartTwo);
				$line = $PartTwo;
			}
			$this->{$temp}[$j] = $this->{$temp}[$j].$line;
			$j++;
			$i++;
		}
	}
}

?>