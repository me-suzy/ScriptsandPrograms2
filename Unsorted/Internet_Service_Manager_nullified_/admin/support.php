<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="support"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.

include "header.php";
if($reallocate){
          mysql_query("UPDATE support_tickets SET allocated='$to' WHERE id='$id'");
}

?> <script language="JavaScript">
			<!--
			var dfgdfgfdg;
			function pop_contact(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_contact.php?contact_id='+id,'popupwinPUP','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


			}
   
   function allocate(tickid, adminid)
   {
       window.location="support.php?reallocate=1&id="+tickid+"&to="+adminid
   }



			//-->
</script>
<?
echo '<BR><font face="'.$admin_font.'" size=2><B>The following are un-answered support tickets.</B> Ones in bold have been allocated to you and will only be seen by you.';

echo '<P><table width=100% cellpadding=4>';

$res=mysql_query("SELECT * FROM support_tickets WHERE completed='0' && (allocated='0' OR allocated='$admin_id') ORDER BY priority DESC, date");
       $font='<font face="'.$admin_font.'" size=1><B>';
       echo '<tr bgcolor="'.$admin_color_2.'"><td></td><td>'.$font.'Priority</td><td>'.$font.'Subject</td><td>'.$font.'Time Recieved</td><td>'.$font.'From</td><td>'.$font.'Client</td><td>'.$font.'Allocated To..</td><td>'.$font.'Handle</td></tr>';

while($r=mysql_fetch_array($res)){
      echo '<tr>';
             $font='<font face="'.$admin_font.'" size=1>';if($r[allocated]){$font='<font face="'.$admin_font.'" size=1><B>';}
      $time=round((time()-$r[date])/3600, 1);
      $suber=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE email='".$r[email]."' OR id='".$r[contact_id]."'"));
                   if($suber){
                   $email='<a href="javascript: pop_contact('.$suber[id].')">'.$suber[firstname].' '.$suber[lastname].'</a>';
                   $cli=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$suber[client_id]."'"));
                   $client='<a href="client_list.php?client_id='.$cli[id].'">'.$cli[name].'</a>';
                   }else{
                   $client="Unknown";
                   $email=$r[email];
                   }
                   $attim="";if($r[attatchments]){$attim='<img src="../images/attach.gif">';}
      echo '<td>'.$font.$r[id].':&nbsp;'.$attim.'</td><td>'.$font.$support_priorities[$r[priority]].'</td><td>'.$font.$r[subject].'</td><td>'.$font.''.$time.' hours ago</td><td>'.$font.$email.'</td><td>'.$font.$client.'</td>';
      echo '<td><select name=allocate onChange="allocate('.$r[id].', this.value)"><option value=0>-- All</option>';
      $suptechs=mysql_query("SELECT * FROM admins WHERE privelages LIKE '%support,%'");
             while($st=mysql_fetch_array($suptechs)){
                  $sel="";if($r[allocated]==$st[id]){$sel="SELECTED";}
                   echo '<option '.$sel.' value="'.$st[id].'">'.$st[firstname].' '.$st[lastname].'</option>';
             }
      echo '</select></td><td>'.$font.'<a href="support_handle.php?id='.$r[id].'">Go</a></td>';
      echo '</tr>';
      echo '<tr><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td></tr>';
}

echo '</table>';

include "footer.php";
?>
