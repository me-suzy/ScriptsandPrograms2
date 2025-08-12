<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$sql = "UPDATE ".$tableprefix."_layout SET headingbg='$headingbg', bgcolor1='$bgcolor1', bgcolor2='$bgcolor2', bgcolor3='$bgcolor3', pagebg='$pagebg', tablewidth='$tablewidth', fontface='$fontface', ";
$sql .="fontsize1='$fontsize1', fontsize2='$fontsize2', fontsize3='$fontsize3', fontsize4='$fontsize4', fontcolor='$fontcolor', stylesheet='$stylesheet', headingfontcolor='$headingfontcolor', ";
$sql .="subheadingfontcolor='$subheadingfontcolor', linkcolor='$linkcolor', vlinkcolor='$vlinkcolor', alinkcolor='$alinkcolor', groupfontcolor='$groupfontcolor', tabledescfontcolor='$tabledescfontcolor', ";
$sql .="fontsize5='$fontsize5', dateformat='$new_dateformat', newtime=$newtime, newpic='$newpic', searchpic='$searchpic', printpic='$printpic', backpic='$backpic', listpic='$listpic', ";
$sql .="pageheader='$pageheader', pagefooter='$pagefooter', usecustomheader=$usecustomheader, usecustomfooter=$usecustomfooter, emailpic='$emailpic', questionpic='$questionpic', ";
$sql .="usercommentpic='$usercommentpic', allowlists=$allowlists, allowsearch=$allowsearch, ";
$sql .="searchcomments=$searchcomments, searchquestions=$searchquestions, showsummary=$showsummary, summarylength=$summarylength, progrestrict=$progrestrict, footerfile='$footerfile', headerfile='$headerfile', ";
$sql .="printheader=$printheader, printfooter=$printfooter, mincommentlength=$input_mincomment, minquestionlength=$input_minquestion, proginfopic='$proginfopic', ";
$sql .="proginfowidth=$proginfowidth, proginfoheight=$proginfoheight, textareawidth=$textareawidth, textareaheight=$textareaheight, proginfoleft=$proginfoleft, proginfotop=$proginfotop, ";
$sql .="helpwindowwidth=$helpwindowwidth, helpwindowheight=$helpwindowheight, helpwindowleft=$helpwindowleft, helpwindowtop=$helpwindowtop, helppic='$helppic', closepic='$closepic', kbmode='$kbmode', defsearchmethod=$defsearchmethod, ";
$sql .="enablekeywordsearch=$enablekeywordsearch, enablelanguageselector=$enablelanguageselector, faqsortmethod=$faqsortmethod, kbsortmethod=$kbsortmethod, ";
$sql .="copyrightpos=$copyrightpos, copyrightbgcolor='$copyrightbgcolor', subheadingbgcolor='$subheadingbgcolor', actionbgcolor='$actionbgcolor', headerfilepos=$headerfilepos, footerfilepos=$footerfilepos, newinfobgcolor='$newinfobgcolor', ";
$sql .="ascheader='$ascheader', useascheader=$useascheader, asclinelength=$asclinelength, ascforcewrap=$ascforcewrap, addbodytags='$addbodytags', asclistmimetype='$asclistmimetype', asclistcharset='$asclistcharset', ";
$sql .="keywordsearchmode=$keywordsearchmode, questionrequireos=$questionrequireos, ";
$sql .="questionrequireversion=$questionrequireversion, newfaqdisplaymethod=$newfaqdisplaymethod, enablefaqnewdisplay=$enablefaqnewdisplay, faqnewdisplaybgcolor='$faqnewdisplaybgcolor', ";
$sql .="faqnewdisplayfontcolor='$faqnewdisplayfontcolor', listallfaqmethod=$listallfaqmethod, enableshortcutbar=$enableshortcutbar, enablejumpboxes=$enablejumpboxes, subcatbgcolor='$subcatbgcolor', subcatfontcolor='$subcatfontcolor', ";
$sql .="displayrelated=$displayrelated, htmllisttype=$htmllisttype, pagetoppic='$pagetoppic', attachpic='$attachpic', summaryintotallist=$summaryintotallist, ";
$sql .="summarychars=$summarychars, maxentries=$maxentries, hovercells=$hovercells, activcellcolor='$activcellcolor', ratingspublic=$ratingspublic, ratingcommentpublic=$ratingcommentpublic, ";
$sql .="qesautopub=$qesautopub, ns4style='$ns4style', ns6style='$ns6style', operastyle='$operastyle', geckostyle='$geckostyle', konquerorstyle='$konquerorstyle', ";
$sql .="ascheaderfile='$ascheaderfile', ascheaderfilepos=$ascheaderfilepos, numlatest=$numlatest, tabledescfontsize='$tabledescfontsize', langselectfontsize='$langselectfontsize', faqnewfontsize='$faqnewfontsize', ";
$sql .="jumpboxfontsize='$jumpboxfontsize', actionlinefontsize='$actionlinefontsize', newinfofontsize='$newinfofontsize', newinfofontcolor='$newinfofontcolor', shortbarfontsize='$shortbarfontsize', jumpboxsorting=$jumpboxsorting, ";
$sql .="disableasclist=$disableasclist, disablehtmlemail=$disablehtmlemail, contentcopy='$contentcopy', ";
$sql .="colorscrollbars=$colorscrollbars, sb3dlightcolor='$sb3dlightcolor', sbarrowcolor='$sbarrowcolor', sbdarkshadowcolor='$sbdarkshadowcolor', sbfacecolor='$sbfacecolor', ";
$sql.= "sbhighlightcolor='$sbhighlightcolor', sbshadowcolor='$sbshadowcolor', sbtrackcolor='$sbtrackcolor', pagebgpic='$pagebgpic', ";
$sql.= "pagebgrepeat='$pagebgrepeat', pagebgposition='$pagebgposition', pagebgattach='$pagebgattach', ";
$sql.= "subcatfontsize='$subcatfontsize', questionshorting=$questionshorting, ";
$sql.= "cc_font='$cc_font', cc_fontsize='$cc_fontsize', cc_fontcolor='$cc_fontcolor', subscriptionpic='$subscriptionpic', irow_bgcolor='$irow_bgcolor', ";
$sql.= "irow_fontcolor='$irow_fontcolor', irow_fontsize='$irow_fontsize', pagenavfontcolor='$pagenavfontcolor', pagenavfontsize='$pagenavfontsize', ";
$sql.= "subcatfontstyle=$subcatfontstyle, linkoptions=$linkoptions, listoptions=$listoptions, ";
$sql.= "clist_linkcolor='$clist_linkcolor', clist_vlinkcolor='$clist_vlinkcolor', clist_alinkcolor='$clist_alinkcolor', ";
$sql.= "shownextprev=$shownextprev, nextprevmode=$nextprevmode, ";
$sql.= "kbsearchoptions=$kbsearchoptions, searchoptions=$searchoptions, displayoptions=$displayoptions, usepagenavicons=$usepagenavicons, ";
$sql.= "nextpagepic='$nextpagepic', prevpagepic='$prevpagepic', firstpagepic='$firstpagepic', lastpagepic='$lastpagepic', ";
$sql.= "displayvotesinline=$displayvotesinline, tablealign=$tablealign, votesinlinedisplaymode=$votesinlinedisplaymode, ";
$sql.= "navbarwidth=$navbarwidth, navsync=$navsync, navpic_progclosed='$navpic_progclosed', navpic_progopen='$navpic_progopen', navpic_proglocked='$navpic_proglocked', ";
$sql.= "navpic_catclosed='$navpic_catclosed', navpic_catopen='$navpic_catopen', navpic_catlocked='$navpic_catlocked', navpic_subcatclosed='$navpic_subcatclosed', ";
$sql.= "navpic_subcatopen='$navpic_subcatopen', navpic_subcatlocked='$navpic_subcatlocked', navpic_faq='$navpic_faq', navpic_question='$navpic_question', ";
$sql.= "searchhighlight=$searchhighlight, searchhighlightcolor='$searchhighlightcolor', navpic_kbarticle='$navpic_kbarticle', kbnavoptions=$kbnavoptions, navpic_kbwizard='$navpic_kbwizard', ";
$sql.= "navpic_kbsearch='$navpic_kbsearch', navtreepos=$navtreepos, search_inputfieldwidth='$search_inputfieldwidth', faqnavoptions=$faqnavoptions, ";
$sql.= "tablespacing=$tablespacing, tablepadding=$tablepadding, extdateformat='$new_extdateformat', srchtoolpic='$srchtoolpic', donltrans=$donltrans, displayattachinfo=$displayattachinfo ";
$sql .="WHERE (layoutnr=$layoutnr)";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to update the database.".faqe_db_error());
if(isset($mods))
{
	while(list($null, $mod) = each($_POST["mods"]))
	{
		$mod_query = "INSERT INTO ".$tableprefix."_failed_notify (usernr) VALUES ('$mod')";
		if(!faqe_db_query($mod_query, $db))
			die("<tr class=\"errorrow\"><td>Unable to update the database.");
	}
}
if(isset($rem_mods))
{
	while(list($null, $mod) = each($_POST["rem_mods"]))
	{
		$rem_query = "DELETE FROM ".$tableprefix."_failed_notify WHERE usernr = '$mod'";
		if(!faqe_db_query($rem_query,$db))
			die("<tr class=\"errorrow\"><td>Unable to update the database.");
	}
}
?>