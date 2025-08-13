<?php
//Read in config file
$thisfile = "addcategory";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
include("../includes/links_lib.php");
include("../includes/user_lib.php");
include("../includes/review_lib.php");

$rev_user=get_user_id($HTTP_POST_VARS["rev_user"]);

if ($action == "editreview") {
    validatereview();
    if ($error == "0") {
		$rev_text = inl_escape($rev_text);
        editreview($rev_id);
       inl_header("navigate.php?id=$id&t=reviews&attach=$attach&toprate=$toprate&tophits=$tophits");
    }
}
if ($action == "addreview") {
	validatereview();
    if ($error == "0") {
		$rev_text = inl_escape($rev_text);
        addreview($id);
		 inl_header("navigate.php?id=$id&t=reviews&attach=$attach&toprate=$toprate&tophits=$tophits");
    }
}
if($rev_id && !$action) //edit review
{	$query="SELECT rev_user, rev_text, rev_date FROM inl_reviews WHERE rev_id=$rev_id";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
	{	$rev_month = date("n", $rs->fields[2]);
		$rev_day = date("j", $rs->fields[2]);
		$rev_year = date("Y", $rs->fields[2]);
		$rev_text = $rs->fields[1];
		$rev_user=$rs->fields[0];
	}
}else{
	if ($rev_month == "") {$rev_month = $month;}
	if ($rev_day == "") {$rev_day = $day;}
	if ($rev_year == "") {$rev_year = $year;}
	if (!$HTTP_POST_VARS["rev_user"]){$rev_user=$ses["user_id"];}
}
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
	<TD rowspan="2" width="0"><IMG src="images/icon1-.gif" width="32" height="32"></TD>
	<TD class="title" width="100%"><?php echo $la_nav1; ?></TD>
	<TD rowspan="2" width="0"><A href="help/6.htm#rev"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
	<TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>
<FORM name="addreview" method="post" action="addreview.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
	?>">
  <TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
	<TD class="tabletitle" bgcolor="#666666" colspan="2"><?php if($rev_id) echo $la_title_edit_review; else echo $la_title_add_review;?></TD>
  </TR>
  <TR bgcolor="#DEDEDE"> 
				<TD valign="top" colspan="3"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"><?php echo $la_enable_html;?> <input type="checkbox" name="html_enable" value="yes"><br>
			<img src="images/mark.gif" width="16" height="16" align="absmiddle"><?php echo $la_warning_html_enable;?></span></TD>
			</TR>
		<TR bgcolor="#F6F6F6"> 
		  <TD valign="top"><SPAN class="text"><?php if ($error == 1) { echo "<font color=\"red\">";} ?><?php echo $la_review; ?></SPAN></TD>
		  <TD> 
			<TEXTAREA name="rev_text" cols="30" rows="5" class="text"><?php echo $rev_text; ?></TEXTAREA>
		  </TD>
		</TR>
		<TR bgcolor="#DEDEDE"> 
		    <TD valign="top" class="text">
              <?php if (($error == 3) || ($error == 4) || ($error == 5)) { echo "<font color=\"red\">";} ?><?php echo $la_date_created; ?><?php if (($error == 3) || ($error == 4) || ($error == 5)) { echo "</font>";} ?>
            </TD>
		  <TD> 
			<INPUT type="text" name="rev_month" class="text" size="5" value="<?php echo $rev_month; ?>">
			- 
			<INPUT type="text" name="rev_day" class="text" size="5" value="<?php echo $rev_day; ?>">
			- 
			<INPUT type="text" name="rev_year" class="text" size="7" value="<?php echo $rev_year; ?>">
			<SPAN class="small"><?php echo $la_date_format; ?></SPAN></TD>
		</TR>
				<TR bgcolor="#F6F6F6"> 
		  <TD valign="top" class="text" bgcolor="#F6F6F6"> <?php if ($error == 6) { echo "<font color=\"red\">";} ?><?php echo $la_rev_owner; ?><?php if ($error == 6){ echo "</font>";} ?></TD>
		  <TD bgcolor="#F6F6F6"> 
			 <input type="text" value="<?php if ($HTTP_POST_VARS["rev_user"]) echo $HTTP_POST_VARS["rev_user"]; else echo get_user_name($rev_user); ?>" name="rev_user" class="text" size='30'>
		  </TD>
		</TR>
	  </TABLE>

<P>
  <input type="hidden" name="action" value="<?php if($rev_id) echo "editreview"; else echo "addreview";?>">
  <input type="hidden" name="toprate" value="<?php echo $toprate; ?>">
  <input type="hidden" name="tophits" value="<?php echo $tophits; ?>">
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <input type="hidden" name="rev_id" value="<?php echo $rev_id; ?>">
  <INPUT type="submit" name="Submit" value="<?php if($rev_id) echo $la_button_edit_review; else echo $la_button_add_review;?>" class="button">
  <INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
  <INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
</P>
</FORM>
</BODY>
</HTML>
