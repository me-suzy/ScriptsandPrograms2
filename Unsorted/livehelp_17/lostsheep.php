<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CS Live Help)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// CS LIVE HELP http://www.craftysyntax.com/livehelp/
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

include("config.php");
include("user_access.php");

if($proccess == "yes"){   
      $query = "SELECT * FROM livehelp_users WHERE email='$email' ";
      $data = $mydatabase->select($query);
      if( count($data) == 0){
         $mydatabase->close_connect();
         Header("Location: lostsheep.php?err=1");
         exit;
      }
  
   for($j=0;$j< count($data); $j++){
       $row = $data[$j];
       $emailadd = $row[email];
       $message .= "--------------------------------\n<br> ";
       $message .= "username:  $row[username] \n<br> ";
       $message .= "password:  $row[password] \n<br> ";
     }
     $contactemail  = "$adminemail";
     $headers = "MIME-Version: 1.0\r\n";
     $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
     $headers .= "From: cs live help <noone@nothere.com>\r\n";
     $subject = "CSLIVE HELP Lost Password";
     $headers .= "To: lost sheep <".$email.">\r\n";

     mail($contactemail, $subject, $message, $headers);
              ?>
<HTML>
<HEAD>
   <TITLE>lost</TITLE>
</HEAD>
<body> <center><br>

<br><br>  
A e-mail has beed sent to <?= $email ?> with the log in information... 
<br><br><a href=index.php>Click here to return to main screen</a>	
  	<?
$mydatabase->close_connect();      
exit;
}
?>
<SCRIPT>
if (window.self != window.top){ window.top.location = window.self.location; }
</SCRIPT>
<center>
<?
if($err == 1){ 
print "<font color=990000><b>Sorry No account contains that e-mail address.. </b></font>";
}
?>
<h2>LOST USERNAME and/or PASSWORD:</h2>
<form action=lostsheep.php METHOD=post>
<input type=hidden name=proccess value=yes>
<table bgcolor=FFFFEE width=450>
<tr><td colspan=2>Enter in the e-mail address that is associated with your account. 
The system will then look into the database and e-mail the username and password
that is associated with the e-mail you enter.</td></tr>
<tr><td><b>e-mail:</b></td><td><input type=text name=email size=35></td></tr>
<tr><td colspan=2 align=center><input type=submit value=send></td></tr></table>
</form>
</center>