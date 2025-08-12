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
if ($_SESSION["Admin"] =="Y")
{
	if (!isset($_GET["View"]))
	{
		$strView="Active";
	}
	else
	{
		$strView=QuerySafeString($_GET["View"]);
	}

	if ($strView=="All")
	{
		$strsql = "SELECT ID as 'Menu ID',Title as 'Menu Title',HyperLink as 'Hyper Link',DisplaySequence as 'Sequence',Active" ;
		$strsql .=	" FROM cms_t_menus ORDER BY DisplaySequence ASC";

	}
	else 
	{
		if ($strView=="Inactive")
		{	
			$strsql = "SELECT ID as 'Menu ID',Title as 'Menu Title',HyperLink as 'Hyper Link',DisplaySequence as 'Sequence',Active" ;
			$strsql .=	" FROM cms_t_menus WHERE Active='N' ORDER BY DisplaySequence ASC";
		}
	else
		{
		$strsql = "SELECT ID as 'Menu ID',Title as 'Menu Title',HyperLink as 'Hyper Link',DisplaySequence as 'Sequence',Active" ;
		$strsql .=	" FROM cms_t_menus WHERE Active='Y' ORDER BY DisplaySequence ASC";
		}
	}
$strTable=TableBodyList($strsql,"","","","",5);

print "<HTML><HEAD><TITLE>List of Menus -" .$_SESSION["UserName"]."</TITLE>";
}
?>
<SCRIPT LANGUAGE=javascript>
<!--
var gstrView='<?php print $strView; ?>';
function ModifyData(ID) {
	location.replace('AddModifyInput.php?action=MOD&ID='+ID+'&View='+gstrView);
	}
function AddData() {
	location.replace('AddModifyInput.php?action=ADD&View='+gstrView);
	}
function DeleteData(ID) {
	if (confirm ("This will delete this record?")) {
		location.replace('AddModifyDelete.php?action=DEL&ID='+ID+'&View='+gstrView);
		}
	}

//-->
</SCRIPT>
<?php
include_once ("../../Includes/Styles.php");

print "</HEAD><BODY><H5 align=right>";
print "<A HREF=\"List.php?View=All\">All Menus</A> &nbsp;";
print "<A HREF=\"List.php?View=Active\">Active Menus</A> &nbsp;";
print "<A HREF=\"List.php?View=Inactive\">Inactive Menus</A> &nbsp;";
print "</H5>";

print "<FORM action=\"\" method=POST id=frmForm name=frmForm>";
print "<TABLE border=1>";
print  $strTable;
?>

</TABLE>
<TABLE>	<TR>
	<TD><INPUT type="button" value="Add" id=cmdAdd name=cmdAdd  onclick = "AddData();">
	</TD>
</TR>
<TABLE>
</FORM>


</BODY>
</HTML>
