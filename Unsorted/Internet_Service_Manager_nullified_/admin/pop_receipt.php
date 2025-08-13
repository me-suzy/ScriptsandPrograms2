<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="billing";
include "../conf.php";
include "auth.php";

echo '<HTML>
<HEAD>
<TITLE>Receipt #: '.$receipt_id.'</TITLE>

</HEAD>
<BODY bgcolor="#efefef">';

$items=mysql_query("SELECT * FROM money_received WHERE method_identifier='$receipt_id'");

while($it=mysql_fetch_array($items)){
    $amount=$amount+$it[amount];
    $last=$it;
}

$unpaidins=mysql_query("SELECT * FROM invoices WHERE paid='0' && to_client='$client_id'");
                    $outstanding_balance=0; while($up=mysql_fetch_array($unpaidins)){
                              $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='$inv_id'");
                                              $tpaid=0;while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
                              $outstanding_balance=$outstanding_balance+$up[amount]-$tpaid;
                     }

                     //need to email a receipt to the contact..
                     $template_file="html_receipt.txt";
              //build the receipt to send..
              $fd = fopen ("templates/$template_file", "r");
              while (!feof ($fd)) {
              $template.=fgets($fd, 4096);
              }
              fclose ($fd);
              $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$last[client_id]."'"));
              $contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$client[bill_to_contact]."'"));
              $template=str_replace("%date%", date("F j, Y", $last[date]), $template);
              $template=str_replace("%name%", $contact[firstname].' '.$contact[lastname], $template);
              $template=str_replace("%id%", $receipt_id, $template);
              $template=str_replace("%auth_key%", $last[authkey], $template);
              $template=str_replace("%amount%", $payment_unit.$amount, $template);
              $template=str_replace("%outstanding_balance%", $payment_unit.$outstanding_balance, $template);
              echo $template;
if($print){
    echo '<script language="javascript">
         window.print()
</script>';
}

?>
</BODY>
</HTML>
