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

if($reply){
    //insert the reply info into the support_tickets table
    $r=mysql_fetch_array(mysql_query("SELECT * FROM support_tickets WHERE id='$id'"));
       if($r[randkey]){$key=$r[randkey];
       }else{
             $numbers=array(1,2,3,4,5,6,7,8,9,0,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z);
             for($g=1;$g<40;$g++){
                srand ((double)microtime()*1000000);
                shuffle ($numbers);
                $key.=$numbers[4].$numbers[1];
             }
       }
    mysql_query("UPDATE support_tickets SET randkey='$key', completed='1', reply='$reply', reply_time='".time()."', reply_admin='$admin_id' WHERE id='$id'");
    $s=mysql_fetch_array(mysql_query("SELECT * FROM support_email_addresses WHERE id='$fromemail'"));
    $t=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin_id'"));
     //construct notification email using template support_response.txt!
     $fd = fopen ("templates/support_response.txt", "r");
     while (!feof ($fd)) {
           $message.=fgets($fd, 4096);
     }
     fclose ($fd);

     $message=str_replace("%name%", $to_name, $message);
     $message=str_replace("%sub_subject%", $r[subject], $message);
     $message=str_replace("%sub_details%", $r[details], $message);
     $message=str_replace("%sub_date%", date("F j, Y, g:i a", $r[date]), $message);
     $message=str_replace("%sub_email%", $r[email], $message);
     $message=str_replace("%id%", $r[id], $message);
     $message=str_replace("%response%", $reply, $message);
     $message=str_replace("%response_date%", ("F j, Y, g:i a"), $message);
     $message=str_replace("%followup_link%", str_replace("%key%", $key, str_replace("%ticketid%", $r[id], $support_response_link)), $message);
     $message=str_replace("%tech_name%", $t[firstname].' '.$t[lastname], $message);

     
     
      //send notification email to the submitted of the response!
     mysql_query("INSERT INTO emails SET to_email='".$r[email]."', from_email='".$s[email_address]."', to_name='".$to_name."', from_name='".$s[title]."', subject='".str_replace("%ticketid%", $r[id], $support_response_subject)."', message='$message', type='1', date='".time()."', client_id='$client_id'");
echo '<font face="verdana,arial" size=2><center>Update done!
<script langauge="javascript">
window.location="support.php";
</script>';
exit;
}

