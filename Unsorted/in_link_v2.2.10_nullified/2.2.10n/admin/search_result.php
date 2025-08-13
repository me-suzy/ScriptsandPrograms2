<?php
//Read in config file
$thisfile = "search_result";
$admin = 1;
if(!$pend){$pend="0";}
$configfile = "../includes/config.php";
include($configfile);
include("../includes/admin_search_lib.php");
include("../includes/templ_lib.php");

$attach=ereg_replace("\|","&",$attach);

if ($submit==$la_button_search_cats) {
	$rs = &$conn->Execute("DROP TABLE IF EXISTS inl_$sid");
	$r = getcatsearch();
	if($pend==1)
		inl_header("navigate.php?t=pending_cats&$attach"."having=".$r);
	else
		inl_header("navigate.php?t=search_cats&$attach"."having=".$r);
}
if ($submit==$la_button_search_links)
{
	$rs = &$conn->Execute("DROP TABLE IF EXISTS inl_$sid");
	$r = getlinksearch();
	if($pend==1)	
		inl_header("navigate.php?t=pending_links&$attach"."having=".$r);
	else
		inl_header("navigate.php?t=search_links&$attach"."having=".$r);
}
if ($submit==$la_button_search || $table)
{	
	if (strlen(trim($keyword))<3)
	{
			$message=base64_encode($la_error_for_simple_search);
			inl_header("navigate.php?t=error&message=$message");
			break;
	}
	$r = keywordsearch($keyword, $cat);
	$rs = &$conn->Execute("DROP TABLE IF EXISTS inl_$sid");
	if ($table=="links" || $table=="links1" || $table=="links2")
	{							
		if($pend==1)
			inl_header("navigate.php?t=pending_links&$attach"."having=".$r);
		else
			inl_header("navigate.php?t=search_links&$attach"."having=".$r."&cat=".$cat);
	}
	elseif($table=="cats")
	{	
		if($pend==1)			
			inl_header("navigate.php?t=pending_cats&$attach"."having=".$r);
		else
			inl_header("navigate.php?t=search_cats&$attach"."having=".$r);
	}
}
?>