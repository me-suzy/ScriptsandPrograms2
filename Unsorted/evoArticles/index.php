<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

require("./global.php");
$script[tplfolder] = $root."templates/articles";
//************************************************************//
$site['title'] = $evoLANG['main'];
$site['name'] = $settings['sitename'];
$PHP_SELF=$_SERVER[PHP_SELF];


/* ------------------------------ 
		mod_rewrite stuff
-------------------------------- */
if ($settings['useses'] == 1)
{
	$art->rewrite_make();
	if ( !file_exists(".htaccess") )
	{
		$art->rewrite_delete();
		$settings['useses'] = 0;
	}
}


/* ------------------------------ */
switch($_SERVER['QUERY_STRING'])
{
	case "addimage":
		$content = $art->do_addimage($_GET['for']);	
	break;
	/* ----------------------------------------- */
	case "cat":
		$content = $art->main_showcat($_GET['cid']);	
	break;
	/* ----------------------------------------- */
	case "art":
		$content = $art->main_showart($_GET['id']);	
	break;
	/* ----------------------------------------- */
	case "search":
		$content = $art->main_search();	
	break;
	/* ----------------------------------------- */
	case "advsearch":
		$content = $art->search_table($_SERVER['PHP_SELF']."?search");	
	break;
	/* ----------------------------------------- */
	case "supportfile":
		$art->main_supportfile($_GET['id']);	
	break;
	/* ----------------------------------------- */
	case "print":
		$content = $art->main_printart($_GET['id']);	
	break;
	/* ----------------------------------------- */
	case "email":
		$content = $art->main_email($_GET['id']);	
	break;
	/* ----------------------------------------- */
	case "topart":
		$content = $art->main_showtopart('10');	
	break;
	/* ----------------------------------------- */
	case "toprated":
		$content = $art->main_showtoprated('10');	
	break;
	/* ----------------------------------------- */
	case "author":
		$content = $art->main_getauthor($_GET['id']);	
	break;
	/* ----------------------------------------- */
	case "rss":
		$art->main_exportrss();	
	break;
	/* ----------------------------------------- */
	case "sitemap":
		$content = $art->main_sitemap();	
	break;
	/* ----------------------------------------- */
	case "comment":
		$content = $cmt->process_addcomment();	
	break;
	/* ----------------------------------------- */
	case "login":
		header("location: cp.php");
	break;
	/* ----------------------------------------- */
	case "rate":

		if($art->article_rating() == false)
		{
			$content = $art->error;
			$content .= $admin->redirect( $art->link_art($_POST['id']) );
		}
		else
		{
			$content = $art->message;
			$content .= $admin->redirect( $art->link_art($_POST['id']) );
		}

	break;
	/* ----------------------------------------- */
	default;
		$content = $art->main_showindex();

}

// ***********************************************************************************************************************************
$page->generate();
// ***********************************************************************************************************************************

?>