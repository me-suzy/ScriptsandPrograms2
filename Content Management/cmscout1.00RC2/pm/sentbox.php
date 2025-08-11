<?php
/**************************************************************************
    FILENAME        :   sentbox.php
    PURPOSE OF FILE :   Sentbox for Personal Messengers
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

$sql = $data->select_query("pms", "WHERE fromuser='$username' AND type=2 ORDER BY date DESC");
$numpm = $data->num_rows($sql);
$inboxpm = array();
while($inboxpm[] = $data->fetch_array($sql));

$tpl->assign("pm", $inboxpm);
$tpl->assign("numpm", $numpm);
$tpl->assign("onpage", "Sentbox");
$pagenum = 1;
?>