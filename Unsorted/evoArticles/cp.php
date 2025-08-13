<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

require("./global.php");
require("./admin/session.php");
$script[tplfolder] = $root."templates/articles";
//************************************************************//
$site['title'] = $evoLANG['cp'];
$site['name'] = $settings['sitename'];
$PHP_SELF=$_SERVER[PHP_SELF];

/* --------- Login ---------------- */
if ( !isset($_SESSION['incp']) && $_SESSION['incp'] != 1) 
{
	if ( trim($_POST['username']) != '' && trim($_POST['password']) != '' )
	{
		if (trim($_POST['username']) == '' || trim($_POST['password']) == '')
		{
			$art->cp_login('',1);
		}

		$sql = $udb->query("SELECT * FROM ".$usr->db['user']." WHERE username='".$_POST['username']."'");
		
		$count = $udb->num_rows($sql);
		if ($count != 0)
		{
			while ($userinfo = $udb->fetch_array($sql) )
			{
				$userinfo = $admin->strip_array($userinfo);
				if ($userinfo['password'] == md5($_POST['password']))
				{	
					$userperm = $udb->query_once("SELECT * FROM ".$usr->db['usergroup']." WHERE gid='".$userinfo['groupid']."'");
					$_SESSION['userperm'] = $userperm;

					$_SESSION['incp'] = 1;
					$_SESSION['userinfo'] = $userinfo;
					$_SESSION['end'] = time() + $settings['sestimeout'];

					if (trim($_POST['redirect']) != "")
					{
						$usr->redirect($_POST['redirect']);
						exit;
					}
				}
				else
				{
					//kalau tak match?
					$art->cp_login($evoLANG['xpass'],1);
				}
			}
		}
	}
	else
	{ 
		$art->cp_login('',1);				
	}
}

if (!isset($_SESSION['end']) || $_SESSION['end'] == "")
{
	$art->cp_login($evoLANG['xpass'],1);
}

if ($_SESSION['end'] != "" && $_SESSION['end'] < time() )
{
	$_SESSION['end'] = "";
	$art->cp_login($evoLANG['sesexpired'] ,1,$_SERVER['HTTP_REFERER']);
}

$userinfo = &$_SESSION['userinfo'];
// **********************************//
switch($_SERVER['QUERY_STRING'])
{
	case "addart":
		$content .= $art->addarticle();
    break;
	/* ----------------------------------------- */	
	case "addimage":
		$content = $art->do_addimage($_GET['for']);	
	break;
	/* ----------------------------------------- */
	case "submit":		
		$admin->nocache();
		if($_POST['addarticle']) $content = $art->process_addarticle();
		if($_POST['editarticle']) $content = $art->process_editarticle();
		if($_POST['preview']) $content = $art->process_preview();

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
	case "deleteart":
		$usr->checkperm('',"candelete");
		$content .= $art->delete_article($_GET['id']);
    break;
	/* ----------------------------------------- */
	case "managefields":
		$content = $evoLANG['doincp'];
    break;
	/* ----------------------------------------- */
	case "importart":
		$content = $art->do_importart();	
	break;
	/* ----------------------------------------- */
}

/* ---- navigation ------------------------------------------------------ */
require("./admin/lib/admin_nav.php");
$thenav = "articles";
if (is_array($main_links[$thenav]))
{
	foreach ($main_links[$thenav] as $value)
	{
		$val2 = explode(",",$value);
		$cp_links .= ". ".str_replace("articles.php","cp.php",$admin->makelink($val2[1],$val2[0]))."<br />";
	}
}
eval("\$layout[nav_additional] = \"".$tpl->gettemplate("cp_nav")."\";");


/* --------------------------------------------------------------------- */
$page->generate();
/* --------------------------------------------------------------------- */
?>