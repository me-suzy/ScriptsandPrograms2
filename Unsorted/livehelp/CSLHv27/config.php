<? 
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/

// START OF CONFIGURATION  --------------------------
//
// NOTE: !!!!
// PLEASE READ!!!  YOU MIGHT not HAVE TO EDIT THIS FILE!!!!
// ------------------------
// The CSLH program is best installed by just opening up
// the setup.php webpage in a browser window on the server. 
// this is done by going to http://www.yourwebsite.com/livehelp/setup.php
// The setup will test you config settings for any invaild syntax and 
// will walk you through setting up. if because of permissions you 
// can not run the setup.php or if you can not chmod 777 config.php for
// this program then edit this file.
// ------------------------

// set this to true if false you will be re-directed to the setup page
$installed=false;  

if ($installed == false){
 Header("Location: setup.php");
}

if ($installed == true){
	
// dbtype either is either:
// mysql_options.php        - this is for Mysql support
// MSaccess.php             - this is for microsoft access.
// txt-db-api.php           - txt database support. 
$dbtype = "INPUT-DBTYPE";

//database connections for MYSQL 
$server = "INPUT-SERVER";
$database = "INPUT-DATABASE";
$datausername = "INPUT-DATAUSERNAME";
$password = "INPUT-PASSWORD";

// change this to the full SERVER path to your files 
// on the server .. not the HTTP PATH.. for example enter in
// $application_root = "/usr/local/apache/htdocs/livehelp/"
// not /livehelp/
// keep ending slash.
// WINDOWS would look something like:
// $application_root = "D:\\virtual www customers\\craftysyntax\\livehelp_1_6\\";
$application_root = "INPUT-ROOTPATH";

// if using Microsoft ACCESS
// FULL path to the database on the server:
// example:
// $dbpath = "D:\\virtual www customers\\craftysyntax\\db\\livehelp.mdb";
$dbpath = "INPUT-DBPATH";

// if using txt-db-api need the path to the txt databases directory
$DB_DIR = "INPUT-TXTPATH";
// if using txt-db-api need to have the full path to the txt-db-api
// you must set this property to something like /home/website/livehelp/txt-db-api/ 
$API_HOME_DIR = "INPUT-ROOTPATH" . "txt-db-api/";


// END OF DATABASE CONNECTION  -------------------------------


// ----------------------------------------------
// You do not need to edit anything below this line. 
//------------------------------------------------------------------------------

$filename = "$dbtype";
if ($dbtype == "mysql_options.php"){
 require "$filename";
 $mydatabase = new MySQL_optionsVars;
 $mydatabase->init($server,$datausername,$password);
 $mydatabase->selectdb($database);	
} 

if ($dbtype == "MSaccess.php"){
 require "$filename";
 $mydatabase = new MS_options;
 $mydatabase->init();
} 

if ($dbtype == "txt-db-api.php"){	
 $filename = "txt-db-api/" . $filename;
 require "$filename";
 $mydatabase = new Database("livehelp");	
}

$query = "SELECT * FROM livehelp_config";
$data = $mydatabase->select($query);
$data = $data[0];

// these are the configuration settings:
//-------------------------------------------------------------------------
$site_title = $data[site_title];          // title of the live help.. 
$version = $data[version];                // version of the database.
$use_flush = $data[use_flush];            // use the flush method or not.
$membernum = $data[membernum];            // member number for registration.
$show_typing = $data[show_typing];        // show or not to show is typing
$webpath = $data[webpath];                // normal url
$speaklanguage = $data[speaklanguage]; 
$offset = $data[offset];

$languagefile = "language-" . $speaklanguage . ".php";
include($languagefile);
}
?>