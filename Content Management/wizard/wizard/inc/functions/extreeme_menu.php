<?php
/*
	Extreeme menu data generator
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
*/
/* 	This function creates a string formatted for the Xtreeme menu system
* 	String contains $section for formatting the link 
* 	menu_array has four keys:
* 	Key [0] => Value [ Array ]  page record (e.g. Home page)
* 		Key [0] => Value [ 0 ]  	level
*		Key [1] => Value [ 1 ]  	link id
*		Key [2] => Value [ 2 ] 	    title
*		Key [3] => Value [ 3 ]  	parent id
*/


function xtreeme_menu(&$menu_array) {

$item = 0;
$keycount = 0; // record key (used to store id)

foreach ($menu_array as $value) {

    //create variables
	$title = $menu_array[$keycount][2]; // page title of the current key
	$link = $menu_array[$keycount][1];  //page id of the current key
	unset($suffix);
    
	$level = $menu_array[$keycount][0];
	
	//if this is a toplevel menu item, empty $prefix_array
	if ($level == "0" ) {
	
				if (isset($prefix_array)) { 
				    //unset each key but leave array intact
					foreach ($prefix_array as $i => $value) {
    					unset($prefix_array[$i]);
					}
					
				 } 
				
			  
    }

   //check if current key has a child
	$thisid = $menu_array[$keycount][1];
	$nextparentid = $menu_array[$keycount+1][3];

	if ($thisid == $nextparentid ) {
	    $haschild = "1";
	}
	else { $haschild = "x"; }

    //top level and has child
  	if ($level == "0" && $haschild == "1") { 
	    $item++;
		$prefix_array[] = 0;
	    $parta .=  '$e_' . $item . '=SSAddTopLevelMenu("' . $title . '", "$section" .  "' .  $link . '" , "' .  $emptystring . '");';
		$parta .= "\n";
	} //the key does not have a child

	//toplevel and no child
    elseif ($level == "0" && $haschild != "1") {
	    $title = $menu_array[$keycount][2];
		$parta .= 'SSAddMenuItem("' . $title . '", "$section" .  "' .  $link . '"  , "" , null);';
		$parta .= "\n";
	}

 		
	//if item has a child 
    elseif ($haschild == "1") {
	        //get suffix from previous level
	        $count = 0; 
				while($count < $level ){
				    if ($count == 0) {
				       //don't count first level 
				    }
					else {
					$suffix = $suffix . "_"  . $prefix_array[$count];
					}
					$count++;
				} // while
				        
			//if there is a value at this level increment else it is a new level
			if (count($prefix_array)== $level) {
			    //add 1 to the count at this level
				$newvalue = $prefix_array[$level] + 1;
				$prefix_array[$level] = $newvalue;
			}
			else {
			    $prefix_array[] = 1;
			}
			
			
			$prefix = $suffix . "_" . $prefix_array[$level];
			
        	$partb .= '$e_' . $item . $prefix . '=SSAddSubMenu("'. $title .'", "$section" .  "' .  $link . '"  , "",' . '$e_'. $item . $suffix . ');';
			$partb .= "\n";
    	} // has child
	
	   
	//the item has no child so no prefix, suffix comes from parent at previous level
   else {
   				
				//get suffix from previous level
	        	$count = 0; 
				while($count < $level ){
				    if ($count == 0) {
				       //don't count first level 
				    }
					else {
					$suffix = $suffix . "_"  . $prefix_array[$count];
					}
					$count++;
				} // while
				
	
  			$partb .= 'SSAddMenuItem("'. $title .'", "$section".  "' .  $link . '"  , "",' . '$e_'. $item . $suffix . ');';
			$partb .= "\n";
    	}
  
  		
	
	$keycount++;
  
} //foreach $menu_array as $value
  


$partb = $parta . $partb;

return $partb;

} //function end

?>