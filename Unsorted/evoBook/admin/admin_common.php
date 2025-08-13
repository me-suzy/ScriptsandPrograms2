<?php
error_reporting(7);
require("./session.php");
require("./common.php");
$root = "../";
$site[title] = "Login";

$tpl = new template;
$tpl->templatefolder = $root."templates/admin";
$tpl->cssfile        = "site.css";
$tpl->extension      = "tpl";
$cssfile             = $tpl->cssfile;
$settings['imgfolder'] = $root.$settings['imgfolder'];
$conf['imgfolder'] = $settings['imgfolder'];

$conf['in_admin'] = 1;

$admin = new admin;

if ($_GET['do'] == "logout")
{
	$admin->clearcookie("admin[user]");
	$admin->clearcookie("admin[pass]");
	session_destroy();
	$_SESSION = array();

	if ($_GET['r'] != "")
	{
		header("location: $_GET[r]");
	}
	else
	{
		header("location: $_SERVER[PHP_SELF]");
	}
	exit;
}

if ( !isset($_SESSION['inadmin']) ) 
{
	if ( trim($_POST['username']) != '' && trim($_POST['password']) != '' )
	{
		if ($_POST['username'] == '')
		{
			eval("\$content .= \"".$tpl->gettemplate("login")."\";");
			$tpl->generate("main",1);
			exit;
		}

		
		if ( ($conf['username'] == $_POST['username']) && ($conf['password'] == $_POST['password']) )
		{ 
			if ($conf['in_admin'] != 1)
			{
				die('hack attempt');
			}

			$_SESSION['user'] = $_POST['username'];
			$_SESSION['inadmin'] = 1;
				
			$pathed = explode("?",$_SERVER['REQUEST_URI']);
			$_SERVER['REQUEST_URI'] = $pathed[0];
			$_SESSION['path'] = str_replace(basename($_SERVER['PHP_SELF']),'',$_SERVER['REQUEST_URI']);
			$_SESSION['end'] = time() + 3600;
				
			if ($_GET['r'] != "")
			{
				header("location: $_GET[r]");
			}
			else
			{
				header('location: '.$_SERVER['PHP_SELF']);
			}
		}
		else
		{
			$error = $evoLANG['xpass'];
			eval("\$content .= \"".$tpl->gettemplate("login")."\";");
			$tpl->generate("main",1);
			exit;
		}
	}
	else
	{
		$error = $evoLANG['xusername'];
		eval("\$content .= \"".$tpl->gettemplate("login")."\";");
		$tpl->generate("main",1);
		exit;
	}
}

eval("\$nav = \"".$tpl->gettemplate("nav")."\";");

?>