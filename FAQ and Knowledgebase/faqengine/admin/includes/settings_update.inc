<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$sql = "UPDATE ".$tableprefix."_settings SET showproglist=$showproglist, watchlogins=$watchlogins, ";
$sql.= "allowemail=$allowemail, urlautoencode=$urlautoencode, enablespcode=$enablespcode, ";
$sql.= "nofreemailer=$nofreemailer, allowquestions=$allowquestions, faqemail='$input_faqemail', ";
$sql.= "allowusercomments=$allowusercomments, newcommentnotify=$newcommentnotify, ";
$sql.= "enablefailednotify=$enablefailednotify, loginlimit=$new_loginlimit, timezone=$timezone, ";
$sql.= "enablehostresolve=$enablehostresolve, ratecomments=$ratecomments, usemenubar=$usemenubar, ";
$sql.= "admtextareasrows=$new_admtextareasrows, admtextareascols=$new_admtextareascols, ";
$sql.= "enablekbrating=$enablekbrating, userquestionanswermode=$uqanswermode, ";
$sql.= "userquestionanswermail=$userquestionanswermail, userquestionautopublish=$userquestionautopublish, ";
$sql.= "faqengine_hostname='$faqengine_hostname', ratingcomment=$ratingcomment, nosunotify=$nosunotify, ";
$sql.= "blockoldbrowser=$blockoldbrowser, bbccolorbar=$bbccolorbar, ";
$sql.= "disablehtmlemail=$disablehtmlemail, admstorefaqfilters=$admstorefaqfilters, ";
$sql.= "admhideunassigned=$admhideunassigned, admdelconfirm=$new_admdelconfirm, zlibavail=$zlibavail, ";
$sql.= "msendlimit=$new_msendlimit, subscriptionavail=$subscriptionavail, admedoptions=$admedoptions, ";
$sql.= "allsendcompressed=$allsendcompressed, uq_allownoemail=$uq_allownoemail, ";
$sql.= "blockleacher=$blockleacher, defmailsig='$input_defmailsig', faqlistshortcuts=$faqlistshortcuts, ";
$sql.= "faqlimitrelated=$faqlimitrelated, displayrating=$displayrating, showtimezone=$showtimezone, ";
$sql.= "showcurrtime=$showcurrtime, admdateformat='$new_admdateformat', maxconfirmtime=$new_maxconfirmtime, ";
$sql.= "dosearchlog=$dosearchlog, logdateformat='$logdateformat', uqscmail=$uqscmail, extfailedlog=$extfailedlog, ";
$sql.= "usebwlist=$usebwlist, lhide=$lhide ";
$sql.= "WHERE settingnr=1";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to update settings in database.".faqe_db_error());
?>