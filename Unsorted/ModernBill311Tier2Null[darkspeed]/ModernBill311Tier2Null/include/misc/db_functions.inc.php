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

function dbconnect()
{
         GLOBAL $dbhits,$dbh,$locale_db_host,$locale_db_login,$locale_db_pass,$locale_db_name;
         $dbhits++;
         $dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
         mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
         return $dbh;
}

## EDIT WITH --> E X T R E M E <-- CAUTION!

## --> ALL DATE RELATED FUNCTIONS ARE HERE!
##
## NOTE: ALL Dates stored in the db are in UNIX seconds. These functions below
##       translate those raw dates into a human readable format.
##
##       THE CURRENT FORMAT IS YYYY/MM/DD.
##
##       I HAVE NOT TESTED THE EFFECTS OF CHANGING THIS ORDER.
##       YOU MAY DO SO CAUTIOUSLY...Please contact ModernBill for questions.
##
function stamp_to_date($stamp)
{
         GLOBAL $date_format;
         $date = date("Y/m/d",$stamp);
         $date = ($date == "1969/12/31") ? "n/a" : date($date_format,$stamp) ;
         return ($stamp==0) ? NULL : $date ;
}

function date_input_generator($stamp=0,$name,$default=NULL)
{
         GLOBAL $op,$details_view;
         $stamp = ($op=="form_response"&&$stamp) ? date_to_stamp($stamp) : $stamp ;
         $value = ($default&&!$stamp) ? $default : stamp_to_date($stamp) ;
         return ($op=="view"||$op=="details"||$op=="reports"||$details_view) ? stamp_to_date($stamp) : "<input type=TEXT name=\"$name\" value=\"$value\" size=12 maxlength=10>" ;
}

function date_to_stamp($date)
{
         GLOBAL $date_format;
         if (date_check($date)) {
             switch ($date_format) {
                  case "Y/m/d": list($y,$m,$d) = explode("/",$date); break;
                  case "d/m/Y": list($d,$m,$y) = explode("/",$date); break;
                  case "m/d/Y": list($m,$d,$y) = explode("/",$date); break;
                  default:      list($y,$m,$d) = explode("/",$date); break;
             }
             $date_to_stamp = mktime(0,0,0,$m,$d,$y);
         } else {
             $date_to_stamp = mktime();
         }
         return ($date) ? $date_to_stamp : NULL ;
}

function date_check($date)
{
         GLOBAL $date_format;
         /*
         $date_format_types =
                     array("Y/m/d" => "YYYY/MM/DD",
                           "d/m/Y" => "DD/MM/YYYY",
                           "m/d/Y" => "MM/DD/YYYY");
         */
         switch ($date_format) {
            case "Y/m/d": return (ereg("([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})",$date)) ? TRUE : FALSE ; break;
            case "d/m/Y": return (ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})",$date)) ? TRUE : FALSE ; break;
            case "m/d/Y": return (ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})",$date)) ? TRUE : FALSE ; break;
            default:      return (ereg("([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})",$date)) ? TRUE : FALSE ; break;
         }
}

function mini_logo()
{
         GLOBAL $version,$start_timer,$crazy;
         $crazy=($crazy)?$crazy:0;
         echo "<br>";
         start_table(NULL,"30","center");
         echo "<tr><td align=center><a href=http://www.your.server.com target=_blank><img src=images/small_logo.gif ".GetImageSize("images/small_logo.gif")." border=0></a></td></tr>";
         echo "<tr><td align=center>".TFB."<font color=GREEN>$version</font>".EF."</td></tr>";
         echo "<tr><td align=center>".TFB."<font color=GREEN>T:".sprintf("%.3f",abs(get_microtime()-$start_timer))."<!--<br>$crazy--></font>".EF."</td></tr>";
         stop_table();
}

function get_microtime()
{
         $mtime=microtime();
         $mtime=explode(" ",$mtime);
         $mtime=doubleval($mtime[1])+doubleval($mtime[0]);
         return $mtime;
}

function start_form($op,$db_table,$method="post",$action=NULL)
{
         GLOBAL $submitted,$admin_page;
         $action = ($action) ? $action : $admin_page ;
         echo "<form method=\"$method\" action=\"$action?op=$op&db_table=$db_table&".session_id()."\" onSubmit=\"return submitCheck();\">";
}

function stop_form()
{
         echo "</form>";
}

function go_back()
{
         echo "<b><a href=\"javascript:history.go(-1)\" onMouseOver=\"self.status=document.referrer;return true\">".GOBACK."</a></b>";
}

function invoice_display($invoice_snapshot)
{
         GLOBAL $op,$details_view;
         return ($details_view) ? $invoice_snapshot : "<textarea name=invoice_snapshot rows=8 cols=40>$invoice_snapshot</textarea>" ;
}

function check_args($args)
{
         $i=0;
         foreach ($args as $value) {
           if (!${$value["column"]}&&$args[$i]["required"]==1) {
                    echo $args[$i]["title"].":oops<br>";
           }
         $i++;
         }
}

function deny_access()
{
         echo "<tr><td><center><b>".MFB.ACCESSDENIED.EF."</b></center></td></tr>";
}

function reset_password($client_id,$length=8)
{
         GLOBAL $dbh,
                $email,
                $user_login_url,
                $email_to,
                $email_subject,
                $email_body,
                $allow_html_emails;

         $args = array("1","2","3","4","5","6","7","8","9","0","q","w","e","r","t",
                         "y","u","i","o","p","a","s","d","f","g","h","j","k","l",
                         "z","x","c","v","b","n","m","Q","W","E","R","T","Y","U",
                         "I","O","P","A","S","D","F","G","H","J","K","L","Z","X",
                         "C","V","B","N","M");
         srand((float) microtime() * 1000000);
         for($i = $length; $i > 0; $i--) { $new_pass .= $args[rand(0, sizeof($args))]; }

         if (!$dbh) dbconnect();
         $sql    = "UPDATE client_info SET client_password = '".md5($new_pass)."', client_real_pass = '$new_pass' WHERE client_id = $client_id";
         $result = mysql_query($sql,$dbh);

         $allow_html_emails = FALSE;
         $email_to[]        = $client_id;
         $email_subject     = YOURLOGININFORMATION;
         $email_body        = "%%FULLNAME%%,\n\n".USERNAME.": $email\n".PASSWORD_t.": $new_pass\n".URL.": $user_login_url\n";

         return send_email($email_to,$email_cc,3,$email_subject,$email_body,$email_from);
}

