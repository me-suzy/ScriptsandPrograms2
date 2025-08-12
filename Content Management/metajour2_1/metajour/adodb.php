<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage handler
 */
require_once('core/ado/adodb.inc.php');

function &getdbconn() {
	global $CONFIG;
	if (!isset($CONFIG['sql_type'])) $CONFIG['sql_type'] = 'mysql';
	static $_dbhandler = null;
	if (null == $_dbhandler) {
		/* DO NOT USE ASSIGN BY REF HERE AS
		 * STATIC IS IMPLEMENTED IN PHP AS REFERENCES
		 * THEREFOR AN ASSIGN BY REF HERE WOULD
		 * DESTROY THE "STATICNESS" OF $_DBHANDLER
		 */
		$_dbhandler = ADONewConnection('mysql');
		$_dbhandler->connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_password'], $CONFIG['sql_database']);
		$_dbhandler->SetFetchMode(ADODB_FETCH_ASSOC);
	}
	return $_dbhandler;
}
?>
