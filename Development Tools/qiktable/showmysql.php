<?php

// Create QikTable object
require("qiktable.php");			// Use full path if not local
$yourQikTable = new QikTable();

// Open database and send query
// $user, $password, $database and $tablename can be:
// -> Set in an include file
// -> Passed in an HTML query string
// -> Set in a form whose action is this PHP file
// -> Replaced with hardcoded values

$dbconn = @mysql_pconnect("localhost", "$user", "$password") or die("Can't open database");
mysql_select_db("$database") or die("Can't open database");
$result = mysql_query("SELECT * FROM $tablename ") or die("Error processing query");

// Extract headings and first line of table from the first row of the database
$row = mysql_fetch_assoc($result);		// NOT mysql_fetch_array($result)!
$yourQikTable->setHeadingsFromKeys($row);
$yourQikTable->addArray($row);

// Get the rest of the table
while ($row = mysql_fetch_row($result)) $yourQikTable->addRow($row);

// Set desired parameters
$yourQikTable->setHeadingAlign("left");

// And display it
$yourQikTable->printTable();

mysql_free_result($result);

?>