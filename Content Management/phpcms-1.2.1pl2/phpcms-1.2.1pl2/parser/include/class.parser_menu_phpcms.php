<?php
/* $Id: class.parser_menu_phpcms.php,v 1.1.2.10 2004/08/18 14:58:34 bjmg Exp $ */
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
   |    Markus Richert (e157m369)
   |    Thilo Wagner (ignatius0815)
   +----------------------------------------------------------------------+
*/


class menutemplate {
	function menutemplate() {
		global $DEFAULTS;

		if(isset($DEFAULTS->MENUTEMPLATE)) {
			$this->content = new File($DEFAULTS->MENUTEMPLATE);
			$this->content->ReadLeftFields();
		}
	}
}

class menu {
	var $menuKlasse = array();
	var $menuFieldNames = array();
	var $menuFieldValues = array();
	var $menuname = array();

	function menu() {
		global $DEFAULTS;

		if(!isset($DEFAULTS->MENU) OR $DEFAULTS->MENU == '') {
			return;
		}
		$this->content = new File($DEFAULTS->MENU);
		$temp0 = count($this->content->lines);
		$mCount = 0;
		$eCount = 0;
		$delimiter = $DEFAULTS->MENU_DELIMITER;

		for($i = 0; $i < $temp0; $i++) {
			if(!isset($this->content->lines[$i])) {
				continue;
			}
			$line = trim($this->content->lines[$i]);
			if(substr($line, 0, strlen($DEFAULTS->COMMENT)) == $DEFAULTS->COMMENT OR strlen($line) == 0) {
				continue;
			}
			$temp1a = trim(strstr($line, 'MENU:'));
			$temp1b = trim(strstr($line, 'DELIMITER:'));
			if ((strlen($temp1a) == 0) && (strlen($temp1b) == 0)) {
				// read line
				$temp2 = split("[" . $delimiter . "]+", $line);
				// make class
				if($eCount == 1) {
					$this->menuKlasse[$mCount - 1] = trim($temp2[0]);
					$this->menuKlasse[$mCount - 1] = substr($this->menuKlasse[$mCount - 1], 0, strrpos($this->menuKlasse[$mCount - 1], '.'));
				}
				// get field names
				if($eCount == 0) {
					$FieldCount = count($temp2);
					for($j = 0; $j < $FieldCount; $j++) {
						$this->menuFieldNames[$mCount - 1][$j] = trim($temp2[$j]);
					}
				}
				// get Fields
				if($eCount > 0) {
					$FieldCount = count($temp2);
					for($j = 0; $j < $FieldCount; $j++) {
						$temp = trim($temp2[$j]);
						if(strtoupper(substr($temp, 0, 5)) == '$HOME') {
							$temp = $DEFAULTS->PROJECT_HOME.substr($temp, 5);
						} elseif(strtoupper(substr($temp, 0, 10)) == '$PLUGINDIR') {
							$temp = $DEFAULTS->PLUGINDIR.substr($temp, 10);
						}
						$this->menuFieldValues[$mCount - 1][$eCount - 1][$j] = $temp;
					}
				}
				$eCount++;
			} else {
				if (strlen($temp1a) > 0) {
					// get name of menu
					$this->menuname[$mCount] = trim(substr($temp1a, 5));
					$mCount++;
					$eCount = 0;
					$LineCounter = 0;
				} else if (strlen($temp1b) > 0) {
					$delimiter = trim(substr($temp1b, 10));
				}
			}
		}
	}
}
?>
