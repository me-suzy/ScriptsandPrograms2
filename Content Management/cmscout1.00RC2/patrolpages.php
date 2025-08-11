<?php
/**************************************************************************
    FILENAME        :   patrolpages.php
    PURPOSE OF FILE :   Displays a groups website
    LAST UPDATED    :   29 September 2005
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
if (!defined('SCOUT_NUKE'))
{
    die("You have accessed this page illegally, please go use the main menu");
}

location("Patrol Information", $check["uid"]);
$patrolname=$_GET['patrol'];

/*********************************Begin Build Menu for Patrol Page*************************************/
$patrolside = '';
$patroltop = '';
$patrolbottom = '';
$perrow = 1;
$patrolmenu = array();

//Side Menu
$itemsql = $data->select_query("patrolmenu", "WHERE patrol='$patrolname' ORDER BY pos ASC");
if ($data->num_rows($itemsql) > 0) 
{   
    while ($items = $data->fetch_array($itemsql))
    {
        $i = 0;
        $side = $items['side'];
        if ($items['item'] != "url") 
        {
            $pagesql = $data->select_query("functions", "WHERE name='{$items['item']}'");
            if ($data->num_rows($pagesql) == 1)
            {
                $function = $data->fetch_array($pagesql);
                if($function['type'] == 4)
                {
                    $itemtype = "dynamic";
                }
            }
            else
            {
                $pagesql = $data->select_query("patrolcontent", "WHERE name='{$items['item']}' AND patrol='$patrolname'");
                if ($data->num_rows($pagesql) == 1)
                {
                    $itemtype = "static";
                }
                else
                {
                    $itemtype = "none";
                }
            }
        } 
        else 
        {
            $itemtype = "url";
        }
        $i++;
        if ($side == "top")
        {
            $style = "patroltop";
        }
        elseif ($side == "bottom")
        {
            $style = "patrolbottom";
        }
        elseif($side == "side")
        {
            $style = "patrolside";
        }
        
        if ($itemtype == "static") 
        {
            $page = $items['item'];
            $patrolmenu[$side] .= "<span style=\"text-align:center\"><a href=\"index.php?page=patrolpages&amp;patrol=$patrolname&amp;content=$page\" class=\"$style\">{$items['name']}</a>&nbsp;</span>";
        } 
        elseif ($itemtype == "dynamic") 
        {
            $t = $data->fetch_array($data->select_query("functions", "WHERE name = '{$items['item']}'"));
            $code = $t['code'];
            if ($t['type'] == 4 && $code != "patrolpages") 
            {
                $patrolmenu[$side] .= "<span style=\"text-align:center\"><a href=\"index.php?page=patrolpages&amp;patrol=$patrolname&amp;content=$code\" class=\"$style\">{$items['name']}</a></span>&nbsp;";
            }
            elseif ($code == "patrolpages")
            {
                $patrolmenu[$side] .= "<span style=\"text-align:center\"><a href=\"index.php?page=patrolpages&amp;patrol=$patrolname\" class=\"$style\">{$items['name']}</a></span>&nbsp;";
            }
        } 
        elseif ($itemtype == "url") 
        {
            $patrolmenu[$side] .= "<div style=\"text-align:center\"><a href=\"{$items['url']}\" class=\"$style\">{$items['name']}</a></div>";
        } 
        else 
        {
            $i--;
        }
        if ($i == $perrow && $side != "top" && $side != "bottom") 
        {
            $i = 0;
        }
    }
}


$tpl->assign("patrolmenu", $patrolmenu);
/*********************************End Build Menu for Patrol Page*************************************/

/*********************************Begin Get content for patrol page*************************************/
if (isset($_GET['content'])) $content = $_GET['content']; else $content = "";

$dataC = false;
$dbpage = false;
if (isset($_GET['pagenum'])) $pagenum = $_GET['pagenum']; else $pagenum = 0;
$patrolcontent = "";

location($content, $check["uid"]);
$patrolcontent = get_patrol_page($content, $patrolname);

if ($content == '' || !isset($content)) 
{
    $patrolcontent = get_patrol_page("frontpage", $patrolname);	
}
elseif ($patrolcontent == "" && file_exists($content . $phpex)) 
{
    include($content . $phpex);
} 

if ($patrolcontent == "$%$#PageOFF%$^$%")
{
    $patrolcontent = "<span id=\"error\">The page is only available to members of the group.</span>";
}
   
if ($pagenum == 0) 
{
    $pagenum = 1;
}

if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage") 
{
    $tpl->assign("hidesidemenu", 1);
    $patrolcontent = get_temp($pagename, $pagenum);
} 
elseif (isset($pagename) && $pagename == "frontpage")
{
    $patrolcontent = $content;
}

if ($patrolcontent == "")
{
    $patrolcontent = $pagename;
}


if ((!isset($patrolcontent) || $patrolcontent == "") && (!isset($pagename) || $pagename == ""))
{
    $patrolcontent = "";
}
/*********************************End Get content for patrol page*************************************/

$tpl->assign("patrolcontent", $patrolcontent);
$tpl->assign("patrolname", $patrolname);
$tpl->assign("patrolpages", 1);
$dbpage = false;
$pagename = "patrolpages.tpl";
?>