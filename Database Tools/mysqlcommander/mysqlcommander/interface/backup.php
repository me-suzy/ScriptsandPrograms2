<?php
/**
* HTTP interface for MySQL Commander to backup tables via an URL Call
* not possible to backup with bigtable and emailing option
*
* parameters are
* - username : username for the interfase. defined in configuration
* - password : password for the inetrfase. defined in configuration
* - server : number of the mysql server set in the configuration normally set to 1
* - database : name of the database to backup
* - tables : string of all tables for backup. seperated by |. if the value is 'all_tables' all tables in the given database will be backupped.
* - gzipping : can be 1 or 0 for gzip or not
* - versioning : can be 1 or 0 for for versioning on or off. if versioning the files will be stored in a subfolder named with the acatual date and actual timme is added to all filenames.
* - overwrite : can be 1 or 0 for overwriting existing files or not
* - seperator : string of the seeprator for tablefields. default : ||#||
*
* Sample URL call: backup.php?username=test&password=test2&server=1&database=mysql&tables=db|user&gzipping=0&versioning=1&seperator=||#||
*
*/
header("Content-type: text/plain");
$home="../";
$blnLoginCheck = false;
include $home."ressourcen/config.php";
if (isset($_GET['server'])) $HTTP_SESSION_VARS['which_db'] = $_GET['server'];
include $home."ressourcen/dbopen.php";

$strResult = "";
if (isset($config->interface_username) AND (trim($config->interface_username) != "") AND isset($config->interface_password) AND (trim($config->interface_password) != "")) {

	if ( isset($_GET['username']) AND ($_GET['username'] == $config->interface_username) AND isset($_GET['password']) AND ($_GET['password'] == $config->interface_password) ) {
		if ( isset($_GET['database']) AND isset($_GET['tables']) ) {
		
			if (trim($_GET['tables']) == "all_tables") {
				$tables = $funcs->get_tables($_GET['database']);
			} else {
				$tables = explode("|", $_GET['tables']);
			}
			if (!isset($_GET['seperator'])) $seperator = "||#||";
			else $seperator = $_GET['seperator'];
			if (isset($_GET['gzipping']) AND $_GET['gzipping'] ) $gzipping = "yes";
			else $gzipping = "no";
			if (isset($_GET['overwrite']) AND $_GET['overwrite'] ) $overwrite = "yes";
			else $overwrite = "no";
			if (isset($_GET['versioning']) AND $_GET['versioning'] ) $blnVersioning = true;
			else $blnVersioning = false;
			for ($i=0; $i<count($tables); $i++) {
				if (trim($tables[$i]) != "") {
					$res = $funcs->backup_def($_GET['database'], $tables[$i], $overwrite, "./data/", $blnVersioning);
					if (!isset($res['error'])) $strResult .= "Definition: database: ".$_GET['database'] . ", table: ".$tables[$i] . ", success: " . $res['success']."\n";
					else $strResult .= "Definition: database: ".$_GET['database'] . ", table: ".$tables[$i] . ", success: " . $res['success'].", message: " . $res['error']."\n";
					$result_con = $funcs->backup_content($_GET['database'], $tables[$i], $seperator, $overwrite, $gzipping, "yes", "./data/", $blnVersioning);
					//print_r($result_con);
					if (isset($result_con['result']) ) $strResult .= "Data: database: ".$_GET['database'] . ", table: ".$tables[$i] . ", success: " . $result_con['success'].", result: " . $result_con['result']."\n";
					else $strResult .= "Data: database: ".$_GET['database'] . ", table: ".$tables[$i] . ", success: " . $result_con['success'].", message: " . $result_con['error']."\n";
				} else $strResult .= "tablename " . ($i+1) . " was empty!\n";
			}
		}
	} else $strResult .= "given username and/or password incorrect!\n";
}  else $strResult .= "username and/or password for interface access not defined!\n";

echo $strResult;
?>