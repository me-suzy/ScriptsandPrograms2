<?php
/**
* This sets the correct headers that we need. For example UTF-8 encoding of the pages
* it also reads general data that is needed in many places of TSEP
* 
* @author Sebastian P�erl
*
* following will be filled automatically by SubVersion!
* Do not change by hand!
*  $LastChangedDate: 2005-09-01 15:14:02 +0200 (Do, 01 Sep 2005) $
*  @lastedited $LastChangedBy: toon $
*  $LastChangedRevision: 303 $
*
*/

/* !!! Do not change or delete these placeholders !!! */
/* !!! They are used by setup !!! *
/* %BEGIN_SETUP_DATABASE_DATA% */
/* URL to your database server */
$db_server = 'localhost';
/* Your database login name */
$db_usrname = 'test';
/* Your database password */
$db_pwd = 'test';
/* TSEP database name */
$db_name = 'tsep';
/* TSEP table prefix */
$db_table_prefix = 'tsep_';
/* %END_SETUP_DATABASE_DATA% */


/* Force the browser to use UTF-8 enconding */
$headers  = "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
header($headers);

//stop caching dynamic pages
//from http://de3.php.net/header 
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");



/* Get the TSEP root folder */
if ( !mysql_connect( $db_server, $db_usrname, $db_pwd ) ) {
    die("<b>Can't connect to the database.</b><br />MySQL error: ".mysql_error() );
}
if ( !mysql_select_db( $db_name ) ) {
    die("<b>Can't open TSEP database.</b><br />MySQL error: ".mysql_error() );
}
$SQLResult = mysql_query( "SELECT stringvalue FROM ".$db_table_prefix."internal WHERE description='absPath' and stringtag='config'" ) or die( "<b>Couldn't read from the database.</b><br />MySQL error: ".mysql_error() );
list( $tsepRootDir ) = mysql_fetch_row( $SQLResult );
@mysql_close();

/* Open connection to the database */
require_once( "$tsepRootDir/include/dbconnection.php" );
/* general config data */
require_once( "$tsepRootDir/include/config.php" );

/* read language strings */
/* include for security - if admin enters something that does not exist */
/* always read english language as some (new) strings might not have been translated */
include( "$tsepRootDir/language/en_US/language.php" );
/* code recycle: get language strings from fitting language.php */
include( "$tsepRootDir/language/".$tsep_config['config_Language']."/language.php" );
/* handles language related issues */
require_once( "$tsepRootDir/include/languagehandler.php" );

/* Include debug framework */
require_once( "$tsepRootDir/include/tseptrace.php" );

?>