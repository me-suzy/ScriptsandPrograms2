<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
*/
include_once("include/functions.inc.php");
GLOBAL $dbh;
if(!$dbh)dbconnect();
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

## Reset $variables
$num_batch=$sum_batch=0;

## Master Select SQL
$sql    = "SELECT * FROM client_invoice, client_info ";
$sql   .= "WHERE client_invoice.client_id = client_info.client_id ";
$sql   .= "AND client_invoice.invoice_payment_method = 1 ";
$sql   .= "AND client_info.client_status = 2 ";
$sql   .= "AND ( client_invoice.invoice_date_paid = 0 || ( client_invoice.invoice_amount > client_invoice.invoice_amount_paid ) )";
$result = mysql_query($sql,$dbh);
$num    = mysql_num_rows($result);

## --- DEBUGGING DATA --- ##
if($debug)echo SFB.$sql.EF." -- ($num)<br>";

if (!$result) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }
while($client_invoice=mysql_fetch_array($result))
{
      ## --- DEBUGGING DATA --- ##
      if($debug)echo SFB."-------<br>->".$client_invoice['invoice_id']."-".$client_invoice['invoice_amount'].EF."<br>";

      $sql="SELECT * FROM client_info WHERE client_id=".$client_invoice['client_id']."";
      if($debug)echo SFB.$sql.EF."<br>";
      $result2=mysql_query($sql,$dbh);
      if (!$result2) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }
      $client_info=mysql_fetch_array($result2);

      ## --- DEBUGGING DATA --- ##
      if($debug)echo SFB.">>>".$client_info['client_id']."-".$client_info['client_email'].EF."<br>";

      $x_Invoice_Num  = addslashes($client_invoice['invoice_id']);
      $x_Description  = addslashes($x_Description);
      $x_Amount       = addslashes($client_invoice['invoice_amount']);
      $x_Method       = addslashes("CC");
      $x_Type         = addslashes("AUTH_CAPTURE");
      $x_Card_Num     = addslashes($client_info['billing_cc_num']);
      $x_Exp_Date     = addslashes($client_info['billing_cc_exp']);
      $x_CC_Code      = addslashes($client_info['billing_cc_code']);
      $x_Cust_ID      = addslashes($client_info['client_id']);
      $x_First_Name   = addslashes($client_info['client_fname']);
      $x_Last_Name    = addslashes($client_info['client_lname']);
      $x_Company      = addslashes($client_info['client_company']);
      $x_Address      = addslashes($client_info['client_address']);
      $x_City         = addslashes($client_info['client_city']);
      $x_State        = addslashes($client_info['client_state']);
      $x_Zip          = addslashes($client_info['client_zip']);
      $x_Phone        = addslashes($client_info['client_phone1']);
      $x_Email        = addslashes($client_info['client_email']);

      ## Check for duplicates
      $already_inserted=mysql_num_rows(mysql_query("SELECT x_Invoice_Num FROM authnet_batch WHERE x_Invoice_Num='$x_Invoice_Num' ",$dbh));
      if ($already_inserted==0)
      {
        $db_table = "authnet_batch";
        include("include/db_attributes.inc.php");
        $result3=mysql_query($insert_sql,$dbh);
        if (!$result3) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }

        ## --- DEBUGGING DATA --- ##
        if($debug)echo SFB."->INSERT:".$insert_sql.EF."<br>";

        $num_batch++;
        $sum_batch+=$x_Amount;
      }
}
?>