<?php
/**************************************************************************
    FILENAME        :   admin_registerinfo.php
    PURPOSE OF FILE :   Displays new users from the troop
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
	$module['User Management']['New Troop Users'] = "registerinfo";
    $permision['New Troop Users'] = 1;
	return;
}
if ($level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$action = $_GET['action'];
if ($action == "delete")
{
    $username = $_GET['username'];
    $sql = $data->delete_query("registerinfo", "uname='$username'", "Delete registration info", "$username\\'s info deleted");
    if($sql)
    {
        echo "<script> alert('The registration information has been deleted'); window.location = '$pagename';</script>\n";
        exit;   
    } 

}

$row = array();
$record = array();

$result = $data->select_query("registerinfo", "ORDER BY uname");
$numusers = $data->num_rows($result);
while ($temp = $data->fetch_array($result))
{
    $sql = $data->select_query("authuser", "WHERE uname='{$temp['uname']}'");
    $temp2 = $data->fetch_array($sql);
    $temp['id'] = $temp2['id'];
    $row[] = $temp;
}

$filetouse = "admin_registerinfo.tpl";
$tpl->assign('numusers', $numusers);
$tpl->assign('editFormAction', $editFormAction);
$tpl->assign('row', $row);
$tpl->assign('record', $record);
?>	