if($id && $change_status){
       mysql_query("UPDATE support_tickets SET completed='$completed' WHERE id='$id'");
     if($completed){
           echo '<font face="verdana,arial" size=2><center>Update done!
<script langauge="javascript">
window.location="support.php";
</script>';  }
}

?>
<script language="JavaScript">
			<!--
			var dfgdfgfdg;
			function pop_contact(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_contact.php?contact_id='+id,'popupwinPUP','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


			}
   
   function popchangecontact(id){
				window.name='opener';
				dfgdfgfdg=window.open('assign_support_contact.php?support_request='+id,'popupwinPUP','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


}

   function allocate(tickid, adminid)
   {
       window.location="support.php?reallocate=1&id="+tickid+"&to="+adminid
   }



			//-->
</script>
<?

$r=mysql_fetch_array(mysql_query("SELECT * FROM support_tickets WHERE id='$id' && (allocated='0' OR allocated='$admin_id')"));

echo '<table><tr><td width=20><td>
<BR><font face="'.$admin_font.'" size=2><B>Request ID: '.$r[id];
$pes=mysql_fetch_array(mysql_query("SELECT * FROM support_tickets WHERE id='".$r[parent]."'"));
  if($pes){echo '<BR><font face="'.$admin_font.'" size=2 color="red">Parent:</font> <a href="support_handle.php?id='.$pes[id].'" class="left_menu">'.$pes[subject].'</a>';}
$ues=mysql_query("SELECT * FROM support_tickets WHERE parent='".$r[id]."'");
  if(mysql_num_rows($ues)){
  echo '</B><BR><font face="'.$admin_font.'" size=2 color="red">Responses under this one..<BR>';
       while($u=mysql_fetch_array($ues)){
           echo '</font><a href="support_handle.php?id='.$u[id].'" class="left_menu">'.$u[subject].'</a>';
       }
  }
echo '</B><BR><font face="'.$admin_font.'" size=2>Subject: '.$r[subject];
echo '</B><BR><font face="'.$admin_font.'" size=2>Priority: '.$support_priorities[$r[priority]];
if($r[department]){$dep=mysql_fetch_array(mysql_query("SELECT * FROM support_email_addresses WHERE id='".$r[department]."'"));}else{$dep=mysql_fetch_array(mysql_query("SELECT * FROM support_email_addresses WHERE master='1'"));}
echo '</B><BR><font face="'.$admin_font.'" size=2>Department: '.$dep[title];

if($r[attatchments]){
$allats='</B><BR><font face="'.$admin_font.'" size=2>Attatchments: ';
 //looks like we do have attatchments!
 $atts=explode(";", $r[attatchments]);
 foreach($atts as $att){
     if($att){
 $theat=mysql_fetch_array(mysql_query("SELECT * FROM attatchments WHERE id='$att'"));
 $allats.='&nbsp;<a href="view_attatchment.php?id='.$theat[id].'" target="_blank">'.$theat[filename].'</a>&nbsp';
 }}
}
echo $allats;

      $time=round((time()-$r[date])/3600, 1);
if($r[completed]==1){$comp="Ticket Closed";}else{$comp="Ticket Awaiting Response";}
echo '</B><BR><font face="'.$admin_font.'" size=2>Recieved: '.date("F j, Y, g:i a", $r[date]).' ('.$time.' hours ago) <font size=1>'.$comp.'</font>';
echo '</B><BR><BR><table border="1" bordercolor="'.$admin_color_2.'"><TR><TD><font face="'.$admin_font.'" size=2>'.nl2br($r[details]).'</td></tr></table><P>';

      $suber=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE email='".$r[email]."' OR id='".$r[contact_id]."'"));
                   if($suber){
                   $email='<a href="javascript: pop_contact('.$suber[id].')">'.$suber[firstname].' '.$suber[lastname].'</a>';
                   $cli=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$suber[client_id]."'"));
                   $client=$cli[name];
                   echo 'The submitter of this request was a contact for client, <a class="left_menu" href="support_overview.php?client_id='.$cli[id].'">Support Overview</a><B> <a class="left_menu" href="client_list.php?client_id='.$cli[id].'">Client Info</a></B>.<BR><BR>';
                   echo 'Contact Name: '.$suber[firstname].' '.$suber[lastname];
                   echo '<BR>Contact Email: '.$suber[email];
                   echo '<BR>Contact Title: '.$suber[title];
                   echo '<BR><a href="javascript: pop_contact('.$suber[id].')">More</a>';
                   $pname=$suber[firstname].' '.$suber[lastname];
                   }else{
                   echo 'The person submitting this request was not recognised as a client...<a class="left_menu" href="javascript: popchangecontact('.$r[id].')">Assign to Contact</a><BR>
                   Email: '.$r[email].'<BR>
                   Name: '.$r[name].'<BR>';
                     if($r[name]){$pname=$r[name];}else{$pname="Webmaster";}
                   }
     if(!$r[completed]){
          echo '<form action="support_handle.php?id='.$id.'" method="post">';
        echo 'Your Reply...<BR><BR><textarea name="reply" cols=60 rows=8></textarea><P>
        <input type=hidden name="client_id" value="'.$suber[id].'"> <input type=hidden name="to_name" value="'.$pname.'"><input type=hidden name="id" value="'.$id.'">
        <input type=submit name="go" value="Complete support response">&nbsp;&nbsp;Send From: <select name="fromemail">';
        $s=mysql_query("SELECT * FROM support_email_addresses ORDER BY master DESC");
                                                 while($su=mysql_fetch_array($s)){
                                                       $sel="";if($su[id]==$r[department]){$sel="SELECTED";}
                                                       echo '<option '.$sel.' value="'.$su[id].'">'.$su[title].'</option>';
                                                 }
      echo '</select></form>';
      }
      
      echo '<P>';
      if($r[completed]==1){
            echo '&nbsp;<a class="left_menu" href="support_handle.php?change_status=1&id='.$id.'&completed=0">Reopen support request.</a>';
        }else{
            echo '&nbsp;<a class="left_menu" href="support_handle.php?change_status=1&id='.$id.'&completed=1">Mark completed without response..</a>';
        }
                   
echo '</td></tr></table>';

include "footer.php";
?>
