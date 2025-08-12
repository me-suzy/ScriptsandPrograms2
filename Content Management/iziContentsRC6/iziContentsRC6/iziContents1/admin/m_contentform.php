<?php

/***************************************************************************

 m_contentform.php
 ------------------
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


// Localisation variables (used for default values)
// Change these to suit your site preferences
//
$expiryperiod = 'y';	//	Time period to calculate the content expiry date (based on today's date)
$expirynumber = 5;		//	$expiryperiod = 'y' - years
						//	$expiryperiod = 'm' - months
						//	$expiryperiod = 'w' - weeks
						//	$expiryperiod = 'd' - days
						//	$expirynumber - number of $expiryperiod before expiry
$ImageFileTypes = array( 'gif', 'jpg', 'jpeg', 'png');
$GLOBALS["authorvisible_default"] = '';
$GLOBALS["updatedatevisible_default"] = 'Y';


include_once ("rootdatapath.php");
include_once ("compile.php");

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//	have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'content';
$validaccess = VerifyAdminLogin3("ContentName");

includeLanguageFiles('main','admin','content');


//	Set list of textareas in an array for HTMLArea integration
$GLOBALS["textareas"]	= array('teaser','body');
$GLOBALS["base_url"] = SiteBaseUrl($EZ_SESSION_VARS["Site"]);


// If we've been passed the request from the content list, then we
//	read content data from the database for an edit request, or skip
//	if this is an 'add new' request
if ($_GET["ContentName"] != '') {
	$_POST["ContentName"] = $_GET["ContentName"];
	$_POST["page"] = $_GET["page"];
	$_POST["filtergroupname"] = $_GET["filtergroupname"];
	GetGlobalData();
} else {
	if ($_GET["filtergroupname"] != '') { $GLOBALS["fsGroupName"] = $_GET["filtergroupname"]; }
	$timenow = time();								// Calculate the default expiry date
	$GLOBALS["DefExpDate"] = date('Y-m-d H:i:s',DateAdd($expiryperiod,$expirynumber,$timenow));
	$GLOBALS["fbHeaderVisible"]	= 'Y';
	$GLOBALS["fbAuthorVisible"]	= $GLOBALS["authorvisible_default"];
	$GLOBALS["fbUpdateDateVisible"] = $GLOBALS["updatedatevisible_default"];
	$GLOBALS["fsPrinterFriendly"]	= $GLOBALS["gsPrinterFriendly"];
    	$GLOBALS["fsPDFPrint"]          = $GLOBALS["gsPDFPrint"];
	$GLOBALS["fsTellFriend"]	= $GLOBALS["gsTellFriend"];
	$GLOBALS["fsAuthorId"]		= $EZ_SESSION_VARS["UserID"];
}

$GLOBALS["tabindex"] = 1024;
	

if ($_POST["submitted"] == "yes") {
	// User has submitted the data
	if (bCheckForm()) {
		AddContent();
		Header("Location: ".BuildLink('m_content.php')."&page=".$_POST["page"]."&filtergroupname=".$_POST["filtergroupname"]);
	} else {
		// Invalid data has been submitted
		GetFormData();
	}
}
frmContentsForm();


function frmContentsForm()
{
	global $_POST, $EZ_SESSION_VARS;

	adminformheader();
	adminformopen('contentname');
	adminformtitle(4,$GLOBALS["tFormTitle"]);
	if (isset($GLOBALS["strErrors"])) { formError(4); }
	adminsubheader(4,$GLOBALS["thContentLinks"]);
	?>
	<tr class="tablecontent">
		<?php FieldHeading("PageRef","contentname"); ?>
		<td width="100%" valign="top" colspan="3" class="content">
			<input type="text" name="contentname" size="32" value="<?= $GLOBALS["fsContentName"]; ?>" maxlength="32"<?= $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("Menu","cat"); ?>
		<td valign="top" colspan="3" class="content">
			<select name="cat" onChange="setCats(window.document.MaintForm.cat.options[selectedIndex].value)"<?= $GLOBALS["fieldstatus"]; ?>><option value="0"><?= $GLOBALS["tSelect"]; ?>:<?php
				RenderGroupOptions($GLOBALS["fsGroupName"]);
				if ($GLOBALS["fsGroupName"] == '999999999') {
					?><OPTION value="999999999" selected>-- <?= $GLOBALS["tAllMenus"]; ?> --<?php
				}
				elseif (($GLOBALS["gsSectionSecurity"] != 'Y') || ($GLOBALS["fieldstatus"] == ' disabled') || ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"]))
				{
					?><OPTION value="999999999">-- <?= $GLOBALS["tAllMenus"]; ?> --<?php
				}
				?>
			</select>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("Submenu","subgroupname"); ?>
		<td valign="top" colspan="3" class="content">
			<select name="subgroupname"<?= $GLOBALS["fieldstatus"]; ?>>
			<option value="0"><?= $GLOBALS["tSelect"]; ?>:<?php RenderSubGroupOptions($GLOBALS["fsGroupName"]); ?></select>
		</td>
	</tr>
	<?php adminsubheader(4,$GLOBALS["thDates"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("PublishDate","PublishDay"); ?>
		<td colspan="3" valign="top" class="content">
			<?php admindatedisplay('Publish',$GLOBALS["fsPublishDate"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("ExpiryDate","ExpireDay"); ?>
		<td colspan="3" valign="top" class="content">
			<?php admindatedisplay('Expire',$GLOBALS["fsExpireDate"],$GLOBALS["DefExpDate"]); ?>
		</td>
	</tr>
	<?php adminsubheader(4,$GLOBALS["thHeader"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("Header","title"); ?>
		<td valign="top" colspan="3" class="content">
			<input type="text" name="title" size="70" value="<?= htmlspecialchars($GLOBALS["fsTitle"]) ?>" maxlength=255<?= $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("HeaderImage","headerimage"); ?>
		<td valign="top" colspan="3" class="content">
			<input type="text" name="headerimage" size="64" value="<?= $GLOBALS["fsHeaderImage"]; ?>" maxlength="255"<?= $GLOBALS["fieldstatus"]; ?>>
			<?php adminimagedisplay('headerimage',$GLOBALS["fsHeaderImage"],$GLOBALS["tShowImage"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("ShowHeader","headervisible"); ?>
		<td valign="top" colspan="3" class="content">
			<input type="checkbox" name="headervisible" value="Y" <?php if($GLOBALS["fbHeaderVisible"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<?php adminsubheader(4,$GLOBALS["thTeaser"]); ?>
	<tr class="tablecontent">
		<?php adminHTMLAreadisplay("teaser","Teaser",$GLOBALS["fsTeaser"],"headervisible",3); ?>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("TeaserImage","image"); ?>
		<td colspan="3" valign="top" class="content">
			<input type="text" name="image" size="64" value="<?= $GLOBALS["fsImage"]; ?>" maxlength="255"<?= $GLOBALS["fieldstatus"]; ?>>
			<?php adminimagedisplay('image',$GLOBALS["fsImage"],$GLOBALS["tShowImage"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("TeaserImageAlign",13); ?>
		<td colspan="3" valign="top" class="content">
			<input type="radio" value="L" name="imagealign" <?php if($GLOBALS["fsImageAlign"] == "L" || $GLOBALS["fsImageAlign"] == "") echo "checked" ?><?= $GLOBALS["fieldstatus"]; ?>><?= $GLOBALS["tLeft"]; ?><br />
			<input type="radio" value="R" name="imagealign" <?php if($GLOBALS["fsImageAlign"] == "R") echo "checked" ?><?= $GLOBALS["fieldstatus"]; ?>><?= $GLOBALS["tRight"]; ?>
		</td>
	</tr>
	<?php adminsubheader(4,$GLOBALS["thBodyContent"]); ?>
	<tr class="tablecontent">
		<?php adminHTMLAreadisplay("body","BodyText",$GLOBALS["fsBody"],"imagedetails",3); ?>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("DetailImage","imagedetails"); ?>
		<td colspan="3" valign="top" class="content">
			<input type="text" name="imagedetails" size="64" value="<?= $GLOBALS["fsImageDetails"]; ?>" maxlength="255"<?= $GLOBALS["fieldstatus"]; ?>>
			<?php adminimagedisplay('imagedetails',$GLOBALS["fsImageDetails"],$GLOBALS["tShowImage"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("DetailImageAlign",17); ?>
		<td colspan="3" valign="top" class="content">
			<input type="radio" value="L" name="imagedetailsalign" <?php if($GLOBALS["fsImageDetailsAlign"] == "L" || $GLOBALS["fsImageDetailsAlign"] == "") echo "checked" ?><?= $GLOBALS["fieldstatus"]; ?>><?= $GLOBALS["tLeft"]; ?><br />
			<input type="radio" value="R" name="imagedetailsalign" <?php if($GLOBALS["fsImageDetailsAlign"] == "R") echo "checked" ?><?= $GLOBALS["fieldstatus"]; ?>><?= $GLOBALS["tRight"]; ?>
		</td>
	</tr>
	<!-- RSS/zoek part -->	
	<?php adminsubheader(4,$GLOBALS["thRSS"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("RSS","rssvisible"); ?>
		<td valign="top" class="content"><input type="checkbox" name="rssvisible" value="Y" <?php if($GLOBALS["fbRSSVisible"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>></td>
		<?php FieldHeading("Search","searchvisible"); ?>
		<td valign="top" class="content"><input type="checkbox" name="searchvisible" value="Y" <?php if($GLOBALS["fbSearchVisible"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<!-- end of RSS/zoek part -->
	<?php adminsubheader(4,$GLOBALS["thFooter"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("ShowAuthor","authorvisible"); ?>
		<td valign="top" class="content">
			<input type="checkbox" name="authorvisible" value="Y" <?php if($GLOBALS["fbAuthorVisible"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
		</td>
		<?php FieldHeading("ShowUpdate","updatedatevisible"); ?>
		<td valign="top" class="content">
			<input type="checkbox" name="updatedatevisible" value="Y" <?php if($GLOBALS["fbUpdateDateVisible"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<?php if ($EZ_SESSION_VARS["UserGroup"] == 'administrator') { $sFieldStatus = ''; } else { $sFieldStatus = ' DISABLED'; } ?>
	<tr class="tablecontent">
		<?php FieldHeading("Author",25); ?>
		<td valign="top" class="content">
			<select name="AuthorId" size="1"<?= $sFieldStatus; ?>><?php RenderAuthors($GLOBALS["fsAuthorId"]); ?></select>
		</td>
		<?php FieldHeading("PrinterFriendly","printerfriendly"); ?>
		<td valign="top" class="content">
			<input type="checkbox" name="printerfriendly" value="Y" <?php if($GLOBALS["fsPrinterFriendly"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<tr>
		<?php FieldHeading("TellFriend", "TellFriend");?>
		<td valign="top" class="content">
			<input type="checkbox" name="TellFriend" value="Y" <?php if($GLOBALS["fsTellFriend"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
		</td>
		<?php FieldHeading("PDFPrint","PDFprint");?>
		<td valign="top" class="content">
			<input type="checkbox" name="PDFprint" value="Y" <?php if($GLOBALS["fsPDFPrint"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
		</td>
		
	</tr>
	<?php
	if ($GLOBALS["gsLRContentFrame"] != 'N') {
		adminsubheader(4,$GLOBALS["thPosition"]);
		?>
		<tr class="tablecontent">
			<?php FieldHeading("LeftRight",27); ?>
			<td valign="top" colspan="3" class="content">
				<input type="radio" value="L" name="leftright" <?php if($GLOBALS["fsLeftRight"] == "L" || $GLOBALS["fsLeftRight"] == "") echo "checked" ?><?= $GLOBALS["fieldstatus"]; ?>><?= $GLOBALS["tLeft"]; ?><br />
				<input type="radio" value="R" name="leftright" <?php if($GLOBALS["fsLeftRight"] == "R") echo "checked" ?><?= $GLOBALS["fieldstatus"]; ?>><?= $GLOBALS["tRight"]; ?>
			</td>
		</tr><?php
	} else {
		?>
		<input type="hidden" name="leftright" value="L">
		<?php
	}
	if (($GLOBALS["gsAllowRatings"] == 'Y') || ($GLOBALS["gsAllowComments"] == 'Y')) {
		adminsubheader(4,$GLOBALS["thRatings"]);
		?>
		<tr class="tablecontent">
		<?php
		if ($GLOBALS["gsAllowRatings"] == 'Y') {
			FieldHeading("CanRate",'canrate');
			if ($GLOBALS["gsAllowComments"] == 'Y') {
				?><td valign="top" class="content"><?php
			} else {
				?><td valign="top" colspan="3" class="content"><?php
			}
			?><input type="checkbox" name="canrate" value="Y" <?php if($GLOBALS["fbCanRate"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
			</td>
			<?php
		} else {
			?><input type="hidden" name="canrate" value="N"><?php
		}
		if ($GLOBALS["gsAllowComments"] == 'Y') {
			FieldHeading("CanComment",'cancomment');
			if ($GLOBALS["gsAllowRatings"] == 'Y') {
				?><td valign="top" class="content"><?php
			} else {
				?><td valign="top" colspan="3" class="content"><?php
			}
			?><input type="checkbox" name="cancomment" value="Y" <?php if($GLOBALS["fbCanComment"] == 'Y') echo "checked"?><?= $GLOBALS["fieldstatus"]; ?>>
			</td>
			<?php
		} else {
			?><input type="hidden" name="cancomment" value="N"><?php
		}
		?></tr><?php
	} else {
		?>
		<input type="hidden" name="canrate" value="N">
		<input type="hidden" name="cancomment" value="N">
		<?php
	}
	fadminformsavebar(4,'m_content.php');
	if ($GLOBALS["specialedit"] == True) {
		adminhelpmsg(4);
		?><input type="hidden" name="ContentName" value="<?= $_POST["ContentName"]; ?>"><?php
		?><input type="hidden" name="contentid" value="<?= $GLOBALS["fsContentID"]; ?>"><?php
		?><input type="hidden" name="orderid" value="<?= $GLOBALS["fsOrderID"]; ?>"><?php
		?><input type="hidden" name="filtergroupname" value="<?= $_POST["filtergroupname"]; ?>"><?php
	}
	adminformclose();
} // function frmContentsForm()


function AddContent()
{
	global $_POST, $EZ_SESSION_VARS;

	if ($_POST["AuthorId"] == '') { $_POST["AuthorId"] = $_POST["authorid"]; }

	if ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"]) { $scriptsAllowed = 'Y'; } else { $scriptsAllowed = 'N'; }

	$publishisodate = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]));
	$expireisodate  = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["ExpireYear"], $_POST["ExpireMonth"], $_POST["ExpireDay"]));
	$updateisodate  = dbDateTime(sprintf("%04d-%02d-%02d %02d:%02d:%02d", strftime("%Y"), strftime("%m"), strftime("%d"), strftime("%H"), strftime("%M"), strftime("%S")));
	$authorid = lGetAuthorID();

	$sTitle	= trim(dbString($_POST["title"]));
	$sBody	= trim(dbString($_POST["body"]));
	if ($sBody == '<br />') { $sBody = $cBody = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sBody	= str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sBody);
		$sBody	= str_replace($GLOBALS["base_url"],'./',$sBody);
		$sBody	= str_replace('<./','</',$sBody);
		$sBody	= str_replace('../','',$sBody);
		$cBody	= $sBody;
		//	Compile pre-compiled tags
		$cBody	= trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sBody.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', $_POST["leftright"], $scriptsAllowed));
	}
	$sTeaser	= trim(dbString($_POST["teaser"]));
	if ($sTeaser == '<br />') { $sTeaser = $cTeaser = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sTeaser = str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sTeaser);
		$sTeaser = str_replace($GLOBALS["base_url"],'./',$sTeaser); 
		$sTeaser = str_replace('<./','</',$sTeaser);
		$sTeaser = str_replace('../','',$sTeaser);
		$cTeaser = $sTeaser;
		//	Compile pre-compiled tags
		$cTeaser = trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sTeaser.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', $_POST["leftright"], $scriptsAllowed));
	}

	if ($_POST["cat"] == '0') { $_POST["cat"] = ''; }
	if ($_POST["subgroupname"] == '0') { $_POST["subgroupname"] = ''; }
	if ($_POST["ContentName"] != '') {
		if (($_POST["cat"] == '999999999') && ($_POST["orderid"] >= 0)) { $_POST["orderid"] = 0 - $_POST["orderid"]; }
		// Update any foreign language copies of this article as well
		$strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET groupname='".$_POST["cat"]."', subgroupname='".$_POST["subgroupname"]."', publishdate='".$publishisodate."', expiredate='".$expireisodate."', orderid='".$_POST["orderid"]."', updatedate='".$updateisodate."', imagealign='".$_POST["imagealign"]."', image='".$_POST["image"]."', headervisible='".$_POST["headervisible"]."', authorvisible='".$_POST["authorvisible"]."', updatedatevisible='".$_POST["updatedatevisible"]."', headerimage='".$_POST["headerimage"]."', imagedetails='".$_POST["imagedetails"]."', imagedetailsalign='".$_POST["imagedetailsalign"]."', leftright='".$_POST["leftright"]."', contentname='".$_POST["contentname"]."', canrate='".$_POST["canrate"]."', cancomment='".$_POST["cancomment"]."', printerfriendly='".$_POST["printerfriendly"]."', pdfprint='".$_POST["PDFprint"]."', tellfriend='".$_POST["TellFriend"]."', rssvisible='".$_POST["rssvisible"]."', searchvisible='".$_POST["searchvisible"]."', authorid='".$_POST["AuthorId"]."' WHERE contentname='".$_POST["ContentName"]."' AND language<>'".$GLOBALS["gsLanguage"]."'";
		$result = dbExecute($strQuery,true);
		$strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET groupname='".$_POST["cat"]."', subgroupname='".$_POST["subgroupname"]."', title='".$sTitle."', body='".$sBody."', publishdate='".$publishisodate."', expiredate='".$expireisodate."', teaser='".$sTeaser."', orderid='".$_POST["orderid"]."', updatedate='".$updateisodate."', imagealign='".$_POST["imagealign"]."', image='".$_POST["image"]."', headervisible='".$_POST["headervisible"]."', authorvisible='".$_POST["authorvisible"]."', updatedatevisible='".$_POST["updatedatevisible"]."', headerimage='".$_POST["headerimage"]."', imagedetails='".$_POST["imagedetails"]."', imagedetailsalign='".$_POST["imagedetailsalign"]."', leftright='".$_POST["leftright"]."', contentname='".$_POST["contentname"]."', canrate='".$_POST["canrate"]."', cancomment='".$_POST["cancomment"]."', cbody='".$cBody."', cteaser='".$cTeaser."', printerfriendly='".$_POST["printerfriendly"]."', pdfprint='".$_POST["PDFprint"]."', tellfriend='".$_POST["TellFriend"]."', rssvisible='".$_POST["rssvisible"]."', searchvisible='".$_POST["searchvisible"]."', authorid='".$_POST["AuthorId"]."' WHERE contentname='".$_POST["ContentName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
	} else {
		$strQuery = "INSERT INTO ".$GLOBALS["eztbContents"]." VALUES('', '".$_POST["cat"]."', '".$sTitle."', '".$sBody."', '".$publishisodate."', '".$expireisodate."', 1, '".$sTeaser."', '".$_POST["orderid"]."', '".$_POST["AuthorId"]."', '".$updateisodate."', '".$_POST["subgroupname"]."', '".$_POST["imagealign"]."', '".$_POST["image"]."', '".$_POST["headervisible"]."', '".$_POST["authorvisible"]."', '".$_POST["updatedatevisible"]."', '".$_POST["headerimage"]."', '".$_POST["imagedetails"]."', '".$_POST["imagedetailsalign"]."', '".$_POST["leftright"]."', '".$_POST["contentname"]."', '".$GLOBALS["gsLanguage"]."', '".$_POST["canrate"]."', '".$_POST["cancomment"]."', 0, 0, '".$cBody."', '".$cTeaser."', '".$_POST["printerfriendly"]."', '".$_POST["PDFprint"]."', '".$_POST["TellFriend"]."', '".$_POST["rssvisible"]."', '".$_POST["searchvisible"]."')";  
	}
	$result = dbExecute($strQuery,true);
	$dummy = dbInsertValue($GLOBALS["eztbContents"]);

	if ($dummy == 0) { $dummy = $_POST["contentid"]; }

	//	For new articles:
	//		if no name was specified, set a default
	//		if no orderid was specified, set a default
	if ((($_POST["ContentName"] == '') && ($_POST["contentname"] == '')) || ($_POST["orderid"] == '')) {
		if (($_POST["ContentName"] == '') && ($_POST["contentname"] == '')) {
			$contentname = $dummy;
		} else {
			$contentname = $_POST["contentname"];
		}
		if ($_POST["orderid"] == '') { $orderid = $dummy; }
		else { $orderid = $_POST["orderid"]; }
		if (($_POST["cat"] == '999999999') && ($orderid >= 0)) $orderid = 0 - $orderid;
		$strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET contentname='".$contentname."', orderid='".$orderid."' WHERE contentid='".$dummy."'";
		$result = dbExecute($strQuery,true);
	}
	dbCommit();
} // function AddContent()


function GetGlobalData()
{
	global $EZ_SESSION_VARS, $_GET, $_POST;

	$strQuery="SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$_GET["ContentName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs	= dbFetch($result);

	$GLOBALS["fsContentName"]		= $rs["contentname"];
	$GLOBALS["fsContentID"]			= $rs["contentid"];
	$GLOBALS["fsGroupName"]			= $rs["groupname"];
	$GLOBALS["fsSubGroupName"]		= $rs["subgroupname"];
	$GLOBALS["fsTitle"]				= $rs["title"];
	$GLOBALS["fsOrderID"]			= $rs["orderid"];
	$GLOBALS["fsPublishDate"]		= $rs["publishdate"];
	$GLOBALS["fsExpireDate"]		= $rs["expiredate"];
	$GLOBALS["fsImageAlign"]		= $rs["imagealign"];
	$GLOBALS["fsImage"]				= $rs["image"];
	$GLOBALS["fbHeaderVisible"]		= $rs["headervisible"];
	$GLOBALS["fbAuthorVisible"]		= $rs["authorvisible"];
	$GLOBALS["fbUpdateDateVisible"] = $rs["updatedatevisible"];
	$GLOBALS["fsHeaderImage"]		= $rs["headerimage"];
	$GLOBALS["fsImageDetailsAlign"] = $rs["imagedetailsalign"];
	$GLOBALS["fsImageDetails"]		= $rs["imagedetails"];
	$GLOBALS["fsLeftRight"]			= $rs["leftright"];
	$GLOBALS["fbCanRate"]			= $rs["canrate"];
	$GLOBALS["fbCanComment"]		= $rs["cancomment"];
	$GLOBALS["fsPrinterFriendly"]	        = $rs["printerfriendly"];
	$GLOBALS["fsPDFPrint"]			= $rs["pdfprint"];
	$GLOBALS["fsTellFriend"]		= $rs["tellfriend"];
	$GLOBALS["fsAuthorId"]			= $rs["authorid"];
	$GLOBALS["fbRSSVisible"] 		= $rs["rssvisible"];
	$GLOBALS["fbSearchVisible"] 	= $rs["searchvisible"];

	if ($EZ_SESSION_VARS["WYSIWYG"] == 'Y') {
		$GLOBALS["fsBody"]			= formatWYSIWYG($rs["cbody"]);
		$GLOBALS["fsTeaser"]		= formatWYSIWYG($rs["cteaser"]);
	} else {
		$GLOBALS["fsTeaser"]		= $rs["teaser"];
		$GLOBALS["fsBody"]			= $rs["body"];
	}

	if ($GLOBALS["fsImageAlign"] == "") $GLOBALS["fsImageAlign"] = "L";
	if ($GLOBALS["fsImageDetailsAlign"] == "") $GLOBALS["fsImageDetailsAlign"] = "L";
	if ($GLOBALS["fsLeftRight"] == "") $GLOBALS["fsLeftRight"] = "L";

	$_POST["authorid"] = $rs["authorid"];
	if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}

	$_POST["ContentName"]	= $_GET["ContentName"];
	$_POST["filtergroupname"] = $_GET["filtergroupname"];
	dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
	global $EZ_SESSION_VARS, $_POST;

	$publishisodate = sprintf("%04d-%02d-%02d", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]);
	$expireisodate  = sprintf("%04d-%02d-%02d", $_POST["ExpireYear"], $_POST["ExpireMonth"], $_POST["ExpireDay"]);

	$GLOBALS["fsContentName"]		= $_POST["contentname"];
	$GLOBALS["fsContentID"]			= $_POST["contentid"];
	$GLOBALS["fsGroupName"]			= $_POST["cat"];
	$GLOBALS["fsSubGroupName"]		= $_POST["subgroupname"];
	$GLOBALS["fsTitle"]				= $_POST["title"];
	$GLOBALS["fsTeaser"]			= $_POST["teaser"];
	$GLOBALS["fsBody"]				= $_POST["body"];
	$GLOBALS["fsOrderID"]			= $_POST["orderid"];
	$GLOBALS["fsPublishDate"]		= $publishisodate;
	$GLOBALS["fsExpireDate"]		= $expireisodate;
	$GLOBALS["fsImageAlign"]		= $_POST["imagealign"];
	$GLOBALS["fsImage"]				= $_POST["image"];
	$GLOBALS["fbHeaderVisible"]		= $_POST["headervisible"];
	$GLOBALS["fbAuthorVisible"]		= $_POST["authorvisible"];
	$GLOBALS["fbUpdateDateVisible"] = $_POST["updatedatevisible"];
	$GLOBALS["fsHeaderImage"]		= $_POST["headerimage"];
	$GLOBALS["fsImageDetailsAlign"] = $_POST["imagedetailsalign"];
	$GLOBALS["fsImageDetails"]		= $_POST["imagedetails"];
	$GLOBALS["fsLeftRight"]			= $_POST["leftright"];
	$GLOBALS["fbCanRate"]			= $_POST["canrate"];
	$GLOBALS["fbCanComment"]		= $_POST["cancomment"];
	$GLOBALS["fsPrinterFriendly"]	= $_POST["printerfriendly"];
    $GLOBALS["fsPDFPrint"]          = $_POST["PDFprint"];
    $GLOBALS["fsTellFriend"]		= $_POST["TellFriend"];
	$GLOBALS["fsAuthorId"]			= $_POST["authorid"];
	$GLOBALS["fbRSSVisible"] 		= $_POST["rssvisible"];
	$GLOBALS["fbSearchVisible"] 	= $_POST["searchvisible"];

	if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
} // function GetFormData()


function bCheckForm()
{
	global $_POST;

	$bFormOK = true;
	if (bRecordExists('eztbContents','contentname',$_POST["contentname"],'contentid'))	{ $GLOBALS["strErrors"][] = $GLOBALS["eArticleExists"]; }
	if ($_POST["contentname"] <> urlencode($_POST["contentname"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eInvalidName"]; }

	if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
	return $bFormOK;
} // function bCheckForm()


function lGetAuthorID()
{
	global $EZ_SESSION_VARS;

	$strQuery = "select authorid from ".$GLOBALS["eztbAuthors"]." where login='".$EZ_SESSION_VARS["LoginCookie"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs	= dbFetch($result);
	$authorid = $rs["authorid"];

	dbFreeResult($result);
	return $authorid;
} // function lGetAuthorID()


function RenderGroupsJava()
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND grouplink='' ORDER BY topgroupname,grouporderid";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		?>var <?= $rs["groupname"]; ?>=new Array(["0", "<?= $GLOBALS["tSelect"]; ?>:"]<?php
		RenderSubGroupsJava($rs["groupname"]);
		?>);<?php
	}
	dbFreeResult($result);
} // function RenderGroupsJava()


function RenderSubGroupsJava($GroupName)
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."' AND subgrouplink='' ORDER BY subgrouporderid";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		?>,["<?= $rs["subgroupname"]; ?>","<?= $rs["subgroupdesc"]; ?>"]<?php
	}
	dbFreeResult($result);
} // function RenderSubGroupsJava()


function RenderGroupOptions($SelectedGroupName)
{
	global $EZ_SESSION_VARS;

	if ($GLOBALS["gsShowTopMenu"] == 'Y') {
		if (($GLOBALS["gsSectionSecurity"] == 'Y') && ($GLOBALS["fieldstatus"] != ' disabled') && ($EZ_SESSION_VARS["UserGroup"] != $GLOBALS["gsAdminPrivGroup"])) {
			$sqlQuery = "SELECT g.groupname AS groupname,g.groupdesc AS groupdesc,t.topgroupdesc AS topgroupdesc FROM ".$GLOBALS["eztbGroups"]." g LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE g.language='".$GLOBALS["gsLanguage"]."' AND g.grouplink='' AND g.authorid='".$EZ_SESSION_VARS["UserID"]."' ORDER BY t.topgrouporderid,g.grouporderid";
		} else {
			$sqlQuery = "SELECT g.groupname AS groupname,g.groupdesc AS groupdesc,t.topgroupdesc AS topgroupdesc FROM ".$GLOBALS["eztbGroups"]." g LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE g.language='".$GLOBALS["gsLanguage"]."' AND g.grouplink='' ORDER BY t.topgrouporderid,g.grouporderid";
		}
	} else {
		if (($GLOBALS["gsSectionSecurity"] == 'Y') && ($GLOBALS["fieldstatus"] != ' disabled') && ($EZ_SESSION_VARS["UserGroup"] != $GLOBALS["gsAdminPrivGroup"])) {
			$sqlQuery = "SELECT groupname,groupdesc FROM ".$GLOBALS["eztbGroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND grouplink='' AND authorid='".$EZ_SESSION_VARS["UserID"]."' ORDER BY grouporderid";
		} else {
			$sqlQuery = "SELECT groupname,groupdesc FROM ".$GLOBALS["eztbGroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND grouplink='' ORDER BY grouporderid";
		}
	}
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($SelectedGroupName == $rs["groupname"]) { echo 'selected '; }
		echo 'value="'.$rs["groupname"].'">';
		if ($GLOBALS["gsShowTopMenu"] == 'Y') { echo $rs["topgroupdesc"].' - '; } 
		echo $rs["groupdesc"];
	}
	dbFreeResult($result);
} // function RenderGroupOptions()


function RenderSubGroupOptions($GroupName)
{
	global $EZ_SESSION_VARS;

	if (($GLOBALS["gsSectionSecurity"] == 'Y') && ($GLOBALS["fieldstatus"] != ' disabled') && ($EZ_SESSION_VARS["UserGroup"] != $GLOBALS["gsAdminPrivGroup"])) {
		$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."' AND subgrouplink='' AND authorid='".$EZ_SESSION_VARS["UserID"]."' ORDER BY subgrouporderid";
	} else {
		$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."' AND subgrouplink='' ORDER BY subgrouporderid";
	}
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		?><option <?php
		if($rs["subgroupname"] == $GLOBALS["fsSubGroupName"]) { echo "selected "; }
		?>value="<?= $rs["subgroupname"]; ?>"><?= $rs["subgroupdesc"];?>
<?php		
	}
	
	dbFreeResult($result);
} // function RenderSubGroupOptions()


function RenderAuthors($AuthorId)
{
	$sqlQuery = "SELECT authorid,authorname FROM ".$GLOBALS["eztbAuthors"]." ORDER BY authorname";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($AuthorId == $rs["authorid"]) { echo 'selected '; }
		echo 'value="'.$rs["authorid"].'">'.$rs["authorname"];
	}
	dbFreeResult($result);
} // function RenderAuthors()


?>

<script language="javascript" type="text/javascript">
	<!-- Begin

	// Category groups
	var Select=new Array(["0","<?= $GLOBALS["tSelect"]; ?>:"]);
	<?php RenderGroupsJava(); ?>

	// setCats: sets the category dropdown options based on the selected
	// parent category option
	function setCats(parent) {
		if (parent == "0") { parent= "Select";
		} else { if (parent == "999999999") { parent= "Select";
		} else { parent=parent.replace(/\W/g,"_"); } }
		var cats=window.document.MaintForm.subgroupname;
		// destroy previous category options
		while (cats.options.length>0) {
		cats.options[0]=null;
		}
		// get new category options
		var newCats=eval(parent);
		// now build new category options dropdown
		for (var index=0;index<newCats.length;index++) {
		addOption(newCats[index],cats);
		}
		cats.selectedIndex = 0;
	}

	// addOption: adds a new option to the categories dropdown
	function addOption (category,cats) {
		// create a new option and set its attributes
		var newOption=new Option();
		newOption.value=category[0];
		newOption.text=category[1];
		// tag the option onto the list
		cats.options[cats.options.length]=newOption;
	}
	//  End -->
</script>


<?php include($GLOBALS["rootdp"]."include/javafuncs.php"); ?>
