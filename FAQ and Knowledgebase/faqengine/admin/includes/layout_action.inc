<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$errors=0;
$donltrans=0;
if(isset($chbrtrans))
	$donltrans=setbit($donltrans,BIT_1);	
if(isset($cfbrtrans))
	$donltrans=setbit($donltrans,BIT_2);	
$faqnavoptions=0;
if(isset($faqnav_expandsubtree))
	$faqnavoptions=setbit($faqnavoptions,BIT_1);
$kbnavoptions=0;
if(isset($kbnav_wizard))
	$kbnavoptions=setbit($kbnavoptions,BIT_1);
if(isset($kbnav_search))
	$kbnavoptions=setbit($kbnavoptions,BIT_2);
if(isset($kbnav_expandsubtree))
	$kbnavoptions=setbit($kbnavoptions,BIT_3);
if(isset($highlightsearch))
	$searchhighlight=1;
else
	$searchhighlight=0;
if(isset($synctree))
	$navsync=1;
else
	$navsync=0;
if(isset($votesinline))
	$displayvotesinline=1;
else
	$displayvotesinline=0;
if(isset($enablepagenavicons))
	$usepagenavicons=1;
else
	$usepagenavicons=0;
$displayoptions=0;
if(isset($displaysubcat))
	$displayoptions=setbit($displayoptions,BIT_1);
$searchoptions=0;
if(isset($searchdisplayprog))
	$searchoptions=setbit($searchoptions,BIT_1);
if(isset($searchdisplaycat))
	$searchoptions=setbit($searchoptions,BIT_2);
if(isset($searchdisplaysubcat))
	$searchoptions=setbit($searchoptions,BIT_3);
if(isset($enablenextprev))
	$shownextprev=1;
else
	$shownextprev=0;
$kbsearchoptions=0;
if(isset($kbsearch_displayprog))
	$kbsearchoptions=setbit($kbsearchoptions,BIT_1);
if(isset($kbsearch_displaycat))
	$kbsearchoptions=setbit($kbsearchoptions,BIT_2);
$listoptions=0;
if(isset($listgotop))
	$listoptions=setbit($listoptions,BIT_1);
if($listtoptype==1)
	$listoptions=setbit($listoptions,BIT_2);
if(isset($listcatgotop))
	$listoptions=setbit($listoptions,BIT_3);
$linkoptions=0;
if(isset($addlinks))
	$linkoptions=setbit($linkoptions,BIT_1);
if($prognamelink==1)
	$linkoptions=setbit($linkoptions,BIT_2);
if(isset($enablesbcolors))
	$colorscrollbars=1;
else
	$colorscrollbars=0;
if(isset($noasclist))
	$disableasclist=1;
else
	$disableasclist=0;
if(isset($qautopub))
	$qesautopub=1;
else
	$qesautopub=0;
if(isset($ratingspub))
	$ratingspublic=1;
else
	$ratingspublic=0;
if(isset($pubratecom))
{
	if($ratingspublic==1)
		$ratingcommentpublic=1;
	else
		$ratingcommentpublic=0;
}
else
	$ratingcommentpublic=0;
if(isset($enablehover))
	$hovercells=1;
else
	$hovercells=0;
if(isset($totalsummary))
	$summaryintotallist=1;
else
	$summaryintotallist=0;
if(isset($enablerelated))
	$displayrelated=1;
else
	$displayrelated=0;
if(isset($allowjumpboxes))
	$enablejumpboxes=1;
else
	$enablejumpboxes=0;
if(isset($dofaqnewdisplay))
	$enablefaqnewdisplay=1;
else
	$enablefaqnewdisplay=0;
if(isset($enablecustomheader))
	$usecustomheader=1;
else
	$usecustomheader=0;
if(isset($enablecustomfooter))
	$usecustomfooter=1;
else
	$usecustomfooter=0;
if(isset($enableascheader))
	$useascheader=1;
else
	$useascheader=0;
if(isset($questionosrequired))
	$questionrequireos=1;
else
	$questionrequireos=0;
if(isset($useshortcutbar))
	$enableshortcutbar=1;
else
	$enableshortcutbar=0;
if(isset($versionrequired))
	$questionrequireversion=1;
else
	$questionrequireversion=0;
if(isset($clearcustomheader))
	$pageheader="";
