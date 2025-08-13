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

if($handle){
mysql_query("UPDATE chats SET admin_id='$admin_id' WHERE id='$handle'");	
?><script language="javascript">
           			 
				var clientchatwindow=window.open('chat.php?id=<?echo $handle;?>',<?echo $handle;?>,'width=500,height=500,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');        				 
    window.location="chat_monitor.php?opened=2";
	</script>
    <?
exit;
}

//update the admins table to put in the last time you were online.
$admin=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin_id'"));
mysql_query("UPDATE admins SET online='".time()."' WHERE id='$admin_id'");

$latest=mysql_fetch_array($res=mysql_query("SELECT * FROM chats WHERE client_last>'".(time()-20)."' ORDER BY requested DESC"));
//check if there are any new chat requests!..
if(($admin[online]-10)<$latest[requested] || $opened || mysql_num_rows($res)==0){
//print out a list of all the client chats that are current needing to be answered...
echo '<html><head>
<title>Online - Available for chat</title>
</head><body>';
	echo '<table><tr><td><font face="'.$admin_font.'" size="2"><B>Client Name</td><td><font face="'.$admin_font.'" size="2"><B>Waiting For..</td></tr>';
	$chats=mysql_query("SELECT * FROM chats WHERE admin_id='0' && client_last>'".(time()-20)."' ORDER BY lastdate ASC");
	while($chat=mysql_fetch_array($chats)){
	echo '<tr><td><font face="'.$admin_font.'" size="2">'.$chat[client_name].'</td>';
	$time=(time()-$chat[requested])/60;
	echo '<td><font face="'.$admin_font.'" size="2">'.round($time, 0).' minutes</td>';
	echo '<td><font face="'.$admin_font.'" size="2"><a href="chat_monitor.php?handle='.$chat[id].'">Handle</a></td></tr>';
	}
	echo '</table>';
}else{
header("HTTP/1.1 204");
exit;
}

//refresh the window every few seconds if there are new chat requests!
echo '<script language="javascript">
  <!--
  function refreshchatmonitor(){
  window.location="chat_monitor.php?"  
  }
  setInterval("refreshchatmonitor()", 10000);
  // -->
</script>';

?>