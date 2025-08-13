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
require("globals.php");
include("config.php");
include("user_access.php");

if($proccess == "yes"){
      $username = $myusername;
      validate();
      setcookie("username","$myusername");
      // generate random string for later authentication. 
      $random = RandomPassword(10);
      $query = "UPDATE livehelp_users set identity='$random' WHERE username='$myusername' ";
      $mydatabase->sql_query($query);

      $query = "SELECT * FROM livehelp_users WHERE username='$myusername' ";
      $person_a = $mydatabase->select($query);
      $person = $person_a[0];
      $visits = $person[visits];
      
      setcookie("random","$random");
      $visits++;
      $query = "UPDATE livehelp_users SET visits='$visits' WHERE username='$myusername' ";
      $mydatabase->sql_query($query);
      
      if( ($visits % 15) == 14){
        ?>
        <SCRIPT>
        function gothere(){
          window.location.replace("admin.php");
        }
        function gofo(){
          window.open('http://www.craftysyntax.com/livehelp/updates.php','rdfdsf','toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes');
        }        
        setTimeout("gofo();",40);
        setTimeout("gothere();",4000);
        </SCRIPT>  
                <h2>Please wait... </h2>               
        <?	
      } else {	
       ?>
               <SCRIPT>
        function gothere(){
          window.location.replace("admin.php");
        }
        setTimeout("gothere();",4000);
        </SCRIPT> 
        <h2>Please wait... </h2> 
       <?       
      }
$mydatabase->close_connect();
exit;
}
?>
<SCRIPT>
if (window.self != window.top){ window.top.location = window.self.location; }
</SCRIPT>
<center>
<body bgcolor=D3DBF1>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<center>
<table border=0 cellpadding=0 cellspacing=0 width=450><tr>
<td width=100% background=images/nav_bg.gif align=right><img src=images/version.gif width=126 height=32></td>
</tr>
<tr><td bgcolor=FFFFC0><center>
<h2>CSLH OPERATOR LOGIN:</h2>
<?
if($err == 1){ 
print "<font color=990000><b>The username you entered Does not exist.</b></font>";
}
if($err == 2){ 
print "<font color=990000><b>The Password you entered is the incorrect password.</b></font>";
}
if($err == 3){ 
print "<font color=990000><b>You have Been Logged Out.</b></font>";
}
?>
<form action=login.php METHOD=post>
<input type=hidden name=proccess value=yes>
<table bgcolor=FFFFEE width=400>
<tr><td><b>Username:</b></td><td><input type=text name=myusername></td></tr>
<tr><td><b>Password:</b></td><td><input type=password name=mypassword></td></tr>
<tr><td colspan=2 align=center><a href=lostsheep.php>Lost your username and/or password?</a></td></tr>
<tr><td colspan=2 align=center><input type=submit value=Login></td></tr></table>
</form><br>
powered by <a href=http://www.craftysyntax.com/livehelp/?v=<?= $version?> >Crafty Syntax Live Help <?= $version ?></a> 
<br><br>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help © 2003 by <a href=http://www.craftysyntax.com/>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>]
</font>
</center>
</td></tr></table>
</center>