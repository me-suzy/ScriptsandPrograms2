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

?> <script language="JavaScript">
			<!--

   function chooseclient(id)
   {
      window.location="receive_money.php?client_id="+id+"&amount="+document.f.amount.value;
   }
   
   function pop_invoice(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_invoice.php?invoice_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                    }
                    
                     function pop_receipt(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_receipt.php?receipt_id='+id,'popupwinPUP','width=400,height=400,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                    }




			//-->
</script>
<?

if($handle){
//first of all some error checking...
        //the unique identifier must be unique!
        if(!$method_identifier || mysql_num_rows(mysql_query("SELECT * FROM money_received WHERE method_identifier LIKE '$method_identifier'"))){
             echo '<script language="javascript">
                     alert("The unique identifier you entered was not unique!");
                     window.history.back();
             </script>';
             exit;
        }
        
        //check that all the inputs balance out!
         if($inv){foreach($inv as $id=>$inv_id){
            $ta=$ta+$inv_amount[$id];
         }}
          if($ta>$amount){
             echo '<script language="javascript">
                     alert("The amounts you entered are more than the total payment amount..");
                     window.history.back();
             </script>';
             exit;
          }
          @reset($inv);
          $ta=0;
          
                      $numbers=array(1,2,3,4,5,6,7,8,9,0,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z);
             for($g=1;$g<$billing_auth_key_length;$g++){
                srand ((double)microtime()*1000000);
                shuffle ($numbers);
                $key.=$numbers[4].$numbers[1];
             }
             $key=strtoupper($key);
          
          if($inv){foreach($inv as $id=>$inv_id){
            if($inv_amount[$id]){

                   //check that not to much is being paid on this invoice!
                   $cinv=mysql_fetch_array(mysql_query("SELECT * FROM invoices WHERE id='$inv_id'"));
                    $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='$inv_id'");
                                              $tpaid=0;while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
                                              if($tpaid+$inv_amount[$id]>$cinv[amount]){
                                                   $inv_amount[$id]=$cinv[amount]-$tpaid;
                                              }

                mysql_query("INSERT INTO money_received SET authkey='$key', receipt='$receipt', admin_id='$admin_id', client_id='$client_id', date='".time()."', amount='".$inv_amount[$id]."', invoice='$inv_id', comments='$comments', method='$method', method_identifier='$method_identifier'");
            $ta=$ta+$inv_amount[$id];
            //check if the invoice is now fully paid!
                    $cinv=mysql_fetch_array(mysql_query("SELECT * FROM invoices WHERE id='$inv_id'"));
                    $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='$inv_id'");
                                              $tpaid=0;while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
                                              if($cinv[amount]<=$tpaid){
                                                  echo '<font face="'.$admin_font.'" size="2">Invoice #'.$cinv[id].' is now fully paid!<BR>';
                                                  mysql_query("UPDATE invoices SET paid='1', date_paid='".time()."' WHERE id='$inv_id'");
                                              }
            }
         }}
         
         //if theres an excess enter it client the db..
         $excess=$amount-$ta;
         if($excess){
             mysql_query("UPDATE clients SET account_balance=account_balance+$excess WHERE id='$client_id'");

         mysql_query("INSERT INTO money_received SET authkey='$key', receipt='$receipt', admin_id='$admin_id', client_id='$client_id', date='".time()."', amount='".$excess."', invoice='', comments='$comments', method='$method', method_identifier='$method_identifier'");
         echo '<font face="'.$admin_font.'" size="2">'.$payment_unit.$excess.' was added to the clients account balance.<BR>';
         }
         
         if($receipt=="email"){
                     $unpaidins=mysql_query("SELECT * FROM invoices WHERE paid='0' && to_client='$client_id'");
                     while($up=mysql_fetch_array($unpaidins)){
                              $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='$inv_id'");
                                              $tpaid=0;while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
                              $outstanding_balance=$outstanding_balance+$up[amount]-$tpaid;
                     }

                     //need to email a receipt to the contact..
                     $template_file="email_receipt.txt";
              //build the receipt to send..
              $fd = fopen ("templates/$template_file", "r");
              while (!feof ($fd)) {
              $template.=fgets($fd, 4096);
              }
              fclose ($fd);
              $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
              $contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$client[bill_to_contact]."'"));
              $template=str_replace("%date%", date("F j, Y"), $template);
              $template=str_replace("%name%", $contact[firstname].' '.$contact[lastname], $template);
              $template=str_replace("%id%", $method_identifier, $template);
              $template=str_replace("%auth_key%", $key, $template);
              $template=str_replace("%amount%", $payment_unit.$amount, $template);
              $template=str_replace("%outstanding_balance%", $payment_unit.$outstanding_balance, $template);
            mysql_query("INSERT INTO emails SET to_id='".$contact[id]."', to_email='".$contact[email]."', from_email='$billing_email_address', from_name='$billing_name', to_name='".$contact[firstname].' '.$contact[lastname]."', subject='".str_replace("%id%", mysql_insert_id(),$reciept_email_subject)."', message='$template', type='1', client_id='".$client_id."', date='".time()."'");
            echo '<font face="'.$admin_font.'" size="2">A receipt has been emailed to the client<BR>';
         }


      echo '<font face="'.$admin_font.'" size="2"><P>The id of this reciept is: <B>'.$method_identifier.'</B><BR>';
      echo 'The authorisation key for this receipt is: <b><font size=3>'.$key.'</font><P></B>';
      echo '<a href="billing.php" class="left_menu">Continue to billing main page &gt;&gt;</a>';
        if($receipt=="print"){
        echo '<script language="javascript">
             pop_receipt("'.$method_identifier.'");
        </script>';
        }
}elseif($client_id && $amount){
              echo '<form action="receive_money.php?client_id='.$client_id.'&amount='.$amount.'" method="post">';
              $inv=mysql_query("SELECT * FROM invoices WHERE paid='0' && to_client='$client_id' ORDER BY due_date");
              echo '<font face="'.$admin_font.'" size="2">The following invoices have not been settled by for this client, enter the amount to pay for each one next to the invoice number..the total amount cannot excede the '.$payment_unit.$amount.' received.<BR>';
              $rem=$amount;
              if(!mysql_num_rows($inv)){
              echo '<BR>There are no invoices unpaid for this client, just add this money to their account balance.';
              }
              while($i=mysql_fetch_array($inv)){
                  echo '<BR>';
                  $x++;

                                    $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$i[id]."'");
                                              $tpaid=0;
                                              while($d=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$d[amount];
                                    }
                                    
                    $totalamount=$i[amount];
                    $i[amount]=$i[amount]-$tpaid;
                  //calculate how much to give to this invoice by default!
                  $temprem=$rem;
                  $rem=$rem-$i[amount];
                  if($rem>0){
                     $disamount=$i[amount];
                  }else{
                     if($temprem>0){
                         $disamount=$temprem;
                     }else{
                          $disamount=0;
                     }
                  }



                echo '&nbsp;<a href="javascript: pop_invoice('.$i[id].')" class="left_menu">Invoice #'.$i[id].'</a> (Invoice Total: '.$payment_unit.$totalamount.'; Already Paid: '.$payment_unit.$tpaid.')&nbsp;<input type=hidden name="inv['.$x.']" value="'.$i[id].'"><input size="5" value="'.$disamount.'" type=text name=inv_amount['.$x.']>';
              }

              echo '<P>
              How was this money received? <select name="method">
                   <option value="Other">Other</option>
                   <option value="Check">Check</option>
                   <option value="Money Order">Money Order</option>
                   <option value="Credit Card">Credit Card</option>
                   <option value="Cash">Cash</option>
              </select>
              <P>
              Unique Identifier: <input type=text name=method_identifier>
              <P>
              Comments:<BR>
              <textarea name="comments" cols=40 rows=4></textarea><P>
              
               Reciept: <select name="receipt">
                   <option value="none">None</option>
                   <option value="email">Email to Client</option>
                   <option value="print">Print View</option>
              </select><P>
              
              <input type=submit name=handle value="Enter payment info..">
              ';


}else{
      echo '<form name="f"><font face="'.$admin_font.'" size="2">
      How much money have you received? '.$payment_unit.'<input type=text name="amount" size="8"><P>
      Select the client this money is from..<P>';
      echo '<select onChange="chooseclient(this.value)"><option value="">---</option>';

$pe=mysql_query("SELECT * FROM clients ORDER BY name");
while($p=mysql_fetch_array($pe)){
 echo '<option value="'.$p[id].'">'.$p[name].'</option>';
}
echo '</select>';
}

include "footer.php";
?>
