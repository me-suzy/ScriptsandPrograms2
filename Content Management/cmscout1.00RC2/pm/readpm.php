<?php
/**************************************************************************
    FILENAME        :   readpm.php
    PURPOSE OF FILE :   Shows a personal message
    LAST UPDATED    :   12 August 2005
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

$username = $check['uname'];
$pid = $_GET['id'];

$sql = $data->select_query("pms", "WHERE id=$pid");
$pm = $data->fetch_array($sql);

if (($pm['touser'] == $check['uname'] && ($pm['type'] != 4 && $pm['type'] != 2)) || ($pm['fromuser'] == $check['uname'] && ($pm['type'] == 4 || $pm['type'] == 2)))
{
    if ($pm['type'] == 4)
    {
        $tpl->assign("drafts", 1);
    }
    elseif ($pm['type'] == 2)
    {
        $tpl->assign("sentbox", 1);
    }
    elseif ($pm['type'] == 1 && ($pm['new'] == 1 || $pm['read'] == 0))
    {
        $data->update_query("pms", "readpm='1', newpm='0'", "id='$pid'", "", "", false);
    }
    $tpl->assign("pm", $pm);
}
else
{
    error_message("You are not allowed to view other users personal messages");
}
$pagenum = 2;
?>