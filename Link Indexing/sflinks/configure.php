<?php

//SFLinks script by Valerie of spoken-for.org and unrighteous.org
//By using this script you agree to link either of those sites from somewhere in your site - either right from where
//you are using the script or from a links page or from the domain page, etc.  I am not "forcing" a link on you -
//meaning that no link will be displayed automatically with where your random links are displayed - so I expect this
//small request of mine to be respected.  Thanks!!!

//This is the configuration script, all information you need to install this should be included here.
//If you have further questions, please do not hesitate to ask.  I can be reached at webmaster@spoken-for.org or by
//hitting that site or unrighteous.org

//For installation, enter in the requested info in between the "" or '' - do not enter it in place of the $value
//This is a comment, comments are preceeded by the slashes and will tell you what to do.
//See below customization for more information on various things

////// START CUSTOMIZING HERE //////
	//Enter in your database variables below.
	//Change your_database to your database name, note that most hosts will proceed it with your username and a _
	//Change your_username to the username that you have linked to that database.  Make sure it has all permissions.
	//Change your_password to the password the above username uses
	//Change localhost only if you know what you're doing and need to.  Chances are you won't have to.
		mysql_connect ('localhost', 'your_username', 'your_password') ;
		mysql_select_db ('your_database');

	//Enter here the username and password you would like to use for entering the adminstration panel
		$admin_password = "password";

	//Enter here the number of links you want to display at any one time.  Be sure that you have at least this many
	//links in the database at all times!
		$numlinks = "10";
		
	//What you would like displayed before and after each link.  Examples: <li></li> and <p></p> or <br>, etc.
	//Do not enter any double quotes ("), use only single quotes if required (').  Leave blank (nothing between the
	//quotes if unneeded.
		$before = "<li>";
		$after = "</li>";
		
	//Use images instead of text links?  Answer yes or no.
		$imagelinks = "no";
	//Enter the FULL path of your image folder, including the trailing slash
		$imagefolder = "http://spoken-for.org/scripts/spokenfor/sflinks/images/";
	
	//The full path of the file to where you will include the showall.php
		$showallurl = "http://spoken-for.org/x-exit.php";
	

//////// The following are optional, leave them there, but you do not have to edit them if you don't want to ///////
		$table = "sflinks";


/////// MORE NOTES //////
	//A stylesheet is included, but it only really links to the addlink.php page for your viewing pleasure only.
	//The links.php page is without a stylesheet on purpose.  You are to simply include it where you want it to
	//display (in a PHP page).  For example:
	//  include('/full/path/to/links.php');
	//To add links, visit the addlinks.php page and enter your password.  Give each link a name and it's URL.
	//BE SURE TO INCLUDE THE HTTP:// FOR EACH LINK!!!!!



//////// Do not change anything below this line //////////

$version = "1.0";

?>