<?php
/**************************************************************************
    FILENAME        :   admin_users_view.php
    PURPOSE OF FILE :   Displays a users details
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
	return;
}

if ($level != 4 && $level != 3 && $level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}
$id = $_GET['id'];

$user_query = $data->select_query("authuser", "WHERE id=$id");
$users = $data->fetch_array($user_query);

$records_query = $data->select_query("records", "WHERE uname='{$users['uname']}'");
$record = $data->fetch_array($records_query);

$action = "view";

if (!$user_query) 
{
        echo "<script> alert('User is non existent'); window.location = '$pagename';</script>\n";
        exit; 
}

$tpl->assign('uinfo', $users);
$tpl->assign('details', $record);
$tpl->assign('action', $action);
$filetouse = "admin_users.tpl";
?>