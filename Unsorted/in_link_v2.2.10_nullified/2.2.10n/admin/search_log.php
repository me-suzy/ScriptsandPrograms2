<?php
$thisfile = "log";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
include("../includes/hierarchy_lib.php");
include("../includes/admin_search_lib.php");
if($reset==$la_button_reset)
	inl_header("confirm.php?action=resetlog&log_search=$log_search");

if($submitYes==$la_yes){deletelog();}
if(!$orderby){$orderby="log_date";}
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="images/icon5-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav4 ?></TD>
    <TD rowspan="2" width="0"><A href="help/6.htm#searchlog"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR><FORM name="form1" method="post" action="search_log.php<?php
	if($sid && $session_get)
		echo "?sid=$sid";
?>">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <?php
	 if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_statistics, $la_title_search_log, $la_title_reports);
	$nav_links_admin[$la_title_statistics]="log.php$att_sid";
	$nav_links_admin[$la_title_search_log]="search_log.php$att_sid";
	$nav_links_admin[$la_title_reports]="reports.php$att_sid";
	echo display_admin_nav($la_title_search_log, $nav_names_admin, $nav_links_admin);
?>

  <TR> 
    <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_search_log ?></TD>
  </TR>
  <TR> 
      <TD bgcolor="#F6F6F6"> 
		<TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
		  <TR bgcolor="#999999" valign="middle"> 
			<TD colspan="4" class="textTitle"> </TD>
		  </TR>
		  <TR bgcolor="#F6F6F6" valign="middle"> 
			<TD class="text" colspan="2"> 
			  <SELECT name="log_search" class="text">
			  <OPTION value="1"<?php if($log_search=="1"){echo " selected";}?>><?php echo $la_simple_search ?></OPTION>
				<OPTION value="2"<?php if($log_search=="2"){echo " selected";}?>><?php echo $la_advanced_search ?></OPTION>
			  </SELECT>
			  <INPUT type="submit" name="submit" value="<?php echo $la_button_go ?>" class="button">
			</TD>
			<TD class="text" align="right" colspan="2">
			  <INPUT type="submit" name="reset" value="<?php echo $la_button_reset ?>" class="button">
			</TD>
		  </TR>
		     <TR bgcolor="#999999" valign="middle"> 
            <TD class="textTitle"><a href="search_log.php?<?php
				if($sid && $session_get)
					$att_sid="sid=$sid&";
				echo $att_sid;
			?>orderby=log_keyword"><img src="images/orderarrow<?php if($orderby=="log_keyword"){echo "2";}else{echo "1";}?>.gif" border='0'><font color='ffffff'><?php echo $la_keyword ?></font></a></TD>
			 
			<TD class="textTitle"><a href="search_log.php?<?php echo $att_sid;?>orderby=log_type"><img src="images/orderarrow<?php if($orderby=="log_type"){echo "2";}else{echo "1";}?>.gif" border='0'><font color='ffffff'><?php echo $la_table_type ?></font></a></TD>
			<TD class="textTitle"><a href="search_log.php?<?php echo $att_sid;?>orderby=log_date"><img src="images/orderarrow<?php if($orderby=="log_date"){echo "2";}else{echo "1";}?>.gif" border='0'><font color='ffffff'><?php echo $la_date_time ?></font></a></TD>
		    <TD class="textTitle"><a href="search_log.php?<?php echo $att_sid;?>orderby=log_search"><img src="images/orderarrow<?php if($orderby=="log_search"){echo "2";}else{echo "1";}?>.gif" border='0'><font color='ffffff'><?php  echo $la_search_type ?></font></TD>
          </TR>
		  <?php echo display_search_log(); ?>
		  <TR bgcolor="<?php echo $bgc; ?>" valign="middle"> 
			<TD class="text" colspan="4" align="right"><b><?php echo $pagenav; ?></b></TD>
		  </TR>
		</TABLE>
		
	  </TD>
  </TR>
</TABLE>
</FORM>
</BODY>
</HTML>
