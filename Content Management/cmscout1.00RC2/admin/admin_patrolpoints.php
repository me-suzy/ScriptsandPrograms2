<?php
/**************************************************************************
    FILENAME        :   admin_patrolpoints.php
    PURPOSE OF FILE :   Manages points
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
	$module['Troop Content Management']['Patrol Points'] = "patrolpoints";
    $permision['Patrol Points'] = 4;
	return;
}

if ($level != 4 && $level != 3 && $level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$edit = true;
if ($level != 3 && $level != 4) 
{
    $what = $_POST['Submit'];
	if ($what == "Submit") 
    {
        $sql = $data->select_query("patrolpoints");
        while($temp=$data->fetch_array($sql))
        {
            $tpoint = safesql($_POST[$temp['ID']], "int");
            $up = $data->update_query("patrolpoints", "Points=$tpoint", "Patrolname='{$temp['Patrolname']}'", "Patrol points", "Updated points for $i");
        }
	}
} 
else 
{
 $edit = false;
}

$points_qu = $data->select_query("patrolpoints");
$numpoints = $data->num_rows($points_qu);
$points = array();
while ($points[] = $data->fetch_array($points_qu));

$tpl->assign('points', $points);
$tpl->assign('edits', $edit);
$tpl->assign('editFormAction', $editFormAction);
$tpl->assign('numpoints', $numpoints);
$filetouse = "admin_patrolpoints.tpl";
?>