function validate_cc_input($billing_cc_num,$client_id)
{
         GLOBAL $data;
         if ($billing_cc_num && !$client_id) {
           $data=$cleaned_cc_no=credit_card::clean_no ($billing_cc_num);
           list($valid,$type)=credit_card::check($cleaned_cc_no);
           if ($valid&&$type) {
               $last4=substr($cleaned_cc_no, strlen($cc_no)-4, 4);
               return $type . "-" . $last4;
           } else {
               return NULL;
           }
         } else {
           return NULL;
         }
}

function billing_cc_display($billing_cc_type,$client_id)
{
         GLOBAL $op,$do;
         if (!$billing_cc_type && !$client_id) { // New Client
            return "<input type=text name=billing_cc_type value=\"$billing_cc_type\" size=16 maxlength=16>";
         } elseif ($billing_cc_type) { // Edit or View Client
            return ($op=="view"||$op=="details"||$do=="edit") ? SFB.$billing_cc_type.EF : "<input type=text name=billing_cc_type value=\"$billing_cc_type\" size=16 maxlength=16>" ;
         } else { // Error ?!#@!
            return SFB."error".EF;
         }
}

function validate_table($db_table,$option=2)
{
         GLOBAL $locale_db_name, $dbh, $error;
         if(!$dbh)dbconnect();
         $tables=mysql_list_tables($locale_db_name);
         while (list($each_table)=mysql_fetch_array($tables)) { $all_tables[]=$each_table; }
         if(!in_array($db_table,$all_tables)) {
            switch($option) {
               case 1:
                    $error  = "<table border=0 width=100% cellspacing=1 cellpadding=1>";
                    $error .= "<tr><td align=center>".SFB."[".ERROR."] ".THETABLE1.": <b>$db_table</b> ".THETABLE3.":<b>$db</b>.".EF."</td></tr>";
                    $error .= "</table>";
                    echo $error;
               break;
               case 2:
                    $error  = 1;
                    return $error;
               break;
            }
         }
}

function encrpyt($pwd=NULL, $data=NULL, $decrypt=NULL)
{
     $data = ($decrypt) ? urldecode($data) : $data ;

     $key[] = $box[] = $temp_swap = "";
     $pwd_length = 0;
     $pwd_length = strlen($pwd);

     for ($i = 0; $i <= 255; $i++) {
          $key[$i] = ord(substr($pwd, ($i % $pwd_length), 1));
          $box[$i] = $i;
     }

     $x = 0;

     for ($i = 0; $i <= 255; $i++) {
          $x = ($x + $box[$i] + $key[$i]) % 256;
          $temp_swap = $box[$i];
          $box[$i] = $box[$x];
          $box[$x] = $temp_swap;
     }

     $temp = $k = $cipherby = $cipher = "";
     $a = $j = 0;

     for ($i = 0; $i < strlen($data); $i++) {
          $a = ($a + 1) % 256;
          $j = ($j + $box[$a]) % 256;
          $temp = $box[$a];
          $box[$a] = $box[$j];
          $box[$j] = $temp;
          $k = $box[(($box[$a] + $box[$j]) % 256)];
          $cipherby = ord(substr($data, $i, 1)) ^ $k;
          $cipher .= chr($cipherby);
     }

     return ($decrypt) ? urldecode(urlencode($cipher)) : urlencode($cipher) ;
}

function generate_paypal_link($pp_item_name,$pp_item_number,$pp_amount,$pp_link_type="button")
{
         GLOBAL $pp_url,
                $pp_business,
                $pp_return,
                $pp_cancel_return,
                $pp_item_name,
                $pp_image_url,
                $pp_item_number,
                $pp_amount,
                $pp_add,
                $pp_no_note,
                $pp_submit_button,
                $pp_undefined_quantity,
                $pp_no_shipping;

                $pp_amount = display_currency($pp_amount,1);

                $paypal_url     = "business=$pp_business&";
                $paypal_url    .= "image_url=$pp_image_url&";
                $paypal_url    .= "item_name=".urlencode($pp_item_name)."&";
                $paypal_url    .= "item_number=$pp_item_number&";
                $paypal_url    .= "amount=$pp_amount&";
                $paypal_url    .= "return=$pp_return&";
                $paypal_url    .= "cancel_return=$pp_cancel_return&";
                $paypal_url    .= "no_note=$pp_no_note";
                $paypal_button  = "<a href=# onclick=window.open('$pp_url"."$paypal_url','SecureOnlinePayment','width=600,height=400,scrollbars,location,resizable,status');>";
                $paypal_button .= "<img src=$pp_submit_button border=0>";
                $paypal_button .= "</a>";

                if ($pp_amount>0) { return ($pp_link_type=="button") ? $paypal_button : $pp_url.$paypal_url ; }
}

function generate_worldpay_link($pp_item_name,$pp_item_number,$pp_amount,$wp_link_type="button")
{
         GLOBAL $wp_url,
                $wp_testmode,
                $wp_istid,
                $wp_currency,
                $pp_amount,
                $worldpay_id;

                $pp_amount = display_currency($pp_amount,1);

                $worldpay_url  = "<form action=\"$wp_url\" name=BuyForm method=POST>";
                $worldpay_url .= "<input name=instId type=hidden value=\"$wp_istid\">";
                $worldpay_url .= "<input type=hidden name=\"cartId\" value=\"$pp_item_number\">";
                $worldpay_url .= "<input type=hidden name=\"currency\" value=\"$wp_currency\">";
                $worldpay_url .= "<input type=hidden name=\"desc\" value=\"$pp_item_name\">";
                $worldpay_url .= "<input type=hidden name=\"amount\" value=\"$pp_amount\">";
                $worldpay_url .= "<input type=hidden name=\"hideCurrency\">";
                $worldpay_url .= "<input type=hidden name=\"flag\" value=\"1\">";
                $worldpay_url .= "<input type=hidden name=\"testMode\" value=\"$wp_testmode\">";
                $worldpay_url .= "<input type=submit name=\"submit\" value=\"".PAYWITHWORLDPAY."\">";
                $worldpay_url .= "</form>";

                if ($pp_amount>0) { return $worldpay_url; }

                /*
                $worldpay_url     = "instID=$wp_istid&";
                $worldpay_url    .= "cart_id=$pp_item_number&";
                $worldpay_url    .= "amount=$pp_amount&";
                $worldpay_url    .= "currency=$wp_currency&";
                $worldpay_url    .= "desc=".urlencode($pp_item_name)."&";
                $worldpay_url    .= "testmode=$wp_testmode&";
                $worldpay_button  = "<a href=# onclick=window.open('$wp_url?"."$worldpay_url','SecureOnlinePayment','width=600,height=400,scrollbars,location,resizable,status');>";
                $worldpay_button .= "Pay with WorldPay";
                $worldpay_button .= "</a>";
                return ($wp_link_type=="button") ? $worldpay_button : $wp_url."?".$worldpay_url ;
                */
}

