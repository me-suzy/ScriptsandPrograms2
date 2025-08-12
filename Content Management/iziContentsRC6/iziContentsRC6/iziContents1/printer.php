<?php

/***************************************************************************

 printer.php
 ------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

require_once ("./rootdatapath.php");
include_once ($GLOBALS["rootdp"]."include/content.php");

HTMLHeader($title);

/***************************************************************************
 * Modification for advanced settings for the printed page *** Bernd JM
 ***************************************************************************/
$prs_color = 'N';			// Y = printout using the colorsettings of the site
							// N = printout in black & white
$prs_show_icon = 'Y';		// Y = show the printer icon 
							// N = don't show it
$prs_prn_text = 'Print';	// Display a text near the icon
							// if empty no text will be shown
$prs_icon_loc = 'R';		// L = printer icon and/or text on the left
							// R = printer icon and/or text on the right
							// C = printer icon and/or text in the middle
$prs_close_win = 'Y';		// Y = close the printer window automatic
							// N = the window will not be closed

/******************************************************************************/

function locatePrintstylesheet()
{
	global $EZ_SESSION_VARS;
	if ($EZ_SESSION_VARS["Site"] != '') {
		$styledir = $GLOBALS["rootdp"].$GLOBALS["sites_home"];
		$styledir .= $EZ_SESSION_VARS["Site"];
		if ($EZ_SESSION_VARS["Theme"] != '') {
			$styledir .= '/themes/';
			$styledir .= $EZ_SESSION_VARS["Theme"];
		}
	} else {
		$styledir = $GLOBALS["rootdp"].$GLOBALS["themes_home"];
		if ($EZ_SESSION_VARS["Theme"] != '') { $styledir .= $EZ_SESSION_VARS["Theme"]; }
	}
	if (substr($styledir ,-1) != '/') { $styledir .= '/'; }
	$stylesheet = $styledir.'print.css';

	return $stylesheet;
} // function locatePrintstylesheet()

function PrintStyleSheet()
{
	if ($GLOBALS["safe_mode"] || $GLOBALS["open_basedir"] <> '') {
		include('./include/printstyle.php');
		} else {
		?>
		<LINK HREF="<?php echo locatePrintstylesheet(); ?>" REL=STYLESHEET TYPE="text/css">
		<?php
	}
} // function PrintStyleSheet()


if ($prs_color=='Y') 
	{ StyleSheet(); }
	else
	{ PrintStyleSheet(); }

if ($prs_show_icon=='Y')
	{ echo '<style type="text/css">';
	  echo '<!-- ';
	  echo '@media print { a.print {display:none;}}';
	  echo '@media screen { a.print {display:inline;}}'; 
	  echo '-->';
	  echo '</style>';
	  $prs_icon = '<img src="'.$GLOBALS["icon_home"].'printerfriendly.gif" width="15" height="11" alt="'.$prs_prn_text.'" border="0">';
	}

if ($prs_icon_loc=='L') 
	{ $prs_icon_loc = 'left'; }
	elseif ($prs_icon_loc=='R')
	{ $prs_icon_loc = 'right'; }
	elseif ($prs_icon_loc=='C')
	{ $prs_icon_loc = 'center'; }
	
if ($prs_close_win=='Y')
	{ $prs_close_win = 'self.close();'; }
	else
	{ $prs_close_win = ''; }
?>

</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">

<?php
	echo '<div align="'.$prs_icon_loc.'"><a class="print" media="screen" href="" onclick="window.print(); '.$prs_close_win.' return false;">'.$prs_icon.'&nbsp;'.$prs_prn_text.'&nbsp;'; 
?>
</a>&nbsp;</div>

<?php
/*****************************************************************************
 * End modification
 *****************************************************************************/

if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname ='".$_GET["article"]."' AND language='".$GLOBALS["gsLanguage"]."'";
} else {
	$lOrder = '';
	if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname ='".$_GET["article"]."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY language".$lOrder;
}
$result = dbRetrieve($strQuery,true,0,0);

$bEncodeHTML = true;
$nContentName = '';
while ($rsContent = dbFetch($result)) {
	if ($rsContent["contentname"] != $nContentName) {
		$nContentName = $rsContent["contentname"];
		PrintContent($rsContent,0);
	}
}

dbFreeResult($result);


?>
</body>
</html>
<?php

function PrintContent($rsContent)
{
	ShowContentHeader($rsContent);
	PrintContentBody($rsContent);
} // function PrintContent()


function PrintContentBody($rsContent)
{
	$bEncodeHTML = true;
	?><tr><td class="tablecontent" valign="top"><?php
	if ($rsContent["imagedetails"] != "") {
		echo imagehtmltag($GLOBALS["image_home"],$rsContent["imagedetails"],'',0,$rsContent["imagedetailsalign"]);
	}

	if ($GLOBALS["gsTeaserWithDetails"] == 'Y') {
		echo '<I>';
		echo ext_print($rsContent["cteaser"], $bEncodeHTML, 'L', 'Y');
		echo '</I><P>';
	}

	$contentpages = explode("[pagebreak]",$rsContent["cbody"]);
	$contentpage = implode('<hr style="border-style:dashed"/><p style="page-break-before:always"></p>',$contentpages);
	echo ext_print($contentpage, $bEncodeHTML, 'L', 'Y');
	echo '</td></tr>';
}

?>
