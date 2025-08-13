<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";
include "auth.php";
include "header.php";

mysql_query("UPDATE quick_msg SET handled='1' WHERE id='$handled'");

echo '<font face="'.$admin_font.'" size="2">
<table width=100%><tr><td width=50% valign=top >';
//quick msg's
$res=mysql_query("SELECT * FROM quick_msg WHERE admin_id='$admin_id' ORDER BY handled, date DESC LIMIT 10");
	echo '<font face="'.$admin_font.'" size="2"><B>:::: New Quick Msgs ::::</B><P><font size="1">';
while($r=mysql_fetch_array($res)){
echo '<B>'.$r[subject].'</b> - <i>'.date("F j, Y, g:i a", $r[date]).'</i><BR>'.nl2br($r[message]).'<BR>';
if(!$r[handled]){echo '<a href="index.php?handled='.$r[id].'">Mark as Handled</a>';}
echo '<hr color="'.$admin_color_2.'"><P>';
}

echo '</td><td width=1 bgcolor="'.$admin_color_2.'"></td><td valign=top width=50% align=left>';
//check for updates!
@$fp=fopen($admin_update_check, "r");
if($fp){while(!feof($fp)){
$updatedata.=fgets($fp, 1024);
}}
if($updatedata!=$thisversion){
echo '</font>';
}
	

	
//news...
	echo '<font face="'.$admin_font.'" size="2"><B>:::: News ::::</B><P>';
@$fp=fopen($admin_news_page, "r");
if($fp){while(!feof($fp)){
echo fgets($fp, 1024);
}}
	
echo '</td></tr></table>';



include "footer.php";
?>
