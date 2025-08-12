<?php

/***************************************************************************

 functions.php
 --------------
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

function force_page_refresh()
{
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");			// Date in the past
	header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");		// always modified
	header ("Cache-Control: private, no-store, no-cache, must-revalidate");			// HTTP/1.1
	header ("Cache-Control: post-check=0, pre-check=0", false);
	header ("Pragma: no-cache");						// HTTP/1.0
} // function force_page_refresh()


function HTMLHeader($title,$doctype = "Transitional")
{
	$charsets = explode(',',$GLOBALS["gsCharset"]);
	$charset = $charsets[0];
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 <?php echo $doctype; ?>//EN">
	<html dir="<?php echo $GLOBALS["gsDirection"]; ?>">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
	<title><?php echo $title; ?></title>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		function putFocus(formInst, elementInst) {
			if (document.forms[formInst]) {
				if (typeof elementInst == 'number') {
					for (i = elementInst; i < document.forms[formInst].elements.length; i++) {
						if (document.forms[formInst].elements[i].type != 'hidden' && document.forms[formInst].elements[i].disabled != true) {
							document.forms[formInst].elements[i].focus();
							return true;
						}
					}
				}
				else
				{
					if (document.forms[formInst].elements[elementInst].type != 'hidden' && document.forms[formInst].elements[elementInst].disabled != true) {
						document.forms[formInst].elements[elementInst].focus();
						return true;
					}
				}
			}
			return false;
		}
		//  End -->
	</script>
	<?php
}


// Date Formatting
function FormatDate($udate)
{
	$old_locale = setlocale(LC_ALL, 0);
	setlocale (LC_TIME,$GLOBALS["locale"]);
	$rdate = strftime($GLOBALS["gsDateFormat"],strtotime($udate));
	setlocale(LC_ALL, $old_locale);
	if ($GLOBALS["gsTimezone"] != '') { $rdate .= ' '.$GLOBALS["gsTimezone"]; }
	return $rdate;
} // function FormatDate()


// Display time to generate page for efficiency testing
function Start_Timer()
{
	if (($GLOBALS["gsTimegen_display"] == 'Y') || ($GLOBALS["gsTimegen_display"] == 'F')) {
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$GLOBALS["starttime"] = $mtime;
	}
} // function StartTime()


function End_Timer()
{
	global $_SERVER;

	if (($GLOBALS["gsTimegen_display"] == 'Y') || ($GLOBALS["gsTimegen_display"] == 'F')) {
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$GLOBALS["endtime"] = $mtime;
		$totaltime = ($GLOBALS["endtime"] - $GLOBALS["starttime"]);
		echo '<center><table border=0 bgcolor="blue"><tr><td align="center"><font size="-2" color="yellow">';
		printf('ezContents Created this page in %01.4f seconds,<br />', $totaltime);
		printf('with %u database accesses taking %01.4f seconds.', $GLOBALS["dbAccesses"], $GLOBALS["dbTotalTime"]);
		echo '</font></td></tr></table></center>';

		if ($GLOBALS["gsTimegen_display"] == 'F') {
			$fp = fopen("./timer.log", "ab");
			if ($fp) {
				fwrite($fp,$_SERVER["REQUEST_URI"].chr(08).$totaltime.chr(08).$GLOBALS["dbTotalTime"].chr(10));
				fclose($fp);
			}
		}
	}
} // function EndTime()


// Option of gzip compression for page
function Start_Gzip()
{
	if ($GLOBALS["gsUse_compression"] == 'Y') {
		if (!headers_sent() && (connection_status() == 0)) {
			if (strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== FALSE) {
				$GLOBALS["gzip_encoding"] = 'x-gzip';
			} elseif (strpos($HTTP_ACCEPT_ENCODING,'gzip') !== FALSE) {
				$GLOBALS["gzip_encoding"] = 'gzip';
			}
			if ($GLOBALS["gzip_encoding"]) {
				ob_start();
				ob_implicit_flush(FALSE);
			}
		}
	} else {
		ob_start();
		ob_implicit_flush(FALSE);
	}
} // function Start_Gzip()


function End_Gzip()
{
	if (($GLOBALS["gsUse_compression"] == 'Y') && ($GLOBALS["gzip_encoding"])) {
		$contents = ob_get_contents();
		ob_end_clean();
		header("Content-Encoding: ".$GLOBALS["gzip_encoding"]);
		print "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		$size = strlen($contents);
		$crc = crc32($contents);
		$contents = gzcompress($contents, 9);
		$contents = substr($contents, 0, strlen($contents) - 4);
		print $contents;
		print pack('V', $crc);
		print pack('V', $size);
	} else {
		ob_end_flush();
	}
} // function End_Gzip()


// This function determines whether an image filename is language specific or not, determines the height and width of the displayed image,
//	and generates an html image tag which is then returned to the calling procedure
function lsimagehtmltag($directory,$filename,$language,$alttext='',$border='',$align='')
{
	if ((!isset($GLOBALS["rootdp"])) || ($GLOBALS["rootdp"] == '')) { $GLOBALS["rootdp"] = './'; }
	if ($border == '') { $border = '0'; }
	$imgalign = 'middle';
	if (strtoupper($align) == 'R') { $imgalign = 'right';
	} elseif (strtoupper($align) == 'L') { $imgalign = 'left'; }
	$imagetag = '';
	if ($filename != '') {
		$rfilename = strrev($filename);
		$name = trim(substr(strstr($rfilename, "."), 1));
		$ext = trim(substr($rfilename, 0, strpos($rfilename, ".")));
		$name = strrev($name);
		$ext = strrev($ext);
		$imagesize = '';
		$fname = $GLOBALS["rootdp"].$directory.'/'.$name."_".$language.".".$ext;
		$fname = str_replace('//', '/', $fname);
		$fname = str_replace('/./', '/', $fname);
		if (file_exists($fname) == true) {
			$size = GetImageSize($fname);
			$imagesize = $size["3"];
		} else {
			$fname = $GLOBALS["rootdp"].$directory.'/'.$name."_".$GLOBALS["gsDefault_language"].".".$ext;
			$fname = str_replace('//', '/', $fname);
			$fname = str_replace('/./', '/', $fname);
			if (($GLOBALS["gsDefault_language"] != $language ) && (file_exists($fname) == true)) {
				$size = GetImageSize($fname);
				$imagesize = $size["3"];
			} else {
				$fname = $GLOBALS["rootdp"].$directory.'/'.$name.".".$ext;
				$fname = str_replace('//', '/', $fname);
				$fname = str_replace('/./', '/', $fname);
				if (file_exists($fname) == true) {
					$size = GetImageSize($fname);
					$imagesize = $size["3"];
				}
			}
		}
		if ($imagesize != '') { $imagetag = '<img src="'.$fname.'" alt="'.$alttext.'" border="'.$border.'" align="'.$imgalign.'" '.$imagesize.'>'; }
	}
	return $imagetag;
} // function lsimagehtmltag()


// This function determines the height and width of the displayed image, and generates an html image tag which is then returned to the calling procedure
function imagehtmltag($directory,$filename,$alttext,$border,$align)
{
	if ((!isset($GLOBALS["rootdp"])) || ($GLOBALS["rootdp"] == '')) { $GLOBALS["rootdp"] = './'; }
	if ($border == '') { $border = '0'; }
	$imgalign = 'middle';
	if (strtoupper($align) == 'R') { $imgalign = 'right';
	} elseif (strtoupper($align) == 'L') { $imgalign = 'left'; }
	$imagesize = '';
	$imagetag = '';
	if ($filename != '') {
		$fname = $GLOBALS["rootdp"].$directory.'/'.$filename;
		$fname = str_replace('//', '/', $fname);
		$fname = str_replace('/./', '/', $fname);
		if (file_exists($fname) == true) {
			$size = GetImageSize($fname);
			$imagesize = $size["3"];
		}
	}
	if ($imagesize != '') { $imagetag = '<img src="'.$fname.'" alt="'.$alttext.'" border="'.$border.'" align="'.$imgalign.'" '.$imagesize.'>'; }
	return $imagetag;
} // function imagehtmltag()


// This function determines the height and width of the displayed image, and generates an html image tag which is then returned to the calling procedure
function imagelinktag($directory,$filename,$nametext)
{
	if ((!isset($GLOBALS["rootdp"])) || ($GLOBALS["rootdp"] == '')) { $GLOBALS["rootdp"] = './'; }
	$imagesize = '';
	if (file_exists($GLOBALS["rootdp"].$directory.'/'.$filename) == true) {
		$size = GetImageSize($GLOBALS["rootdp"].$directory.'/'.$filename);
		$imagesize = $size["3"];
	}
	$imagetag = '<img src="'.$GLOBALS["rootdp"].$directory.'/'.$filename.'" name="'.$nametext.'" border="0" '.$imagesize.'>';
	return $imagetag;
} // function imagelinktag()


function DateAdj($plusminus, $interval, $number, $sdate) {
	$date_time_array  = getdate($sdate);

	$hours =  $date_time_array["hours"];
	$minutes =  $date_time_array["minutes"];
	$seconds =  $date_time_array["seconds"];
	$month =  $date_time_array["mon"];
	$day =  $date_time_array["mday"];
	$year =  $date_time_array["year"];

	if ($plusminus == '+') {
		switch ($interval) {
			case "y": $year += $number;		break;
			case "m": $month += $number;	break;
			case "d": $day += $number;		break;
			case "w": $day += ($number*7);	break;
		}
	} else {
		switch ($interval) {
			case "y": $year -= $number;		break;
			case "m": $month -= $number;	break;
			case "d": $day -= $number;		break;
			case "w": $day -= ($number*7);	break;
		}
	}
	$timestamp =  mktime($hours,$minutes,$seconds,$month,$day,$year);
	return $timestamp;
} // function DateAdj()

function DateSub($interval, $number, $sdate)
{
	return DateAdj('-',$interval, $number, $sdate);
} // function DateSub()

function DateAdd($interval, $number, $sdate)
{
	return DateAdj('+',$interval, $number, $sdate);
} // function DateSub()


// This function determines whether an language filename exists or not
//	and includes the appropriate text file in a language default
function include_languagefile($directory,$language,$filename)
{
	$rfilename = strrev($filename);
	$name = trim(substr(strstr($rfilename, "."), 1));
	$ext = trim(substr($rfilename, 0, strpos($rfilename, ".")));
	$name = strrev($name);
	$fname = $name."_en.".$ext;
	$fulldir = $GLOBALS["rootdp"].$directory.'/';
	if (file_exists($fulldir.$fname) == true) {
		include_once ($fulldir.'/'.$fname);
	}
	if ($GLOBALS["gsDefault_language"] != 'en') {
		$fname = $name."_".$GLOBALS["gsDefault_language"].".".$ext;
		if (file_exists($fulldir.'/'.$fname) == true) {
			include_once ($fulldir.'/'.$fname);
		}
	}
	if (($language != 'en') && ($language != $GLOBALS["gsDefault_language"])) {
		$fname = $name."_".$language.".".$ext;
		if (file_exists($fulldir.'/'.$fname) == true) {
			include_once ($fulldir.'/'.$fname);
		}
	}

	return True;
} // function include_languagefile()


function BuildLinkMouseOver($OMOStr)
{
	$OMO  = ' onMouseOver="window.status=\''.str_replace("'","\'",$OMOStr).'\'; return true;"';
	$OMO .= ' onMouseOut="window.status=\'\'; return true;"';
	return $OMO;
} // function BuildLinkMouseOver()


function pagedHdFtSite($form,$colspan,$nCurrentPage,$nPages)
{
	global $_GET;

	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		$pLink = BuildLink($form.'.php').'&ref=userdata.php';
	} else {
		$pLink = BuildLink('control.php').'&link='.$form.'.php&ref=control.php'.BuildGroupsLink();
	}

	?>
	<tr>
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
					<?php
						if ($nCurrentPage != 0) {
							?><a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage - 1; ?>"><?php echo $GLOBALS["tPrevPage"] ?></a><?php
						} else {
							echo $GLOBALS["tPrevPage"];
						}

						// Pages to show as links
						$nPagesToShow = 5;
						if($nPagesToShow > $nPages) { $nPagesToShow = $nPages; }

						$nMinPage = 1;
						$nMaxPage = $nPagesToShow;
						// If the page is over the half of the pages to show then adjust the min and max values.
						if($nCurrentPage + 1 > intval(($nPagesToShow - 0.5) / 2) + 1) {
							if($nCurrentPage + 1 <= $nPages - intval(($nPagesToShow - 0.5) / 2)) {
								$nMinPage = $nCurrentPage + 2 - ($nPagesToShow - intval(($nPagesToShow - 0.5) / 2));
								$nMaxPage = $nCurrentPage + 1 + intval(($nPagesToShow - 0.5) / 2);
							} else {
								$nMinPage = $nPages - $nPagesToShow + 1;
								$nMaxPage = $nPages;
							}

						}
						echo '&nbsp;';
						for($i=$nMinPage; $i<=$nMaxPage; $i++) {
							if($i - 1 != $nCurrentPage) {
								?><a href="<?php echo $pLink; ?>&page=<?php echo $i - 1; ?>"><?php echo $i ?>&nbsp;</a><?php
							} else {
								if($nPages != 1) { echo $i . "&nbsp;"; }
							}
						}
						if ($nCurrentPage + 1 != $nPages) {
							?><a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage + 1; ?>"><?php echo $GLOBALS["tNextPage"] ?></a><?php
						} else {
							echo $GLOBALS["tNextPage"];
						}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
}


function pagedHdFt($form,$colspan,$nCurrentPage,$nPages)
{
	global $_GET;

	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		$pLink = BuildLink($form.'.php').'&ref=userdata.php"';
	} else {
		$pLink = BuildLink('control.php').'&ulink='.$form.'.php&link='.$form.'.php&ref=control.php'.BuildGroupsLink();
	}

	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
						<a href="<?php echo $pLink; ?>&page=0" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>>
						<?php echo $GLOBALS["iFirst"]; ?></a><?php
						if ($nCurrentPage != 0) {
							?>
							<a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>>
							<?php echo $GLOBALS["iPrev"]; ?></a><?php
						} else {
							echo $GLOBALS["iPrev"];
						}
						$nCPage = $nCurrentPage + 1;
						echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
						if ($nCurrentPage + 1 != $nPages) {
							?>
							<a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage + 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>>
							<?php echo $GLOBALS["iNext"]; ?></a><?php
						} else {
							echo $GLOBALS["iNext"];
						}
						?>
						<a href="<?php echo $pLink; ?>&page=<?php echo $nPages - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>>
						<?php echo $GLOBALS["iLast"]; ?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
} // function pagedHdFt()


// Function that automatically appends the SID to a link
function BuildLink($script)
{
	global $_COOKIE;

	if (isset($_COOKIE["ezSID"])) { $blink = $script.'?';
	// Start modification by Comic
	// Get rid of the ezSID when bots are visiting
  	} else if ($GLOBALS["ezSID"]=="") { $blink = $script.'?';
// End modification by Comic
	} else { $blink = $script.'?ezSID='.$GLOBALS["ezSID"]; }
	return($blink);
} // function BuildLink()


function BuildGroupsLink()
{
	global $_GET;

	$bgl = '';
	if ($_GET["topgroupname"] != '') { $bgl .= '&topgroupname='.$_GET["topgroupname"]; }
	if ($_GET["groupname"] != '') { $bgl .= '&groupname='.$_GET["groupname"]; }
	if ($_GET["subgroupname"] != '') { $bgl .= '&subgroupname='.$_GET["subgroupname"]; }
	if ($_GET["contentname"] != '') { $bgl .= '&contentname='.$_GET["contentname"]; }
	return $bgl;
} // function BuildGroupsLink()


function GetOrderByText($groupname,$subgroupname)
{
	if ($subgroupname != '') {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$subgroupname."' AND groupname='".$groupname."' AND language='".$GLOBALS["gsLanguage"]."'";
		$fieldorderby = "submenuorderby";
		$fieldorderdir = "submenuorderdir";
	} elseif ($groupname != '') {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$groupname."' AND language='".$GLOBALS["gsLanguage"]."'";
		$fieldorderby = "menuorderby";
		$fieldorderdir = "menuorderdir";
	} else {
		$GLOBALS["orderText"] = '';
		return false;
	}

	$result = dbRetrieve($strQuery,true,0,0);

	$GLOBALS["orderText"] = 'ORDER BY ';
	if ($rs = dbFetch($result)) {
		if ($rs[$fieldorderby] == "2") {
			$GLOBALS["orderText"] .= "publishdate ";
		} elseif ($rs[$fieldorderby] == "3") {
			$GLOBALS["orderText"] .= "updatedate ";
		} elseif ($rs[$fieldorderby] == "4") {
			$GLOBALS["orderText"] .= "title ";
		} else {
			$GLOBALS["orderText"] .= "orderid ";
		}
		if ($rs[$fieldorderdir] == "A") {
			$GLOBALS["orderText"] .= "ASC";
		} elseif ($rs[$fieldorderdir] == "D") {
			$GLOBALS["orderText"] .= "DESC";
		}
	}
	dbFreeResult($result);

	if ($GLOBALS["gsLanguage"] != $GLOBALS["gsDefault_language"]) {
		$lOrder = '';
		if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
		if ($GLOBALS["orderText"] != 'ORDER BY ') $GLOBALS["orderText"] .= ",";
		$GLOBALS["orderText"] .= "language".$lOrder;
	}
	if (($GLOBALS["orderText"] == 'ORDER BY ') || ($GLOBALS["orderText"] == 'ORDER BY  DESC')) { $GLOBALS["orderText"] = ''; }
} // function GetOrderByText()


function EditButtons($Name,$name)
{
	$icondir = $GLOBALS["rootdp"].$GLOBALS["icon_home"];
	?>
	<script language="JavaScript1.2" type="text/javascript">
		<!--
		function paste_strinL<?php echo $Name; ?>(<?php echo $Name; ?>, <?php echo $name; ?>){
			var isForm=document.forms["MaintForm"];
			if (isForm) {
				var input=document.forms["MaintForm"].elements["<?php echo $name; ?>"];
				input.value=input.value+<?php echo $Name; ?>;
			}
		}
		//-->
	</script>
	<?php
	for ($i=0, $max=count($GLOBALS["editorTags"]); $i < $max; $i++) {
		$tag	= $GLOBALS["editorTags"][$i];
		$parms	= $GLOBALS["editorParms"][$i];
		$text	= $GLOBALS["editorTexts"][$i];
		$image	= $GLOBALS["editorIcons"][$i];
		?>
		<a href="JavaScript:paste_strinL<?php echo $Name; ?>('[<?php echo $tag; ?>]<?php echo $parms; ?>[/<?php echo $tag; ?>]',0)" onMouseOver="window.status='<?php echo $text; ?>'; return true" onMouseOut="window.status=''; return true"><img src="<?php echo $icondir.$image; ?>.gif" width="23" height="22" alt="<?php echo $text; ?>" border="0"></a>
		<?php
	}
	echo '<br />';
} // EditButtons()


function DetailReturnLink($returntext)
{
	global $_SERVER;
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="headercontent">
		<tr><td class="tablecontent">&nbsp;<br /><?php echo $GLOBALS["tClickToReturn"]; ?>
				<a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>"<?php
				echo ' title="'.$returntext.'" '.BuildLinkMouseOver($returntext).'"> ';
				echo $returntext; ?></a><br />&nbsp;</td>
		</tr>
	</table>
	<?php
} // function DetailReturnLink()


function locatestylesheet()
{
	global $EZ_SESSION_VARS;

	//  Work out the directory where the stylesheet file is located, based on Site and Theme
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
	$stylesheet = $styledir.'ezc.css';

	return $stylesheet;
} // function locatestylesheet()


function StyleSheet()
{
	//  If PHP has safe mode set 'On' and/or open_basedir defined, then we may not have been able to write the stylesheet file
	//			so we generate it within the HTML instead.
	//  It's an overhead, but that's the price you pay for playing with your security settings.
	if ($GLOBALS["safe_mode"] || $GLOBALS["open_basedir"] <> '') {
		include('./include/style.php');
	} else {
		?>
		<LINK HREF="<?php echo locatestylesheet(); ?>" REL=STYLESHEET TYPE="text/css">
		<?php
	}
} // function StyleSheet()


function hiddenmenu($loginreq,$usergroups)
{
	global $EZ_SESSION_VARS;

	$hidden = false;
	if (($GLOBALS["gsPrivateMenus"] == 'H') && ($loginreq == 'Y')) {
		if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $hidden = true;
		} else {
			if ($rs["usergroups"] != '') {
				$Menu_Usergroups = explode(',',$usergroups);
				if (($EZ_SESSION_VARS["UserGroup"] == '') || (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups))) { $hidden = true; }
			}
		}
	}
	return $hidden;
} // function hiddenmenu(


function privatemenu($loginreq,$usergroups,$vlink)
{
	global $EZ_SESSION_VARS;

	$link = $vlink;
	if ($loginreq == 'Y') {
		// User isn't logged in
		if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $link = "loginreq.php";
		} else {
			// User is logged in, so test against the list of valid user groups for this option
			if ($usergroups != '') {
				$Menu_Usergroups = explode(',',$usergroups);
				if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups)) {
					$link = "loginreq2.php";
				}
			}
		}
	}
	return $link;
}  // function privatemenu()


function topmenusecuritycheck($topgroupname)
{
	global $EZ_SESSION_VARS;

	$menuaccess = True;
	if ($GLOBALS["gsShowTopMenu"] == 'Y') {
		$strQuery = "SELECT loginreq,usergroups FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$topgroupname."' AND language='".$GLOBALS["gsDefault_language"]."'";
		$result = dbRetrieve($strQuery,true,0,1);
		if ($rs = dbFetch($result)) {
			$loginreq	= $rs["loginreq"];
			$usergroups = $rs["usergroups"];
		}
		dbFreeResult($result);

		if ($loginreq == 'Y') {
			// Check if user is logged in
			if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $menuaccess = False;
			} else {
				// User is logged in, so test against the list of valid user groups for this option
				if ($usergroups != '') {
					$Menu_Usergroups = explode(',',$usergroups);
					if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups)) { $menuaccess = False; }
				}
			}
		}
	}
	return $menuaccess;
} // function topmenusecuritycheck()


function menusecuritycheck($groupname,$loginreq,$usergroups)
{
	global $EZ_SESSION_VARS;

	$menuaccess = True;
	if ($loginreq == 'Y') {
		// Check if user is logged in
		if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $menuaccess = False;
		} else {
			// User is logged in, so test against the list of valid user groups for this option
			if ($usergroups != '') {
				$Menu_Usergroups = explode(',',$usergroups);
				if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups)) { $menuaccess = False; }
			}
		}
	}
	return $menuaccess;
} // function menusecuritycheck()


function testSimpleTag($tag,&$nCurrent,&$nStartgroup,$teststring)
{
	$nStartgroup = strpos($teststring, $GLOBALS["tqBlock1"].$tag.$GLOBALS["tqBlock2"], $nCurrent);
	if ($nStartgroup === False) { $nCurrent = -1;
	} else {
		$nCurrent = $nStartgroup + strlen($tag) + 2;
	}
}  // function testSimpleTag()


function testOpenCloseTag($tag,&$nCurrent,&$nStartgroup,&$nEndgroup,$teststring)
{
	$nStartgroup = strpos($teststring, $GLOBALS["tqBlock1"].$tag.$GLOBALS["tqBlock2"], $nCurrent);
	if ($nStartgroup === False) { $nCurrent = -1;
	} else {
		$nEndgroup = strpos($teststring, $GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].$tag.$GLOBALS["tqBlock2"], $nStartgroup);
		if (($nEndgroup === False) || ($nEndgroup < $nStartgroup)) { $nEndgroup = strlen($teststring) - 1; }
		$nCurrent = $nEndgroup + 1;
	}
}  // function testOpenCloseTag()


function includeLanguageFile($languagedir,$filename,$language) {
	global $EZ_SESSION_VARS;

	$filefound = False;
	if ($EZ_SESSION_VARS["Country"] <> '') {
		$fname = $languagedir.$language."/lang_".$filename."_".$EZ_SESSION_VARS["Country"].".php";
		if (file_exists($fname) == true) {
			include_once ($fname);
			$filefound = True;
		}
	}
	if (!$filefound) {
		$fname = $languagedir.$language."/lang_".$filename.".php";
		if (file_exists($fname) == true) {
			include_once ($fname);
			$filefound = True;
		}
	}
	return $filefound;
} // function includeLanguageFile()


function includeLanguageFiles() {
	$FileCount = func_num_args();
	if ($FileCount > 0) {
		$FileList = func_get_args();
		$languagedir	= $GLOBALS["rootdp"].$GLOBALS["language_home"];
		if (isset($GLOBALS["gsLanguage"])) { $gsLanguage = $GLOBALS["gsLanguage"]; } else { $gsLanguage = ''; }
		if (isset($GLOBALS["gsDefaultLanguage"])) { $gsDefaultLanguage = $GLOBALS["gsDefaultLanguage"]; } else { $gsDefaultLanguage = ''; }
		for ($i=0; $i<$FileCount; $i++) {
			includeLanguageFile($languagedir,$FileList[$i],'en');
			if (($gsDefaultLanguage != '') && ($gsDefaultLanguage != 'en')) { includeLanguageFile($languagedir,$FileList[$i],$gsDefaultLanguage); }
			if (($gsLanguage != '') && ($gsLanguage != 'en') && ($gsLanguage != $gsDefaultLanguage)) { includeLanguageFile($languagedir,$FileList[$i],$gsLanguage); }
		}
	}
} // includeLanguageFiles()


function ezContentsRootDir()
{
	$savedir = getcwd();
	//	Convert Windows directory reference to Unix format if necessary
	$savedir = str_replace('\\', '/', $savedir);
	if (substr($savedir,1,1) != '/') { $savedir = substr($savedir,2); }
	//	Pop to the roor level
	$dirs = explode('/',$savedir);
	$rootcount = substr_count($GLOBALS["rootdp"], '../'); 
	$i = 1;
	while ($i <= $rootcount) {
		array_pop($dirs);
		$i++;
	}
	//	Tidy up
    $savedir = implode('/',$dirs);
	$savedir = str_replace('//', '/', $savedir);

	return $savedir;
} // function ezContentsRootDir()


function isExternalLink ($linkref)
{
	if ( (substr($linkref,0,5) == 'http:')		|| (substr($linkref,0,6) == 'https:')	||
		 (substr($linkref,0,5) == 'file:')		|| (substr($linkref,0,4) == 'ftp:')		||
		 (substr($linkref,0,7) == 'gopher:')	|| (substr($linkref,0,7) == 'mailto:')	||
		 (substr($linkref,0,5) == 'news:')		|| (substr($linkref,0,7) == 'telnet:')	||
		 (substr($linkref,0,5) == 'wais:') ) {
		 return True;
	} else {
		 return False;
	}
} // isExternalLink

function randomstring ($length, $useletters=False, $usemixed=False, $usenumbers=False, $usespecial=False)
{ 
	$key = $charset = '';
	if ($useletters)	$charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	if ($usemixed)		$charset .= "abcdefghijklmnopqrstuvwxyz"; 
	if ($usenumbers)	$charset .= "0123456789"; 
	if ($usespecial)	$charset .= "~!@#$%^*()_+-={}";
	$charsetlen = strlen($charset) - 1;
    for ($i=0; $i<$length; $i++) {
    	$key .= $charset[mt_rand(0,(charsetlen))];
    }
    return $key; 
} // randomstring()

?>
