<?php
/**************************************************************************
    FILENAME        :   admin_patrolart.php
    PURPOSE OF FILE :   Manages articles
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
if( !empty($getmodules) )
{
	$module['User Content Management']['Articles'] = "patrolart";
    $permision['Articles'] = 4;
	return;
}

if ($level != 4 && $level != 3 && $level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$action = $_GET['action'];
$id = $_GET['id'];
if ($action == 'delete') 
{
	$sqlq = $data->delete_query("patrol_articles", "ID=$id", "Admin Articles", "Deleted $id");
	if ($sqlq) 
    { 
        echo "<script> alert('The article has been updated'); window.location = '$pagename';</script>\n";
        exit;   
	}
}
elseif ($action == 'publish') 
{
	$sqlq = $data->update_query("patrol_articles", "allowed = 1", "ID=$id", "Admin Articles", "Published $id");
    header("Location: $pagename");
}
elseif ($action == 'unpublish') {
	$sqlq = $data->update_query("patrol_articles", "allowed = 0", "ID=$id", "Admin Articles", "Unpublished $id");
    header("Location: $pagename");
}
elseif ($action == 'edit') 
{
	if ($level != 4 && $level != 3 && $level != 2 && $level != 1 && $level != 0) 
    {
	 error_message("Admin Panel", "Sorry, you can't edit this article");
	}
	$query = $data->select_query("patrol_articles", "WHERE ID=$id");
	$row = $data->fetch_array($query);
	$row['detail'] = $row['detail'];
	$quer = $data->select_query("album_track");
	$res = $data->fetch_array($quer);
	$i = 0;
	$albumid = array();
	$albumname = array();
    
	do 
    { 
		$i++;
		$albumid[] = $res['ID'];
		$albumname[] = $res['album_name'];
	} while ($res = $data->fetch_array($quer));
    
	$tpl->assign('numalbum', $i);
	$tpl->assign('id', $albumid);
	$tpl->assign('albumname', $albumname);
	$teamname = array();
	$team_query = $data->select_query("patrolpoints");
	$numteams = $data->num_rows($team_query);
	$teams = $data->fetch_array($team_query);
    
	do 
    {
	 $teamname[] = $teams['Patrolname'];
	} while ($teams = $data->fetch_array($team_query));
    
	$tpl->assign('teamname',$teamname);
	$tpl->assign('articleteam', $row['patrol']);
	$tpl->assign('numteams', $numteams);
	
	$submit=$_POST["Submit"];
	if ($submit == "Update") 
    {
        if ($_POST['title'] == '')
        {
            error_message("You need to enter a title for the article");
            exit;
        }
        if ($_POST['editor'] == '')
        {
            error_message("You need to type out the article");
            exit;
        }
        
        $title = safesql($_POST['title'], "text");
		$dh = safesql($_POST['dh'], "date");
		$cat = $_POST['cat'];
		$story = safesql($_POST['editor'], "text", false);
		$auth = safesql($_POST['auth'], "text");
		$patrol = safesql($_POST['patrol'], "text");
		if ($patrol != '') {
			$sql = $data->update_query("patrol_articles", " patrol=$patrol, title=$title, detail=$story, date_happen=$dh, album_id='$cat', author=$auth",
									"ID=$id", "Admin Articles", "Edited Article $id");	
		} else {	
			$sql = $data->update_query("patrol_articles", "title=$title, detail=$story, date_happen=$dh, album_id='$cat', author=$auth",
									"ID=$id", "Admin Articles", "Edited Article $id");	
		}
        if($sql)
        {
            echo "<script> alert('The article has been updated'); window.location = '$pagename';</script>\n";
            exit;   
        } 						
	}

} 
if ($action == "") 
{
	$row = array();
    if ($level != 0 && $level != 1 && $level != 2) 
    {
		$patrol = $check['team'];
		$result = $data->select_query("patrol_articles", "WHERE patrol='$patrol'");
	} 
    else 
    {
		$result = $data->select_query("patrol_articles");
	}
    
	$numarticles = $data->num_rows($result);
	$row[] = $data->fetch_array($result); 
	while ($row[] = $data->fetch_array($result));
}

$filetouse = "admin_patrolart.tpl";
$tpl->assign('numarticles', $numarticles);
$tpl->assign('detail', $detail);
$tpl->assign("level", $check['level']);
$tpl->assign('row', $row);
$tpl->assign('action', $action);
$tpl->assign("editor", true);

?>	