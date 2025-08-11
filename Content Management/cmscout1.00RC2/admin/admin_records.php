<?php
/**************************************************************************
    FILENAME        :   admin_records.php
    PURPOSE OF FILE :   Manages users records
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
	return;
}

if ($level != 3 && $level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$action = $_GET['action'];
$what = $_POST['what'];
$id = $_GET['id'];
if ($action == "addreq") 
{
     $item = $_GET["itemid"];
     $comment = $_GET["comment"];
     if ($comment == "undefined") $comment = "";
     $comment = safesql($comment, "text");
          if ($comment == null) $comment = "";

     $bla = $data->num_rows($data->select_query("scoutrecord", "WHERE userid=$id AND requirement='$item'"));
     if ($item != '' && isset($item) && $bla == 0) 
     {
        $sql = $data->insert_query("scoutrecord", "'$id', $item, $comment", "Records", "$item is now complete for $uname");
        if($sql)
        {
            $advancement = $_GET['place'];
            echo "<script> alert('Item is now marked as complete'); window.location = '$pagename&id=$id&action=edit#$advancement';</script>\n";
            exit; 
        }
     }
} 
elseif ($action == "reqremove") 
{
     $item = $_GET["itemid"];
     $bla = $data->delete_query("scoutrecord", "userid='$id' AND requirement='$item'", "Records", "$item is now incomplete for $uname");
     if ($bla) 
     {
        $advancement = $_GET['place'];
        echo "<script> alert('Item is now marked as incomplete'); window.location = '$pagename&id=$id&action=edit#$advancement';</script>\n";
        exit; 
     }
} 
elseif ($action == "badge") 
{
     $badgename = safesql($_GET["badge"], "text");
     $date = safesql($_GET["date"], "date");
     $bla = $data->num_rows($data->select_query("badges", "WHERE userid='$id' AND badge=$badgename"));
     if ($badgename != '' && isset($badgename) && $bla == 0) 
     {
        $sql = $data->insert_query("badges", "'', '$id', $badgename, $date", "Records", "$unames add $badgename badge");
        if($sql)
        {
            echo "<script> alert('Badge added'); window.location = '$pagename&id=$id&action=edit#badges';</script>\n";
            exit; 
        }
     }
}
elseif ($action == "adv") 
{
     $id = $_GET["id"];
     $comment = $_GET["comment"];
     if ($comment == "undefined") $comment = "";
     $comment = safesql($comment, "text");
     if ($comment == null) $comment = "";
     $recsql = $data->select_query("requirements", "WHERE advancement = $id");
     $ok = true;
     while ($temp = $data->fetch_array($recsql)) 
     {
        $item = $temp['ID'];
        $bla = $data->num_rows($data->select_query("scoutrecord", "WHERE userid='$id' AND requirement='$item'"));
        if ($item != '' && isset($item) && $bla == 0) 
        {
            $sql = $data->insert_query("scoutrecord", "'$id', $item, $comment", "Administration", "$item is now complete for $uname");
            $ok = $sql && $ok ? true : false;
        }
    }
    if($ok)
    {
        echo "<script> alert('Advancement is now marked as complete'); window.location = '$pagename&id=$id&action=edit#$id ';</script>\n";
        exit; 
    }
}

$sql = $data->select_query("authuser", "WHERE id='$id'");
$row = $data->fetch_array($sql);
$username = $row['uname'];
$sql = $data->select_query("records", "WHERE uname='{$row['uname']}'");
$row = $data->fetch_array($sql);

$advansql = $data->select_query("advancements", "WHERE scheme = {$row['scheme']} ORDER BY position ASC");
$numadva = $data->num_rows($advansql);
$advancements = array();
$numitems = 0;
while ($temp = $data->fetch_array($advansql)) 
{
    $getrequirements = $data->select_query("requirements", "WHERE advancement = '{$temp["ID"]}' ORDER BY position ASC");
    $temp['numitems'] = $data->num_rows($getrequirements);
    while ($temp2 = $data->fetch_array($getrequirements))
    {
        $sql = $data->select_query("scoutrecord", "WHERE requirement = {$temp2['ID']} AND userid='$id'");
        if ($data->num_rows($sql) > 0)
        {
            $temp2['done'] = 1;
            $temp3 = $data->fetch_array($sql);
            $temp2['comment'] = $temp3['comment'];
        }
        else
        {
            $temp2['done'] = 0;
            $temp2['comment'] = '';
        }
        $temp['items'][] = $temp2;
    }
    $advancements[] = $temp;
}
$tpl->assign("advan", $advancements);
$tpl->assign("numadva", $numadva);

$badgesql = $data->select_query("badges", "WHERE userid = '$id'");
$numbadge = $data->num_rows($badgesql);
$badges = array();
$badges[] = $data->fetch_array($badgesql);
while ($badges[] = $data->fetch_array($badgesql));

$tpl->assign("badges", $badges);
$tpl->assign("numbadge", $numbadge);
$tpl->assign("comment", $comment);
$tpl->assign("done", $done);
$tpl->assign("req", $req);
$tpl->assign("prevnum", $prevnum);
$tpl->assign("records", 1);
$tpl->assign("username", $username);
$back = isset($_GET['back']) && $_GET['back'] == "view" ? "users_view" : "user_edit";
$tpl->assign("back", $back);

$tpl->assign('editFormAction', $editFormAction);
$tpl->assign("userid", $id);
$tpl->assign("action", $action);
$filetouse='admin_records.tpl';
?>