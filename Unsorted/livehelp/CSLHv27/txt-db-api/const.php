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

/***********************************
		 	User Settings
************************************/

$DEBUG=0;				    // 0=Debug disabled, 1=Debug enabled
$LIKE_CASE_SENSITIVE=0;     // 0=LIKE is case insensitive, 1=LIKE is case sensitive
$ORDER_CASE_SENSITIVE=0;	// 0=ORDER BY is case insensitive, 1=ORDER BY is case sensitive
$ASSUMED_RECORD_SIZE=30;    // Set this to the average size of one record, if in doubt 
                            // leave the default value. DON'T set it to <1! int's only!
$PRINT_ERRORS=1;			// 0 = Warnings are NOT displayed, 1 = Warnings are displayed
$PRINT_WARNINGS=0;			// 0 = Errors are NOT displayed, 1 = Errors are displayed


/***********************************
		 	Constants 
************************************/
// Don't change them, expect you know
// what you do!

// Define User Settings
define("LIKE_CASE_SENSITIVE",$LIKE_CASE_SENSITIVE);
define("ORDER_CASE_SENSITIVE",$ORDER_CASE_SENSITIVE);
define("TXTDBAPI_DEBUG",$DEBUG);

// Even more Debug Infos ?
define("TXTDBAPI_VERBOSE_DEBUG",0);

// This constant doesn't limit the max size of a record it's only the assmued size 
// of a record when a table is read for appending. If not a whole record is
// contained in ASSUMED_RECORD_SIZE bytes, the the number of bytes read 
// is increased until a whole record was read. Choosing this value wisely may
// result in a better INSERT performance
define("ASSUMED_RECORD_SIZE",$ASSUMED_RECORD_SIZE);

define("PRINT_ERRORS",$PRINT_ERRORS);
define("PRINT_WARNINGS",$PRINT_WARNINGS);

// Version
define("TXTDBAPI_VERSION","0.2.1-Beta-01");

// General
define("NOT_FOUND",-1);

// File parsing
define("TABLE_FILE_ESCAPE_CHAR","%"); 	// Char to Escape # in the Table Files
define("TABLE_FILE_OPEN_MODE","b"); 	// "b" or ""

// Timeouts
define("OPEN_TIMEOUT",10); 		// Timeout in seconds to try opening a still locked Table
define("LOCK_TIMEOUT",10); 		// Timeout in seconds to try locking a still locked Table
define("LOCKFILE_TIMEOUT",30); 	// Timeout for the maximum time a lockfile can exist

// Predefined Databases
define("ROOT_DATABASE","");

// Order Types
define("ORDER_ASC",1);
define("ORDER_DESC",2);

// Column Types
define("COL_TYPE_INC","inc");
define("COL_TYPE_INT","int");
define("COL_TYPE_STRING","str");

// Column Function Types
define("COL_FUNC_TYPE_SINGLEREC",1);
define("COL_FUNC_TYPE_GROUPING",2);


// File Extensions
define("TABLE_FILE_EXT",".txt");
define("LOCK_FILE_EXT",".lock");


?>