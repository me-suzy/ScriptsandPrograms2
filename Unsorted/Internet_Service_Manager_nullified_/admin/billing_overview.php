<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="billing"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.
include "header.php";
$res=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
  if($res[account_balance]<=0){$res[account_balance]=0;}
  echo '<table width="629" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td colspan="4"><font face="'.$admin_font.'" size="2"><b>Client
      Profile: '.$res[name].' (Balance: '.$payment_unit.$res[account_balance].')</b></font></td>

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

  ?>
  <script language="JavaScript">
			<!--
			var dfgdfgfdg;
                        function pop_contact(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_contact.php?contact_id='+id,'popupwinPUP','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


			}
   
   function popproject(id){
     window.name='opener';
     dfgdfgfdg=window.open('pop_project_details.php?project_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


  }




			//-->
</script>
        <?


echo '<BR><B><font face="'.$admin_font.'" size="2">Billing overview for client..</B><a href="send_statement.php?client_id='.$client_id.'" class="left_menu">Send Statement</a>&nbsp;&nbsp<a href="create_invoice.php?client_id='.$client_id.'" class="left_menu">Invoice Client</a>&nbsp;&nbsp<a href="recieve_money.php?client_id='.$client_id.'" class="left_menu">Recieve Money</a>';

echo '<P><table width="100%">';
 $font='<font face="'.$admin_font.'" size="2"><B>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="2"><font face="'.$admin_font.'" size="1">Invoices updaid</td></tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td>'.$font.'ID</td><td>'.$font.'Due Date</td><td>'.$font.'Issued On</td><td>'.$font.'<center>For..</center></td><td>'.$font.'Amount</td><td>'.$font.'How Sent?</td></tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="6"></td></tr>';
$bills=mysql_query("SELECT * FROM invoices WHERE to_client='$client_id' && paid='0'");

while($b=mysql_fetch_array($bills)){
 echo '<tr>';
 if(time()>$b[due_date]){
 $font='<font face="'.$admin_font.'" size="2" color="red">';
 $due="DUE!";
}else{
 $due="";
 $font='<font face="'.$admin_font.'" size="2">';
}
          echo '<td>'.$font.$b[id].'</td>';
     echo '<td>'.$font.''.date("F j, Y", $b[due_date]).' <B>'.$due.'</B></td>';
     echo '<td>'.$font.''.date("F j, Y", $b[date]).'</td>';
     echo '<td>'.$font.'<font size="1">';
           if($b[project_id]){
               $pr=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE client_id='$client_id' && id='".$b[project_id]."'"));
               $for='<center><B><a href="javascript: popproject('.$pr[id].')">'.$pr[project_name].'</a></B><BR>';
           }
           
           if($b[stage_id]){
                $stages=explode(",", $b[stage_id]);
                foreach($stages as $stage){
                $st=mysql_fetch_array(mysql_query("SELECT * FROM project_stages WHERE id='".$stage."'"));
                $for.='&nbsp;'.$st[stage_name];
                }
           }
           
     echo $for.'</center></td>';

     //check if any money has been paid for this invoice so far..
                    $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$b[id]."'");
                                              $tpaid=0;
                                              while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }

      echo '<td><center>'.$font.$payment_unit.''.$b[amount];

      if($tpaid){echo '<font size=1><BR>'.$payment_unit.$tpaid.' paid</font>';}

      echo '</td>';
      
     $rec=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$b[sent_to]."'"));
      if($rec[firstname]){
       $to=' to <a href="javascript: pop_contact('.$rec[id].')">'.$rec[firstname].' '.$rec[lastname].'</a>';
      }
      if(!$b[sent_type]){$b[sent_type]="Printed Out";}
     echo '<td>'.$font.$b[sent_type].$to;
         if($b[paid]<1){echo '&nbsp;<a href="billing_settle.php?invoice_id'.$b[id].'" class="left_menu">Settle</a>';}
     
 echo '</td></tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="6"></td></tr>';
}


//paid bills!
echo '<BR><BR><table width="100%">';
 $font='<font face="'.$admin_font.'" size="2"><B>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="2"><font face="'.$admin_font.'" size="1">Settled Invoices</td></tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td>'.$font.'ID</td><td>'.$font.'Due Date</td><td>'.$font.'Issued On</td><td>'.$font.'<center>For..</center></td><td>'.$font.'Amount</td><td>'.$font.'How Sent?</td></tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="6"></td></tr>';
$bills=mysql_query("SELECT * FROM invoices WHERE to_client='$client_id' && paid='1'");

while($b=mysql_fetch_array($bills)){
 echo '<tr>';
 $font='<font face="'.$admin_font.'" size="2">';

     echo '<td>'.$font.$b[id].'</td>';
     echo '<td>'.$font.''.date("F j, Y", $b[due_date]).' <B>'.$due.'</B></td>';
     echo '<td>'.$font.''.date("F j, Y", $b[date]).'</td>';
     echo '<td>'.$font.'<font size="1">';
           if($b[project_id]){
               $pr=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE client_id='$client_id' && id='".$b[project_id]."'"));
               $for='<center><B><a href="javascript: popproject('.$pr[id].')">'.$pr[project_name].'</a></B><BR>';
           }

           if($b[stage_id]){
                $stages=explode(",", $b[stage_id]);
                foreach($stages as $stage){
                $st=mysql_fetch_array(mysql_query("SELECT * FROM project_stages WHERE id='".$stage."'"));
                $for.='&nbsp;'.$st[stage_name];
                }
           }

     echo $for.'</center></td>';

     echo '<td>'.$font.$payment_unit.''.$b[amount].'</td>';
     $rec=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$b[sent_to]."'"));
      if($rec[firstname]){
       $to=' to <a href="javascript: pop_contact('.$rec[id].')">'.$rec[firstname].' '.$rec[lastname].'</a>';
      }
      if(!$b[sent_type]){$b[sent_type]="Printed Out";}
     echo '<td>'.$font.$b[sent_type].$to;


     echo '</td>';


 echo '</tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="6"></td></tr>';
}

include "footer.php";
?>
