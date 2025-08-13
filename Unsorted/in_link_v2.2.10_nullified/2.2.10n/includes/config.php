<?php
/*	In-link Configuration Version 2.2.10
	Contains database settings and invokes all initializations
	Required for all scripts

	Last updated 08/28/02
*/
#
# Edit the following values, enclosed in quotes, only:
#
$sql_type = "sql_type";#sql_type
$sql_server = "sql_server";#sql_server
$sql_user = "sql_user";#sql_user
$sql_pass = "sql_pass";#sql_pass
$sql_db = "sql_db";#sql_db
#
# Do not edit anything after this line!
##############################################################
#
$month = date("n");
$day = date("j");
$year = date("Y");
$version = "2.2.10n";
$php_version = floor(phpversion());
#
############ File LIST for LOGIN #############################

$file_list ="navigate, left, top, pending, confirm, search_result, search_advanced, addlink, addcategory, move";


if($admin == 1 || $redir == 1 || $prev_admin==1)
{	$include_path="../includes";
	$language_path="../languages";
}
else
{	$include_path="includes";
	$language_path="languages";
}
if($backup_inport==1)
{	$include_path="../../includes";
	$language_path="../../languages";
}


if($admin==1)
	$theme_path="admin/templ";
else
	$theme_path="themes";

include_once("$include_path/adodb/adodb.inc.php");
include_once("$include_path/sessions_lib.php");
include_once("$include_path/security_lib.php");
include_once("$include_path/functions_lib.php");


//initialize all - workaround
include_once("$include_path/init.php");
?>