<?php
/**************************************************************************
    FILENAME        :   subsite.php
    PURPOSE OF FILE :   Displays a Sub website
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
if (!defined('SCOUT_NUKE'))
{
    die("You have accessed this page illegally, please go use the main menu");
}

$subsite=$_GET['site'];
location("Sub Site - $subsite", $check["uid"]);

/*********************************Begin Build Menu for Patrol Page*************************************/
$perrow = 1;
$sitemenu = array();

//Side Menu
$itemsql = $data->select_query("submenu", "WHERE site='$subsite' ORDER BY pos ASC");
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
                if($function['type'] == 5)
                {
                    $itemtype = "dynamic";
                }
            }
            else
            {
                $pagesql = $data->select_query("subcontent", "WHERE name='{$items['item']}' AND site='$subsite'");
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
            $style = "subtop";
        }
        elseif ($side == "bottom")
        {
            $style = "subbottom";
        }
        elseif($side == "side")
        {
            $style = "subside";
        }
        
        if ($itemtype == "static") 
        {
            $pagelink = $items['item'];
            $sitemenu[$side] .= "<div align=\"center\"><a href=\"index.php?page=subsite&amp;site=$subsite&amp;content=$pagelink\" class=\"$style\">{$items['name']}</a></div>&nbsp;";
        } 
        elseif ($itemtype == "dynamic") 
        {
            $t = $data->fetch_array($data->select_query("functions", "WHERE name = '{$items['item']}'"));
            $code = $t['code'];
            if ($t['type'] == 5 && $code != "subsite") 
            {
                $sitemenu[$side] .= "<span style=\"text-align:center\"><a href=\"index.php?page=subsite&amp;site=$subsite&amp;content=$code\" class=\"$style\">{$items['name']}</a></span>&nbsp;";
            }
            elseif ($code == "subsite")
            {
                $sitemenu[$side] .= "<span style=\"text-align:center\"><a href=\"index.php?page=subsite&amp;site=$subsite\" class=\"$style\">{$items['name']}</a></span>&nbsp;";
            }
        } 
        elseif ($itemtype == "url") 
        {
            $sitemenu[$side] .= "<a href=\"{$items['url']}\" class=\"$style\">{$items['name']}</a>";
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


$tpl->assign("sitemenu", $sitemenu);
/*********************************End Build Menu for Patrol Page*************************************/

/*********************************Begin Get content for patrol page*************************************/
if (isset($_GET['content'])) $content = $_GET['content']; else $content = "";
    
$dataC = false;
$dbpage = false;
if (isset($_GET['pagenum'])) $pagenum = $_GET['pagenum']; else $pagenum = 0;
$sitecontent = "";

location($content, $check["uid"]);

if ($content == '' || !isset($content)) 
{
    $sitecontent = get_sub_page("frontpage", $subsite);	
}
else
{
    $sitecontent = get_sub_page($content, $subsite);
}
   
if ($pagenum == 0) 
{
    $pagenum = 1;
}

if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage" && $sitecontent == "") 
{
    $tpl->assign("hidesidemenu", 1);
    $sitecontent = get_temp($pagename, $pagenum);
} 
elseif (isset($pagename) && $pagename == "frontpage")
{
    $sitecontent = $content;
}

if ($sitecontent == "")
{
    $sitecontent = $pagename;
}


if ((!isset($sitecontent) || $sitecontent == "") && (!isset($pagename) || $pagename == ""))
{
    $sitecontent = "";
}
/*********************************End Get content for patrol page*************************************/

$tpl->assign("sitecontent", $sitecontent);
$tpl->assign("sitename", $subsite);
$tpl->assign("sitepages", 1);
$dbpage = false;
$pagename = "sitepages.tpl";
?>