<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
define('NAV_PROG',0);
define('NAV_CAT',1);
define('NAV_SUBCAT',2);
define('NAV_QUESCAT',3);
define('NAV_FAQ',4);
define('NAV_QUES',5);
define('NAV_KB',6);
$img_expand		= "$url_faqengine/gfx/tree_expand.gif";
$img_collapse		= "$url_faqengine/gfx/tree_collapse.gif";
$img_line		= "$url_faqengine/gfx/tree_vertline.gif";  
$img_split		= "$url_faqengine/gfx/tree_split.gif";
$img_end		= "$url_faqengine/gfx/tree_end.gif";
$img_leaf		= "$url_faqengine/gfx/tree_leaf.gif";
$img_spc		= "$url_faqengine/gfx/tree_space.gif";
$img_progclosed		= "$url_faqengine/".$navpic_progclosed;
$img_progopen		= "$url_faqengine/".$navpic_progopen;
$img_proglocked		= "$url_faqengine/".$navpic_proglocked;
$img_catclosed		= "$url_faqengine/".$navpic_catclosed;
$img_catopen		= "$url_faqengine/".$navpic_catopen;
$img_catlocked		= "$url_faqengine/".$navpic_catlocked;
$img_subcatopen		= "$url_faqengine/".$navpic_subcatopen;
$img_subcatclosed	= "$url_faqengine/".$navpic_subcatclosed;
$img_subcatlocked	= "$url_faqengine/".$navpic_subcatlocked;
// images for FAQ navbar
$img_faq		= "$url_faqengine/".$navpic_faq;
$img_ques		= "$url_faqengine/".$navpic_question;
// images for KB navbar
$img_kb			= "$url_faqengine/".$navpic_kbarticle;

function navbar_mkitemurl($treeitem)
{
	global $url_faqengine, $langvar, $act_lang, $layout, $navframe, $faqnavoptions;
	
	if(!bittst($faqnavoptions,BIT_1))
		$desturl=$url_faqengine."/faq.php?$langvar=$act_lang";
	else
		$desturl=$url_faqengine."/faqframe.php?$langvar=$act_lang";
	if(isset($limitprog))
		$desturl.="&limitprog=$limitprog";
	switch($treeitem[3])
	{
		case NAV_PROG:
			$desturl.="&list=categories&prog=".$treeitem[2];
			break;
		case NAV_CAT:
			list($progid,$catnr)=explode("|",$treeitem[2]);
			$desturl.="&list=category&prog=$progid&catnr=$catnr";
			break;
		case NAV_SUBCAT:
			list($progid,$catnr,$subcatnr)=explode("|",$treeitem[2]);
			if($subcatnr>0)
				$desturl.="&list=subcategory&prog=$progid&catnr=$catnr&subcatnr=$subcatnr";
			else
				$desturl.="&list=category&prog=$progid&catnr=$catnr";
			break;
		case NAV_QUESCAT:
			$desturl.="&list=questions&prog=".$treeitem[2];
			break;
		case NAV_QUES:
			if(bittst($faqnavoptions,BIT_1))
				$desturl=$url_faqengine."/faqframe.php?special=question&";
			else
				$desturl=$url_faqengine."/question.php?$langvar=$act_lang";
			list($progid,$quesnr)=explode("|",$treeitem[2]);
			$desturl.="&mode=read&prog=$progid&question=$quesnr";
			$backurl=$url_faqengine."/faq.php?list=questions&langvar=$act_lang&prog=$progid";
			if(isset($layout))
				$backurl.="&layout=$layout";
			$desturl.="&backurl=".urlencode($backurl);
			break;
		case NAV_FAQ:
			list($progid,$catnr,$faqnr)=explode("|",$treeitem[2]);
			$desturl.="&display=faq&faqnr=$faqnr&prog=$progid&catnr=$catnr";
			break;
	}

	if(isset($layout))
		$desturl.="&layout=$layout";
	if($navframe==1)	
		$desturl.="&navframe=1";
	return $desturl;
}

function navbar_kb_mkitemurl($treeitem)
{
	global $url_faqengine, $langvar, $act_lang, $layout, $navframe, $kbnavoptions;
	global $langvar, $act_lang, $limitprog;
	
	if(!bittst($kbnavoptions,BIT_3))
		$desturl=$url_faqengine."/kb.php?$langvar=$act_lang";
	else
		$desturl=$url_faqengine."/kbframe.php?$langvar=$act_lang";
	if(isset($limitprog))
		$desturl.="&limitprog=$limitprog";
	
	switch($treeitem[3])
	{
		case NAV_PROG:
			$desturl.="&mode=catlist&prog=".$treeitem[2];
			break;
		case NAV_CAT:
		case NAV_SUBCAT:
			list($progid,$catnr)=explode("|",$treeitem[2]);
			$desturl.="&mode=category&prog=$progid&catnr=$catnr";
			break;
		case NAV_KB:
			list($progid,$catnr,$articlenr)=explode("|",$treeitem[2]);
			if($navframe==1)
			{
				$backurl="$url_faqengine/kbframe.php?";
				if(isset($limitprog))
					$backurl.="limitprog=$limitprog&";
			}
			else
				$backurl="$url_faqengine/kb.php?";
			$backurl.="$langvar=$act_lang&mode=category&prog=$progid&catnr=$catnr";
			$desturl.="&mode=display&kbnr=$articlenr&prog=$progid&catnr=$catnr&backurl=".urlencode($backurl);
			break;
	}

	if($navframe==1)	
		$desturl.="&navframe=1";
	if(isset($layout))
		$desturl.="&layout=$layout";
	return $desturl;
}
?>
