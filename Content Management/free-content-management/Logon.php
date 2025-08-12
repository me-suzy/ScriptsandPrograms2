<?php 
session_start(); 
include("Includes/PortalConection.php");
include("Includes/Database.php");

	if (!isset($_REQUEST["txtUserID"]))
	{
		$strUserID="";
	}
	else
	{
//			session_start(); 
			$strUserID= QuerySafeString($_REQUEST["txtUserID"]);
	}
$strErrorMessages="";

if ($strUserID != "")
{
	$strPassword= QuerySafeString($_REQUEST["txtPassword"]);
	$strUserID= strtoupper($strUserID);
	$conclass =new DataBase();
	$strsql = "SELECT Password ,UserName, AdminUser"; 
	$strsql .=" FROM cms_t_users WHERE UserID='" . $strUserID . "' AND Active='Y'" ;

	$rst= $conclass->Execute ($strsql,$strErrorMessages);
	$strErrorMessages="Invalid user id/password combination";	
	while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	     {
		if ($strPassword==$line['Password'])
		{
			$strErrorMessages="";

			$_SESSION['UserID']= $strUserID;
			$_SESSION['UserName']=$line['UserName'];
			$_SESSION['Admin']="Y";
			//print $_SESSION['UserID'];
			Redirect('Frames.php');
			//header("Location: Frames.php");
			return;
		}	
		else
		{
			$strErrorMessages="Invalid user id/password combination";	
		}
	     }

}

print '<HTML><HEAD><TITLE>CMS</TITLE>';

include("Includes/Styles.php");
?>

<SCRIPT LANGUAGE=javascript>
<!--
function myLogin() { 
	var strUserID = document.frmLogon.txtUserID.value;
 	if (!strUserID ) {
		document.frmLogon.txtUserID.focus();
	    alert("User id cannot be Empty");
	    return;
	    } 
	var strPassword=document.frmLogon.txtPassword.value;
	if (!strPassword) {
		document.frmLogon.txtPassword.focus();
		alert("Password cannot be empty");
		return;
		}
			document.frmLogon.submit();            
	}     
function myCancel() { 
	document.frmLogon.txtUserID.value='';
	document.frmLogon.txtPassword.value='';
	}     
function forgetPassword() { 
	var objChk=window.open('Forgetpassword.php', 'password', 'toolbar=no, directories=no, location=no, status=yes, menubar=no, resizable=no, scrollbars=no, width=300, height=200'); 
	if (window.focus) {objChk.focus()}

	}     

//-->
</SCRIPT>

</HEAD>
<BODY>
<?php
 print $strErrorMessages;
 ?>
<FORM action="" method=POST id=frmLogon name=frmLogon>
<TABLE WIDTH=100%>
	<TR align=center valign=middle>
		<TD valign=middle align=center> 

<table border='2' cellspacing='0' cellpadding='6' bordercolor='#C0C0C0'>
	<TR>
		<TD>Login ID</TD>
		<TD><INPUT type="text" id=txtUserID name=txtUserID  maxlength=10 size=27></TD>
	</TR>
	<TR>
		<TD>Password</TD>
		<TD><INPUT type="password" id=txtPassword name=txtPassword maxlength=10 size=27></TD>
	</TR>
	<TR>
		<TD style="border:none">&nbsp; <A HREF="javaScript:forgetPassword();">Forget Password?</A></TD>
		<TD style="border:none"><TABLE><TR>
		<TD><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Login" id=cmdLogin name=cmdLogin onclick = "myLogin();"></TD>
		<TD><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Cancel" id=cmdCancel name=cmdCancel onclick = "myCancel();"></TD>
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
