<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "header.php";

if($movenow){
mysql_query("UPDATE emails SET folder='$moveto' WHERE id='$id'");
}

  ?>
  <script language="JavaScript">
			<!--
			var dfgdfgfdg;
			function pop_contact(id){
				window.name='newwindow';
				dfgdfgfdg=window.open('pop_contact.php?contact_id='+id,'popupwinPUP','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


			}
   
                         function moveto(folder)
                         {
                           window.location="read_email.php?movenow=1&id=<?echo $id;?>&moveto="+folder
                         }
                         




			//-->
</script>
        <?
mysql_query("UPDATE emails SET handled='1' WHERE id='$id'");
$r=mysql_fetch_array(mysql_query("SELECT * FROM emails WHERE (to_id='$admin_id' && type='0' && id='$id') OR type='1'"));

echo '<table>';
if($r[handled]){$im="msg_read.gif";}else{$im="msg_unread.gif";}
echo '<tr><td colspan=2><img src="../images/'.$im.'">&nbsp;<font face="'.$admin_font.'" size="2"><B>Reading mail message # '.$id.'</td></tr>';

echo '<tr><td align="right"><font face="'.$admin_font.'" size="2">Subject: </td><td><font face="'.$admin_font.'" size="2">'.$r[subject].'</td></tr>';
//see if its a contact!
$contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE email='".$r[from_email]."'"));
if($contact){$con='Contact: <a href="javascript: pop_contact('.$contact[id].')" class="left_menu">'.$contact[firstname].' '.$contact[lastname].'</a>';}

echo '<tr><td align="right"><font face="'.$admin_font.'" size="2">From: </td><td><font face="'.$admin_font.'" size="2">'.$r[from_name].'&lt;<a class="left_menu" href="compose.php?to_name='.$r[from_name].'&to='.trim($r[from_email]).'&subject=Re: '.$r[subject].'">'.trim($r[from_email]).'</a>&gt; '.$con.' </td></tr>';
echo '<tr><td align="right"><font face="'.$admin_font.'" size="2">To: </td><td><font face="'.$admin_font.'" size="2">'.$r[to_name].'&lt;'.trim($r[to_email]).'&gt;</td></tr>';

if($r[attatchments]){
 //looks like we do have attatchments!
 $atts=explode(";", $r[attatchments]);
 foreach($atts as $att){
     if($att){
 $theat=mysql_fetch_array(mysql_query("SELECT * FROM attatchments WHERE id='$att'"));
 $allats.='&nbsp;<a href="view_attatchment.php?id='.$theat[id].'" target="_blank">'.$theat[filename].'</a>&nbsp';
 }}
}
echo '<tr><td align="right"><font face="'.$admin_font.'" size="2">Attatchments: </td><td><font face="'.$admin_font.'" size="2">'.$allats.'</td></tr>';
echo '<tr><td align="right"><font face="'.$admin_font.'" size="2">Recieved: </td><td><font face="'.$admin_font.'" size="2">'.date("F j, Y, g:i a ",$r[date]).'</td></tr>';
echo '</table>';

if($r[message_type]!="html"){$r[message]=nl2br($r[message]);}
echo '<P><table cellpadding=6 width="100%" border=1 bordercolor="'.$admin_color_2.'"><tr><td><font face="'.$admin_font.'" size=2>'.$r[message].'</td></tr></table>';

echo '<P><table width="100%"><tr>
<td><font face="'.$admin_font.'" size="2"><a class="left_menu" href="delete_mail.php?id='.$id.'">Delete</a> | <a href="compose.php?to='.trim($r[from_email]).'&subject=Re: '.$r[subject].'" class="left_menu">Compose</a> | <a class="left_menu" href="email_inbox.php?folder='.$r[folder].'">Inbox</a></td>
<td colspan=3><font face="'.$admin_font.'" size="2"><font size="2">Move this message to: <select onChange="moveto(this.value)">
<option value="">INBOX</option>';
     $folders=mysql_query("SELECT * FROM email_folders WHERE admin_id='$admin_id'");
     while($f=mysql_fetch_array($folders)){
       $sel="";if($f[id]==$r[folder]){$sel="SELECTED";}
           echo '<option '.$sel.' value="'.$f[id].'">'.$f[folder_name].'</option>';
     }
echo '</select></td></tr></table>';

include "footer.php";
?>
