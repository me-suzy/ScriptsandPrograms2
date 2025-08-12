<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2005 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
// -------------------------------------------------------------------------- 
// BIG NOTE:
//     At the time of the release of this version of CSLH, Version 3.1.0 
//     which is a more modular, extendable , skinable version of CSLH
//     was being developed.. please visit http://www.craftysyntax.com to see if it was released!  
//===========================================================================
require_once("admin_common.php");
validate_session($identity);

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];

$timeof = date("YmdHis");
$timeof_old = $timeof - 100000;
 
         // get when they logged in and how many seconds they have been online:
        $query = "SELECT dateof FROM livehelp_operator_history WHERE opid=$myid AND action='login' ORDER by dateof DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
        $seconds = timediff(date("YmdHis"),$row3['dateof']);
        
        // update history for operator to show login:
        $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid,totaltime) VALUES ($myid,'Logout','".date("YmdHis")."','".$identity['SESSIONID']."',$seconds)";
        $mydatabase->query($query);
        
       // log them off:
       $query = "UPDATE livehelp_users set authenticated='N',isonline='N',status='offline' WHERE user_id=$myid";
       $mydatabase->query($query); 
logout($identity);       
$mydatabase->close_connect();

?>