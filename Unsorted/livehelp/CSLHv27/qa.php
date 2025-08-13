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

checkuser();

$pass = $username;

$query = "SELECT * FROM livehelp_users WHERE username='$username'";
$data = $mydatabase->select($query);
$row = $data[0];
$isadminsetting = $row[isadmin];

// create a folder
if($newfolder != ""){
 $query = "INSERT INTO livehelp_qa (parent,question,typeof,status,ordernum) VALUES ('$current','$newfolder','folder','A','0') ";
 $mydatabase->insert($query);	
}

// create a question
if($newquestion != ""){
 $query = "INSERT INTO livehelp_qa (parent,question,typeof,status,ordernum) VALUES ('$current','$newquestion','question','A','0') ";
 $mydatabase->insert($query);	
}

// update a question
if($whatdo == "UPDATE"){
  if($nltobr != ""){ $question = nl2br($question); }
  if($newinsert==""){
  $query = "UPDATE livehelp_qa SET question='$question' WHERE recno='$recno'";
  $mydatabase->sql_query($query);  
  } else {
   $query = "INSERT INTO livehelp_qa (parent,question,typeof) VALUES ('$answer','$question','answer')";
   $mydatabase->insert($query);  
  }
}

// remove a question
if($whatdo == "REMOVE"){
  $query = "DELETE FROM livehelp_qa WHERE recno='$recno'";
  $mydatabase->sql_query($query);  
}

// re-order folders/questions.
if($whatdo == "REORDER"){
  $query = "SELECT * from livehelp_qa";
  $myarray = $mydatabase->select($query); 
  for($j=0;$j<count($myarray);$j++){
     $row = $myarray[$j];	
     $lookingfor = "ordering__" . $row[recno];
     if($$lookingfor != ""){
       $value = $$lookingfor;
       $query = "UPDATE livehelp_qa set ordernum='$value' where recno='$row[recno]' ";
       $mydatabase->sql_query($query); 
     }        
  }   
}


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
     if($editit == ""){print "<input type=text size=3 name=ordering__$row[recno] value=$row[ordernum]>";}
     if($editit != $row[recno]){
     print "<A href=qa.php?current=$current&answer=$row[recno]>$row[question]</a> [<a href=qa.php?current=$current&editit=$row[recno]>Edit</a>]";
     } else {
      print "<FORM action=qa.php method=post>";
      print "<input type=hidden name=current value=$current>";
      print "<input type=hidden name=recno value=$editit>";
      print "#<input type=text name=ordernum value=\"$row[ordernum]\" size=3> <input type=text size=45 name=question value=\"$row[question]\">";
      print "<input type=submit name=whatdo value=UPDATE>";
      print "<input type=submit name=whatdo value=REMOVE>";            
      print "</FORM>";
     }     
     print "</td></tr>";
  }
  if( count($children) == 0){
   print "<tr><td> $lang_user_choose </td></tr>";	
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
     if($editit != $row[recno]){
     print "<img src=images/blank.gif width=$spacing height=2>";
     if($editit == ""){print "<input type=text size=3 name=ordering__$row[recno] value=$row[ordernum]>";}
     if($opened == 0){ 
     	print "<img src=images/help_folder.gif width=18 height=16>"; 
     }
     if($opened == 1){ 
     	print "<img src=images/help_folder_open.gif width=18 height=16>"; 
     }     
     print "<A href=qa.php?current=$row[recno]>$row[question]</a> [<a href=qa.php?current=$current&editit=$row[recno]>Edit</a>]";
     } else {
      print "<FORM action=qa.php method=post>";
      print "<input type=hidden name=current value=$current>";
      print "<input type=hidden name=recno value=$editit>";
      print "#<input type=text name=ordernum value=\"$row[ordernum]\"> <input type=text name=question value=\"$row[question]\">";
      print "<input type=submit name=whatdo value=UPDATE>";
      print "<input type=submit name=whatdo value=REMOVE>";            
      print "</FORM>";
     }
     print "</td></tr>";
     if( in_array($row[recno],$mypath) ){
       showtree($row[recno],$depth+1);       
     }
  }

}
?>
<body bgcolor=FFFFEE><center>
<?
if($answer != ""){
  $query = "SELECT * FROM livehelp_qa WHERE recno='$answer' AND typeof='question'";
  $question = $mydatabase->select($query); 
  $question = $question[0];
  $query = "SELECT * FROM livehelp_qa WHERE parent='$answer' AND typeof='answer'";
  $question_a = $mydatabase->select($query); 
  if( count($question_a) == 0){ $answeredit = 1; }
  $question_a = $question_a[0];
?>
<table width=100%>
<tr bgcolor=EFEF9D>
<td colspan=2><b>Question:</b> <?= $question[question] ?></b></td></tr>
<tr bgcolor=FFFFEE>
<td colspan=2>
<img src=images/help_a.gif width=18 height=16><b><?= $lang_answer ?>:</b><br>
<table width=90%><tr><td bgcolor=FFFFC0>

<?
if ($answeredit == 1){ 
?>
<FORM ACTION=qa.php METHOD=POST>
<input type=hidden name=whatdo value=UPDATE>
<input type=hidden name=answer value=<?= $answer ?> >
<input type=hidden name=current value=<?= $current ?> >
<? if($question_a[recno] == ""){ print "<input type=hidden name=newinsert value=yes>\n"; $defaultif = " CHECKED "; } ?>
<input type=hidden name=recno value=<?= $question_a[recno] ?> >
<textarea cols=65 rows=9 name=question><?=$question_a[question]?></textarea><br>
<input type=checkbox name=nltobr value=YES <?=$defaultif?> >Convert new lines to breaks.<br>
<input type=submit value=UPDATE>
</FORM>
<?
} else {
 print "$question_a[question] ";
 print "<br><a href=qa.php?current=$current&answer=$answer&answeredit=1>EDIT THIS</A><br><br><br>";
 print "<br><a href=qa.php?current=$current&answer=$answer&whatdo=REMOVE&recno=$question_a[recno]>REMOVE THIS</A><br><br><br>"; 
}

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
 print "<br><a href=qa.php?current=$current&answer=$answer&whatdo=REMOVE&recno=$comment[recno]>REMOVE THIS</A><br><br><br>"; 

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
<a href=qa.php>top</a>
<FORM action=qa.php method=POST>
<input type=hidden name=current value=<?= $current ?> >
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
<input type=submit value=REORDER name=whatdo></form>
<hr>
Create in Current open Catigory:<br>
<form action=qa.php method=POST>
<input type=hidden name=current value=<?= $current?> >
+<img src=images/help_folder.gif width=18 height=16> new folder: <input type=text name=newfolder size=15><input type=submit value=ADD><br>
+<img src=images/help_q.gif width=18 height=16> new Question: <input type=text name=newquestion size=45><input type=submit value=ADD><br>


<p><p>
<!--
<table width=100%>
<tr bgcolor=EFEF9D>
<td><b>Un-answered Questions:</b></td>
</tr></table>
-->
<?
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