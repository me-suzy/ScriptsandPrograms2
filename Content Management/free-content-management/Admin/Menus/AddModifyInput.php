<?php
session_start();
include("../../Includes/PortalConection.php");
include("../../Includes/Database.php");

$strRootpath= "../../";
include_once ("../../Includes/validsession.php");


function FileListOption($pstrFilePath,$pstrFileTypeNeeded,$pstrRelativefolderpath,$pstrDefaultValue)
{

    $dh = opendir("../".$pstrFilePath); 
    $strTemp="";
       while (false !== ($file = readdir($dh))) { 
	//Don't list subdirectories 
          if (!is_dir("$pstrFilePath$file")) { 
	//Truncate the file extension and capitalize the first letter 
		if (stristr($file, $pstrFileTypeNeeded)==TRUE)
		{
			$strTemp1= $pstrRelativefolderpath . $file;
			$strTemp2=htmlspecialchars(ucfirst(preg_replace('/\..*$/', '', $file)));
			if ($pstrDefaultValue=="")
				{$strTemp.= "<OPTION value ='" . $strTemp1 . "' >" .$strTemp2 . "</OPTION>";}
			elseif ($pstrDefaultValue == $strTemp1)
				{$strTemp.= "<OPTION value ='" . $strTemp1 . "' selected>" .$strTemp2. "</OPTION>";}
			else
				{$strTemp.= "<OPTION value ='" .$strTemp1 . "' >" .$strTemp2 . "</OPTION>";}
		}
   		} 
	} 
closedir($dh); 
return $strTemp;

}

	if (!isset($_GET['ID']))
	{
		$strID="0";
	}
	else
	{
		$strID=QuerySafeString($_GET["ID"]);
	}
	if (!isset($_GET['View']))
	{
		$strView="Active";
	}
	else
	{
		$strView=QuerySafeString($_GET["View"]);
	}
$conclass =new DataBase();

$strTitle="";
$strHyperLinkCombo="";
$strActive="";
$strSequence="";
$strHyperLinkText="";
$strError ="";
if (($strID != "") && ($strID != "0"))
{
	$strsql = "SELECT * FROM cms_t_menus WHERE ID=" . $strID;
	$rst= $conclass->Execute ($strsql,$strError);
	if ($strError=="")
	{
		while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	     {
			$strID=$line['ID'];
			$strTitle=$line['Title'];
			$strHyperLinkCombo=$line['HyperLink'];
			$strActive=$line['Active'];
			$strSequence=$line['DisplaySequence'];
		}
	}
	//print stristr(strtoupper($strHyperLinkCombo),'HTTP');
	//print strtoupper($strHyperLinkCombo);
	if (stristr($strHyperLinkCombo,"HTTP")==TRUE)
	{
		$strTable = FileListOption( FileUploadPath,".HTM",FileUploadPathRelative,"");
		$strHyperLinkText=$strHyperLinkCombo;
	}
	else
	{	$strTable = FileListOption( FileUploadPath,".HTM",FileUploadPathRelative,$strHyperLinkCombo);
	}
	
}
else
{
	$strTable = FileListOption( FileUploadPath,".HTM",FileUploadPathRelative,"");
}
print "<HTML><HEAD><TITLE>";
if ($strID!="")
{	print "Menu - " . $strTitle;
}
else
{	print "Add new menu";
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
		alert('Please enter title');
		objControl.focus();
		return;
	}
	objControl=vfrmForm.txtHyperlink ;
	strTemp=objControl.value;
	if (!strTemp ) {
		objControl=vfrmForm.cmbHyperlink ;
		strTemp=objControl.value;
		if (!strTemp ) {
			alert('Please enter URL');
			objControl.focus();
			return;
		}
	}
	objControl=vfrmForm.txtSequence ;
	strTemp=objControl.value;
	if (!strTemp ) {
		alert('Please enter display sequence');
		objControl.focus();
		return;
	}
	objControl=vfrmForm.chkActive ;
	vfrmForm.txtActive.value = 'N';
	if (objControl.checked==true) {
		vfrmForm.txtActive.value = 'Y';
	}

	vfrmForm.submit();
	}
function AbortChanges() {
	location.replace('List.php?View=<?php print $strView;?>');
	}

//-->
</SCRIPT>
</HEAD>
<BODY>

<FORM action="AddModifyDelete.php" method=POST id=frmForm name=frmForm>
<TABLE border=1>
	<TR>
		<?php if (($strID=="") || ($strID=="0"))
		{null;}
		else
		{print "<TD>Menu ID</TD>";
		print "<TD>".$strID."</TD>";
		}

		?>
			
	</TR>
	<TR>
		<TD>Title</TD>
		<TD><INPUT type="text" id=txtTitle name=txtTitle value='<? print $strTitle;?>'  maxlength=100></TD>
	</TR>
	<TR>
		<TD>URL</TD>
		<TD>
		<INPUT type="text" id=txtHyperlink name=txtHyperlink value='<? print $strHyperLinkText;?>'  maxlength=255 size=50>
		<br>Must have http at the beginning and must be a full address like http://www.cnn.com
		<BR>
		<SELECT size=2 id=cmbHyperlink name=cmbHyperlink>
		<OPTION value="">Please select</OPTION>
		<?php print $strTable;?>
		</SELECT>
		</TD>
	</TR>
	<TR>
		<TD>Display Sequence</TD>
		<TD>
		<INPUT type="Text" id=txtSequence name=txtSequence value='<? print $strSequence;?>'  maxlength=5></TD>
	</TR>
	<TR>
		<TD>Active</TD>
		<TD>
		<INPUT type="checkbox" id=chkActive name=chkActive value='<? print $strActive;?>' <?php if ($strActive=="Y") {print "checked";}?> >
		</TD>
	</TR>


	<TR bordercolor=White>
		<TD  style="border:none">&nbsp;
		</TD>
		<TD align=center style="border:none">
			<TABLE>	<TR>
				<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="<?php 
													if (($strID == "") || ($strID == "0")) 
													{
														print "Add"; 
													}
													else 
													{
														print "Modify";
													} 
													?>" id=cmdOK name=cmdOK  onclick = "SaveData();">
				</TD>
				<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Cancel" id=cmdCancel name=cmdCancel  onclick = "AbortChanges();">
				</TD>
			</TR>
		<TABLE>
		</TD>
	</TR>

</TABLE>
<INPUT type="hidden" id=txtID name=txtID value="<?  print $strID;?>">
<INPUT type="hidden" id=txtActive name=txtActive value=''>
<INPUT type="hidden" id=txtView name=txtView value="<? print $strView;?>">

</FORM>

<P>&nbsp;</P>

</BODY>
</HTML>