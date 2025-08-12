<?php
/*
	Menu Generation for the Admin Suckerfish Menu
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

*	Menu generation for Suckerfish menu system
*	Copyright 2006 Philip Shaddock www.ragepictures.com
*   $topMenu[$count][0];  level beginning at zero
*   $topMenu[$count][1];  page id
*   $topMenu[$count][2];  page title
*	$topMenu[$count][3];  parent id
*   $topMenu[$count][4];  server filename
*   $topMenu[$count][5];  include (on) or not (off) in menu
*   $topMenu[$count][6];  sitemap
*   $topMenu[$count][7];  group permission level
*/


// page and menu data 
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "menuData WHERE id=0 ");
	$i = $db->next_record();
	$topMenu = unserialize($i[serialized]);
	
//include 'dump.class.php';
//$dumparray = new dump();
//echo $dumparray->dump($topMenu); 


// check to see if the top menu has been set (has been assigned one level or more from $topMenu)


echo "<ul id=\"nav\">";
$count = 0;

//iterate through the menu data file
   foreach ($topMenu as $v1) {
         
	  
	  echo "<li><a ";
	  if (($topMenu[$count+1][0] > $topMenu[$count][0]) && ($topMenu[$count][0] != 0)) {echo "class=\"sublev\"";}   //applies sublevel styling
	  echo "href=\"".CMS_WWW. $section . $topMenu[$count][1] . "\">".$topMenu[$count][2]."</a>"; 
 
	  //now determine closing tags
	  if ($topMenu[$count+1][0] > $topMenu[$count][0])
	     { echo "<ul>"; }
	  
	  elseif($topMenu[$count+1][0] == $topMenu[$count][0])  // next item is at same level so close tag
	  	{echo "</li>";}
	  elseif (!isset($topMenu[$count+1][0]) || $topMenu[$count+1][0] < $topMenu[$count][0]) //next is end of array or one level up
	     { 
		     echo "</li>";	 
			 
		    $leveldepth = $topMenu[$count][0] - $topMenu[$count+1][0];
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