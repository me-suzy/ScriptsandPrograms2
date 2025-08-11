<?php
include("Includes/PortalConection.php");
include("Includes/Database.php");

$strRootpath= "";
print "<HTML><HEAD>";

if (!isset($_REQUEST["txtUserID"]))
{
	$strUserID="";
}
else
{
	$strUserID= QuerySafeString($_REQUEST["txtUserID"]);
}
$strErrorMessages="";
if ($strUserID !="")
{
	$strUserID= strtoupper($strUserID);
	$conclass =new DataBase();
	$strsql = "SELECT password,username, email";
	$strsql .=	" FROM pages_t_users WHERE userid='" . SQLSafeString($strUserID) . "'" ;
	$rst= $conclass->Execute ($strsql,$strErrorMessages);
	$strErrorMessages="Invalid user id"	;
	while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	{
		$strPassword=$line["password"];
		$strUserName=$line["username"];
		$strPassword = "Your password is " . $strPassword;
		$strEmail=$line["email"];
		/* To send HTML mail, you can set the Content-type header. */
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		/* additional headers */
		$headers .= "To: $strUserName <$strEmail>\r\n";
		$headers .= "From: Password Reminder <".FromEMail.">\r\n";
		$to=$strEmail;
		/* and now mail it */
		mail($to, 'Your Password', $strPassword, $headers);
		$strErrorMessages="";
		
	}

}


print "<TITLE>Forget Password</TITLE>";
include ("Includes/Styles.php");
?>
<SCRIPT LANGUAGE=javascript>
<!--
function myemail() {
	var strUserID = document.frmLogon.txtUserID.value;
 	if (!strUserID ) {
		document.frmLogon.txtUserID.focus();
	    alert("User id cannot be Empty");
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
<?php if (($strErrorMessages=="") && ($strUserID!=""))
{
	print "<BODY onLoad=\"javaScript:myClose();\">";
}
else
{
print "<BODY>";
}
print $strErrorMessages;
?>
<FORM action="" method=POST id=frmLogon name=frmLogon>
<TABLE WIDTH=100%>
	<TR align=center valign=middle>
		<TD valign=middle align=center> 

		<TABLE  BORDER=1 CELLSPACING=1 CELLPADDING=1>
			<TR>
				<TD>Login ID</TD>
				<TD><INPUT type="text" id=txtUserID name=txtUserID  maxlength=10></TD>
			</TR>
			<TR>
				<TD style="border:none">&nbsp;</TD>
				<TD style="border:none"><TABLE><TR>
				<TD><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="E-mail" id=cmdemail name=cmdemail onclick = "myemail();"></TD>
				<TD><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Close" id=cmdClose name=cmdClose onclick = "myClose();"></TD>
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
