<?php
//Read in config file
$thisfile = "backup";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="images/icon8-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav7; ?></TD>
    <TD rowspan="2" width="0"><A href="help/6.htm#import"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>
<FORM name="addlink" method="post" action="test.php">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <?php
	if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_backup, $la_title_restore, $la_title_import);
	$nav_links_admin[$la_title_backup]="backup.php$att_sid";
	$nav_links_admin[$la_title_restore]="restore.php$att_sid";
	$nav_links_admin[$la_title_import]="import.php$att_sid";
	echo display_admin_nav($la_title_import, $nav_names_admin, $nav_links_admin);
?>
  <TR> 
      <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_import ?></TD>
  </TR>

          <TR> 
            <TD valign="top" bgcolor="#F6F6F6"><SPAN class="tableitem">
			<a href="import/index.php<?php echo $att_sid;?>" class="tableitem">
              In-link 1.x
              </a> <img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		  <TR> 
            <TD valign="top" bgcolor="#DEDEDE"><SPAN class="tableitem">
			<a href="threads/install_cat.php<?php echo $att_sid;?>" class="tableitem">
              Gossamer Threads Links 2.x flat-file
              </a> <img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
          <TR> 
            <TD valign="top" bgcolor="#F6F6F6"><SPAN class="tableitem">
			<a href="linkssql/index.php<?php echo $att_sid;?>" class="tableitem">
              Gossamer Threads Links 2.x SQL
              </a> <img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		   <TR> 
            <TD valign="top" bgcolor="#DEDEDE"><SPAN class="tableitem">
			<a href="indexu/index.php<?php echo $att_sid;?>" class="tableitem">
              Sentraweb Indexu 2.x
              </a> <img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		  <TR> 
            <TD valign="top" bgcolor="#F6F6F6"><SPAN class="tableitem">
			<a href="csv/install_link.php<?php echo $att_sid;?>" class="tableitem">
              Custom CSV file
              </a> <img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>

</TABLE>
</FORM>
</BODY>
</HTML>
