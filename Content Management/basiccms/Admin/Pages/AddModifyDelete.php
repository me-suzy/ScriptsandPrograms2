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

	if (($strID!="") && ($strID!="0"))
	{
		$strsql="DELETE FROM pages_t_details ";
		$strsql.= " WHERE id=" .SQLSafeString($strID);
		$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{
			$strErrorMessages = "Could not delete the news information. " .$strErrorMessages;
		}
	}
}	
else
{
	$strID= QuerySafeString(strtoupper($_REQUEST["txtID"]));
	$strTitle= QuerySafeString($_REQUEST["txtTitle"]);
	$strDescription= QuerySafeString($_REQUEST["txtDescription"]);


	if (($strID !="") && ($strID!="0"))
	{
		$strsql="UPDATE pages_t_details SET title='" . SQLSafeString($strTitle) ;
		$strsql.=		"',description ='" . SQLSafeString($strDescription) ;
	
		$strsql.= "' WHERE id=" . SQLSafeString($strID);
		$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{
			$strErrorMessages = "Could not update the information. " . $strErrorMessages;
		}
	}
	else
	{
	$strsql= "INSERT INTO pages_t_details(title,description,startpage";
	$strTemp= " VALUES('" . SQLSafeString($strTitle) .  "','" . SQLSafeString($strDescription) .  "','" ;
	$strTemp.=			"N'";
	$strsql.= ") " . $strTemp . ")";
	$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{	$strErrorMessages = "Could not add the news. <BR>" . $strErrorMessages;}
		
	$strsql= "SELECT LAST_INSERT_ID() AS id1;";
	$var1=$conclass->Execute ($strsql,$strErrorMessages);
		if ($strErrorMessages!="")
		{	$strErrorMessages = "Could not add the news. <BR>" . $strErrorMessages;}

	while ($line = mysql_fetch_array($var1, MYSQL_ASSOC)) 
	{
		$strID=$line["id1"];
	}
	}
}

if ($strErrorMessages=="")
{	
	Redirect("AddModifyInput.php?ID=".$strID);
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
<A HREF="AddModifyInput.php?ID=<?=$strID?>"> Back to List</A>
<?php
print "</TD></TR>";
print "</TABLE>";
?>

</BODY>
</HTML>