function log_event($client_id,$log_comments,$log_type=3)
{        //$log_types = array("1" => ADMIN, "2" => USER, "3" => SYSTEM);
         GLOBAL $dbh;
         if(!$dbh)dbconnect();
         $insert_sql = "INSERT INTO event_log (log_id, client_id, log_type, log_comments, log_stamp) VALUES (NULL, '$client_id', '$log_type', '$log_comments', '".mktime()."')";
         @mysql_query($insert_sql,$dbh);
}

function split_price($price,$type,$cycle)
{
         $price_array = explode("|",$price);
         switch($type)
         {
              case price: // PRICE
                    switch($cycle) // MM.MM|QQ.QQ|SS.SS|YY.YY
                    {
                         case 1:  $this_price = $price_array[0]; break;
                         case 3:  $this_price = $price_array[1]; break;
                         case 6:  $this_price = $price_array[2]; break;
                         case 12: $this_price = $price_array[3]; break;
                         case 24: $this_price = $price_array[4]; break;
                         default: $this_price = $price_array[0]; break;
                    }
                    return ($this_price) ? $this_price : $price_array[0] ;
              break;

              case setup: // SETUP
                    switch($cycle) // MM.MM|QQ.QQ|SS.SS|YY.YY
                    {
                         case 1:  $this_price = $price_array[0]; break;
                         case 3:  $this_price = $price_array[1]; break;
                         case 6:  $this_price = $price_array[2]; break;
                         case 12: $this_price = $price_array[3]; break;
                         case 24: $this_price = $price_array[4]; break;
                         default: $this_price = $price_array[0]; break;
                    }
                    return ($this_price) ? $this_price : $price_array[0] ;
              break;

              case domain: // DOMAIN
                    switch($cycle) // 1Y|2Y|3Y|4Y|5Y|6Y|7Y|8Y|9Y|10Y
                    {
                         case 1:  $this_price = $price_array[0]; break;
                         case 2:  $this_price = $price_array[1]; break;
                         case 3:  $this_price = $price_array[2]; break;
                         case 4:  $this_price = $price_array[3]; break;
                         case 5:  $this_price = $price_array[4]; break;
                         case 6:  $this_price = $price_array[5]; break;
                         case 7:  $this_price = $price_array[6]; break;
                         case 8:  $this_price = $price_array[7]; break;
                         case 9:  $this_price = $price_array[8]; break;
                         case 10: $this_price = $price_array[9]; break;
                         default: $this_price = $price_array[0]; break;
                    }
                    return ($this_price) ? $this_price : $price_array[0] ;
              break;

              default:
                    return $price_array[0];
              break;
         }
}

function map_domains($id,$limit=1)
{
         GLOBAL $dbh,$op;
         if(!$dbh)dbconnect();
         $list_domains=$domain_name=NULL;
         //$sql="SELECT d.domain_name FROM account_details a, domain_names d WHERE a.domain_id=d.domain_id AND a.cp_id='".$id."' ORDER BY a.details_id LIMIT 0,$limit";
         $sql="SELECT d.domain_name FROM account_details a, domain_names d WHERE a.domain_id=d.domain_id AND a.cp_id='".$id."' ORDER BY a.details_id";
         $domain_result=mysql_query($sql,$dbh);
         if (!$domain_result) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }
         $list_domains = "<br>";
         while(list($domain_name)=mysql_fetch_array($domain_result)) {
               $list_domains .= $domain_name."<br>";
               $are_domains   = TRUE;
         }
         //return ($list_domains) ? substr($list_domains,0,-1) : NONE ;
         return ($are_domains&&$op!="form") ? $list_domains : NULL ;
}

function list_domains($id)
{
         GLOBAL $dbh;
         if(!$dbh)dbconnect();
         $list_domains=$domain_name=NULL;
         $sql="SELECT d.domain_name FROM account_details a, domain_names d WHERE a.domain_id=d.domain_id AND a.cp_id='".$id."' ORDER BY a.details_id";
         $domain_result=mysql_query($sql,$dbh);
         if (!$domain_result) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }
         while(list($domain_name)=mysql_fetch_array($domain_result)) {
               $list_domains .= "<li>".$domain_name."<br>";
         }
         return ($list_domains) ? $list_domains : NULL ;
}

function is_valid_email($email)
{
         return (preg_match('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'.'@'.'([-0-9A-Z]+\.)+'.'([0-9A-Z]){2,4}$/i',trim($email)));
}

function print_graph($tempdata,$title,$table_width=350,$bar_height=15,$align="center",$header_color="#999999",$delim="|")
{

          $tempdata = ($tempdata) ? $tempdata : "Monday:10|Tuesday:10|Wednesday:10|Thursday:10|Friday:10";

          $data = explode($delim,$tempdata);
          $data_items = count($data);
          $num = 0;

          //Split each Caption-Value pair, get captions and their values
          for ($i = 0; $i < $data_items ; $i++)
          {
              $temp = explode(":",$data[$i]);
              $caption[$i] = $temp[0];
              $val[$i] = $temp[1];
          }

          $total = 0;

          //Total calculated here
          for ($i = 0; $i < $data_items ; $i++)
          {
                  $total = $total + $val[$i];
          }

          //Calculate percentage for every caption
          for ($i = 0; $i < $data_items ; $i++)
          {
             $percent[$i] = ($total) ? round(($val[$i]*100) / $total) : 0 ;
          }

          //Mark highest percentage
          $highest_percent = max($percent);

          //Main Graph Table
          //One Outer Table with Table Header
          //Inner Table contains graph
          start_table($title,$table_width,$align,$header_color);
          ?>
          <TR>
           <TD>
            <TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0>
            <?
            for ($i = 0; $i < $data_items ; $i++)
            {
                //Calculate Width of bar for this Caption
                $width = ($highest_percent) ? round(($percent[$i]*$table_width)/$highest_percent) : 0 ;
                echo "<TR VALIGN=middle gbcolor=FFFFFF>
                       <TD><nobr>".SFB."<B>$caption[$i]</B>".EF."</nobr></TD>
                       <TD VALIGN=top WIDTH=$table_width><nobr>";
                if ($percent > 0)
                {
                    echo "<img src=\"images/leftbar.gif\"  height=$bar_height width=7 Alt=\"$percent[$i] %\">";
                    echo "<img src=\"images/mainbar.gif\"  height=$bar_height width=$width Alt=\"$percent[$i] %\">";
                    echo "<img src=\"images/rightbar.gif\" height=$bar_height width=7 Alt=\"$percent[$i] %\">";
                }
                else
                {
                    echo "<img src=\"images/leftbar.gif\"  height=$bar_height width=7 Alt=\"$percent[$i] %\">";
                    echo "<img src=\"images/mainbar.gif\"  height=$bar_height width=3 Alt=\"$percent[$i] %\">";
                    echo "<img src=\"images/rightbar.gif\" height=$bar_height width=7 Alt=\"$percent[$i] %\">";
                }
                echo SFB." ".$val[$i]."=$percent[$i]%".EF."</nobr></TD></TR>";
            }
            $average = $total/$i;
            ?>
            </TABLE>
           </TD>
          </TR>
          <TR>
           <TD ALIGN=center>
            <B><?=SFB?><?=TOTAL?>: <?=$total?> | <?=AVERAGE?>: <?=number_format($average,2,'.','')?><?=EF?></B>
           </TD>
          </TR>
          <?
          stop_table();
}

