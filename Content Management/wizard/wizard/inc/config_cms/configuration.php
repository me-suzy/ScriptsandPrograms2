<?php
/*  
   Main Configuration file.
   (c) 2006 Philip Shaddock All rights reserved.
       www.ragepictures.com 
*/ 


// database
	define("DB_SERVER", "localhost" );
	define("DB_USER", "db_username" );
	define("DB_PASS", "db_password" );
	define("DB_DATABASE", "db_database_name" );
	define("DB_PREPEND", "rp_" ); //prepends to database table, use "" if no prepend

//domain
    define("CMS_DOMAIN", "yoursite.com" ); 

// site URL, including subdirectory if any e.g. http://www.yoursite.com/subdirectory
	define("CMS_WWW", "http://www.yoursite.com" ); 

//subdirectory if any
	$cmssubdir = "";          //eg: "/home"  (notice the forward slash)
	
	// site server path, including subdirectory where installed, if any, no trailing "/"
	// note: if you are getting "file not found" messages try uncommenting the following line and commenting out the one after that.
	
	define ("CMS_ROOT", preg_replace('/\/$/', '', $_SERVER['DOCUMENT_ROOT']).(($cmssubdir) ? "".$cmssubdir : "") );   
	 
	 //define("CMS_ROOT", "/home/sitename/public_html/home");
	
// Language
    $language = "english";  //options: english german
	
//Site Constants	
    define("SITE_NAME", "Your Site Name" );
	define("SITE_EMAIL", "info@yoursite.com" );
	define("COMPANY_NAME", "Your Company Inc.");
	
	
?>