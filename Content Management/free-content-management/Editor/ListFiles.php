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
		$strTemp.=  "<TR " . $pstrDataRowParameters . ">";
		$strTemp.=  "<TD " . $pstrDataColumnParameters . ">" . $intCounter . "</TD>";
		$strTemp.=  "<TD " . $pstrDataColumnParameters . "><A HREF =\"javaScript:ModifyFile('" . $file. "','File')\" >" ;
		$strTemp.= $file. "</A></TD>";
		$strTemp.=  "<TD " . $pstrDataColumnParameters . "><A HREF =\"javaScript:DeleteFile('" . $file . "','File')\">Delete</TD>";

		$strTemp.= "</TR>";
		$intCounter=1 + $intCounter;
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


$strTable = FileListTable( FileUploadPath,"HTM","","" );
?><HEAD>
<SCRIPT LANGUAGE=javascript>
<!--
function AddFile(Type) { 
	document.frmForm.txtFile.value='';
	if (Type == 'File') 
	{
		document.frmForm.txtAction.value='ADD';
		document.frmForm.action='HTMLModify.php';
	}
	else
	{
		document.frmForm.txtAction.value='ADD';
		document.frmForm.action='HTMLModify.php';
	}

	document.frmForm.submit();
	
}     

function ModifyFile(ID,Type) { 
	document.frmForm.txtFile.value=ID;
	if (Type=='File') 
	{
		document.frmForm.txtAction.value='MOD';
		document.frmForm.action='HTMLModify.php';
	}
	else
	{
		document.frmForm.txtAction.value='';
		document.frmForm.action='ListFiles.php';
	}

	document.frmForm.submit();
	
}     
function DeleteFile(ID,Type) { 
	if (confirm ("This will delete this?")) {
		document.frmForm.txtFile.value=ID;
		document.frmForm.txtAction.value='DEL';
		document.frmForm.action='SaveDeleteFile.php';
		document.frmForm.submit();
	}   
}

//-->
</SCRIPT>
<?php
include ("../Includes/Styles.php");
print "</HEAD><BODY>";
?>

<FORM action="" method=POST id=frmForm name=frmForm>
<TABLE WIDTH=75% BORDER=1 CELLSPACING=1 CELLPADDING=1>
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
	<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Add" id=cmdAdd name=cmdAdd  onclick = "AddFile('File');">
	</TD>
</TR>
<TABLE>

<INPUT type="hidden" id=txtAction name=txtAction>
<INPUT type="hidden" id=txtFile name=txtFile>
<INPUT type="hidden" id=txtType name=txtType>
<INPUT type="hidden" id=txtCurrentFolder name=txtCurrentFolder value='<?php print $strCurrentFolder;?>'>
</FORM>

</BODY>
</HTML>
