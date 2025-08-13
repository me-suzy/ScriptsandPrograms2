<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
** THIS IS THE CONFIG FILE.
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
$dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
$this_email_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='email'"));
##
##
$allow_str_emails    = TRUE;
##
##
$send_client_email   = $this_email_config["config_1"];
$cc_email_id         = $this_email_config["config_2"];
$check_email_id      = $this_email_config["config_3"];
$approved_email_id   = $this_email_config["config_4"];
$declined_email_id   = $this_email_config["config_5"];
$error_email_id      = $this_email_config["config_6"];
$manual_email_id     = $this_email_config["config_7"];
$inv_email_priority  = $this_email_config["config_8"];
$inv_email_subject   = $this_email_config["config_9"];
$inv_error_to        = $this_email_config["config_10"];
$inv_email_cc        = $this_email_config["config_11"];
$inv_email_from      = $this_email_config["config_12"];
$default_from        = $this_email_config["config_13"];
$default_x_sender    = $this_email_config["config_14"];
$default_return_path = $this_email_config["config_15"];
$default_reply_to    = $this_email_config["config_16"];
$default_email_bcc   = $this_email_config["config_17"];
$default_errors_to   = $this_email_config["config_18"];
$default_signature   = $this_email_config["config_19"];
$allow_sql_emails    = $this_email_config["config_20"];
//$this_email_config["config_21"];
$paypal_email_id     = $this_email_config["config_30"];
$worldpay_email_id   = $this_email_config["config_32"];
$allow_html_emails   = $this_email_config["config_31"];
$expired_cc_email_id = $this_email_config["config_33"];
$support_type_menu   = $this_email_config["config_48"];
$email_to_menu       = $this_email_config["config_49"];
$email_subject_menu  = $this_email_config["config_50"];
##
## User Contact form select_menu TO:
##
$each_email_to_menu  = explode(",",$email_to_menu);
$count = count($each_email_to_menu);
if ($count == 1) {
   $each_guts = explode("|",trim($each_email_to_menu[0]));
   $email_to_menu = "<input type=hidden name=email_to[] value=\"".$each_guts[0]."\"> ".$each_guts[1];
} elseif ($count > 1) {
   $email_to_menu = "<select name=email_to>";
   for ($i = 0; $i <= $count - 1; $i++) {
      $each_guts = explode("|",trim($each_email_to_menu[$i]));
      $email_to_menu .= "<option value=\"".$each_guts[0]."\">".$each_guts[1]."</option>";
   }
   $email_to_menu .= "</select>";
}
##
## User Contact form select_menu SUBJECT:
##
$each_email_subject_menu  = explode(",",$email_subject_menu);
$count = count($each_email_subject_menu);
if ($count == 1) {
   $each_guts = explode("|",trim($each_email_subject_menu[0]));
   $email_subject_menu = "<input type=hidden name=user_email_subject value=\"".$each_guts[0]."\"> ".$each_guts[1];
} elseif ($count > 1) {
   $email_subject_menu = "<select name=user_email_subject>";
   for ($i = 0; $i <= $count - 1; $i++) {
      $each_guts = explode("|",trim($each_email_subject_menu[$i]));
      $email_subject_menu .= "<option value=\"".$each_guts[0]."\">".$each_guts[1]."</option>";
   }
   $email_subject_menu .= "</select>";
}
##
## User Support Desk form select_menu CALL_TYPE:
##
$each_support_type_menu  = explode(",",$support_type_menu);
function support_type_menu($id)
{
         GLOBAL $op,$details_view,$each_support_type_menu;

         $count = count($each_support_type_menu);
         if ($count == 1) {
            $each_guts = explode("|",trim($each_support_type_menu[0]));
            $support_type_menu = "<input type=hidden name=call_type value=\"".$each_guts[0]."\"> ".$each_guts[1];
            $this=$each_guts[1];
         } elseif ($count > 1) {
            $support_type_menu = "<select name=call_type>";
            for ($i = 0; $i <= $count - 1; $i++) {
               $each_guts = explode("|",trim($each_support_type_menu[$i]));
               $support_type_menu .= "<option value=\"".$each_guts[0]."\"";
                  if ($id==$each_guts[0]) { $support_type_menu.= " SELECTED "; $this=$each_guts[1]; }
               $support_type_menu .= ">".$each_guts[1]."</option>";
            }
            $support_type_menu .= "</select>";
         }
         return ($details_view) ? $this : $support_type_menu ;
}
?>