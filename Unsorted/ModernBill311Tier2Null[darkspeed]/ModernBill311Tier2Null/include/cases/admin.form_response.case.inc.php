<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+

## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if ($this_admin[admin_level]!=9&&($db_table=="config"||
                                  $db_table=="admin"||
                                  $db_table=="authnet_batch"||
                                  $db_table=="package_type"||
                                  $db_table=="package_feature"))
{
    start_short_html($title);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".MFB.ACCESSDENIED.EF."</b>&nbsp;&nbsp;&nbsp;&nbsp;";
    stop_short_html();
    exit;
}
/* ---- ACCEPT ALL FORM POSTS ----*/
// THIS IS THE MASTER CASE THAT WILL ADD & EDIT EVERY TABLE
## Used to Fake Magic Quotes if OFF
## ---------------------------------
/*
if (get_magic_quotes_gpc()) {
    for (reset($HTTP_POST_VARS); list($k, $v) = each($HTTP_POST_VARS); ) {
         $$k = addslashes($v);
    }
}
*/
        validate_table($db_table,1); if(isset($error)) return;

        // Reset $oops variable & validate all Form Input
        $oops=NULL;
        if ($db_table!="config" &&
            $db_table!="email_config" &&
            $db_table!="faq_questions" &&
            $db_table!="package_feature")include("include/misc/validate_form_input.inc.php");

        // Reload db_table attributes
        $submit=1;
        include("include/db_attributes.inc.php");

        // Check for "required" fields
        if (!$make_payments) {
             $i=0;
             foreach ($args as $value) {
               if (!${$value["column"]}&&$args[$i]["required"]==1) {
                 $oops.="[".REQUIRED."] ".$args[$i]["title"]."<br>";
               }
             $i++;
             }
        }

        // Prepare SQL Statement
        $sql = ($do=="edit") ? $update_sql : $insert_sql ;
        if($debug)echo SFB.$sql.EF."<br>";

        // GENERATE FORM AGAIN if ERROR
        if (isset($oops)){
            start_html();
            start_form("form_response",$db_table);
            admin_heading($tile);
            $do_disp = ($do=="add") ? DOADD : DOEDIT ;
            start_table(FORM.": $title [$do_disp]",$a_tile_width);
                 echo "<tr><td colspan=2><center>".SFB.PLEASEFILLIN."</center><br><br>$oops<hr size=1>".EF."</td></tr>";
                 build_form($args,$result);
                 echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center>
                        <input type=hidden and name=do value=\"$do\">
                        <input type=hidden name=id value=\"$id\">
                        <input type=hidden name=tile value=\"$tile\">
                        <input type=hidden name=from value=\"$from\">
                        </td></tr>";
            stop_table();
            stop_form();
            stop_html();
        } elseif (!mysql_query($sql,$dbh)) {
            echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
        } else {
            // Header override
         if ($db_table=="client_invoice"&&$session_from) {
             Header("Location: $page?$session_from&".session_id());
         } else {
            if ($uri) {
                $url = "$page?$uri&".session_id();
            } elseif ($this_user) {
                $url = "$page?op=details&tile=myinfo&".session_id();

            } elseif ($from=="client_id"||$db_table=="client_info") {
                if ($db_table=="client_info") {
                    $client_id = ($do=="add") ? mysql_insert_id() : $client_id ;
                }
                $url = "$page?op=client_details&db_table=client_info&tile=$tile&id=client_id|$client_id&".session_id(); # <-- $db_table must be client_info

            } elseif ($from=="client_register"||$db_table=="client_register") {
                $url = "$page?op=menu&tile=client_register&".session_id();

            } elseif ($from=="package_admin") {
                $url = "$page?op=menu&tile=package&".session_id();

            } elseif ($make_payments) {
                $url = "$page?op=client_invoice&db_table=$db_table&tile=$tile&id=invoice_id|$invoice_id" ;
                ## SEND CUSTOM INVOICE EMAIL
                if ($send_client_email && $manual_email_id)
                {
                  $email_id       = $manual_email_id;
                  $email_type     = "invoice";
                  $where          = "i.invoice_id = $invoice_id";
                  $email_to[0]    = $client_id;
                  $email_cc       = $inv_email_cc;
                  $email_priority = $inv_email_priority;
                  $email_subject  = $inv_email_subject;
                  $email_from     = $inv_email_from;
                  $email_body     = "%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%";
                  @send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
                }
            } else {
              if ($do=="edit") $id=explode("|",$id); $url = "$page?op=details&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]&".session_id()."" ;
              if ($do=="add") $url = "$page?op=view&db_table=$db_table&tile=$tile&".session_id()."" ;
            }
            if ($debug) {
               echo TFB."<a href=$url>$url</a>".EF;
            } else {
               header("Location: $url");
            }
         } // ENDIF SESSION_FROM
        }
?>