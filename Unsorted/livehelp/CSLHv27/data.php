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
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

//checkuser();

$pass = $username;



function showpaging(){
   global $mydatabase,$perpage,$top,$total_p,$show;
   $maxout = 10;
   if (($perpage == 0) || ($perpage == "")){ $perpage =25; }
   $diff = ($top % $perpage);
   $page = ($top- $diff)/$perpage + 1;
   $start = (($page - ($page % $maxout))/$maxout ) * $maxout;
   $counting = ($start * $perpage) - $perpage;
   if (($total_p % $perpage) == 0){ $diff = 0; } else { $diff = 1; }
   $total = ($total_p  - ($total_p % $perpage))/$perpage + $diff;
   print "<table width=620>";
   print "<tr bgcolor=FFFFCC>";
   if ($page != 1){
   $previous = (($page - 2) * $perpage) ;
     print "<td width=1%><table cellpadding=0 cellspacing=0 border=0><tr><td><a href=data.php?show=$show&top=$previous><img src=images/back_s.gif width=20 height=13 border=0></a></td><td><b><a href=data.php?show=$show&top=$previous>Previous</b></a></td></tr></table></td>";
   } else {
   print "<td width=1%>.</td>";	
   }
   print "<td width=98% align=center>";
   print "<font size=-1>Page <b><font color=007700>$page</font></b> of <b><font color=000077>$total</font></b> :</font> ";
        $count = 1;
         $back =  $counting - $perpage;
         if ($back >= 0 ){ 
         	print "<a href=data.php?show=$show&top=$back>Last 10 </a> <font size=+1>|</font> ";       
         }        
        for($i = $start; $i <= $total; $i++){  
         if ($page == $i){
            print " <b>$i</b> <font size=+1>|</font> ";
         } else {        	
            if($i != 0){
             print " <a href=data.php?show=$show&top=$counting>$i</a> <font size=+1>|</font> ";	
            }
         }
         if ($count > 9){ 
         	$counting = $counting + $perpage;
         	print " <a href=data.php?show=$show&top=$counting>next 10 </a>  ";       
                $i = $total;
         }
         $count++;
         $counting = $counting + $perpage;
        }           
   print "</td>";
   if ($page < $total){
    $nextpage = ($page * $perpage) ;
     print "<td width=1%><table cellpadding=0 cellspacing=0 border=0><tr><td><b><a href=data.php?show=$show&top=$nextpage>Next</b></a></td><td><a href=data.php?show=$show&top=$nextpage><img src=images/next_s.gif width=20 height=13 border=0></a></td></tr></table></td>";
   } else {
     print "<td width=1%><b>.</b></td>";    
    }
   print "</tr></table>";
}

if($top == ""){ $top = 0; }
if($perpage == ""){ $perpage = 25; }

$query = "SELECT * FROM livehelp_users WHERE username='$username'";
$data = $mydatabase->select($query);
$row = $data[0];
$isadminsetting = $row[isadmin];

if($isadminsetting != "Y"){ $clearall = ""; $remove=""; }

if($remove != ""){
 $query = "DELETE FROM livehelp_transcripts WHERE recno='$remove' ";
 $mydatabase->sql_query($query);
}

$clear_from =  sprintf("%04d%02d%02d", $clearfrom_year, $clearfrom_month, $clearfrom_day);

