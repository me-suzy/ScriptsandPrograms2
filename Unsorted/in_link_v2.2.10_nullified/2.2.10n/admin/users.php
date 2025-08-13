<?php
//Read in config file
$thisfile = "user";
$admin = 1;

include("../includes/config.php");
include("../includes/admin_users_lib.php");
include("../includes/admin_search_lib.php");
include("../includes/hierarchy_lib.php");

if(!$pend){$pend="0";}
if(!$duplicatemail){$duplicatemail="0";}

if(!$page_nav_vars["pend"]){$page_nav_vars["pend"]=$pend;}

if(strlen($orderby)<1 && $duplicatemail=="0"){$orderby="user_date";}



$page_nav_vars["orderby"]=$orderby;
	$attach="";
	if($user_name){$attach.="|user_name=$user_name";}
	if($first){$attach.="|first=$first";}
	if($last){$attach.="|last=$last";}
	if($fday){$attach.="|fday=$fday";}
	if($dmonth){$attach.="|dmonth=$dmonth";}
	if($fyear){$attach.="|fyear=$fyear";}
	if($lday){$attach.="|lday=$lday";}
	if($lmonth){$attach.="|lmonth=$lmonth";}
	if($lyear){$attach.="|lyear=$lyear";}
	if($email){$attach.="|email=$email";}
	if($user_perm){$attach.="|user_perm=$user_perm";}
	if($status){$attach.="|status=$status";}
	if($sep){$attach.="|sep=$sep";}
	if($ucust1){$attach.="|ucust1=$ucust1";}
	if($ucust2){$attach.="|ucust2=$ucust2";}
	if($ucust3){$attach.="|ucust3=$ucust3";}
	if($ucust4){$attach.="|ucust4=$ucust4";}
	if($ucust5){$attach.="|ucust5=$ucust5";}
	if($ucust6){$attach.="|ucust6=$ucust6";}
	if($submit!=$la_button_edit && $submit!=$la_button_delete){$attach.="|submit=$submit";}
	if($search){$attach.="|search=$search";}
	if($result){$attach.="|result=$result";}
	if($pend){$attach.="|pend=$pend";}
	if($searchkey){$attach.="|searchkey=$searchkey";}
	if($user_perm){$attach.="|user_perm=$user_perm";}

if($submit==$la_button_edit)
	inl_header("edituser.php?id=$userid&attach=$attach&submit=$la_button_edit");

if($submit==$la_button_delete)
	inl_header("confirm.php?deleteid=$userid&action=edituser&attach=$attach");

if($search==$la_button_search)
{
	$having=" and (user_name like '%$searchkey%' or first like '%$searchkey%' or last like '%$searchkey%' or email like '%$searchkey%') ";
	$page_nav_vars["searchkey"]=$searchkey;
	$page_nav_vars["search"]=$la_button_search;
}
if($submit==$la_button_search)
{	$quer=getsearchquery(); 
	$lim=$result;
}
if($submit==$la_button_approve)
	approve_user($userid);

$toprint=getusers($orderby, $lim, $having, $displayall, $start, $quer);
?>

<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>
<BODY bgcolor="#FFFFFF">
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
    <TR>
	<?php if($pend){echo "<TD rowspan=2 width=0><IMG src='images/icon2-.gif' width='32' height='32'></TD>
      <TD class='title' width='100%'>$la_nav2</TD>";}
	else{ echo "<TD rowspan=2 width=0><IMG src='images/icon9-.gif' width=32 height=32></TD>
      <TD class='title' width='100%'>$la_nav3</TD>";}?>
      <TD rowspan="2" width="0"><A href="help/6.htm#userlist"><IMG border="0" src="images/but1.gif" width="30" height="32"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><IMG border="0" src="images/but2.gif" width="30" height="32"></A></TD>
    </TR>
    <TR>
      <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
    </TR>

</TABLE>
<BR>
<TABLE border="0" cellpadding="2" cellspacing="0" class="tableborder" width="100%">
<?php
	if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_user_list,$la_title_email);
	$nav_links_admin[$la_title_user_list]="users.php$att_sid";
	$nav_links_admin[$la_title_email]="users_e-mail.php$att_sid";
	echo display_admin_nav($la_title_user_list, $nav_names_admin, $nav_links_admin);
?>
    <TR>
      <TD bgcolor="#666666" class="tabletitle">
	<?php 
	if($duplicatemail==1)
		echo $la_title_users_duplicate_email;
	elseif($pend==1){echo "$la_title_pending_users";}
		else{echo $la_title_user_list;}	
	?></TD>
    </TR>
    <TR>
      <TD bgcolor="#f6f6f6">
		<?php echo $toprint; ?>
      </TD>
    </TR>
</TABLE>
</BODY>
</HTML>