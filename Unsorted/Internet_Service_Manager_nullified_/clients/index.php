<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";
include "auth.php";

list($QUERY_STRING, $extrainfo)=explode(":", $QUERY_STRING);
//check if the file exists in the query_string if there is a query string..
if($QUERY_STRING && file_exists("$client_template_dir/$QUERY_STRING.htm")){
$file="$QUERY_STRING.htm";
}else{
$file="index.htm";
}

if($file=="projectinfo.htm"){$project_id=$extrainfo;}

if($file=="showinvoice.htm"){$invoice_id=$extrainfo;}

if($file=="files.htm"){$folder=$extrainfo;}

$template_file=$file;
include "template.php";
?>