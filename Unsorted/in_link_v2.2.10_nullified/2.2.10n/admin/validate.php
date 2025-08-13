<?php
//Read in config file
$thisfile = "validate";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="images/icon2-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav2 ?></TD>
    <TD rowspan="2" width="0"><A href="help/6.htm#validation"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
	<TD class="tabletitle" bgcolor="#666666"><?php echo $la_validate_spec; ?></TD>
  </TR>
  <FORM name="form1" method="post" action="linksvalidate.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
	?>">
	<TR> 
	  <TD bgcolor="#f6f6f6"><?php echo $la_display_empty_urls;?> 
		<INPUT type="checkbox" name="urls" value="urls">
	  </TD>
	</TR>
	<TR> 
	  <TD bgcolor="#DEDEDE"> 
		<DIV align="center"> 
		  <INPUT type="submit" class="button" name="submit" value="<?php echo $la_button_go;?>">
		</DIV>
	  </TD>
	</TR>
  </FORM>
</TABLE>
<BR>
</BODY>
</HTML>