<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
** THIS IS THE MASTER FUNTIONS FILE (Included by every file)
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
include_once("include/functions.inc.php");
GLOBAL $dbh;
if(!$dbh)dbconnect();
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }
@set_time_limit(1000000);

$batch_offset  = ($batch_offset=="") ? 0+$batch_limit : $batch_offset+$batch_limit ;
$batch_limit   = ($batch_limit=="")  ? $default_batch_limit : $batch_limit ;
$sql           = "SELECT * FROM authnet_batch LIMIT $batch_offset,$batch_limit";

addslashes($result = mysql_query($sql,$dbh));
$fields = mysql_num_fields($result);
$rows = mysql_num_rows($result);
if($debug)echo SFB.$sql.EF."<br>";

### START BATCH LOOP
while ($myrow = mysql_fetch_array($result))
{
  $cycle_count++;

  // $status_types = array("1" => INACTIVE, "2" => ACTIVE, "3" => PENDING, "4" => "Canceled");
  $string=$auth_return=$authorize=$auth_code=$avs_code=$trans_id=$invoice_date_paid=$invoice_amount_paid=NULL;
  $this_user = mysql_fetch_array(mysql_query("SELECT * FROM client_info WHERE client_id='".$myrow[9]."'",$dbh));

  # Charge ONLY active clients!
  if ($this_user['client_status']==2)
  {
    $this_num  = ($this_user['billing_cc_num']!=$myrow[6]) ? $this_user['billing_cc_num'] : $myrow[6] ; # Select new cc
    $this_exp  = ($this_user['billing_cc_exp']!=$myrow[7]) ? $this_user['billing_cc_exp'] : $myrow[7] ; # Select new exp
    $data      = encrpyt($this_user[20].$decrypt_key,$this_num,1);
    if ($debug) echo MFB."CC: $data :: encrpyt(".$this_user[20]."$decrypt_key,$this_num,1);".EF."<br>";

    ######### AUTHORIZE.NET/PAYMENTPLANET #############
    if ($authnet_enabled)
    {
       include_once("include/misc/authnet.php");
       $string  = "x_Invoice_Num=". urlencode($myrow[1])."&";
       $string .= "x_Description=". urlencode($x_Description)."&";
       $string .= "x_Amount=".      urlencode($myrow[3])."&";
       $string .= "x_Method=".      urlencode("CC")."&";
       $string .= "x_Type=".        urlencode("AUTH_CAPTURE")."&";
       $string .= "x_Card_Num=".    urlencode($data)."&";
       $string .= "x_Exp_Date=$this_exp&";
       $string .= "x_Cust_ID=".     urlencode($this_user['client_id'])."&";
       $string .= "x_First_Name=".  urlencode($this_user['client_fname'])."&";
       $string .= "x_Last_Name=".   urlencode($this_user['client_lname'])."&";
       $string .= "x_Company=".     urlencode($this_user['client_company'])."&";
       $string .= "x_Address=".     urlencode($this_user['client_address'])."&";
       $string .= "x_City=".        urlencode($this_user['client_city'])."&";
       $string .= "x_State=".       urlencode($this_user['client_state'])."&";
       $string .= "x_Zip=".         urlencode($this_user['client_zip'])."&";
       $string .= "x_Country=".     urlencode($this_user['client_country'])."&";
       $string .= "x_Phone=".       urlencode($this_user['client_phone1'])."&";
       $string .= "x_Fax=".         urlencode($this_user['client_phone2'])."&";
       $string .= "x_Email=".       $this_user['client_email'];

       $this_charge = authnet_gateway($string);
       $auth_return = $this_charge[0];

       switch($this_charge[0])
       {
            case 1: ## APPROVED
                 $auth_return = 1;
                 $auth_code   = $this_charge[4];
                 $avs_code    = $this_charge[5];
                 $trans_id    = $this_charge[6];
                 if ($debug) echo "$auth_code,$avs_code,$trans_id<br>";
                 $cc_result   = "<b>".YOURORDERSUCCESS."</b><br>";
                 $cc_result  .= AUTHCODE.": $auth_code<br>";
                 $cc_result  .= AVSCODE.": $avs_code<br>";
                 $cc_result  .= TRANSID.": $trans_id<br>";
                 $do_update   = 1;
                 $invoice_amount_paid = $myrow[3];
                 $invoice_date_paid   = mktime();
                 $batch_sum_approved += $myrow[3];
                 $batch_num_approved ++;
                 $email_id            = $approved_email_id;
                 $batch_delete_sql[]  = "DELETE FROM authnet_batch WHERE an_id='".$myrow[0]."'";
            break;

            case 2: ## DECLINED
                 $auth_return = 2;
                 $cc_result = "<b>".YOURORDERDECLINED."</b><br>";
                 $auth_code   = $this_charge[4];
                 $avs_code    = $this_charge[5];
                 $trans_id    = $this_charge[6];
                 $invoice_amount_paid = "0.00";
                 $invoice_date_paid   = "0";
                 $batch_sum_declined += $myrow[3];
                 $batch_num_declined ++;
                 $email_id            = $declined_email_id;
            break;

            default: ## ERROR
                 $auth_return = 3;
                 $cc_result = "<b>".YOURORDEREERROR."</b><br>";
                 $auth_code   = $this_charge[4];
                 $avs_code    = $this_charge[5];
                 $trans_id    = $this_charge[6];
                 $invoice_amount_paid = "0.00";
                 $invoice_date_paid   = "0";
                 $batch_sum_error    += $myrow[3];
                 $batch_num_error    ++;
                 $email_id            = $error_email_id;
            break;
       }
    }
    ######### ECHO #############
    elseif ($echo_enabled)
    {
       include_once("include/misc/echo.php");
       $string  = "billing_ip_address=".urlencode($HTTP_SERVER_VARS["REMOTE_ADDR"])."&";
       $string .= "cc_number=".         urlencode($data)."&";
       list($ccexp_month,$ccexp_year) = explode("/",$this_exp);
       $string .= "ccexp_month=".       urlencode($ccexp_month)."&";
       $string .= "ccexp_year=".        urlencode($ccexp_year)."&";
       $string .= "grand_total=".       urlencode($myrow[3])."&";
       $string .= "billing_first_name=".urlencode($this_user['client_fname'])."&";
       $string .= "billing_last_name=". urlencode($this_user['client_lname'])."&";
       $string .= "billing_company_name=".urlencode($this_user['client_company'])."&";
       $string .= "billing_address1=".  urlencode($this_user['client_address'])."&";
       $string .= "billing_city=".      urlencode($this_user['client_city'])."&";
       $string .= "billing_state=".     urlencode($this_user['client_state'])."&";
       $string .= "billing_zip=".       urlencode($this_user['client_zip'])."&";
       $string .= "billing_country=".   urlencode($this_user['client_country'])."&";
       $string .= "billing_phone=".     urlencode($this_user['client_phone1'])."&";
       $string .= "billing_fax=".       urlencode($this_user['client_phone2'])."&";
       $string .= "billing_email=".     $this_user['client_email'];

       $this_charge=echo_gateway($string);

       switch($this_charge['status'])
       {
            case G: ## APPROVED
                 $auth_return = 1;
                 $auth_code   = $this_charge['auth_code'];
                 $avs_code    = $this_charge['avs_result'];
                 $trans_id    = $this_charge['order_number'];
                 if ($debug) echo "$auth_code,$avs_code,$trans_id<br>";
                 $cc_result   = "<b>".YOURORDERSUCCESS."</b><br>";
                 $cc_result  .= AUTHCODE.": $auth_code<br>";
                 $cc_result  .= AVSCODE.": $avs_code<br>";
                 $cc_result  .= TRANSID.": $trans_id<br>";
                 $do_update   = 1;
                 $invoice_amount_paid = $myrow[3];
                 $invoice_date_paid   = mktime();
                 $batch_sum_approved += $myrow[3];
                 $batch_num_approved ++;
                 $email_id            = $approved_email_id;
                 $batch_delete_sql[]  = "DELETE FROM authnet_batch WHERE an_id='".$myrow[0]."'";
            break;

            case D: ## DECLINED
                 $auth_return = 2;
                 $cc_result = "<b>".YOURORDERDECLINED."</b><br>";
                 $auth_code   = $this_charge['auth_code'];
                 $avs_code    = $this_charge['avs_result'];
                 $trans_id    = $this_charge['order_number'];
                 $invoice_amount_paid = "0.00";
                 $invoice_date_paid   = "0";
                 $batch_sum_declined += $myrow[3];
                 $batch_num_declined ++;
                 $email_id            = $declined_email_id;
            break;

            default: ## ERROR
                 $auth_return = 3;
                 $cc_result = "<b>".YOURORDEREERROR."</b><br>";
                 $auth_code   = $this_charge['auth_code'];
                 $avs_code    = $this_charge['avs_result'];
                 $trans_id    = $this_charge['order_number'];
                 $invoice_amount_paid = "0.00";
                 $invoice_date_paid   = "0";
                 $batch_sum_error    += $myrow[3];
                 $batch_num_error    ++;
                 $email_id            = $error_email_id;
            break;
       }
    }
    ##-------##


    ## UPDATE THIS INVOICE
    $update_sql = "UPDATE client_invoice SET invoice_amount_paid='$invoice_amount_paid',
                                          invoice_date_paid='$invoice_date_paid',
                                          auth_return='$auth_return',
                                          auth_code='$auth_code',
                                          avs_code='$avs_code',
                                          trans_id='$trans_id',
                                          batch_stamp='".mktime()."' WHERE invoice_id='".$myrow[1]."'";
    if($debug) echo $update_sql."<br>";
    @mysql_query($update_sql,$dbh);

    ## SEND CUSTOM INVOICE EMAIL
    if ($send_client_email && $email_id)
    {
        $email_type     = "invoice";
        $where          = "i.invoice_id = '".$myrow[1]."'";
        $email_to[0]    = $myrow[9];
        $email_cc       = $inv_email_cc;
        $email_priority = $inv_email_priority;
        $email_subject  = $inv_email_subject;
        $email_from     = $inv_email_from;
        $email_body     = "%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%";
        @send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
    }

    $log_comments = "Process Invoice $invoice_id [Return: $auth_return]: on ".date("$date_format: h:i:s")." by ".$this_admin['admin_realname'];
    log_event($this_user['client_id'],$log_comments,3);

    if ($auth_return == 1)
    {
       ## client_register entry
       $reg_desc = "Auto Batch";
       $reg_payment = $invoice_amount_paid;
       register_insert($this_user['client_id'],$reg_desc,$invoice_id,NULL,$reg_payment);
    }

  } #--> End if client_status==2
} #--> NEXT IN BATCH LOOP
?>