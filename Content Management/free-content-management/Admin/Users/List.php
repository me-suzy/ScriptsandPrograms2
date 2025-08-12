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
		$strsql = "SELECT UserID as 'User ID',Password as 'Password',UserName as 'Name',Email as 'E-Mail',AdminUser as 'Admin' ,Active" ;
		$strsql .=" FROM cms_t_users ORDER BY UserID ASC";
	}
	else
	{
		if ($strView=="Inactive")  
		{
			$strsql = "SELECT UserID as 'User ID',Password as 'Password',UserName as 'Name',Email as 'E-Mail',AdminUser as 'Admin' ,Active" ;
			$strsql	.= " FROM cms_t_users WHERE Active='N' ORDER BY UserID ASC";
		}
		else 
		{
			$strsql = "SELECT UserID as 'User ID',Password as 'Password',UserName as 'Name',Email as 'E-Mail',AdminUser as 'Admin' ,Active" ;
			$strsql	.=" FROM cms_t_users WHERE Active='Y' ORDER BY UserID ASC";
		}
	}

$strTable=TableBodyList($strsql,"","","","",6);
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
include ("../../Includes/Styles.php");
?>

</HEAD>
<BODY>
<h5>
<A HREF="List.php?View=All">All Users</A> &nbsp;
<A HREF="List.php?View=Active">Active Users</A> &nbsp;
<A HREF="List.php?View=Inactive">Inactive Users</A> &nbsp;

</H5>

<FORM action="" method=POST id=frmForm name=frmForm>
<table border='2' cellspacing='0' cellpadding='6' bordercolor='#C0C0C0'>


<?php
print $strTable;
?>

</TABLE>
<TABLE>	<TR>
	<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Add" id=cmdAdd name=cmdAdd  onclick = "AddData();">
	</TD>
</TR>
<TABLE>
</FORM>


</BODY>
</HTML>
