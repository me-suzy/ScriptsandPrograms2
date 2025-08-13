<?php
//Read in config file
$admin = 1;
$backup_inport = 1;
$configfile = "../../includes/config.php";
include($configfile);
$conn->Execute("Delete from inl_config where name='links_user'");
$conn->Execute("Delete from inl_config where name='links_pass'");
$conn->Execute("Delete from inl_config where name='links_db'");                                               
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle;?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="../admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="../images/icon8-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav7; ?></TD>
    <TD rowspan="2" width="0"><A href="../help/manual.pdf"><IMG src="../images/but1.gif" width="30" height="32" border="0"></A><A href="../confirm.php?action=logout" target="_top"><IMG src="../images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>
<FORM name="install_link" method="get" action="import.php">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
      <TD class="tabletitle" bgcolor="#666666">Troubleshooting</TD>
  </TR>
  <TR> 
      <TD bgcolor="#F6F6F6"> <SPAN class="hint"><IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		Please make sure that the content of this page has not been cached!<BR>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		The two databases should be on the same server in order the import to 
		work!<BR>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		If there are any problematic categories, they will be inserted in a root 
		category cald &quot;Lost and Found&quot;!<BR>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		</SPAN> Please do not interupt the process before it is completely done!</TD>
  </TR>
  <TR> 
      <TD class="tabletitle" bgcolor="#666666">LinksSQL</TD>
  </TR>
  <TR>
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
          <TR> 
            <TD valign="top" class="text">LinksSQL Database:<BR>
			</TD>
            <TD> 
              <INPUT type="text" name="links_db" class="text" size="30" value="">
			</TD>
          </TR>
		  <TR bgcolor="#DEDEDE"> 
		    <TD class="text">LinksSQL User:</TD>
            <TD> 
			  <INPUT type="text" name="links_user" class="text" size="30" value="">
			</TD>
		    
		</TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="#F6F6F6">LinksSQL Password:</TD>
            <TD bgcolor="#F6F6F6"> 
			  <INPUT type="password" name="links_pass" class="text" size="30" value="">
			</TD>
          </TR>
		 </TABLE>
    
		  
    </TD>
  </TR>
</TABLE>
  <P> 
	<INPUT type="submit" name="submit" value="<?php echo $la_button_go; ?>" class="button">
<INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
<INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
</P>
</FORM>
</BODY>
</HTML>