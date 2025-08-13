<?
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : GhostDriver TGP  (Random Gallery Rotator)        //
//   Release Version      : 0.2                                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//           Random Gallery Rotator  (c) Copyright  Nibbi `2002              //
//                    Copyright  WTN Team `2000 - `2002                      //
//                                                                           //
///////////////////////////////////////////////////////////////////////////////
include ("settings.inc.php");
if($V34ec78fc){
 $Vbb3ccd58 = explode("\n", $V2a1585a8);
$Vacbd18db = count($Vbb3ccd58);
$V865c0c0b=0; 
while ($V865c0c0b<$Vacbd18db){
 $Vd7271b0e = explode(",", $Vbb3ccd58[$V865c0c0b]);
$V572d4e42 = trim($Vd7271b0e[0]);
$Vc4ef352f = trim($Vd7271b0e[1]);
$V67daf92c = trim($Vd7271b0e[2]);
$V62b8a2e8 = trim($Vd7271b0e[3]);
$V4ca5d171 = trim($Vd7271b0e[4]);
$V599dcce2 = trim($Vd7271b0e[5]);
$V3039627b = trim($Vd7271b0e[6]);
$Vcb5e100e = "<font color=green>Ok</font><br>";
$V77ddcb5f = "Yes";

if($Vba4b6dbd == 'Yes'){
 $V442f62ba = mysql_query("SELECT * from TgpRotate where url = '$V572d4e42'");
$V0fc3cfbc = @mysql_num_rows($V442f62ba);
If ($V0fc3cfbc) {
 $Vcb5e100e = "<font color=red>Rejected Duplicate Post</font><br>";
$V77ddcb5f = "dupe";
}
} 
 $V30646579 = @fopen("$Vd7271b0e[0]", "r");
if(!$V30646579){ 
 $Vcb5e100e = "<font color=red>Bad URL</font><br>";
$V77ddcb5f = "404";
}else{
 while(!feof($V30646579)){
 $V7cef8a73= htmlspecialchars(fgets($V30646579, 1024));
if($V21bc3bdb == 'Yes'){
 $V02d6c730 = substr_count($V7cef8a73, "window.open");
if($V02d6c730){
 $Vcb5e100e = "<font color=red>Rejected Pop-Up Found</font><br>";
$V77ddcb5f = "Pop";
}
}

 if($Vaf2febe3 == 'Yes'){
 $Ve5767085 = substr_count($V7cef8a73, "changecolor(");
if($Ve5767085){
 $Vcb5e100e = "<font color=red>Rejected Flash</font><br>";
$V77ddcb5f = "Flash";
}
} 
 }
} 
 
 $V67daf92c = strtolower($V67daf92c);
$V67daf92c = ucwords($V67daf92c); 
 mysql_query("INSERT into TgpRotate (url, category, description, numpic, vote, stat, type, picname) VALUES ('$V572d4e42', '$Vc4ef352f', '$V67daf92c', '$V62b8a2e8', '$V4ca5d171', '$V77ddcb5f', '$V599dcce2', '$V3039627b')");
echo "$Vd7271b0e[0] $Vcb5e100e";
flush();
$V865c0c0b++;
}
echo "Gallerie(s) import complete.";
exit();
}
?>
<html>
<head>
<title>GhostRiderTGP Script</title>
</head>
<body bgcolor="ffffff">
<table width=100% border=1 cellspacing=3 cellpadding=5 align=center>
 <tr bgcolor=ffffff> 
 <td colspan=8>
<div align=center><font color=CCCC99><b><font size=6>GhostDriver 
Import Tool</font></b></font></div>
<form name="form" method="post" action="add.php">
 <? 
if ($V3d801aa5) {
echo "<input type=\"hidden\" name=\"V2a1585a8\" value=\"$Vbb3ccd58\">
 <tr bgcolor=ffffff> 
 <td colspan=7 height=30> 
 <div align=center><b><font color=CCCC99>Import Information</font></b></div>
 </td>
 </tr>
 <tr> 
 <td> 
 <div align=left><b><font color=CCCC99>URL</b></font></div>
 </td>
 <td> 
 <div align=left><b><font color=CCCC99>Category</b></font></div>
 </td>
 <td> 
 <div align=left><b><font color=CCCC99>Description</b></font></div>
 </td>
 <td> 
 <div align=left><b><font color=CCCC99># Images</b></font></div>
 </td>
 <td> 
 <div align=left><b><font color=CCCC99>Vote</b></font></div>
 </td>
 <td> 
 <div align=left><b><font color=CCCC99>Type</b></font></div>
 </td> 
 <td> 
 <div align=left><b><font color=CCCC99>PicName</b></font></div>
 </td>
 </tr>";
$Vbb3ccd58 = explode("\n", $Vbb3ccd58);
$Vacbd18db = count($Vbb3ccd58);
$V865c0c0b=0; 
 while ($V865c0c0b<$Vacbd18db){
 $Vd7271b0e = explode(",",$Vbb3ccd58[$V865c0c0b]); 
 echo " <tr>
 <td><font color=000000>$Vd7271b0e[0]</font></td>
 <td><font color=000000>$Vd7271b0e[1]</font></td>
 <td><font color=000000>$Vd7271b0e[2]</font></td>
 <td><font color=000000>$Vd7271b0e[3]</font></td>
 <td><font color=000000>$Vd7271b0e[4]</font></td>
 <td><font color=000000>$Vd7271b0e[5]</font></td>
 <td><font color=000000>$Vd7271b0e[6]</font></td>
 </tr>";
$V865c0c0b++;
}
echo "</table>";
echo "<p>
 <center><input type=\"submit\" name=\"V34ec78fc\" value=\"Yes, I'm sure this info is correct\"></center>
 </p>"; 
 exit();
}
?>
 <font color=000000> Comma delimited list in this order - URL, Category, 
 Description, Number of Images, Vote, Type, Image Name 
 <p><b>Example:</b> http://www.testing.com/foobar.html, Amateurs, Hot Amateurs, 16, 5, 
 Thumb, 001.jpg</p>
 </font>
 <p>
 <div align=center><textarea name="Vbb3ccd58" cols="90" rows="30"></textarea></div>
 </p>
 <p>
 <div align=center><input type="submit" name="V3d801aa5" value="Submit"></div>
 </p>
</form>
</td>
</tr>
</table>
</body>
</html>