if($clear_from > 2000000){
  $query = "SELECT * FROM livehelp_referers WHERE dayof<$clear_from";
  $old = $mydatabase->select($query);
  for($i=0;$i<count($old); $i++){
   $row = $old[$i];
   $old_count = $row[uniquevisits];
   $camefrom = $row[camefrom];
   $recno = $row[recno];
   $query = "SELECT * FROM livehelp_referers_total WHERE camefrom='$camefrom' ";
   $old_d = $mydatabase->select($query);
   $old_d = $old_d[0];
   $old_count =  $old_d[ctotal] - $old_count;
   $query = "UPDATE livehelp_referers_total set ctotal='$old_count' WHERE camefrom='$camefrom' ";
   $mydatabase->sql_query($query);
   $query = "DELETE FROM livehelp_referers where recno='$recno'";
   $mydatabase->sql_query($query);
  }
  $query = "SELECT * FROM livehelp_visits WHERE dayof<$clear_from";
  $old = $mydatabase->select($query);
  for($i=0;$i<count($old); $i++){
   $row = $old[$i];
   $old_count = $row[uniquevisits];
   $pageurl = $row[pageurl];
   $recno = $row[recno];
   $query = "SELECT * FROM livehelp_visits_total WHERE pageurl='$pageurl' ";
   $old_d = $mydatabase->select($query);
   $old_d = $old_d[0];
   $old_count =  $old_d[ctotal] - $old_count;
   $query = "UPDATE livehelp_visits_total set ctotal='$old_count' WHERE pageurl='$pageurl' ";
   $mydatabase->sql_query($query);
   $query = "DELETE FROM livehelp_visits where recno='$recno'";
   $mydatabase->sql_query($query);
  }    
  $query = "DELETE FROM livehelp_visits_total WHERE ctotal<'1' ";
  $mydatabase->sql_query($query);
  $query = "DELETE FROM livehelp_referers_total WHERE ctotal<'1' ";
  $mydatabase->sql_query($query);
}
if($clearall == "YES"){
 $query = "DELETE FROM livehelp_operator_channels";
 $mydatabase->sql_query($query);  
 $query = "DELETE FROM livehelp_users WHERE isoperator='N'";
 $mydatabase->sql_query($query);  
 $query = "DELETE FROM livehelp_messages";
 $mydatabase->sql_query($query); 
 $query = "DELETE FROM livehelp_visit_track";
 $mydatabase->sql_query($query); 
 $query = "DELETE FROM livehelp_channels";
 $mydatabase->sql_query($query);  
 print "<font color=007700 size=+2>TEMP DATA CLEARED...</font>";
}

