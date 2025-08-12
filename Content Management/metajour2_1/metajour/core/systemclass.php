<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

class system {
	/**
	 * Return an array of fontnames defined in the table system_fonts
	 */
	function getFonts() {
		global $CONFIG;
		$adodb = &ADONewConnection($CONFIG['sql_type']);
		$adodb->connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_password'], $CONFIG['sql_database']);
		$adodb->SetFetchMode(ADODB_FETCH_ASSOC);
		return $adodb->getcol('select navn from system_fonts order by navn');
	}

	function getlanguages() {
		global $CONFIG;
		$adodb = &ADONewConnection($CONFIG['sql_type']);
		$adodb->connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_password'], $CONFIG['sql_database']);
		$adodb->SetFetchMode(ADODB_FETCH_ASSOC);
		$res = &$adodb->execute('select language, langcode from system_languages order by language');
		$cnt = 0;
		while($row = $res->fetchrow()) {
			$result[$cnt]['language'] = $row['language'];
			$result[$cnt]['langcode'] = $row['langcode'];
			$cnt++;
		}
		return $result;
	}

	function getCountries() {
		global $CONFIG;
		$adodb = &ADONewConnection($CONFIG['sql_type']);
		$adodb->connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_password'], $CONFIG['sql_database']);
		$adodb->SetFetchMode(ADODB_FETCH_ASSOC);
		$res = &$adodb->execute('select * from system_country order by country');
		$cnt = 0;
		while($row = $res->fetchrow()) {
			$result[$cnt]['country'] = $row['country'];
			$result[$cnt]['countrycode'] = $row['countrycode'];
			$cnt++;
		}
		return $result;
	}

}
?>
