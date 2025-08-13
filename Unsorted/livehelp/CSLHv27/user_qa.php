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

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
sleep(1);
if($identity == ""){
 if($REMOTE_ADDR == ""){
    $REMOTE_ADDR = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
 }
 if($HTTP_USER_AGENT == ""){
    $HTTP_USER_AGENT = $HTTP_SERVER_VARS["HTTP_USER_AGENT"]; 
 }
 $identity = $REMOTE_ADDR . $HTTP_USER_AGENT . $rand_id;
 $referer = $HTTP_REFERER;
 $identity = ereg_replace(" ","",$identity);
}
$query = "UPDATE livehelp_users set status='qna' WHERE identity='$identity'";	
$mydatabase->sql_query($query);

// get the depth and the path.. 
function depthof($id){
  global $mydatabase;	
  $pathto = array();
  while ($id != 0){     
     $query = "SELECT * FROM livehelp_qa WHERE recno='$id'";
     $children = $mydatabase->select($query); 
     $row = $children[0];
     array_push ($pathto, $id);   
     $id = $row[parent];
   } 	
  return $pathto;
}
function questions(){
  global $mydatabase,$current,$editit;
  $query = "SELECT * FROM livehelp_qa WHERE parent='$current' AND typeof='question' ORDER BY ordernum,question";
  $children = $mydatabase->select($query);  

  for($i=0;$i< count($children); $i++){
     $row = $children[$i];        	 
     print "<tr><td NOWRAP>";
     print "<img src=images/blank.gif width=$spacing height=2>";
     print "<img src=images/help_q.gif width=18 height=16>";    
     print "<A href=user_qa.php?current=$current&answer=$row[recno]>$row[question]</a>";    
     print "</td></tr>";
  }
  if( count($children) == 0){
   print "<tr><td>$lang_user_choose</td></tr>";	
  }
}

function showtree($parent,$depth){
  global $editit,$current,$mydatabase,$mypath;

  $query = "SELECT * FROM livehelp_qa WHERE parent='$parent' AND typeof='folder' ORDER by ordernum,question";
  $children = $mydatabase->select($query);  
  $spacing = ($depth * 10) + 1;
  for($i=0;$i< count($children); $i++){
     $row = $children[$i];
     
     if( in_array($row[recno],$mypath) ){ $opened = 1; } else { $opened = 0; }
         	 
     print "<tr><td NOWRAP>";

     print "<img src=images/blank.gif width=$spacing height=2>";
     if($opened == 0){ 
     	print "<img src=images/help_folder.gif width=18 height=16>"; 
     }
     if($opened == 1){ 
     	print "<img src=images/help_folder_open.gif width=18 height=16>"; 
     }     
     print "<A href=user_qa.php?current=$row[recno]>$row[question]</a>";
    
     print "</td></tr>";
     if( in_array($row[recno],$mypath) ){
       showtree($row[recno],$depth+1);       
     }
  }

}
?>
<body bgcolor=FFFFEE  onload=expandit()>
<br>
<SCRIPT>
function expandit() {
 <? if ($tab != 1){ ?>
  window.parent.resizeTo(window.screen.availWidth - 100,      
  window.screen.availHeight - 100);
 <? } ?>
}
</SCRIPT><center>
<?
if($answer != ""){
  $query = "SELECT * FROM livehelp_qa WHERE recno='$answer' AND typeof='question'";
  $question = $mydatabase->select($query); 
  $question = $question[0];
  $query = "SELECT * FROM livehelp_qa WHERE parent='$answer' AND typeof='answer'";
  $question_a = $mydatabase->select($query); 
  $question_a = $question_a[0];
?>
<table width=100%>
<tr bgcolor=EFEF9D>
<td colspan=2><b><?= $lang_question ?>:</b> <?= $question[question] ?></b></td></tr>
<tr bgcolor=FFFFEE>
<td colspan=2>
<img src=images/help_a.gif width=18 height=16><b><?= $lang_answer ?>:</b><br>
<table width=90%><tr><td bgcolor=FFFFC0>

<?
 print "$question_a[question] ";
?>

</td></tr></table>
<p><p>

<?
  $query = "SELECT * FROM livehelp_qa WHERE parent='$answer' AND typeof='comment'";
  $comments_a = $mydatabase->select($query); 
for($j=0;$j< count($comments_a); $j++){
  $comment = $comments_a[$j];
  print "<p>";
  print "$comment[question]";
}
?>
<p></td></tr>
</table>
<?   	
}
?>
<table width=100%>
<tr bgcolor=EFEF9D>
<td><b><?= $lang_help ?>:</b></td>
<td><b><?= $lang_help2 ?>:</b></td>
</tr>
<tr><td>
<a href=user_qa.php>top</a>
<table>
<? 
if($current == ""){ $current = 0; }
$mypath = depthof($current);
showtree(0,1); ?>
</table>

</td><td>
<table>
<?
questions(); 
?>
</table>
</td>
</tr></table>
</center>
<p><p>
<?
$mydatabase->close_connect();
?>
<pre>


</pre>
<font size=-2 color=DDDDDD>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help © 2003 by Eric Gerdes  
<br>
CSLH is  Software released 
under the GNU/GPL license
</font>