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
if($sendthem){

$res=mysql_query("SELECT * FROM invoices WHERE due_date<'".(time()-$overdue)."' && paid='0'");
        $x=0;
        while($inv=mysql_fetch_array($res)){

              $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$inv[id]."'");
                            $tpaid=0;while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
                                              if($tpaid+$inv_amount[$id]>$inv[amount]){
                                                   $inv_amount[$id]=$inv[amount]-$tpaid;
                                              }
                                              $settle=$inv[amount]-$tpaid;
              //build the reminder notice and send it..
              $fd = fopen ("templates/payment_reminder_email.txt", "r");
              while (!feof ($fd)) {
              $template.=fgets($fd, 4096);
              }
              fclose ($fd);
              $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$inv[to_client]."'"));
              $contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$client[bill_to_contact]."'"));
              $template=str_replace("%date%", date("F j, Y"), $template);
              $template=str_replace("%name%", $contact[firstname].' '.$contact[lastname], $template);
              $template=str_replace("%invoice_id%", $inv[id], $template);
              $template=str_replace("%due_date%", date("F j, Y", $inv[due_date]), $template);
              $template=str_replace("%amount_owed%", $payment_unit.$settle, $template);
            mysql_query("INSERT INTO emails SET to_id='".$contact[id]."', to_email='".$contact[email]."', from_email='$billing_email_address', from_name='$billing_name', to_name='".$contact[firstname].' '.$contact[lastname]."', subject='".str_replace("%invoice_id%", $inv[id],$reminder_email_subject)."', message='$template', type='1', client_id='".$client[id]."', date='".time()."'");
        $x++;
        }
        
        echo '<font face="'.$admin_font.'" size="2">'.$x.' reminder notices have been emailed to clients!';
        echo '<P><a href="billing.php" class="left_menu">Return to main billing page>></a>';
}else{
echo '<font face="'.$admin_font.'" size="2">';
echo '<BR><BR><center><a href="billing_reminders.php?sendthem=1&overdue=0" class="left_menu">Click here</a> to send a payment reminder to all clients with atleast one over due bill..';
echo '<BR><BR><center><a href="billing_reminders.php?sendthem=1&overdue=604800" class="left_menu">Click here</a> to send a payment reminder to all clients with a bill that is atleast 1 week overdue..';
}

include "footer.php";
?>
