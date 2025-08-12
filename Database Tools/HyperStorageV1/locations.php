<?

# Scripts written by Nathan Huebner (admin@sellchain.com)

include "config.php";

################################################################
# USAGE LICENSE
################################################################

# Scripts written by Nathan Huebner (admin@sellchain.com)
# Freeware distribution.
# Give credit where credit is due. Royalties are not necessary, free use.
# You can give it out, just remember to tell them who made it.
# Try to leave these credits, thanks.

################################################################

function FindLocation($type,$thekey) {

# Finds the location

return SmartQuery("SELECT id FROM locations.$type WHERE thekey='$thekey';");

}



function MakeLocation($type,$thekey,$extra='') {

# Makes the location

global $templates; # imports templates
global $maxhyperstore; # imports max rows

$templateimported=$templates[$type];

SmartQuery("CREATE DATABASE IF NOT EXISTS locations COLLATE utf8_general_ci;");


// Default Location System
$keytablequery="
CREATE TABLE IF NOT EXISTS locations.$type (
  `thekey` varchar(100) NOT NULL,
  `extra` varchar(100) NOT NULL,
  `id` int(3) NOT NULL,
  KEY `thekey` (`thekey`),
  KEY `extra` (`extra`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
SmartQuery($keytablequery); # RUN QUERY

$hyperstore=$maxhyperstore[$type];

if ($hyperstore<=0) { $hyperstore=500000; } // Default 500,000 per table...

$countusers=SmartQuery("SELECT count(thekey) FROM locations.$type;");

$countusers=$countusers + ($hyperstore * 10); // Determines how many per table...
$tablename=substr($countusers,0,3); # GET FIRST 3 DIGITS.
$ctablename=$tablename;

SmartQuery("CREATE DATABASE IF NOT EXISTS $type COLLATE utf8_general_ci;");


		// CREATES THE TABLE USING THE TEMPLATE FROM templates.php
		$ctablequery="
		CREATE TABLE IF NOT EXISTS $type.$ctablename (
		
		$templateimported
		
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
	
	# RUN QUERY TO CREATE TABLE 500
	SmartQuery($ctablequery);
	
	# Add him to the locations.users table.
	
	SmartQuery("INSERT INTO locations.$type SET thekey='$thekey', id='$ctablename', extra='$extra';");
	
return $ctablename;
} 




##########################################################################
#### SMART QUERY FUNCTION - EXAMPLES
##########################################################################

#$rowcount = SmartQuery("SELECT count(*) from mytable;");
#$firstname = SmartQuery("SELECT firstname from mytable WHERE email='bob@aol.com';");
#$myarray = SmartQuery("ARR SELECT firstname,lastname from mytable WHERE email='bob@aol.com';");
# Usage for $myarray:   $firstname=$myarray[0];  $lastname=$myarray[1];

##########################################################################
#### SMART QUERY FUNCTION
##########################################################################

function SmartQuery ($QueryString) {
global $db, $db_host, $db_user, $db_password;
$con = mysql_connect($db_host,$db_user,$db_password);
if (!$con) {
die("MySQL Database Connection Problem: " . mysql_error() . "\n *");
exit;
}

if (strtolower(substr($QueryString,0,3))=="arr") {
# RETURN AS ARRAY
$QueryString=trim(substr($QueryString,3));
$returnarray=true;
} ELSE {
# RETURN ROW ZERO.
$returnarray=false;
}

$query = $QueryString;
mysql_select_db("$db"); # not necessary for SELECT/INSERT FROM mydb.table syntax.
$mysql_result = mysql_query($query, $con);
$ret = mysql_fetch_row($mysql_result);
if ($returnarray==true) {
return $ret;
} ELSE {
return $ret[0];
}
}



?>