<?php
// ********************************************************************* //
//    --- Dengan nama Allah yang Maha Pemurah lagi Maha Penyayang..  --- //
//    Global Script for main folder script by MHR (amunzir@tm.net.my)    //
// **********************************************************************//
chdir('admin');
include 'common.php';
chdir('../');

$root = '';

$admin = new admin;
$admin->imgfolder=$root.'img';
$script['imgfolder'] = $admin->imgfolder;
$_GET = $admin->clean_code($_GET);
$_POST = $admin->clean_code($_POST);


$tpl = new template;
$tpl->templatefolder = 'templates';
$tpl->extension      = 'tpl';
$cssfile             = $tpl->cssfile;

class Page
{
	var $spliter = "/";

	function generate($layoutcrap=1)
	{
		global $site,$layout,$tpl,$admin,$script,$db,$content,$evoLANG,$nav,$database,$udb,$custom;
		global $_SERVER,$cssfile,$userinfo,$credits,$_GET;
		


		if ($site['gzip'] == "1") $admin->do_compress();
		$timer = "<br />".showtime();
		
		if ($site['sqldebug'] == "1")
		{
			if ($userinfo['id'] == "1")
			{
				$content .= $udb->show_all();
			}
		}
		
		eval("\$main = \"". $tpl->gettemplate("main") ."\";");
		
		$site['title'] = trim($this->site_title) == '' ? $site['title']:$this->site_title;
		if ($site['title'] != "")
		{
			$main = str_replace("{page}",$site['title'],$main);
		}
		
		$secArray = array ( "{content}" , "{page}","{timer}" );
		$repArray = array ( $content , $site['title'], $timer );
		do_out (str_replace($secArray,$repArray,$main));
	}
}

if ( $_SERVER['QUERY_STRING'] == "checkheader" )
{
	$headers = getallheaders();
	while (list ($header, $value) = each ($headers))
	{
		$content .= "$header: $value<br />\n";
	}
}
/* ........................................................................... */
$page = new Page;
?>