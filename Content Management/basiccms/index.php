<?php
session_start();
include("Includes/PortalConection.php");
include("Includes/Database.php");

if (!isset($_GET['id']))
{
	$strID="";
}
else
{
	$strID=QuerySafeString($_GET["id"]);
}


$strDetails="";
$strError ="";
if ($strID != "")
{
	$strsql = "SELECT description ";
	$strsql .=" FROM pages_t_details ";
	$strsql .=" WHERE id=$strID";
	$conclass =new DataBase();
	$rst= $conclass->Execute ($strsql,$strError);
	if ($strError=="")
	{
		while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	     {
			$strDetails=$line['description'];
		}
	}
	
}
elseif ($strID == "0") {
	$strDetails="";
}
else 
{
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
}
print $strDetails;
?>