function mysql_one_data($query)
{
         GLOBAL $dbh; if(!$dbh)dbconnect();
         $r = mysql_fetch_row(mysql_query($query));
         return $r[0];
}
function mysql_one_array($query)
{
         GLOBAL $dbh; if(!$dbh)dbconnect();
         return mysql_fetch_array(mysql_query($query));
}

function register_insert($client_id,$reg_desc,$invoice_id,$reg_bill=NULL,$reg_payment=NULL,$reg_tracker=NULL)
{
         GLOBAL $dbh;
         $insert_sql = "INSERT INTO client_register (reg_id,
                                                     client_id,
                                                     reg_date,
                                                     reg_desc,
                                                     invoice_id,
                                                     reg_bill,
                                                     reg_payment,
                                                     reg_tracker) VALUES (NULL,
                                                                          '$client_id',
                                                                          '".mktime()."',
                                                                          '$reg_desc',
                                                                          '$invoice_id',
                                                                          '$reg_bill',
                                                                          '$reg_payment',
                                                                          '$reg_tracker')";
         if(!$dbh)dbconnect();
         $result = mysql_query($insert_sql,$dbh);
}

function display_account_register($db_table,$where,$order,$sort,$offset,$limit,$selectlimit=15)
{
         GLOBAL $page,
                $db_table,
                $date_format,
                $dbh,
                $op,
                $details_view,
                $tile,
                $debug,
                $sort,
                $client_id,
                $invoice_id,
                $invoice_page,
                $id,
                $suppress_add,
                $this_user;
         ?>
         <?=start_box(ACCOUNTREGISTER)?>
         <table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
         <tr><td bgcolor=DDDDDD>
         <?
         $details_view = 1;
         $select_sql  = "SELECT * FROM client_register ";
         $limit       = $selectlimit;
         $order       = (!$order&&$select_order) ? $select_order : $order ;
         $sort        = (!$sort) ? "ASC" : $sort ;
         $select_sql .= ($where&&!$search) ? " ".str_replace("\\",NULL,stripslashes(urldecode($where)))." " : NULL ; // WHERE is passed in, not via SEARCH
         $select_sql .= ($order) ? "ORDER BY $order $sort " : "" ;
         $this_num_results = mysql_num_rows(mysql_query($select_sql,$dbh));
         $offset      = ($offset=="") ? 0 : $offset ;
         $select_sql .= (!$recursive||$selectlimit) ? "LIMIT $offset,$limit" : NULL ;
         $this_sort   = $sort;

         if ($debug) echo $select_sql;

         $result = mysql_query($select_sql,$dbh);

         $sort     = ($sort=="ASC") ? "DESC" : "ASC" ;
         ?>
         <table border=0 cellpadding=1 cellspacing=1 width=100% align=center>
               <tr><td align=center><? if ($order == "reg_date") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=reg_date&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&id=$id&where=".stripslashes(urlencode($where))."\">".DATE."</a>".EF?></b></td>
                   <td align=center><? if ($order == "client_id") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=client_id&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&id=$id&where=".stripslashes(urlencode($where))."\">".CLIENT."</a>".EF?></b></td>
                   <td align=center><? if ($order == "reg_desc") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=reg_desc&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&id=$id&where=".stripslashes(urlencode($where))."\">".DESCRIPTION."</a>".EF?></b></td>
                   <td align=center><? if ($order == "invoice_id") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=invoice_id&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&id=$id&where=".stripslashes(urlencode($where))."\">".INVOICE."</a>".EF?></b></td>
                   <td align=center><? if ($order == "reg_bill") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=reg_bill&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&id=$id&where=".stripslashes(urlencode($where))."\">".DEBIT." (-)"."</a>".EF?></b></td>
                   <td align=center><? if ($order == "reg_payment") { echo ($this_sort=="ASC") ? ASC_IMG : DESC_IMG; } ?>
                                    <b><?=SFB."<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=reg_payment&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "tile=$tile&print=$print&id=$id&where=".stripslashes(urlencode($where))."\">".CREDIT." (+)"."</a>".EF?></b></td>
                   <td align=center><b><?=SFB.TOTAL.EF?></b></td>
                   <? if (!$this_user) { ?> <td align=center><b><?=SFB.ACTION.EF?></b></td> <? } ?>
               </tr>
         <?
         while($this_record=mysql_fetch_array($result))
         {
               ?>
               <tr><td bgcolor=FFFFFF>&nbsp;<?=SFB.stamp_to_date($this_record[reg_date]).EF?>&nbsp;</td>
                   <td bgcolor=FFFFFF>&nbsp;<?=SFB.client_select_box($this_record[client_id]).EF?>&nbsp;</td>
                   <td bgcolor=FFFFFF>&nbsp;<?=SFB.$this_record[reg_desc].EF?>&nbsp;</td>
                   <td bgcolor=FFFFFF align=right>&nbsp;<?=SFB?><a href=<?=$page?>?op=client_invoice&db_table=client_invoice&tile=client&print=&id=invoice_id|<?=$this_record[invoice_id]?>><?=$this_record[invoice_id]?></a><?=EF?>&nbsp;</td>
                   <td bgcolor=FFFFFF align=right>&nbsp;<?=SFB.display_currency($this_record[reg_bill]).EF?>&nbsp;</td>
                   <td bgcolor=FFFFFF align=right>&nbsp;<?=SFB.display_currency($this_record[reg_payment]).EF?>&nbsp;</td>
                   <? $total = $total + $this_record[reg_payment] - $this_record[reg_bill]; ?>
                   <? $color = ($total < 0) ? "RED" : "GREEN" ; ?>
                   <td bgcolor=FFFFFF align=right><nobr>&nbsp;<font color=<?=$color?>><?=SFB.display_currency($total).EF?></font>&nbsp;</nobr></td>
                   <? if (!$this_user) { ?>
                       <td bgcolor=FFFFFF align=center>&nbsp;<?=SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&from=client_register&id=reg_id|$this_record[reg_id]\">".EDIT_IMG."</a>".EF?>&nbsp;
                       <?=SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&from=client_register&id=reg_id|$this_record[reg_id]\">".DELETE_IMG."</a>".EF?>&nbsp;</td>
                   <? } ?>
               </tr>
               <?
         }
         $details_view = 0;
         $sort = $this_sort;
         ?>
               <tr><td bgcolor=FFFFFF colspan=<?=($this_user)?8:9;?> align=center><?=PieceNavigation($db_table,$limit,$where)?><br></td></tr>
         <? if ($suppress_add||$this_user) { ?>
         <? } elseif ($invoice_page) { ?>
               <tr><td bgcolor=FFFFFF colspan=<?=($this_user)?8:9;?> align=center>
                       <br>
                       <?=MFB?><a href=<?=$page?>?op=menu&tile=billing&id=<?=$invoice_id?>><?=APPLYPAYMENT?></a><?=EF?>
                       <br>
                   </td></tr>
         <? } else { ?>
               <tr><td align=center><b><?=SFB.DATE.EF?></b></td>
                   <td align=center><b><?=SFB.CLIENT.EF?></b></td>
                   <td align=center><b><?=SFB.DESCRIPTION.EF?></b></td>
                   <td align=center><b><?=SFB.INVOICE.EF?></b></td>
                   <td align=center><b><?=SFB.DEBIT." (-)".EF?></b></td>
                   <td align=center><b><?=SFB.CREDIT." (+)".EF?></b></td>
                   <td align=center colspan=2><b><?=SFB.ACTION.EF?></b></td>
               </tr>
               <form method=post action=<?$page?>?op=form_response>
               <input type=hidden name=db_table value=client_register>
               <input type=hidden name=from value=client_register>
               <tr><td bgcolor=FFFFFF>&nbsp;<input type=text name=reg_date value="<?=date($date_format)?>" size=12 maxlength=255>&nbsp;</td>
                   <td bgcolor=FFFFFF>&nbsp;<?=client_select_box($client_id)?>&nbsp;</td>
                   <td bgcolor=FFFFFF>&nbsp;<input type=text name=reg_desc value="" size=25 maxlength=255>&nbsp;</td>
                   <td bgcolor=FFFFFF>&nbsp;<input type=text name=invoice_id value="<?=$invoice_id?>" size=5 maxlength=255>&nbsp;</td>
                   <td bgcolor=FFFFFF>&nbsp;<input type=text name=reg_bill value="" size=15 maxlength=255>&nbsp;</td>
                   <td bgcolor=FFFFFF>&nbsp;<input type=text name=reg_payment value="" size=15 maxlength=255>&nbsp;</td>
                   <td bgcolor=FFFFFF align=center colspan=2>&nbsp;<?=SUBMIT_IMG?>&nbsp;</td>
               </tr>
               </form>
         <? } ?>
         </table>
         </td></tr>
         </table>
         <?=stop_box()?>
         <?
}

