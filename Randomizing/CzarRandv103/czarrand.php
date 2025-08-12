<?
##########################################
### 
### CzarRand v1.03
### Made by: Czaries  [czaries@pacbell.net]
### http://www.czaries.net/scripts/
### for more scripts and updates.
###
##########################################


### Database Definitions ###
$dbhost = "localhost";
$dbname = "database_name";
$dbuser = "database_user";
$dbpass = "database_password";

### Default Connection String ###
$connection = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Unable to connect to MySQL server." . mysql_error()); 
$db = mysql_select_db($dbname, $connection) or die ("Unable to select database: " . mysql_error()); 

### Further MySQL Info ###
// Name of the table you wish to get random items out of
$randtable = "czn_quotes";

### Values you do NOT want included in the random drawing ###
// Turns this function off or on
$xvals = "off";
// Array of values you do not want included - separated by commas
$xarr = array("1", "4", "11");

### ATTENTION - IMPORTANT TABLE INFO ###
#
# The table you use MUST have an "id" field - this is
# how the generator knows how to pick a random item!
#
# Please assign the name of the id field (usually just "id")
$idvar = "id";
#
# If you get stuck, or don't know how to make this table, you
# may use the example below to help you get started, or use
# the .sql file that came in the .zip
/*
CREATE TABLE random_quotes (
  id tinyint(3) NOT NULL auto_increment,
  quote longtext NOT NULL,
  author varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
*/
### If using your own table, please edit a few variables below ###
?>


<?
### Make a query to draw the records from the table
	$randsql_result = mysql_query("SELECT * FROM $randtable",$connection) or die ('Database Query Failed.  Please check the $randtable variable under Further MySQL Info, and make sure you have the correct table name.');
	if(mysql_num_rows($randsql_result) == 0) {
	print "There are no entries in the database!<br>Please insert entries so a random one can be picked!"; exit; }
### Load entries into an array to select one randomly
	$i = 0;
	while($row = mysql_fetch_row($randsql_result)) { 
	if($xvals == "on") {
	if(!in_array("$row[0]", $xarr) && $row[0] != "") {
	$rand[$i] = $row[0];
	$i++;
	}
	} elseif($row[0] != "") {
	$rand[$i] = $row[0];
	$i++;
	}
	}
	mysql_free_result($randsql_result);

### Select a random entry from the assembled array
	if(count($rand) == "0") { print "There are no entries in the array!"; exit; }
	$randnum = array_rand($rand);

	$rst = mysql_query("SELECT * FROM $randtable WHERE $idvar = '$rand[$randnum]' LIMIT 1", $connection) or die('Could not retrieve random entry from databse.  Please check the $idvar variable under IMPORTANT TABLE INFO to make sure the id field is correct');
	$q = mysql_fetch_array($rst);

### You may have to EDIT these output variables if you did not use the example table above. ###
### The text inside the brackets is the name of the COLUMN in the table you wish to display.  ###

	print "<i>$q[quote]</i><br>- $q[author]";
?>