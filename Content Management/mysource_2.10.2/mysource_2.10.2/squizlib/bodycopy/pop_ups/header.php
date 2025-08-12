<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/header.php,v $
## $Revision: 2.7 $
## $Author: gsherwood $
## $Date: 2003/02/27 03:34:22 $
#######################################################################
if (!$bgcolor) $bgcolor = "#212E61";


?> 
<html>
<head>
<style type="text/css">
	body { 
		background-color: <?=$bgcolor?>;
	}
	td { 
		font-family: Arial, Verdana, Sans-Serif; 
		font-size: 12px; 
	}
	.bodycopy-popup-heading {
		font-family: Arial, Verdana, Sans-Serif; 
		font-size: 12px; 
		font-weight: bold;
	}
	.bodycopy-popup-table { 
		background-color: #C0C0C0;
	}
	.smallprint {
		font-family: Arial, Verdana, Sans-Serif; 
		font-size: 10px; 
	}
	.warning {
		font-family: Arial, Verdana, Sans-Serif; 
		font-size: 11px; 
		color: #ff0000;
	}
</style>
<? 
if ($stylesheet && $show_stylesheet) { 
?> 
	<link rel="stylesheet" href="<?=$stylesheet?>" type="text/css">
<? 
} #end if 

if ($browser != "ns") {
?> 
	<script language="JavaScript" src="<?=squizlib_href('js','detect.js');?>"></script>
<?
}#end if
?>
<script language="JavaScript">

	if (is_ie4up || is_dom) {
		var owner = parent;
	} else {
		var owner = window;
	}// end if

	function popup_close() {
		popup_init = null;
		owner.bodycopy_hide_popup();
	}

</script>
</head>
<?
	if ($_GET['page_width'])  $table_width  = "width='".$_GET['page_width']."'";
	if ($_GET['page_height']) $table_height = "height='".$_GET['page_height']."'";
?>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onload="javascript: if(typeof popup_init == 'function') popup_init();" <?=$body_extra?>>
<table <?=$table_width?> <?=$table_height?> border="1">
	<tr><td valign="top" align="center" class="bodycopy-popup-table">