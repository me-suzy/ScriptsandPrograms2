<?php
session_start(); 
include("../../Includes/PortalConection.php");
include("../../Includes/Database.php");

$strRootpath= "../../";
include_once ("../../Includes/validsession.php");

function FileListTable($pstrFilePath,$pstrFileTypeNeeded,$pstrDataRowParameters, $pstrDataColumnParameters)
{

    $dh = opendir($pstrFilePath); 
    $strTemp="";
    $intCounter =1;
       while (false !== ($file = readdir($dh))) { 
//Don't list subdirectories 
          if (!is_dir("$pstrFilePath/$file")) { 
//Truncate the file extension and capitalize the first letter 
		//if (stristr($file, $pstrFileTypeNeeded)==TRUE)
		{
		$strTemp.=  "<TR " . $pstrDataRowParameters . ">";
		$strTemp.=  "<TD " . $pstrDataColumnParameters . ">" . $intCounter . "</TD>";
		$strTemp.=  "<TD " . $pstrDataColumnParameters . ">" ;
		$strTemp.= $file. "</TD>";
		$strTemp.=  "<TD " . $pstrDataColumnParameters . "><A HREF =\"javaScript:DeleteFile('" . $file . "','File')\">Delete</TD>";

		$strTemp.= "</TR>";
		$intCounter=1 + $intCounter;
		}
   } 
   } 

return $strTemp;

}

if (!isset($_REQUEST["txtFile"]))
{
	$strFileName="";
}
else
{
	$strFileName= QuerySafeString($_REQUEST["txtFile"]);
}



$strTable = FileListTable(ImageUploadPath,"","","" );
?>
<HEAD>
<TITLE>File Management
</TITLE>

<SCRIPT LANGUAGE=javascript>
<!--

function DeleteFile(ID,Type) { 
	if (confirm ("This will delete this?")) {
		document.frmForm.txtFile.value=ID;
		document.frmForm.txtAction.value='DEL';
		document.frmForm.action='DeleteImage.php';
		document.frmForm.submit();
	}   
}
function AddFile() {
	location.replace('UploadFilesImage.php');
	}

//-->
</SCRIPT>
<?php
include ("../../Includes/Styles.php");
print "</HEAD><BODY>";
print "<TABLE border=0>";
print "<TR><TD width=25% VALIGN=TOP>";
include_once ("../../navigation.php");
print "</TD></TR>";

print "<TR><TD>";

?>
<FORM action="" method=POST id=frmForm name=frmForm>
<table border='2' cellspacing='0' cellpadding='4' bordercolor='#ff8811' width='70%'>
	<TR>
		<TH>No</TH>
		<TH>File Name</TH>
		<TH>Delete</TH>
	</TR>
<?php
	print $strTable;
?>	
</TABLE>
<TABLE>	<TR>
	<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Add" id=cmdAdd name=cmdAdd  onclick = "AddFile();">
	</TD>
</TR>
<TABLE>

<INPUT type="hidden" id=txtAction name=txtAction>
<INPUT type="hidden" id=txtFile name=txtFile>
<INPUT type="hidden" id=txtType name=txtType>
</FORM>
<?php
print "</TD></TR>";
print "</TABLE>";
?>
<? include("../../Includes/data-t.php"); ?>
</BODY>
</HTML>
