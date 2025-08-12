<?php

/***************************************************************************

 tagpicker2.php
 ---------------
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

include_once ("rootdatapath.php");

includeLanguageFiles('admin');

$GLOBALS["PermittedTags"] = explode($GLOBALS["tqSeparator"],urldecode($_GET["restricted"]));


force_page_refresh();
frmTags();


function frmTags()
{
	global $_GET, $EzAdmin_Style;


	$Taglist = array (	array (	'tagname'	=> 'code',
								'tagtype'	=> 'precompiled',
								'tagformat'	=> $GLOBALS["tqBlock1"].'code'.$GLOBALS["tqBlock2"].'code_block'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'code'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'contentlist',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'contentlist'.$GLOBALS["tqBlock2"].'<menu_name>'.$GLOBALS["tqSeparator"].'<submenu_name>'.$GLOBALS["tqCloseBlock"].'contentlist'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'download',
								'tagtype'	=> 'precompiled',
								'tagformat'	=> $GLOBALS["tqBlock1"].'download'.$GLOBALS["tqBlock2"].'filename'.$GLOBALS["tqSeparator"].'text_block'.$GLOBALS["tqSeparator"].'<file_extension>'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'download'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'email',
								'tagtype'	=> 'precompiled',
								'tagformat'	=> $GLOBALS["tqBlock1"].'email'.$GLOBALS["tqBlock2"].'email_address'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'email'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'file',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'file'.$GLOBALS["tqBlock2"].'file_name'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'file'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'flash',
								'tagtype'	=> 'precompiled',
								'tagformat'	=> $GLOBALS["tqBlock1"].'flash'.$GLOBALS["tqBlock2"].'filename'.$GLOBALS["tqSeparator"].'<width>'.$GLOBALS["tqSeparator"].'<height>'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'flash'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'grouplist',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'grouplist'.$GLOBALS["tqBlock2"].'<menu_name>'.$GLOBALS["tqCloseBlock"].'grouplist'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'hovernote',
								'tagtype'	=> 'precompiled',
								'tagformat'	=> $GLOBALS["tqBlock1"].'hovernote'.$GLOBALS["tqBlock2"].'note'.$GLOBALS["tqSeparator"].'text_block'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'hovernote'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'ilink',
								'tagtype'	=> 'precompiled',
								'tagformat'	=> $GLOBALS["tqBlock1"].'ilink'.$GLOBALS["tqBlock2"].'link_url'.$GLOBALS["tqSeparator"].'<link_title>'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'ilink'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'image',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'image'.$GLOBALS["tqBlock2"].'image_filename'.$GLOBALS["tqSeparator"].'image_format_template'.$GLOBALS["tqSeparator"].'<caption>'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'image'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'include',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'include'.$GLOBALS["tqBlock2"].'article_name'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'include'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'link',
								'tagtype'	=> 'precompiled',
								'tagformat'	=> $GLOBALS["tqBlock1"].'link'.$GLOBALS["tqBlock2"].'link_url'.$GLOBALS["tqSeparator"].'<link_title>'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'link'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'menulink',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'menulink'.$GLOBALS["tqBlock2"].'top_menu_name'.$GLOBALS["tqSeparator"].'text_block'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'menulink'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'pagebreak',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'pagebreak'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'pagelink',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'pagelink'.$GLOBALS["tqBlock2"].'article_name'.$GLOBALS["tqSeparator"].'<text_block>'.$GLOBALS["tqSeparator"].'<status_bar_text_block>'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'pagelink'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'sidebar',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'sidebar'.$GLOBALS["tqBlock2"].'sidebar_template'.$GLOBALS["tqSeparator"].'text_block'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'sidebar'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'sitelink',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'sitelink'.$GLOBALS["tqBlock2"].'site_name'.$GLOBALS["tqSeparator"].'text_block'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'sitelink'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'sitelist',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'sitelist'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'teaserinclude',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'teaserinclude'.$GLOBALS["tqBlock2"].'article_name'.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'teaserinclude'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> 'year',
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].'year'.$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> $GLOBALS["tqBlock1"],
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].$GLOBALS["tqBlock1"].$GLOBALS["tqBlock2"] ),
						array (	'tagname'	=> $GLOBALS["tqBlock2"],
								'tagtype'	=> 'run-time',
								'tagformat'	=> $GLOBALS["tqBlock1"].$GLOBALS["tqBlock2"].$GLOBALS["tqBlock2"] ),
						) ;


	admhdr();
	if ($_GET["WYSIWYG"] != 'Y') {
		?>
		<script language="JavaScript" type="text/javascript">
			<!-- Begin
				function ReturnModule(sTagName) {
					var input=window.opener.document.forms["MaintForm"].<?php echo $_GET["control"]; ?>;
					input.value=input.value+sTagName;
					window.close();
				}
			//  End -->
		</script>
		<?php
	}
	?>
	<title>TagPicker</title>
	</head>
	<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">
	<table border="0" width="100%" cellspacing="3" cellpadding="3"><?php

	// Generate image tags for the different images that appear on the page
	adminbuttons('','','','');
	$iSelectImage = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["SelectIcon"],'',0,'');

	$nCurrentPage = 0;
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }

	$lRecCount = count($Taglist);

	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	frmTagsHdFt($nCurrentPage,$nPages);

	$i = $lStartRec;
	$j = $lStartRec + $GLOBALS["RECORDS_PER_PAGE"];
	while ($i < $j) {
		if ($i < $lRecCount) {
			$validTag = True;
			if ($_GET["secure"] == 'C') {
				if (!(in_array($Taglist[$i]["tagname"],$GLOBALS["PermittedTags"]))) { $validTag = False; }
				if ($Taglist[$i]["tagname"] == $GLOBALS["tqBlock1"]) { $validTag = True; }
				if ($Taglist[$i]["tagname"] == $GLOBALS["tqBlock2"]) { $validTag = True; }
			}
			?>
			<tr class="teasercontent">
			<td width="15%" valign="top"><?php
			if (($validTag) && ($_GET["WYSIWYG"] != 'Y')) {
				?><a class="menulink" href="javascript:ReturnModule('<?php echo $Taglist[$i]["tagformat"]; ?>')"><?php
			}
			if (!$validTag) { echo '<font color="LightBlue">'; }
			echo $Taglist[$i]["tagname"];
			if (!$validTag) { echo '</font>'; }
			if (($validTag) && ($_GET["WYSIWYG"] != 'Y')) { echo '</a>'; }
			?></td>
				<td width="20%" valign="top"><?php echo $Taglist[$i]["tagtype"]; ?></td>
				<td valign="top"><?php echo htmlspecialchars($Taglist[$i]["tagformat"]); ?></td>
			</tr>
			<?php
		}
		$i++;
	}

	frmTagsHdFt($nCurrentPage,$nPages);

	?>
	<tr class="headercontent">
		<td colspan="3" align="<?php echo $GLOBALS["right"]; ?>"><a href="javascript:window.close();"><?php echo $GLOBALS["tCloseHelp"]; ?></a></td>
	</tr>
	</table>
	</body>
	</html>
	<?php
} // function frmTags()


function frmTagsHdFt($nCurrentPage,$nPages)
{
	global $_GET;
	?>
	<tr class="topmenuback">
		<td colspan="3" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
						<a href="<?php echo BuildLink('tagpicker2.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&secure=<?php echo $_GET["secure"]; ?>&restricted=<?php echo $_GET["restricted"]; ?>&page=0"><?php echo $GLOBALS["iFirst"]; ?></a>&nbsp;<?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo BuildLink('tagpicker2.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&secure=<?php echo $_GET["secure"]; ?>&restricted=<?php echo $_GET["restricted"]; ?>&page=<?php echo $nCurrentPage - 1; ?>"><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo BuildLink('tagpicker2.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&secure=<?php echo $_GET["secure"]; ?>&restricted=<?php echo $_GET["restricted"]; ?>&page=<?php echo $nCurrentPage + 1; ?>"><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; } ?>
						<a href="<?php echo BuildLink('tagpicker2.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&secure=<?php echo $_GET["secure"]; ?>&restricted=<?php echo $_GET["restricted"]; ?>&page=<?php echo $nPages - 1; ?>"><?php echo $GLOBALS["iLast"]; ?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
} // function frmTagsHdFt()

?>
