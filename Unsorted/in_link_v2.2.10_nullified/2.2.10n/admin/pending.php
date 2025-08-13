<?php
//Read in config file
$thisfile = "pending";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
include("../includes/admin_pending_lib.php");
pendinginfo();
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="images/icon2-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav2 ?></TD>
    <TD rowspan="2" width="0"><A href="help/6.htm#pend"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			$att_sid="sid=$sid&";
		echo $att_sid;
	?>action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
      <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_pending ?></TD>
  </TR>
  <TR> 
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
          <TR> 
            <TD valign="top" class="tableitem"><SPAN class="text"><a href="navigate.php?<?php echo $att_sid;?>t=pending_cats" class="tableitem">
              <?php echo $la_title_pending_cats ?>
              </a></SPAN><span class="catno">(<?php echo $pcats; ?>)</span> <SPAN class="text"><img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		  <TR> 
            <TD valign="top" bgcolor="DEDEDE" class="tableitem"><SPAN class="text"><a href="navigate.php?<?php echo $att_sid;?>t=pending_links" class="tableitem">
              <?php echo $la_title_pending_links ?>
              </a></SPAN><span class="catno">(<?php echo $plinks; ?>)</span> <SPAN class="text"><img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		   <TR> 
            <TD valign="top" class="tableitem"><SPAN class="text"><a href="navigate.php?<?php echo $att_sid;?>t=links_prev" class="tableitem">
              <?php echo $la_title_pending_reviews ?>
              </a></SPAN><span class="catno">(<?php echo $previews; ?>)</span> <SPAN class="text"><img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
<?php
  if($ses["user_perm"]!=5):
?>
          <TR> 
            <TD valign="top"  bgcolor="DEDEDE" class="tableitem"><a href="users.php?<?php echo $att_sid;?>pend=1" class="tableitem">
              <?php echo $la_title_pending_users ?>
              </a><span class="catno"> (<?php echo $pusers; ?>)</span> <span class="text"><img src="images/arrow1.gif" width="8" height="9"></span></TD>
          </TR>
<?php
  endif; 
?>
        </TABLE>
      </TD>
  </TR>
</TABLE>
<?php
  if ($ses["user_perm"]!=5): 
?>
	
<BR><br>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
      <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_validate_data; ?></TD>
  </TR>
  <TR> 
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
          <TR> 
            <TD valign="top" class="tableitem"><SPAN class="text"><a href="duplicatelinks.php?sid=<?php echo $sid; ?>" class="tableitem">
              <?php echo $la_duplicate_link_check; ?>
              </a></SPAN> <SPAN class="text"><img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		  <TR> 
            <TD valign="top" bgcolor="DEDEDE" class="tableitem"><SPAN class="text"><a href="users.php?sid=<?php echo $sid; ?>&duplicatemail=1" class="tableitem">
              <?php echo $la_duplicate_user_email_check; ?>
              </a></SPAN> <SPAN class="text"><img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		  <TR> 
            <TD valign="top" class="tableitem"><SPAN class="text"><a href="validate.php?sid=<?php echo $sid; ?>" class="tableitem">
              <?php echo $la_links_validation; ?>
              </a></SPAN> <SPAN class="text"><img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
        </TABLE>
      </TD>
  </TR>
</TABLE>
<BR><br>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
      <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_data_cleanup; ?></TD>
  </TR>
  <TR> 
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
          <TR> 
            <TD valign="top" class="tableitem"><SPAN class="text"><a href="data_cleanup.php?sid=<?php echo $sid; ?>" class="tableitem">
              <?php echo $la_title_database_maintenance_cleanup; ?>
				</a></SPAN> <SPAN class="text"><img src="images/arrow1.gif" width="8" height="9">
              </SPAN> </TD>
          </TR>
		 </TABLE>
      </TD>
  </TR>
</TABLE>

<?php
  endif;
?>

</BODY>
</HTML>
