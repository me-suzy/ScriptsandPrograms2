<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: security.inc.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Set of security functions
// ----------------------------------------------------------------------

function getip() {
if (isSet($_SERVER)) {
 if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
  $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
 } elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) {
  $realip = $_SERVER["HTTP_CLIENT_IP"];
 } else {
  $realip = $_SERVER["REMOTE_ADDR"];
 }

} else {
 if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
  $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
 } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
  $realip = getenv( 'HTTP_CLIENT_IP' );
 } else {
  $realip = getenv( 'REMOTE_ADDR' );
 }
}
return $realip;
}

function inTree($db, $a, $b){
    if($a == $b || $a == 0){
          return true;
    }else{
          $strsql = "SELECT `parent_id` FROM `pages` WHERE `id` = $b AND `id`<>`parent_id`";
          $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
          if (!$rs->EOF) {
             $b = $rs->fields["parent_id"];
             return inTree($db, $a, $b);
          }else{
             return false;
          }
    }
}

function noPrivilege(){
         die('<img src="cmsimages/error.gif" width="48" height="48" alt=""><br>'.NO_PRIVILEGE);
}

/*
Privilege structure:
1st bit (1) for administrate website [Restore|META|Marquee]
2nd bit (2) for maintain website [Backup]
3rd bit (4) for pages editor [Add (status hide), Edit (status hide)|Order]
4th bit (8) for pages admin [Add|Edit|Delete|Status]
5th bit (16) for managing interactive entries [Entry status|Entry Delete]
*/
?>
