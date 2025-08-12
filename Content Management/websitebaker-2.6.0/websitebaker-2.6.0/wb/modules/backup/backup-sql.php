<?php

// $Id: backup-sql.php 191 2005-09-28 13:51:44Z ryan $

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

// Filename to use
$filename = $_SERVER['HTTP_HOST'].'-backup-'.gmdate('Y-m-d', mktime()+TIMEZONE).'.sql';

// Check if user clicked on the backup button
if(!isset($_POST['backup'])){ header('Location: ../'); }

// Include config
require_once('../../config.php');

// Create new admin object
require(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Settings', 'settings_advanced', false);

// Begin output var
$output = "".
"#\n".
"# Website Baker ".WB_VERSION." Database Backup\n".
"# ".WB_URL."\n".
"# ".gmdate(DATE_FORMAT, mktime()+TIMEZONE).", ".gmdate(TIME_FORMAT, mktime()+TIMEZONE)."\n".
"#".
"\n";

// Get table names
$result = $database->query("SHOW TABLE STATUS");

// Loop through tables
while($row = $result->fetchRow()) { 
	//show sql query to rebuild the query
	$sql = 'SHOW CREATE TABLE '.$row['Name'].''; 
	$query2 = $database->query($sql); 
	// Start creating sql-backup
	$sql_backup ="\r\n# Create table ".$row['Name']."\r\n\r\n";
	$out = $query2->fetchRow();
	$sql_backup.=$out['Create Table'].";\r\n\r\n"; 
	$sql_backup.="# Dump data for ".$row['Name']."\r\n\r\n";
	// Select everything
	$out = $database->query('SELECT * FROM '.$row['Name']); 
	$sql_code = '';
	// Loop through all collumns
	while($code = $out->fetchRow()) { 
		$sql_code .= "INSERT INTO ".$row['Name']." SET "; 
		$numeral = 0;
		foreach($code as $insert => $value) {
			// Loosing the numerals in array -> mysql_fetch_array($result, MYSQL_ASSOC) WB hasn't? 
			if($numeral==1) {
				$sql_code.=$insert ."='".addslashes($value)."',";
			}
			$numeral = 1 - $numeral;
		}
		$sql_code = substr($sql_code, 0, -1);
		$sql_code.= ";\r\n";
	}
	$output .= $sql_backup.$sql_code; 
}

// Output file
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename='.$filename);
echo $output;

?>