<?php
//Read in config file
$thisfile = "pending";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
include("../includes/admin_pending_lib.php");
include("../includes/templ_lib.php");
$attach="query_ids=$query_ids|start=$start";
$ses["destin"]="query_ids=$query_ids|start=$start";
save_session($sid);
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
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
      <TD class="tabletitle" bgcolor="#666666"><?php echo $la_links_validation  ?></TD>
  </TR><?php if($display=="Display"){echo "<tr bgcolor='#999999' valign='middle'><td class='textTitle'>$la_displaying_dead_links</td></tr>";}?>
	<tr><td bgcolor="#ffffff" <?php if($display!="Display"){echo "align=\"center\"";} ?>>
       <?php 
		if($display!="Display")
			echo checkdead();
		else
		{	$ids=split(",",$query_ids);
			$query="SELECT inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_url, cat_id, link_vis FROM inl_links LEFT JOIN inl_lc ON inl_lc.link_id=inl_links.link_id LEFT JOIN inl_custom ON inl_links.link_cust=inl_custom.cust_id WHERE (";
			if($start)
				$p=$start;
			else
				$p=1;
			for($i=$p;$i<$p+$lim;$i++)
				$query.=" inl_lc.link_id='$ids[$i]' or";

			$query=ereg_replace("or$","",$query);
			$query=$query.")";
			echo	"<br><form name='form2' method='post' action='confirm.php?$att_sid"."table=links&attach'>
					<p align='left'>
  					<input type='button' name='selectall' value='$la_button_select_all' class='button' onClick=\"arc_boxchange(document.form2,true);\">
  					<input type='button' name='deselectall' value='$la_button_unselect_all' class='button' onClick=\"arc_boxchange(document.form2,false);\">
  					</p>";
			echo "<br><ul>".print_links($query, "validate_links")."</ul>";
			$stupid["display"]="Display";
			$stupid["query_ids"]=$query_ids;
			pagenavdead($cat, $query, "linksvalidate", $start, $stupid, count($ids));
			echo "<p align='left'><input type='hidden' name='query_ids' value='$query_ids'>
			<input type='hidden' name='table' value='links'>
			<input type='hidden' name='attach' value='$attach'>
			<input type='submit' name='action' value='$la_button_delete_selected' class='button'>
			</p>  </form>";

		}
		?></td></tr>
	  <TR> 
    <TD bgcolor="#ffffff" align="right"><?php echo "$pagenav";?>
    </td></tr>
</TABLE>
<br>
<?php if($display!="Display")
		echo $check_redirect;
?>
</body>
</htmL>