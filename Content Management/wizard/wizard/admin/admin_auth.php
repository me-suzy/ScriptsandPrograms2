<?php

if (!user_isloggedin()) {
	    $message = NOTLOGGED; //You are not logged in
        $location = CMS_WWW . "/templates/forms/login.php?message=$message&pageid=2";
		header("Location: $location");
		exit(); 
    }
	if (!is_memberof(2)) {
	    
	    $message = ADMINONLY; //access restricted to administrators
		echo "<tr><td><table border=\"1\" width=\"100%\" bgcolor=\"#FFFFFF\"><tr><td><p>&nbsp;</p><p align=\"center\"><img alt=\"restricted\" src=\"admin/images/restricted.gif\" height=\"32\" width=\"32\" /></p><p align=\"center\">$message</p></td></tr></table>";   
		exit();
	}
?>