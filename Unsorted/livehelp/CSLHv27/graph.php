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

include("config.php");
include("user_access.php");
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

checkuser();

$pass = $username;

$gtotal = 0;
if($type == "refer"){
$query = "SELECT * FROM livehelp_referers_total where recno='$item'";
$openedacc_tmp = $mydatabase->select($query);
$openedacc_tmp = $openedacc_tmp[0];
$query = "SELECT * FROM livehelp_referers where camefrom='$openedacc_tmp[camefrom]' ORDER by dayof";
$openedacc = $mydatabase->select($query);
} else {
$query = "SELECT * FROM livehelp_visits_total where recno='$item'";
$openedacc_tmp = $mydatabase->select($query);
$openedacc_tmp = $openedacc_tmp[0];
$query = "SELECT * FROM livehelp_visits where pageurl='$openedacc_tmp[pageurl]' ORDER by dayof";
$openedacc = $mydatabase->select($query);
}

$max = 10;
  for($j=0;$j< count($openedacc); $j++){     	
     $myday = $openedacc[$j];
     $uniquevisits = $myday[uniquevisits];     
     if($uniquevisits>$max){ $max =$uniquevisits; }
  }
?>
<table bgcolor=FFFFEE>
  <tr><td bgcolor=FFFFFF colspan=31> 
  <b>Unique Traffic:</b></td></tr>
  <tr>
  <?
  $back = $top - 30;
  $next = $top + 30;
  if ($back > -1){
  $days_row .= "<td></td>";
  }
  for($j=0;$j< count($openedacc); $j++){     	
     $myday = $openedacc[$j];
     $uniquevisits = $myday[uniquevisits];
     $gtotal = $gtotal + $uniquevisits;
     $dayof =  $myday[dayof];     
     $height = floor(($uniquevisits/$max) * 200);
     print "<td valign=bottom>$uniquevisits<br><img src=images/bar.gif width=10 height=$height></td>";
     $days_row .= "<td>" . substr($dayof,6,2);
     $days_row .= "</font></td>";     
  }
  ?>
  </tr><tr>
  <?= $days_row ?>
  </tr>
  </table><br>
<?
$mydatabase->close_connect();
?>