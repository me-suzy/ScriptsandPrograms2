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
      $username = $myusername;
      validate();
      setcookie("username","$myusername");
      // generate random string for later authentication. 
      $random = RandomPassword(10);
      $query = "UPDATE livehelp_users set identity='$random' WHERE username='$myusername' ";
      $mydatabase->sql_query($query);
      setcookie("random","$random");
      $query = "SELECT * FROM livehelp_config";
      $data = $mydatabase->select($query);
      $data = $data[0];
      $membernum = $data[membernum];
      if( ($membernum > 10) && ($membernum < 20)){
        ?>
        <FORM ACTION=http://craftysyntax.com/livehelp/donations.php Method=GET name=mine TARGET=_blank>
        <input type=hidden name=v value=<?= $version ?> >
        <input type=hidden name=p value=<?= $Processor ?> >
        </FORM>
        <SCRIPT>
        function gothere(){
          window.location.replace("admin.php");
        }
        document.mine.submit();
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
      $membernum++;
      $query = "UPDATE livehelp_config SET membernum='$membernum'";
      $mydatabase->sql_query($query);
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
print "<font color=990000><b>The username you entered Does not exist.</b></font>";
}
if($err == 2){ 
print "<font color=990000><b>The Password you entered is the incorrect password.</b></font>";
}
?>
<h2>CS LIVE HELP OPERATOR LOGIN:</h2>
<form action=login.php METHOD=post>
<input type=hidden name=proccess value=yes>
<table bgcolor=FFFFEE>
<tr><td><b>Username:</b></td><td><input type=text name=myusername></td></tr>
<tr><td><b>Password:</b></td><td><input type=password name=mypassword></td></tr>
<tr><td colspan=2><a href=lostsheep.php>Lost your username and/or password?</a></td></tr>
<tr><td colspan=2 align=center><input type=submit value=Login></td></tr></table>
</form>
</center>