<?php
session_start();
include("../../Includes/PortalConection.php");
include("../../Includes/Database.php");

$strRootpath= "../../";
include_once ("../../Includes/validsession.php");



if (!isset($_GET['ID']))
{
	$strID="0";
}
else
{
	$strID=QuerySafeString($_GET["ID"]);
}
$conclass =new DataBase();

$strTitle="";
$strDetails="";
$strStartPage="";
$strError ="";
if ($strID != "0")
{
	$strsql = "SELECT id, title,description,startpage FROM pages_t_details WHERE id=" . $strID;
	$rst= $conclass->Execute ($strsql,$strError);
	if ($strError=="")
	{
		while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	     {
			$strID=$line['id'];
			$strTitle=$line['title'];
			$strDetails=$line['description'];
			$strStartPage=$line['startpage'];
		}
	}
	
}
print "<HTML><HEAD><TITLE>";
if ($strID!="0")
{	print "Page - " . $strTitle;
}
else
{	print "Add new page";
}

print "</TITLE>";
include ("../../Includes/Styles.php");
?>

<SCRIPT LANGUAGE=javascript>
<!--

function SaveData() {
 var vfrmForm = document.frmForm;
 var objControl;
 var strTemp;
	objControl=vfrmForm.txtTitle ;
	strTemp=objControl.value;
	if (!strTemp ) {
		alert('Please enter page description');
		objControl.focus();
		return;
	}
	objControl=vfrmForm.txtDescription ;
	strTemp=objControl.value;
	if (!strTemp ) {
		alert('Please enter page contents');
		objControl.focus();
		return;
	}


	vfrmForm.submit();
	}
function AbortChanges() {
	location.replace('List.php');
	}

//-->
</SCRIPT>
</HEAD>
<BODY>
<?php
print "<TABLE border=0>";
print "<TR><TD width=15% VALIGN=TOP>";
include_once ("../../navigation.php");
print "</TD></TR>";
print "<TR><TD>";
?>

<FORM action="AddModifyDelete.php" method=POST id=frmForm name=frmForm>
<TABLE border=1 width=70%>
	<TR bordercolor=White>
		<TD  style="border:none">&nbsp;
		</TD>
		<TD align=center style="border:none">
			<TABLE>	<TR>
				<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Save" id=cmdOK name=cmdOK  onclick = "SaveData();">
				</TD>
				<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Cancel" id=cmdCancel name=cmdCancel  onclick = "AbortChanges();">
				</TD>
			</TR>
		<TABLE>
		</TD>
	</TR>

	<TR>
		<?php if (($strID=="") || ($strID=="0"))
		{null;}
		else
		{print "<TD>ID :";
		print $strID."</TD>";
		}

		?>
			
	</TR>
	<TR>
		<TD>Page Description</TD>
	</TR>
	<TR>
		<TD><INPUT type="text" id=txtTitle name=txtTitle value='<?print $strTitle;?>'  maxlength=255 size=100></TD>
	</TR>
	<TR>
		<TD>Page Contents</TD>
	</TR>
	<TR>
		
		<TD>
		<TEXTAREA rows=15 cols=80 id=txtDescription name=txtDescription><? print $strDetails;?></TEXTAREA>


		</TD>
	</TR>


</TABLE>
<INPUT type="hidden" id=txtID name=txtID value="<?print $strID;?>">
<INPUT type="hidden" id=txtAction name=txtAction>

</FORM>
<IFRAME SRC="../../index.php?id=<?print $strID;?>" WIDTH =100% height=400 TITLE="Preview"  style="background:#FFFFFF; color:#FFFFFF"> >
<!-- Alternate content for non-supporting browsers -->
<H2>The Famous Recipe</H2>
<H3>Ingredients</H3>
</IFRAME>
<?php
print "</TD></TR>";
print "</TABLE>";
?>

<? include("../../Includes/data-t.php"); ?>


</BODY>
</HTML>