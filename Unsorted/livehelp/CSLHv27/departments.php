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
if($isadminsetting != "Y"){
 print "sorry only admin users have access to this... ";
$mydatabase->close_connect();
 exit;	
}
?>
<body bgcolor=FFFFEE><center>
<?
function  showdetails($row,$update){
  global $mydatabase,$edit,$lang_online_image,$lang_offline_image,$lang_qa_image,$lang_qa_section,$lang_askname,$lang_hide_icon,$lang_email,$lang_credit,$lang_offline_mess,$lang_opening_message;
?>
 <tr><td colspan=3>
 <FORM ACTION=departments.php METHOD=POST>
<? if($update ==0){ ?>
 <input type=hidden name=updateit value=<?=$edit?>>
<? } else { ?>
<h2>Create a new Department:</h2>
 <input type=hidden name=createit value=yes>
<? } ?>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Department Name:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
This is the name of the department.
</td></tr></table>
<b>Name:</b><input type=text size=55 name=nameof value="<?=$row[nameof]?>">
<br><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Active Tabs Shown for this department:</b>
</td></tr></table>
Check the checkboxes next to the tabs you want to be visable for
this department. Also Check a checkbox for which tab to show 
when no operators are online.
<?
$query = "SELECT * FROM livehelp_modules ";
$data_tab = $mydatabase->select($query);
print "<table>";
for($i=0;$i< count($data_tab); $i++){
 $row_tab = $data_tab[$i];
 if($bgcolor=="EFEF9D"){ $bgcolor="FFFFC0"; } else { $bgcolor="EFEF9D"; }
 $sql = "SELECT * FROM livehelp_modules_dep WHERE departmentid='$edit' and modid='$row_tab[id]'";
 $tmp = $mydatabase->select($sql);
 if( $edit == ""){
  $sql = "SELECT * FROM livehelp_modules_dep";
  $tmp = $mydatabase->select($sql); 
 }
 $row2 = $tmp[0];
 if(( count($tmp) != 0) ) { $isselected = " CHECKED "; } else { $isselected = "  "; }
 if($row2[defaultset] == "Y"){ $isdefault = " CHECKED "; } else { $isdefault = " "; }
 print "<tr><td bgcolor=$bgcolor><b>$row_tab[name]</b></td><td>Active: <input type=checkbox name=modules_$row_tab[id] value=Y $isselected></td><td> order number: <input type=text size=3 name=modules_ord_$row_tab[id] value=\"$row2[ordernum]\"> </td><td>default to when offline: <input type=checkbox name=modules_def_$row_tab[id] value=Y $isdefault></td> </tr>\n";
}
print "</table><br><input type=submit value=UPDATE><br>";
?>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Online Image:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_online_image ?>
</td></tr></table>
<img src=<?= $row[onlineimage] ?>><br>
<b>Url:</b><input type=text size=55 name=onlineimage value="<?=$row[onlineimage]?>">
<br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Leave Message Image:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_offline_image ?>
</td></tr></table>
<img src=<?= $row[offlineimage] ?>><br>
 <b>Url:</b><input type=text size=45 name=offlineimage value="<?=$row[offlineimage]?>">
<br>

<?
if($row[needname] == "no"){
 $needname_s_y  = ""; 
 $needname_s_n  = " CHECKED ";  
} else {
 $needname_s_y  = " CHECKED "; 
 $needname_s_n  = "";  	
}
if($row[leaveamessage] == "no"){
$leaveamessage_s_n = " CHECKED ";
$leaveamessage_s_y = "";
} else {
$leaveamessage_s_n = "";
$leaveamessage_s_y = " CHECKED ";	
}
if($row[qa_enabled] == "N"){
$qa_enabled_s_n = " CHECKED ";
$qa_enabled_s_y = "";
} else {
$qa_enabled_s_n = "";
$qa_enabled_s_y = " CHECKED ";	
}
if(($row[creditline] == "W") || ($row[creditline] == "") ){ $credit_w = " CHECKED "; } 
if($row[creditline] == "L"){ $credit_l = " CHECKED "; }
if($row[creditline] == "N"){ $credit_n = " CHECKED "; }
 
 
?>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Ask for name on load:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_askname ?></b>
</td></tr></table>
<br>
<b>Ask for name on load:</b>Yes <input type=radio name=requirename value=YES  <?= $needname_s_y ?>  > no <input type=radio name=requirename value=no <?= $needname_s_n ?> ><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Hide Icons if not online:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_hide_icon ?></b>
</td></tr></table>
<b>Hide Icons if not online:</b> no <input type=radio name=leaveamessage value=YES  <?= $leaveamessage_s_y ?>  > Yes <input type=radio name=leaveamessage value=no   <?= $leaveamessage_s_n ?> >
<table width=100% bgcolor=FFFFC0><tr><td>
<b>message email:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_email ?></b>
</td></tr></table>
<b>message email:</b><input type=text size=25 name=messageemail value="<?=$row[messageemail]?>"><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>opening message:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_opening_message ?></b>
</td></tr></table>
opening message if ask for name is set to Yes:<br>
<textarea name=opening rows=7 cols=40><?= $row[opening] ?></textarea><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Offline message:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_offline_mess ?> </b>
</td></tr></table>
Offline message to show when not online:<br>
<textarea name=offline rows=7 cols=40><?= $row[offline] ?></textarea><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Program Credit line:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
<?= $lang_credit ?> </b>
</td></tr></table>
<b>credit line:</b><br>
<input type=radio name=creditline value=L  <?= $credit_l ?>  > <img src=livehelp.gif><br>
<input type=radio name=creditline value=W  <?= $credit_w ?>  > <img src=livehelp2.gif><br>
<input type=radio name=creditline value=N  <?= $credit_n ?>  > <b>(none)</b><img src=images/blank.gif><br>

<? if($update ==0){ ?>
<input type=submit value=UPDATE>
<? } else { ?>
<input type=submit value=CREATE>
<? }?>
</form>
</td></tr>
<?  	
}

