<?php
session_start();

print "<HTML><HEAD><TITLE>Install Scripts </TITLE>";
?>

<SCRIPT LANGUAGE=javascript>
<!--
function myInstall() { 
	var strTemp = document.frmForm.txtServer.value;
 	if (!strTemp ) {
		document.frmForm.txtServer.focus();
	    alert("Server name cannot be Empty");
	    return;
	    } 
	    
	strTemp=document.frmForm.txtAdmin.value;
	if (!strTemp) {
		document.frmForm.txtAdmin.focus();
		alert("Admin User ID cannot be empty");
		return;
		}
		
	strTemp=document.frmForm.txtNEWSDB.value;
	if (!strTemp) {
		document.frmForm.txtNEWSDB.focus();
		alert("News Database Name cannot be empty");
		return;
		}
	document.frmForm.submit();            
	}     

//-->
</SCRIPT>


<?php
include_once ("../Includes/Styles.php");
print "</HEAD><BODY>";

print "<FORM action=\"Install.php\" method=POST id=frmForm name=frmForm>";

?>
<TABLE  BORDER=1 CELLSPACING=1 CELLPADDING=1>
	<TR>
		<TD>Server Name</TD>
		<TD><INPUT type="text" id=txtServer name=txtServer   size=27></TD>
	</TR>
	<TR>
		<TD>Admin User ID</TD>
		<TD><INPUT type="text" id=txtAdmin name=txtAdmin size=27></TD>
	</TR>
	
	<TR>
		<TD>Admin Password</TD>
		<TD><INPUT type="text" id=txtAdminPassword name=txtAdminPassword size=27></TD>
	</TR>

	<TR>
		<TD>CMS Database Name</TD>
		<TD><INPUT type="text" id=txtNEWSDB name=txtNEWSDB size=27></TD>
	</TR>
	<TR>
		<TD style="border:none">&nbsp;</TD>
		<TD style="border:none"><TABLE><TR>
		<TD><INPUT class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type="button" value="Install" id=cmdInstall name=cmdInstall onclick = "myInstall();"></TD>
		</TR></TABLE>
		</TD>
	</TR>
</TABLE>

</FORM>
</BODY>
</HTML>
