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
if($send){
foreach($sendto as $admin=>$other){
mysql_query("INSERT INTO quick_msg SET message='$message', admin_id='$admin', subject='$subject', date='".time()."'");
}
echo '<script language="javascript">
window.location="index.php"
</script>';
exit;
}
$thisadmin=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin_id'"));
include "header.php";
echo '<font face="'.$admin_font.'" size="2"><B><BR>Quick Msgs are messages between admins.</B> <BR>They are displayed on the main page of the admins they are sent to..<BR>
To send a quick message, type the note below, select who should receive it and send it!';

echo '<form action="quick_msg.php" method="post">
Subject: <input type=subject name=subject size=40><P>
Message:<BR>
<textarea cols=40 rows=5 name=message>'."\n\n\nFrom: ".$thisadmin[firstname].' '.$thisadmin[lastname].'</textarea><P>Send to: <BR>';

$admins=mysql_query("SELECT * FROM admins");
while($a=mysql_fetch_array($admins)){
echo '<input type=checkbox name="sendto['.$a[id].']"> '.$a[firstname].' '.$a[lastname].'<BR>';
}
echo '<BR><input type=submit name=send value="Send It Now!">';

include "footer.php";
?>
