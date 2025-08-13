<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";

if(!$frame){
//check that this chat is active!
if(mysql_num_rows(mysql_query("SELECT * FROM chats WHERE id='$id' && lastdate>'".(time()-10000)."' OR lastdate='0'"))){
echo '<HTML>
<head>
<title>Client Chat</title>
</head>
<frameset rows="*,60,100" noresize>
<frame name="chat_window" src="chat.php?lastdate='.$lastdate.'&frame=chat&id='.$id.'" frameborder="0">
<frame src="chat.php?frame=control&id='.$id.'" frameborder="0">
<frame src="chat.php?frame=info&id='.$id.'" frameborder="0">
</frameset>
</HTML>';
}else{
echo "Invalid or inactive chat!";
}
}

if($frame=="chat"){
//update that they have refreshed this window so they are not offline!
mysql_query("UPDATE chats SET client_last='".time()."' WHERE id='$id'");
//now check if the other person is offline!
if(mysql_num_rows(mysql_query("SELECT * FROM chats WHERE id='$id' && admin_last<'".(time()-20)."'"))){
$dataadd='--435323400032nn324ol320920324n32002--'.time().'--33--239324b20921--system--33--239324b20921--<font color="#666666">----Please wait for a support technician..';
$info=mysql_fetch_array(mysql_query("SELECT * FROM chats WHERE id='$id'"));
$data=$info[data].$dataadd;
mysql_query("UPDATE chats SET data='$data', lastdate='".time()."', admin_last='".(time()+180)."' WHERE id='$id'");
}
$info=mysql_fetch_array(mysql_query("SELECT * FROM chats WHERE id='$id'"));
if($lastdate>$info[lastdate]){
//no updates
header("HTTP/1.1 204");
exit;
}else{
echo '<html><body><table cellpadding="1">';
$info=mysql_fetch_array(mysql_query("SELECT * FROM chats WHERE id='$id'"));
$lines=explode("--435323400032nn324ol320920324n32002--", $info[data]);
foreach($lines as $line){
if($line){list($time, $person, $chat)=explode("--33--239324b20921--", $line);
$font="";if($person=="client"){$person=$info[client_name];}elseif($person=="admin"){$person="Support"; $font='<font color="'.$admin_color.'">';}else{$person="System";}
echo '<tr><td width=120><font face="'.$admin_font.'" size="1"><B>'.$person.'</B>('.date("g:i a", $time).'): </td><td><font face="'.$admin_font.'" size="1">'.$font.$chat.'</font></td></tr>';}}
echo '<a name="thebottom">';
$lastdate=time();
echo '</table><a name="bottom"></a>
<script language="javascript">
  <!--
  function refreshchat(){
  parent.frames[0].location="chat.php?lastdate='.$lastdate.'&frame=chat&id='.$id.'#bottom"  
  }
  setInterval("refreshchat()", 10000);
  // -->
</script>';
}}

if($frame=="info"){
echo '<font face="'.$admin_font.'" size="1"><B>Chat info:<BR></B>';
$info=mysql_fetch_array(mysql_query("SELECT * FROM chats WHERE id='$id'"));
echo 'Client Name: '.$info[client_name];
echo '<BR>Last Message: '.date("F j, Y, g:i a", $info[lastdate]);
}

if($frame=="control"){
if($message){
$info=mysql_fetch_array(mysql_query("SELECT * FROM chats WHERE id='$id'"));
$dataadd='--435323400032nn324ol320920324n32002--'.time().'--33--239324b20921--client--33--239324b20921--'.$message;
$data=$info["data"].$dataadd;
mysql_query("UPDATE chats SET data='$data', lastdate='".time()."' WHERE id='$id'");
echo '<script language="javascript">
  <!--
  parent.frames[0].location="chat.php?frame=chat&id='.$id.'#bottom"
  // -->
</script>';
}
echo '<table width=100% cellspacing="0">
<tr height=1 bgcolor="'.$admin_color_2.'"><td></td><td></td></tr>
<tr height=3><td></td><td></td></tr>
<form method="get" action="chat.php"><input type=hidden name=frame value="'.$frame.'"><input type=hidden name=id value="'.$id.'"><tr height=1><td align="right"><font face="'.$admin_font.'" size="2">Message: </td><td><input name=message size=35 type=text></td></form></tr>
</table>
';
}

?>


