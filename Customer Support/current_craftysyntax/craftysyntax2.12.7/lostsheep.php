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

$message = "";
if(!(isset($UNTRUSTED['proccess']))){ $UNTRUSTED['proccess'] = "no"; }
if(!(isset($UNTRUSTED['email']))){ $UNTRUSTED['email'] = ""; }
if($UNTRUSTED['proccess'] == "yes"){   
  $query = "SELECT * 
            FROM livehelp_users 
            WHERE email='".filter_sql($UNTRUSTED['email'])."'
             AND isoperator='Y'";
  $data = $mydatabase->query($query);
  if( $data->numrows() == 0){
      $mydatabase->close_connect();
      $message = "<font color=990000><b>" . $lang['txt97'] . " </b></font> <font color=000077> ".$UNTRUSTED['email']." </font><br>";
  } else {      
      while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
        $emailadd = $row['email'];
        $message .= "--------------------------------<br> ";
        $message .= "username:  " . $row['username'] . " <br> ";
        $message .= "password:  " . $row['password'] . " <br> ";
      }
     // to avoid relay errors make this lostpasswords@currentdomain.com
     if(!(empty($_SERVER['HTTP_HOST']))){
        $host = str_replace("www.","",$_SERVER['HTTP_HOST']);
        $contactemail  = "CSLH@" . $host;
      } else {
      	$contactemail  = $UNTRUSTED['email'];
      }  
         
     if (!(send_mail("CSLH", $contactemail, "Customer", $UNTRUSTED['email'], "CSLH Lost Password", $message, "text/html", $lang['charset'], false))) {
        send_mail("CSLH", $contactemail, "Customer", $UNTRUSTED['email'], "CSLH Lost Password", $message, "text/html", $lang['charset'], true);
     }   
                
     $message = "<font color=007700>Sent   to ".$UNTRUSTED['email']." with log in information. <br><br><a href=login.php> Log In</a><br><br>";

   }
}
?>
<SCRIPT>
if (window.self != window.top){ window.top.location = window.self.location; }
</SCRIPT>
<center>
<body bgcolor=D3DBF1>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<center>
<table border=0 cellpadding=0 cellspacing=0 width=450>
<tr><td bgcolor=CADAF5><center>
</td></tr>
<tr>
<td width=100% height=1 bgcolor=000000><img src=images/blank.gif width=400 height=1 border=0></td>
</tr>
<tr>
<td width=100% background=images/nav_bg.gif align=right><a href=http://craftysyntax.com/><img src=images/version.gif width=267 height=32 border=0></a></td>
</tr>
<tr>
<td width=100% height=1 bgcolor=000000><img src=images/blank.gif width=400 height=1 border=0></td>
</tr>
<table bgcolor=<?php echo $color_background;?> cellpadding=0 cellspacing=0 border=0 width=450>
<tr>
<td bgcolor=000000 colspan=4 height=1><img src=images/blank.gif width=300 height=1 border=0></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td align=center>
 <blockquote>
<font face="Arial, Helvetica, sans-serif" size="3" color=000077><b><?php echo $lang['txt168']; ?>:</b></font>
<br><font color=990000><b></b></font>
<form action=lostsheep.php METHOD=post>
 <?php echo $message; ?>
<input type=hidden name=proccess value=yes>
<table bgcolor=<?php echo $color_background;?> width=350>
<tr><td colspan=2><?php echo $lang['txt167']; ?></td></tr>
<tr><td nowrap=nowrap><b>e-mail:</b></td><td><input type=text name=email size=30></td></tr>
<tr><td colspan=2 align=center><input type=submit value=send></td></tr></table>
</form>
 </blockquote>
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>

<tr>
<td bgcolor=000000 colspan=4 height=1><img src=images/blank.gif width=300 height=1 border=0></td>
</tr>
</table>

</form><br>

</td></tr></table>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help &copy; 2003 - 2005 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>]
</font>