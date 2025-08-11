<?php
/**************************************************************************
    FILENAME        :   admin_patrol.php
    PURPOSE OF FILE :   Displays patrols and gives access to patrol content manager and menu manager
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
	$module['User Content Management']['Group Sites'] = "patrol";
    $permision['Group Sites'] = 4;
	return;
}

if ($level != 4 && $level != 3 && $level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}
$action = $_GET['action'];
if ($level != 0 && $level != 1 && $level != 2) 
{
 	$patrols = $check['team'];
	$patrol_query = $data->select_query("authteam", "WHERE teamname='$patrols' AND ispatrol = 1");
} 
else 
{
 	$patrol_query = $data->select_query("authteam", "WHERE ispatrol = 1");
}
if ($action == "") 
{
	$numpatrols = $data->num_rows($patrol_query);
	$patrol = array();
	$patrol[] = $data->fetch_array($patrol_query);
	while  ($patrol[] = $data->fetch_array($patrol_query));
}

$tpl->assign('patrol', $patrol);
$tpl->assign('patrolInfo', $patrolInfo);
$tpl->assign('action', $action);
$tpl->assign('numpatrol', $numpatrols);
$filetouse = "admin_patrol.tpl";
?>