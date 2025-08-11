<?php
/**************************************************************************
    FILENAME        :   patrolarticle.php
    PURPOSE OF FILE :   Displays articles. 
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
location("Patrol Articles", $check["uid"]);
$patrol = isset($_GET['patrol']) ? safesql($_GET['patrol'], "text") : "";
$id = isset($_GET['id']) ? safesql($_GET['id'], "int") : $id = "";

if (!isset($id) || $id == "") 
{
	$showarticle = 'no';
    $patrolarts = array();
    
	$currentPage = $_SERVER["PHP_SELF"];
    if (isset($patrol) && $patrol != '')
        $sql = "WHERE patrol = $patrol AND allowed = 1";
    else
        $sql = "WHERE patrol = 'general' AND allowed = 1";    
	$sql = $data->select_query("patrol_articles", $sql);
	while($patrolarts[] = $data->fetch_array($sql));
	$numarts = $data->num_rows($sql);
    
    if ($numarts == 0 && $patrol != '')
    {
        show_message_back("There are no articles for this patrol yet.");
    }
    elseif ($numarts == 0)
    {
        show_message_back("There are no troop articles.");
    }
    
	if ($numarts == 1) 
    {
        $art = " article";
    } 
    else 
    {
        $art = " articles";
    }

    $tpl->assign("numarts", $numarts);
    $tpl->assign("patrolarts", $patrolarts);
    $tpl->assign("art", $art);
    $tpl->assign("showart", "no");
} 
else 
{
    $highlight = unserialize(stripslashes(html_entity_decode($_GET['highlight'])));

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $tpl->assign('editFormAction', $editFormAction);

	$showarticle = 'yes';
    $sql = $data->select_query("patrol_articles", "WHERE ID=$id AND allowed=1");
    $article = $data->fetch_array($sql);
    
    if (!empty($highlight) && isset($highlight))
    {
        $terms_rx = search_rx_escape_terms($highlight);

        $article['detail'] = search_highlight($article['detail'], $terms_rx);
    }
    
	if (isset($article['album_id'])) 
    { 
			$albumid = $article['album_id'];
			$inarticle = true;
			include_once("photos.php");
	} 
    
    if (isset($_POST['submit']) && $_POST['submit'] == "Post Comment")
    {
        $comment = safesql(strip_tags($_POST['comment']), "text");
        if ($config['confirmcomment'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) $allowed = 0;
        else $allowed = 1;
        $timestamp = time();
        $data->insert_query("comments", "'', $id, '{$check['uname']}', $timestamp, $comment, $allowed", "", "", false);
        
        if ($config['confirmcomment'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
        {
            $page = $_SERVER['PHP_SELF'];
            if (isset($_SERVER['QUERY_STRING'])) 
            {
                $page .= "?" . $_SERVER['QUERY_STRING'];
            }

            publish_mail($check['uname'], "Comment", $_POST['comment']);
            echo "<script> alert('The administrator must first look at the comment and then publish it before it will be shown'); window.location = '$page';</script>\n";
            exit;
        }
    }
     
    $sql = $data->select_query("comments", "WHERE article_id=$id AND allowed = 1");
    $numcom = $data->num_rows($sql);
    $comments = array();
    while ($comments[] = $data->fetch_array($sql));

    $authsql = $data->select_query("auth", "WHERE page='comviewallowed'");
    $authtemp = $data->fetch_array($authsql);
    if (isset($authtemp['level'])) 
    {
        $auths = unserialize($authtemp['level']);
    }
    
    $usergroup = $check['team'];
    if (!isset($usergroup) || $usergroup == '')
    {
        $usergroup = 'guest';
    }
    
    if (isset($auths))
    {
        $comviewallowed = $auths[$usergroup];
    }
    
    if (!$data->num_rows($authsql))
    {
        $comviewallowed=1;
    }

    $authsql = $data->select_query("auth", "WHERE page='compostallowed'");
    $authtemp = $data->fetch_array($authsql);
    if (isset($authtemp['level'])) 
    {
        $auths = unserialize($authtemp['level']);
    }
    
    $usergroup = $check['team'];
    if (!isset($usergroup) || $usergroup == '')
    {
        $usergroup = 'guest';
    }
    
    if (isset($auths)) 
    {
        $compostallowed = $auths[$usergroup];
    }
    if (!$data->num_rows($authsql))
    {
        $compostallowed=1;
    }

    $tpl->assign("numcom", $numcom);
    $tpl->assign("com", $comments);
    $tpl->assign("comviewallowed", $comviewallowed);
    $tpl->assign("compostallowed", $compostallowed);
    $tpl->assign("article", $article);
}

$tpl->assign("patrol", $patrol);
$dbpage = true;
$pagename = "patrolarticle";
?>