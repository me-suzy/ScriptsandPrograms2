<?php
/* $Id: class.lib_error_phpcms.php,v 1.3.2.12 2004/07/16 09:01:26 bjmg Exp $ */
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
   +----------------------------------------------------------------------+
*/


// Library for error functions
// print_error ($error_string)
// write error message to output and exit.

if(!defined("_ERROR_"))
	define("_ERROR_", TRUE);

function print_error($error_string, $file, $line) {
	echo 'ERROR!<br />';
	echo '====================================<br />';
	echo $error_string."<br />\n";
	echo '====================================<br />';
	echo 'Script-name: '.$file.'<br />';
	echo 'Line: '.$line.'<br />';
	echo '====================================<br />';
	exit;
}

// debug_lines($function_name)
// write actual function to output and continue.

function debug_lines($function_name) {
	if(!isset($GLOBALS['error_debug']) OR $GLOBALS['error_debug'] == false) {
		return;
	}
	echo 'Current function name: '.$function_name."<br />\n";
	return;
}

function ExitError($errnr, $er0='', $er1='', $er2='', $er3='', $er4='', $er5='') {
	global $DEFAULTS, $MESSAGES;

	if(isset($DEFAULTS->DEBUG) AND $DEFAULTS->DEBUG == 'off') {
		$DEFAULTS->ERROR_PAGE = str_replace('$errnr',$errnr,$DEFAULTS->ERROR_PAGE);
		Header('Location: '.$DEFAULTS->DOMAIN_NAME.$DEFAULTS->ERROR_PAGE);
	} else {
		if(isset($DEFAULTS)) {
			echo 'phpCMS '.$DEFAULTS->VERSION."<br />\n";
		}
		if(isset($MESSAGES)) {
			echo $MESSAGES[57].$MESSAGES['ERRORCODES'][$errnr+5]."<br />\n";
		}
		for($i = 0; $i < 6 ; $i++) {
			if(${"er$i"} != '') {
				echo ${"er$i"}."<br />\n";
			}
		}
	}
	exit;
}

?>