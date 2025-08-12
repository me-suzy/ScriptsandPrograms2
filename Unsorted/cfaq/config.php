<?php

/* 
	CascadianFAQ v4.1 - Last Updated: November 2003
	Summer S. Wilson, Eclectic Designs, http://eclectic-designs.com
	cfaq@eclectic-designs.com

	This file is where you customize CascadianFAQ to run on your server and to 
	your specifications.  By using the configuration file, you won't have to
	edit the actual PHP code (unless you want to totally change some aspect
	of the system).  
*/

$dbhost = "localhost"; // Host of your database server, usually localhost
$dbusername = "username"; // A valid username for logging into the database
$dbpassword = "password"; // The password that goes with the username above
$dbname = "databasename"; //The name of the database to use
$dbtype = "mySQL"; // Type of database (available choices: PostgreSQL or mySQL)

$faqname = "My FAQ"; // Name of the FAQ (usually Some Sitename's FAQ)
$adminname = "Me"; // Name of the FAQ administrator
$adminemail = "mysite@myurl.com"; //Email address of FAQ administrator
$background = "#FFFFFF"; // Background color of site, used for tables in admin area

// Set to 1 to display the number of questions in a category, 0 to not show it
$showcounts = 1; 

// Set to 1 to display the times a question has been viewed, 0 to not show it
$showviewcounts = 1; 

// Set to 1 to display category descriptions, 0 to turn them off
$usedescripts = 1; 

// Set to 1 to have level navigation appear at the top of each page, 0 to turn it off; Goes 3 levels deep
$topmenu = 1; 

// Set to 1 to show a credit line for my work, 0 to hide the credit in the comments
$givecredit = 1;

// An intro message to appear on the first page of the FAQ.  HTML is allowed, but if you 
// use quotes, be sure to escape them with a \ first.
$intro = "<p>Welcome to Cascadian FAQ.  Please select a category below.</p>";

// You should only change this if you renamed the index.php file
$cfaqindex = "index.php";

// More options
$mostrecent = 1; // Set to 1 to turn the most recent list on, 0 to turn it off.
$nummostrecent = 3; // How many questions should appear in the most recent list.

$mostpopular = 1; // Set to 1 to turn the most popular list on, 0 to turn it off.
$nummostpopular = 3; // How many questions should appear in the most popular list

$usersubmit = 1; // Set to 1 to allow users to submit new questions, 0 to not allow it.
$emailonusersubmit = 1; // Set to 1 to be emailed when new questions are submitted, 0 to turn it off

// Globalizing variables so functions can access them
global $dbhost, $dbusername, $dbpassword, $dbname
?>