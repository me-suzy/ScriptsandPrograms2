<?php
/**************************************************************************
    FILENAME        :   newsbox.php
    PURPOSE OF FILE :   SideBox: Fetches news items from database
    LAST UPDATED    :   01 November 2005
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
    die("You have accessed this page illegally, please go use the main menu");

	$content = $data->select_query("newscontent","WHERE allowed = 1 ORDER BY event DESC LIMIT {$config['numnews']}");
	if ($content) 
    {
		$news = array();
		while ($news[] = $data->fetch_array($content));
		$tpl -> assign('news',$news);
        $numnews = $data->num_rows($content);
        $tpl->assign("numnews", $numnews);
	}
    
    if (isset($_GET['id']))
    {
     	$id = $_GET['id'];
        $content = $data->select_query("newscontent","WHERE id=$id AND allowed = 1");
        if ($content) 
        {
            $newsitem = $data->fetch_array($content);
            $tpl -> assign('newsitem',$newsitem);
        }   
        $dbpage = true;
        $pagename = "newsitem";
    }
?>