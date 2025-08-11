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
		$strsql="DELETE FROM pages_t_users " ;
		$strsql.=" WHERE userid='". SQLSafeString($strID) . "'";
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
	$strEMail=QuerySafeString($_REQUEST["txtEmail"]);
	$strActive=QuerySafeString($_REQUEST["txtActive"]);
	$strView= QuerySafeString($_REQUEST["txtView"]);

	if ($strActive =="") 
	{
		$strActive="N";
	}


	if (($strID !="") && ($strID != '0'))
	{
		$strsql="UPDATE pages_t_users SET username='". SQLSafeString($strUserName) ;
		$strsql.="',password='". SQLSafeString($strPassword) ;
		$strsql.=	"',email='". SQLSafeString($strEMail) ;
		$strsql.=	"',active='". SQLSafeString($strActive) ;

		$strsql.="' WHERE userid='". SQLSafeString($strID) . "'";
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
		$strsql= "INSERT INTO pages_t_users(userid,username,password,email,active";
		$strTemp= " VALUES('" . SQLSafeString($strID) . "','" . SQLSafeString($strUserName) .  "','" . SQLSafeString($strPassword) .  "','" ;
		$strTemp.=  SQLSafeString($strEMail) . "','" . SQLSafeString($strActive) . "'";  
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
	Redirect("List.php?View=" . $strView);
}
print "<HTML><HEAD>";
include ("../../Includes/Styles.php");
print "</HEAD><BODY>";
print "<TABLE border=0>";
print "<TR><TD WIDTH=15% VALIGN=TOP>";
include_once ("../../navigation.php");
print "</TD><TD>";

print $strErrorMessages;
?>
<P>&nbsp;</P>
<A HREF="List.php?View=<?php print $strView;?>"> Back to List</A>
<?php
print "</TD></TR>";
print "</TABLE>";
print "</BODY>";
print "</HTML>";
?>