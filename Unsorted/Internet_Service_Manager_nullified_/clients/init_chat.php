<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";

if($goforit){
if(!$name){$name="Client";}
$client=mysql_fetch_array($res=mysql_query("SELECT * FROM clients WHERE id='$client_id'"));

if(mysql_num_rows($res)){
$extrainfo='Client: '.$client[name].'/'.$name;
}
mysql_query("INSERT INTO chats SET extrainfo='$extrainfo', client_ip='$REMOTE_ADDR', requested='".time()."', client_last='".time()."', lastdate='".time()."', client_name='$name', data='--435323400032nn324ol320920324n32002--".time()."--33--239324b20921--system--33--239324b20921--".$please_wait_message."'");
$id=mysql_insert_id();
?>
<script language="javascript">     	 
				var clientchatwindow=window.open('chat.php?id=<?echo $id;?>','ClientChatWindow','width=500,height=450,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');        				 
			 window.close();
			     </script>
	<?
	exit;
}

$number_admins=mysql_num_rows(mysql_query("SELECT * FROM admins WHERE online>'".(time()-30)."'"));

$fp=fopen("templates/init_chat.htm", "r");
while(!feof($fp)){
$data.=fgets($fp, 1204);
}

$contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='$contact_id'"));

$data=str_replace("%number_admins%", $number_admins, $data);
$data=str_replace("%init_chat_url%", "init_chat.php?goforit=1&client_id=".$contact[client_id]."&name=Contact: ".$contact[firstname], $data);
$data=str_replace("%support_ticket_url%", "support_ticket.php?&client_id=".$contact[client_id]."&contact_id=".$contact[id], $data);

echo $data;






?>