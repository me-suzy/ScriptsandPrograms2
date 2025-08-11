<?PHP
session_start(); 
include("Includes/PortalConection.php");
include("Includes/Database.php");

$strRootpath= "";
include_once ("Includes/validsession.php");

$strNewPassword= QuerySafeString($_REQUEST["txtNewPassword"]);
$strPassword= QuerySafeString($_REQUEST["txtPassword"]);
$strErrorMessages="";
if ($strPassword!="")
{
	$conclass =new DataBase();
	$strsql = "SELECT password ,username";
	$strsql .= " FROM pages_t_users WHERE UserID='" .SQLSafeString($_Session["UserID"]) . "'" 
	$rst= $conclass->Execute ($strsql,$strErrorMessages)
	$strErrorMessages="Invalid user id/password combination"	
	if ($strPassword=rst.fields("password"))
	{
		$strErrorMessages=""
		$strsql = "UPDATE pages_t_users SET password = '" .SQLSafeString(strNewPassword) . "' "; 
		$strsql = " WHERE userid = '" .SQLSafeString($_Session["UserID"]) . "'"; 
		$conclass->ExecuteDML ($strsql,$strErrorMessages)
		if ($strErrorMessages !="")
		{	$strErrorMessages = "Could not change the password. " . $strErrorMessages;}
		
	}
	else
		{$strErrorMessages="Invalid old password entered";}

}

print "<HTML><HEAD><TITLE>Change Password</TITLE>";
include ("Includes/Styles.php");
?>

<SCRIPT LANGUAGE=javascript>
<!--
function myChange() { 
	var strPassword = document.frmLogon.txtPassword.value;
 	if (!strPassword ) {
		document.frmLogon.txtPassword.focus();
	    alert("Please enter old password");
	    return;
	    } 
	strPassword=document.frmLogon.txtNewPassword.value;
	if (!strPassword) {
		document.frmLogon.txtNewPassword.focus();
		alert("Please enter new password");
		return;
		}
	var strPassword1=document.frmLogon.txtNewPassword1.value;
	if (!strPassword1) {
		document.frmLogon.txtNewPassword1.focus();
		alert("Please enter new password");
		return;
		}
	if (strPassword1!=strPassword) {
		alert("New passwords does not match please re enter");
		document.frmLogon.txtNewPassword1.value="";
		document.frmLogon.txtNewPassword.value="";
		return;
		}
		
			document.frmLogon.submit();            
	}     
function myClose() { 
	window.close();
	}     

//-->
</SCRIPT>

</HEAD>
<?php 
if (($strErrorMessages="") && ($strPassword !=""))
{print"<BODY onLoad=""javaScript:myClose();"">";}

else
{ print"<BODY>";}
end if
print $strErrorMessages;
?>
<FORM action="" method=POST id=frmLogon name=frmLogon>
<TABLE WIDTH=100%>
	<TR align=center valign=middle>
		<TD valign=middle align=center> 

<TABLE  BORDER=1 CELLSPACING=1 CELLPADDING=1>
	<TR>
		<TD>Old Password</TD>
		<TD><INPUT type="password" id=txtPassword name=txtPassword maxlength=10></TD>
	</TR>
	<TR>
		<TD>New Password</TD>
		<TD><INPUT type="password" id=txtNewPassword name=txtNewPassword maxlength=10></TD>
	</TR>
	<TR>
		<TD>New Password</TD>
		<TD><INPUT type="password" id=txtNewPassword1 name=txtNewPassword1 maxlength=10></TD>
	</TR>

	<TR>
		<TD  style="border: none">&nbsp;</TD>
		<TD style="border: none"><TABLE><TR>
		<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'" type="button" value="Change" id=cmdChange name=cmdChange onclick = "myChange();"></TD>
		<TD><INPUT  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Close" id=cmdClose name=cmdClose onclick = "myClose();"></TD>
		</TR></TABLE>
		</TD>
	</TR>
</TABLE>
		</TD>
	</TR>
</TABLE>

</FORM>
<P>&nbsp;</P>

</BODY>
</HTML>
