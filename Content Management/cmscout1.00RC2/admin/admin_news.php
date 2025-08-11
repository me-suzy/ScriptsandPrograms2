<?php
/**************************************************************************
    FILENAME        :   admin_news.php
    PURPOSE OF FILE :   Manages news
    LAST UPDATED    :   21 November 2005
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
if( !empty($getmodules) )
{
	$module['Troop Content Management']['News'] = "news";
    $permision['News'] = 2;
	return;
}

if ($check['level'] != 1 && $check['level'] != 0 && $check['level'] != 2)
{
 error_message("Sorry, you can't access this section");
}	

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$Submit = $_POST['Submit'];
$action = $_GET['action'];
$id = $_GET['id'];

// Add / Edit news
if ($Submit == "Add")
{
    if ($_POST['editor'] == '')
    {
        error_message("You need to supply news content");
        exit;
    }
    if ($_POST['title'] == '')
    {
        error_message("You need to supply a title");
        exit;
    }        
    $news = safesql($_POST['editor'], "text", false);
    $title = safesql($_POST['title'], "text");
	$Add = $data->insert_query("newscontent", "'', $title, $news, NOW(), '{$check['uname']}', 1", 'News Admin', "Added news item");
    if ($Add)
    {
        echo "<script> alert('News added'); window.location = '$pagename';</script>\n";
        exit; 
    }
	$action="";
}
elseif ($Submit == "Modify")
{
	$news = safesql($_POST['editor'], "text");
    $title = safesql($_POST['title'], "text");
	$Update = $data->update_query("newscontent", "title=$title, news=$news", "id='$id'", 'News Admin', "Updated news item $id");
    if ($Update)
    {
        echo "<script> alert('News updated'); window.location = '$pagename';</script>\n";
        exit; 
    }
	$action = "";
}

// Delete News
if ($action=="delete")
{
	$Delete = $data->delete_query("newscontent", "id='$id'", 'News Admin', "Deleted New item");	
    if ($Delete)
    {
        echo "<script> alert('News deleted'); window.location = '$pagename';</script>\n";
        exit; 
    }
}
elseif ($action == 'publish') 
{
	$sqlq = $data->update_query("newscontent", "allowed = 1", "id=$id", "News Admin", "Published $id");
    header("Location: $pagename");
}
elseif ($action == 'unpublish') 
{
	$sqlq = $data->update_query("newscontent", "allowed = 0", "id=$id", "News Admin", "Unpublished $id");
    header("Location: $pagename");
}

// Show specific news
if ($id != "")
{
	// Show selected news
	$Show = $data->select_query("newscontent", "WHERE id='$id'");
	$shownews = $data->fetch_array($Show);
	$shownews['news'] = $shownews['news'];
    $tpl->assign("editor", true);
}
if ($action == "new")
{
    $tpl->assign("editor", true);
}

// Show all news
$result = $data->select_query("newscontent", "ORDER BY id DESC");

$news = array();
$numnews = $data->num_rows($result);
while ($news[] = $data->fetch_array($result));

$tpl->assign('shownews', $shownews);
$tpl->assign('action', $action);
$tpl->assign('numnews', $numnews);
$tpl->assign('news', $news);
$tpl->assign('editFormAction',$editFormAction);
$filetouse = "admin_news.tpl";
?>

