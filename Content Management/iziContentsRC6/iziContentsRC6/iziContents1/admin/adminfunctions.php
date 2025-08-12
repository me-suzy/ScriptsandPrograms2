<?php

/***************************************************************************

 adminfunctions.php
 -------------------
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
//include ($GLOBALS["rootdp"].'include/debuglib.php');

function admhdr($charset='')
{
	if ($charset == '') { $charset = $GLOBALS["gsCharset"]; }
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html dir="<?php echo $GLOBALS["gsDirection"]; ?>">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
	<?php
	if ($GLOBALS["gsAdminStyle"] != '') {
		?>
		<LINK HREF="<?php echo $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"]; ?>/vs.css" REL=STYLESHEET TYPE="text/css">
		<?php
	} else {
		include ($GLOBALS["rootdp"]."include/style.php");
	}
} // function admhdr()


function framecheck()
{
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		if (parent.location.href == self.location.href){
			window.location.href='index.php';
		}
		//  End -->
	</script>
	<?php
} // framecheck()


function adminheader($framedpage=True)
{
	admhdr($GLOBALS["gsCharset"]);
	if ($framedpage) { framecheck(); }
	?>
	</head>
	<?php
} // function adminheader()



function adminformheader($charset='')
{
	global $EZ_SESSION_VARS;

	if ($charset == '') { $charset = $GLOBALS["gsCharset"]; }
	admhdr($charset);
	framecheck();

	if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
		$langlookup	= array (	zh		=> "b5",		da		=> "da",		de		=> "de",
								en		=> "en",		es		=> "es",		fi		=> "fi",
								fr		=> "fr",		en_uk	=> "gb",		it		=> "it",
								ja		=> "ja-euc",	ja-jis	=> "ja-jis",	ja-sjis	=> "js-sjis",
								ja-utf8	=> "ja-utf8",	nb		=> "nb",		nl		=> "nl",
								pl		=> "pl",		pt		=> "pt-br",		ro		=> "ro",
								ru		=> "ru",		se		=> "se",		vn		=> "vn",
								cz		=> "cz" );

		$langref = $GLOBALS["gsLanguage"];
		if ($langlookup[$langref] == '') { $langref = $GLOBALS["gsDefault_language"]; }
		if ($langlookup[$langref] == '') { $langref = 'en'; }
		?>
 			<script language="javascript" type="text/javascript" src="<?php echo $GLOBALS["rootdp"]; ?>include/tinymce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript">
   			tinyMCE.init({
			mode: "textareas",
			theme : "advanced",
			plugins : "table,advhr,advlink,preview,flash,searchreplace,ibrowser,filemanager",
			theme_advanced_buttons1_add : "fontsizeselect",
			theme_advanced_buttons2_add : "separator,forecolor,backcolor,ibrowser,filemanager,flash",
			theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
			theme_advanced_buttons3_add_before : "tablecontrols,separator",
			theme_advanced_buttons3_add : "advhr,preview",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_path_location : "bottom",
			width : "100%",
			height : "400px",
			content_css : "../themes/ezc.css",
			extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade|colour],font[face|size|color|style],span[class|align|style]",
			verify_css_classes : "false",
			verify_html : "false",
      		language : "<?php echo $langref; ?>"
		   	});
			</script>
			<?php
	}
} // function adminformheader()

function adminformopen($firstfield)
{
	global $_SERVER, $EZ_SESSION_VARS;

	if ($GLOBALS["specialedit"] == True) {
		?>
		<body leftmargin="5" rightmargin="5" topmargin=10 marginwidth="5" marginheight="10" class="mainback" onUnload="closeChildWindows()">
		<?php
	} else {
		?>
		<body leftmargin="5" rightmargin="5" topmargin=10 marginwidth="5" marginheight="10" class="mainback">
		<?php
	}
	?>
	<center>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr><td align=center>
				<form name="MaintForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
				<table border="0" width="100%" cellspacing="1" cellpadding="3" class=bg_table>
	<?php
} // function adminform()


function adminformclose()
{
	global $EZ_SESSION_VARS, $_POST;

	if ($_POST["authorid"] == '') { $_POST["authorid"] = $EZ_SESSION_VARS["UserID"]; }
					?>
					<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
					<input type="hidden" name="authorid" value="<?php echo $_POST["authorid"]; ?>">
					<input type="hidden" name="submitted" value="yes">
					<input type="hidden" name="page" value="<?php echo $_POST["page"]; ?>">
					<input type="hidden" name="sort" value="<?php echo $_POST["sort"]; ?>">
				</table>
				</form>
		</td></tr>
	</table>
	</center>
	</body>
	</html>
	<?php
} // function adminformclose()


function admintitle($colspan,$title)
{
	?>
	<body leftmargin="0" rightmargin="5" topmargin="10" marginwidth="5" marginheight="10" class="mainback">
	<center>
	<table border="0" width="100%" cellspacing="1" cellpadding="3" class=bg_table>
	<?php
	adminformtitle($colspan,$title);
} // function admintitle()


function adminformtitle($colspan,$title)
{
	?>
	<tr class="headercontent">
		<td colspan="<?php echo $colspan; ?>" align="center" class="header">
			<b><?php echo $title; ?></b>
		</td>
	</tr>
	<?php
} // adminformtitle()


function adminbuttontest($directory,$filename,$language,$alttext,$border,$default)
{
	$button = lsimagehtmltag($directory,$filename,$language,$alttext,$border);
	if ($button == '') {
		$button = lsimagehtmltag($GLOBALS["style_home"],$default,$language,$alttext,$border);
	}
	return $button;
}  // adminbuttontest()

function adminbuttons($tView,$tAddNew,$tEdit,$tDelete)
{
	global $EzAdmin_Style;

	// Generate image tags for the different images that appear on the page
	if ($GLOBALS["gsDirection"] == 'rtl') {
		$GLOBALS["iFirst"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["LastIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0,'last_button.gif');
		$GLOBALS["iPrev"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["NextIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0,'next_button.gif');
		$GLOBALS["iNext"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["PrevIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0,'prev_button.gif');
		$GLOBALS["iLast"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["FirstIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0,'first_button.gif');
	} else {
		$GLOBALS["iFirst"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["FirstIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0,'first_button.gif');
		$GLOBALS["iPrev"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["PrevIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0,'prev_button.gif');
		$GLOBALS["iNext"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["NextIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0,'next_button.gif');
		$GLOBALS["iLast"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["LastIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0,'last_button.gif');
	}
	$GLOBALS["iAddNew"]	= adminbuttontest($GLOBALS["icon_home"],'addnew_button.gif',$GLOBALS["gsLanguage"],$tAddNew,0,'addnew_button.gif');
	$GLOBALS["iView"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["ViewIcon"],$GLOBALS["gsLanguage"],$tView,0,'view_button.gif');
	$GLOBALS["iEdit"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["EditIcon"],$GLOBALS["gsLanguage"],$tEdit,0,'edit_button.gif');
	$GLOBALS["iDelete"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["DeleteIcon"],$GLOBALS["gsLanguage"],$tDelete,0,'del_button.gif');
	$GLOBALS["iBlank"]	= imagehtmltag($GLOBALS["icon_home"],'blank.gif','',0,'');
	$GLOBALS["iUp"]		= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["UpIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tMoveUp"],0,'up.gif');
	$GLOBALS["iDown"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["DownIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tMoveDown"],0,'down.gif');
	$GLOBALS["iSort"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["SortIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tSort"],0,'sort.gif');
	$GLOBALS["iCSort"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["CurrentSortIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tSort"],0,'current_sort.gif');

	$GLOBALS["tAddNew"] = $tAddNew;
} // function adminbuttons()


function RenderPageList($nCPage,$nPages,$pLink,$linkmod='')
{
	global $_GET;

	$pagelist .= '&nbsp;&nbsp;'.$GLOBALS["tPage"].' ';
	$pagelist .= '<select name="page" class="paging" onChange="submit();">';
	for ($i=0; $i<$nPages; $i++) {
		$j = $i+1;
		$pagelist .= "<option";
		if (intval($nCPage)==$j) { $pagelist .= " selected"; }
		$pagelist .= ' value="'.$i.'">'.$j.'</option>';
	}
	$pagelist .= '</select>';
	$pagelist .= ' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
	$pagelist .= '<input type="hidden" name="ezSID" value="'.$GLOBALS["ezSID"].'">';
	$pagelist .= '<input type="hidden" name="sort" value="'.$_GET["sort"].'">';
	if ($linkmod != '') {
		$parms = explode('&',$linkmod);
		for ($i=0, $max=count($parms); $i<$max; $i++) {
			$parmset = array_pop($parms);
			$parm = explode('=',$parmset);
			$parmname = $parm[0];
			$parmval = $parm[1];
			if ($parmname != '') { $pagelist .= '<input type="hidden" name="'.$parmname.'" value="'.$parmval.'">'; }
		}
	}
	return $pagelist;
} // function RenderPageList()


function adminHdFt($form,$colspan,$nCurrentPage,$nPages,$linkmod)
{
	global $_GET;

	$pLink = BuildLink('m_'.$form.'.php').'&sort='.$_GET["sort"];
	$fLink = BuildLink('m_'.$form.'form.php').'&sort='.$_GET["sort"];
	$hlink = '<a href="'.$fLink.'&page='.$nCurrentPage.$linkmod.'" title="'.$GLOBALS["tAddNew"].'" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>';
	echo '<form name="PagingForm" action="'.$pLink.'" method="GET">';
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><?php
					if ($GLOBALS["canadd"] === True) {
						?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
						echo displaybutton('addbutton',$form,$GLOBALS["tAddNew"].'...',$hlink);
						?></td><?php
					}
					?>
					<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
						<?php
						$first = $GLOBALS["iFirst"];
						if ($nCurrentPage != 0) { $first = '<a href="'.$pLink.$linkmod.'&page=0" '.BuildLinkMouseOver($GLOBALS["tFirstPage"]).'>'.$first.'</a>'; }
						echo '&nbsp;';
						$prev = $GLOBALS["iPrev"];
						$prevpage = $nCurrentPage - 1;
						if ($nCurrentPage != 0) { $prev = '<a href="'.$pLink.$linkmod.'&page='.$prevpage.'" '.BuildLinkMouseOver($GLOBALS["tPrevPage"]).'>'.$prev.'</a>'; }
						$nCPage = $nCurrentPage + 1;
						$pageref .= RenderPageList($nCPage,$nPages,'m_'.$form.'.php',$linkmod);
						$next = $GLOBALS["iNext"];
						$nextpage = $nCurrentPage + 1;
						if ($nCurrentPage + 1 != $nPages) { $next = '<a href="'.$pLink.$linkmod.'&page='.$nextpage.'" '.BuildLinkMouseOver($GLOBALS["tNextPage"]).'>'.$next.'</a>'; }
						echo '&nbsp;';
						$last = $GLOBALS["iLast"];
						$lastpage = $nPages - 1;
						if ($nCurrentPage + 1 != $nPages) { $last = '<a href="'.$pLink.$linkmod.'&page='.$lastpage.'" '.BuildLinkMouseOver($GLOBALS["tLastPage"]).'>'.$last.'</a>'; }
						echo $first.'&nbsp;'.$prev.$pageref.$next.'&nbsp;'.$last;
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	echo '</form>';
} // function adminHdFt()


function adminlistitem($width,$title,$align='',$order='')
{
	global $_SERVER, $_GET;

	if ($align == 'r') { $talign = $GLOBALS["right"];
	} elseif ($align == 'c') { $talign = 'center';
	} else { $talign = $GLOBALS["left"]; }
	?>
	<th nowrap width="<?php echo $width; ?>%"<?php if ($align != '') { echo ' align="'.$talign.'"'; } ?> valign="bottom" class="content">
		<b><?php echo $title; ?></b><?php
		if ($order != '') {
			$href = $_SERVER["PHP_SELF"];
			if ($_SERVER["QUERY_STRING"] != '') { $href .= '?'.$_SERVER["QUERY_STRING"]; } else { $href .= '?'; }
			if (strpos($href,'&sort=') === False) {
				$href = $href.'&sort='.$order;
			} else {
				$href = str_replace('&sort='.$_GET["sort"],'&sort='.$order, $href);
			}
			if ($order == $_GET["sort"]) {
				echo '&nbsp;&nbsp;'.$GLOBALS["iCSort"];
			} else {
				echo '&nbsp;&nbsp;<a href="'.$href.'" '.BuildLinkMouseOver($GLOBALS["tSort"]).'>'.$GLOBALS["iSort"].'</a>';
			}
		}
		?>
	</th>
	<?php
} // function adminlistitem()


function admineditcheck($linkref,$varname,$value,$userid)
{
	global $_GET, $EZ_SESSION_VARS;

	$fLink = BuildLink('m_'.$linkref.'.php');
	if ($GLOBALS["canedit"] == False) {
		if ($userid == $EZ_SESSION_VARS["UserID"]) {
			if (isset($_GET["filtergroupname"])) {
				// Owner Edit privileges
				?>
				<a href="<?php echo $fLink; ?>&<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
				<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
			} else {
				?>
				<a href="<?php echo $fLink; ?>&<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
				<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
			}
		} else {
			if ($GLOBALS["canview"] == False) {
				// No privileges
				echo $GLOBALS["iBlank"];
			} else  {
				// View only privileges
				if (isset($_GET["filtergroupname"])) {
					?>
					<a href="<?php echo $fLink; ?>&<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tView"]); ?>>
					<?php echo $GLOBALS["iView"]; ?></a>&nbsp;<?php
				} else {
					?>
					<a href="<?php echo $fLink; ?>&<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tView"]); ?>>
					<?php echo $GLOBALS["iView"]; ?></a>&nbsp;<?php
				}
			}
		}
	} else {
		// Full Edit privileges
		if (isset($_GET["filtergroupname"])) {
			?>
			<a href="<?php echo $fLink; ?>&<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
			<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
		} else {
			?>
			<a href="<?php echo $fLink; ?>&<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
			<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
		}
	}
} // function admineditcheck()


function admineditcheck2($linkref,$varname1,$value1,$varname2,$value2,$userid)
{
	global $_GET, $EZ_SESSION_VARS;

	$fLink = BuildLink('m_'.$linkref.'.php');
	if ($GLOBALS["canedit"] == False) {
		if ($userid == $EZ_SESSION_VARS["UserID"]) {
			// Owner Edit privileges
			if (isset($_GET["filtergroupname"])) {
				?>
				<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
				<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
			} else {
				?>
				<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
				<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
			}
		} else {
			if ($GLOBALS["canview"] == False) {
				// No privileges
				echo $GLOBALS["iBlank"];
			} else {
				// View only privileges
				if (isset($_GET["filtergroupname"])) {
					?>
					<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tView"]); ?>>
					<?php echo $GLOBALS["iView"]; ?></a>&nbsp;<?php
				} else {
					?>
					<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tView"]); ?>>
					<?php echo $GLOBALS["iView"]; ?></a>&nbsp;<?php
				}
			}
		}
	} else {
		// Full Edit privileges
		if (isset($_GET["filtergroupname"])) {
			?>
			<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
			<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
		} else {
			?>
			<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tEdit"]); ?>>
			<?php echo $GLOBALS["iEdit"]; ?></a>&nbsp;<?php
		}
	}
} // function admineditcheck2()


function admintranslatecheck($linkref,$varname1,$value1,$varname2,$value2)
{
	global $_GET;

	$fLink = BuildLink('m_'.$linkref.'.php');
	if ($GLOBALS["cantranslate"] == False) {
		// No privilege
		echo $GLOBALS["iBlank"];
	} else {
		// Add privilege
		if (isset($_GET["filtergroupname"])) {
			?>
			<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tTranslate"]); ?>>
			<?php echo $GLOBALS["iTranslate"]; ?></a>&nbsp;<?php
		} else {
			?>
			<a href="<?php echo $fLink; ?>&<?php echo $varname1; ?>=<?php echo $value1; ?>&<?php echo $varname2; ?>=<?php echo $value2; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tTranslate"]); ?>>
			<?php echo $GLOBALS["iTranslate"]; ?></a>&nbsp;<?php
		}
	}
} // admintranslatecheck()


function admindeletecheck($linkref,$varname,$value)
{
	global $_GET;

	if ($GLOBALS["candelete"] == False) {
		// No privilege
		echo $GLOBALS["iBlank"];
	} else {
		// Delete privilege
		if (isset($_GET["filtergroupname"])) {
			?>
			<a href="javascript:<?php echo $linkref; ?>('<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tDelete"]); ?>>
			<?php echo $GLOBALS["iDelete"]; ?></a><?php
		} else {
			?>
			<a href="javascript:<?php echo $linkref; ?>('<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tDelete"]); ?>>
			<?php echo $GLOBALS["iDelete"]; ?></a><?php
		}
	}
} // function admindeletecheck()


function adminmovecheck($direction,$linkref,$varname,$value)
{
	global $_GET;

	if ($GLOBALS["canedit"] == False) {
		// No privilege
		echo $GLOBALS["iBlank"];
	} else {
		// Move privilege
		if (isset($_GET["filtergroupname"])) {
			?>
			<a href="javascript:<?php echo $linkref; ?>('<?php echo $varname; ?>=<?php echo $value; ?>&direction=<?php echo $direction; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>');"<?php
		} else {
			?><a href="javascript:<?php echo $linkref; ?>('<?php echo $varname; ?>=<?php echo $value; ?>&direction=<?php echo $direction; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>');"<?php
		}
		if ($direction == 'up') {
			echo BuildLinkMouseOver($GLOBALS["tMoveUp"]).'>'.$GLOBALS["iUp"].'</a>';
		} else {
			echo BuildLinkMouseOver($GLOBALS["tMoveDown"]).'>'.$GLOBALS["iDown"].'</a>';
		}
	}
} // function adminmovecheck()


function adminformsavebar($colspan,$cancelref,$convertcharset=False)
{
	global $_GET, $_POST;

	if ($_POST["page"] == '') { $_POST["page"] = $_GET["page"]; }
	if ($_POST["sort"] == '') { $_POST["sort"] = $_GET["sort"]; }

	$tSave	= charsetText($GLOBALS["tSave"],$convertcharset,$GLOBALS["gsCharset"]);
	$tReset  = charsetText($GLOBALS["tReset"],$convertcharset,$GLOBALS["gsCharset"]);
	$tCancel = charsetText($GLOBALS["tCancel"],$convertcharset,$GLOBALS["gsCharset"]);
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<?php
			if ($GLOBALS["specialedit"] == True) {
				// Save privilege
				?>
				<input type="submit" value="<?php echo $tSave; ?>" name="submit">&nbsp;
				<input type="reset" value="<?php echo $tReset; ?>" name="reset">&nbsp;
				<?php
			}
			?>
			<input type="button" value="<?php echo $tCancel; ?>" onClick="javascript:document.location.href='<?php echo BuildLink($cancelref); ?>&page=<?php echo $_POST["page"]; ?>&sort=<?php echo $_POST["sort"]; ?>&filterlangname=<?php echo $_POST["LanguageCode"]; ?>'" name="cancel">
		</td>
	</tr>
	<?php
} // function adminformsavebar()


function fadminformsavebar($colspan,$cancelref)
{
	global $_GET, $_POST;

	if ($_POST["page"] == '') { $_POST["page"] = $_GET["page"]; }
	if ($_POST["sort"] == '') { $_POST["sort"] = $_GET["sort"]; }
	if ($_POST["filtergroupname"] == '') { $_POST["filtergroupname"] = $_GET["filtergroupname"]; }
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<?php
			if ($GLOBALS["specialedit"] == True) {
				// Save privilege
				?>
				<input type="submit" value="<?php echo $GLOBALS["tSave"]; ?>" name="submit">&nbsp;
				<input type="reset" value="<?php echo $GLOBALS["tReset"]; ?>" name="reset">&nbsp;
				<?php
			}
			?>
			<input type="button" value="<?php echo $GLOBALS["tCancel"]; ?>" onClick="javascript:document.location.href='<?php echo BuildLink($cancelref); ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_POST["sort"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>&filterlangname=<?php echo $_POST["LanguageCode"]; ?>'" name="cancel">
		</td>
	</tr>
	<?php
} // function fadminformsavebar()


function adminhelpmsg($colspan)
{
	?>
	<tr class="headercontent">
		<td colspan="<?php echo $colspan; ?>">
			<?php echo $GLOBALS["tDetails"].'<br /><br />'.$GLOBALS["tHelpText"]; ?>
		</td>
	</tr>
	<?php
} // function adminhelpmsg()


function adminsubheader($colspan, $subheader)
{
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<?php echo '<b>'.$subheader.'</b>'; ?>
		</td>
	</tr>
	<?php
} // function adminsubheader()


function FieldHeading($field,$fieldfocus)
{
	$fieldtextkey = 't'.$field;
	$fieldhelpkey = 'h'.$field;
	?>
	<td valign="top" class="content" width="30%">
	<?php
	if (isset($GLOBALS[$fieldhelpkey])) {
		?>
		<span style="cursor:help">
		<?php
		if ($GLOBALS["specialedit"] == True) {
			?><a OnClick='FieldHelp("<?php echo BuildLink('help.php'); ?>&form=<?php echo $GLOBALS["form"]; ?>&field=<?php echo $field; ?>", "<?php echo $fieldfocus; ?>");' tabindex="<?php echo $GLOBALS["tabindex"]; ?>"><?php
		}
		?>
		<b><?php echo $GLOBALS[$fieldtextkey]; ?>:</b></a></span><?php
	} else {
		?><b><?php echo $GLOBALS[$fieldtextkey]; ?>:</b><?php
	}
	?>
	</td>
	<?php
	$GLOBALS["tabindex"]++;
} // function FieldHeading()

function FieldHeading2($field,$fieldfocus)
{
	$fieldtextkey = 't'.$field;
	$fieldhelpkey = 'h'.$field;

	if (isset($GLOBALS[$fieldhelpkey])) {
		?>
		<span style="cursor:help">
		<?php
		if ($GLOBALS["specialedit"] == True) {
			?><a OnClick='FieldHelp("<?php echo BuildLink('help.php'); ?>&form=<?php echo $GLOBALS["form"]; ?>&field=<?php echo $field; ?>", "<?php echo $fieldfocus; ?>");' tabindex="<?php echo $GLOBALS["tabindex"]; ?>"><?php
		}
		?>
		<b><?php echo $GLOBALS[$fieldtextkey]; ?></b></a></span><?php
	} else {
		?><b><?php echo $GLOBALS[$fieldtextkey]; ?></b><?php
	}
	$GLOBALS["tabindex"]++;
} // function FieldHeading2()

function testPopupIcon($filespec)
{
	if ($GLOBALS["gsAdminStyle"] != '') {
		$fname = $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"]."/images/".$filespec;
		if (file_exists($fname) != true) {
			$fname = $GLOBALS["rootdp"].$GLOBALS["style_home"].$filespec;
			if (file_exists($fname) != true) { $fname = ''; }
		}
	} else {
		$fname = $GLOBALS["rootdp"].$GLOBALS["style_home"].$filespec;
		if (file_exists($fname) != true) { $fname = ''; }
	}
	return $fname;
} // function testPopupIcon()


function adminimagedisplay($FieldName,$ImageFileName,$ShowMessage)
{
	if ($GLOBALS["specialedit"] == True) {
		$fname = testPopupIcon('imagepicker.gif');
		if ($fname != '') {
			?>&nbsp;<a name="<?php echo $FieldName; ?>_images"><a href="#<?php echo $FieldName; ?>_images" <?php echo BuildLinkMouseOver('ImagePicker'); ?> onclick="ImagePicker('<?php echo $FieldName; ?>');"><img src="<? echo $fname; ?>" alt="ImagePicker" border=0></a><?php
		} else {
			?>&nbsp;<input align=absmiddle type="button" value="..." <?php echo BuildLinkMouseOver('ImagePicker'); ?> OnClick="javascript:ImagePicker('<?php echo $FieldName; ?>');"><?php
		}
	}
	if ($ImageFileName != "") {
		?><br /><a href="javascript:ShowImage('<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$ImageFileName; ?>');" <?php echo BuildLinkMouseOver($ShowMessage)?> class="small">
		<img align=absmiddle src="<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$ImageFileName; ?>" border=0 height=30></a>
		<?php
	}
} // function adminimagedisplay()


function admincolourdisplay($FieldName)
{
	if ($GLOBALS["specialedit"] == True) {
		$fname = testPopupIcon('colourpicker.gif');
		if ($fname != '') {
			?>&nbsp;<a name="<?php echo $FieldName; ?>_colours"><a href="#<?php echo $FieldName; ?>_colours" <?php echo BuildLinkMouseOver('ColourPicker'); ?> onclick="ColorPicker('<?php echo $FieldName; ?>');"><img src="<? echo $fname; ?>" alt="ColourPicker" border=0></a><?php
		} else {
			?>&nbsp;<input type="button" value="..." <?php echo BuildLinkMouseOver('ColourPicker'); ?> onclick="ColorPicker('<?php echo $FieldName; ?>');"><?php
		}
	}
} // function admincolourdisplay()


function adminmoduledisplay($FieldName)
{
	if ($GLOBALS["specialedit"] == True) {
		$fname = testPopupIcon('modulepicker.gif');
		if ($fname != '') {
			?>&nbsp;<a name="<?php echo $FieldName; ?>_modules"><a href="#<?php echo $FieldName; ?>_modules" <?php echo BuildLinkMouseOver('ModulePicker'); ?> onclick="ModulePicker('<?php echo $FieldName; ?>');"><img src="<? echo $fname; ?>" alt="ModulePicker" border=0></a><?php
		} else {
			?>&nbsp;<input type="button" value="..." <?php echo BuildLinkMouseOver('ModulePicker'); ?> onclick="ModulePicker('<?php echo $FieldName; ?>');"><?php
		}
	}
} // function adminmoduledisplay()


function admintagdisplay($FieldName)
{
	global $EZ_SESSION_VARS;

	if ($GLOBALS["specialedit"] == True) {
		$fname = testPopupIcon('tagpicker.gif');
		if ($fname != '') {
			?>&nbsp;<a name="<?php echo $FieldName; ?>_tags"><a href="#<?php echo $FieldName; ?>_tags" <?php echo BuildLinkMouseOver('User TagPicker'); ?> onclick="TagPicker('<?php echo $FieldName; ?>');"><img src="<? echo $fname; ?>" alt="User TagPicker" border=0></a><?php
		} else {
			?>&nbsp;<input type="button" value="..." <?php echo BuildLinkMouseOver('User TagPicker'); ?> onclick="TagPicker('<?php echo $FieldName; ?>','<?php echo $EZ_SESSION_VARS["WYSIWYG"]; ?>');"><?php
		}
	}
} // function admintagdisplay()

function admintagdisplay2($FieldName)
{
	global $EZ_SESSION_VARS;
	if ($GLOBALS["specialedit"] == True) {
?>
                <br><select id="seltag" name="seltag" onchange="tinyMCE.execInstanceCommand('<?php echo $FieldName; ?>','mceInsertContent',false,this.options[this.selectedIndex].value);">
                <option value="">-- Tags --</option>
                <option value="[pagelink]article_name,text_block,status_bar_text_block[/pagelink]">Pagelink</option>
                <option value="[include]article_name[/include]">Include</option>
                <option value="[ilink]link_url,link_title[/ilink]">Ilink</option>
                <option value="[teaserinclude]article_name[/teaserinclude]">Teaserinclude</option>
                <option value="[code]code_block[/code]">Code</option>
                <option value="[menulink]top_menu_name,text_block[/menulink]">Menulink</option>
                <option value="[contentlist]menu_name,submenu_name[/contentlist]">Contentlist</option>
                <option value="[file]file_name[/file]">File</option>
                <option value="[grouplist]menu_name[/grouplist]">Grouplist</option>
                <option value="[download]filename,text_block,file_extension[/download]">Download</option>
				<option value="[email]email_address[/email]">Email</option>
				<option value="[link]link_url,link_title[/link]">Link</option>
				<option value="[pagebreak]">Pagebreak</option>
				<option value="[flash]filename,width,height[/flash]">Flash</option>
				<option value="[sitelink]site_name,text_block[/sitelink]">Sitelink</option>
				<option value="[sitelist]">Sitelist</option>
                </select>
<?
	}
} // function admintagdisplay()

function ColourField($fieldname,$fieldvalue)
{
	echo '<input style="background-color: '.$fieldvalue.'" onChange="changeColor(this,this.value)" type="text" name="'.$fieldname.'" id="'.$fieldname.'" size="16" value="'.$fieldvalue.'" maxlength="16"'.$GLOBALS["fieldstatus"].'>';
	admincolourdisplay($fieldname);
} // function ColourField()


function admindatedisplay($FieldName,$DisplayDate='',$DefaultDate='')
{
	if (trim($DisplayDate) == '' ) { $DisplayDate = $DefaultDate; }
	if (trim($DisplayDate) == '' ) { $DisplayDate = sprintf("%04d-%02d-%02d %02d:%02d:%02d", strftime("%Y"), strftime("%m"), strftime("%d"), strftime("%H"), strftime("%M"), strftime("%S")); }

	echo '<select size="1" name="'.$FieldName.'Day"'.$GLOBALS["fieldstatus"].'>';
	BuildDays(substr($DisplayDate, 8, 2));
	echo '</select>';
	echo '<select size="1" name="'.$FieldName.'Month"'.$GLOBALS["fieldstatus"].'>';
	BuildMonths(substr($DisplayDate, 5, 2));
	echo '</select>';
	echo '<select size="1" name="'.$FieldName.'Year"'.$GLOBALS["fieldstatus"].'>';
	BuildYears(substr($DisplayDate, 0, 4));
	echo '</select>';

} // function admincolourdisplay()

function adminHTMLAreadisplay($FieldName,$FieldLabel,$FieldValue,$PrevFieldName,$colspan=1)
{
	global $EZ_SESSION_VARS;

	if ((isset($GLOBALS["textareas"])) && (in_array($FieldName,$GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
		FieldHeading($FieldLabel,$PrevFieldName); ?>
		<td valign="top" colspan="<?php echo $colspan; ?>" class="content">
		<textarea id="<?php echo $FieldName; ?>" name="<?php echo $FieldName; ?>" style="width:540; height:240"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $FieldValue; ?></textarea>
		<?php
	} else {
		FieldHeading($FieldLabel,$FieldName); ?>
		<td valign="top" colspan="<?php echo $colspan; ?>" class="content">
		<?php EditButtons($FieldLabel,$FieldName); ?>
		<textarea rows="8" id="<?php echo $FieldName; ?>" name="<?php echo $FieldName; ?>" cols="64"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($FieldValue); ?></textarea>
		<?php
	}
	//admintagdisplay($FieldName);
	admintagdisplay2($FieldName);
	echo '</td>';
} // function adminHTMLAreadisplay()


function BuildDays($nDaySelected)
{
	for ($i=1; $i<=31; $i=$i+1) {
		echo "<option";
		if (intval($nDaySelected)==$i) { echo " selected"; }
		echo " value=\"".$i."\">".$i."</option>".chr(13);
	}
} // function BuildDays()


function BuildMonths($nMonthSelected)
{
	$Months = $GLOBALS["tMonth_Array"];
	for ( $sMonth = 1; $sMonth <= 12; $sMonth++ ) {
		$s = "";
		if ($sMonth == $nMonthSelected) $s = " selected";
		echo '<option value="'.$sMonth.'"'.$s.'>'.$Months[$sMonth].'</option>';
	}
} // function BuildMonths()


function BuildYears($nYearSelected)
{
	for ($i=2000; $i<=2050; $i=$i+1) {
		echo "<option";
		if (intval($nYearSelected) == $i) { echo " selected"; }
		echo " value=\"".$i."\">".$i."</option>".chr(13);
	}
} // function BuildYears()


function UpdateSetting($value,$name)
{
	$strQuery = "UPDATE ".$GLOBALS["eztbSettings"]." SET settingvalue='".$value."' WHERE settingname='".$name."'";
	$result = dbExecute($strQuery,true);

	$strQuery = "SELECT cssentry FROM ".$GLOBALS["eztbSettings"]." WHERE settingname='".$name."' AND cssentry = 'Y'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rcheck = dbRowsReturned($result);
	if ($rcheck != 0) {
		dbFreeResult($result);
		return true;
	}
	dbFreeResult($result);
	return false;
} // function UpdateSetting()


function bRecordExists($table,$field,$value,$ref)
{
	global $_POST;

	$strQuery = "SELECT ".$ref." FROM ".$GLOBALS[$table]." WHERE ".$field."='".$value."' AND language='".$GLOBALS["gsLanguage"]."'";
	if ($_POST[$ref] != '') {
		$strQuery .= " AND ".$ref." <> ".$_POST[$ref];
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$rcheck = dbRowsReturned($result);
	if ($rcheck != 0) {
		dbFreeResult($result);
		return true;
	}
	dbFreeResult($result);
	return false;
} // function bRecordExists()


function GetSpecialData($ModuleName)
{
	$GLOBALS["scTable"] = $GLOBALS["scCatTable"] = "";

	$strQuery = "SELECT * FROM ".$GLOBALS["eztbSpecialcontents"]." WHERE scname='".$ModuleName."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs			= dbFetch($result);
	if ($rs["scname"] == $ModuleName) {
		$GLOBALS["scTable"] = $rs["scdb"];
		if ($rs["scuseprefix"] == 'Y') { $GLOBALS["scTable"] = $GLOBALS["eztbPrefix"].$GLOBALS["scTable"]; }
		$GLOBALS["scTitle"]				= $rs["sctitle"];
		$GLOBALS["scValidate"]			= $rs["scvalid"];
		$GLOBALS["scLoginRequired"]		= $rs["screg"];
		$GLOBALS["scUsergroups"]		= $rs["usergroups"];
		$GLOBALS["subTextDisplay"]		= $rs["stextdisplay"];
		$GLOBALS["subText"]				= $rs["stext"];
		$GLOBALS["subGraphicDisplay"]	= $rs["sgraphicdisplay"];
		$GLOBALS["subGraphic"]			= $rs["sgraphic"];
		$GLOBALS["scUseCategories"]		= $rs["scusecategories"];
		$GLOBALS["scOrderBy"]			= $rs["orderby"];
		$GLOBALS["scPostedBy"]			= $rs["showpostedby"];
		$GLOBALS["scPostedDate"]		= $rs["showposteddate"];
		$GLOBALS["scPerPage"]			= $rs["perpage"];
		if ($GLOBALS["scUseCategories"] == 'Y') {
			$GLOBALS["scCatTable"] = $GLOBALS["scTable"].'categories';
		}
	}
	dbFreeResult($result);

} // function GetSpecialData()


function CssTag($tagname,$weight,$colour,$font,$fontsize,$fontstyle='',$bgtype='',$background='',$backgroundrepeat='',$bgposition)
{
	$csstagstr = chr(10).$tagname.chr(10);
	$csstagstr .= '{'.chr(10);
	if ($fontsize != '') { $csstagstr .= '	FONT-SIZE: '.$fontsize.';'.chr(10); }
	if ($colour != "") {
		$csstagstr .= '	COLOR: ';
		if (is_numeric($colour)) { $csstagstr .= '#'; }
		$csstagstr .= $colour.';'.chr(10);
	}
	if ($weight != "") {
		$csstagstr .= '	FONT-WEIGHT: '.$weight.';'.chr(10); 
    }        
	if ($font != '') { $csstagstr .= '	FONT-FAMILY: '.$font.';'.chr(10); }
	if ($fontstyle != '') { $csstagstr .= '	TEXT-DECORATION: '.$fontstyle.';'.chr(10); }
	if ($bgtype == 'IMAGE') {
		if ($background != '') {
			if ($background == 'NONE') { $csstagstr .= '	BACKGROUND-IMAGE: '.$background.';'.chr(10);
			} else { $csstagstr .= '	BACKGROUND-IMAGE: URL('.$GLOBALS["rootref"].$GLOBALS["image_home"].$background.');'.chr(10); }
			if ($backgroundrepeat != '') {
				$csstagstr .= 'BACKGROUND-REPEAT: ';
				if ($backgroundrepeat != "Y") { $csstagstr .= 'NO-'; }
				$csstagstr .= 'REPEAT;'.chr(10);
			}
		if ($bgposition != '') {
			$csstagstr .= 'BACKGROUND-POSITION: '.$bgposition.';'.chr(10);
		}
		}
	}
	if ($bgtype == 'COLOUR') {
		if ($background != '') {
			$csstagstr .= '	BACKGROUND-COLOR: ';
			if (is_numeric($background)) { $csstagstr .= '#'; }
			$csstagstr .= $background.';'.chr(10);
		}
	}
	$csstagstr .= '}'.chr(10);
	return $csstagstr;
} // function CssTag()


function CssBackTag($tagname,$colour,$font,$fontsize,$fontstyle,$bgimage,$bgimagerepeat,$bgimagefixed,$bgcolour,$bgposition)
{
	$csstagstr = chr(10).$tagname.chr(10);
	$csstagstr .= '{'.chr(10);
	//$csstagstr .= '	MARGIN: 0;'.chr(10);
	if ($fontsize != '') { $csstagstr .= '	FONT-SIZE: '.$fontsize.';'.chr(10); }
	if ($colour != "") {
		$csstagstr .= '	COLOR: ';
		if (is_numeric($colour)) { $csstagstr .= '#'; }
		$csstagstr .= $colour.';'.chr(10);
	}
	if ($font != '') { $csstagstr .= '	FONT-FAMILY: '.$font.';'.chr(10); }
	if ($fontstyle != '') { $csstagstr .= '	TEXT-DECORATION: '.$fontstyle.';'.chr(10); }
	if ($bgimage != '') {
		if ($bgimage == 'NONE') { $csstagstr .= '	BACKGROUND-IMAGE: '.$bgimage.';'.chr(10); }
		else { $csstagstr .= '	BACKGROUND-IMAGE: URL('.$GLOBALS["rootref"].$GLOBALS["image_home"].$bgimage.');'.chr(10); }
		$csstagstr .= 'BACKGROUND-REPEAT: ';
		if ($bgimagerepeat != "Y") { $csstagstr .= 'NO-'; }
		$csstagstr .= 'REPEAT;'.chr(10);
		if ($bgimagefixed != '') {
			$csstagstr .= 'BACKGROUND-ATTACHMENT: FIXED;'.chr(10);
		}
		if ($bgposition != '') {
			$csstagstr .= 'BACKGROUND-POSITION: '.$bgposition.';'.chr(10);
		}
	}
	if ($bgcolour != '') {
		$csstagstr .= '	BACKGROUND-COLOR: ';
		if (is_numeric($bgcolour)) { $csstagstr .= '#'; }
		$csstagstr .= $bgcolour.';'.chr(10);
	}
	$csstagstr .= '}'.chr(10);
	return $csstagstr;
} // function CssBackTag()


function RebuildStyleSheet()
{
	GLOBAL $EZ_SESSION_VARS;

	// If site is configured with Safe Mode enabled, or open_basedir defined, we always generate the stylesheet at run-time,
	//		so don't bother building it now.
	if ($GLOBALS["safe_mode"] || $GLOBALS["open_basedir"] <> '') { return false; }


	//  Retrieve the settings we've just updated
	GetSettings();
	$savedir = getcwd();

	//  Work out the directory that we need to save the file to based on Site and Theme
	if ($EZ_SESSION_VARS["Site"] != '') {
		$setdir = $GLOBALS["rootdp"].$GLOBALS["sites_home"];
		$setdir .= $EZ_SESSION_VARS["Site"];
		if ($EZ_SESSION_VARS["Theme"] != '') {
			$setdir .= '/themes/';
			$setdir .= $EZ_SESSION_VARS["Theme"];
		}
	} else {
		$setdir = $GLOBALS["rootdp"].$GLOBALS["themes_home"];
		if ($EZ_SESSION_VARS["Theme"] != '') {
			$setdir .= $EZ_SESSION_VARS["Theme"];
		}
	}
	if (substr($setdir,-1) == '/') { $setdir = substr($setdir,0,-1); }
	chdir($setdir);
	//  Images referenced in the stylesheet are relative to the stylesheet directory, so work
	//			out the relative directory
	$reldir = explode('/',$setdir);
	$count = count($reldir) - 1;
    $b = 'bold';
    $n = 'normal';
	$GLOBALS["rootref"] = str_repeat('../',$count);
	//  Write the stylesheet file
	$fp = fopen("ezc.css", "wb");

	fwrite($fp,CssTag('A',$n,$GLOBALS["color_ahref"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],$GLOBALS["gsFontStyle1"]));
	fwrite($fp,CssTag('A:visited',$n,$GLOBALS["color_ahref_visited"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],$GLOBALS["gsFontStyle1"]));
	fwrite($fp,CssTag('A:hover',$n,$GLOBALS["color_ahref_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],$GLOBALS["gsFontStyle1"]));

	if ($GLOBALS["gsSmallFontSize"] != "") {
		fwrite($fp,CssTag('A.small',$n,$GLOBALS["color_ahref_small"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],$GLOBALS["gsFontStyle1"]));
		fwrite($fp,CssTag('A.small:visited',$n,$GLOBALS["color_ahref_small_visited"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],$GLOBALS["gsFontStyle1"]));
		fwrite($fp,CssTag('A.small:hover',$n,$GLOBALS["color_ahref_small_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],$GLOBALS["gsFontStyle1"]));
	} else {
		fwrite($fp,CssTag('A.small',$n,$GLOBALS["color_ahref_small"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],$GLOBALS["gsFontStyle1"]));
		fwrite($fp,CssTag('A.small:visited',$n,$GLOBALS["color_ahref_small_visited"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],$GLOBALS["gsFontStyle1"]));
		fwrite($fp,CssTag('A.small:hover',$n,$GLOBALS["color_ahref_small_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],$GLOBALS["gsFontStyle1"]));
	}

	fwrite($fp,CssTag('A.menulink',$n,$GLOBALS["menu_color_ahref"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize3"],$GLOBALS["gsFontStyle3"]));
	fwrite($fp,CssTag('A.menulink:visited',$n,$GLOBALS["menu_color_ahref_visited"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize3"],$GLOBALS["gsFontStyle3"]));
	fwrite($fp,CssTag('A.menulink:hover',$n,$GLOBALS["menu_color_ahref_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize3"],$GLOBALS["gsFontStyle3"]));

	fwrite($fp,CssTag('A.submenulink',$n,$GLOBALS["menu_color_ahref"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],$GLOBALS["gsFontStyle3"]));
	fwrite($fp,CssTag('A.submenulink:visited',$n,$GLOBALS["menu_color_ahref_visited"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],$GLOBALS["gsFontStyle3"]));
	fwrite($fp,CssTag('A.submenulink:hover',$n,$GLOBALS["menu_color_ahref_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],$GLOBALS["gsFontStyle3"]));

	fwrite($fp,CssTag('A.topmenulink',$n,$GLOBALS["topmenu_color_ahref"],$GLOBALS["gsFont1"],$GLOBALS["gsTopMenuFontSize"],$GLOBALS["gsTopMenuFontStyle"]));
	fwrite($fp,CssTag('A.topmenulink:visited',$n,$GLOBALS["topmenu_color_ahref_visited"],$GLOBALS["gsFont1"],$GLOBALS["gsTopMenuFontSize"],$GLOBALS["gsTopMenuFontStyle"]));
	fwrite($fp,CssTag('A.topmenulink:hover',$n,$GLOBALS["topmenu_color_ahref_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsTopMenuFontSize"],$GLOBALS["gsTopMenuFontStyle"]));

	fwrite($fp,CssTag('A.rightcol',$n,$GLOBALS["rcol_color_ahref"],$GLOBALS["gsFont1"],$GLOBALS["gsRColFontSize"],$GLOBALS["gsRColFontStyle"]));
	fwrite($fp,CssTag('A.rightcol:visited',$n,$GLOBALS["rcol_color_ahref_visited"],$GLOBALS["gsFont1"],$GLOBALS["gsRColFontSize"],$GLOBALS["gsRColFontStyle"]));
	fwrite($fp,CssTag('A.rightcol:hover',$n,$GLOBALS["rcol_color_ahref_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsRColFontSize"],$GLOBALS["gsRColFontStyle"]));

	fwrite($fp,CssTag('A.heading',$n,$GLOBALS["color_header"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize2"]));
	fwrite($fp,CssTag('A.heading:visited',$n,$GLOBALS["color_header"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize2"]));
	fwrite($fp,CssTag('A.heading:hover',$n,$GLOBALS["color_ahref_hover"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize2"]));

	fwrite($fp,CssTag('TD',$n,$GLOBALS["color_td"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','IMAGE','NONE'));
	fwrite($fp,CssTag('H1',$n,$GLOBALS["color_h1"],$GLOBALS["gsFont1"],'20px'));
	fwrite($fp,CssTag('.header',$n,$GLOBALS["color_header"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize2"],'','IMAGE',$GLOBALS["gsHeaderB"],$GLOBALS["gbHeaderBgRep"],$GLOBALS["gbHeaderBgPos"]));
	fwrite($fp,CssTag('.rcolheader',$n,$GLOBALS["rcol_color_header"],$GLOBALS["gsFont1"],$GLOBALS["gsRColHeaderFontSize"],'','IMAGE',$GLOBALS["gsHeaderB"],$GLOBALS["gbHeaderBgRep"],$GLOBALS["gbHeaderBgPos"]));
	fwrite($fp,CssTag('.teaserheader',$n,$GLOBALS["gsColor_tsrheader"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize2"],'','IMAGE',$GLOBALS["gsHeaderB"],$GLOBALS["gbHeaderBgRep"],$GLOBALS["gbHeaderBgPos"]));

	fwrite($fp,CssTag('.headercontent',$n,'#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["bgcolor_headercnt"]));
	fwrite($fp,CssTag('.teaserheadercontent',$n,'#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["gsBgcolor_headertsr"]));
	fwrite($fp,CssTag('.rcolheadercontent',$n,'#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["rcol_bgcolor_headercnt"]));

	fwrite($fp,CssTag('.tablecontent',$n,$GLOBALS["color_td"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["bgcolor_cnttbl"]));
	fwrite($fp,CssTag('.teasercontent',$n,$GLOBALS["gsColor_tsrtd"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["gsBgcolor_tsrtbl"]));
	fwrite($fp,CssTag('.rcolcontent',$n,$GLOBALS["rcol_color_td"],$GLOBALS["gsFont1"],$GLOBALS["gsRColFontSize"],'','COLOUR',$GLOBALS["rcol_bgcolor_cnttbl"]));

	fwrite($fp,CssTag('.tablecontentfooter',$n,$GLOBALS["color_td"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],'','COLOUR',$GLOBALS["bgcolor_cnttbl"]));
	fwrite($fp,CssTag('.teasercontentfooter',$n,$GLOBALS["gsColor_tsrtd"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],'','COLOUR',$GLOBALS["gsBgcolor_tsrtbl"]));

	fwrite($fp,CssTag('.menu',$n,'#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["bgcolor_menu"]));
	fwrite($fp,CssTag('.topmenu',$n,'#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["bgcolor_topmenu"]));

	fwrite($fp,CssTag('.helptext',$n,$GLOBALS["gsHelptextColor"],$GLOBALS["gsFont1"],$GLOBALS["gsHelptextFontSize"]));
	fwrite($fp,CssTag('.smalldropdown',$n,$GLOBALS["gsHelptextColor"],$GLOBALS["gsFont1"],$GLOBALS["gsSmallFontSize"],$GLOBALS["gsFontStyle1"]));

	fwrite($fp,CssTag('body',$n,$GLOBALS["color_td"],$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'','COLOUR',$GLOBALS["bgcolor_cnttbl"]));	

	fwrite($fp,CssBackTag('.topback','#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'',$GLOBALS["gsTopBg"],$GLOBALS["gbTopBgRep"],$GLOBALS["gbTopBgFix"],$GLOBALS["bgcolor_header"],$GLOBALS["gbHeaderBgPos"]));
	fwrite($fp,CssBackTag('.menuback','#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'',$GLOBALS["gsMenuBg"],$GLOBALS["gbMenuBgRep"],$GLOBALS["gbMenuBgFix"],$GLOBALS["bgcolor_menu"],$GLOBALS["gbMenuBgPos"]));
	fwrite($fp,CssBackTag('.mainback','#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'',$GLOBALS["gsMainBg"],$GLOBALS["gbMainBgRep"],$GLOBALS["gbMainBgFix"],$GLOBALS["bgcolor_main"],$GLOBALS["gbMainBgPos"]));
	fwrite($fp,CssBackTag('.topmenuback','#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'',$GLOBALS["gsTopMenuBg"],$GLOBALS["gbTopMenuBgRep"],$GLOBALS["gbTopMenuBgFix"],$GLOBALS["bgcolor_topmenu"],$GLOBALS["gbTopMenuBgPos"]));
	fwrite($fp,CssBackTag('.bottomback','#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'',$GLOBALS["gsFooterBg"],$GLOBALS["gbFooterBgRep"],$GLOBALS["gbFooterBgFix"],$GLOBALS["bgcolor_footer"],$GLOBALS["gbFooterBgPos"]));
	fwrite($fp,CssBackTag('.userdataback','#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'',$GLOBALS["gsUserdataBg"],$GLOBALS["gbTopBgRep"],$GLOBALS["gbTopBgFix"],$GLOBALS["bgcolor_header"],$GLOBALS["gbTopBgPos"]));
	fwrite($fp,CssBackTag('.borderback','#FFFFFF',$GLOBALS["gsFont1"],$GLOBALS["gsFontSize1"],'',$GLOBALS["gsBorderBg"],$GLOBALS["gbBorderBgRep"],$GLOBALS["gbBorderBgFix"],$GLOBALS["bgcolor_border"],$GLOBALS["gbBorderBgPos"]));

	fwrite($fp,('.submit {font-family:Verdana, Arial; font-size: 9pt; color:#333333; border-color:#666666; border-width:1px; BACKGROUND-COLOR: #F3F0F0; font-weight:bold}'));

	fwrite($fp,('input,textarea,select {font-family:Verdana, Arial; font-size: 9pt; color:#333333; border-color:#666666; border-width:1px}'));

	fwrite($fp,('li {list-style-image: url('.$GLOBALS["rootref"].$GLOBALS["image_home"].$GLOBALS["gsListStyleIcon"].')}'));	

	fwrite($fp,('body,select,textarea { scrollbar-face-color: '.$GLOBALS["bgcolor_main"].'; scrollbar-shadow-color: #666666; scrollbar-highlight-color: '.$GLOBALS["bgcolor_main"].'; scrollbar-3dlight-color: '.$GLOBALS["bgcolor_main"].'; scrollbar-darkshadow-color: '.$GLOBALS["bgcolor_main"].'; scrollbar-track-color: '.$GLOBALS["bgcolor_main"].'; scrollbar-arrow-color: #666666}'));

	fwrite($fp,('.small {color: '.$GLOBALS["color_ahref_small"].'; font-family: '.$GLOBALS["gsFont1"].'; font-size: '.$GLOBALS["gsSmallFontSize"].';}'));

	if ($GLOBALS["gnImageColumnBreak"] != '') {
		$csstagstr = chr(10).'.sep_column'.chr(10);
		$csstagstr .= '{'.chr(10);
		$csstagstr .= '	BACKGROUND-REPEAT : REPEAT-Y;'.chr(10);
		$csstagstr .= '	BACKGROUND : URL('.$GLOBALS["rootref"].$GLOBALS["image_home"].$GLOBALS["gnImageColumnBreak"].');'.chr(10);
		$csstagstr .= '}'.chr(10);
		fwrite($fp,$csstagstr);
	}

	fclose($fp);
	chdir($savedir);
} // function RebuildStyleSheet()


function display_size($file_size)
{
	if ($file_size >= 1073741824) {
		$file_size = round($file_size / 1073741824 * 100) / 100 ." GB";
	} elseif ($file_size >= 1048576) {
		$file_size = round($file_size / 1048576 * 100) / 100 ." MB";
	} elseif ($file_size >= 1024) {
		$file_size = round($file_size / 1024 * 100) / 100 ." KB";
	} else {
		$file_size = $file_size." bytes";
	}
	return $file_size;
} // function display_size()


function formError($cols=1)
{
	$errorstring = implode('<li>',$GLOBALS["strErrors"]);
	echo '<tr bgcolor=#900000><td colspan="'.$cols.'"><b><ul><li>'.$errorstring.'</ul></b></td></tr>';
} // function formError()


function charsetText($text,$convertcharset,$fromcharset)
{
	$returntext = $text;
	if ($convertcharset) { $returntext = mb_convert_encoding($returntext,"UTF-8",$fromcharset); }
	return $returntext;
} // function charsetText()


function UTF8Text($text,$convertcharset,$tocharset)
{
	$returntext = $text;
	if ($convertcharset) { $returntext = mb_convert_encoding($returntext,$tocharset,"UTF-8"); }
	return $returntext;
} // function charsetText()


function SiteBaseUrl($Site='')
{
	$scriptpath = explode('/',$GLOBALS["PAGE_URL"]);
	$dummy = array_pop($scriptpath);
	$adp = explode('/',$GLOBALS["rootdp"]);
	$dpCount = count($adp);
	$i = 1;
	while ($i < $dpCount) {
		$dummy = array_pop($scriptpath);
		$i++;
	}
	if ($Site != '') { array_push($scriptpath, 'sites',$Site); }
	$SiteBaseUrl = implode('/',$scriptpath).'/';
	return $SiteBaseUrl;
} // function SiteBaseUrl()


function formatWYSIWYGText (&$ftext,$rtext)
{
	$nCurrent = 0;
	while ($nCurrent >= 0) {
		$nCurrent = strpos($ftext, $rtext, $nCurrent);
		if ($nCurrent === false) { $nCurrent = -1;
		} else {
			$nCurrent += strlen($rtext);
			$pretext = substr($ftext, 0, $nCurrent);
			$posttext = substr($ftext, $nCurrent);

			if ( (isExternalLink($posttext)) || (substr($posttext,0,1) == '#') ||
				 (substr($posttext,0,11) == 'javascript:') ) {
			} elseif (substr($posttext,0,2) == './') {
				$posttext = $GLOBALS["base_url"].substr($posttext,2);
			} else {
				$posttext = $GLOBALS["base_url"].$posttext;
			}
			$ftext = $pretext.$posttext;
		}
	}
	return $ftext;
} // formatWYSIWYGText ()


function formatWYSIWYG ($text)
{
	$ftext = $text;
	$pos = strpos($ftext, chr(10));
	//if ($pos !== false) { $ftext = str_replace(chr(10),'<br />',$ftext); }
	//	Strip obsolete [html] tags
	$pos = strpos($ftext, $GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"]);
	if ($pos !== false) { 
		$ftext = str_replace($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"],'', $ftext);
		$ftext = str_replace($GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"],'', $ftext);
	}
	formatWYSIWYGText($ftext,'src="');
	formatWYSIWYGText($ftext,'href="');

	return $ftext;
} // function formatWYSIWYG ()


function array_csort()
{
	$args = func_get_args();
	$marray = array_shift($args);
	$i= 0;

	$msortline = "return(array_multisort(";
	foreach ($args as $arg) {
		$i++;
		if (is_string($arg)) {
			foreach ($marray as $row) {
				$sortarr[$i][] = $row[$arg];
			}
		} else {
			$sortarr[$i] = $arg;
		}
		$msortline .= "\$sortarr[".$i."],";
	}
	$msortline .= "\$marray));";

	eval($msortline);
	return $marray;
} // array_csort()


function safeModeWarning($colcount)
{
	if ($GLOBALS["safe_mode"] || $GLOBALS["open_basedir"] <> '') {
		echo '<tr bgcolor="#900000"><td colspan="'.$colcount.'"><b>'.$GLOBALS["eSafeModeWarning"].'</b></td></tr>';
	}
} // function safeModeWarning()

?>