function edit_delete($pack_id)
{
         GLOBAL $page,$db_table,$tile;
         echo SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&from=client_package&id=cp_id|$pack_id\">".EDIT_IMG."</a>".EF;
         echo "&nbsp;";
         echo SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&from=client_package&id=cp_id|$pack_id\">".DELETE_IMG."</a>".EF;
}

function custom_error_handler($errNumber,$errDescript,$errFile,$errLine,$HTTP_SERVER_VARS)
{
         GLOBAL $errors_to;

         $die = NULL;
         switch ($errNumber) {
                  case 1:    $die = 1; break; // E_ERROR
                  case 2:    $die = 1; break; // E_WARNING
                  case 4:    $die = 1; break; // E_PARSE
                  case 8:    $die = 0; break; // E_NOTICE
                  case 16:   $die = 1; break; // E_CORE_ERROR
                  case 32:   $die = 1; break; // E_CORE_WARNING
                  case 64:   $die = 1; break; // E_COMPILE_ERROR
                  case 128:  $die = 1; break; // E_COMPILE_WARNING
                  case 256:  $die = 1; break; // E_USER_ERROR
                  case 512:  $die = 1; break; // E_USER_WARNING
                  case 1024: $die = 1; break; // E_USER_NOTICE
                  default:   $die = 0; break;
         }

         $error_string  = "<pre>";
         $error_string .= "Oops, you broke it. :( The Admin has been notified.\n\n";
         $error_string .= "Error Number..$errNumber\n";
         $error_string .= "Error Desc....$errDescript\n";
         $error_string .= "Error File....$errFile\n";
         $error_string .= "Error Line....$errLine\n\n";
         $error_string .= "Error URL.....".$HTTP_SERVER_VARS["HTTP_HOST"].$HTTP_SERVER_VARS["REQUEST_URI"]."\n";
         $error_string .= "Remote Addr...".$HTTP_SERVER_VARS["REMOTE_ADDR"]."\n";
         $error_string .= "User Agent....".$HTTP_SERVER_VARS["HTTP_USER_AGENT"]."\n";
         $error_string .= "TimeStamp.....".date("Y-m-d: h:i:s")."\n\n";
         $error_string .= "</pre>";

         $errors_to = ($errors_to) ? $errors_to : "v3-errors@your-server.com";
         $headers .= "From: ModernBill <$errors_to>\n";
         $headers .= "X-Sender: <$errors_to>\n";
         $headers .= "X-Mailer: PHP\n";
         $headers .= "X-Priority: 1\n";
         $headers .= "Return-Path: <$errors_to>\n";

         //Uncomment this to send html format
         //$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
         //$headers .= "cc: $errors_to\n";
         //$headers .= "bcc: $errors_to";

         if ($die) {
             error_log(strip_tags($error_string),1,$errors_to,$headers);
             die();
         }
}

