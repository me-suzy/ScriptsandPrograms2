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
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

$username = $username;
checkuser();

$pass = $username;
?>
<body bgcolor=FFFFEE>
<center>
<table width=600 border=1>
<tr bgcolor=DDDDDD><td><b>Version Notes:</td></tr>
<tr bgcolor=FFFFFF><td>
This is <b>version 1.7</b> of the live help program released 
on May 10, 2003. <br>Please see:
<a href=http://craftysyntax.com/livehelp/>http://craftysyntax.com/livehelp/</a>
for change log and version updates. </td></tr>
<tr bgcolor=DDDDDD><td><b>Additional Information and features:</td></tr>
<tr bgcolor=FFFFFF><td>
<b>Additional Programs by CS:</b><br>
<a href=http://craftysyntax.com/csgallery/ target=_blank>CS Gallery</a><br>
<a href=http://craftysyntax.com/csgallery/ target=_blank>My Scrapbook</a><br>
<a href=http://craftysyntax.com/projects/ target=_blank>More...</a><br>
<br>
e-mail me at <a href=mailto:crafty_syntax@yahoo.com>crafty_syntax@yahoo.com</a> 
if you need it to do something 
that it is not.. Also tell me if there are any bugs in the program.. </td></tr>
<tr bgcolor=DDDDDD><td><b>Support and Help:</td></tr>
<tr bgcolor=FFFFFF><td>
<font color=990000><b>Very Important:</b></font>
As with all programs it takes time and money to develop and 
test and if you would like to see this project continued then 
supporting its development would be a very good idea. Please visit
<a href=http://www.craftysyntax.com/livehelp/donations.php target=_blank>LIVE HELP DONATIONS</a></td></tr>
</table>

</center>
</body>
<?
$mydatabase->close_connect();
?>