<?php
/**************************************************************************
    FILENAME        :   admin.php
    PURPOSE OF FILE :   Main admin file. Calls admin modules and sets up menu
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
$bit = "./../";
require_once ("../common.php");
$users = new auth($dbname, $dbhost, $dbusername, $dbpassword, $dbprefix);
location("Admin", $check["uid"]);
$error = "";
$show = "yes";
/********************************************Begin Initilization of page*****************************************/

$menufile = 'menu.tpl';
$message = '';
$oldtpldir = $tpl->template_dir;
$tpl->template_dir = '../templates/';
$tpl->compile_dir = '../templates_c/';
$tpl->config_dir = '../configs/';
$tpl->cache_dir = '../cache/';

$tpl->assign('tempdir', $tpl->template_dir);
$tpl->assign('title','Administration Panel');
$tpl->assign('imagedir', $tpl->template_dir.'images');
if (isset($check["uname"])) 
{
    $tpl->assign('name',$check["uname"]);
    $tpl->assign('logged',true);
} 
else 
{
    $tpl->assign('logged',false);
}

$uname = $check["uname"];
$level = $check["level"];
if (!isset($level))
{
	echo "Access Denied";
    exit;
} 
elseif (($level != 4) && ($level != 3) && ($level != 2) && ($level != 1) && ($level != 0)) 
{
	echo "Access Denied";
    exit;
}

$notsecond = "no";
if ($level == 4) 
{
    $notsecond = "yes";
}
/********************************************End Initilization of page*****************************************/


/********************************************Start Menu Building and Module Scanning*****************************************/
$mainmenu = "<table width=\"100%\" border=\"0\">";

$dirname = @opendir(".");

$getmodules = 1;
while( $filename = @readdir($dirname) )
{
    if( preg_match("/^admin_.*?" . $phpex . "$/", $filename) )
    {
        include($filename);
    }
}

@closedir($dirname);

unset($getmodules);

$mainmenu .= "<tr><td><div align=\"center\"><a href=\"admin.php\" class=\"menuitem\">Admin Main</a></div></td></tr>";
$mainmenu .= "<tr><td><div align=\"center\"><a href=\"../index.php\" class=\"menuitem\">Main Page</a> </div></td></tr>";


ksort($module);
$action = "";
while( list($cats, $action_array) = each($module) )
{
    $temp = $action_array;
    ksort($temp);
    $catitems = false;
    while( list($names, $pages)	= each($temp) )
    {
        $perm = $permision[$names];
        if ($check['level'] <= $perm)
        {
            $catitems = true;
        }

    }
    
    if ($catitems)
    {
        $mainmenu .= "<tr><th class=\"menuhead\">$cats</th></tr>";
    
        ksort($action_array);
        $mainmenu .= "<tr><td class=\"menuitem\"><div class=\"menuitem\">";
        while( list($names, $pages)	= each($action_array) )
        {
            $perm = $permision[$names];
            if ($check['level'] <= $perm)
                $mainmenu .= "<a href=\"admin.php?page=$pages\" class=\"menuitem\">$names</a><br />";
        }
        $mainmenu .= "</div></td></tr>";
    }
}
$mainmenu .= "</table>";
$tpl->assign("mainmenu", $mainmenu);
/********************************************End Menu Building and Module Scanning*****************************************/


/********************************************Start Content Generation*****************************************/

$page = $_GET['page'];
$pagename = $_SERVER['PHP_SELF'];
$pagename = $pagename . "?page=$page";
if (file_exists("admin_" . $page . $phpex))
{
    include("admin_" . $page . $phpex);
}
else 
{
    include("admin_main.php");
}

$ex = ( isset($_GET['ex']) ) ? $_GET['ex'] : "";

$tpl->assign("pagename", $pagename);
$tpl->assign("ex", $ex);
$tpl->assign("error", $error);
$tpl->assign('menufile', $menufile);
$tpl->assign('file', $filetouse);
$tpl->assign('message', $message);
$tpl->assign('show', $show);
$tpl->assign('userlevel', $check['level']);
$tpl->assign('notsecond', $notsecond);
$tpl->assign("timeoffset", getuseroffset($check['uname']));
/********************************************End Content Generation*****************************************/
//Compile page
if ($config['softdebug'] == 1)
{
    $endtime = microtime();
    $totaltime = $endtime - $starttime;
    $counter = $data->get_counter();
    $debug .= "<br />This page took $totaltime seconds to render<br />CMScout performed $counter database queries";
}
$tpl->assign('debug', $debug);
$tpl->display('admin/admin.tpl');
$error = false;
$loggedout = false;
?>