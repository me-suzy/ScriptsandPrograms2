<?php

/***************************************************************************

 rateit.php
 -----------
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
include ($GLOBALS["rootdp"]."include/banners.php");
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

$GLOBALS["ScreenWidthMultiplier"] = 3.5;


if ($_POST["submitted"] == "yes") {
	// User has submitted the data
	$_GET["article"] = $_POST["article"];
	AddRating($_GET["article"]);
}
frmRatingForm($_GET["article"]);


function frmRatingForm($article)
{
	global $_SERVER;

	HTMLHeader('ratings');
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

	// Display any rating made by this user, so that it can be changed
	if ($GLOBALS["RatingName"] != '') {
		$strQuery = "SELECT rating FROM ".$GLOBALS["eztbRatings"]." WHERE contentname ='".$article."' AND rating != 99 AND authorid='".$GLOBALS["RatingName"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rsRatings = dbFetch($result);
		$rating = $rsRatings["rating"];
		?>
		<table border="0" width="100%" cellspacing="1" cellpadding="3" class="teaserheadercontent">
		<form name="CommentForm" action="<?php echo $_SERVER["PHP_SELF"]; if ($_SERVER["QUERY_STRING"] != '') { echo '?'.$_SERVER["QUERY_STRING"]; } ?>" method="POST" enctype="multipart/form-data">
		<tr><td class="teaserheader"><?php echo $GLOBALS["tYourRating"]; ?></td></tr>
		<tr><td class="tablecontent" valign="top">
		<table border="0" cellspacing="1" cellpadding="3">
			<tr><td valign="top">
					<select size="1" name="rating">
					<?php BuildRatings($GLOBALS["gsRatingMin"],$GLOBALS["gsRatingMax"],$rating); ?>&nbsp;&nbsp<?php echo $GLOBALS["gsRatingMin"]; ?> = <?php echo $GLOBALS["tRatingBad"]; ?>&nbsp;&nbsp<?php echo $GLOBALS["gsRatingMax"]; ?> = <?php echo $GLOBALS["tRatingGood"]; ?>
					</select>
				</td><td valign="bottom">
						<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
						<input type="hidden" name="article" value="<?php echo $article; ?>">
						<input type="hidden" name="oldrating" value="<?php echo $rating; ?>">
						<input type="hidden" name="submitted" value="yes">
						<input type="submit" value="<?php echo $GLOBALS["tSave"]; ?>" name="submit">&nbsp;&nbsp;
						<input type="reset" value="<?php echo $GLOBALS["tReset"]; ?>" name="reset">
				</td></tr>
		</table>
		</td></tr>
		</form>
		</table>
		<?php
		dbFreeResult($result);
	}


	for ($i=$GLOBALS["gsRatingMin"]; $i<=$GLOBALS["gsRatingMax"]; $i=$i+1) { $ratings[$i+10] = 0; }
	$ratingstotal = 0;
	$ratingsvalue = 0;
	$strQuery = "SELECT rating,count(ratingid) FROM ".$GLOBALS["eztbRatings"]." WHERE contentname ='".$article."' AND rating != 99 GROUP BY rating";
	$result = dbRetrieve($strQuery,true,0,0);
	while ($rsRatings = dbFetch($result)) {
		$r = $rsRatings["rating"];
		$rval = $rsRatings["count(ratingid)"];
		$ratings[$r+10] = $rval;
		$ratingstotal += $rval;
		$ratingsvalue += $r * $rval;
	}
	dbFreeResult($result);

	?>
	<table border="0" width="100%" cellspacing="1" cellpadding="3" class="teaserheadercontent">
	<tr><td class="tablecontent" colspan="4">
	<?php echo $ratingstotal; ?> <?php if ($ratingstotal == 1) { echo $GLOBALS["tRatingMessage1"]; } else { echo $GLOBALS["tRatingMessage2"]; } ?> <?php if ($ratingstotal == 0) { echo '0.00'; } else { echo round($ratingsvalue / $ratingstotal,2); } ?> <?php echo $GLOBALS["tRatingMessage3"]; ?> <?php echo $GLOBALS["gsRatingMin"]; ?> <?php echo $GLOBALS["tRatingMessage4"]; ?> <?php echo $GLOBALS["gsRatingMax"]; ?>
	</td></tr>
	<tr><td class="teaserheader" colspan="4"><?php echo $GLOBALS["tRatings"]; ?></td></tr>
	<tr class="topmenu">
		<td width="10%" valign="bottom" align="center" class="content"><b><?php echo $GLOBALS["tScore"]; ?></b></td>
		<td width="10%" valign="bottom" align="<?php echo $GLOBALS["right"]; ?>" class="content"><b><?php echo $GLOBALS["tVoters"]; ?></b></td>
		<td width="10%" valign="bottom" align="<?php echo $GLOBALS["right"]; ?>" class="content"><b><?php echo $GLOBALS["tVotersPercent"]; ?></b></td>
		<td width="70%" valign="bottom" class="content">&nbsp;</td>
	</tr>
	<?php

	for ($i=$GLOBALS["gsRatingMin"]; $i<=$GLOBALS["gsRatingMax"]; $i=$i+1) {
		?><tr><td class="tablecontent" valign="top" align="center"><?php echo $i; ?></td><td class="tablecontent" valign="top" align="<?php echo $GLOBALS["right"]; ?>"><?php echo $ratings[$i+10]; ?></td><td class="tablecontent" valign="top" align="<?php echo $GLOBALS["right"]; ?>"><?php echo PercentValue($ratings[$i+10],$ratingstotal); ?></td><td class="tablecontent" valign="top"><?php echo GraphValue($ratings[$i+10],$ratingstotal,$i); ?></td></tr><?php
	}

	?>
	</table>
	</body>
	</html>
	<?php
} // function frmRatingForm()


function AddRating($article)
{
	global $_POST;

	//  Test to see if a record already exists for this article/user combination. If so we're updating rather than inserting
	$strQuery = "SELECT ratingid FROM ".$GLOBALS["eztbRatings"]." WHERE contentname ='".$article."' AND authorid = '".$GLOBALS["RatingName"]."'";
	$result = dbRetrieve($strQuery,true,0,0);

	//  Update/Insert into the ratings table
	if (dbRowsReturned($result) > 0) {
		$rsCommentRef = dbFetch($result);
		$ratingid = $rsCommentRef["ratingid"];
		$strQuery = "UPDATE ".$GLOBALS["eztbRatings"]." SET rating='".$_POST["rating"]."' WHERE ratingid=".$ratingid;
	} else {
		$strQuery = "INSERT INTO ".$GLOBALS["eztbRatings"]."(authorid,contentname,rating) VALUES('".$GLOBALS["RatingName"]."', '".$article."', '".$_POST["rating"]."')";
	}
	dbFreeResult($result);
	$result = dbExecute($strQuery,true);

	//  Update the rating figures on the article record itself
	if ($_POST["oldrating"] != '') {
		$strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET ratingtotal=ratingtotal+".$_POST["rating"]."-".$_POST["oldrating"]." WHERE contentname ='".$article."'";
	} else {
		$strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET ratingtotal=ratingtotal+".$_POST["rating"].", ratingvotes=ratingvotes+1 WHERE contentname ='".$article."'";
	}
	$result = dbExecute($strQuery,true);

	dbCommit();
} // function AddRating()


function BuildRatings($minrating, $maxrating, $rating)
{
	if ((intval($rating == 99)) || ($rating == '')) {
		echo '<option selected value="99">'.$GLOBALS["tUnrated"].'</option>';
	}
	for ($i=$minrating; $i<=$maxrating; $i=$i+1) {
		echo "<option";
		if ((intval($rating) == $i) && ($rating != '')) { echo " selected"; }
		echo " value=\"".$i."\">".$i."</option>".chr(13);
	}
} // function BuildRatings()


function PercentValue($rating,$ratingtotal)
{
	$percentvalue = '';
	if ($ratingtotal > 0) {
		$percentvalue = number_format(($rating / $ratingtotal) * 100, "2");
	} else {
		$percentvalue = '0.00';
	}
	$percentvalue .= '%';
	return $percentvalue;
} // function PercentValue()


function GraphValue($rating,$ratingtotal,$colour)
{
	$colourbars = array('blue','pink','yellow','darkgreen','purple','gold','green','brown','orange','aqua','grey','red');

	if ($colour < 0) {
		$colourval = count($colourbars) + ($colour % count($colourbars));
	} else {
		$colourval = ($colour % count($colourbars));
	}
	$graphvalue = '';

	if ($ratingtotal > 0) {
		$percentage = abs(floor(($rating / $ratingtotal) * 100));
		if (($ratingtotal > 0) && ($percentage > 0)) {
			$imagelength = $percentage * $GLOBALS["ScreenWidthMultiplier"];
			$graphvalue='<IMG SRC="'.$GLOBALS["rootdp"].$GLOBALS["icon_home"].'graphbar_'.$colourbars[$colourval].'.gif" HEIGHT="10" WIDTH="'.$imagelength.'">';
		}
	}
	return $graphvalue;
} // function GraphValue()


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
		$rs		= dbFetch($result);
		if ($rs["login"] == $_COOKIE["UserIdCookie"]) { $RatingName = $rs["login"]; }
		dbFreeResult($result);
	} elseif (($EZ_SESSION_VARS["UserID"] != '') && ($EZ_SESSION_VARS["UserID"] != 0)) {
		$strQuery = "SELECT authorid,login from ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$EZ_SESSION_VARS["UserID"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rs		= dbFetch($result);
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
