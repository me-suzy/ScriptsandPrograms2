<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


$root = "../";

// start session handler ------
error_reporting(0);
require("./session.php");
require("./common.php");

// ------------------------------------------------------//
define(OUT_FOLDER,$root."out/");
define(LANG_FOLDER,$root."lang/");
define(MISC_FOLDER,$root."misc/");
$settings['inadmin'] = 1;

// ------------------------------------------------------//
$settings['wysiwyg'] = MISC_FOLDER."wysiwyg/";
// ------------------------------------------------------//
$tpl = new template;

$adminskin = "default";
$tpl->templatefolder = $root."templates/admin/";
$settings['admin_css'] = "admin_".$adminskin.".css";

$tpl->extension="inc";
$cssfile = $tpl->cssfile;

$admin = new admin;
$admin->imgfolder=$root."img";

// ----------------------------
$site['name'] = $settings['sitename'];
$settings['imgfolder'] = $root.$settings['imgfolder'];
// ----------------------------
if (!file_exists(LANG_FOLDER.$settings['deflang'].".php"))
{
	$evoLANG_file = LANG_FOLDER."lang_english.php";
}
else
{
	$evoLANG_file = LANG_FOLDER.$settings['deflang'].".php";
}

require ($evoLANG_file); // get lang file

##*************************************************************##
$script[imgfolder] = $admin->imgfolder; // default image folder

// start accessmask thingy
require ("lib/class_access.php");
$acc = new Access;

// start auth thingy
require ("lib/class_user.php");
$usr = new User;

$usr->db['user'] = $database['article_user'];
$usr->db['field'] = $database['article_userfield'];
$usr->db['usergroup'] = $database['article_usergroup'];

if ($_GET['info'] == "exp") echo date("F j, Y, g:i a",$_SESSION['end']); // to know expiration

//print_r($_POST);
//print_r($_SESSION);

if ( ( !isset($_SESSION['inadmin']) && $_SESSION['inadmin'] != 1 ) || $_POST['auth'] ) 
{
	if ( trim($_POST['username']) != '' && trim($_POST['password']) != '' )
	{
		if ($_POST['username'] == '')
		{
			$usr->loginform('',1);	
		}

		$sql = $udb->query("SELECT
									".$usr->db[user].".*,
									$database[article_access].cat_id AS access
										
										FROM ".$usr->db['user']."
											
											LEFT JOIN $database[article_access] ON ($database[article_access].userid=".$usr->db['user'].".id)
												WHERE username='".$_POST['username']."'");
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

					//$usr->checkperm($userperm,"isadmin");

					$_SESSION['inadmin'] = 1;
					$_SESSION['userinfo'] = $userinfo;
					$_SESSION['end'] = ""; //saje
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
					$usr->loginform($evoLANG['xpass'],1);
					exit;
				}
			}
		}
		else
		{
			$usr->loginform($evoLANG['xusername'],1);
			exit;
		}
	}
	else
	{ 
		$usr->loginform('',1);	
		exit;
	}
}

if ($_SESSION['end'] != "" && $_SESSION['end'] < time() )
{
	$usr->loginform($evoLANG['sesexpired'] ,1,$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
}

$userinfo = $_SESSION['userinfo'];
if ( ( empty($_SESSION['userinfo']) || empty($userinfo) ) && trim($_SESSION['user']) != '' && trim($_SESSION['pass']) != '')
{
	$userinfo = $udb->query_once("SELECT
									".$usr->db[user].".*,
									$database[article_access].cat_id AS access
										
										FROM ".$usr->db['user']."
											
											LEFT JOIN $database[article_access] ON ($database[article_access].userid=".$usr->db['user'].".id)
												WHERE username='".$_POST['username']."' AND password='".md5($_POST['password'])."'");
	
}

/* ---------------------------- */
//			get nav
/* ---------------------------- */
require("./lib/admin_nav.php");
if ( file_exists("comments/".$settings['commentsystem'].".php") )
{
	include ( "comments/".$settings['commentsystem'].".php");
	$cmt = new Comments;
}
else
{
	//echo "<h1>Warning</h1> <br /> Invalid Comments System Selected";
}


// -------------- Navigation --------------------------
foreach($main_nav as $thenav)
{
	$id++;
	unset($thelinks);
	if (is_array($main_links[$thenav]))
	{
		foreach ($main_links[$thenav] as $value)
		{
			if ($value == "spacer")
			{
				$thelinks .= "<br /><div style=\"border-bottom:1px solid black\"></div><br />";
			}
			else
			{
				$val2 = explode(",",$value);
				$thelinks .= ". ".$admin->makelink($val2[1],$val2[0])."<br />";
			}
		}
	}
	$thenav = $admin->intercap($thenav);
	$nav_show = $_SESSION['ex'] == $id ? "":EXPAND_NAV;	
	eval("\$nav .= \"".$tpl->gettemplate("nav_loop")."\";");
}
?>