<?php

/***************************************************************************

 compile.php
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


$strQuery="SELECT * FROM ".$GLOBALS["eztbTags"];
$result = dbRetrieve($strQuery,true,0,0);
while ($rs = dbFetch($result)) {
	$tagname = $rs["tag"];
	$tagvalue = $rs["translation"];
	$GLOBALS["translation"][$tagname] = $tagvalue;
}
dbFreeResult($result);


function compile($text, $WYSIWYG='N', $secure='N', $chColumn='L', $allowScript='N')
{
/***************************************************************************

	Parameters :-
	$text			- The block of text to compile.
	$secure			- Secure compilation flag.
						'Y'	- Anything goes, with the exception of embedded <script> scripts (unless
								$allowScript is set).
						'C'	- Controlled, no scripting in content, and only those pre-defined tags that are
								listed in the $GLOBALS["PermittedTags"] array.
						'N'	- Only user-defined tags permitted (default).
	$chColumn		- 'L'eft or 'Right' column.
	$allowScript	- 'Y'es or 'N'o (default 'N'o).
						If set to 'Y'es, and $secure is 'Y'es, then embedded <script> scripts are
						permitted.

 ***************************************************************************/

	if ($chColumn == 'R') { $strHrefClass = 'class="rightcol" '; }
	if ($secure != 'Y') { $allowScript='N'; }

	$hnCount = 0;

	$cText = $text;
	if ($cText != '') {
		$tqBlock1		= $GLOBALS["tqBlock1"];
		$tqBlock2		= $GLOBALS["tqBlock2"];
		$tqCloseBlock	= $GLOBALS["tqCloseBlock"];
		$tqSeparator	= $GLOBALS["tqSeparator"];


		$blocks = explode($tqBlock1."html".$tqBlock2, $cText);
		$i = 0;
		foreach ($blocks as $block) {
			if ($i == 0) {
				$pretext = htmlspecialchars($block);
				$i++;
			} else {
				$htmlblocks = explode($tqBlock1.$tqCloseBlock."html".$tqBlock2, $block);
				$j = 0;
				foreach ($htmlblocks as $htmlblock) {
					if ($j == 0) {
						$htmlblock = str_replace("\r", " ", $htmlblock);
						if ($WYSIWYG == 'Y') { $htmlblock = str_replace("\n", " ", $htmlblock);
						} else { $htmlblock = str_replace("\n", "<br />", $htmlblock); }
						$pretext .= $htmlblock;
						$j++;
					} else {
						$pretext .= htmlspecialchars($htmlblock);
					}
				}
			}
		}
		$cText = $pretext;


		if ($allowScript != 'Y') {
			//	Suppress any script tags that appear in the content unless scripts are
			//	explicitly permitted
			$cText = str_replace('<script',$tqBlock1."code".$tqBlock2.'<script', $cText);
			$cText = str_replace('<SCRIPT',$tqBlock1."code".$tqBlock2.'<SCRIPT', $cText);
			$cText = str_replace('<Script',$tqBlock1."code".$tqBlock2.'<Script', $cText);
			$cText = str_replace('</script>','</script>'.$tqBlock1.$tqCloseBlock."code".$tqBlock2, $cText);
			$cText = str_replace('</SCRIPT>','</SCRIPT>'.$tqBlock1.$tqCloseBlock."code".$tqBlock2, $cText);
			$cText = str_replace('</Script>','</Script>'.$tqBlock1.$tqCloseBlock."code".$tqBlock2, $cText);
			$cText = str_replace('<applet',$tqBlock1."code".$tqBlock2.'<applet', $cText);
			$cText = str_replace('<APPLET',$tqBlock1."code".$tqBlock2.'<APPLET', $cText);
			$cText = str_replace('<Applet',$tqBlock1."code".$tqBlock2.'<Applet', $cText);
			$cText = str_replace('</applet>','</applet>'.$tqBlock1.$tqCloseBlock."code".$tqBlock2, $cText);
			$cText = str_replace('</APPLET>','</APPLET>'.$tqBlock1.$tqCloseBlock."code".$tqBlock2, $cText);
			$cText = str_replace('</Applet>','</Applet>'.$tqBlock1.$tqCloseBlock."code".$tqBlock2, $cText);
		}


		$testTag = 'code';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,&$nStartCode,&$nEndCode,$cText);
				$nCode = "";
				if ($nCurrent > 0) {
					$pretext = substr($cText, 0, $nStartCode);
					$posttext = substr($cText, $nEndCode + 7, strlen ($cText) - $nEndCode);
					$nCode = substr($cText, $nStartCode + 6, $nEndCode - $nStartCode - 6);
					$cText = $pretext.'<font face="Courier New,Courier,Monospace" style="FONT-SIZE: 10px;" color="#D0FFD0">'.htmlspecialchars($nCode).'</font>'.$posttext;
				}
			}
		} // [code] tag


		$testTag = 'link';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,&$nStartlink,&$nEndlink,$cText);
				$sLink = "";
				if ($nCurrent > 0) {
					$sLink = substr($cText, $nStartlink + 6, $nEndlink - $nStartlink - 6);

					$Linkdata = explode($tqSeparator,$sLink);
					$sURL = trim($Linkdata[0]);
					$sText = trim($Linkdata[1]);
					if ($sText == '') { $sText = $sURL; }
					$cText = substr_replace ($cText, '<a '.$strHrefClass.'href="'.$sURL.'" target="_blank">'.$sText.'</a>', $nStartlink, $nEndlink - $nStartlink + 7);
				}
			}
		} // [link] tag


		$testTag = 'ilink';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$cText);
				if ($nCurrent > 0) {
					$sLink = substr($cText, $nStartlink + 7, $nEndlink - $nStartlink - 7);

					$Linkdata = explode($tqSeparator,$sLink);
					$sURL = trim($Linkdata[0]);
					$sText = trim($Linkdata[1]);
					if ($sText == '') { $sText = $sURL; }
					$cText = substr_replace ($cText, '<a '.$strHrefClass.'href="'.$sURL.'">'.$sText.'</a>', $nStartlink, $nEndlink - $nStartlink + 8);
				}
			}
		} // [ilink] tag


		$testTag = 'hovernote';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$cText);
				if ($nCurrent > 0) {
					$sLink = substr($cText, $nStartlink + 11, $nEndlink - $nStartlink - 11);

					$Linkdata = explode($tqSeparator,$sLink);
					$sNote = trim($Linkdata[0]);
					$sText = trim($Linkdata[1]);
					if ($sText == '') {
						$hnCount++;
						$sText = $hnCount;
					}
					$cText = substr_replace ($cText, '<a '.$strHrefClass.'href="#" title="'.$sNote.'">'.$sText.'</a>', $nStartlink, $nEndlink - $nStartlink + 12);
				}
			}
		} // [hovernote] tag


		$testTag = 'download';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$cText);
				$sLink = "";
				if ($nCurrent > 0) {
					$sLink = substr($cText, $nStartlink + 10, $nEndlink - $nStartlink - 10);

					$Linkdata = explode($tqSeparator,$sLink);
					$sFile = trim($Linkdata[0]);
					$sText = trim($Linkdata[1]);
					$sIcon = trim($Linkdata[2]);

					$fileicon = '';
					$filesplit = explode(".", $sFile);
					$extension = array_pop($filesplit);
					if ($sIcon != '') { $extension = $sIcon; }
					$strQuery = "SELECT * FROM ".$GLOBALS["eztbFiletypes"]." WHERE filetype='".$extension."'";
					$result = dbRetrieve($strQuery,true,0,1);
					while ($rs = dbFetch($result)) {
						$fileicon = '<img src="'.$GLOBALS["image_home"].$rs["fileicon"].'">&nbsp;';
					}
					dbFreeResult($result);

					$cText = substr_replace ($cText, $fileicon.'<a '.$strHrefClass.'href="'.$GLOBALS["downloads_home"].$sFile.'" target="_blank">'.$sText.'</a>', $nStartlink, $nEndlink - $nStartlink + 11);
				}
			}
		} // [download] tag


		$testTag = 'email';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$cText);
				$nLink = "";
				if ($nCurrent > 0) {
					$nLink = substr($cText, $nStartlink + 7, $nEndlink - $nStartlink - 7);
					$cText = substr_replace ($cText, '<a '.$strHrefClass.'href="mailto:'.$nLink.'">'.$nLink.'</a>', $nStartlink, $nEndlink - $nStartlink + 8);
				}
			}
		} // [email] tag


		$testTag = 'flash';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$cText);
				$fLink = "";
				if ($nCurrent > 0) {
					$fLink = substr($cText, $nStartlink + 7, $nEndlink - $nStartlink - 7);
					$Flashdata = explode($tqSeparator,$fLink);
					$sFile = trim($Flashdata[0]);
					$sWidth = trim($Flashdata[1]);
					$sHeight = trim($Flashdata[2]);
					$flashref = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"';
					if ($sWidth != '') { $flashref .= ' width="'.$sWidth.'"'; }
					if ($sHeight != '') { $flashref .= ' height="'.$sHeight.'"'; }
					$flashref .= '>';
					$flashref .= '<param name=movie value="'.$sFile.'">';
					$flashref .= '<param name=quality value=high>';
					$flashref .= '<param name="wmode" value="transparent">';
					$flashref .= '<embed src="'.$sFile.'" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"';
					if ($sWidth != '') { $flashref .= ' width="'.$sWidth.'"'; }
					if ($sHeight != '') { $flashref .= ' height="'.$sHeight.'"'; }
					$flashref .= '>';
					$flashref .= '</embed>';
					$flashref .= '</object>';
					$cText = substr_replace ($cText, $flashref, $nStartlink, $nEndlink - $nStartlink + 8);
				}
			}
		} // [flash] tag


		reset ($GLOBALS["translation"]);
		while (list ($key, $val) = each ($GLOBALS["translation"])) {
			$cText = str_replace($tqBlock1.$key.$tqBlock2,$val, $cText);
		}
	}
	return $cText;
} // function compile()


?>
