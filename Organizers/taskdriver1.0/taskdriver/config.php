<?php
/****************************************************************************\
* TaskDriver                                                               
* Version:1.0                                                              
* Release date: Nov. 05 2005                                          
* Author: Todd Brillon (tbrillon@taskdriver.com)                                      
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        
******************************************************************************
* TaskDriver is free software; you can redistribute it and/or    
* modify it under the terms of the GNU General Public License as published   
* by the Free Software Foundation; either version 2 of the License, or (at  your option) any later version.                                         
*                                                                         
* TaskDriver is distributed in the hope that it will be          
* useful, but WITHOUT ANY WARRANTY; without even the implied warranty of     
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              
* GNU General Public License for more details.                              
*                                                                           
* You should have received a copy of the GNU General Public License         
* along with this program; if not, write to the Free Software               
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
\****************************************************************************/
/* Email address used with your admin account */
$AdminEmail = "youremail@youraddress.org";	
/* Database Hostname (usually localhost) */
$hostname = "localhost";			
/* Username for database */
$user = "DB_USERNAME";				
/* Password for database */
$pass = "DB_PASSWORD";				

/* Database name */
$database = "DB_NAME";		

// DO NOT ALTER THE TABLE DATA UNLESS YOU KNOW WHAT YOUR DOING	
/* table name for the user table that will be inserted into the database, If your uncertain what this is leave it as it is */
$userstable = "users";				
/* table name for the categories table that will be inserted into the database, If you are uncertain what this is leave it as it is */
$cattable = "categories";
/* table name for the dev_tasks table that will be inserted into the database, If you are uncertain what this is leave it as it is */				
$taskstable = "dev_tasks";
/* table name for the history table that will be inserted into the database, If you are uncertain what this is leave it as it is */				
$historytable = "history";



/* Do NOT enter www. or http:// */				
$domain = "yourdomain.org";
/* this is the directory account of the script ie.. /login/ be sure to add a slash at the beginning and end */			
$directory = "/taskdriver/";




/* DO NOT REMOVE ANYTHING BELOW */
ini_set("error_reporting", E_ALL & ~E_NOTICE);
/* Versioning */
$version = "1.0";
?>