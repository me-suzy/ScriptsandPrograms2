<?php
/*
	Menu generation for Suckerfish menu system
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

*   $leftMenu[$count][0];  level beginning at zero
*   $leftMenu[$count][1];  page id
*   $leftMenu[$count][2];  page title
*	$leftMenu[$count][3];  parent id
*   $leftMenu[$count][4];  server filename
*   $leftMenu[$count][5];  include (on) or not (off) in menu
*   $leftMenu[$count][6];  group permission level
*/


// page and menu data 
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "menuData WHERE id=2 ");
	$i = $db->next_record();
	$leftMenu = unserialize($i[serialized]);
	
//include 'dump.class.php';
//$dumparray = new dump();
//echo $dumparray->dump($leftMenu); 


// check to see if the top menu has been set (has been assigned one level or more from $leftMenu)


echo "<ul id=\"vnav\">";
$count = 0;

//iterate through the menu data file
   foreach ($leftMenu as $v1) {
          
	  	//check if this page can be seen by visitor or member
	  		if ($id == $leftMenu[$count][1]) {
	     		//if this is not a public page check the user's group membership
	     		if ( $leftMenu[$count][6] != 4 ){
		        	//check to see if visitor is member of restricted group
		    		if (!is_memberof($page[permit]))  {
	    				$location = CMS_WWW . "/templates/forms/notpermitted.php";
						header("Location: $location");
						exit;
						}  
		 		} 
	  		} // if this is a public page
	  
	  echo "<li><a ";
	  if (($leftMenu[$count+1][0] > $leftMenu[$count][0]) && ($leftMenu[$count][0] != 0)) {echo "class=\"sublev\"";}   //applies sublevel styling
	  echo "href=\"".CMS_WWW."/pages/". $leftMenu[$count][4] . "\">".$leftMenu[$count][2]."</a>"; 
 
	  //now determine closing tags
	  if ($leftMenu[$count+1][0] > $leftMenu[$count][0])
	     { echo "<ul>"; }
	  
	  elseif($leftMenu[$count+1][0] == $leftMenu[$count][0])  // next item is at same level so close tag
	  	{echo "</li>";}
	  elseif (!isset($leftMenu[$count+1][0]) || $leftMenu[$count+1][0] < $leftMenu[$count][0]) //next is end of array or one level up
	     { 
		     echo "</li>";	 
			 
		    $leveldepth = $leftMenu[$count][0] - $leftMenu[$count+1][0];
			$leveldepth = abs($leveldepth) ;
			while ($leveldepth > 0 )
						{
				    		echo "</ul></li>";
							$leveldepth--;
						}
		 }   //it is up one level  
		 
		 else { echo "There is an error in the pages table hierarchy."; };

 $count++; 
} // foreach
	   
   


echo "</ul>";
?>