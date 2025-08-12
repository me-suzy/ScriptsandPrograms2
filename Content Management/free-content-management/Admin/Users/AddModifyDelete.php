<?php
session_start(); 

include_once ("../../Includes/PortalConection.php");
include_once ("../../Includes/Database.php");
$strRootpath= "../../";
include_once ("../../Includes/validsession.php");


if (!isset($_REQUEST['action']))
{
	$strAction="";
}
else
{
	$strAction=QuerySafeString($_REQUEST["action"]);
}

$conclass =new DataBase();
$strErrorMessages="";

if ($strAction=="DEL")
{
	$strID= QuerySafeString($_REQUEST["ID"]);
	$strView= QuerySafeString($_REQUEST["View"]);
	if (($strID !="") && ($strID!="0"))
	{
		$strsql="DELETE FROM cms_t_users " ;
		$strsql.=" WHERE UserID='". SQLSafeString($strID) . "'";
		$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{
			$strErrorMessages = "Could not delete the user information. ". strErrorMessages;
		}
	}
}
else
{
	$strID= QuerySafeString($_REQUEST["txtID"]);
	$strUserName= QuerySafeString($_REQUEST["txtUserName"]);
	$strPassword= QuerySafeString($_REQUEST["txtPassword"]);
	$strAdmin=QuerySafeString($_REQUEST["txtAdmin"]);
	$strEMail=QuerySafeString($_REQUEST["txtEmail"]);
	$strActive=QuerySafeString($_REQUEST["txtActive"]);
	$strView= QuerySafeString($_REQUEST["txtView"]);

	if ($strActive =="") 
	{
		$strActive="N";
	}


	if (($strID !="") && ($strID != '0'))
	{
		$strsql="UPDATE cms_t_users SET UserName='". SQLSafeString($strUserName) ;
		$strsql.="',Password='". SQLSafeString($strPassword) ;
		$strsql.=	"',Email='". SQLSafeString($strEMail) ;
		$strsql.=	"',Active='". SQLSafeString($strActive) ;
		$strsql.=	"',AdminUser='". SQLSafeString($strAdmin); 

		$strsql.="' WHERE UserID='". SQLSafeString($strID) . "'";
		//print $strsql;
		$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages !="")
		{
			$strErrorMessages = "Could not update the user information. " . $strErrorMessages;
		}
	}
	else
	{
		$strID= QuerySafeString(strtoupper($_REQUEST["txtUserID"]));
		$strsql= "INSERT INTO cms_t_users(UserID,UserName,Password,AdminUser,Email,Active";
		$strTemp= " VALUES('" . SQLSafeString($strID) . "','" . SQLSafeString($strUserName) .  "','" . SQLSafeString($strPassword) .  "','" ;
		$strTemp.= SQLSafeString($strAdmin) . "','" . SQLSafeString($strEMail) . "','" . SQLSafeString($strActive) . "'";  
		$strsql.= ") " . $strTemp . ")";
		//print $strsql;	
	
		$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages !="")
		{
			$strErrorMessages = "Could not add the user. <BR>" . $strErrorMessages;
		}
	}
}
if ($strErrorMessages=="")
{
	Redirect('List.php?View='.$strView);
	//?View='.$strView
	return;
}
print "<HTML>";
print "<HEAD>";
include ("../../Includes/Styles.php");
print "</HEAD>";
print "<BODY>";
print $strErrorMessages;
print "<P>&nbsp;</P>";
print "<A HREF='List.php?View='.strView.'> Back to List</A>";
print "</BODY>";
print "</HTML>";
?>