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

$timeof = date("YmdHis");

checkuser();

function whatdep($id){
  global $mydatabase;
  $query = "SELECT * FROM livehelp_departments WHERE recno='$id'";
  $dat = $mydatabase->select($query);
  $myrow = $dat[0];
  return  $myrow[nameof];
}

if($myid == ""){
  // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE username='$username'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
}

if($what == "SAVE"){
 if($editidnum==""){
  $query = "INSERT INTO livehelp_autoinvite (isactive,department,message,page,visits,referer) VALUES ('Y','$department','$comment','$page','$visits','$referer')";
  $mydatabase->insert($query);  	
 } else {
  $query = "UPDATE livehelp_autoinvite SET department='$department',message='$comment',page='$page',visits='$visits',referer='$referer' WHERE idnum='$editidnum' "; 
  $mydatabase->sql_query($query);   	
 }
}
if($what == "UPDATE"){
  
  $query = "SELECT * FROM livehelp_autoinvite";
  $data = $mydatabase->select($query);
  for($i=0;$i< count($data); $i++){
    $row = $data[$i];
    $varname = "isactive__" . $row[idnum]; 
    if($$varname != "") {  
        $query = "UPDATE livehelp_autoinvite set isactive='Y' WHERE idnum='$row[idnum]' ";
        $mydatabase->sql_query($query);
    } else {
        $query = "UPDATE livehelp_autoinvite set isactive='N' WHERE idnum='$row[idnum]' ";
        $mydatabase->sql_query($query);    	
    }
  }
  
}

if($what == "REMOVE"){
        $query = "DELETE FROM livehelp_autoinvite WHERE idnum='$which' ";
        $mydatabase->sql_query($query);  
}

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<body bgcolor=E0E8F0 onload=window.focus()>

<table width=100% bgcolor=FFFFC0><tr><td><b>Auto Invite Users for Chat :</b></td></tr></table>
Auto Invites monitors the visitors to the site and
when a visitor matching the criteria listed below is met the visitor
is auto invited for a chat. 
You can Create monitors to auto invite visitors based on 
either what referer they came from, and/or how many pages they have viewed, and/or
what page they are looking at, and/or What department they are in.
<br>
<?
if ( ($createnew == "") && ($what != "EDIT") ){ 
?>
<b>Current Auto Invite Monitors:</b>
<form action=autoinvite.php method=post>
<table width=555>
<tr bgcolor=FFFFCC><td><b>Active</b></td><td><b>Invite:</b></td><td><b>Options</b></td></tr>
<?
$query = "SELECT * FROM livehelp_autoinvite ";
$data = $mydatabase->select($query);
if( count($data) == 0){
  print "<tr><td colspan=3 bgcolor=FFFFFF align=center><font color=990000>There are currently NO monitors setup...</font></td></tr>";  	
} else {
 for($i=0;$i< count($data); $i++){
  $row = $data[$i];
  if($bgcolor=="FEFEFE"){ $bgcolor="CDCDCD";  } else { $bgcolor="FEFEFE"; }
  if($row[isactive] =="Y"){ $isactive = " CHECKED "; } else { $isactive = " "; }
  print "<tr bgcolor=$bgcolor><td><input type=checkbox name=isactive__$row[idnum] value=Y $isactive></td><td>";
  print "<table width=100% bgcolor=FFFFEE><tr><td> ";
  if($row[department]!= 0){ print "<b>Matching Department:</b><font color=000077>" . whatdep($row[department]) . "</font><br>"; }
  if($row[page]!= ""){ print "<b>Matching Page:</b><font color=000077>$row[page]</font><br>"; }
  if($row[referer]!= ""){ print "<b>Matching Referer:</b><font color=000077>$row[referer]</font><br>"; }
  if($row[visits] > 1){ print "<b>Matching Visits:</b><font color=000077>$row[visits]</font><br>";  }
  print "</td></tr></table>";
  print "$row[message]</td><td><a href=autoinvite.php?what=EDIT&which=$row[idnum]>EDIT</a> <br><Br><br> <a href=autoinvite.php?what=REMOVE&which=$row[idnum]><font color=990000>Delete</font></td></tr>";	
 }

}
?>
</table>
<table><tr><td><input type=submit name=what value=UPDATE></td><td><input type=submit name=createnew value="CREATE NEW MONITOR"></td></tr></table>
<br>

<? } else { ?>
<form action=autoinvite.php name=chatter method=post><?
if ($what == "EDIT"){ 
 $query = "SELECT * FROM livehelp_autoinvite WHERE idnum='$which'";
 $row = $mydatabase->select($query);
 $item = $row[0];
 print "<input type=hidden name=editidnum value=$which>\n";
}

?>

<h2>ADD/EDIT Auto Invite Monitor:</h2>
<b>Auto invite Visitors that are...</b>
<table>
<tr><td><b>in the Department:</b></td><td>
<select name=department>
<option value=0>ANY</option>
<?
 $query = "SELECT * FROM livehelp_departments ";
 $data = $mydatabase->select($query);
 for($i=0;$i<count($data);$i++){
   $row = $data[$i];
   print "<option value=$row[recno] ";
   if($item[department] == $row[recno]){ print " SELECTED "; }
   print ">$row[nameof]</option>\n";
 }
?>
</select>
</td></tr>
</table>
<b>and are looking at the webpage url of :</b><br>
(Leave blank for any page. if filled in this should be something
like mywebsite.com/page.htm )<br>

<input type=text size=45 name=page value="<?= $item[page]?>" ><br>
<b>and came from the Referer :</b><br>
(Leave blank for any page. if filled in this should be something
like mywebsite.com/page.htm )<br>
<input type=text size=45 name=referer  value="<?= $item[referer]?>" ><br>
<table>
<tr><td><b>and have visited:</b></td><td>
<select name=visits>
<option value="<?= $item[visits]?>"><?= $item[visits]?></option>
<?
for($i=1; $i< 30; $i++){
print "<option value=$i>$i</option>\n";
}
?>

</select> of my webpages.
</td></tr>
</table>
<br>

<br>

<input type=hidden name=typing value="no">
<input type=hidden name=channelsplit value="<?= $channelsplit ?>" > 
<b>Message:</b>
<input type=hidden name=user_id value="<?= $myid ?>">
<input type=hidden name=alt_what value="">
<SCRIPT>
function autofill(num){
   if(num == 0){ document.chatter.comment.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' AND typeof!='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='$row[message]'; document.chatter.editid.value='$row[id]';  }\n";	
 } ?>
}
function autofill_url(num){
   if(num == 0){ document.chatter.comment.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='[SCRIPT]openwindow(\'$row[message]\',\'window$row[id]\');[/SCRIPT]';  }\n";	
 } ?>
}

