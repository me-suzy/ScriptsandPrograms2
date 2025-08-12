<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage util
 */

/*
Misc stand-alone functions
No database or METAZO-object specific stuff here!
*/

function myaddslashes($string) {
	if (get_magic_quotes_gpc()) {
		return $string;
	} else {
		return addslashes($string);
	}
}

?>