?>
<body bgcolor=FFFFEE><center>
<? if($view == ""){ ?>
<table bgcolor=DDDDDD width=600><tr><td>
<b>Referers:</b></td></tr></table>
<?
if($show=="referer"){
$query = "SELECT * FROM livehelp_referers_total ORDER by ctotal DESC";
$refer_a = $mydatabase->select($query);
$total_p = count($refer_a);

showpaging();

$query = "SELECT * FROM livehelp_referers_total ORDER by ctotal DESC LIMIT $top,$perpage";
$refer_a = $mydatabase->select($query);

print "<table width=600><tr bgcolor=EFEF9D><td><b>url</b></td><td><b># clicks</b></td><td><b>graph</b></td></tr>";

for($i=0;$i<count($refer_a);$i++){
 $refer = $refer_a[$i];
 if($bgcolor=="FFFFEE"){$bgcolor="F0F1E1"; } else { $bgcolor="FFFFEE"; }
 print "<tr bgcolor=$bgcolor><td NOWRAP><a href=$refer[camefrom] target=_blank>" . substr($refer[camefrom],0,100) . "</a></td><td>$refer[ctotal]</td><td NOWRAP> <a href=graph.php?item=$refer[recno]&type=refer target=_blank>SHOW GRAPH</a></td></tr>";
}
print "</table>";
showpaging();
} else {
 print "<tr><td colspan=3><a href=data.php?show=referer>CLICK HERE TO SHOW REFERERS</a></td></tr>";	
}
?>


<br><br>


<table bgcolor=DDDDDD width=600><tr><td>
<b>Page Visits:</b></td></tr></table>

<?
if($show=="visit"){
$query = "SELECT pageurl FROM livehelp_visits_total ORDER by ctotal DESC";
$refer_a = $mydatabase->select($query);
$total_p = count($refer_a);

showpaging();

$query = "SELECT * FROM livehelp_visits_total ORDER by ctotal DESC LIMIT $top,$perpage";
$visits_a = $mydatabase->select($query);

print "<table width=600><tr bgcolor=EFEF9D><td><b>url</b></td><td><b># clicks</b></td><td><b>graph</b></td></tr>";
for($i=0;$i<count($visits_a);$i++){
 $visits = $visits_a[$i];
 if($bgcolor=="FFFFEE"){$bgcolor="F0F1E1"; } else { $bgcolor="FFFFEE"; }
 print "<tr bgcolor=$bgcolor><td><a href=$visits[pageurl] target=_blank>$visits[pageurl]</a></td><td>$visits[ctotal]</td><td> <a href=graph.php?item=$visits[recno]&type=visit target=_blank>SHOW GRAPH</a></td></tr>";
}
print "</table>";
showpaging();
} else {
 print "<tr><td colspan=3><a href=data.php?show=visit>CLICK HERE TO SHOW VISITORS</a></td></tr>";	
}
?>

<br><br>
<table bgcolor=DDDDDD width=600><tr><td>
<b>Transcripts:</b></td></tr></table>
<?
}
if($view != ""){ 
 $query = "SELECT * FROM livehelp_transcripts WHERE recno='$view'";
 $data = $mydatabase->select($query);
print "<table width=600 bgcolor=FFFFFF border=1><tr><td>";
$data = $data[0];
print $data[transcript];
print "</td></tr></table>";
} else { ?>
<table width=600>
<? if($show=="trans"){ ?>
<tr bgcolor=FFFFFF><td>Date</td><td>name</td><td>Options</td></tr>
<?
 $query = "SELECT * FROM livehelp_transcripts order by daytime DESC";
 $data = $mydatabase->select($query);
 for($i=0;$i< count($data); $i++){
   $row = $data[$i];
  if($bgcolor=="EFEF9D"){ $bgcolor="FFFFC0"; } else { $bgcolor="EFEF9D"; }
   print "<tr bgcolor=$bgcolor><td>";
   print substr($row[daytime],4,2) . "-" . substr($row[daytime],6,2) . "-" . substr($row[daytime],0,4) . " (" . substr($row[daytime],8,2) . ":" . substr($row[daytime],10,2) . ":" . substr($rwo[daytime],12,2);
   print "</td><td>$row[who]</td><td> <a href=data.php?view=$row[recno] target=_blank>VIEW</a> ";
   if($isadminsetting == "Y"){   
     print " <a href=data.php?remove=$row[recno]><font color=990000>REMOVE</font></a> ";
   }
   print "</td></tr>";   
 } 
} else {
 print "<tr><Td> <a href=data.php?show=trans>CLICK HERE TO SHOW TRANSCRIPTS</a></td></tr>";	
}
 ?>
</table>
<? } ?>
<? if($view == ""){ ?>
<? if($isadminsetting == "Y"){ ?>
<br><br><br><br>
<table bgcolor=DDDDDD width=600><tr><td>
<b>Data Clean Up:</b></td></tr></table>
<a href=data.php?clearall=YES>CLICK HERE TO CLEAR ALL TEMP MESSAGES and TEMP USERS..</a>
<br><br>
<b>Clear all data That entered the database before the date of: </b>
<form action=data.php Method=POST>
<table><tr>
<td>Month:</td>
<td><select name=clearfrom_month>
<? for($i=1;$i<13;$i++){
  print "<option value=$i>$i</option>\n";
}
?>
</select>
</td>
<td>Day:</td>
<td><select name=clearfrom_day>
<? for($i=1;$i<32;$i++){
  print "<option value=$i>$i</option>\n";
}
?>
</select>
</td>
<td>Year:</td>
<td><select name=clearfrom_year>
<? for($i= date("Y");$i>date("Y")-5;$i--){
  print "<option value=$i>$i</option>\n";
}
?>
</select></td></tr></table>
<input type=submit value=CLEAR></form>
<br>

<?
}
} else { print "<a href=javascript:window.close()>click here to close this window</A>";}

$mydatabase->close_connect();
?>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help © 2003 by <a href=http://www.craftysyntax.com/>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>]
</font>