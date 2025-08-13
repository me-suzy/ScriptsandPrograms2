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

     ?>
                    <script language="javascript">
                         <!--
			var dfgdfgfdg;
			function pop_invoice(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_invoice.php?invoice_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                    }

                     function settlenow(id, settle)
                     {
                          window.location="billing_settle.php?settle="+settle+"&key="+document.f.key.value+"&invoice_id="+id
                     }
                     
                     function wrongkey()
                     {
                       alert("The key you entered was incorrect");
                       window.location="billing.php"
                     }
                     
                     function donesettlement()
                     {
                       alert("Settlement completed and successful..");
                       window.location="billing.php"
                     }


                                                                                                                                                                                                                                                                                                                                                                //-->
                         </script>
                  <?

                  if($settle){
                           if($key==$settle_no_payment_key){
                              $inv=mysql_fetch_array(mysql_query("SELECT * FROM invoices WHERE id='$invoice_id'"));
                              mysql_query("INSERT INTO money_received SET authkey='enter', receipt='none', admin_id='$admin_id', client_id='".$inv[to_client]."', date='".time()."', amount='$settle', invoice='$invoice_id', comments='$comments', method='Settlement', method_identifier='".$invoice_id."settlement'");
                              mysql_query("UPDATE invoices SET paid='1' WHERE id='$invoice_id'");
                                     echo '<script language="javascript">
                                    donesettlement();
                              </script>';
                           }else{
                              echo '<script language="javascript">
                                    wrongkey();
                              </script>';
                           }
                  exit;
                  }


echo '<font face="'.$admin_font.'" size="2">';

$inv=mysql_fetch_array(mysql_query("SELECT * FROM invoices WHERE id='$invoice_id'"));

echo '<form name="f">You wish to settle <a href="javascript: pop_invoice('.$inv[id].')" class="left_menu">invoice # '.$inv[id].'</a><P>';
              $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='$invoice_id'");
                            $tpaid=0;while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
                                              if($tpaid+$inv_amount[$id]>$inv[amount]){
                                                   $inv_amount[$id]=$inv[amount]-$tpaid;
                                              }
                                              $settle=$inv[amount]-$tpaid;

echo $payment_unit.$tpaid.' has already been paid towards this invoice. You are settling a value of '.$payment_unit.$settle.'<P>';

if($settle_no_payment_key){
 echo 'To do this you need to enter the settlement key here: <input type=text name=key><P>';
}
 echo '<a href="javascript: settlenow('.$inv[id].', '.$settle.')" class="left_menu">Settle It Now</a>';

include "footer.php";
?>
