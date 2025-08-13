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
if (!file_exists("settings.inc.php")) {
 die ("Can not locate settings.inc.php");
} else {
 if (!is_writeable("settings.inc.php")) {
 die ("Unable to write to config.inc.php. Please make sure that you chmod it to 766.");
}
}
if(isset($Veb399bca)){
$V6820603f = "html_galtmpl.txt";
$Vf5d78585 = fopen($V6820603f, "w");
$V83f1e3e3 = stripslashes($V83f1e3e3);
fputs($Vf5d78585,$V83f1e3e3);
fclose($Vf5d78585);
$V18ce02ad = "phtml_galtmpl.txt";
$Vd8f2e30c = fopen($V18ce02ad, "w");
$Vbe9fcf63 = stripslashes($Vbe9fcf63);
fputs($Vd8f2e30c,$Vbe9fcf63);
fclose($Vd8f2e30c);

 if(!$Va27b6f7f){ $Va27b6f7f = "m/d"; }
if(isset($Vcdddb6db)){
 $V7adc785b = $Vf038c0c9; 
 }else{
 $V7adc785b = $V7adc785b;
}
$V78e73102 = "<b><font color=red>Settings Updated!</font></b>";
$Va7295612[] = "<?\n";
$Va7295612[] = "\$Vfe01ce2a = '$Vfe01ce2a';\n";
$Va7295612[] = "\$V7adc785b = '$V7adc785b';\n";
$Va7295612[] = "\$V8642fb61 = '$V8642fb61';\n";
$Va7295612[] = "\$Vd77d5e50 = '$Vd77d5e50';\n";
$Va7295612[] = "\$V6402673d = '$V6402673d';\n";
$Va7295612[] = "\$Vc4cf7065 = '$Vc4cf7065';\n";
$Va7295612[] = "\$V575f64c6 = '$V575f64c6';\n";
$Va7295612[] = "\$Ve332b677 = '$Ve332b677';\n";
$Va7295612[] = "\$V6f7e0389 = '$V6f7e0389';\n";
$Va7295612[] = "\$Vba4b6dbd = '$Vba4b6dbd';\n";
$Va7295612[] = "\$V13dabcba = '$V13dabcba';\n";
$Va7295612[] = "\$V89001b0f = '$V89001b0f';\n";
$Va7295612[] = "\$V1aee9883 = '$V1aee9883';\n";
$Va7295612[] = "\$V6a1ed5b1 = '$V6a1ed5b1';\n";
$Va7295612[] = "\$V1a2d9e84 = '$V1a2d9e84';\n";
$Va7295612[] = "\$Va27b6f7f = '$Va27b6f7f';\n";
$Va7295612[] = "\$Vc78e688e = date('$Va27b6f7f');\n";
$Va7295612[] = "\$Ved09110d = '$Ved09110d';\n";
$Va7295612[] = "\$V5fc73231 = 'date(\"Ymd\");';\n";
$Va7295612[] = "\$Vf1965a85 = '$Vf1965a85';\n";
$Va7295612[] = "\$V255a5cac = '$V255a5cac';\n";
$Va7295612[] = "\$V2de42fbb = '$V2de42fbb';\n";
$Va7295612[] = "\$Vc73291af = '$Vc73291af';\n";
$Va7295612[] = "\$V42a76fcb = '$V42a76fcb';\n";
$Va7295612[] = "\$Va70fcfcf= '$Va70fcfcf';\n";
$Va7295612[] = "\$V0c1d0e2e = @mysql_connect(\"\$V8642fb61\",\"\$V6402673d\",\"\$Vc4cf7065\");
@mysql_select_db(\"\$Vd77d5e50\");\n";
$Va7295612[] = "?>";
if ($V0666f0ac = @ fopen("settings.inc.php" , "w")) {
 $V9efab239 = implode("", $Va7295612);
fwrite($V0666f0ac, $V9efab239);
fclose($V0666f0ac);
}
} 
?>
<?PHP
@include("settings.inc.php");
$V6820603f = "html_galtmpl.txt";
$V7c4850a6 = fopen($V6820603f, "r");
$V60d26a92 = filesize($V6820603f);
$Veae51e3c = fread($V7c4850a6,$V60d26a92);
fclose($V7c4850a6);
$V18ce02ad = "phtml_galtmpl.txt";
$Va521c5ed = fopen($V18ce02ad, "r");
$V2307facb = filesize($V18ce02ad);
$V8c5e3635 = fread($Va521c5ed,$V2307facb);
fclose($Va521c5ed);
$V18206d05 = $_SERVER["PATH_TRANSLATED"];
$V18206d05 = substr("$V18206d05",0,-9); 
echo"<style type=\"text/css\">
BODY {
	MARGIN-TOP: 0px; SCROLLBAR-FACE-COLOR: #ffffff; MARGIN-BOTTOM: 0px; MARGIN-LEFT: 0px;FONT-FAMILY: Tahoma;FONT-SIZE: 12px;
}
</style><body>";
echo "<form name=\"form1\" method=\"post\" action=\"admin.php\">
<input type=\"hidden\" name=\"V7adc785b\" value=\"$V7adc785b\">
<input type=\"hidden\" name=\"V8642fb61\" value=\"$V8642fb61\">
<input type=\"hidden\" name=\"Vd77d5e50\" value=\"$Vd77d5e50\">
<input type=\"hidden\" name=\"V6402673d\" value=\"$V6402673d\">
<input type=\"hidden\" name=\"Vc4cf7065\" value=\"$Vc4cf7065\">
<center>$V78e73102</center>
<table width=650 border=0 cellspacing=0 cellpadding=0 align=center>
 <tr>
 <td valign=top> 
 <table width=650 border=0 cellspacing=0 cellpadding=0>
 <tr>
 <td bgcolor=ffffff>
 <div align=center><font color=CCCC99><b><font size=6>GhostDriver 
 TGP Admin</font></b></font></div><center>
Nullified by WTN Team `2002
 </td>
 </tr>
 </table>
 <table width=100% border=1 cellspacing=3 cellpadding=4>
 <tr bgcolor=ffffff> 
 <td colspan=2><font color=CCCC99><b>Database Settings</b></font></td>
 </tr>
 <tr> 
 <td width=34%> DB Host</td>
 <td width=66%> 
 <input type=text name=V8642fb61 size=40 value=$V8642fb61>
 </td>
 </tr>
 <tr> 
 <td width=34%> Database Name</td>
 <td width=66%> 
 <input type=text name=Vd77d5e50 size=40 value=$Vd77d5e50>
 </td>
 </tr>
 <tr> 
 <td width=34%> Database User</td>
 <td width=66%> 
 <input type=text name=V6402673d size=40 value=$V6402673d>
 </td>
 </tr>
 <tr> 
 <td width=34%> Database Password</td>
 <td width=66%> 
 <input type=text name=Vc4cf7065 size=40 value=$Vc4cf7065>
 </td>
 </tr>
 <tr bgcolor=ffffff> 
 <td colspan=2><font color=CCCC99><b>Demo Mode Setting</b></font></td>
 </tr>
 <tr valign=top> 
 <td width=62%>Run in demo mode? <a href=\"help.html#demo\">Help</a></td>
 <td width=38%> 
 <select name=Vfe01ce2a>
 <option selected value=$Vfe01ce2a>$Vfe01ce2a</option>
 <option value=No>No</option>
 <option value=Yes>Yes</option>
 </select>
 </td>
 </tr>
 <tr bgcolor=ffffff> 
 <td colspan=2><font color=CCCC99><b>Text Link Settings</b></font></td>
 </tr>
 <tr valign=top> 
 <td width=62%>Limit number of galleries displayed <a href=\"help.html#numlist\">Help</a></td>
 <td width=38%> 
 <input type=text name=Ve332b677 size=8 value=$Ve332b677>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Limit description length <a href=\"help.html#dlength\">Help</a></td>
 <td width=38%> 
 <input type=text name=V6f7e0389 size=8 value=$V6f7e0389>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Text Trade script prefix (ie. out.php? url=) <a href=\"help.html#pre\">Help</a></td>
 <td width=38%> 
 <input type=text name=V6a1ed5b1 value=$V6a1ed5b1>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Text Trade script sufix (ie. &link=gal&s=70&first=1) <a href=\"help.html#suf\">Help</a></td>
 <td width=38%> 
 <input type=text name=V1a2d9e84 value=$V1a2d9e84>
 </td>
 </tr> 
 <tr bgcolor=ffffff> 
 <td colspan=2><font color=CCCC99><b>Thumbnail Link Settings</b></font></td>
 </tr>
 <tr valign=top> 
 <td width=62%>Limit number of Thumbnails displayed <a href=\"help.html#pnumlist\">Help</a></td>
 <td width=38%> 
 <input type=text name=Va70fcfcf size=8 value=$Va70fcfcf>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Number of Columns in Table</td>
 <td width=38%> 
 <input type=text name=Vf1965a85 size=8 value=$Vf1965a85>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Table Width in Pixels</td>
 <td width=38%> 
 <input type=text name=V255a5cac size=8 value=$V255a5cac>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Table Border Width</td>
 <td width=38%> 
 <input type=text name=V2de42fbb size=8 value=$V2de42fbb>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Cell Spacing</td>
 <td width=38%> 
 <input type=text name=Vc73291af size=8 value=$Vc73291af>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Cell Padding</td>
 <td width=38%> 
 <input type=text name=V42a76fcb size=8 value=$V42a76fcb>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Thumb Trade script prefix (ie. out.php? url=)</td>
 <td width=38%> 
 <input type=text name=V89001b0f value=$V89001b0f>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Thumb Trade script sufix (ie. &link=gal&s=60)</td>
 <td width=38%> 
 <input type=text name=V1aee9883 value=$V1aee9883>
 </td>
 </tr> 
 <tr valign=top> 
 <td width=62%>Check for duplicate posts on import? <a href=\"help.html#dupe\">Help</a></td>
 <td width=38%> 
 <select name=Vba4b6dbd>
 <option value=$Vba4b6dbd selected>$Vba4b6dbd</option>
 <option value=No>No</option>
 <option value=Yes>Yes</option>
 </select>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Check for pop-up's on gallery import? <a href=\"help.html#pop\">Help</a></td>
 <td width=38%> 
 <select name=V13dabcba>
 <option value=$V13dabcba selected>$V13dabcba</option>
 <option value=No>No</option>
 <option value=Yes>Yes</option>
 </select>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%>Date format. <a href=\"http://www.php.net/manual/en/function.date.php\" target=\"_new\">Click 
 here for more info</a></td>
 <td width=38%> 
 <input type=text name=Va27b6f7f value=$Va27b6f7f>
 </td>
 </tr>
 <tr valign=top> 
 <td width=62%><a href=\"add.php\">Click to Add Galleries to Database</a></td>
 <td width=38%>&nbsp; </td>
 </tr>
 <tr valign=top> 
 <td width=62%>&nbsp;</td>
 <td width=38%>&nbsp;</td>
 </tr>
 <tr valign=top> 
 <td colspan=2> 
 <div align=center> 
 <input type=\"submit\" name=\"Veb399bca\" value=\"Submit\">
 </div>
 </td>
 </tr>
 </table>
 <br>
<table width=650 border=1 cellspacing=3 cellpadding=4>
 <tr bgcolor=ffffff>
 <td>
 <div align=left><font color=CCCC99><b>Update HTML Template For Text Links</font></b></div>
 </td>
 </tr>
 <tr>
 <td height=26>
 <div align=center>
 <textarea name=V83f1e3e3 cols=60 rows=10>$Veae51e3c</textarea><br><a href=\"help.html#html\">Help</a>
 </div>
 </td>
 </tr>
 <tr bgcolor=ffffff>
 <td>
 <div align=left><font color=CCCC99><b>Update HTML Template For Thumb Links</font></b></div>
 </td>
 </tr>
 <tr>
 <td height=26>
 <div align=center>
 <textarea name=Vbe9fcf63 cols=60 rows=10>$V8c5e3635</textarea><br><a href=\"help.html#html\">Help</a>
 </div>
 </td>
 </tr>
 
</table>
 </td>
 </tr>
</table>
</form>";
?>