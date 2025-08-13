<? ## TIER2 PAGE
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/


## Must be included ONLY once!
include_once("include/functions.inc.php");
session_register("payment_completed");
if ($debug) { $payment_completed = NULL; }

## Validate that the user
if (!testlogin()&&(!$this_user||!$this_admin))  { Header("Location: http://$standard_url?op=logout"); exit; }

$is_popup=TRUE;

GLOBAL $data;

if ($this_admin) {
    $sql = "SELECT * FROM client_info WHERE client_id=$client_id";
    if($debug) echo SFB.$sql.EF."<br>";
    $result = mysql_query($sql,$dbh) or die (mysql_error());
    $this_user = mysql_fetch_array($result);
}

// validate and clean form input
include("include/misc/validate_form_input.inc.php");

$billing_cc_exp = $x_Exp_Month."/".$x_Exp_Year;

if (!ereg("([0-9]{1,2})/([0-9]{4})",$billing_cc_exp)) $oops .= "<font color=red><b>[".ERROR."]</b></font> ".EXPIRESINVALID."<br>";
$billing_cc_type=validate_cc_input($billing_cc_num,NULL);
if (!$billing_cc_type||!$data) $oops .= "<font color=red><b>[".ERROR."]</b></font> ".CCNUMINVALID."!<br>";

$sql = "SELECT * FROM client_invoice WHERE invoice_id=$id AND client_id=".$this_user['client_id']."";
if($debug) echo SFB.$sql.EF."<br>";
addslashes($result = mysql_query($sql,$dbh));
$this_invoice = mysql_fetch_array($result);

if ($oops||$payment_completed) {
    start_short_html(SECUREPAYMENTS);
     if ($this_invoice[invoice_amount] <= $this_invoice[invoice_amount_paid]) {
         start_table("".INVOICENUM." [".$this_invoice[0]."]</b><br><font color=gray><b>".display_currency($this_invoice['invoice_amount'])."</b></font>","200");
         echo "<tr><td align=center>".SFB."<b><font color=GREEN>".PAID."</font></b>: ".stamp_to_date($this_invoice[invoice_date_paid]).EF."</td></tr>";
         stop_table();
         $payment_completed = TRUE;
     } else {
         echo "<form method=post action=$https://$secure_url"."$page?op=pay_invoice_response&".session_id().">";
         start_table("".INVOICENUM." [".$this_invoice[0]."]</b><br><font color=gray><b>".display_currency($this_invoice['invoice_amount'])."</b></font>","200");
              echo "<tr><td>".SFB.$oops.EF."</td></tr>";
              echo "<tr><td>".SFB."<b>".CCNUM.":</b>".EF."<br><input type=TEXT name=billing_cc_num size=25 maxlength=20><br>".SFB.$we_accept.EF."</td></tr>";
              echo "<tr><td> ".SFB."<b>".EXPIRATIONDATE2.":</b>".EF."<br>";
                 ?>
                    <select name=x_Exp_Month>
                    <?
                    for($i=1;$i<=12;$i++){
                        echo "<option value=\"$i\"";
                        if($x_Exp_Month==$i) { echo " SELECTED "; }
                        echo ">$i</option>";
                    }
                    ?>
                    </select>
                    /
                    <select name=x_Exp_Year>
                    <?
                    for($i=date("Y");$i<=date("Y")+10;$i++){
                        echo "<option value=\"$i\"";
                        if($x_Exp_Year==$i) { echo " SELECTED "; }
                        echo ">$i</option>";
                    }
                    ?>
                    </select>
                    (mm/yyyy)
                 <?
              echo "<tr><td><center>".CHARGE_IMG."</center><input type=hidden name=id value=$id></td></tr>";
         if ($this_admin) { echo "<input type=hidden name=client_id value=".$this_user[client_id].">"; }
         stop_table();
         stop_form();
     }
    stop_short_html(1);
}
else
{
  if ($tier2)
  {
    if ($authnet_enabled)
    {
       include("include/misc/authnet.php");
       $string  = "x_Invoice_Num=". urlencode($this_invoice['invoice_id'])."&";
       $string .= "x_Description=". urlencode($x_Description)."&";
       $string .= "x_Amount=".      urlencode($this_invoice['invoice_amount'])."&";
       $string .= "x_Method=".      urlencode("CC")."&";
       $string .= "x_Type=".        urlencode("AUTH_CAPTURE")."&";
       $string .= "x_Card_Num=".    urlencode($data)."&";
       $string .= "x_Exp_Date=$billing_cc_exp&";
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

       $this_charge=authnet_gateway($string);

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
                 $do_update  = 1;
            break;

            case 2: ## DECLINED
                 $auth_return = 2;
                 $cc_result = "<b>".YOURORDERDECLINED."</b><br>";
            break;

            case 3: ## ERROR
                 $auth_return = 3;
                 $cc_result = "<b>".YOURORDEREERROR."</b><br>";
            break;

            default: ## ERROR
                 $auth_return = 3;
                 $cc_result = "<b>".YOURORDEREERROR."</b><br>";
            break;
       }
    }
    elseif ($echo_enabled)
    {
       include("include/misc/echo.php");

       $string  = "billing_ip_address=".urlencode($HTTP_SERVER_VARS["REMOTE_ADDR"])."&";
       $string .= "product_description=". urlencode($x_Description)."&".
       $string .= "cc_number=".         urlencode($data)."&";
       list($ccexp_month,$ccexp_year) = explode("/",$billing_cc_exp);
       $string .= "ccexp_month=".       urlencode($ccexp_month)."&";
       $string .= "ccexp_year=".        urlencode($ccexp_year)."&";
       $string .= "grand_total=".       urlencode($this_invoice['invoice_amount'])."&";
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
            break;

            case D: ## DECLINED
                 $auth_return = 2;
                 $cc_result = "<b>".YOURORDERDECLINED."</b><br>";
            break;

            default: ## ERROR
                 $auth_return = 3;
                 $cc_result = "<b>".YOURORDEREERROR."</b><br>";
            break;
       }
    }
  }

  if ($do_update)
  {
    $update_sql = "UPDATE client_invoice SET invoice_amount_paid='".str_replace(",","",display_currency($this_invoice['invoice_amount'],1))."',
                                             invoice_date_paid='".mktime()."',
                                             auth_return='$auth_return',
                                             auth_code='$auth_code',
                                             avs_code='$avs_code',
                                             trans_id='$trans_id',
                                             batch_stamp='".mktime()."' WHERE invoice_id='".$this_invoice['invoice_id']."'";
    if($debug)echo $update_sql."<br>";
    @mysql_query($update_sql,$dbh);
    $delete_sql = "DELETE FROM authnet_batch WHERE x_Invoice_Num='".$this_invoice['invoice_id']."'";
    if($debug)echo $delete_sql."<br>";
    @mysql_query($delete_sql,$dbh);
    $payment_completed = TRUE;
  }

  ## client_register entry credit
  if ($do_update)
  {
      $reg_desc = MANUALPAYMENT;
      $reg_payment = $this_invoice['invoice_amount'];
      register_insert($this_user['client_id'],$reg_desc,$this_invoice['invoice_id'],0,$reg_payment);
  }

  start_short_html(SECUREPAYMENTS);
  start_table("".INVOICENUM." [".$this_invoice[0]."]</b><br><font color=gray><b>".display_currency($this_invoice['invoice_amount'])."</b></font>","200");
  echo "<tr><td>".SFB.$cc_result.EF."</td></tr>";
  stop_table();
  stop_short_html(1);
}
?>