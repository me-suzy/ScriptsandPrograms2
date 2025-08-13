<?php
$thisfile = "confirm";
$admin = 1;
include("../includes/config.php");
include("../includes/links_lib.php");
include("../includes/cats_lib.php");
$attach1=ereg_replace("\|","&",$attach);

if ($action == "dellinkconfirm")
{	if($submitAll)
	{	delete_link($id, "all");
		inl_header("navigate.php?cat=$cat&having=$having&".$ses["destin"]);
	}
	elseif($submitOnly || $submitYes)
	{	
		if(ereg("pending_links",$ses["destin"])>0)
			delete_link($id, "all");
		else
			delete_link($id, $cat);
	
		if($attach=="duplicates")
			inl_header("duplicatelinks.php");
		elseif(ereg("query_ids",$attach)>0)
			inl_header("linksvalidate.php?display=Display&$attach1");
		else
			inl_header("navigate.php?cat=$cat&having=$having&".$ses["destin"]);
	}
	elseif($submitNo){
		if($attach=="duplicates")
			inl_header("duplicatelinks.php");
		elseif(ereg("query_ids",$attach)>0)
			inl_header("linksvalidate.php?display=Display&$attach");
		else
			inl_header("navigate.php?cat=$cat&having=$having&".$ses["destin"]);
	}
}
if ($action == "movelinkconfirm")
{	if($submitAll)
		moves_link($id, $cat, "all");
	elseif($submitOnly || $submitYes)
		moves_link($id, $cat, $catfrom);

	inl_header("navigate.php?cat=$cat&having=$having&$attach1");
}
if($action=="movelink")
{	//detect references of the link
	$query="select count(link_id) from inl_lc where link_id=$id and link_pend=0";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
	{	if($rs->fields[0]<2) //only one
			$msg = $la_confirm_move;
		else
		{	if(!$catfrom)
			{	$cat_data[2]="Root";
				$catfrom=0;
			}
			else
				$cat_data[2]=$catfrom;

			$msg = $la_confirm_move_inst."(".$la_confirm_move_inst2." ".$cat_data[2].")". $la_confirm_move_inst3."(".$rs->fields[0].")?";
		}
		$action = "movelinkconfirm";
	}
	else
		$msg = $la_error_db;
	$doctitle=$la_nav1;
	$docimage="images/icon1-.gif";
}

if ($action == "delcatconfirm") {
	if($submitYes==$la_yes)
	{	$error_msg=delcat($id);
		if(!$error_msg)
			inl_header("http://$server$filepath" . "admin/navigate.php?cat=$cat&having=$having&$attach1");
		else
		{	$message=base64_encode($error_msg);
			inl_header("http://$server$filepath" . "admin/navigate.php?t=error&message=$message");
		}
	}
	if($submitNo==$la_no)
		inl_header("http://$server$filepath" . "admin/navigate.php?cat=$cat&having=$having&$attach1");
	
}
if ($action == "delreviewconfirm") {
	include("../includes/review_lib.php");
	if($submitYes==$la_yes)
		delreview($deleteid);
	inl_header("http://$server$filepath" . "admin/navigate.php?id=$id&t=reviews&having=$having&$attach1");

}
if ($action == "dellink")
{	//detect references of the link
	$query="select count(link_id) from inl_lc where link_id=$id and link_pend=0";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
	{	if($rs->fields[0]<2) //only one
			$msg = $la_delete_link;
		else
		{	$cat_data=get_cat_data($cat); //#2 is the name
			$msg = "$la_confirm_del_inst ($la_confirm_del_inst2 '$cat_data[2]') $la_confirm_del_inst3	 (".$rs->fields[1] .") ?";
		}
		$action = "dellinkconfirm";
	}
	else
		$msg = "DB Problem.";
	$doctitle=$la_nav1;
	$docimage="images/icon1-.gif";
}
 if ($action == "delreview") {
	$msg = $la_delete_rev;
	$action = "delreviewconfirm";
	$doctitle=$la_nav1;
	$docimage="images/icon1-.gif";
 }
if ($action == "delcat") {
	$msg = $la_delete_cat;
	$action = "delcatconfirm";
	$doctitle=$la_nav1;
	$docimage="images/icon1-.gif";
 }
