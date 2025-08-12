<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$sql = "INSERT INTO ".$tableprefix."_settings (settingnr, showproglist, watchlogins, ";
$sql.= "allowemail, urlautoencode, enablespcode, nofreemailer, allowquestions, faqemail, ";
$sql.= "allowusercomments, newcommentnotify, enablefailednotify, loginlimit, timezone, ";
$sql.= "enablehostresolve, ratecomments, usemenubar, admtextareasrows, admtextareascols, ";
$sql.= "enablekbrating, userquestionanswermode, userquestionanswermail, userquestionautopublish, ";
$sql.= "faqengine_hostname, ratingcomment, nosunotify, blockoldbrowser, bbccolorbar, ";
$sql.= "disablehtmlemail, admstorefaqfilters, admhideunassigned, admdelconfirm, zlibavail, ";
$sql.= "msendlimit, subscriptionavail, admedoptions, allsendcompressed, uq_allownoemail, ";
$sql.= "blockleacher, defmailsig, faqlistshortcuts, faqlimitrelated, displayrating, showtimezone, ";
$sql.= "showcurrtime, admdateformat, maxconfirmtime, dosearchlog, logdateformat, uqscmail, extfailedlog, ";
$sql.= "usebwlist, lhide";
$sql.= ") VALUES (";
$sql.= "1, $showproglist, $watchlogins, $allowemail, $urlautoencode, $enablespcode, $nofreemailer, ";
$sql.= "$allowquestions, '$input_faqemail', $allowusercomments, $newcommentnotify, $enablefailednotify, ";
$sql.= "$new_loginlimit, $timezone, $enablehostresolve, $ratecomments, $usemenubar, ";
$sql.= "$new_admtextareasrows, $new_admtextareascols, $enablekbrating, $uqanswermode, ";
$sql.= "$userquestionanswermail, $userquestionautopublish, '$faqengine_hostname', $ratingcomment, ";
$sql.= "$nosunotify, $blockoldbrowser, $bbccolorbar, $disablehtmlemail, $admstorefaqfilters, ";
$sql.= "$admhideunassigned, $new_admdelconfirm, $zlibavail, $new_msendlimit, $subscriptionavail, ";
$sql.= "$admedoptions, $allsendcompressed, $uq_allownoemail, $blockleacher, '$input_defmailsig', ";
$sql.= "$faqlistshortcuts, $faqlimitrelated, $displayrating, $showtimezone, $showcurrtime, ";
$sql.= "'$new_admdateformat', $new_maxconfirmtime, $dosearchlog, '$logdateformat', $uqscmail, $extfailedlog, ";
$sql.= "$usebwlist, $lhide";
$sql.= ")";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to add settings to database.".faqe_db_error());
?>