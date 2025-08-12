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
require_once("visitor_common.php");
  
$query = "SELECT * FROM livehelp_transcripts WHERE sessionid='".filter_sql($UNTRUSTED['transsessionid'])."' ORDER by recno DESC";
$transarray = $mydatabase->query($query);
if($transarray->numrows()==0){
   print $lang['txt129'];
} else {	  
  $transarray = $transarray->fetchRow(DB_FETCHMODE_ASSOC);
  $comments = $transarray['transcript'];
  $department = $transarray['department'];
  $query = "SELECT * FROM livehelp_departments WHERE recno=".intval($department);
  $data_d = $mydatabase->query($query);  
  $department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);
  $messageemail = $department_a['messageemail'];
  if(empty($messageemail)){
    // to avoid relay errors make this lostpasswords@currentdomain.com
    if(!(empty($_SERVER['HTTP_HOST']))){
        $host = str_replace("www.","",$_SERVER['HTTP_HOST']);
        $messageemail  = "CSLH@" . $host;
      } else {
      	$messageemail  = $UNTRUSTED['email'];
      }  
  }
  $departmentname = whatdep($department);
 // mail("$sendto","Live Help Transcript","$comments","From: $messageemail\r\nContent-Type: text/html; charset=iso-8859-15");
  if (!(send_mail($departmentname, $messageemail, "Customer", $UNTRUSTED['sendto'], "Live Help Transcript", $comments, "text/html", $lang['charset'], false))) {
        send_mail($departmentname, $messageemail, "Customer", $UNTRUSTED['sendto'], "Live Help Transcript", $comments, "text/html", $lang['charset'], true);
     }   
  print "<center><h2>".$lang['txt130']."</h2></center>";  
}
  
  print "<br><br><br><br><a href=javascript:window.close()>" . $lang['txt40'] . "</a></center>"; 

 
$mydatabase->close_connect();
?>