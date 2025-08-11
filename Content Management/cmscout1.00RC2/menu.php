<?php
/**************************************************************************
    FILENAME        :   menu.php
    PURPOSE OF FILE :   Builds the menu
    LAST UPDATED    :   11 October 2005
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
//Build menu
$menu = array();
$menu['left'] = array();
$menu['right'] = array();
$menu['top'] = array();

if (!$config['disablesite'])
{
    $catnum['left'] = 0;
    $catnum['right'] = 0;
    $catnum['top'] = 0;
    
    //Left Hand Menu
    $catsql = $data->select_query("menu_cats", "ORDER BY Position ASC");
    if ($data->num_rows($catsql) > 0) 
    {
        while ($menucats = $data->fetch_array($catsql))
        {
            $side = $menucats['side'];
            if ($menucats['showwhen'] == 0 || ($menucats['showwhen'] == 1 && $check['uname'] != "Guest" && $check['level'] != -1) || ($menucats['showwhen'] == 2 && $check['uname'] == "Guest" && $check['level'] == -1))
            {
                $menu[$side][$catnum[$side]]['name'] = $menucats['name'];
                $menu[$side][$catnum[$side]]['showhead'] = $menucats['showhead'];

                $itemsql = $data->select_query("menu_items", "WHERE cat = '{$menucats['id']}' ORDER BY pos ASC");
                $itemnum = 0;
                
                if ($data->num_rows($itemsql) > 0) 
                {                       
                    while ($items = $data->fetch_array($itemsql))
                    {
                        switch($items['type'])
                        {
                            case 1:
                                $itemtype="static";
                                break;
                            case 2:
                                $itemtype="dynamic";
                                break;
                            case 3:
                                $itemtype="box";
                                break;
                            case 4:
                                $itemtype="subsite";
                                break;
                            case 5:
                                $itemtype="url";
                                break;
                            default:
                                $itemtype="none";
                                break;
                        }
                        
                        if ($itemtype == "static") 
                        {
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 1;
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = $items['item'];
                        }
                        elseif ($itemtype == "dynamic") 
                        {
                            $t = $data->fetch_array($data->select_query("functions", "WHERE name = '{$items['item']}'"));
                            
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 1;
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = $t['code'];
                        } 
                        elseif($itemtype == "box")
                        {
                            $t = $data->fetch_array($data->select_query("functions", "WHERE name = '{$items['item']}'"));
                            if ($t['filetouse'] != "" && file_exists("sidebox/{$t['filetouse']}".$phpex))
                            {
                                include_once("sidebox/{$t['filetouse']}".$phpex);
                            }
                            
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 4;
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = $t['code'];
                        }
                        elseif ($itemtype == "url") 
                        {
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 3;
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = $items['url'];
                        }
                        elseif ($itemtype == "subsite")
                        {
                            $subsite = $items['item'];
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 2;
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = "index.php?page=subsite&amp;site={$items['item']}";
                        }
                        else 
                        {
                            $itemnum--;
                        }
                        $itemnum++;
                    } //End Item While
                } //End Item If
                
                $menu[$side][$catnum[$side]]['numitems'] = $itemnum;
                $catnum[$side]++;
            } //End If show cat
        } //End Cat While
    } //End Cat If
}

if ($config['disablesite'] || ($catnum['left'] == 0 && $catnum['right'] == 0 && $catnum['top'] == 0))
{  
    $t = $data->fetch_array($data->select_query("functions", "WHERE name = 'Logon Box'"));
    if ($t['filetouse'] != "" && file_exists("sidebox/{$t['filetouse']}".$phpex))
    {
        include_once("sidebox/{$t['filetouse']}".$phpex);
    }
    $menu['left'][0]['name'] = 'Login';
    $menu['left'][0]['showhead'] = 1;
    $menu['left'][0]['items'][0]['type'] = 4;
    $menu['left'][0]['items'][0]['name'] = $items['name'];
    $menu['left'][0]['items'][0]['link'] = $t['code'];
    $menu['left'][0]['numitems'] = 1;
    $catnum['left']=1;
}
$tpl->assign('menu', $menu);
$tpl->assign('nummenucats', $catnum);

?>