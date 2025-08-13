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

if($client_id && $period){
//determine format, template, and item format..
if($format=="email"){
  $template_file="email_statement.txt";
  $item_format=$email_statement_item_format;
}else{
  $template_file="html_statement.txt";
  $item_format=$html_statement_item_format;
}

//grab all the unsettled invoices!
$res=mysql_query("SELECT * FROM invoices WHERE paid='0' && to_client='".$client_id."'");
 while($r=mysql_fetch_array($res)){
                       $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$r[id]."'");
                                              $tpaid=0;
                                              while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
   $thisitem=str_replace("%cost%", $payment_unit.$r[amount], $item_format);
   $thisitem=str_replace("%details%", "Invoice # ".$r[id], $thisitem);
   $thisitem=str_replace("%already_paid%", $payment_unit.$tpaid, $thisitem);
   $outstanding_balance=$outstanding_balance+$r[amount]-$tpaid;
 }
 $allinvoices=$thisitem;
 

 //grab all the payments made to us by the client in the last $period..
 
 if($period=="month"){
       $period="30 Days";
       $res=mysql_query("SELECT * FROM money_received WHERE date>'".(time()-2592000)."' && client_id='$client_id'");
 }else{
       $period="All Time";
       $res=mysql_query("SELECT * FROM money_received WHERE client_id='$client_id'");
 }
       while($r=mysql_fetch_array($res)){
          $payments_received=$payments_received+$r[amount];
       }

              $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
              $contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$client[bill_to_contact]."'"));
              
    //add to the statements table!
    mysql_query("INSERT INTO statements SET sent_as='$format', sent_to='".$contact[id]."', to_client='$client_id', date='".time()."'");
    $statement_id=mysql_insert_id();
    
   //create the statement..
    $fd = fopen ("templates/$template_file", "r");
              while (!feof ($fd)) {
              $template.=fgets($fd, 4096);
              }
              fclose ($fd);
              $template=str_replace("%date%", date("F j, Y"), $template);
              $template=str_replace("%name%", $contact[firstname].' '.$contact[lastname], $template);
              $template=str_replace("%statement_id%", $statement_id, $template);
              $template=str_replace("%period%",$period, $template);
              $template=str_replace("%invoices%", $allinvoices, $template);
              $template=str_replace("%payments%", $payment_unit.$payments_received, $template);
              $template=str_replace("%outstanding_balance%", $payment_unit.$outstanding_balance, $template);
            if($format=="email"){
                mysql_query("INSERT INTO emails SET to_id='".$contact[id]."', to_email='".$contact[email]."', from_email='$billing_email_address', from_name='$billing_name', to_name='".$contact[firstname].' '.$contact[lastname]."', subject='Account Statement', message='$template', type='1', client_id='".$client[id]."', date='".time()."'");
            }else{
                 ?>
                    <script language="javascript">
                         <!--
			var dfgdfgfdg;
			function pop_statement(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_statement.php?print=1&statement_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                    }
                                                                                                                                                         //-->
                         </script>
                  <?
               echo '<script language="javascript">
                       pop_statement('.$statement_id.');
                       alert("Statement Done!");
                       window.location="billing.php";
               </script>';
            }

            mysql_query("UPDATE statements SET data='$template' WHERE id='$statement_id'");

}else{
      echo '<form action="send_statement.php"><BR><BR><font face="'.$admin_font.'" size="2">
         Include payments made by client in last:<P> <select name="period">
               <option value="month">30 Days</option>
               <option value="">All</option>
      </select><P>

      What format should this statement be in:<P> <select name="format">
               <option value="print">Print View</option>
               <option value="email">Email</option>
      </select><P>
      Select the client you would like to send a statement to..<P>';
      echo '<select name="client_id"><option value="">---</option>';

$pe=mysql_query("SELECT * FROM clients ORDER BY name");
while($p=mysql_fetch_array($pe)){
 $sel="";if($p[id]==$client_id){$sel="SELECTED";}
 echo '<option '.$sel.' value="'.$p[id].'">'.$p[name].'</option>';
}
echo '</select>';
echo '<P><input type=submit name=go value="Create It!"></form>';

}


include "footer.php";
?>