function index_view() {
  GLOBAL $this_page,$faq_name;
  echo "<b><a href=$this_page>".LFH.$faq_name.EF."</a></b>".LFH." >> ".PLEASESELECTCAT.EF."<br><br>";
  select_all_categories();
}

function faq_view($cid,$fid,$query) {
  GLOBAL $this_page,$faq_name,$empty;
  if ($fid)
  {
     select_search_faqs($cid,$show,$fid,$query);
  }
  else
  {
     select_all_faqs($cid,"q",$fid,$query);
     if (!$empty) echo "<ol>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</ol>";
     if (!$empty) select_all_faqs($cid,"a",$fid,$query);
  }
}

function select_all_categories() {
  GLOBAL $this_page,$faq_name;
  dbconnect();
  $result = mysql_query("SELECT * FROM faq_categories ORDER BY cname");
  $num = mysql_num_rows($result);
  $split = $num / 2;
  $left = ceil($split);
  $numq=0;
  echo MFB."<ol>";
  while(list($cid,$cname,$numq) = mysql_fetch_row($result)) {
    list($numq) = mysql_fetch_row(mysql_query("SELECT count(fid) FROM faq_questions WHERE cid=$cid"));
    echo "<li> <A href=$this_page&faq_op=faq&cid=$cid>".MFB.$cname.EF."</A> ".TFB."[$numq]".EF;
    $numq=0;
  }
  echo "</ol>".EF;
}

function search_box() {
  GLOBAL $this_page,$faq_name,$PHP_SELF;
  echo "<form method=post action=\"$this_page&faq_op=search\" name=search>";
  echo "<input type=text name=query size=14 maxsize=50>&nbsp;<input type=submit name=submit value=".SEARCH.">";
  echo "</form>";
}

function select_search_faqs($cid,$show,$fid,$query)
{
  GLOBAL $this_page,$faq_name,$search_color,$query;
  dbconnect();
  $num=1;
  $result = mysql_query("SELECT * FROM faq_questions q, faq_categories c WHERE q.cid=c.cid AND q.cid='$cid' AND fid='$fid' ORDER BY q.question");
  while(list($fid,$cid,$question,$answer,$timestamp,$cid,$cname) = mysql_fetch_row($result))
  {
    if ($cname!=$current)
    {
        $current=$cname;
        echo "<b><a href=$this_page>".LFH.$faq_name.EF."</a></b>".LFH." >> ".search_replace($cname,$query).":".EF."<br><br>";
    }
    echo "<ol>";
    echo "<li> <A name=$fid><b>".MFB.search_replace($question,$query).EF."</b></A>&nbsp;".SFB."[<a href=\"javascript:history.back(-1)\">".GOBACK."</a>]".EF."<br>";
    echo "</ol>";
    echo "<blockquote>".MFB.nl2br(search_replace($answer,$query)).EF."<br><br>";
    echo SFB."<i>".TIMESTAMP.": ".stamp_to_date($timestamp)."</i>".EF."</blockquote>";
  }
}

function search_view($query)
{
  GLOBAL $this_page,$faq_name,$query;
  dbconnect();
  $n = 1;
  $result = mysql_query("SELECT * FROM faq_questions q, faq_categories c WHERE q.cid=c.cid AND ( q.question LIKE '%$query%' OR q.answer LIKE '%$query%' ) ORDER BY c.cname");
  $num    = mysql_num_rows($result);
  if ($num == 0)
  {
      echo MFB.NOMATCHESFOUND.": \"<b>$query</b>\". [<a href=\"javascript:history.back(-1)\">".GOBACK."</a>]".EF;
      return;
  }
  echo "<b><a href=$this_page>".LFH.$faq_name.EF."</a></b>".LFH." >> $num ".MATCHESFOUND." $query:".EF."<br><br>";
  while(list($fid,$cid,$question,$answer,$timestamp,$cid,$cname) = mysql_fetch_array($result))
  {
    if ($cname!=$current)
    {
        $n=1;
        $current=$cname;
        echo "</ol>";
        if ($open) echo "<ol>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</ol>";
        $open=0;
        echo LFH."<b>".search_replace($cname,$query).":</b>".EF;
        echo "<ol>"; $open=1;
    }
    echo "<li> <A href='$this_page&faq_op=faq&cid=$cid&fid=$fid&query=$query'>".MFB.search_replace($question,$query).EF."</A><br>";
    $n++;
  }
  if ($open) echo "</ol>";
}

function search_replace($string,$query)
{
  GLOBAL $search_color,$query;
  $search_color = ($search_color) ? $search_color : "RED";
  $string = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])","<a href=\"\\1://\\2\\3\" target=\"$target\">\\1://\\2\\3</a>",$string);
  $string = eregi_replace("(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))","<a href=\"mailto:\\1\">\\1</a>", $string);
  $string = str_replace("<i>",SFB."<i>",$string);
  $string = str_replace("</i>","</i>".EF,$string);
  return ($query!="") ? eregi_replace($query,"<font color=$search_color><font size=+1><b>$query</b></font></font>",$string) : $string ;
}

function select_all_faqs($cid,$show,$query) {
  GLOBAL $this_page,$faq_name,$empty,$PHP_SELF,$faq_op,$query;
  dbconnect();
  $result = mysql_query("SELECT * FROM faq_questions q, faq_categories c WHERE q.cid=c.cid AND q.cid='$cid' ORDER BY q.question");
  $num = mysql_num_rows($result);
  if ($num == 0) { echo MFB.NOMATCHESFOUND." [<a href=\"javascript:history.back(-1)\">".GOBACK."</a>]".EF; $empty=1; return; }
  if ($show == "a") echo "<ol>";
  while(list($fid,$cid,$question,$answer,$timestamp,$cid,$cname) = mysql_fetch_row($result)) {
    if ($show == "q")
    {
        if ($cname!=$current)
        {
            $current=$cname;
            echo "<b><a href=$this_page>".LFH.$faq_name.EF."</a></b>".LFH." >> $cname:".EF."<br><br>";
            echo "<ol>";
        }
        echo "<li> <A href='$this_page&faq_op=$faq_op&&cid=$cid#$fid'>".MFB.search_replace($question,$query).EF."</A><br>";
    }
    elseif ($show == "a")
    {
        echo "<li> <b><A name=$fid>".MFB.$question.EF."</A></b>&nbsp;".SFB."[<A href=#top>top</A>]".EF."<br>";
        echo "<blockquote>".MFB.nl2br(search_replace($answer,$query)).EF."<br><br>";
        echo TFB."<i>".TIMESTAMP.": ".stamp_to_date($timestamp)."</i>".EF."</blockquote>";
        echo "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br><br>";
    }
  }
  echo "</ol>";
}

