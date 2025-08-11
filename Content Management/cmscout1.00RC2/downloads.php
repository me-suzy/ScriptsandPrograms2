<?php
/**************************************************************************
    FILENAME        :   downloads.php
    PURPOSE OF FILE :   Displays downloads, fetches downloads when user requests one
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
    die("You have accessed this page illegally, please go use the main menu");
location("Downloads", $check["uid"]);

$isdown = "b";
$catid = isset($_GET['catid']) ? $_GET['catid'] : 0;
$action = isset($_GET['action']) ?$_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id']: 0;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($catid <> 0 && ($action == 'cat' || $action == 'down')) 
{
	$sql = $data->select_query("download_cats", "WHERE id='$catid'");
	$catinfo = $data->fetch_array($sql);
	
	$sql = $data->select_query("downloads", "WHERE cat='$catid' AND allowed = 1");
	$numdown = $data->num_rows($sql);
    if ($numdown == 0)
    {
        show_message_back("This category has no downloads");
    }
	$downloads = array();
	while ($downloads[] = $data->fetch_array($sql));
    $tpl->assign("numdown", $numdown);
    $tpl->assign("downloads", $downloads);
    $tpl->assign("catinfo", $catinfo);
} 

if ($action == 'down') {
	$sql = $data->select_query("downloads", "WHERE id='$id'");
	$down = $data->fetch_array($sql);
	if (file_exists($config["downloadpath"] . '/' . $down['file'])) 
    {
		header("location: ". $config["downloadpath"] . '/' . $down['file']);
		$sql = $data->update_query("downloads", "numdownloads = numdownloads + 1", "id='$id'", "", "", false);
	} 
    else 
    {
 		error_message('File not found, please contact the administrator');
	}
	 
}

$sql = $data->select_query("download_cats");
$numcats = 0;
$cats = array();
while ($tempcat = $data->fetch_array($sql))
{
    if ($data->num_rows($data->select_query("downloads", "WHERE cat = '{$tempcat['id']}'")) > 0)
    {
        $auth = unserialize($tempcat['downauth']);
        if($auth[$check['team']] == 1)
        {
            $cats[] = $tempcat;
            $numcats++;
        }
        elseif ($check['uname'] == "Guest" && $auth['Guest'] == 1)
        {
            $cats[] = $tempcat;
            $numcats++;
        }
    }
}


$tpl->assign("action", $action);
$tpl->assign("numcats", $numcats);
$tpl->assign("cats", $cats);
$dbpage = true;
$pagename = "downloads";
?>