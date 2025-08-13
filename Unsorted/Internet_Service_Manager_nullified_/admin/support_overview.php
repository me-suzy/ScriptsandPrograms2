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
$client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
  if($client[account_balance]<=0){$client[account_balance]=0;}
  echo '<table width="629" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td colspan="4"><font face="'.$admin_font.'" size="2"><b>Client
      Profile: '.$client[name].' (Balance: '.$payment_unit.$client[account_balance].')</b></font></td>

    <td width="115">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" height="3"></td>
  </tr>
  <tr bgcolor="#006699">
    <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="client_list.php?client_id='.$client_id.'" class="other_text">Profile</a> </div>
    </td>
    <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="billing_overview.php?client_id='.$client_id.'" class="other_text">Billing</a> </div>
    </td>
    <td width="125">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_project.php?client_id='.$client_id.'" class="other_text">Add Project</a></div>
    </td>
    <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="edit_client.php?client_id='.$client_id.'" class="other_text">Edit Client</a></div>
    </td>
    <td width="120">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="support_overview.php?client_id='.$client_id.'" class="other_text">Support</a></div>
      </td>
      <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_contact.php?client_id='.$client_id.'" class="other_text">Add Contact</a></div>
    </td>
  </tr>
  <tr>
    <td width="115">&nbsp;</td>
    <td width="125">&nbsp;</td>
    <td width="130">&nbsp;</td>
    <td width="120">&nbsp;</td>
    <td width="115">&nbsp;</td>
  </tr>
</table>
';

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
       window.location="support_overview.php?contact=<?echo $contact;?>&client_id=<?echo $client_id;?>&perpage=<?echo $perpage;?>&reallocate=1&id="+tickid+"&to="+adminid
   }



			//-->
</script>
<?

echo '<BR><font face="'.$admin_font.'" size=2><B>Support overview for "'.$client[name].'"</B> Ones in bold have been not been answered! They are divided into contacts.';

if(!$perpage){$perpage="LIMIT 5";}else{$perpage="";}

if($contact){
  $contacts=mysql_query("SELECT * FROM contacts WHERE id='$contact'");
}else{
  $contacts=mysql_query("SELECT * FROM contacts WHERE client_id='$client_id'");
}
 echo '<P><table width=100% cellpadding=4>';
while($con=mysql_fetch_array($contacts)){


$res=mysql_query("SELECT * FROM support_tickets WHERE contact_id='".$con[id]."' OR email='".$con[email]."' ORDER BY date DESC $perpage");
if(mysql_num_rows($res)){
       echo '<TR bgcolor="'.$admin_color_2.'"><td colspan="6"><font face="'.$admin_font.'" size="2"><B>Contact:</b> '.$con[firstname].' '.$con[lastname].' - '.$con[title].'</b><font size=1 color="'.$admin_color.'"> Most recent: '.$perpage.'</font></font></td></tr>';
       $font='<font face="'.$admin_font.'" size=1><B>';
       echo '<tr bgcolor="'.$admin_color_2.'"><td></td><td>'.$font.'Priority</td><td>'.$font.'Subject</td><td>'.$font.'Time Recieved</td><td>'.$font.'From</td><td>'.$font.'Client</td><td>'.$font.'Allocated To..</td><td>'.$font.'Handle</td></tr>';


    while($r=mysql_fetch_array($res)){
      echo '<tr>';
             $font='<font face="'.$admin_font.'" size=1>';if(!$r[completed]){$font='<font face="'.$admin_font.'" size=1><B>';}
            $time=date("F j, Y a", $r[date]);
      $suber=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE email='".$r[email]."' OR id='".$r[contact_id]."'"));
                   if($suber){
                   $email='<a href="javascript: pop_contact('.$suber[id].')">'.$suber[firstname].' '.$suber[lastname].'</a>';
                   $cli=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$suber[client_id]."'"));
                   $client=$cli[name];
                   }else{
                   $client="Unknown";
                   $email=$r[email];
                   }
                         $attim="";if($r[attatchments]){$attim='<img src="../images/attach.gif">';}
      echo '<td width=25>'.$font.$r[id].':&nbsp;'.$attim.'</td><td>'.$font.$support_priorities[$r[priority]].'</td><td>'.$font.$r[subject].'</td><td>'.$font.''.$time.'</td><td>'.$font.$email.'</td><td>'.$font.$client.'</td>';
      echo '<td width=50><select name=allocate onChange="allocate('.$r[id].', this.value)"><option value=0>-- All</option>';
      $suptechs=mysql_query("SELECT * FROM admins WHERE privelages LIKE '%support,%'");
             while($st=mysql_fetch_array($suptechs)){
                  $sel="";if($r[allocated]==$st[id]){$sel="SELECTED";}
                   echo '<option '.$sel.' value="'.$st[id].'">'.$st[firstname].' '.$st[lastname].'</option>';
             }
      echo '</select></td><td>'.$font.'<a href="support_handle.php?id='.$r[id].'">Go</a></td>';
      echo '</tr>';
      echo '<tr><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td><td height="1" bgcolor="'.$admin_color_2.'"></td></tr>';
}
      echo '<TR><td colspan="2"></td><td colspan="6" align=right><font size=1><a href="support_history.php">View support history of all client</a> &nbsp;&nbsp;</font>';
       if(!$contact){
          echo '<font size=1><a href="support_overview.php?contact='.$con[id].'&client_id='.$client_id.'&perpage=1">View All of Contact</a></font></td></tr>';
          }else{
     echo '<font size=1><a href="support_overview.php?client_id='.$client_id.'">View All of Client</a></font><BR><BR></td></tr>';
      }
             echo '<TR><td colspan="6">&nbsp;</td></tr>';
}}
echo '</table>';
include "footer.php";
?>