if($createnew != ""){
  $query = "SELECT * FROM livehelp_departments ";
  $data = $mydatabase->select($query);
  $row = $data[0];
  ?><table width=600><tr><td><?
  $row[nameof] = " ";
  showdetails($row,1);
  ?></td></tr></table><?
  exit;
}

if($createit != ""){
$query = "INSERT INTO livehelp_departments (nameof,offline,opening,messageemail,leaveamessage,requirename,qa_topic,qa_enabled,qaimage,onlineimage,offlineimage) VALUES ('$nameof','$offline','$opening','$messageemail','$leaveamessage','$requirename','$qa_topic','$qa_enabled','$qaimage','$onlineimage','$offlineimage') ";
$mydatabase->insert($query);	
print "<table width=500 bgcolor=FFFFC0><tr><td><b>Database Updated.</b></td></tr></table>";
}

if($removeit !=""){
$query = "DELETE FROM livehelp_departments WHERE recno='$removeit'";
$mydatabase->sql_query($query);
}

if($updateit != ""){
$query = "UPDATE livehelp_departments set creditline='$creditline',nameof='$nameof',offline='$offline',opening='$opening',messageemail='$messageemail',leaveamessage='$leaveamessage',requirename='$requirename',qa_topic='$qa_topic',qa_enabled='$qa_enabled',qaimage='$qaimage',onlineimage='$onlineimage',offlineimage='$offlineimage' WHERE recno='$updateit'";
$mydatabase->sql_query($query);

// clear old tabs.
$query = "DELETE FROM livehelp_modules_dep WHERE departmentid='$updateit'";
$mydatabase->sql_query($query);

// add tabs. 
  $query = "SELECT * FROM livehelp_modules";
  $data = $mydatabase->select($query);
  for($i=0;$i< count($data); $i++){
    $row = $data[$i];
    $varname = "modules_" . $row[id]; 
    $varname_ord = "modules_ord_" . $row[id]; 
    $varname_def = "modules_def_" . $row[id];     
    if($$varname != "") {  
        $ord_ = $$varname_ord;        
        $def_ = $$varname_def;
         $query = "INSERT INTO livehelp_modules_dep (modid,departmentid,ordernum,defaultset) VALUES ('$row[id]','$updateit','$ord_','$def_')";	
        $mydatabase->insert($query);
    }	
  }


print "<table width=500 bgcolor=FFFFC0><tr><td><b>Database Updated.</b></td></tr></table>";
}


if($help == 1){
print "<table width=500 bgcolor=FFFFC0><tr><td><b>Select the link that reads <i><u>HTML CODE</u></i> next to the department you wish to create Live help HTML for.</td></tr></table>";
}
?>
<br><br>
<table bgcolor=DDDDDD width=600><tr><td>
<b>Departments:</b>
</b></td></tr></table>
<table width=600>
<tr bgcolor=FFFFFF><td><b>Department Name</b></td><td><b>Options:</b></td></tr>
<?
$query = "SELECT * FROM livehelp_departments ";
$data = $mydatabase->select($query);
for($i=0;$i< count($data); $i++){
 $row = $data[$i];
   if($bgcolor=="EFEF9D"){ $bgcolor="FFFFC0"; } else { $bgcolor="EFEF9D"; }
 print "<tr bgcolor=$bgcolor><td><b>$row[nameof]</b></td><td NOWRAP> <a href=departments.php?edit=$row[recno]>Settings</a> | <a href=departments.php?html=$row[recno]>HTML CODE</a> | ";
 if(count($data) != 1){
 	print "<a href=departments.php?removeit=$row[recno] onClick=\"return confirm('Are you sure you want to Remove this Department ?!?')\"><font color=990000>REMOVE</font>";
 } 
 print "</td></tr>";
 if($row[recno] == $edit){
 showdetails($row,0);
 }
 if($row[recno] == $html){
 print "<tr><td colspan=3 width=500>";
 ?>
<?= $lang_how_to_add ?>
<br>
<table width=700 bgcolor=FFFFC0 border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- Powered by: Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/ --&gt;<br>
&lt;script language="javascript" src="<?= $webpath ?>livehelp_js.php?department=<?= $row[recno] ?>"&gt;&lt;/script&gt;<br>
&lt;!-- copyright 2003 by Eric Gerdes --&gt;<br><br>
</b></td></tr></table><br><br>
<?= $lang_how_to_add2 ?><br>
<table width=700 bgcolor=FFFFC0 border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- Powered by: Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/ --&gt;<br>
&lt;a href="<?= $webpath ?>livehelp.php?department=<?= $row[recno] ?>"&gt;&lt;/a&gt;<br>
&lt;!-- copyright 2003 by Eric Gerdes --&gt;<br>
</b><br><br>
</td></tr></table><br><br><bR>
<?= $lang_how_to_add3 ?>
<table width=700 bgcolor=FFFFC0 border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- Powered by: Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/ --&gt;<br>
&lt;script language="javascript" src="<?= $webpath ?>livehelp_js.php?cmd=hidden&department=<?= $row[recno] ?>"&gt;&lt;/script&gt;<br>
&lt;!-- copyright 2003 by Eric Gerdes --&gt;<br>
</b><br><br>
</td></tr></table><br><br><bR>
<?
 print "</td></tr>";
 }
}
?>
<table bgcolor=DDDDDD width=600><tr><td>
&nbsp;
</b></td></tr></table>
<a href=departments.php?createnew=yes>Create a new Department</a>
<br>
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