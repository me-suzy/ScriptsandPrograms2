<?php
//----------------------------------------//
//     		 Php Counter v1.9			  //
//			 Creator: Tivadar 		      //
//             2005 september			  //
//				    				 	  //
//										  //
// E-mail: info@tivadar.tk				  //
// MSN: msn@tivadar.tk                    //
// Web: www.tivadar.tk					  //
//----------------------------------------//

define ("GO_HOME", true);

if (!defined ("GO_HOME")) {

	die (";)");

}

$szoveg = "Count";    		  // Visit count text
$szoveg2 = "Page download";   // Page download count text
$kod = "iso-8859-1";		  // Character coding
$stilus = 1;                  // Included style uses, 1 = yes | 0 = no (your page style)
$dbh = 'localhost';           // MySQL  host
$dbf = 'root';                // MySQL user name
$dbj = '';      	          // MySQL password
$dbn = '';  		          // Table name in MySQL
		
		@mysql_connect($dbh, $dbf, $dbj) or die ('MySQL connecting failed!');

		@mysql_select_db($dbn) or die ('Database connecting failed!');
		
		
?>