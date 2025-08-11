<?php
session_start(); 
include ("../../Includes/PortalConection.php");
include ("../../Includes/Database.php");


$strRootpath= "../../";

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
	$strUserName="";
	$strPassword="";
	$strEmail="";
	$strActive="";

if (($strID != "") && ($strID != "0"))
	{
	
	$strsql = "SELECT userid,password,username,email,active FROM pages_t_users" ;
	$strsql .=" WHERE userid='".$strID."'";
	$strError="";
	$rst= $conclass->Execute ($strsql,$strError);

	if ($strError != "")
		{
		print $strError;
		}
	else
		{
		while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	     {
			$strID=$line['userid'];
			$strPassword=$line['password'];
			$strUserName=$line['username'];
			$strEmail=$line['email'];
			$strActive=$line['active'];
	     }
			
		}
	}
?>
<HTML>
<HEAD>
<TITLE>
<?php if ($strID != "" )
{
	print "User name ".$strUserName;
}
else
{
	print "Add new user";
}
?>

</TITLE>
<?php
include ("../../Includes/Styles.php");
?>

<SCRIPT LANGUAGE=javascript>
<!--
function SaveData() { 
 var vfrmForm = document.frmForm;
 var objControl;
 var strTemp;
<?php  if (!(($strID != "") && ($strID != "0")))
{
	print "objControl=vfrmForm.txtUserID;\n" ;
	print "strTemp=objControl.value;\n";
	print "if (!strTemp ) {\n";
	print "	alert('Please enter User ID');\n";
	print "	objControl.focus();\n";
	print "	return;\n";
	print "}\n";
}

?>
	objControl=vfrmForm.txtUserName ;
	strTemp=objControl.value;
	if (!strTemp ) {
		alert('Please enter user name');
		objControl.focus();
		return;
	}
	objControl=vfrmForm.txtPassword ;
	strTemp=objControl.value;
	if (!strTemp ) {
		alert('Please enter password');
		objControl.focus();
		return;
	}
	objControl=vfrmForm.txtEmail ;
	strTemp=objControl.value;
	if (!strTemp ) {
		alert('Please enter e-mail');
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
<?php
print "<TABLE border=0>";
print "<TR><TD width=15% VALIGN=TOP>";
include_once ("../../navigation.php");
print "</TD><TR>";
print "<TR><TD>";
?>

<FORM action="AddModifyDelete.php" method=POST id=frmForm name=frmForm>
<table border='2' cellspacing='0' cellpadding='4' bordercolor='#ff8811'>
	<TR>
		<TD>User ID</TD>
		<?php if (($strID=="") || ($strID=="0")) {?>
		<TD><INPUT type="text" id=txtUserID name=txtUserID maxlength=20  size=28></TD>
		<?php } else {?>
		<TD><?php print $strID;}?></TD>
		

	</TR>

	<TR>
		<TD>User Name</TD>
		<TD><INPUT type="text" id=txtUserName name=txtUserName value='<?php print $strUserName?>'  maxlength=50 size=28></TD>
	</TR>
	<TR>
		<TD>Password</TD>
		<TD>
		<INPUT type="Password" id=txtPassword name=txtPassword value='<?php print $strPassword?>'  maxlength=20 size=28></TD>
	</TR>
	<TR>
		<TD>E-mail</TD>
		<TD>
		<INPUT type="Text" id=txtEmail name=txtEmail value='<?php print $strEmail?>'  maxlength=50 size=28></TD>
	</TR>
	<TR>
		<TD>Active</TD>
		<TD>
		<INPUT type="checkbox" id=chkActive name=chkActive value='<?php print $strActive?>' <?php if ($strActive=="Y") {print "checked";} ?> >
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
<INPUT type="hidden" id=txtID name=txtID value="<?php print $strID;?>">
<INPUT type="hidden" id=txtActive name=txtActive value=''>
<INPUT type="hidden" id=txtView name=txtView value="<?php print $strView;?>">

</FORM>
<?php
print "</TD></TR>";
print "</TABLE>";
?>

<? include("../../Includes/data-t.php"); ?>
</BODY>
</HTML>