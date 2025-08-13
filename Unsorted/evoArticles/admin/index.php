<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

require("./admin_common.php");
$site[title] = "Main";

$PHP_SELF = $_SERVER['PHP_SELF'];

/* main page crap */
$admin->row_width="50%";
$admin->row_align="left";

if ($stats = @exec('uptime'))
{
     preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/',$stats,$regs);
     $server_load = $admin->add_row("Server Load",$regs[1].",".$regs[2].",".$regs[3]);
}
else
{
	$server_load='';
}

$mysql_version = mysql_get_server_info();
$phpversion = phpversion();

$get_total = $udb->query_once("SELECT COUNT(id) as totalarticles, SUM(views) as totalviews FROM $database[article_article]");
$total['articles'] = number_format($get_total['totalarticles']);
$total['artviews'] = number_format($get_total['totalviews']);

$get_unlive = $udb->query_once("SELECT COUNT(id) as xlive FROM $database[article_article] WHERE validated=0");
$total['pendingarticles'] = $admin->makelink($get_unlive['xlive'],"articles.php?do=manageart&search=1&sort=date&order=desc&status=0");

$get_ctotal = $udb->query_once("SELECT COUNT(cid) as totalcat FROM $database[article_cat]");
$total['categories'] = number_format($get_ctotal['totalcat']);


$info .= $admin->add_spacer("Statistics");
$info .= $admin->add_row("evoArticles Version","<b>".$version."</b>");
$info .= $admin->add_row("PHP Version",$phpversion);
$info .= $admin->add_row("mySQL Version",$mysql_version);
$info .= $admin->add_row("Total Articles",$total['articles']);
$info .= $admin->add_row("Total Article Views",$total['artviews']);
$info .= $admin->add_row("Total Categories",$total['categories']);
$info .= $admin->add_row("Pending Articles",$total['pendingarticles']);


$info .= $server_load;

$info .= $admin->add_spacer("Credits");
$info .= $admin->add_row("Lead Developer","Munzir Rosdi");
$info .= $admin->add_row("Business Development","Ahmed Farooq");
$info .= $admin->add_row("Publishing","<a href=\"http://www.enthropia.com\" target=\"_blank\">Enthropia Inc</a>");
$info .= $admin->add_row("A Product Of","<a href=\"http://www.evo-dev.org\" target=\"_blank\">Evo-Dev Systems</a> ");


$content = $admin->add_table($info,"80%");

$usr->table_lookup = array (
							 "username" => $evoLANG['username']
							);
$usr->loc = "user.php";

$content .= $usr->searchtable();
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
?>