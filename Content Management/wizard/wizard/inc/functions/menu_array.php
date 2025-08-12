<?php
/*  Menu Array Generator
	(c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    Derive the tree structure from a MySQL table with parent-child relationships. With thanks to Ratface.
	Returns an array with the page id, page title, parent id, server file name, menu status, group permission level
	Example: Home Page
	   Key [0] => Value [ 1 ]   
       Key [id] => Value [ 1 ]   = Page id
       Key [1] => Value [ Home ]  
       Key [title] => Value [ Home ]  = Page title
       Key [2] => Value [ 0 ]
       Key [parentId] => Value [ 0 ]   = Parent id
       Key [3] => Value [ newpage.html ]
       Key [filename] => Value [ newpage.html ]  = Server filename
       Key [4] => Value [ on ]
       Key [menu] => Value [ on ]      = Include or not in menu
       Key [5] => Value [ 4 ]
       Key [permit] => Value [ 4 ]      = Permission level for page
*/

function result_array($menutable){



	$db = new DB();
	$db->query("SELECT id,title,parentId,filename,menu,permit FROM ". DB_PREPEND . $menutable . " WHERE menu='on' AND admin='0' ORDER BY parentId, position ASC");
	$i = 0;

	
	while ($result_row = $db->next_record()){
	$result[$i] = $result_row;
    	$i++;
	} //while
	$db->close();
    return $result;    

} //function



function step_up ($parent, $startSeed, $depth) {
    global $result_array;
    global $arr_size;
	
	    
    if ($startSeed > $arr_size) {
        return;
    } else {
        
        if ($result_array[$startSeed-1][2] == 
           $result_array[$startSeed][2]) {
              $depth--;
              return find_child($result_array[$startSeed-1][2],
                    $startSeed, $depth);
            
        
        } else {
            if ($result_array[$startSeed-1][2] == "" && 
                $result_array[$startSeed][2] == 1) {
                return;
            }
            
            for ($j = 0; $j <= $arr_size; ++$j) {
                if ($result_array[$j][0] ==
                  $result_array[$startSeed-1][2]) {
                    $depth--;
                    return
                    step_up($result_array[$j+1][0], $j+1, $depth);
                }
            }
            return;
        }
    
}
}


function find_child ($parent, $startSeed, $depth) {
    global $result_array;
    global $arr_size;
	global $menu_array;
    
    for ($k = $startSeed; $k <= $arr_size; ++$k) {
        
        if ($result_array[$k][2] == $parent) {
            
			//correction for Home item
			if ($menu_array[0][0]) {
			    $menu_array[0][0] = "0";
			}
            
			$menu_array[] = array($depth, $result_array[$k][0], $result_array[$k][1], $result_array[$k][2], $result_array[$k][3],$result_array[$k][4],$result_array[$k][5],$result_array[$k][6]);
            
			
            
            $parent = $result_array[$k][0];
            $startSeed = ++$k;
            $depth++;
            
            return find_child($parent, $startSeed, $depth);
            
        } elseif ($result_array[$k][2] > $parent) {
            
            break;    
        }
    }
    
    
    step_up ($parent, $startSeed, $depth);
}
?>