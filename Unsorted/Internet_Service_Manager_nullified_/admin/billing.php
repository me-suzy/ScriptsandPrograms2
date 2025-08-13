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


echo '<BR><B><font face="'.$admin_font.'" size="2">Outstanding invoices all clients.<a href="receive_money.php?" class="left_menu">Recieve Money</a></B>';

echo '<P><table width="100%">';
 $font='<font face="'.$admin_font.'" size="2"><B>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="2"><font face="'.$admin_font.'" size="1">Invoices updaid</td></tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td>'.$font.'ID</td><td>'.$font.'Due Date</td><td>'.$font.'Client</td><td>'.$font.'<center>For..</center></td><td>'.$font.'Amount</td><td>'.$font.'How Sent?</td></tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="6"></td></tr>';
$bills=mysql_query("SELECT * FROM invoices WHERE paid='0'");

while($b=mysql_fetch_array($bills)){
 echo '<tr>';
 if(time()>$b[due_date]){
 $font='<font face="'.$admin_font.'" size="2" color="red">';
 $due="DUE!";
}else{
 $due="";
 $font='<font face="'.$admin_font.'" size="2">';
}

    ?>
                    <script language="javascript">
                         <!--
			var dfgdfgfdg;
			function pop_invoice(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_invoice.php?invoice_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                    }
                                                                                                                                                          //-->
                         </script>
                  <?

    echo '<td><a href="javascript: pop_invoice('.$b[id].')" class="left_menu">'.$font.$b[id].'</a></td>';
     echo '<td>'.$font.''.date("F j, Y", $b[due_date]).' <B>'.$due.'</B></td>';
     echo '<td>'.$font;
           $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$b[to_client]."'"));
           echo $client[name];
     echo '</td>';
     echo '<td>'.$font.'<font size="1">';
           if($b[project_id]){
               $pr=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='".$b[project_id]."'"));
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
     echo '<td>'.$font.$b[sent_type].$to.' <a href="billing_settle.php?invoice_id='.$b[id].'" class="left_menu">Settle</a></td>';
     
     
 echo '</tr>';
echo '<tr bgcolor="'.$admin_color_2.'"><td colspan="6"></td></tr>';
}

include "footer.php";
?>
