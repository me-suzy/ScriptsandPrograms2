<?php
/**********************************************************************
						 Php Textfile DB Access API
						Copyright 2002 by c-worker.ch
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

// I have these set in the config.php file.. do not set them here... 
// $DB_DIR = "do not set";
// $API_HOME_DIR = ""do not set";
// $DB_DIR="do not set";			
		




// ----------- IGNORE FROM HERE (Users) --------------
if(!defined("API_HOME_DIR")) 			define("API_HOME_DIR" ,$API_HOME_DIR);
if(!defined("DB_DIR")) 					define("DB_DIR" ,$DB_DIR);

/**********************************************************************
								Includes
***********************************************************************/

require(API_HOME_DIR . "resultset.php");
require(API_HOME_DIR . "database.php");


?>