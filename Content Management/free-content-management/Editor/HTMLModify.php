<?php
session_start(); 
include("../Includes/PortalConection.php");
include("../Includes/Database.php");
$strRootpath= "../";
include_once ("../Includes/validsession.php");



if (!isset($_REQUEST['txtFile']))
{
	$strFileName="";
}
else
{
	$strFileName= QuerySafeString($_REQUEST['txtFile']);
}

if (!isset($_REQUEST['txtAction']))
{
	$strAction="";
}
else
{
	$strAction= QuerySafeString($_REQUEST['txtAction']);
}

?>
<HTML>
<HEAD>
<script language="Javascript1.2"><!-- //
function SaveFile() { 
	<?php if (($strFileName=="") || ($strFileName=="0"))
	{
		print "document.frmForm.txtFile.value=document.frmForm.txtFileName.value;";
	}?>
	document.frmForm.action='SaveDeleteFile.php';
	document.frmForm.submit();

	}     
function CancelEdit() { 
	if (confirm ("This will ignore all modifications made?")) {
		location.replace('ListFiles.php');
		}   
	}
// --></script> 

<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script> 
<script language="JavaScript1.2" defer>
editor_generate('textarea1');

</script>
<?php
include ("../Includes/Styles.php");
print "</HEAD><BODY>";

	$strHTML ="";
	$strBODY ="";
	$strTitle ="";
	$strHead ="";
	$strNoBODY ="";

if ($strAction=="ADD")
{
	$strHTML ="";
	$strBODY ="";
	$strTitle ="";
	$strHead ="";
	$strNoBODY ="";
}
else
{	
	$strHTML=file_get_contents("../".FileUploadPathRelative . $strFileName);
	
	if ($strHTML==FALSE)
	{null;}
	else
	{
		//print $strHTML;	
		$arr=getTitleBody($strHTML);
		//print $arr["BODY"];

		$strHead= FormatingOriginalHTML($arr["HEAD"]);
		$strNoBODY= FormatingOriginalHTML($arr["NOBODY"]);
		$strBODY=$arr["BODY"];
		//print $strBODY;
		$strTitle=$arr["TITLE"];
	}
}

?>

<FORM action="" method=POST id=frmForm name=frmForm>
<TABLE WIDTH="80%" BORDER=0 CELLSPACING=1 CELLPADDING=1>
	<TR>
		<TD width="10%"><B>File Name</B></TD>
		<?php if (($strFileName=="") or ($strFileName=="0") )
		{
			print "<TD width=\"90%\"><INPUT type=\"text\" id=txtFileName name=txtFileName maxlength=200  size=94></TD>";
		}
		else
		{
			print "<TD width=\"90%\">".$strFileName."</TD>";
		}
		
		?>
		
	</TR>

	<TR>
		<TD width="10%"><B>Title</B></TD>
		<TD width="90%">
		<INPUT type="text" id=txtTitle name=txtTitle value="<?php print $strTitle;?>" maxlength=200 size=94>
		</TD>
		
	</TR>
	<TR>
		<TD colspan=2>
		<TEXTAREA rows=25 cols=80 id=textarea1 name=textarea1><?php print $strBODY;?>
		</TEXTAREA>
		</TD>
	</TR>
	<TR>
		<TD></TD>
		<TD width="90%">
		<TABLE WIDTH="100%" BORDER=0 CELLSPACING=1 CELLPADDING=1>
		<TR>
			<TD align="right">
				<INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Save" id=cmdSave name=cmdSave onclick = "SaveFile();" >			
			</TD>
			<TD align="left">
				<INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Cancel" id=cmdCancel name=cmdCancel onclick = "CancelEdit();">			
			</TD>
		</TR>
		</TABLE>
		
	
		
		</TD>
		
	</TR>

</TABLE>
<INPUT type="hidden" id=txtAction name=txtAction value="<?php print $strAction;?>">
<INPUT type="hidden" id=txtFile name=txtFile  value="<?php print $strFileName;?>">
<INPUT type="hidden" id=txtHTMLHEAD name=txtHTMLHEAD  value="<?php print $strHead;?>">
<INPUT type="hidden" id=txtHTMLNoBODY name=txtHTMLNoBODY  value="<?php print $strNoBODY;?>">

</FORM>

<BR>
</BODY>
</HTML>