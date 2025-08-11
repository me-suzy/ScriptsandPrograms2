<?php
/**************************************************************************
    FILENAME        :   mypatrol.php
    PURPOSE OF FILE :   Displays a list of users in current users group. Allows users to email and private message each other.
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

if (isset($_GET['action'])) $action = $_GET['action'];
$message = "";
$pagenum = 1;

if ($check['level'] == 2 || $check['level'] == 1 || $check['level'] == 0)
{
    $sql = $data->select_query("authuser");
}
else
{
    $sql = $data->select_query("authuser", "WHERE team='{$check['team']}' OR level=0 OR level=1");
}
$nummem = $data->num_rows($sql);

$patrollist = array();
$i = 0;
while($temp=$data->fetch_array($sql))
{
    $usersql = $data->select_query("records", "WHERE uname='{$temp['uname']}'");
    $tempdetails = $data->fetch_array($usersql);
    $patrollist[$i]['id'] = $tempdetails['id'];
    $patrollist[$i]['uname'] = $tempdetails['uname'];
    $patrollist[$i]['firstname'] = $tempdetails['firstname'];
    $patrollist[$i]['lastname'] = $tempdetails['lastname'];
    $i++;
}


if ($action == "pmall")
{
    $pagenum = 2;
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    if ($check['level'] != 2 && $check['level'] != 1 && $check['level'] != 0) {
        $patrol = $check['team'];
        $result = $data->select_query("authuser", "WHERE team='$patrol' AND uname != '{$check['uname']}'");
    } else {
        $result = $data->select_query("authuser", "WHERE uname != '{$check['uname']}'");
    }
    $row = $data->fetch_array($result);
    $emailadds = $row['uname'];
    while($row = $data->fetch_array($result))
    {
        $emailadds .= ',' .$row['uname'];
    }
    echo "<script>window.location='index.php?page=pmmain&action=typepm&user=$emailadds';</script>";
    exit;
}

$tpl->assign("nummem", $nummem);
$tpl->assign("patrollist", $patrollist);
$tpl->assign("username", $check['uname']);
$dbpage = true;
$pagename = "mypatrol";
?>

