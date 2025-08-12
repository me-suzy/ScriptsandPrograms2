<?php

/***************************************************
General configuration files. From here you can change all the
configuration parameters like the db login information.
Graphic configuration can be changed from the file cfg.inc.css
****************************************************/

$dbconnect  = NULL; 				//keep it NULL
$dbhost     = "";		//IP address of the database
$dbusername = "";			//Username for the db
$dbuserpass = "";		    //Password for the db
$dbname = "";				//Name of the db

table_list = Array('Person','Message');	//IMPORTANT // Fill this array with the classes that you have created in the ./classes dir
																				//(Cancel the Person and Message classes as they're examples:D)

$default_rows_per_page = 25;			//Number of rows per page to show

$page_title = "Php interface example for generic mysql data";
										//Title of the page
										
$main_title = "RTInterface"; 	    	//Title to show in every page

$main_image = "logo.jpg";				//Url of the logo

$use_trans_sid = false;					//If this option is active compatibility will be improved(cookies hasn't to be enabled) but decrease security

$footer_text = "Php interface example for generic mysql data by Roberto Toldo";	
										//This text will appear at the foot of every page						
$

?>