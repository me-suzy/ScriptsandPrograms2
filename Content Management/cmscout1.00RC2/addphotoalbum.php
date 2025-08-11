<?php
/**************************************************************************
    FILENAME        :   addphotoalbum.php
    PURPOSE OF FILE :   Add a users photo album to the database
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
location("Adding a Photo Album", $check["uid"]);
/********************************************Check if user is allowed*****************************************/
if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
} 

$message = "";
$uname = $check["uname"];
if (!$error) 
{
    $currentPage = $_SERVER["PHP_SELF"];

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING']))
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $tpl->assign('editFormAction', $editFormAction);

    if (isset($_POST["submit"])) 
    {
        $album_name = strip_tags($_POST['album_name']);
        $patrol = $_POST['patrol'];
        if ($_POST['album_name'] == '')
        {
            error_message("You need to supply a name for the photo album");
            exit;
        }
        
        $insertSQL = sprintf("'', %s, %s, %s, 0",
                                   safesql($album_name, "text"),
                                   safesql($patrol, "text"),
                                   safesql($check["uname"], "text"));	
        if($config['confirmalbum'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) 
        {
            $insertSQL .= ", 0";
            $extra = "The administrator first needs to publish your album before it will be available to view. ";
            publish_mail($check['uname'], "Photo Album", $album_name);
        }
        else $insertSQL .= ", 1";
        
        $album_name = safesql($album_name, "text");
        $sql = $data->select_query("album_track", "WHERE album_name = $album_name");
        $albumexist = false;
        
        if ($data->num_rows($sql) == 0)
            $Result1 = $data->insert_query("album_track", $insertSQL, "", "", false);
        else
        {
            $albumexist = true;
            $existalbum = $data->fetch_array($sql);
        }
            
        $album = $data->fetch_array($data->select_query("album_track", "WHERE album_name=$album_name"));
        if(!$albumexist)
        {
            echo "<script> alert('Your photo album {$album['album_name']} has been created. $extra You will now be taken to the album so you can add photos'); window.location='index.php?page=mythings&cat=album&action=edit&id={$album['ID']}';</script>";
        }
        else
        {
            echo "<script> alert('An album by that name already exists. Please type in another album, or contact the owner({$existalbum['owner']}) of the existing album and ask them to add your photos.'); window.location='index.php?page=addphotoalbum';</script>";
        }
        exit;
    }
    
            
    $teamname = array();
    $team_query = $data->select_query("authteam", "WHERE ispatrol=1");
    $numteams = $data->num_rows($team_query);
    $teams = $data->fetch_array($team_query);
    do {
     $teamname[] = $teams['teamname'];
    } while ($teams = $data->fetch_array($team_query));
    
    $tpl->assign('teamname',$teamname);
    $tpl->assign('userteam', $check['team']);
    $tpl->assign('numteams', $numteams);
    $tpl->assign("post", $post);
}

$dbpage = true;
$pagename = "addalbum";
?>