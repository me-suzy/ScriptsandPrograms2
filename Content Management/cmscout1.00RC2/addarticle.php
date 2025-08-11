<?php
/**************************************************************************
    FILENAME        :   addarticle.php
    PURPOSE OF FILE :   Add a users article to the database
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
/********************************************Check if user is allowed*****************************************/
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
location("Adding a article", $check["uid"]);
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
    if (isset($_SERVER['QUERY_STRING'])) {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $where = $config['photopath'] . "/";
	if ((isset($_POST["Submit"])) && ($_POST["Submit"] == "Submit")) 
    {
        if ($_FILES['file']['name'] != "") 
        {
            if (($_FILES['file']['type'] == 'image/gif') || ($_FILES['file']['type'] == 'image/jpeg') || ($_FILES['file']['type'] == 'image/png') || ($_FILES['file']['type'] == 'image/pjpeg')) 
            {
                $filestuff = uploadpic($_FILES['file'], $config['photox'], $config['photoy'], true);
                $filename = $filestuff['filename'];
            } 
            else
            {
                error_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.<br />And the file that you wish to upload is a {$_FILES['file']['type']}");
            }
        }
        else
            $filename = "";
                   
        $patrols = $check["team"];
        $sql = $data->select_query("authteam", "WHERE teamname='$patrols'");
        $temp = $data->fetch_array($sql);
        if ($temp['ispatrol'] == 0)
        {
            $patrols = "general";    
        }
        
        if ($_POST['title'] == '')
        {
            error_message("You need to enter a title for the article");
            exit;
        }
        if ($_POST['story'] == '')
        {
            error_message("You need to type out the article");
            exit;
        }
        $insertSQL = sprintf("'', %s, %s, %s, %s, %s, %d, %s, %s, %s",
                           safesql($patrols, "text"),
                           safesql($filename, "text"),
                           safesql($_POST['title'], "text"),
                           safesql($_POST['story'], "text", false),
                           safesql($_POST['dh'], "date"),                           
                           $timestamp,									   
                           safesql($_POST['cat'], "int"),									   
                           safesql($_POST['auth'], "text"),
                           safesql($uname, "text"));
        
        if ($config['confirmarticle'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
        {
            $extra = "The administrator first needs to publish your article before it will be available on the website";
            $insertSQL .= ", 0";
            publish_mail($check['uname'], "Article", $_POST['title']);
        }
        else
        {
            $insertSQL .= ", 1";
        }
        $Result1 = $data->insert_query("patrol_articles", $insertSQL, "", "", false);
        if ($Result1) 
        { 
            echo "<script> alert('Your article has been added.$extra'); window.location = 'index.php?page=mythings';</script>\n";
            exit;
        }
    }
       
    $uname = safesql($uname, "text");
	$quer = $data->select_query("album_track", "WHERE owner=$uname");
    $numalbum = $data->num_rows($quer);
	$albums = array();
	$albums[] = $data->fetch_array($quer);
	while ($albums[] = $data->fetch_array($quer)); 
    
	$tpl->assign('numalbum', $numalbum);
	$tpl->assign('albums', $albums);
	$tpl->assign('editFormAction', $editFormAction);
    $tpl->assign("isedit", "adv");

}
$dbpage = true;
$pagename = "addarticle";
?>