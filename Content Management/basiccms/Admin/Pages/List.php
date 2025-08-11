<?php
session_start();

include_once ("../../Includes/PortalConection.php");
include_once ("../../Includes/Database.php");
$strRootpath= "../../";
include_once ("../../Includes/validsession.php");


if (!isset($_POST['txtAction']))
{
	$strAction="";
}
else
{
	$strAction=QuerySafeString($_POST["txtAction"]);
}
if ($_SESSION["Admin"] =="Y")
{

$conclass =new DataBase();
if ($strAction=="DEL")
{
	if (isset($_POST['chkSelect']))
	{
		$strSelection=$_POST['chkSelect'];
		$strSQL="DELETE FROM pages_t_details WHERE id IN(". join(',', $strSelection) . ")";
		//print $strSQL;
		$strErrorMessages="";
		$var1=$conclass->Execute ($strSQL,$strErrorMessages);
	}

}
elseif ($strAction=="START")
{
	if (isset($_POST['chkSelect']))
	{
		$strSelection=$_POST['chkSelect'];
		$strSQL="UPDATE pages_t_details SET startpage='N' WHERE startpage='Y'";
		//print $strSQL;
		$strErrorMessages="";
		$var1=$conclass->Execute ($strSQL,$strErrorMessages);
		$strSQL="UPDATE pages_t_details SET startpage='Y' WHERE id =". join(',', $strSelection) ;
		//print $strSQL;
		$var1=$conclass->Execute ($strSQL,$strErrorMessages);

	}

}

$strsql = "SELECT id as 'ID',title as 'Description',startpage as 'Start Page'" ;
$strsql .=	" FROM pages_t_details ";
$strsql .=	" ORDER BY id ASC";
//print $strsql;
$strTable=TableBodyListForPage($strsql,"","","","",3);

$strsql = "SELECT id as 'id1' " ;
$strsql .=	" FROM pages_t_details WHERE startpage='Y'";
$strErrorMessages="";
$var1=$conclass->Execute ($strsql,$strErrorMessages);
	if ($strErrorMessages!="")
	{	$strErrorMessages = "Could not select stsrt page. <BR>" . $strErrorMessages;}
	$strID="Not Set";
	while ($line = mysql_fetch_array($var1, MYSQL_ASSOC)) 
	{
		$strID=$line["id1"];
	}

print "<HTML><HEAD><TITLE>List of Pages -" .$_SESSION["UserName"]."</TITLE>";
}
?>
<SCRIPT LANGUAGE=javascript>
<!--

function ModifyData(ID) {
	location.replace('AddModifyInput.php?action=MOD&ID='+ID);
	}
function AddData() {
	location.replace('AddModifyInput.php?action=ADD');
	}
	
	
function StartPageData() {
	if (LimitCheckbox(document.frmForm.elements['chkSelect[]'],1,1,'start page')) {
		if (confirm ("This will make this page as start page?")) {
			document.frmForm.txtAction.value='START';
			document.frmForm.submit();
			}
		}
	}

function DeleteData() {
	if (LimitCheckbox(document.frmForm.elements['chkSelect[]'],1,1000,'delete')) {
		if (confirm ("This will delete this record?")) {
			document.frmForm.txtAction.value='DEL';
			document.frmForm.submit();
			}
		}
	}
// This functions limits the checkbox seletsin option
function LimitCheckbox(objCheckbox,intMin,intMax,strFieldName) 
{ 
	var intSize=0;
	if (typeof objCheckbox==='undefined')
	{
		alert ('No records available for selection' );
		return false;
	}
	var intFullsize=objCheckbox.length;
	if (typeof intFullsize==='undefined')
	{
		if (objCheckbox.checked) {
			intSize=intSize+1;
		}
	} else 
	{
	for (var i=0;i< intFullsize; i++) {  
		if (objCheckbox[i].checked) {
			intSize=intSize+1;
		}
	}
	}
	if (intSize==0)  {
		alert ('At least one should be selected for ' + strFieldName );
		objCheckbox[0].focus();
		return false;
	}
	if (intSize<intMin)  {
		alert ('Minimun '+ intMin + ' should be selected for '  + strFieldName);
		objCheckbox[0].focus();
		return false;
	}

	if (intSize>intMax) {
		alert ('Maximun '+ intMax + ' should be selected for '  + strFieldName);
		objCheckbox[0].focus();
		return false;
	}
	return true;

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
print "<span style='margin-left:5px;'>Start Page : <B>$strID</B></span>";
print "</TD></TR>";

print "<TR><TD>";
print "<FORM action=\"\" method=POST id=frmForm name=frmForm>";
print "<table border='2' cellspacing='0' cellpadding='4' bordercolor='#ff8811' width='70%'>";
print  $strTable;
?>

</TABLE>

<TABLE>	<TR>
	<TD><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Add" id=cmdAdd name=cmdAdd  onclick = "AddData();">
	</TD>
	<TD align=left><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Set Start Page" id=cmdStartPage name=cmdStartPage  onclick = "StartPageData();">
	</TD>

	<TD align=left><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Delete" id=cmdDelete name=cmdDelete  onclick = "DeleteData();">
	</TD>

</TR>
<TABLE>
<INPUT type="hidden" id=txtAction name=txtAction>

</FORM>

<?php
print "</TD></TR>";
print "</TABLE>";
?>
<? include("../../Includes/data-t.php"); ?>
</BODY>
</HTML>