class LG_Target {
  var $target;
  var $ip;
  var $fqdn;

  function LG_Target($tg) {
    $this->target=$tg;
    $this->get_ip();
    $this->get_fqdn();
    if ( !$this->is_ip() ) $this->get_ip();
    if ( !$this->is_fqdn() ) $this->get_fqdn();
  }

  function is_ip() {
    return ( !empty($this->ip) );
  }

  function is_fqdn() {
    return ( !empty($this->fqdn) );
  }

  function get_ip() {
    if ( ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$",$this->target) ) {
      $this->ip=$this->target;
    } else {
      if ( !$this->is_fqdn() ) $t=$this->target;
      else $t=$this->fqdn;
      $r=gethostbyname($t);
      if ( $t!=$r ) $this->ip=$r;
    }
  }

  function get_fqdn() {
    if ( !$this->is_ip() ) $t=$this->target;
    else $t=$this->ip;
    $r=@gethostbyaddr($t);
    if ( $t!=$r ) $this->fqdn=$r;
  }

  function is_rfc1918() {
    return ereg("^(192\.168|10|^172\.(1[6-9]|2[0-9]|3[0-2]))\.",$this->ip);
  }

  function go_ip($com) {
    if ( $this->is_rfc1918() ) {
      echo "<h4><font color=\"red\">Sorry, can't do, target: $this->target is RFC1918 space.</font></h4><br>\n";
    } elseif ( !$this->is_ip() ) {
        echo "<h4><font color=\"red\">Sorry, can't do, target: $this->target unresolvable.</font></h4><br>\n";
    } else {
      echo "<hr><h4>Target: $this->target, IP: $this->ip, FQDN: $this->fqdn<br>\n";
      $this->go("$com $this->target");
    }

  }

  function go_msc($com) {
    echo "<hr><h4>Target: $this->target<br>\n";
    $this->go("$com $this->target");
  }

  function go($com) {
    echo COMMAND.": $com</h4><hr>";
    echo "<pre>\n";
    system("$com 2>&1");
    echo "</pre><hr>\n";
  }

  function show() {
    if ( $this->is_rfc1918() ) {
      echo "<h4><font color=\"red\">Sorry, can't do, target: $this->target is RFC1918 space.</font></h4><br>\n";
    } else {
      echo "<hr><table border=\"0\" width=\"50%\"><tr><td>Target:</td><td>$this->target</td></tr>\n<tr><td>IP:</td><td>";
      if ( $this->is_ip() ) echo "$this->ip";
      else echo "Non Resolvable";
      echo "</td></tr>\n<tr><td>FQDN:</td><td>";
      if ( $this->is_fqdn() ) echo "$this->fqdn";
      else echo "Non Resolvable";
      echo "</td></tr></table>\n<hr>";
    }
  }
}
$whois_servers = array(
                 "ac"  => "whois.nic.ac",
                 "al"  => "whois.ripe.net",
                 "am"  => "whois.amnic.net",
                 "as"  => "whois.nic.as",
                 "at"  => "whois.nic.at",
                 "au"  => "whois.aunic.net",
                 "az"  => "whois.ripe.net",
                 "ba"  => "whois.ripe.net",
                 "be"  => "whois.dns.be",
                 "biz" => "whois.neulevel.biz",
                 "bg"  => "whois.ripe.net",
                 "br"  => "whois.registro.br",
                 "by"  => "whois.ripe.net",
                 "ca"  => "whois.cira.ca",
                 "cc"  => "whois.nic.cc",
                 "ch"  => "whois.nic.ch",
                 "ck"  => "whois.ck-nic.org.ck",
                 "cn"  => "whois.cnnic.net.cn",
                 "co.uk" => "nominet.org.uk",
                 "com" => "whois.nsiregistry.net",
                 "cx"  => "whois.nic.cx",
                 "cy"  => "whois.ripe.net",
                 "cz"  => "whois.nic.cz",
                 "de"  => "whois.denic.de",
                 "dk"  => "whois.dk-hostmaster.dk",
                 "dz"  => "whois.ripe.net",
                 "edu" => "rs.internic.net",
                 "ee"  => "whois.ripe.net",
                 "eg"  => "whois.ripe.net",
                 "es"  => "whois.ripe.net",
                 "fi"  => "whois.ripe.net",
                 "fj"  => "whois.usp.ac.fj",
                 "fo"  => "whois.ripe.net",
                 "fr"  => "whois.nic.fr",
                 "gb"  => "whois.ripe.net",
                 "gb.com" => "whois.nomination.net",
                 "gb.net" => "whois.nomination.net",
                 "ge"  => "whois.ripe.net",
                 "gov" => "whois.nic.gov",
                 "gr"  => "whois.ripe.net",
                 "gs"  => "whois.adamsnames.tc",
                 "hk"  => "whois.hknic.net.hk",
                 "hm"  => "whois.registry.hm",
                 "hr"  => "whois.ripe.net",
                 "hu"  => "whois.ripe.net",
                 "id"  => "whois.idnic.net.id",
                 "ie"  => "whois.domainregistry.ie",
                 "info" => "whois.afilias.info",
                 "int" => "whois.isi.edu",
                 "il"  => "whois.ripe.net",
                 "is"  => "whois.isnet.is",
                 "it"  => "whois.nic.it",
                 "jp"  => "whois.nic.ad.jp",
                 "ke"  => "whois.rg.net",
                 "kg"  => "whois.domain.kg",
                 "kr"  => "whois.nic.or.kr",
                 "kz"  => "whois.domain.kz",
                 "li"  => "whois.nic.li",
                 "lk"  => "whois.nic.lk",
                 "lt"  => "whois.ripe.net",
                 "lu"  => "whois.ripe.net",
                 "lv"  => "whois.ripe.net",
                 "ma"  => "whois.ripe.net",
                 "md"  => "whois.ripe.net",
                 "mil" => "whois.nic.mil",
                 "mk"  => "whois.ripe.net",
                 "mm"  => "whois.nic.mm",
                 "ms"  => "whois.adamsnames.tc",
                 "mt"  => "whois.ripe.net",
                 "mx"  => "whois.nic.mx",
                 "net" => "whois.nsiregistry.net",
                 "net.au" => "whois.net.au",
                 "nl"  => "whois.domain-registry.nl",
                 "no"  => "whois.norid.no",
                 "no.com" => "whois.nomination.net",
                 "nu"  => "whois.nic.nu", "nunames",
                 "nz"  => "whois.domainz.net.nz",
                 "org" => "whois.nsiregistry.net",
                 "pl"  => "whois.ripe.net",
                 "pk"  => "whois.pknic.net.pk",
                 "pt"  => "whois.ripe.net",
                 "ro"  => "whois.ripe.net",
                 "ru"  => "whois.ripn.ru",
                 "se"  => "whois.nic-se.se",
                 "se.com" => "whois.nomination.net",
                 "se.net" => "whois.nomination.net",
                 "sg"  => "whois.nic.net.sg",
                 "si"  => "whois.ripe.net",
                 "sh"  => "whois.nic.sh",
                 "sk"  => "whois.ripe.net",
                 "sm"  => "whois.ripe.net",
                 "st"  => "whois.nic.st",
                 "su"  => "whois.ripe.net",
                 "tc"  => "whois.adamsnames.tc",
                 "tf"  => "whois.adamsnames.tc",
                 "tj"  => "whois.nic.tj",
                 "th"  => "whois.thnic.net",
                 "tm"  => "whois.nic.tm",
                 "tn"  => "whois.ripe.net",
                 "to"  => "whois.tonic.to",
                 "tr"  => "whois.ripe.net",
                 "tw"  => "whois.twnic.net",
                 "ua"  => "whois.ripe.net",
                 "uk"  => "whois.nic.uk",
                 "uk.net" => "whois.nomination.net",
                 "uk.com" => "whois.nomination.net",
                 "us"  => "whois.isi.edu",
                 "va"  => "whois.ripe.net",
                 "vg"  => "whois.adamsnames.tc",
                 "ws"  => "whois.nic.ws",
                 "yu"  => "whois.ripe.net",
                 "za"  => "whois.frd.ac.za");

