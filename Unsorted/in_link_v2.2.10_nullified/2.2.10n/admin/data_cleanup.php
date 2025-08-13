<?php
//Read in config file
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="images/icon2-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav11; ?></TD>
    <TD rowspan="2" width="0"><A href="help/manual.pdf"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>

<?php
function update_links($sub=0)
{	global $conn;
	$query="select cat_id from inl_cats where cat_sub=$sub and cat_pend=0";
	
	$retval["cats"]=0;
	$retval["links"]=0;

	//all childern and their links
	$rs = &$conn->Execute($query);

	while ($rs && !$rs->EOF) 
	{	
		$val=update_links($rs->fields[0]);

		$retval["cats"]  += $val["cats"]+1;
		$retval["links"] += $val["links"];

		$query="Update inl_cats set cat_cats=".$val["cats"].",cat_links=".$val["links"]." where cat_id = ".$rs->fields[0];
		$conn->Execute($query);

		$rs->MoveNext();
	}

	//its own links
	$query="select count(link_id) from inl_lc where cat_id=$sub and link_pend=0";
	$rs = &$conn->Execute($query);

	if ($rs && !$rs->EOF) $retval["links"] += $rs->fields[0];

	return $retval; 
}

if(strlen($submit)>0)
{	
	if($update_links)
	{
		update_links(0); 
				
		echo "<hr noshade size=1><span class='text'><b>Update links and category count: DONE</b></span><hr noshade size=1></body></html>";
		exit();
	}
}

?>
<FORM name="maintenance" method="post" action="data_cleanup.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>">
  <TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
	<TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_database_maintenance_cleanup; ?></TD>
  </TR>
  <TR> 
	<TD bgcolor="#F6F6F6"> 
	  	<TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
		<TR> 
		  <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_update_links_cats; ?></TD>
		  <TD bgcolor="#F6F6F6"> 
			<INPUT type="checkbox" name="update_links" class="text" value="1">
			</TD>
		</TR>
	  </TABLE>
	</TD>
  </TR>
</TABLE>
  <P>
	<INPUT type="submit" name="submit" value="<?php echo $la_button_proceed; ?>" class="button">
	<INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
  </P>
</FORM>
</BODY>
</HTML>