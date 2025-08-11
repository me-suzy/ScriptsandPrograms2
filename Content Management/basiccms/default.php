<?php
session_start();
include("Includes/PortalConection.php");
include("Includes/Database.php");


$strDetails="";
$strError ="";
	$strsql = "SELECT description ";
	$strsql .=" FROM pages_t_details ";
	$strsql .=" WHERE startpage='Y'";
	$conclass =new DataBase();
	$rst= $conclass->Execute ($strsql,$strError);
	if ($strError=="")
	{
		while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	     {
			$strDetails=$line['description'];
		}
	}
	
print $strDetails;
?>
