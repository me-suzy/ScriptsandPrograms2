<?php

/***************************************************************************

 m_tcontentform.php
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

include_once ("rootdatapath.php");
include_once ("compile.php");

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = $GLOBALS["cantranslate"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'content';
$validaccess = VerifyAdminLogin3("ContentName");

includeLanguageFiles('admin','content');


//	Set list of textareas in an array for HTMLArea integration
$GLOBALS["textareas"]	= array('baseteaser','teaser','basebody','body');
$GLOBALS["base_url"] = SiteBaseUrl($EZ_SESSION_VARS["Site"]);



$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');

// If we've been passed the request from the content list, then we
//    read content data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["ContentName"] != '') {
   $_POST["ContentName"] = $_GET["ContentName"];
   $_POST["LanguageCode"] = $_GET["LanguageCode"];
   $_POST["page"] = $_GET["page"];
   $_POST["filtergroupname"] = $_GET["filtergroupname"];
   GetGlobalData();
} else {
   GetFormData();
}


$strQuery = "SELECT languagename,charset FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$GLOBALS["gsDefault_language"]."'";
$result = dbRetrieve($strQuery,true,0,0);
if ($rs = dbFetch($result)) {
   $baselanguagename = $rs["languagename"];
   $basecharset = $rs["charset"];
}
dbFreeResult($result);

$strQuery = "SELECT languagename,charset FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$_POST["LanguageCode"]."'";
$result = dbRetrieve($strQuery,true,0,0);
if ($rs = dbFetch($result)) {
   $languagename = $rs["languagename"];
   $charset = $rs["charset"];
}
dbFreeResult($result);


$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   AddContent($basecharset,$charset);
   Header("Location: ".BuildLink('m_content.php')."&page=".$_POST["page"]."&filtergroupname=".$_POST["filtergroupname"]."&filterlangname=".$_POST["LanguageCode"]);
}
frmContentsForm($baselanguagename,$basecharset,$languagename,$charset);


function frmContentsForm($baselanguagename,$basecharset,$languagename,$charset)
{
   global $_POST, $EZ_SESSION_VARS;

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (function_exists('mb_convert_encoding')) { adminformheader('UTF-8'); }
      else {
         $convertcharsets = false;
         adminformheader($charset);
      }
   } else { adminformheader(); }

   adminformopen('title');
   adminformtitle(4,charsetText($GLOBALS["tFormTitle2"],$convertcharsets,$GLOBALS["gsCharset"]).' - '.charsetText($languagename,$convertcharsets,$GLOBALS["gsCharset"]));
   echo $GLOBALS["strErrors"];
   adminsubheader(4,charsetText($GLOBALS["thHeader"],$convertcharsets,$GLOBALS["gsCharset"]));
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("Header","title"); ?>
       <td valign="top" colspan="3" class="content">
           <table border="0" cellpadding="1" cellspacing="0">
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="basetitle" size="72" value="<?php echo charsetText($GLOBALS["bsTitle"],$convertcharsets,$basecharset); ?>" maxlength="100" readonly>
                   </td>
               </tr>
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="title" size="72" value="<?php echo htmlspecialchars(charsetText($GLOBALS["fsTitle"],$convertcharsets,$charset)) ?>" maxlength=255<?php echo $GLOBALS["fieldstatus"]; ?>>
                   </td>
               </tr>
           </table>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("HeaderImage","headerimage"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="headerimage" size="80" value="<?php echo $GLOBALS["fsHeaderImage"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('headerimage',$GLOBALS["fsHeaderImage"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,charsetText($GLOBALS["thTeaser"],$convertcharsets,$GLOBALS["gsCharset"])); ?>
   <tr class="tablecontent">
       <?php FieldHeading("Teaser","teaser"); ?>
       <td align="<?php echo $GLOBALS["left"]; ?>" valign="top" colspan="3" class="content">
			<b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b><br />
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				?>
				<textarea id="baseteaser" name="baseteaser" style="width:540; height:180"><?php echo charsetText($GLOBALS["bsTeaser"],$convertcharsets,$basecharset); ?></textarea>
				<?php
			} else {
				?>
				<textarea rows="3" name="baseteaser" cols="62" readonly><?php echo htmlspecialchars(charsetText($GLOBALS["bsTeaser"],$convertcharsets,$basecharset)); ?></textarea>
				<?php
			}
			?><br />
			<b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b><br />
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				?>
				<textarea id="teaser" name="teaser" style="width:540; height:180"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo charsetText($GLOBALS["fsTeaser"],$convertcharsets,$basecharset); ?></textarea>
				<?php
			} else {
				?>
				<textarea rows="4" name="teaser" cols="62"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars(charsetText($GLOBALS["fsTeaser"],$convertcharsets,$charset)); ?></textarea>
				<?php
			}
			?>
       </td>
   </tr>
<?php if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {} else {?>
   <tr class="tablecontent">
       <?php FieldHeading("TeaserImage","image"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="image" size="80" value="<?php echo $GLOBALS["fsImage"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('image',$GLOBALS["fsImage"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php  } adminsubheader(4,charsetText($GLOBALS["thBodyContent"],$convertcharsets,$GLOBALS["gsCharset"])); ?>
   <tr class="tablecontent">
       <?php FieldHeading("BodyText","body"); ?>
       <td colspan="3" valign="top" class="content" align="<?php echo $GLOBALS["left"]; ?>" valign="top">
			<b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b><br />
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				?>
				<textarea id="basebody" name="basebody" style="width:540; height:180"><?php echo charsetText($GLOBALS["bsBody"],$convertcharsets,$basecharset); ?></textarea>
				<?php
			} else {
				?>
				<textarea rows="3" name="basebody" cols="62" readonly><?php echo htmlspecialchars(charsetText($GLOBALS["bsBody"],$convertcharsets,$basecharset)); ?></textarea>
				<?php
			}
			?><br />
			<b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b><br />
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				?>
				<textarea id="body" name="body" style="width:540; height:180"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo charsetText($GLOBALS["fsBody"],$convertcharsets,$basecharset); ?></textarea>
				<?php
			} else {
				?>
				<textarea rows="4" name="body" cols="62"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars(charsetText($GLOBALS["fsBody"],$convertcharsets,$charset)); ?></textarea>
				<?php
			}
			?>
       </td>
   </tr>
<?php if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {} else {?>
   <tr class="tablecontent">
       <?php FieldHeading("DetailImage","imagedetails"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="imagedetails" size="80" value="<?php echo $GLOBALS["fsImageDetails"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('imagedetails',$GLOBALS["fsImageDetails"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php }
   fadminformsavebar(4,'m_content.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="contentid" value="<?php echo $GLOBALS["fsContentID"]; ?>"><?php
      ?><input type="hidden" name="ContentName" value="<?php echo $_POST["ContentName"]; ?>"><?php
      ?><input type="hidden" name="LanguageCode" value="<?php echo $_POST["LanguageCode"]; ?>"><?php
      ?><input type="hidden" name="orderid" value="<?php echo $GLOBALS["fsOrderID"]; ?>"><?php
      ?><input type="hidden" name="filtergroupname" value="<?php echo $_POST["filtergroupname"]; ?>"><?php

      ?><input type="hidden" name="groupname" value="<?php echo $GLOBALS["fsGroupName"]; ?>"><?php
      ?><input type="hidden" name="subgroupname" value="<?php echo $GLOBALS["fsSubGroupName"]; ?>"><?php
      ?><input type="hidden" name="publishdate" value="<?php echo $GLOBALS["fsPublishDate"]; ?>"><?php
      ?><input type="hidden" name="expiredate" value="<?php echo $GLOBALS["fsExpireDate"]; ?>"><?php
      ?><input type="hidden" name="updatedate" value="<?php echo $GLOBALS["fsUpdateDate"]; ?>"><?php
      ?><input type="hidden" name="headervisible" value="<?php echo $GLOBALS["fbHeaderVisible"]; ?>"><?php
      ?><input type="hidden" name="imagealign" value="<?php echo $GLOBALS["fsImageAlign"]; ?>"><?php
      ?><input type="hidden" name="imagedetailsalign" value="<?php echo $GLOBALS["fsImageDetailsAlign"]; ?>"><?php
      ?><input type="hidden" name="authorvisible" value="<?php echo $GLOBALS["fbAuthorVisible"]; ?>"><?php
      ?><input type="hidden" name="updatedatevisible" value="<?php echo $GLOBALS["fbUpdateDateVisible"]; ?>"><?php
      ?><input type="hidden" name="leftright" value="<?php echo $GLOBALS["fsLeftRight"]; ?>"><?php
      ?><input type="hidden" name="canrate" value="<?php echo $GLOBALS["fbCanRate"]; ?>"><?php
      ?><input type="hidden" name="ratingtotal" value="<?php echo $GLOBALS["fbRatingTotal"]; ?>"><?php
      ?><input type="hidden" name="ratingvotes" value="<?php echo $GLOBALS["fbRatingVotes"]; ?>"><?php
      ?><input type="hidden" name="cancomment" value="<?php echo $GLOBALS["fbCanComment"]; ?>"><?php
      ?><input type="hidden" name="printerfriendly" value="<?php echo $GLOBALS["fbPrinterFriendly"]; ?>"><?php

      ?><input type="hidden" name="edittype" value="<?php echo $GLOBALS["fsEditType"]; ?>"><?php
   }
   adminformclose();
} // function frmContentsForm()


function AddContent($basecharset,$charset)
{
   global $_POST, $EZ_SESSION_VARS;

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (!(function_exists('mb_convert_encoding'))) {
         $convertcharsets = false;
      }
   }

	if ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"]) { $scriptsAllowed = 'Y'; } else { $scriptsAllowed = 'N'; }

   $publishisodate = $_POST["publishdate"];
   $expireisodate  = $_POST["expiredate"];
   $updateisodate  = $_POST["updatedate"];

	$sTitle  = dbString(UTF8Text($_POST["title"],$convertcharsets,$charset));
	$sBody = dbString(UTF8Text($_POST["body"],$convertcharsets,$charset));
	if ($sBody == '<br />') { $sBody = $cBody = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sBody	= str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sBody);
		$sBody	= str_replace($GLOBALS["base_url"],'./',$sBody);
		$sBody	= str_replace('<./','</',$sBody);
		//	Compile pre-compiled tags
		$cBody	= trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sBody.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', $_POST["leftright"], $scriptsAllowed));
	}
	$sTeaser = dbString(UTF8Text($_POST["teaser"],$convertcharsets,$charset));
	if ($sTeaser == '<br />') { $sTeaser = $cTeaser = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sTeaser	= str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sTeaser);
		$sTeaser	= str_replace($GLOBALS["base_url"],'./',$sTeaser);
		$sTeaser	= str_replace('<./','</',$sTeaser);
		//	Compile pre-compiled tags
		$cTeaser	= trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sTeaser.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', $_POST["leftright"], $scriptsAllowed));
	}


   if ($_POST["edittype"] != 'add') {
      $strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET title='".$sTitle."', body='".$sBody."', teaser='".$sTeaser."', image='".$_POST["image"]."', headerimage='".$_POST["headerimage"]."', imagedetails='".$_POST["imagedetails"]."', cbody='".$cBody."', cteaser='".$cTeaser."' WHERE contentname='".$_POST["ContentName"]."' AND language='".$_POST["LanguageCode"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbContents"]." VALUES('', '".$_POST["groupname"]."', '".$sTitle."', '".$sBody."','".$publishisodate."','".$expireisodate."',1, '".$sTeaser."', '".$_POST["orderid"]."', '".$EZ_SESSION_VARS["UserID"]."', '".$updateisodate."', '".$_POST["subgroupname"]."', '".$_POST["imagealign"]."', '".$_POST["image"]."', '".$_POST["headervisible"]."', '".$_POST["authorvisible"]."', '".$_POST["updatedatevisible"]."', '".$_POST["headerimage"]."', '".$_POST["imagedetails"]."', '".$_POST["imagedetailsalign"]."', '".$_POST["leftright"]."', '".$_POST["ContentName"]."', '".$_POST["LanguageCode"]."', '".$_POST["canrate"]."', '".$_POST["cancomment"]."', ".$_POST["ratingtotal"].", ".$_POST["ratingvotes"].", '".$cBody."', '".$cTeaser."', '".$_POST["printerfriendly"]."', '".$_POST["rssvisible"]."', '".$_POST["searchvisible"]."')";
   }
   $result = dbExecute($strQuery,true);

   dbCommit();
} // function AddContent()


function GetGlobalData()
{
	global $EZ_SESSION_VARS, $_GET, $_POST;

	$strQuery="SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$_GET["ContentName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs     = dbFetch($result);

	$GLOBALS["bsTitle"]				= $rs["title"];
	$GLOBALS["bsHeaderImage"]		= $rs["headerimage"];
	$GLOBALS["bsImage"]				= $rs["image"];
	$GLOBALS["bsImageDetails"]		= $rs["imagedetails"];
	$GLOBALS["fsContentName"]		= $rs["contentname"];
	$GLOBALS["fsContentID"]			= $rs["contentid"];
	$GLOBALS["fsGroupName"]			= $rs["groupname"];
	$GLOBALS["fsSubGroupName"]		= $rs["subgroupname"];
	$GLOBALS["fsTitle"]				= $rs["title"];
	$GLOBALS["fsOrderID"]			= $rs["orderid"];
	$GLOBALS["fsPublishDate"]		= $rs["publishdate"];
	$GLOBALS["fsExpireDate"]		= $rs["expiredate"];
	$GLOBALS["fsUpdateDate"]		= $rs["updatedate"];
	$GLOBALS["fsImageAlign"]		= $rs["imagealign"];
	$GLOBALS["fsImage"]				= $rs["image"];
	$GLOBALS["fbHeaderVisible"]		= $rs["headervisible"];
	$GLOBALS["fbAuthorVisible"]		= $rs["authorvisible"];
	$GLOBALS["fbUpdateDateVisible"]	= $rs["updatedatevisible"];
	$GLOBALS["fsHeaderImage"]		= $rs["headerimage"];
	$GLOBALS["fsImageDetailsAlign"]	= $rs["imagedetailsalign"];
	$GLOBALS["fsImageDetails"]		= $rs["imagedetails"];
	$GLOBALS["fsLeftRight"]			= $rs["leftright"];
	$GLOBALS["fbCanRate"]			= $rs["canrate"];
	$GLOBALS["fbRatingTotal"]		= $rs["ratingtotal"];
	$GLOBALS["fbRatingVotes"]		= $rs["ratingvotes"];
	$GLOBALS["fbCanComment"]		= $rs["cancomment"];
	$GLOBALS["fbPrinterFriendly"]	= $rs["printerfriendly"];
	if ($EZ_SESSION_VARS["WYSIWYG"] == 'Y') {
		$GLOBALS["bsBody"]			= formatWYSIWYG($rs["cbody"]);
		$GLOBALS["bsTeaser"]		= formatWYSIWYG($rs["cteaser"]);
		$GLOBALS["fsBody"]			= formatWYSIWYG($rs["cbody"]);
		$GLOBALS["fsTeaser"]		= formatWYSIWYG($rs["cteaser"]);
	} else {
		$GLOBALS["bsTeaser"]		= $rs["teaser"];
		$GLOBALS["bsBody"]			= $rs["body"];
		$GLOBALS["fsTeaser"]		= $rs["teaser"];
		$GLOBALS["fsBody"]			= $rs["body"];
	}

	if ($GLOBALS["fsImageAlign"] == "") $GLOBALS["fsImageAlign"] = "L";
	if ($GLOBALS["fsImageDetailsAlign"] == "") $GLOBALS["fsImageDetailsAlign"] = "L";
	if ($GLOBALS["fsLeftRight"] == "") $GLOBALS["fsLeftRight"] = "L";

	$strQuery="SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$_GET["ContentName"]."' AND language='".$_GET["LanguageCode"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	if (dbRowsReturned($result) != 0) {
		$rs = dbFetch($result);

		$GLOBALS["fsContentName"]		= $rs["contentname"];
		$GLOBALS["fsContentID"]			= $rs["contentid"];
		$GLOBALS["fsGroupName"]			= $rs["groupname"];
		$GLOBALS["fsSubGroupName"]		= $rs["subgroupname"];
		$GLOBALS["fsTitle"]				= $rs["title"];
		$GLOBALS["fsOrderID"]			= $rs["orderid"];
		$GLOBALS["fsPublishDate"]		= $rs["publishdate"];
		$GLOBALS["fsExpireDate"]		= $rs["expiredate"];
		$GLOBALS["fsUpdateDate"]		= $rs["updatedate"];
		$GLOBALS["fsImageAlign"]		= $rs["imagealign"];
		$GLOBALS["fsImage"]				= $rs["image"];
		$GLOBALS["fbHeaderVisible"]		= $rs["headervisible"];
		$GLOBALS["fbAuthorVisible"]		= $rs["authorvisible"];
		$GLOBALS["fbUpdateDateVisible"]	= $rs["updatedatevisible"];
		$GLOBALS["fsHeaderImage"]		= $rs["headerimage"];
		$GLOBALS["fsImageDetailsAlign"]	= $rs["imagedetailsalign"];
		$GLOBALS["fsImageDetails"]		= $rs["imagedetails"];
		$GLOBALS["fsLeftRight"]			= $rs["leftright"];
		$GLOBALS["fbCanRate"]			= $rs["canrate"];
		$GLOBALS["fbRatingTotal"]		= $rs["ratingtotal"];
		$GLOBALS["fbRatingVotes"]		= $rs["ratingvotes"];
		$GLOBALS["fbCanComment"]		= $rs["cancomment"];
		$GLOBALS["fbPrinterFriendly"]	= $rs["printerfriendly"];
		$GLOBALS["fsEditType"]			= 'update';
		if ($EZ_SESSION_VARS["WYSIWYG"] == 'Y') {
			$GLOBALS["fsBody"]			= formatWYSIWYG($rs["cbody"]);
			$GLOBALS["fsTeaser"]		= formatWYSIWYG($rs["cteaser"]);
		} else {
			$GLOBALS["fsTeaser"]		= $rs["teaser"];
			$GLOBALS["fsBody"]			= $rs["body"];
		}
	} else { $GLOBALS["fsEditType"]= 'add'; }

	$_POST["authorid"] = $rs["authorid"];
	if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}

	$_POST["ContentName"]  = $_GET["ContentName"];
	$_POST["LanguageCode"] = $_GET["LanguageCode"];
} // function GetGlobalData()


function GetFormData()
{
	global $EZ_SESSION_VARS, $_POST;

	$publishisodate = sprintf("%04d-%02d-%02d", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]);
	$expireisodate = sprintf("%04d-%02d-%02d", $_POST["ExpireYear"], $_POST["ExpireMonth"], $_POST["ExpireDay"]);

	$GLOBALS["fsContentName"]		= $_POST["contentname"];
	$GLOBALS["fsContentID"]			= $_POST["contentid"];
	$GLOBALS["fsGroupName"]			= $_POST["groupname"];
	$GLOBALS["fsSubGroupName"]		= $_POST["subgroupname"];
	$GLOBALS["fsTitle"]				= $_POST["title"];
	$GLOBALS["fsTeaser"]			= $_POST["teaser"];
	$GLOBALS["fsBody"]				= $_POST["desc"];
	$GLOBALS["fsOrderID"]			= $_POST["orderid"];
	$GLOBALS["fsPublishDate"]		= $_POST["publishdate"];
	$GLOBALS["fsExpireDate"]		= $_POST["expiredate"];
	$GLOBALS["fsImageAlign"]		= $_POST["imagealign"];
	$GLOBALS["fsImage"]				= $_POST["image"];
	$GLOBALS["fbHeaderVisible"]		= $_POST["headervisible"];
	$GLOBALS["fbAuthorVisible"]		= $_POST["authorvisible"];
	$GLOBALS["fbUpdateDateVisible"]	= $_POST["updatedatevisible"];
	$GLOBALS["fsHeaderImage"]		= $_POST["headerimage"];
	$GLOBALS["fsImageDetailsAlign"]	= $_POST["imagedetailsalign"];
	$GLOBALS["fsImageDetails"]		= $_POST["imagedetails"];
	$GLOBALS["fsLeftRight"]			= $_POST["leftright"];
	$GLOBALS["fbCanRate"]			= $_POST["canrate"];
	$GLOBALS["fbRatingTotal"]		= $_POST["ratingtotal"];
	$GLOBALS["fbRatingVotes"]		= $_POST["ratingvotes"];
	$GLOBALS["fbCanComment"]		= $_POST["cancomment"];
	$GLOBALS["fbPrinterFriendly"]	= $_POST["printerfriendly"];

	if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
} // function GetFormData()


function lGetAuthorID()
{
	global $EZ_SESSION_VARS;

	$strQuery = "select * from ".$GLOBALS["eztbAuthors"]." where login='".$EZ_SESSION_VARS["LoginCookie"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs     = dbFetch($result);
	$authorid = $rs["authorid"];

	dbFreeResult($result);
	return $authorid;
} // function lGetAuthorID()


$GLOBALS["eztbTable"] = $GLOBALS["eztbContents"];
$GLOBALS["eztbKeyField"] = 'contentname';
$GLOBALS["keyfieldval"] = $_POST["ContentName"];
include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
