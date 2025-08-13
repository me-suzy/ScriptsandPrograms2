<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "header.php";


if($createnewfolder){
    mysql_query("INSERT INTO email_folders SET admin_id='$admin_id', folder_name='$createnewfolder'");
}

?>
  <script language="javascript">
          function switchfolder(folder)
          {
              window.location="email_inbox.php?folder="+folder;
          }

                        function createfolder()
                         {
                          foldername=prompt("What should the new folder be called?", "New Folder");
                          if(foldername!="null"){
                          window.location="email_inbox.php?createnewfolder="+foldername;
                          }
                         }
  </script>
<?

if($folder=="sent"){
    $admin=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin_id'"));
    $res=mysql_query("SELECT * FROM emails WHERE from_email LIKE '".$admin[email]."' && type='1'");
}else{
    $res=mysql_query("SELECT * FROM emails WHERE to_id='$admin_id' && type='0' && folder='$folder'");
}
echo '<font face="'.$admin_font.'" size="2"><B>Emails!<P>';
echo '<table cellpadding="4">';
$font='<font face="'.$admin_font.'" size="1" color="'.$admin_font_color_2.'"><B>';
echo '<tr><td bgcolor="'.$admin_color.'" width=40></td><td width=80 bgcolor="'.$admin_color.'">'.$font.'Date</td><td width=200 bgcolor="'.$admin_color.'">'.$font.'Subject</td><td width=180 bgcolor="'.$admin_color.'">'.$font.'From</td><td width=80 bgcolor="'.$admin_color.'">'.$font.'Actions</td></tr>';

$font='<font face="'.$admin_font.'" size="1">';

while($r=mysql_fetch_array($res)){
echo '<tr>';
if($r[handled]){$im="msg_read.gif";}else{$im="msg_unread.gif";}
$im2="";if($r[attatchments]){$im2='&nbsp;<img src="../images/'.$im2.'attach.gif">';}
echo '<td><img src="../images/'.$im.'">'.$im2.'</td>';
echo '<td>'.$font.''.date("F j, Y, g:i a ",$r[date]).'</td>';
echo '<td>'.$font.'<a href="read_email.php?id='.$r[id].'">'.$r[subject].'</a></td>';
echo '<td>'.$font.''.$r[from_name].'&lt;<a href="compose.php?to_name='.$r[from_name].'&to='.trim($r[from_email]).'&subject=Re: '.$r[subject].'">'.trim($r[from_email]).'</a>&gt;</td>';
echo '<td>'.$font.'<a href="delete_mail.php?folder='.$folder.'&id='.$r[id].'">Delete</a> | <a href="read_email.php?id='.$r[id].'">Read</a></td>';
echo '</tr>';
echo '<tr><td colspan=5 bgcolor="'.$admin_color_2.'"></td></tr>';
}

echo '<tr><td colspan=3>'.$font.'<font size="2">Move to: <select onChange="switchfolder(this.value)">
<option value="">INBOX</option><option value="sent">Sent Items</option>';
     $folders=mysql_query("SELECT * FROM email_folders WHERE admin_id='$admin_id'");
     while($f=mysql_fetch_array($folders)){
       $sel="";if($f[id]==$folder){$sel="SELECTED";}
           echo '<option '.$sel.' value="'.$f[id].'">'.$f[folder_name].'</option>';
     }
echo '</select> <a href="javascript: createfolder()" class="left_menu">Create New Folder</a></td></tr>';

echo '</table>';

include "footer.php";
?>
