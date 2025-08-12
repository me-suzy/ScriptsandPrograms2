<?php

/*******************************************************************************  
@    Download Count XML Output
@    by Drew Phillips (www.drew-phillips.com)
@
@    If you wish to output your download count results in XML format
@    for parsing by a third party or in your own xml based webpages, 
@    you can fetch the output from this script.
@
@    The root element is called <dl_count> and the child node is <download>.
@    The element <download> contains 2 fields of information.  The filename,
@    in the tag <file>, and the number of downloads, in the tag <count>.
@
@    An additional tag, <total_downloads> contains a the total of all the 
@    files' download counts added together.
@
@    If you wish not to see every file listed, you can optionally specify
@    the specific file you want data for.  Call the script like this:
@    xmlout.php?file=filename.ext
@    That will only return data about the specific file as well as the
@    total count.
@
********************************************************************************/

//DATABASE CONFIGURATION
$SQL_HOST = "localhost";
$SQL_USER = "drew";
$SQL_PASS = "password";
$SQL_DB   = "drew";

//end configuration

$xmlcnt_link = mysql_connect($SQL_HOST, $SQL_USER, $SQL_PASS) or die("Could not connect to database. Reason: " . mysql_error());
mysql_select_db($SQL_DB, $xmlcnt_link);

if (isset($_GET['file'])) {
    $count_query = "SELECT * FROM dl_count WHERE file = '{$_GET['file']}'";
    $count_result = mysql_query($count_query);
} else {
	$count_query = "SELECT * FROM dl_count ORDER BY file ASC";
	$count_result = mysql_query($count_query);
}

echo "<?xml version=\"1.0\"?>\n\n";
echo "<dl_count>\n";

while($data = mysql_fetch_array($count_result)) {
    echo "  <download>\n"
        ."    <file>{$data['file']}</file>\n"
        ."    <count>{$data['count']}</count>\n"
        ."  </download>\n";
}

$count_query = "SELECT SUM(count) FROM dl_count";
$count_result = mysql_query($count_query);

$data = mysql_fetch_row($count_result);

echo "\n  <total_downloads>{$data[0]}</total_downloads>\n";

echo "\n</dl_count>\n";

?>