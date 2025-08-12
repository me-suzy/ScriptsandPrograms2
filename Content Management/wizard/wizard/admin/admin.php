<?php

/*  
   Admin index page.
   (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.
*/

//authentication check
	include ("admin_auth.php");
//load the main tab items from the database
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "admin WHERE parentId=0 AND visible=1 ORDER BY 'position' ");

?><div id="tabbed">
<table width="100%"><tr><td>
<ul>
<?php 

$item = $_GET[item];
    // get 1st level menus from database
	while($adminMenu = $db->next_record()){
	if ($item == $adminMenu[id]) {
    	echo "<li id=\"current\" ><a href=\"admin.php?id=2&item=$item&sub=$sub\">$adminMenu[title]</a></li>"; 
		$showsub = $adminMenu[id];  
		}
	else {

    	echo "<li ><a href=\"admin.php?id=2&item=$adminMenu[id]&sub=$sub\">$adminMenu[title]</a></li>";
	    
	}
    } // while
	echo "</ul></td></tr></table></div>";

	// if there is a 1st level menu iterm currently active, show its children

	if ($showsub) {
	    $dbsub = new DB();
		$dbsub->query("SELECT * FROM ". DB_PREPEND . "admin WHERE parentId=$showsub AND visible=1 ORDER BY 'position' ");
   		$count = $dbsub->num_rows();
	
		if ($count > 0) {
		    $sub = $_GET[sub];
			echo "<div id=\"tabbedsub\">";
			echo "<table width=\"100%\"><tr><td>";
			echo"<ul id=\"subul\" >";
				while($subthis = $dbsub->next_record()){
			
				if ($sub == $subthis[id]) {
			    	echo "<li id=\"subactive\"><a href=\"admin.php?id=2&item=$item&sub=$subthis[id]\" >$subthis[title]</a></li>";
					//set up its admin folder and php filename location
					$pagename = $subthis[pageName];
					$category = $subthis[category];
				}
				else{
			    	echo "<li id=\"subcurrent\"><a href=\"admin.php?id=2&item=$item&sub=$subthis[id]\">$subthis[title]</a></li>";
					}
				
				} // while
			echo "</ul></td></tr></table></div>";
			}// if count
	
	     } //if showsub

// if a submenu choice is currently active, load its php file, whose location is stored in the admin menu table
if ($pagename) {
   $pagename = CMS_ROOT . "/admin/" . $category. "/" . $pagename;
	echo "<div>";
	include "$pagename";
	echo "</div>"; 
}		 
else
{
echo "<div>";
echo "<table border=\"1\"  bgcolor=\"#f8f8ff\" cellpadding=\"5\" style=\"border-collapse: collapse\"   bordercolor=\"#E5E5E5\" width=\"100%\">";
echo "<tr><td>";
$message = $_GET['message'];
if ($message) {
    echo "<center><p class=\"message\">$message</p></center>";
}
echo "<center><p><img border=\"0\" src=\"admin/images/admin_icon.gif\" width=\"51\" height=\"45\"></p></center>";
echo "</td></tr></table>";
echo "</div>";
}?>