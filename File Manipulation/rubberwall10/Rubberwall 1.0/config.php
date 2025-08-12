<?php //config.php
/***************************************************************************
 *                                RubberwaLL 1.0a
 *                            -------------------
 *   created:                : Friday, 9th May 2005
 *   copyright               : (C) 2005 ExplodingPanda.com, Neil Ord
 *   email                   : neil@explodingpanda.com
 *   web                     : http://www.explodingpanda.com/
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
$path = "/home/httpd/vhosts/yoursite.com/htdocs/files/rubberwall/lockdown/"; 
//path to lockdown (or whatever you renamed the folder) on your server. Remember trailing slash.

$webaddress = "http://username:password@www.yoursite.com/files/rubberwall/lockdown/"; 
//address of folder lockdown. Remember trailing slash.

$allowblank = 1; 
//if this is set to 1, users without information on where they came from will still see your files. This is recommended, as some user's browsers block the
//referrer information - we don't want to lock anyone legitimate out. Set to 0 to disable.//

$logging = 1; 
//Set to 0 to disable logging of access grants / failures/

$alloweddomains = array('explodingpanda.com','yoursite.com', 'anysite.com'); 
// domains from which your content may be opened. Includes all subdomains, so no need to
// add www.yourdomain.com, or other subdomains.//
?>