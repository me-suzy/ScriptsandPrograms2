<?php
/**************************************************************************
    FILENAME        :   admin_main.php
    PURPOSE OF FILE :   Displays current users. To be expanded to show site stats, etc.
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

$Auth->get_active();

$sql = $data->select_query("onlineusers", "ORDER BY lastupdate DESC");
$onlineuser = array();
$numusers = $data->num_rows($sql);

while($temp = $data->fetch_array($sql))
{
    $sql2 = $data->select_query("authuser", "WHERE uname='{$temp['uname']}'");
    $temp2 = $data->fetch_array($sql2);
    $temp['id'] = $temp2['id'];
    $onlineusers[] = $temp;
};

$tpl->assign("numusers", $numusers);
$tpl->assign("onlineusers", $onlineusers);
$filetouse='admin_main.tpl';
?>