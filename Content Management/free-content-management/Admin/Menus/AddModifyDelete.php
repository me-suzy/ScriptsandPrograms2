<?php
session_start();
include("../../Includes/PortalConection.php");
include("../../Includes/Database.php");

$strRootpath= "../../";
include_once ("../../Includes/validsession.php");

if (!isset($_GET['action']))
{
	$strAction="";
}
else
{
	$strAction =QuerySafeString($_GET["action"]);
}

$conclass =new DataBase();
$strErrorMessages="";

if ($strAction=="DEL")
{
	$strID= QuerySafeString($_GET["ID"]);

	if (!isset($_GET['View']))
	{
		$strView="Active";
	}
	else
	{
		$strView= QuerySafeString($_GET["View"]);
	}
	if (($strID!="") && ($strID!="0"))
	{
		$strsql="DELETE FROM cms_t_menus ";
		$strsql.= " WHERE ID=" .SQLSafeString($strID);
		$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{
			$strErrorMessages = "Could not delete the menu information. " .$strErrorMessages;
		}
	}
}	
else
{
	$strID= QuerySafeString(strtoupper($_REQUEST["txtID"]));
	$strTitle= QuerySafeString($_REQUEST["txtTitle"]);
	$strHyperLink= QuerySafeString($_REQUEST["txtHyperlink"]);
	if ($strHyperLink=="")
	{
		$strHyperLink= QuerySafeString($_REQUEST["cmbHyperlink"]);
	}

	$strSequence=QuerySafeString($_REQUEST["txtSequence"]);
	$strActive=QuerySafeString($_REQUEST["txtActive"]);
	$strView= QuerySafeString($_REQUEST["txtView"]);

	if ($strActive=="")
		{
		$strActive="N";
		}
		
	if (($strID !="") && ($strID!="0"))
	{
		$strsql="UPDATE cms_t_menus SET Title='" . SQLSafeString($strTitle) ;
		$strsql.=		"',HyperLink='" . SQLSafeString($strHyperLink) ;
		$strsql.=		"',Active='" . SQLSafeString($strActive);
		$strsql.=		"',DisplaySequence=" . SQLSafeString($strSequence);

		$strsql.= " WHERE ID=" . SQLSafeString($strID);
		$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{
			$strErrorMessages = "Could not update the menu information. " . $strErrorMessages;
		}
	}
	else
	{
	$strsql= "INSERT INTO cms_t_menus(Title,HyperLink,Active,DisplaySequence";
	$strTemp= " VALUES('" . SQLSafeString($strTitle) .  "','" . SQLSafeString($strHyperLink) .  "','" ;
	$strTemp.=			SQLSafeString($strActive) . "'," . $strSequence;
	$strsql.= ") " . $strTemp . ")";
	$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{	$strErrorMessages = "Could not add the menu. <BR>" . $strErrorMessages;}
		

	}
}
if ($strErrorMessages=="")
{	
	Redirect("List.php?View=" . $strView);
}
print "<HTML><HEAD>";
include ("../../Includes/Styles.php");
print "</HEAD><BODY>";
print $strErrorMessages;
?>
<P>&nbsp;</P>
<A HREF="List.php?View=<?php print $strView;?>"> Back to List</A>
</BODY>
</HTML>
