<?php
/**********************************************************************
						 Php Textfile DB API
						Copyright 2003 by c-worker.ch
						  http://www.c-worker.ch
***********************************************************************/
/**********************************************************************
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
***********************************************************************/

/**********************************************************************
					Master Include File (Users)
***********************************************************************/

/**********************************************************************
	Essential Properties (MUST BE SET BEFORE THE API CAN BE USED)
***********************************************************************/

// Directory where the API is located (Server Path, no URL)

// This is set in config.php in main proigram.
//$API_HOME_DIR="/myhomepage/html/php-api/";	
//$API_HOME_DIR="c:\\programme\\apache\\htdocs\\php-api\\";	



// Directory where the Database Directories are located
// THIS IS NOT THE FULL PATH TO A DATABASE, ITS THE PATH
// TO A DIRECTORY CONTAINING 1 OR MORE DATABASE DIRECTORIES
// e.g. if you have a Database in Directory /home/website/test/TestDB
// you must set this property to /home/website/test/ 		

//$DB_DIR="/myhomepage/html/databases/";			
//$DB_DIR="c:\\programme\\apache\\htdocs\\php-api-tests\\";			




// ----------- IGNORE FROM HERE (Users) --------------
if(!defined("API_HOME_DIR")) 			define("API_HOME_DIR" ,$API_HOME_DIR);
if(!defined("DB_DIR")) 					define("DB_DIR" ,$DB_DIR);

/**********************************************************************
								Includes
***********************************************************************/

include_once(API_HOME_DIR . "resultset.php");
include_once(API_HOME_DIR . "database.php");


?>