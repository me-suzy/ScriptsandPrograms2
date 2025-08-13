<?
session_start();
session_name('d4sess');
if (isset($_REQUEST[d4sess]) and !($_REQUEST[d4sess]=="")) {
	session_id($_REQUEST[d4sess]);
} 
$sessid = session_id();
$d4sess = session_id();

include("include/config.inc.php");
include("include/editor.inc.php");
include("include/mysql-class.inc.php");
include("include/style.inc.php");
include("include/functions.inc.php");
include("include/zip-class.inc.php");
?>