<?php

/***************************************************************************

 comments.php
 -------------
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include ($GLOBALS["rootdp"]."include/settings.php");
include ($GLOBALS["rootdp"]."include/functions.php");
include ($GLOBALS["rootdp"]."include/content.php");
includeLanguageFiles('admin','main');


$GLOBALS["RatingName"] = GetRatingName();


if ($GLOBALS["gsDirection"] == 'rtl') {
	$GLOBALS["iFirst"] = lsimagehtmltag($GLOBALS["icon_home"],'last_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0);
	$GLOBALS["iPrev"]  = lsimagehtmltag($GLOBALS["icon_home"],'next_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0);
	$GLOBALS["iNext"]  = lsimagehtmltag($GLOBALS["icon_home"],'prev_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0);
	$GLOBALS["iLast"]  = lsimagehtmltag($GLOBALS["icon_home"],'first_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0);
} else {
	$GLOBALS["iFirst"] = lsimagehtmltag($GLOBALS["icon_home"],'first_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0);
	$GLOBALS["iPrev"]  = lsimagehtmltag($GLOBALS["icon_home"],'prev_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0);
	$GLOBALS["iNext"]  = lsimagehtmltag($GLOBALS["icon_home"],'next_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0);
	$GLOBALS["iLast"]  = lsimagehtmltag($GLOBALS["icon_home"],'last_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0);
}


if ($_POST["submitted"] == "yes") {
	// User has submitted the data
	$_GET["article"] = $_POST["article"];
	AddComments($_GET["article"]);
}
frmCommentsForm($_GET["article"]);


function frmCommentsForm($article)
{
	global $_SERVER;

	HTMLHeader('comments');
	StyleSheet();
	?>
	</head>
	<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback">
	<?php

	//  Display the header text for this article
	if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname ='".$article."' AND language='".$GLOBALS["gsLanguage"]."'";
	} else {
		$lOrder = '';
		if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname ='".$article."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY language".$lOrder;
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$rsContent = dbFetch($result);
	ShowContentHeader($rsContent);
	dbFreeResult($result);
	?>
	</table>
	<?php

	// Display any comments made by this user, so that they can be edited
	if ($GLOBALS["RatingName"] != '') {
		$strQuery = "SELECT comments FROM ".$GLOBALS["eztbRatings"]." WHERE contentname ='".$article."' AND comments != '' AND authorid = '".$GLOBALS["RatingName"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rsComments = dbFetch($result);
		$comments = $rsComments["comments"];
		?>
		<table border="0" width="100%" cellspacing="1" cellpadding="3" class="teaserheadercontent">
			<form name="CommentForm" action="<?php echo $_SERVER["PHP_SELF"]; if ($_SERVER["QUERY_STRING"] != '') { echo '?'.$_SERVER["QUERY_STRING"]; } ?>" method="POST" enctype="multipart/form-data">
			<tr><td class="teaserheader"><?php echo $GLOBALS["tYourComments"]; ?></td></tr>
			<tr><td class="tablecontent" valign="top">
					<table border="0" cellspacing="1" cellpadding="3">
						<tr><td valign="top">
								<textarea rows="4" name="comments" cols="60"><?php echo htmlspecialchars($comments); ?></textarea>
							</td>
							<td valign="bottom">
								<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
								<input type="hidden" name="article" value="<?php echo $article; ?>">
								<input type="hidden" name="submitted" value="yes">
								<input type="submit" value="<?php echo $GLOBALS["tSave"]; ?>" name="submit"><br /><br />
								<input type="reset" value="<?php echo $GLOBALS["tReset"]; ?>" name="reset">
						</td></tr>
					</table>
			</td></tr>
			</form>
		</table>
		<?php
		dbFreeResult($result);
	}

	$strQuery = "SELECT ratingid FROM ".$GLOBALS["eztbRatings"]." WHERE contentname ='".$article."' AND comments != '' AND authorid != '".$GLOBALS["RatingName"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	$nCurrentPage = 0;
	if ($_GET["page"] != "") {
		$nCurrentPage = $_GET["page"];
	}
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	?>
	<br />
	<table border="0" width="100%" cellspacing="1" cellpadding="3" class="teaserheadercontent">
	<tr><td class="teaserheader"><?php echo $GLOBALS["tComments"]; ?></td></tr>
	<?php

	PagingHdFt($nCurrentPage,$nPages);

	$strQuery = "SELECT r.comments as comments,r.authorid as authorid,a.authorname AS authorname FROM ".$GLOBALS["eztbRatings"]." r LEFT JOIN ".$GLOBALS["eztbAuthors"]." a ON a.login=r.authorid WHERE r.contentname ='".$article."' AND r.comments != '' AND r.authorid != '".$GLOBALS["RatingName"]."' ORDER BY r.ratingid DESC";
	$result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
	while ($rsComments = dbFetch($result)) {
		?><tr><td class="tablecontent" valign="top"><?php echo $rsComments["comments"];
		if (substr($rsComments["authorid"],0,1) != '^') {
			?><p align="<?php echo $GLOBALS["right"]; ?>"><?php echo $rsComments["authorname"]; ?></p></td></tr><?php
		}
	}
	dbFreeResult($result);

	PagingHdFt($nCurrentPage,$nPages);
	?>
	</table>
	</body>
	</html>
	<?php
} // function frmCommentsForm()


function AddComments($article)
{
	global $_POST;

	// Don't bother adding anything if there's nothing to add
	if ($_POST["comments"] != '') {
		$sComments = dbString($_POST["comments"]);

		$strQuery = "SELECT ratingid FROM ".$GLOBALS["eztbRatings"]." WHERE contentname ='".$article."' AND authorid = '".$GLOBALS["RatingName"]."'";
		$cresult = dbRetrieve($strQuery,true,0,0);
		if (dbRowsReturned($cresult) > 0) {
			$rsCommentRef = dbFetch($cresult);
			$ratingid = $rsCommentRef["ratingid"];
			$strQuery = "UPDATE ".$GLOBALS["eztbRatings"]." SET comments='".$sComments."' WHERE ratingid='".$ratingid."'";
		} else {
			$strQuery = "INSERT INTO ".$GLOBALS["eztbRatings"]."(authorid,contentname,comments) VALUES('".$GLOBALS["RatingName"]."', '".$article."', '".$sComments."')";
		}
		dbFreeResult($cresult);
		$result = dbExecute($strQuery,true);
		dbCommit();
	}
} // function AddComments()


function PagingHdFt($nCurrentPage,$nPages)
{
	global $_GET;

	if ($nPages > 1) {
		$iref = BuildLink('comments.php');
		$iref .= '&article='.$_GET["article"].'&';
		?>
		<tr class="topmenu">
			<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
				<a href="<?php echo $iref; ?>page=0" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php echo $GLOBALS["iFirst"]; ?></a> <?php
				if ($nCurrentPage != 0) {
					?><a href="<?php echo $iref; ?>page=<?php echo $nCurrentPage - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php echo $GLOBALS["iPrev"]; ?></a><?php
				} else { echo $GLOBALS["iPrev"]; }
				$nCPage = $nCurrentPage + 1;
				echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
				if ($nCurrentPage + 1 != $nPages) {
					?><a href="<?php echo $iref; ?>page=<?php echo $nCurrentPage + 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>><?php echo $GLOBALS["iNext"]; ?></a><?php
				} else { echo $GLOBALS["iNext"]; }
				?>
				<a href="<?php echo $iref; ?>page=<?php echo $nPages - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>><?php echo $GLOBALS["iLast"]; ?></a>
			</td>
		</tr>
		<?php
	}
} // function PagingHdFt()



function GetRatingName()
{
	global $_COOKIE, $EZ_SESSION_VARS;

	$RatingName = $GLOBALS["ezSID"];
	if ($_COOKIE["RatingName"] != '') {
		$RatingName = $_COOKIE["RatingName"];
		if (substr($RatingName,0,1) != '^') {
			// Somebody is trying to fiddle a cookie
			$RatingName = '';
		}
	} elseif ($_COOKIE["UserIdCookie"] != '') {
		$strQuery = "SELECT login from ".$GLOBALS["eztbAuthors"]." WHERE login='".$_COOKIE["UserIdCookie"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rs	  = dbFetch($result);
		if ($rs["login"] == $_COOKIE["UserIdCookie"]) { $RatingName = $rs["login"]; }
		dbFreeResult($result);
	} elseif (($EZ_SESSION_VARS["UserID"] != '') && ($EZ_SESSION_VARS["UserID"] != 0)) {
		$strQuery = "SELECT authorid,login from ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$EZ_SESSION_VARS["UserID"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rs	  = dbFetch($result);
		if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) { $RatingName = $rs["login"]; }
		dbFreeResult($result);
	}
	if ($RatingName == $GLOBALS["ezSID"]) {
		$RatingName = substr_replace($RatingName,'^',0,1);
		// Timer values for cookies
		// 15 Minutes	= 900;
		// 1 Hour	= 3600;
		// 2 Hours	= 7200;
		// 6 Hours	= 21600;
		// 1 Day	= 86400;
		// 1 Year	= 31622400;
		setcookie ("RatingName", $RatingName, time()+316224000);
	}
	return $RatingName;
} // function GetRatingName()

?>
