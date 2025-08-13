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
      window.location="create_invoice.php?client_id="+id;
   }

   function warnchange()
   {
       alert("Changing this value will mean the total is no longer accurate.")
   }


   function chooseproject(id)
   {
      window.location="create_invoice.php?client_id=<?echo $client_id;?>&project_id="+id;
   }


			//-->
</script>
<?
if($generate){

     if($method=="email"){
     //method is email..
         $template_file="email_invoice.txt";
         $item_template=$email_invoice_item_format;
         $nl="\n";
     }else{
         $template_file="html_invoice.txt";
         $item_template=$html_invoice_item_format;
         $nl="<BR>";
     }

         foreach($item as $id=>$it){
                     if($it!="other"){
                     //its a project stage, mark the stage as billed!
                     mysql_query("UPDATE project_stages SET billed='1' WHERE id='".$it."'");
                     $stageids="$it,";
                   }
           if($details[$id]){
           $thisitem=str_replace("%details%", $details[$id], $item_template);
           $thisitem=str_replace("%cost%", $cost[$id], $thisitem);
           $allitems.=$thisitem;
           $total=$total+$cost[$id];
           }
           //end each item!
      }
         //generals tuff
         $due_date=mktime(0,0,0,$due_mm,$due_dd,$due_yy);
		 $due_date2="$due_mm/$due_dd/$due_yy";
         $sales_tax=sales_tax($total);

         mysql_query("INSERT INTO invoices SET to_client='$client_id', project_id='$project_id', stage_id='$stageids', amount='$total', sales_tax='$sales_tax', admin_id='$admin_id', date='".time()."', due_date='".$due_date."', sent_type='$method', sent_to='$contact_id'");
                       $invoice_id=mysql_insert_id();

         $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));

         //check if we have to credit the invoice with account balance..
         if($creditaccountbalance && $client[account_balance]){
               if($client[account_balance]<=$total){
                  $tem=$total-$client[account_balance];
                  $credit=$total-$tem;
               }else{
                  $credit=$total;
                  mysql_query("UPDATE invoices SET paid='1' WHERE id='".mysql_insert_id()."'");
               }
               mysql_query("INSERT INTO money_received SET admin_id='$admin_id', client_id='$client_id', date='".time()."', amount='".$credit."', invoice='$invoice_id', comments='Account Balance Credit', method='Account Balance', method_identifier='AccCred:$invoice_id'");
               mysql_query("UPDATE clients SET account_balance=account_balance-$credit WHERE id='$client_id'");
             $total=$total-$credit;
         $thisitem=str_replace("%details%", "Account Credit", $item_template);
         $allitems.=str_replace("%cost%", "-".$credit, $thisitem);
                  $sales_tax=sales_tax($total);

         }
                           if($add_on_sales_tax){$total=$total+$sales_tax;}


         //bill to..
         $contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='$contact_id'"));
         $bill_to=$client[name].$nl.'Attn: '.$contact[firstname].' '.$contact[lastname].$nl;
         if($nl=="<BR>"){
              $bill_to.=nl2br($contact[address]);
         }else{
              $bill_to.=$contact[address];
         }
         $bill_to.=$nl.'Email: '.$contact[email];

         //build the invoice to send..
              $fd = fopen ("templates/$template_file", "r");
              while (!feof ($fd)) {
              $template.=fgets($fd, 4096);
              }
              fclose ($fd);
              $template=str_replace("%bill_to%", $bill_to, $template);
              $template=str_replace("%invoice_number%", mysql_insert_id(), $template);
              $template=str_replace("%date%", date("F j, Y"), $template);
              $template=str_replace("%due_date%", date("F j, Y", $due_date), $template);
              $template=str_replace("%items%", $allitems, $template);
              $template=str_replace("%total%", $total, $template);
              $template=str_replace("%service_tax%", $sales_tax, $template);
              mysql_query("UPDATE invoices SET sales_tax='$sales_tex', data='$template' WHERE id='".$invoice_id."'");

              if($method=="email"){
                  mysql_query("INSERT INTO emails SET to_id='$contact_id', to_email='".$contact[email]."', from_email='$billing_email_address', from_name='$billing_name', to_name='".$contact[firstname].' '.$contact[lastname]."', subject='".str_replace("%invoice_number%", $invoice_id,$invoice_email_subject)."', message='$template', type='1', client_id='".$client_id."', date='".time()."'");
                  echo '<script language="javascript">
                           alert("Invoice Completed..")
                           window.location="billing.php";
                  </script>';
              }else{
                    ?>
                    <script language="javascript">
                         <!--
			var dfgdfgfdg;
			function pop_invoice(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_invoice.php?print=1&invoice_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                    }
                     pop_invoice(<?echo $invoice_id;?>);
                                                      window.location="billing.php";                                                                                                                                                   //-->
                         </script>
                  <?
              }
}elseif($CREATEIT){
                    if(!$stage){
               echo '<script language="javascript">
                           alert("You must select stages!")
                           window.location="create_invoice.php?client_id='.$client_id.'&project_id='.$project_id.'";
                  </script>';
     }


                echo '<form action="create_invoice.php?client_id='.$client_id.'&project_id='.$project_id.'" method="post"><P><table>';
              
              echo '<tr>';
              $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
              echo '<td valign="top"><font face="'.$admin_font.'" size="2">Bill to: </td><td><font face="'.$admin_font.'" size="2"><B>'.$client[name].'</B><BR>';
              $contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$client[bill_to_contact]."'"));
              echo 'Attn: '.$contact[firstname].' '.$contact[lastname].'<BR>';
              echo nl2br($contact[address]).'<BR>';
              echo 'Email: '.$contact[email].'</td><input type=hidden name="contact_id" value="'.$contact[id].'"></tr>';
              if(!$contact){
               echo '<BR><font color=red>ERROR: NO BILLING CONTACT DEFINED! PLEASE SET THIS TO CONTINUE!';
               exit;
              }
              echo '<tr><td>&nbsp;</td><td></td></tr>';
              echo '<tr><td><font face="'.$admin_font.'" size="2">Client #: </td><td><font face="'.$admin_font.'" size="2">'.$client[id].'</td></tr>';
              echo '<tr><td>&nbsp;</td><td></td></tr>';
                            echo '<tr><td valign="top"><font face="'.$admin_font.'" size="2">Items: </td><td>';
                                             echo '<table><tr><td></td><td><font face="'.$admin_font.'" size="2"><B>Details</td><td align="right"><font face="'.$admin_font.'" size="2"><B>Amount</td></tr>';
            $project=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='$project_id'"));
            foreach($stage as $st){
                   $x++;
                   $info=mysql_fetch_array(mysql_query("SELECT * FROM project_stages WHERE id='".$st."'"));
                  $total=$total+$info[cost];
                  echo '<tr><td><font face="'.$admin_font.'" size="2"><input type="hidden" name="item['.$x.']" value="'.$info[id].'">Stage: '.$info[stage_name].'</td><td><input size=30 type=text name="details['.$x.']" value="'.$project[project_name].' - '.$info[stage_name].'"></td>';
                  echo '<td>&nbsp;&nbsp;<font face="'.$admin_font.'" size="2">'.$payment_unit.'<input size=5 onClick="warnchange()" type=text name="cost['.$x.']" value="'.$info[cost].'"></td></tr>';
            }
                  $x++;
                  echo '<tr><td><font face="'.$admin_font.'" size="2">Other: </td><td><input type="hidden" name="item['.$x.']" value="other"><input size=30 type=text name="details['.$x.']" value=""></td>';
                  echo '<td>&nbsp;&nbsp;<font face="'.$admin_font.'" size="2">'.$payment_unit.'<input size=5 onClick="warnchange()" type=text name="cost['.$x.']" value=""></td></tr>';
                  echo '<tr><td colspan="2"></td><td><font face="'.$admin_font.'" size="2">Total: '.$payment_unit.$total.'</td></tr>';
            echo '</table></td></tr>';
              $tax=sales_tax($total);
            echo '<tr><td><font face="'.$admin_font.'" size="2">Sales Tax:</td><td><font face="'.$admin_font.'" size="2">'.$payment_unit.$tax.'</td></tr>';
            echo '<tr><td>&nbsp;</td><td></td></tr>';
            echo '<tr><td><font face="'.$admin_font.'" size="2">Due Date:<BR>(mm/dd/yyyy)</td><td valign="top">
                    <select name="due_mm"><option value="">--</option>';
        $curfin=getdate((time()+$default_due_date));
        for($m=1; $m<13; $m++){
                  $sel="";if($curfin[mon]==$m){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="due_dd"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                  $sel="";if($curfin[mday]==$d){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="due_yy"><option value="">--</option>';
        for($y=2000; $y<2020; $y++){
                  $sel="";if($curfin[year]==$y){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$y.'">'.$y.'</option>';
        }
      echo '
      </select></td></tr>';
            echo '<tr><td>&nbsp;</td><td></td></tr>';
            if($client[billing_method]){
                                        if($client[billing_method]=="mail"){$mailsel="SELECTED";}
                                        if($client[billing_method]=="email"){$emailsel="SELECTED";}
            }
            echo '<tr><td><font face="'.$admin_font.'" size="2">Method: </td><td>
            <select name="method">
               <option '.$emailsel.' value="email">Email to client</option>
               <option '.$mailsel.' value="">Display Print View</option>
            </select></td></tr>';
            
              echo '<tr><td>&nbsp;</td><td></td></tr>';
             if($client[account_balance]){
              echo '<tr><td colspan="2"><font face="'.$admin_font.'" size="2">This client has '.$payment_unit.$client[account_balance].' in their account. Credit this amount to this invoice? <select name="creditaccountbalance"><option value="">No</option><option>Yes</option></select></td></tr>';
              
              echo '<tr><td>&nbsp;</td><td></td></tr>';
          }
                          echo '<tr><td>&nbsp;</td><td><input type="submit" name="generate" value="Complete the Invoice.."></td></tr>';
      echo '</form></table>';

}elseif($client_id){
        echo '<form action="create_invoice.php?client_id='.$client_id.'&project_id='.$project_id.'" method="POST">';
        $projects=mysql_query("SELECT * FROM projects WHERE client_id='$client_id'");
               echo '<P><font face="'.$admin_font.'" size="2">Select a project to bill: <select onChange="chooseproject(this.value)"><option value="">---</option>';

               $pe=mysql_query("SELECT * FROM clients ORDER BY name");
               while($pro=mysql_fetch_array($projects)){
                                                        $sel="";if($project_id==$pro[id]){$sel="SELECTED";}
                                                        echo '<option '.$sel.' value="'.$pro[id].'">'.$pro[project_name].'</option>';
               }
               echo '</select>';
               if($project_id){
               echo '<P>Select the stages you wish to bill for..<P>';
                   $stages=mysql_query("SELECT * FROM project_stages WHERE project_id='$project_id' && billed='0'");
                   while($st=mysql_fetch_array($stages)){
                                                         $comp="";$sel="";if($st[completed]){
                                                         $comp="<font size=1 color=red> STAGE COMPLETED</font>";
                                                         $sel="CHECKED";
                                                         }
                                                         echo '<input type=checkbox '.$sel.' name="stage['.$st[id].']" value="'.$st[id].'">';
                                                         echo $st[stage_name];
                                                         echo $comp;
                                                         echo ' ('.$payment_unit.$st[cost].')';
                                                         echo '<br>';
                   }
                   echo '<P><input type=submit name="CREATEIT" value="Make the invoice>>">';
               }
}else{
      echo '<BR><BR><font face="'.$admin_font.'" size="2">Select the client you would like to bill..<P>';
      echo '<select onChange="chooseclient(this.value)"><option value="">---</option>';

$pe=mysql_query("SELECT * FROM clients ORDER BY name");
while($p=mysql_fetch_array($pe)){
 echo '<option value="'.$p[id].'">'.$p[name].'</option>';
}
echo '</select>';

}

include "footer.php";
?>
