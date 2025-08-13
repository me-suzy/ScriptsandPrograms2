<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


require("./admin_common.php");
$site['title'] = "Articles Management";

require ("./lib/class_articles.php");
require ("./lib/class_codeparse.php");
$parser = new Parser;

$art = new addon_Article;
switch ($_GET['do'])
{
	/* ----------------------------------------- */
	case "managefields":
		$usr->checkperm('',"isadmin");
		$content = $art->fields_main();
    break;
	/* ----------------------------------------- */
	case "managecatfields":
		$usr->checkperm('',"isadmin");
		$content = $art->cat_fields_main();
    break;
	/* ----------------------------------------- */
	case "addart":
		$usr->checkperm('',"canpost");
		$content .= $art->addarticle();
    break;
	/* ----------------------------------------- */
	case "addcat":
		$usr->checkperm('',"isadmin");
		$content .= $art->addcat();
    break;
	/* ----------------------------------------- */
	case "managecat":
		$usr->checkperm('',"isadmin");
		$content .= $art->listcat($ex);
    break;
	/* ----------------------------------------- */
	case "manageart":
		$content .= $art->listarticles();
    break;
	/* ----------------------------------------- */
	case "editart":
		$content .= $art->edit_article($_GET['id']);
    break;
	/* ----------------------------------------- */
	case "make_edit":
		$content .= $art->edit_cat($_GET[cid]);
    break;
	/* ----------------------------------------- */
	case "validate":
		$usr->checkperm('',"canapprove");
		$content = $art->validate_article($_REQUEST['id']);
	break;
	/* ----------------------------------------- */
	case "addfield":
		$usr->checkperm('',"isadmin");
		$content = $art->addfield();
	break;
	/* ----------------------------------------- */
	case "cat_addfield":
		$usr->checkperm('',"isadmin");
		$content = $art->cat_addfield();
	break;
	/* ----------------------------------------- */
	case "editfield":
		$usr->checkperm('',"isadmin");
		$content = $art->editfield($_GET['id']);
	break;
	/* ----------------------------------------- */
	case "cat_editfield":
		$usr->checkperm('',"isadmin");
		$content = $art->cat_editfield($_GET['id']);
	break;
	/* ----------------------------------------- */
	case "deletefield":
		$usr->checkperm('',"isadmin");
		$content = $art->deletefield($_GET['id']);
	break;
	/* ----------------------------------------- */
	case "deletecatfield":
		$usr->checkperm('',"isadmin");
		$content = $art->cat_deletefield($_GET['id']);
	break;
	/* ----------------------------------------- */
	case "clearcache":
		$usr->checkperm('',"isadmin");
		$content = $art->delete_allcache();
	break;
	/* ----------------------------------------- */
	case "addimage":
		$content = $art->do_addimage($_GET['for']);	
	break;
	/* ----------------------------------------- */
	case "importart":
		$content = $art->do_importart();	
	break;
	/* ----------------------------------------- */
	case "massmove":
		$usr->checkperm('',"isadmin");
		$content = $art->do_massmove();	
	break;
	/* ----------------------------------------- */

	/* ----------------------------------------- */
	case "submit":
		
		$admin->nocache();
		if($_POST['addcat']) $content .= $art->sql_addcat();
		if($_POST['editcat']) $content .= $art->sql_editcat();
		/* ------------------------------------------------------------- */
		if ($_POST['addfield']) $content = $art->process_addfield();
		if ($_POST['editfield']) $content = $art->process_editfield();
		/* ------------------------------------------------------------- */
		/* ------------------------------------------------------------- */
		if ($_POST['cat_addfield']) $content = $art->cat_process_addfield();
		if ($_POST['cat_editfield']) $content = $art->cat_process_editfield();
		/* ------------------------------------------------------------- */
		if($_POST['addarticle']) $content = $art->process_addarticle();
		if($_POST['editarticle']) $content = $art->process_editarticle();
		if($_POST['preview']) $content = $art->process_preview();

		
    break;
	/* ----------------------------------------- */
	case "deletecat":
		$usr->checkperm('',"isadmin");
		$content .= $art->deletecat($_GET[cid]);
    break;
	/* ----------------------------------------- */
	case "deleteart":
		$usr->checkperm('',"candelete");
		$content .= $art->delete_article($_GET['id']);
    break;
	/* ----------------------------------------- */
	case "deleteimage":
		$content .= $art->delete_image($_GET['id']);
    break;
	/* ----------------------------------------- */
	default:
		$content .= $art->main();
}
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
?>