function basic_whois($domain,$ext)
{
        GLOBAL $whois_servers;

        $server = ($whois_servers[$ext]!="") ? $whois_servers[$ext] : "whois.internic.net" ;
        $fp = fsockopen ($server,43,$errnr,$errstr,20) or die("$errno: $errstr");

        fputs($fp, "$domain\n");
        $whois_buffer = MFB."<b>[$domain = $server]</b>".EF;
        while (!feof($fp)) {
             $whois_buffer .= "<pre>".fgets($fp,2048)."</pre>";
        }
        fclose($fp);

        return $whois_buffer;
}

function arinwhois($domain)
{
        $fcontents = file ("http://www.arin.net/cgi-bin/whois.pl?queryinput=$domain");
        while (list ($line_num, $line) = each ($fcontents)) {
               $whois_buffer .= "<pre>$line</pre>";
        }
        return strip_tags($whois_buffer);
}

function hostdrilldown($domain)
{
         $ip = gethostbyname($domain);
         list($q1,$q2,$q3,$q4)=explode('.',$ip);
         for ($i=0;$i<256;$i++)
         {
             $new_ip = "$q1.$q2.$q3.$i";
             $host = gethostbyaddr($new_ip);
             if ($ip == $new_ip) {
               echo "<li> <font size=+1 color=RED><b>$new_ip => <a href=http://$host target=_blank>$host</a></b></font>";
             } else {
               echo "<li> $new_ip => <a href=http://$host target=_blank>$host</a>";
             }
         }
}

function minimum_version($vercheck)
{
         $minver = explode(".", $vercheck);
         $curver = explode(".", phpversion());

         return (($curver[0] < $minver[0]) ||
                (($curver[0] = $minver[0]) && ($curver[1] < $minver[1])) ||
                (($curver[0] = $minver[0]) && ($curver[1] = $minver[1]) && ($curver[2][0] < $minver[2][0]))) ? FALSE : TRUE;
}

function custom_nl2br($text)
{
         GLOBAL $enable_nl2br;
         return ($enable_nl2br) ? nl2br($text) : $text ;
}

function credit_affiliate($cp_id,$cp_start_stamp,$aff_code,$full_pay)
{
         GLOBAL $dbh, $z, $d, $date_format, $is_domain, $debug;

         if ($debug) echo "[credit_affiliate($cp_id,$cp_start_stamp,$aff_code,$full_pay)]<br>";

         if (!$aff_code) return;

         if(!$dbh) dbconnect();

         $sql     = "SELECT * FROM affiliate_config WHERE aff_code = '$aff_code' AND aff_status = 2";
         if ($debug) echo "$sql<Br>";
         $result  = mysql_query($sql,$dbh);
         $this_cp = mysql_fetch_assoc($result);

         $aff_stamp = mktime(0,0,0,date("m")+$z,date("d")+$d-$this_cp[aff_pay_time],date("Y"));

         if ($debug) {
                  echo "<pre>";
                  echo $cp_start_stamp."<=".$aff_stamp."<br>";
                  print_r($this_cp);
                  echo "</pre>";
         }

         if ($cp_start_stamp <= $aff_stamp) {

            // Calculate Amount for Affiliate
            switch ($this_cp[aff_pay_type]) {
                    case 1: $credit_amount = $full_pay * $this_cp[aff_pay_amount]; break; // Percentage
                    case 2: $credit_amount = $this_cp[aff_pay_amount]; break; // Flat Fee
                    default: return; break; // Nothing, return
            }

            // Prepare Credit for Affiliate
            $credit_comments = "Affiliate Commission for [CP:$cp_id Code:".$this_cp[aff_code]."] ~ ".date("$date_format: h:i:s");
            $insert_sql = "INSERT INTO client_credit (credit_id,
                                                      client_id,
                                                      credit_amount,
                                                      credit_comments,
                                                      credit_stamp) VALUES (NULL,
                                                                           '$this_cp[client_id]',
                                                                           '$credit_amount',
                                                                           '$credit_comments',
                                                                           '".mktime()."')";
            if ($debug) echo "$insert_sql<br>";
            if (!mysql_query($insert_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }

            $update_sql = "UPDATE affiliate_config SET aff_pay_sum=aff_pay_sum+$credit_amount WHERE aff_code = '$aff_code'";
            if (!mysql_query($update_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
            if ($debug) echo "$update_sql<br>";

            $update_sql = "UPDATE client_package SET aff_last_paid='".mktime()."' WHERE cp_id = '$cp_id'";
            if (!mysql_query($update_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
            if ($debug) echo "$update_sql<br>";
         }
}
?>