function openwindow(url){ 
 window.open(url, 'chat540', 'width=572,height=320,menubar=no,scrollbars=0,resizable=1');
}

function autofill_image(num){
   if(num == 0){ document.chatter.comment.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='<img src=$row[message]>';  }\n";	
 } ?>
}

</SCRIPT><br>
<input type=hidden name=timeof value=<?= $timeof ?> >
<input type=hidden name=editid value="">
<textarea cols=40 rows=3 name=comment ><?= $item[message]?>
</textarea><input type=submit name=what value=SAVE>
<br>
</form>
<br>
<table width=100%>
<tr><td colspan=3>
<table width=100% bgcolor=FFFFC0><tr><td><b>Additional Options and Actions:</b></td></tr></table>
<img src=images/blank.gif width=300 height=1><br>
</td></tr>
<tr><td><b>Push Url</b>:</td><td>
<select name=url  onchange=autofill_url(this.selectedIndex)><option value=-1 >pick something:</option>
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select>
</td><td><a href=javascript:openwindow('edit_quick.php?typeof=URL')>Edit URLS</a></td></tr>
<tr><td><b>Push Image:</b></td><td>
<select name=image onchange=autofill_image(this.selectedIndex)><option value=-1 >pick something:</option>
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select>
</td><td><a href=javascript:openwindow('edit_quick.php?typeof=IMAGE')>Edit Images</a></td></tr>
<tr><td colspan=3><b>Load Quick Note:</b><br>
<select name=quicknote onchange=autofill(this.selectedIndex)>
<option value=-1 >pick something:</option>
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' AND typeof!='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select> <a href=javascript:openwindow('edit_quick.php')>Edit Quick Notes</a><br>
</td>
</tr></table>
<br>
</body>
<?
$mydatabase->close_connect();
}
?>