if ($action=="edituser") {
	$msg = $la_del_user;
	$thisfile = "edituser";
	$doctitle=$la_nav3;
	$docimage="images/icon9-.gif";
}
if ($action=="resetcounter") {
	$msg = $la_reset_count;
	$thisfile = "log";
	$doctitle=$la_nav4;
	$docimage="images/icon5-.gif";
}
if ($action==$la_button_approve_selected && $table=="cats") 
{	if(count($pendcats) != 0)
		masscatapprove($pendcats);
	$attach1.= "&t=pending_cats";
	inl_header("navigate.php?having=$having&$attach1");
}
if ($action==$la_button_delete_selected && $table=="cats") 
{	if(count($pendcats) != 0)
		masscatdelete($pendcats);
	$attach1.= "&t=pending_cats";
	inl_header("navigate.php?having=$having&$attach1");
}
if ($action=="approvecat") 
{	if(count($pendcats) != 0)
		masscatapprove($pendcats);
	$attach1.= "&t=pending_cats";
	inl_header("navigate.php?having=$having&$attach1");
}
if ($action=="approvelink") 
{	if(count($pendlinks) != 0)
		masslinkapprove($pendlinks);
	$attach1.= "&t=pending_links";
	inl_header("navigate.php?having=$having&".$ses["destin"]);
}
if ($action==$la_button_approve_selected && $table=="links") 
{	if(count($pendlinks) != 0)
		masslinkapprove($pendlinks);
	$attach1.= "&t=pending_links";
	inl_header("navigate.php?having=$having&".$ses["destin"]);
}
if ($action==$la_button_delete_selected && $table=="links") 
{	if(count($pendlinks) != 0)
		masslinkdelete($pendlinks);
	$attach1.= "&t=pending_links";
	if(ereg("query_ids",$attach)>0)
		inl_header("linksvalidate.php?display=Display&$attach1");
	else
		inl_header("navigate.php?having=$having&".$ses["destin"]);
}
if ($action=="resetlog") 
{
	$msg = $la_reset_log;
	$thisfile = "search_log";
	$doctitle=$la_nav4;
	$docimage="images/icon5-.gif";
}
if ($action=="approverev") {
	$query="update inl_reviews set rev_pend=0 where rev_id='$rev_id'";
	$conn->Execute($query);

	$query="select count(rev_id) from inl_reviews where rev_link=$id and rev_pend=0";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
	{	
		$query="update inl_links set link_numrevs=".$rs->fields[0]." where link_id='$id'";
		$conn->Execute($query);
	}
	inl_header("navigate.php?id=$id&t=list_pend_reviews&toprate=$toprate&tophits=$tophits");
}
if ($action=="deleterev") {
		if($submitYes==$la_yes){
			$query="delete from inl_reviews where rev_id=$rev_id";
			$conn->Execute($query);	
			$query="select count(rev_id) from inl_reviews where rev_link=$id and rev_pend=0";
			$rs = &$conn->Execute($query);
			if ($rs && !$rs->EOF) 
			{	
				$query="update inl_links set link_numrevs=".$rs->fields[0]." where link_id='$id'";
				$conn->Execute($query);	
			}
			inl_header("navigate.php?id=$id&t=$t&toprate=$toprate&tophits=$tophits");
		}
		if($submitNo==$la_no)
			inl_header("navigate.php?id=$id&t=$t&toprate=$toprate&tophits=$tophits");

		$msg = $la_delete_rev;
		$thisfile = "confirm";
		$doctitle=$la_nav2;
		$docimage="images/icon2-.gif";
		$pend_rev="rev_id=$rev_id&id=$id&t=$t&toprate=$toprate&tophits=$tophits&action=deleterev&";	
}
if ($action=="logout")
{	logout();
	inl_header("login.php");		
}

?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="<?php echo $docimage;?>" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $doctitle;?></td>
    <td rowspan="2" width="0"><a href="help/manual.pdf"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>    <form name="form1" method="post" action="<?php 
		if($sid && $session_get)
			$att_sid="sid=$sid&";
		echo "$thisfile.php?$att_sid"."pend_rev=$pend_rev&log_search=$log_search&having=$having&attach=$attach";
	?>">

  <table width="100%" border="0" cellspacing="0">
    <tr> 
      <td align="center"> 
        <table width="300" border="0" cellspacing="0" cellpadding="2" class="tableborder">
          <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_confirm; ?></td>
  </tr>
  <tr> 
      <td bgcolor="#F6F6F6" align="center" valign="middle"> 
        <p>&nbsp;</p>
              <p align="center"><b><?php echo $msg; ?></b></p>
  
        <div align="center">
			<input type="hidden" name="pend" value="<?php if($pend){echo $pend;}else{echo "0";} ?>">
			<input type="hidden" name="reset1" value="<?php echo $reset1; ?>">
			<input type="hidden" name="reset2" value="<?php echo $reset2; ?>">
			<input type="hidden" name="deleteid" value="<?php echo $deleteid; ?>">
			<input type="hidden" name="cat" value="<?php echo $cat; ?>">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="action" value="<?php echo $action; ?>">
			<input type="hidden" name="catfrom" value="<?php echo $catfrom; ?>">
                <?php if(($action=="dellinkconfirm" || $action=="movelinkconfirm") && $cat_data[2]) : ?>
				<input type="submit" name="submitAll" value="<?php echo $la_all; ?>" class="button">
                <input type="submit" name="submitOnly" value="<?php echo "'$cat_data[2]' $la_button_only"; ?>" class="button">
                <input type="submit" name="submitNo" value="<?php echo $la_button_cancel; ?>" class="button">
				<?php else : ?>
				<input type="submit" name="submitYes" value="<?php echo $la_yes; ?>" class="button">
                <input type="submit" name="submitNo" value="<?php echo $la_no; ?>" class="button">
				<?php endif ?>
              </div>
        <p>&nbsp;</p>
    </td>
  </tr>
</table>    </td>
  </tr>
</table></form>
<p>&nbsp; </p>
</body>
</html>
