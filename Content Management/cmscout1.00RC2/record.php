<?php
/**************************************************************************
    FILENAME        :   record.php
    PURPOSE OF FILE :   Displays users scouting record
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
location("Viewing Scout Record", $check["uid"]);
/********************************************Check if user is allowed*****************************************/
if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
} 

$message = "";
if (!$error) 
{
	$uname = safesql($check["uname"], "text");
    $sql = $data->select_query("records", "WHERE uname='{$check['uname']}'");
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
            $sql = $data->select_query("scoutrecord", "WHERE requirement = {$temp2['ID']} AND userid={$check['id']}");
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
	
	$badgesql = $data->select_query("badges", "WHERE userid = '{$check['id']}'");
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
}

$dbpage = true;
$pagename='record';
?>                            