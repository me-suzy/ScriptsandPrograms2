<?php
session_start();
include("../../Includes/PortalConection.php");
include("../../Includes/Database.php");

$strRootpath= "../../";
include_once ("../../Includes/validsession.php");

$strsql="";
$strView="Active";
$strTable="";
If ($_SESSION["Admin"] =="Y")
{
	if (!isset($_GET['View']))
	{
		$strView="Active";
	}
	else
	{
		$strView=QuerySafeString($_GET["View"]);
	}
	
	if ($strView=="All") 
	{
		$strsql = "SELECT userid as 'User ID',password as 'Password',username as 'Name',email as 'E-Mail',active as 'Active'" ;
		$strsql .=" FROM pages_t_users ORDER BY userid ASC";
	}
	else
	{
		if ($strView=="Inactive")  
		{
			$strsql = "SELECT userid as 'User ID',password as 'Password',username as 'Name',email as 'E-Mail',active as 'Active'" ;
			$strsql	.= " FROM pages_t_users WHERE active='N' ORDER BY userid ASC";
		}
		else 
		{
			$strsql = "SELECT userid as 'User ID',password as 'Password',username as 'Name',email as 'E-Mail',active as 'Active'" ;
			$strsql	.=" FROM pages_t_users WHERE active='Y' ORDER BY userid ASC";
		}
	}

$strTable=TableBodyList($strsql,"","","","",5);
}
?>

<HTML>
<HEAD>
<TITLE>List of Users </TITLE>
<SCRIPT LANGUAGE=javascript>
<!--
var gstrView='<?php print $strView?>';
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
print "</HEAD><BODY>";
print "<TABLE border=0>";
print "<TR><TD width=25% VALIGN=TOP>";
include_once ("../../navigation.php");
print "</TD></TR>";
print "<TR><TD>";

print "<H5 align=left style='margin-left:10px;'>";
print "<A HREF=\"List.php?View=All\">All Users</A> &nbsp;";
print "<A HREF=\"List.php?View=Active\">Active Users</A> &nbsp;";
print "<A HREF=\"List.php?View=Inactive\">Inactive Users</A> &nbsp;";
print "</H5>";

print "<FORM action=\"\" method=POST id=frmForm name=frmForm>";
print "<table border='2' cellspacing='0' cellpadding='4' bordercolor='#ff8811'>";
print  $strTable;
?>

</TABLE>
<TABLE>	<TR>
	<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Add" id=cmdAdd name=cmdAdd  onclick = "AddData();">
	</TD>
</TR>
<TABLE>
</FORM>
<?php
print "</TD></TR>";
print "</TABLE>";
?>
<? include("../../Includes/data-t.php"); ?>

</BODY>
</HTML>
