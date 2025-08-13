<?php
//Read in config file
$admin = 1;
$backup_inport = 1;
$configfile = "../../includes/config.php";
include($configfile);
$conn->Execute("Delete from inl_config where name='indexu_user'");
$conn->Execute("Delete from inl_config where name='indexu_pass'");
$conn->Execute("Delete from inl_config where name='indexu_db'");
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
      <TD class="tabletitle" bgcolor="#666666">Indexu</TD>
  </TR>
  <TR>
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
          <TR> 
            <TD valign="top" class="text">Indexu Database:<BR>
			</TD>
            <TD> 
              <INPUT type="text" name="indexu_db" class="text" size="30" value="">
			</TD>
          </TR>
		  <TR bgcolor="#DEDEDE"> 
		    <TD class="text">Indexu User:</TD>
            <TD> 
			  <INPUT type="text" name="indexu_user" class="text" size="30" value="">
			</TD>
		    
		</TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="#F6F6F6">Indexu Password:</TD>
            <TD bgcolor="#F6F6F6"> 
			  <INPUT type="password" name="indexu_pass" class="text" size="30" value="">
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