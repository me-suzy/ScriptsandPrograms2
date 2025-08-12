<?php
session_start(); 
include("../Includes/PortalConection.php");
include("../Includes/Database.php");

$strRootpath= "../";
include_once ("../Includes/validsession.php");

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

if (!isset($_REQUEST["txtCurrentFolder"]))
{
	$strCurrentFolder="";
}
else
{
	$strCurrentFolder= QuerySafeString($_REQUEST["txtCurrentFolder"]);
}


$strTable = FileListTable( ImageUploadPath,"","","" );
?>
<HEAD>
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

//-->
</SCRIPT>
<?php
include ("../Includes/Styles.php");
print "</HEAD><BODY>";
?>
<BODY>
<h3><img src="../Includes/image.gif" alt="List Images" width="36" height="36" border="0"> List Images</h3>
<FORM action="" method=POST id=frmForm name=frmForm>
<table WIDTH=75% border="2" cellspacing="0" cellpadding="2" bordercolor="#C0C0C0">
	<TR>
		<TH>No</TH>
		<TH>File Name</TH>
		<TH>Delete</TH>
	</TR>
<?php
	print $strTable;
?>	
</TABLE>

<INPUT type="hidden" id=txtAction name=txtAction>
<INPUT type="hidden" id=txtFile name=txtFile>
<INPUT type="hidden" id=txtType name=txtType>
<INPUT type="hidden" id=txtCurrentFolder name=txtCurrentFolder value='<?php print $strCurrentFolder;?>'>
</FORM>

</BODY>
</HTML>
