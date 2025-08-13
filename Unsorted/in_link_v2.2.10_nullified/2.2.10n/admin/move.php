<?php
//Read in config file
$thisfile = "move";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
include("../includes/cats_lib.php");
include("../includes/links_lib.php");
include("../includes/hierarchy_lib.php");
include("../includes/stats_lib.php");



if ($type == "cat")
{	printmovecat($cat, $id, $type, "select cat_id, cat_name, cat_desc, cat_links, cat_cats, cat_pick, cat_date, cat_vis from inl_cats where cat_sub='$cat' and cat_id!='$id' and cat_pend!=1", $thisfile);
	getcat($id);
	if ($cat_pend == 1)
		$file = "pending_cats";
	else
		$file = "navigate";
} 
else 
{	//if(!$catfrom)
	//	$catfrom=$cat;
	printmovecat($cat, $id, $type, "select cat_id, cat_name, cat_desc, cat_links, cat_cats, cat_pick, cat_date, cat_vis from inl_cats where cat_sub='$cat' and cat_pend!=1", $thisfile);
	$file = "navigate";
}
if ($action == "movetocat")
{	if($type=="cat")
	{	movecat($cat,$id);
		inl_header("http://$server$filepath" . "admin/$file.php?&cat=$cat");
	}
	else //moving a link needs user choice
		inl_header("confirm.php?action=movelink&id=$id&cat=$cat&catfrom=$catfrom");
}

if ($action == "addtocat")
{	addtocat($cat,$id,$type);
	inl_header("http://$server$filepath" . "admin/$file.php");
}

navbar($cat, $thisfile);
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<link rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon1-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav1; ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#mvlink"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="./images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>




<TABLE border="0" cellpadding="2" cellspacing="0" class="tableborder" width="100%">
    <TR>
      <TD bgcolor="#666666" class="tabletitle">
		<?php echo $la_title_moving; ?>
	</TD>
    </TR>
</TABLE>


<table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#F0F0F0">
  <tr>
    <td><b class="text"><span class="navbar"><?php echo $navbar; ?></span></b></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFFF" valign="middle"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"> 
      <?php echo $la_navigate_to_move; ?></span> 
    </td>
  </tr>
</table>
<br>
<table border="0">
<tr>
	<?php 
		$rs=&$conn->Execute("SELECT cat_user FROM inl_cats WHERE cat_id=$cat"); //get current cat's user
		if($ses["user_perm"]==1 || $ses["user_perm"]==2  || ($ses["user_perm"]==5 && $rs->fields[0]==$ses["user_id"])) :
	?>
	<td>
	<form action="move.php<?php
	if($sid && $session_get)
		echo "?sid=$sid";
  ?>" method="post">
	
	<input type="hidden" name="cat" value="<?php echo $cat; ?>">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<input type="hidden" name="type" value="<?php echo $type; ?>">
	<input type="hidden" name="action" value="movetocat">
	<input type="hidden" name="catfrom" value="<?php echo $catfrom; ?>">
	<input type="submit" name="Button" value="<?php echo $la_button_move_to_cat; ?>" class="button">
  </form>
  </td>
  <?php endif; ?>
  <td>
  </td>
  <td>
  <form action="<?php 
		if($sid && $session_get)
			$att_sid="sid=$sid&";
		echo "$file.php?$att_sid"."cat=$cat"; 
		?>" method="post">
	<input type="submit" name="Button" value="<?php echo $la_button_cancel; ?>" class="button"></form>
</td></tr></table></p>
<hr size="1" noshade>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#F7F7FF"> 
    <td><span class="stats"><b><?php echo $la_categories; ?></b></span></td>
  </tr>
</table>
<br>
<?php echo $cats; ?>

<hr size="1" noshade>

    </td>
  </tr>
</table>
</body>
</html>
