<?php

// ***************************************************************************
// * Copyright © 2003 - Thomas Egtvedt - www.evaria.com - thomas@evaria.com  *
// *                                                                         *
// *         EMAIL - Evaria Mail Client - Support: forum.evaria.com          *
// *                                                                         *
// * This program is commercial software; you can not redistribute/reproduce *
// * it and/or sell it without the prior written consent of www.evaria.com   *
// *                                                                         *
// * This program is distributed in the hope that it will be useful,         *
// * but WITHOUT ANY WARRANTY; without even the implied warranty of          *
// * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                    *
// ***************************************************************************
//
// This is the configuration file for the Evaria Mail Client, 
// please review readme4.txt before installing it on your server.
//
//////////////////////////////////////////////////////////////////////////////
//-------------------- EVARIA ECMS Basic Website Settings ---  
//////////////////////////////////////////////////////////////////////////////

$sitename = "evaria mail client";                           // Your site name (title)
$baseurl = "http://www.yourname.com/";                      // Link to your website - homepage
$adminurl = "http://www.yourname.com/admin";                // Link to your admin section
$copyright = "&copy; 2003";                                 // Basic copyright message
$adminmail = "webmaster@yourname.com";                      // Your Email
$author = "Have a nice day - Regards admin @ yourname.com"; // Email signature

//////////////////////////////////////////////////////////////////////////////
//-------------------- EVARIA Email Client and Tell-a-Friend settings ---
//////////////////////////////////////////////////////////////////////////////

$mailtosender = "1";                                      // 0=no 1=yes
$mailtoadmin = "1";                                       // 0=no 1=yes
$up_full = "http://www.yourname.com/udloads/";            // Full url to upload dir
$up_dir = "./udloads/";                                   // Relative url from this script
$UploadNum = "1";                                         // Number of upload fields
$online_isp = "0";	                                      // Mail host functions.

// 0 is for most hosts, 1 is for Online.Net, 2 is for Nexenservices.com

$limit_size = "yes";                                      // Limit file size?
$max_size = "204800";                                     // Max file size in bytes
$min_size = "512";                                        // Min file size in bytes
$limit_ext = "yes";                                       // Limit file extensions?
$ext_count = "6";                                         // Number of file extensions

// Add or edit extensions as you feel, but do not forget to 
// change number of allowed file extensions if you do...

$extensions = array(".gif", ".jpg", ".php", ".inc", ".rar", ".zip");

?>