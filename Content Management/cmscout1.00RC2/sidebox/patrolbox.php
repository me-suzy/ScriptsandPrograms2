<?php
/**************************************************************************
    FILENAME        :   patrolbox.php
    PURPOSE OF FILE :   Sidebox: Shows links to all patrol sites
    LAST UPDATED    :   01 November 2005
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
?><?php
//Patrol menu
if ($config['patrolpage'] == 1) 
{
    $patrolsql = $data->select_query("authteam", "WHERE ispatrol=1 ORDER BY teamname ASC");
    while ($temp = $data->fetch_array($patrolsql))
    {
        $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 2;
        $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $temp['teamname'];
        $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = "index.php?page=patrolpages&amp;patrol={$temp['teamname']}";
        $itemnum++;
    }
}
?>