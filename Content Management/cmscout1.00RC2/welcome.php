<?php
/**************************************************************************
    FILENAME        :   welcome.php
    PURPOSE OF FILE :   Builds the frontpage
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
location("Index", $check["uid"]);
$level = $check['level'];

$frontsql = $data->select_query("frontpage", "ORDER BY pos ASC");

$content = "";
while ($item = $data->fetch_array($frontsql))
{
  if ($item['page'] != 'none') 
  {
    $authsql = $data->select_query("auth", "WHERE page='{$item['page']}'");
    $authtemp = $data->fetch_array($authsql);
    if (isset($authtemp['level'])) $auths = unserialize($authtemp['level']);
    
    $usergroup = $check['team'];
    if (!isset($usergroup) || $usergroup == '')
        $usergroup = "Guest";
    
    if (isset($auths)) $userauth = isset($auths[$usergroup]) ? $auths[$usergroup] : 1;
    if (!$data->num_rows($authsql))
    {
        $userauth=1;
    }
    
    if ($userauth==1)
    {
        $pagesql = $data->select_query("static_content", "where name = '{$item['page']}'");
        $stuff = $data->fetch_array($pagesql);
        $content .= $stuff['content'];
    }
  } 
  elseif ($item['function'] != 'none') 
  {
    $funsql = $data->select_query("functions", "where name = '{$item['function']}'");
    $stuff = $data->fetch_array($funsql);
    
    $authsql = $data->select_query("auth", "WHERE page='{$stuff['code']}'");
    $authtemp = $data->fetch_array($authsql);
    if (isset($authtemp['level'])) $auths = unserialize($authtemp['level']);
    
    $usergroup = $check['team'];
    if (!isset($usergroup) || $usergroup == '')
        $usergroup = "Guest";
    if (isset($auths)) $userauth = ($auths[$usergroup] == 1) && (isset($auths[$usergroup])) ? 1 : 0;
    if (!$data->num_rows($authsql))
    {      
       $userauth=1;
    }
    if ($userauth==1)
    {    
        if ($stuff['type'] == 1)
        {
            $content .= $stuff['code'];
        } 
        elseif ($stuff['type'] == 2)
        {
            if (file_exists($stuff['code'] . $phpex)) 
            {
                include($stuff['code'] . $phpex);
            }
            
            if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage") 
            {
                $content .= get_temp($pagename, $pagenum);
            }  
        }
    }
  }
}

if ($content == "")
{
    $content = "No frontpage defined";
}
$dbpage = true;
$pagename='frontpage';
$helpid = 1;
?>