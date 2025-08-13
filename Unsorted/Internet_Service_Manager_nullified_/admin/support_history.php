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

   function switchclient(id)
   {
      window.location="support_overview.php?client_id="+id;
   }



			//-->
</script>
<?
echo '<BR><font face="'.$admin_font.'" size=2><B>The following are old support tickets.</B> Ones in bold have been allocated to you and will only be seen by you.';

echo '<P><table width=100% cellpadding=4>';
            $perpage=15; if(!$start){$start=0;}
$res=mysql_query("SELECT * FROM support_tickets WHERE completed='1' ORDER BY date DESC LIMIT $start, $perpage");
       $font='<font face="'.$admin_font.'" size=1><B>';
       echo '<tr bgcolor="'.$admin_color_2.'"><td></td><td>'.$font.'Priority</td><td>'.$font.'Subject</td><td>'.$font.'Recieved</td><td>'.$font.'From</td><td>'.$font.'Client</td><td>'.$font.'Replied by</td><td>'.$font.'View</td></tr>';

while($r=mysql_fetch_array($res)){
      echo '<tr>';
             $font='<font face="'.$admin_font.'" size=1>';if($r[allocated]){$font='<font face="'.$admin_font.'" size=1><B>';}
      $time=date("F j, Y a", $r[date]);
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
      echo '<td>'.$font.$r[id].':&nbsp;'.$attim.'</td><td>'.$font.$support_priorities[$r[priority]].'</td><td>'.$font.$r[subject].'</td><td>'.$font.''.$time.'</td><td>'.$font.$email.'</td><td>'.$font.$client.'</td>';
      echo '<td>'.$font.'';
          $ad=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='".$r[reply_admin]."'"));
          if($ad){
              echo $ad[firstname].' ' .$ad[lastname];
          }else{
              echo 'No Reply';
          }
      echo '</td><td>'.$font.'<a href="support_handle.php?id='.$r[id].'">Go</a></td>';
      echo '</tr>';
      echo '<tr><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td></tr>';
}

echo '</table><P><BR><center>';

if($start>0){echo '<a href="support_history.php?start='.($start-$perpage).'" class="left_menu">Prev Page</a>&nbsp;';}

if(mysql_num_rows(mysql_query("SELECT * FROM support_tickets WHERE completed='1'"))>($start+$perpage)){echo '<a href="support_history.php?start='.($start+$perpage).'" class="left_menu">Next Page</a>';}

echo '<P></center><table><TR><td><font face="'.$admin_font.'" size="2">Support history by client:</td><td><select onChange="switchclient(this.value)">';

$pe=mysql_query("SELECT * FROM clients ORDER BY name");
while($p=mysql_fetch_array($pe)){
 echo '<option value="'.$p[id].'">'.$p[name].'</option>';
}
echo '</select></td></tr></table>';
include "footer.php";
?>
