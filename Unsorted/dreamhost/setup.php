<?
/* ------------------------------------------------------------------
THE MYSQL DATABASE SETTINGS...
These settings must be correct before you can successfully continue to
the next setup of the setup proceedure. Once you have done this, it
should be breeze from here out! Remember that whatever name you assign
to the database, the setup file will attempt to create. If the database 
already exsists, either choose another database name to use, delete the
exsisting database, or manually dump the database file. (dreamhost.sql)
---------------------------------------------------------------------*/

$host	  		=	"localhost";    	//  <-- HOSTNAME FOR THE MySQL SERVER
$user	  		=	"usernamel";      	//  <-- USERNAME FOR MySQL LOGIN
$pass	  		=	"password";        	//  <-- PASSWORD FOR MySQL LOGIN
$database 	= 	"dreamhost";	     //  <-- NAME OF DATABASE 


/* ------------------------------------------------------------------
THE SALT FILE FOR THE RC4 ENCYRPTION/DRECYPTION FUNCTIONS... 
The best thing to do is place this file in a secure area on your server.
You can also rename the file if you wish, but there probably isn't much
use in doing so. Remember, location is everything!		
---------------------------------------------------------------------*/

$salt			= 	"\secure\path\to\salt.php";

require($salt); 



/* ------------------------------------------------------------------
THE FILE EXTENTION FOR PHP FILES ON YOUR SERVER... 
(Generally, it is .php for PHP VERSION 4 and .php3 for PHP VERSION 3)
Do not change this unless you have to! If you do, make sure you change
all files name .php to whatever you change the $ext variable to.
---------------------------------------------------------------------*/

$ext	= ".php";                      


//DO NOT CHANGE ANYTHING PAST THIS POINT!
$page_ext=".html";
?>