else
{
	if($new_global_handling)
		$tmp_file=$_FILES['customheaderfile']['tmp_name'];
	else
		$tmp_file=$HTTP_POST_FILES['customheaderfile']['tmp_name'];
	if($upload_avail && is_uploaded_file($tmp_file))
		$pageheader = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
}
if(isset($clearascheader))
	$ascheader="";
else
{
	if($new_global_handling)
		$tmp_file=$_FILES['ascheaderupload']['tmp_name'];
	else
		$tmp_file=$HTTP_POST_FILES['ascheaderupload']['tmp_name'];
	if($upload_avail && is_uploaded_file($tmp_file))
		$ascheader = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
}
if(isset($clearcustomfooter))
	$pagefooter="";
else
{
	if($new_global_handling)
		$tmp_file=$_FILES['customfooterfile']['tmp_name'];
	else
		$tmp_file=$HTTP_POST_FILES['customfooterfile']['tmp_name'];
	if($upload_avail && is_uploaded_file($tmp_file))
		$pagefooter = addslashes(fread(fopen($tmp_file,"r"), filesize($tmp_file)));
}
if(isset($doheaderprint))
	$printheader=1;
else
	$printheader=0;
if(isset($dofooterprint))
	$printfooter=1;
else
	$printfooter=0;
$footerfile=addslashes($footerfile);
$headerfile=addslashes($headerfile);
if((!$pageheader) && (!$headerfile))
	$usecustomheader=0;
if((!$pagefooter) && (!$footerfile))
	$usecustomfooter=0;
if(isset($enablesearch))
	$allowsearch=1;
else
	$allowsearch=0;
if(isset($enablelists))
	$allowlists=1;
else
	$allowlists=0;
if(isset($enablesearchcomments))
	$searchcomments=1;
else
	$searchcomments=0;
if(isset($enablesearchquestions))
	$searchquestions=1;
else
	$searchquestions=0;
if(isset($enablesummary))
	$showsummary=1;
else
	$showsummary=0;
if(isset($dorestrict))
	$progrestrict=1;
else
	$progrestrict=0;
if(isset($allowkeywordsearch))
	$enablekeywordsearch=1;
else
	$enablekeywordsearch=0;
if(isset($showlanguageselector))
	$enablelanguageselector=1;
else
	$enablelanguageselector=0;
if(isset($forcewrap))
	$ascforcewrap=1;
else
	$ascforcewrap=0;
if($headerfile)
{
	$tmp=substr($headerfile,0,5);
	if(($tmp=="http:") || ($tmp=="https"))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		printf ($l_wrongincludefile,$l_customheader,$headerfile);
		echo "</td></tr>";
		$errors=1;
	}
}
if($ascheaderfile)
{
	$tmp=substr($ascheaderfile,0,5);
	if(($tmp=="http:") || ($tmp=="https"))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		printf ($l_wrongincludefile,$l_ascheader,$ascheaderfile);
		echo "</td></tr>";
		$errors=1;
	}
}
if($footerfile)
{
	$tmp=substr($footerfile,0,5);
	if(($tmp=="http:") || ($tmp=="https"))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		printf ($l_wrongincludefile,$l_customfooter,$footerfile);
		echo "</td></tr>";
		$errors=1;
	}
}
if($pageheader && isset($chbrtrans))
	$pageheader=nl2br($pageheader);
if($pagefooter && isset($cfbrtrans))
	$pagefooter=nl2br($pagefooter);
if(isset($showattachinfo))
	$displayattachinfo=1;
else
	$displayattachinfo=0;
if($errors==0)
{
	if(!isset($layoutnr))
	{
		include('./includes/layout_new.inc');
	}
	else
	{
		include('./includes/layout_update.inc');
	}
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"displayrow\" align=\"center\"><td>";
	echo "$l_layoutupdated";
	echo "</td></tr></table></td></tr></table>";
	$locallink="$act_script_url?$langvar=$act_lang";
	if(isset($layoutid))
		$locallink.="&layoutid=$layoutid";
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session($locallink)."\">$l_editlayout</a></div>";
}
else
{
	echo "<tr class=\"actionrow\" align=\"center\"><td>";
	echo "<a href=\"javascript:history.back()\">$l_back</a>";
	echo "</td></tr></table></td></tr></table>";
}
?>