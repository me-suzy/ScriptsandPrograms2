<?php
//Read in config file
$thisfile = "pending";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
include("../includes/admin_pending_lib.php");
include("../includes/templ_lib.php");

if($action == $la_button_delete_selected )
{
	if($duplinks)
	{
		$urls = array_keys($duplinks);
		$size = sizeof($urls);

		if($size)
		{
			if($dislink==1)
			{
				for($i = 0; $i < $size; $i++)
					delete_link($urls[$i], "all");
			}
			else
			{
				$query1 = "SELECT link_id FROM inl_links WHERE link_url = '".$urls[0]."'";
				
				for($i = 1; $i < $size; $i++)
					$query1 .= " OR link_url = '".$urls[$i]."'";

				$rs = &$conn->Execute($query1);

				while ($rs && !$rs->EOF)
				{
					delete_link($rs->fields[0], "all");
					$rs->MoveNext();
				}
			}
		}
	}
}

if($dislink==1)
{	$stupid["url"]=$url;
	$stupid["dislink"]=1;
	
	//$attach="duplicates";
	$attach="duplicatelinks.php?dislink=$dislink&url=$url";
	$ses["destin"]="duplicatelinks.php?dislink=$dislink&url=$url";
	save_session($sid);

	$dups=$url;
	
	$query="SELECT inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_url, cat_id, link_vis FROM inl_links LEFT JOIN inl_lc ON inl_links.link_id=inl_lc.link_id LEFT JOIN inl_custom ON inl_links.link_cust=inl_custom.cust_id where link_url='$url'";

	$links=print_links($query, "list_duplicate_links", $lim, $start);
	pagenav("",$query , "duplicatelinks", $start, $stupid);

}
else
{
	$query=duplicate_links();
	printduplink($query);
}
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">

<script language="JavaScript">
<!--

function arc_boxchange(form2,boxState) {
for(var i=0;i < form2.elements.length; i++) {
var theElement = form2.elements[i];
if (theElement.type=='checkbox') theElement.checked = boxState;
}
}


//-->
</script>

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
      <TD class="tabletitle" bgcolor="#666666"><?php echo $la_duplicate_link_check ?></TD>
  </TR>
  <TR> 
    <TD bgcolor="#ffffff" align="right">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
        	<TR> 
      	<TD class="text"><?php echo "$la_displaying <span class='link'><b>$dups</b></span> $la_duplicates"; ?></TD>
  	</TR><tr><td>
		<form name="form2" method="post" action="duplicatelinks.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>dislink=<?php print $dislink?>&url=<?php print $url?>">
		<p align="left">
		<input type="button" name="selectall" value="<?php print $la_button_select_all ?>" class="button" onClick="arc_boxchange(document.form2,true);">
		<input type="button" name="deselectall" value="<?php print $la_button_unselect_all ?>" class="button" onClick="arc_boxchange(document.form2,false);">
			</p>
			<SPAN>
			<TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
			<?php echo $links ?>
			</TABLE>
			</SPAN>
		<p align="left"> 
		<input type="hidden" name="table" value="links">
		<input type="submit" name="action" value="<?php print $la_button_delete_selected ?>" class="button">
		<?php if($dislink==1) {?>
		<br><br><SPAN><img src="images/arrow2.gif" width="8" height="9"><a href="duplicatelinks.php" class="tableitem"><?php print $la_button_back ?></a></SPAN><SPAN class="text"></SPAN> 
		<?php } ?>
		</p>  </form>
    </td></tr></TABLE>
	<br>
	<?php echo $pagenav ?>
      </TD>
  </TR>
</TABLE>
<br>

</BODY>
</HTML>
