<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

chdir("admin");
require_once("./common.php");
chdir("..");
unset($root);

//checking so any jackass doesnt get bright ideas
if (trim($_REQUEST['content']) != '')
{
	$_REQUEST['content'] = '';
}

/* -------------------------------------------------- */
$settings['wysiwyg'] = MISC_FOLDER."wysiwyg/";
/* -------------------------------------------------- */

$admin = new admin;

//gzip
if($settings['usegzip'] == 1)@$admin->do_compress();

$script['imgfolder'] = $settings['imgfolder'];
start(); // start timer
require($root."lang/".$settings['deflang'].".php");
$tpl = new template;
$site['name'] = $settings['sitename'];

if ($_GET['get'] == "css")
{
	echo $tpl->process_style();
	exit;
}

// start auth thingy
require ("admin/lib/class_user.php");
$usr = new User;
$usr->db['user'] = $database['article_user'];
$usr->db['field'] = $database['article_userfield'];
$usr->db['usergroup'] = $database['article_usergroup'];

foreach($_GET as $_getv['var'] => $_getv['val'])
{
	switch($_getv['var'])
	{
		case "do":
			$_SERVER['QUERY_STRING'] = $_getv['val'];
		break;
		default;
			$_GET[$getv['var']] = $_getv['val'];
	}
}

/*---------------- SES THINGY ----------------- */
if ($_SERVER['QUERY_STRING'] != '')
{
	if (preg_match("/\//",$_SERVER['QUERY_STRING']))
	{
		$g_split = explode("/",$_SERVER['QUERY_STRING']);
		$_SERVER['QUERY_STRING'] = $g_split['0'];
		
		foreach($g_split as $num => $val)
		{
			if ($num != 0)
			{
				if (preg_match("/\,/",$val))
				{
					$g_split2 = explode(",",$val);
					foreach($g_split2 as $gvars)
					{
						$g_split3 = explode(":",$gvars);
						$_GET[$g_split3[0]] = $g_split3[1];
					}
				}
				else
				{
					$g_split3 = explode(":",$val);
					$_GET[$g_split3[0]] = $g_split3[1];
				}
			}
		}
	}
	unset($g_split,$g_split2,$g_split3);
}

/* ------------------------------------------*/
require ("admin/lib/class_codeparse.php");
$parser = new Parser;
require ("admin/lib/class_articles.php");
$art = new addon_Article;
include ( "admin/comments/".$settings['commentsystem'].".php");
$cmt = new Comments;
/* ------------------------------------------*/
class Page
{
	var $spliter;
	
	function Page()
	{
		global $evoLANG;
		$this->init();
	}

	function init($tplfolder='')
	{
		global $tpl,$settings,$evoLANG,$layout;
		
		$tplfolder = $tplfolder == '' ? $settings['defstyle']:$tplfolder;
		$tpl->extension="inc";

		$this->cssfile = $tpl->process_style($tplfolder);
		$tpl->templatefolder = "templates/styles/".$tplfolder;
	}

	function generate($layoutcrap=1)
	{
		global $site,$layout,$tpl,$admin,$script,$content,$evoLANG,$nav,$database,$udb,$custom;
		global $_SERVER,$cssfile,$userinfo,$credits,$version,$settings,$page,$art,$_GET,$_REQUEST;
		

		if (is_array($art->layout))
		{
			foreach ($art->layout as $lname => $lval)
			{
				$layout[$lname] = $lval;
			}
		}

		$cssfile = $this->cssfile;
		$timer = "<br />".showtime();
		
		if ($_GET['sql'] == "show")
		{
			$content .= $udb->show_all();
		}

		$leftnav = $art->make_leftnav();
		$site['title'] = trim($this->site_title) == '' ? $site['title']:$this->site_title;
		
		eval("\$location = \"".$tpl->gettemplate("nav_bits")."\";");
		eval("\$header = \"".$tpl->gettemplate("header")."\";");
		eval("\$footer = \"".$tpl->gettemplate("footer")."\";");

		$key_replace = $this->meta_key != "" ? $this->meta_key:"";
		$desc_replace = $this->meta_desc != "" ? $this->meta_desc:"";
		$tags = "<meta name=\"keywords\" content=\"$key_replace\" />\n<meta name=\"description\" content=\"$desc_replace\" />";
		$header = str_replace("{custom_meta_tags}",$tags,$header);
		
		eval("echo(\"".$tpl->gettemplate("_layout")."\");");
	}

}

$page = new Page;
eval( $admin->get_file($tpl->templatefolder.'/phpparse.inc') );

//$page->spliter="»";


/* ----------- on close --------------------- */
if ($settings['isclose'] == 1)
{
	$site['title'] = $evoLANG['closed']; 
	$content = $settings['closereason'];
	$page->generate();
	exit();
}
?>