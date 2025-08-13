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

# --------------- #
#  "B" Functions  #
# --------------- #
function banned_email_check($email)
{
         GLOBAL $dbh,
                $tablebgcolor,
                $default_ban_message;

         if(!$dbh)dbconnect();
         $sql = "SELECT ban_id,ban_string,ban_message FROM banned_config WHERE ban_type = 2 AND ban_status = 2";
         $result = mysql_query($sql,$dbh);

         $email_parts = explode("@",$email);

         while(list($ban_id,$ban_email,$ban_message) = mysql_fetch_row($result))
         {
              if (strtolower($email_parts[1]) == strtolower($ban_email))
              {
                      mysql_query("UPDATE banned_config SET ban_count=ban_count+1, ban_last_stamp='".mktime()."' WHERE ban_id='$ban_id'",$dbh);
                      return ($ban_message) ? $ban_message : $default_ban_message ;
              }
         }
}

function banned_ip_check($REMOTE_ADDR)
{
         GLOBAL $dbh,
                $REMOTE_ADDR,
                $tablebgcolor;

         if(!$dbh)dbconnect();
         $sql = "SELECT ban_id,ban_string,ban_message FROM banned_config WHERE ban_type = 1 AND ban_status = 2";
         $result = mysql_query($sql,$dbh);
         list($a,$b,$c,$d) = explode(".",$REMOTE_ADDR);
         while(list($ban_id,$ipdata,$ban_message) = mysql_fetch_row($result))
         {
              $aclass=$bclass=$cclass=$dclass=$security_level=$count=0;

              $classes = explode(".",$ipdata);

              if (ereg("\*",$classes[3])) $count++;
              if (ereg("\*",$classes[2])) $count++;
              if (ereg("\*",$classes[1])) $count++;
              if (ereg("\*",$classes[0])) $count++;

              $security_level = $count;

              if(!ereg("\*",$classes[0]) && $a == $classes[0]) $aclass = TRUE;
                 if(!ereg("\*",$classes[1]) && $b == $classes[1]) $bclass = TRUE;
                    if(!ereg("\*",$classes[2]) && $c == $classes[2]) $cclass = TRUE;
                       if(!ereg("\*",$classes[3]) && $d == $classes[3]) $dclass = TRUE;

              if (validate_ip($security_level,$aclass,$bclass,$cclass,$dclass))
              {
                      mysql_query("UPDATE banned_config SET ban_count=ban_count+1, ban_last_stamp='".mktime()."' WHERE ban_id='$ban_id'",$dbh);
                      vortech_HTML_start();
                      ?>
                      <?=vortech_TABLE_start(NULL)?>
                      <table cellpadding=3 cellspacing=1 border=0 width=100%>
                      <tr><td bgcolor=<?=$tablebgcolor?> align=center><?=$ban_message?></td></tr>
                      </table>
                      <?=vortech_TABLE_stop()?>
                      <br>
                      <?
                      vortech_HTML_stop();
                      exit;
              }
         }
}

# --------------- #
#  "D" Functions  #
# --------------- #
function display_cart()
{
         display_cart_no_output();
}


function display_cart_no_output()
{
        GLOBAL $cart,
               $tablebgcolor,
               $allow_pro_rate_billing,
               $child_package,
               $prorate_threshhold,
               $debug,
               $details_view,
               $outerborder,
               $headercolor,
               $headertextcolor,
               $tax_amount,
               $tax_number,
               $tax_enabled,
               $this_get_price,
               $date_format;

        $cart_html  = "<table cellpadding=0 cellspacing=0 border=0 bgcolor=$outerborder align=center width=100%>";
        $cart_html .= "<tr><td bgcolor=$headercolor height=15 align=center>".SFB."<font color=$headertextcolor><b>".YOURCUSTOMORDER."</b></font>".EF."</td></tr> ";
        $cart_html .= "<tr><td>";
        $cart_html .= "<table cellpadding=3 cellspacing=1 border=0  width=100%>";

        foreach($cart[domains] as $key => $value)
        {
            list($register,$domain,$tld_extension,$domain_years,$domain_price) = $value; // ??
            $cart_html .= "<tr>";
            $cart_html .= "<td bgcolor=$tablebgcolor width=35%>".SFB."<b>$key</b>".EF."</td>";
            $cart_html .= "<td bgcolor=$tablebgcolor align=right><nobr>".SFB;
            $details_view = TRUE;
            $cart_html .= tld_price_select_box($register,$domain,$tld_extension,$domain_years,$domain_price);
            $details_view = FALSE;
            $cart_html .= " +".EF."</nobr></td></tr> ";
            $total_domain += $domain_price;
        }
        foreach($cart[packages] as $key => $value)
        {
            list($pack_id,$pack_plan) = $value;
            $set_main_pack_plan = $pack_plan;
            $this_get_price = get_price($pack_id,$pack_plan);
            $total_package_price += $this_get_price[price];
            $total_package_setup += $this_get_price[setup];
            $cart_html .= "<tr>";
            $cart_html .= "<td bgcolor=$tablebgcolor width=35%><nobr>".SFB."<b>".MAINPACKAGE."</b>".EF."</nobr></td>";
            $cart_html .= "<td bgcolor=$tablebgcolor align=right><nobr>".SFB;
            $details_view = TRUE;
            $cart_html .= vortech_package_select_menu($pack_display,$pack_id,$pack_plan);
            $details_view = FALSE;
            $cart_html .= " +".EF."</nobr></td></tr> ";
        }
        foreach($cart[addons] as $key => $value)
        {
            list($pack_id,$pack_plan) = $value;
            if ($value)
            {
                $this_get_price = get_price($pack_id,$pack_plan);
                $total_package_price += $this_get_price[price];
                $total_package_setup += $this_get_price[setup];
                $cart_html .= "<tr>";
                $cart_html .= "<td bgcolor=$tablebgcolor width=35%>".SFB."<b>".ADDONS."</b>".EF."</td>";
                $cart_html .= "<td bgcolor=$tablebgcolor align=right><nobr>".SFB;
                $details_view = $child_package = TRUE;
                $cart_html .= vortech_package_select_menu($pack_display,$pack_id,$pack_plan);
                $details_view = $child_package = FALSE;
                $cart_html .= " +".EF."</nobr></td></tr> ";
            }
        }

        // NEW PRORATE LOGIC
        //
        // date("t") t - number of days in the given month; i.e. "28" to "31"
        // date("j") j - day of the month without leading zeros; i.e. "1" to "31"
        //
        $sub_total = $total_package_price;
        $prorated_days = date("t") - date("j") + 1; // adding 1 to count today

        if ( $allow_pro_rate_billing && (date("j") >= $prorate_threshhold) && (count($cart[packages]) > 0) ) {
            // ProRate + Full Cycle
            $pro_pay = ( ( $sub_total / $set_main_pack_plan ) *
                         ( $prorated_days / date("t") ) );
            $pre_tax = $sub_total + $pro_pay ;
        } elseif ( $allow_pro_rate_billing && (date("j") < $prorate_threshhold) && (count($cart[packages]) > 0) ) {
            // ProRate Only
            $pro_pay = ( ( $sub_total / $set_main_pack_plan ) *
                         ( $prorated_days / date("t") ) );
            $pre_tax = $pro_pay ;
        } else {
            // No ProRate
            $pre_tax = $sub_total ;
        }

        // Add domain total & setup to packages total,
        // becasue domain & setup will always be due in full.
        $pre_tax+=$total_domain+$total_package_setup;

        if ($debug) echo "DAYS: $prorated_days (".display_currency($pro_pay)."):(".display_currency($pre_tax).")";

        foreach($cart[coupons] as $key => $value)
        {
            list($coupon_id,
                 $coupon_code,
                 $coupon_percent_discount,
                 $coupon_dollar_discount,
                 $coupon_comments,
                 $coupon_status,
                 $coupon_start_stamp,
                 $coupon_end_stamp,
                 $coupon_expire_string,
                 $coupon_count,
                 $coupon_max_count,
                 $coupon_new_only,
                 $coupon_renews,
                 $coupon_misc2) = $value;

             if ($coupon_dollar_discount>0)
             {
                 $this_discount = $coupon_dollar_discount;
                 $pre_tax      -= $this_discount;
                 $coupon_code   = $coupon_code;//." \$ ".OFF;

                 // This is the total due next renewal - any discount
                 // UPDATE -- DOLLAR AMOUNT DISCOUNTS ARE NOT RENEWABLE
                 $due_next_renewal = /*$total_package_price;*/ ($coupon_renews) ? $total_package_price - $this_discount : $total_package_price ;
             }
             elseif ($coupon_percent_discount>0)
             {
                 $this_discount    = $pre_tax * $coupon_percent_discount;
                 $this_tax_percent = $coupon_percent_discount * 100;
                 $pre_tax         -= $this_discount;
                 $coupon_code      = $coupon_code." $this_tax_percent% ".OFF;

                 // This is the total due next renewal - any discount
                 $due_next_renewal = ($coupon_renews) ? $total_package_price - ($total_package_price * $coupon_percent_discount) : $total_package_price ;
             }
             else
             {
                 $this_discount = 0;
                 // This is the total due next renewal - any discount
                 $due_next_renewal = $total_package_price ;
             }

             $display_discount_row  = "<tr>";
             $display_discount_row .= "<td bgcolor=$tablebgcolor width=35% align=right>".SFB."<b>".COUPON."</b>".EF."</td>";
             $display_discount_row .= "<td bgcolor=$tablebgcolor align=right>".SFB."<nobr>($coupon_code) <font color=red><b>".display_currency($this_discount)." -</b></font></nobr>".EF."</td></tr> ";
        }

        // If no coupons, set the next due amount to total package price.
        $due_next_renewal = ($due_next_renewal) ? $due_next_renewal : $total_package_price;

        $tax_due   = ($tax_enabled) ? $pre_tax * $tax_amount : 0 ;
        $this_tax  = $tax_amount * 100;
        if ($tax_number) $this_tax_number = "(".TAXID.": $tax_number)";

        $post_tax  =   $pre_tax
                     + $tax_due ;

        $cart[order_total] = number_format($post_tax, 2, '.', '');//display_currency($post_tax,1);

        $cart_html .= "<tr><td colspan=2 bgcolor=$tablebgcolor><hr size=1></td></tr>\n";
        if ($allow_pro_rate_billing && (date("j") >= $prorate_threshhold) && (count($cart[packages]) > 0) ) {
            $cart_html .= "<tr><td bgcolor=$tablebgcolor align=right>".SFB."<b>".PRORATE."</b>".EF."</td>";
            $cart_html .= "<td bgcolor=$tablebgcolor align=right>".SFB."<nobr>(".PRA." $prorated_days ".DAYS.") <b>".display_currency($pro_pay)." +</b></nobr>".EF."</td></tr> ";
        } elseif ($allow_pro_rate_billing && (date("j") < $prorate_threshhold) && (count($cart[packages]) > 0) ) {
            $cart_html .= "<tr><td bgcolor=$tablebgcolor align=right>".SFB."<b>".PRORATE."</b>".EF."</td>";
            $cart_html .= "<td bgcolor=$tablebgcolor align=right>".SFB."<nobr>(".PRA." $prorated_days ".DAYS.") <b>".display_currency($pro_pay)." +</b></nobr>".EF."</td></tr> ";
        }
        if ($display_discount_row) { $cart_html .= $display_discount_row; }
        if ($allow_pro_rate_billing && (date("j") >= $prorate_threshhold) && (count($cart[packages]) > 0) ) {
            $cart_html .= "<tr><td bgcolor=$tablebgcolor align=right>".SFB."<b>".SUBTOTAL."</b>".EF."</td>";
            $cart_html .= "<td bgcolor=$tablebgcolor align=right>".SFB."<b>".display_currency($pre_tax)."</b>&nbsp;&nbsp;".EF."</td></tr> ";
        }
        if ($tax_enabled) {
            $cart_html .= "<tr><td bgcolor=$tablebgcolor align=right>".SFB."<b>".TAXDUE."</b>".EF."</td>";
            $cart_html .= "<td bgcolor=$tablebgcolor align=right>".SFB."$this_tax_number <b>".display_currency($tax_due)." +</b>".EF."</td></tr> ";
        }
        $cart_html .= "<tr><td bgcolor=$tablebgcolor align=right>".SFB."<b>".TOTALDUE."</b>".EF."</td>";
        $cart_html .= "<td bgcolor=$tablebgcolor align=right>".SFB."<font size=+1><b>".display_currency($post_tax)."&nbsp;&nbsp;</b></font>".EF."</td></tr> ";
        if ($allow_pro_rate_billing && (date("j") >= $prorate_threshhold) && (count($cart[packages]) > 0) ) {
            $this_pack_plan = $set_main_pack_plan;
        } elseif ($allow_pro_rate_billing && (date("j") < $prorate_threshhold) && (count($cart[packages]) > 0) ) {
            $this_pack_plan = 0;
        } else {
            $this_pack_plan = $set_main_pack_plan;
        }
        if ($debug) { echo "<h1>$this_pack_plan : ".date("j")." > $prorate_threshhold</h1>"; }
        $cp_renew_date = ($allow_pro_rate_billing) ?
                            date($date_format,mktime(0,0,0,date("m")+$this_pack_plan+1,01,date("Y"))) :
                            date($date_format,mktime(0,0,0,date("m")+$this_pack_plan,date("d"),date("Y"))) ;
        if (count($cart[packages]) > 0) {
            $cart_html .= "<tr><td colspan=2 bgcolor=$tablebgcolor><hr size=1></td></tr> ";
            $cart_html .= "<tr><td bgcolor=$tablebgcolor align=center colspan=2><b>".SFB.display_currency($due_next_renewal)." ".NEXTPAYMENTDATE." ".$cp_renew_date."&nbsp;&nbsp;".EF."</b></td></tr> ";
        }
        $cart_html .= "</table>";
        $cart_html .= "</td></tr> ";
        $cart_html .= "</table>";

        return $cart_html;
}

function display_error($error_msg)
{
         GLOBAL $outerborder,
                $headercolor,
                $headertextcolor,
                $table_width;
         ?>
         <table cellpadding=2 cellspacing=2 border=1 bgcolor=<?=$outerborder?> align=center width="<?=$table_width?>">
         <tr><td bgcolor=beige align=center><?=nl2br($error_msg)?></td></tr>
         </table>
         <br>
         <?
}

function display_step($this_step)
{
         ?>
         <table align=center border=0 cellpadding=2 cellspacing=2>
         <tr><td align=center><b><?=PROGRESS?></b></td></tr>
         <tr><td align=center><img src=images/progress<?=$this_step?>.gif border=0></td></tr>
         </table>
         <br>
         <?
}

# --------------- #
#  "F" Functions  #
# --------------- #
function find_child_packages($parent_pack_id,$this_billing_cycle)
{

    GLOBAL $op,$dbh,$details_view,$tablebgcolor,$HTTP_POST_VARS,$this_vortech_config;

    if ($parent_pack_id=="") return;

    if (!$dbh) dbconnect();

    $allow_xyear           = $this_vortech_config["config_8"];
    $xannual_name          = $this_vortech_config["config_9"];
    $allow_monthly         = $this_vortech_config["config_27"];
    $monthly_name          = $this_vortech_config["config_28"];
    $allow_quarterly       = $this_vortech_config["config_29"];
    $quarterly_name        = $this_vortech_config["config_30"];
    $allow_semiannual      = $this_vortech_config["config_31"];
    $semiannual_name       = $this_vortech_config["config_32"];
    $allow_annual          = $this_vortech_config["config_33"];
    $annual_name           = $this_vortech_config["config_34"];

    $add_on_packages = NULL;

    $result = mysql_query("SELECT child_pack_id
                           FROM package_relationships
                           WHERE parent_pack_id = $parent_pack_id
                           AND pr_status = 2");

    while(list($child_pack_id) = mysql_fetch_row($result))
    {
      $result2 = mysql_query("SELECT pack_id,
                                     pack_name,
                                     pack_price,
                                     pack_setup,
                                     pack_comments,
                                     pack_status,
                                     pack_display,
                                     email_override,
                                     email_id,
                                     pack_stamp
                              FROM package_type
                              WHERE pack_id = $child_pack_id
                              AND pack_status = 2");
      $child_pack_id = mysql_fetch_array($result2);

      $this_price = split_price($child_pack_id[pack_price],"price",$this_billing_cycle)*$this_billing_cycle;
      $this_setup = split_price($child_pack_id[pack_setup],"setup",$this_billing_cycle);

      // debugging
      if ($debug) {
          echo "<pre>";
          echo "pack_price -> $child_pack_id[pack_price] -- $this_price -- $this_billing_cycle\n";
          echo "pack_setup -> $child_pack_id[pack_setup] -- $this_setup -- $this_billing_cycle\n";
          echo "</pre>";
      }

      if ( ($this_price==0) && ($this_setup==0) )
      {
         for($i=1;$i<=count($HTTP_POST_VARS['packages'])-1;$i++)
         {
            //list($parent_pack_id,$billing_cycle,$this_price,$this_setup)=explode("|",$HTTP_POST_VARS['packages'][$i]);
            // v3.0.9 - Removed Price b/c PHP 4.1+ caused variable override
            list($parent_pack_id)=explode("|",$HTTP_POST_VARS['packages'][$i]);
            if ($parent_pack_id==$child_pack_id['pack_id'])
            {
               $checked = "CHECKED";
            }
         }
         $add_on_packages .= "<tr>
                            <td bgcolor=$tablebgcolor width=35%>
                            <b><i>".ADDTOORDER."</i></b>&nbsp;<input type=checkbox name=\"packages[]\" value=\"".$child_pack_id['pack_id']."|$this_billing_cycle\" $checked>
                            <td bgcolor=$tablebgcolor align=right>
                            <nobr><b>".$child_pack_id[pack_name]."</b></nobr>:<br>
                            ".PRICE.": ".FREE."&nbsp;&nbsp;
                            </td></tr>";
      }
      else
      {
         for($i=1;$i<=count($HTTP_POST_VARS['packages'])-1;$i++)
         {
            //list($parent_pack_id,$billing_cycle,$this_price,$this_setup)=explode("|",$HTTP_POST_VARS['packages'][$i]);
            // v3.0.9 - Removed Price b/c PHP 4.1+ caused variable override
            list($parent_pack_id)=explode("|",$HTTP_POST_VARS['packages'][$i]);
            if ($parent_pack_id==$child_pack_id['pack_id'])
            {
               $checked = "CHECKED";
            }
         }
         switch ($this_billing_cycle) {
            case 1:  $this_cycle_name = $monthly_name." @ ";    break;
            case 3:  $this_cycle_name = $quarterly_name." @ ";  break;
            case 6:  $this_cycle_name = $semiannual_name." @ "; break;
            case 12: $this_cycle_name = $annual_name." @ ";     break;
            case 24: $this_cycle_name = $xannual_name." @ ";    break;
         }
         $add_on_packages .= "<tr>
                            <td bgcolor=$tablebgcolor width=35%>
                            <b><i>".ADDTOORDER."</i></b>&nbsp;<input type=checkbox name=\"packages[]\" value=\"".$child_pack_id['pack_id']."|$this_billing_cycle\" $checked>
                            </td>
                            <td bgcolor=$tablebgcolor align=right>
                            <nobr><b>".$child_pack_id['pack_name']."</b></nobr>:<br>
                            $this_cycle_name ".display_currency($this_price)." +<br>
                            ".SETUP.": ".display_currency($this_setup)." +
                            </td></tr>";
      }
      $checked = NULL;
    }
    return ($details_view) ? $add_on_packages : $add_on_packages ;
}

# --------------- #
#  "G" Functions  #
# --------------- #
function get_price($pack_id,$pack_plan)
{
        GLOBAL $dbh,$debug,$pack_display;

        $this_get_price = array();

        if(!$dbh)dbconnect();
        $result = mysql_query("SELECT pack_price,pack_setup FROM package_type WHERE pack_id = $pack_id");
        list($pack_price,$pack_setup) = mysql_fetch_array($result);

        $this_get_price[price] = split_price($pack_price,"price",$pack_plan)*$pack_plan;
        $this_get_price[setup] = split_price($pack_setup,"setup",$pack_plan);

        return $this_get_price;
}

# --------------- #
#  "I" Functions  #
# --------------- #
function is_child($child_pack_id)
{
         GLOBAL $dbh;
         if (!$dbh) dbconnect();
         list($num) = mysql_fetch_array(mysql_query("SELECT count(child_pack_id)
                                                     FROM package_relationships
                                                     WHERE child_pack_id = '$child_pack_id'"));
         return ($num) ? TRUE : FALSE ;
}

function is_parent($parent_pack_id)
{
         GLOBAL $dbh;
         if (!$dbh) dbconnect();
         list($num) = mysql_fetch_array(mysql_query("SELECT count(child_pack_id)
                                                     FROM package_relationships
                                                     WHERE parent_pack_id = '$parent_pack_id'
                                                     AND pr_status = 2"));
         return ($num) ? TRUE : FALSE ;
}

function is_valid_package($pack_id,$pack_plan=NULL)
{
         GLOBAL $dbh,$pack_display;
         if (!$dbh) dbconnect();
         list($num) = mysql_fetch_array(mysql_query("SELECT count(pack_id)
                                                     FROM package_type
                                                     WHERE pack_id = $pack_id
                                                     AND pack_status = 2
                                                     AND pack_display = $pack_display"));
         return ($num) ? TRUE : FALSE ;
}

# --------------- #
#  "S" Functions  #
# --------------- #
function select_country($x_Country="US")
{
         GLOBAL $stop;
         $x_Country = ($x_Country) ? $x_Country : "US" ;

         if (!$stop) { return "<input type=hidden name=x_Country value=\"$x_Country\">"; }
         ?>
        <select name="x_Country">
        <option value="0">Select One</option>
        <option value="0">-------------------------
        <option value="AD" <? if ($x_Country=="AD") echo "SELECTED"; ?>>Andorra</option>
        <option value="AE" <? if ($x_Country=="AE") echo "SELECTED"; ?>>United Arab Emirates</option>
        <option value="AF" <? if ($x_Country=="AF") echo "SELECTED"; ?>>Afghanistan</option>
        <option value="AG" <? if ($x_Country=="AG") echo "SELECTED"; ?>>Antigua and Barbuda</option>
        <option value="AI" <? if ($x_Country=="AI") echo "SELECTED"; ?>>Anguilla</option>
        <option value="AL" <? if ($x_Country=="AL") echo "SELECTED"; ?>>Albania</option>
        <option value="AM" <? if ($x_Country=="AM") echo "SELECTED"; ?>>Armenia</option>
        <option value="AN" <? if ($x_Country=="AN") echo "SELECTED"; ?>>Netherlands Antilles</option>
        <option value="AO" <? if ($x_Country=="AO") echo "SELECTED"; ?>>Angola</option>
        <option value="AQ" <? if ($x_Country=="AQ") echo "SELECTED"; ?>>Antarctica</option>
        <option value="AR" <? if ($x_Country=="AR") echo "SELECTED"; ?>>Argentina</option>
        <option value="AS" <? if ($x_Country=="AS") echo "SELECTED"; ?>>American Samoa</option>
        <option value="AT" <? if ($x_Country=="AT") echo "SELECTED"; ?>>Austria</option>
        <option value="AU" <? if ($x_Country=="AU") echo "SELECTED"; ?>>Australia</option>
        <option value="AW" <? if ($x_Country=="AW") echo "SELECTED"; ?>>Aruba</option>
        <option value="AZ" <? if ($x_Country=="AZ") echo "SELECTED"; ?>>Azerbaijan</option>
        <option value="BA" <? if ($x_Country=="BA") echo "SELECTED"; ?>>Bosnia and Herzegovina</option>
        <option value="BB" <? if ($x_Country=="BB") echo "SELECTED"; ?>>Barbados</option>
        <option value="BD" <? if ($x_Country=="BD") echo "SELECTED"; ?>>Bangladesh</option>
        <option value="BE" <? if ($x_Country=="BE") echo "SELECTED"; ?>>Belgium</option>
        <option value="BF" <? if ($x_Country=="BF") echo "SELECTED"; ?>>Burkina Faso</option>
        <option value="BG" <? if ($x_Country=="BG") echo "SELECTED"; ?>>Bulgaria</option>
        <option value="BH" <? if ($x_Country=="BH") echo "SELECTED"; ?>>Bahrain</option>
        <option value="BI" <? if ($x_Country=="BI") echo "SELECTED"; ?>>Burundi</option>
        <option value="BJ" <? if ($x_Country=="BJ") echo "SELECTED"; ?>>Benin</option>
        <option value="BM" <? if ($x_Country=="BM") echo "SELECTED"; ?>>Bermuda</option>
        <option value="BN" <? if ($x_Country=="BN") echo "SELECTED"; ?>>Brunei Darussalam</option>
        <option value="BO" <? if ($x_Country=="BO") echo "SELECTED"; ?>>Bolivia</option>
        <option value="BR" <? if ($x_Country=="BR") echo "SELECTED"; ?>>Brazil</option>
        <option value="BS" <? if ($x_Country=="BS") echo "SELECTED"; ?>>Bahamas</option>
        <option value="BT" <? if ($x_Country=="BT") echo "SELECTED"; ?>>Bhutan</option>
        <option value="BV" <? if ($x_Country=="BV") echo "SELECTED"; ?>>Bouvet Island</option>
        <option value="BW" <? if ($x_Country=="BW") echo "SELECTED"; ?>>Botswana</option>
        <option value="BY" <? if ($x_Country=="BY") echo "SELECTED"; ?>>Belarus</option>
        <option value="BZ" <? if ($x_Country=="BZ") echo "SELECTED"; ?>>Belize</option>
        <option value="CA" <? if ($x_Country=="CA") echo "SELECTED"; ?>>Canada</option>
        <option value="CC" <? if ($x_Country=="CC") echo "SELECTED"; ?>>Cocos (Keeling) Islands</option>
        <option value="CF" <? if ($x_Country=="CF") echo "SELECTED"; ?>>Central African Republic</option>
        <option value="CG" <? if ($x_Country=="CG") echo "SELECTED"; ?>>Congo</option>
        <option value="CH" <? if ($x_Country=="CH") echo "SELECTED"; ?>>Switzerland</option>
        <option value="CI" <? if ($x_Country=="CI") echo "SELECTED"; ?>>Cote D'Ivoire (Ivory Coast)</option>
        <option value="CK" <? if ($x_Country=="CK") echo "SELECTED"; ?>>Cook Islands</option>
        <option value="CL" <? if ($x_Country=="CL") echo "SELECTED"; ?>>Chile</option>
        <option value="CM" <? if ($x_Country=="CM") echo "SELECTED"; ?>>Cameroon</option>
        <option value="CN" <? if ($x_Country=="CN") echo "SELECTED"; ?>>China</option>
        <option value="CO" <? if ($x_Country=="CO") echo "SELECTED"; ?>>Colombia</option>
        <option value="CR" <? if ($x_Country=="CR") echo "SELECTED"; ?>>Costa Rica</option>
        <option value="CS" <? if ($x_Country=="CS") echo "SELECTED"; ?>>Czechoslovakia (former)</option>
        <option value="CU" <? if ($x_Country=="CU") echo "SELECTED"; ?>>Cuba</option>
        <option value="CV" <? if ($x_Country=="CV") echo "SELECTED"; ?>>Cape Verde</option>
        <option value="CX" <? if ($x_Country=="CX") echo "SELECTED"; ?>>Christmas Island</option>
        <option value="CY" <? if ($x_Country=="CY") echo "SELECTED"; ?>>Cyprus</option>
        <option value="CZ" <? if ($x_Country=="CZ") echo "SELECTED"; ?>>Czech Republic</option>
        <option value="DE" <? if ($x_Country=="DE") echo "SELECTED"; ?>>Germany</option>
        <option value="DJ" <? if ($x_Country=="DJ") echo "SELECTED"; ?>>Djibouti</option>
        <option value="DK" <? if ($x_Country=="DK") echo "SELECTED"; ?>>Denmark</option>
        <option value="DM" <? if ($x_Country=="DM") echo "SELECTED"; ?>>Dominica</option>
        <option value="DO" <? if ($x_Country=="DO") echo "SELECTED"; ?>>Dominican Republic</option>
        <option value="DZ" <? if ($x_Country=="DZ") echo "SELECTED"; ?>>Algeria</option>
        <option value="EC" <? if ($x_Country=="EC") echo "SELECTED"; ?>>Ecuador</option>
        <option value="EE" <? if ($x_Country=="EE") echo "SELECTED"; ?>>Estonia</option>
        <option value="EG" <? if ($x_Country=="EG") echo "SELECTED"; ?>>Egypt</option>
        <option value="EH" <? if ($x_Country=="EH") echo "SELECTED"; ?>>Western Sahara</option>
        <option value="ER" <? if ($x_Country=="ER") echo "SELECTED"; ?>>Eritrea</option>
        <option value="ES" <? if ($x_Country=="ES") echo "SELECTED"; ?>>Spain</option>
        <option value="ET" <? if ($x_Country=="ET") echo "SELECTED"; ?>>Ethiopia</option>
        <option value="FI" <? if ($x_Country=="FI") echo "SELECTED"; ?>>Finland</option>
        <option value="FJ" <? if ($x_Country=="FJ") echo "SELECTED"; ?>>Fiji</option>
        <option value="FK" <? if ($x_Country=="FK") echo "SELECTED"; ?>>Falkland Islands (Malvinas)</option>
        <option value="FM" <? if ($x_Country=="FM") echo "SELECTED"; ?>>Micronesia</option>
        <option value="FO" <? if ($x_Country=="FO") echo "SELECTED"; ?>>Faroe Islands</option>
        <option value="FR" <? if ($x_Country=="FR") echo "SELECTED"; ?>>France</option>
        <option value="FX" <? if ($x_Country=="FX") echo "SELECTED"; ?>>France, Metropolitan</option>
        <option value="GA" <? if ($x_Country=="GA") echo "SELECTED"; ?>>Gabon</option>
        <option value="GB" <? if ($x_Country=="GB") echo "SELECTED"; ?>>Great Britain (UK)</option>
        <option value="GD" <? if ($x_Country=="GD") echo "SELECTED"; ?>>Grenada</option>
        <option value="GE" <? if ($x_Country=="GE") echo "SELECTED"; ?>>Georgia</option>
        <option value="GF" <? if ($x_Country=="GF") echo "SELECTED"; ?>>French Guiana</option>
        <option value="GH" <? if ($x_Country=="GH") echo "SELECTED"; ?>>Ghana</option>
        <option value="GI" <? if ($x_Country=="GI") echo "SELECTED"; ?>>Gibraltar</option>
        <option value="GL" <? if ($x_Country=="GL") echo "SELECTED"; ?>>Greenland</option>
        <option value="GM" <? if ($x_Country=="GM") echo "SELECTED"; ?>>Gambia</option>
        <option value="GN" <? if ($x_Country=="GN") echo "SELECTED"; ?>>Guinea</option>
        <option value="GP" <? if ($x_Country=="GP") echo "SELECTED"; ?>>Guadeloupe</option>
        <option value="GQ" <? if ($x_Country=="GQ") echo "SELECTED"; ?>>Equatorial Guinea</option>
        <option value="GR" <? if ($x_Country=="GR") echo "SELECTED"; ?>>Greece</option>
        <option value="GS" <? if ($x_Country=="GS") echo "SELECTED"; ?>>S. Georgia and S. Sandwich Isls.</option>
        <option value="GT" <? if ($x_Country=="GT") echo "SELECTED"; ?>>Guatemala</option>
        <option value="GU" <? if ($x_Country=="GU") echo "SELECTED"; ?>>Guam</option>
        <option value="GW" <? if ($x_Country=="GW") echo "SELECTED"; ?>>Guinea-Bissau</option>
        <option value="GY" <? if ($x_Country=="GY") echo "SELECTED"; ?>>Guyana</option>
        <option value="HK" <? if ($x_Country=="HK") echo "SELECTED"; ?>>Hong Kong</option>
        <option value="HM" <? if ($x_Country=="HM") echo "SELECTED"; ?>>Heard and McDonald Islands</option>
        <option value="HN" <? if ($x_Country=="HN") echo "SELECTED"; ?>>Honduras</option>
        <option value="HR" <? if ($x_Country=="HR") echo "SELECTED"; ?>>Croatia (Hrvatska)</option>
        <option value="HT" <? if ($x_Country=="HT") echo "SELECTED"; ?>>Haiti</option>
        <option value="HU" <? if ($x_Country=="HU") echo "SELECTED"; ?>>Hungary</option>
        <option value="ID" <? if ($x_Country=="ID") echo "SELECTED"; ?>>Indonesia</option>
        <option value="IE" <? if ($x_Country=="IE") echo "SELECTED"; ?>>Ireland</option>
        <option value="IL" <? if ($x_Country=="IL") echo "SELECTED"; ?>>Israel</option>
        <option value="IN" <? if ($x_Country=="IN") echo "SELECTED"; ?>>India</option>
        <option value="IO" <? if ($x_Country=="IO") echo "SELECTED"; ?>>British Indian Ocean Territory</option>
        <option value="IQ" <? if ($x_Country=="IQ") echo "SELECTED"; ?>>Iraq</option>
        <option value="IR" <? if ($x_Country=="IR") echo "SELECTED"; ?>>Iran</option>
        <option value="IS" <? if ($x_Country=="IS") echo "SELECTED"; ?>>Iceland</option>
        <option value="IT" <? if ($x_Country=="IT") echo "SELECTED"; ?>>Italy</option>
        <option value="JM" <? if ($x_Country=="JM") echo "SELECTED"; ?>>Jamaica</option>
        <option value="JO" <? if ($x_Country=="JO") echo "SELECTED"; ?>>Jordan</option>
        <option value="JP" <? if ($x_Country=="JP") echo "SELECTED"; ?>>Japan</option>
        <option value="KE" <? if ($x_Country=="KE") echo "SELECTED"; ?>>Kenya</option>
        <option value="KG" <? if ($x_Country=="KG") echo "SELECTED"; ?>>Kyrgyzstan</option>
        <option value="KH" <? if ($x_Country=="KH") echo "SELECTED"; ?>>Cambodia</option>
        <option value="KI" <? if ($x_Country=="KI") echo "SELECTED"; ?>>Kiribati</option>
        <option value="KM" <? if ($x_Country=="KM") echo "SELECTED"; ?>>Comoros</option>
        <option value="KN" <? if ($x_Country=="KN") echo "SELECTED"; ?>>Saint Kitts and Nevis</option>
        <option value="KP" <? if ($x_Country=="KP") echo "SELECTED"; ?>>Korea (North)</option>
        <option value="KR" <? if ($x_Country=="KR") echo "SELECTED"; ?>>Korea (South)</option>
        <option value="KW" <? if ($x_Country=="KW") echo "SELECTED"; ?>>Kuwait</option>
        <option value="KY" <? if ($x_Country=="KY") echo "SELECTED"; ?>>Cayman Islands</option>
        <option value="KZ" <? if ($x_Country=="KZ") echo "SELECTED"; ?>>Kazakhstan</option>
        <option value="LA" <? if ($x_Country=="LA") echo "SELECTED"; ?>>Laos</option>
        <option value="LB" <? if ($x_Country=="LB") echo "SELECTED"; ?>>Lebanon</option>
        <option value="LC" <? if ($x_Country=="LC") echo "SELECTED"; ?>>Saint Lucia</option>
        <option value="LI" <? if ($x_Country=="LI") echo "SELECTED"; ?>>Liechtenstein</option>
        <option value="LK" <? if ($x_Country=="LK") echo "SELECTED"; ?>>Sri Lanka</option>
        <option value="LR" <? if ($x_Country=="LR") echo "SELECTED"; ?>>Liberia</option>
        <option value="LS" <? if ($x_Country=="LS") echo "SELECTED"; ?>>Lesotho</option>
        <option value="LT" <? if ($x_Country=="LT") echo "SELECTED"; ?>>Lithuania</option>
        <option value="LU" <? if ($x_Country=="LU") echo "SELECTED"; ?>>Luxembourg</option>
        <option value="LV" <? if ($x_Country=="LV") echo "SELECTED"; ?>>Latvia</option>
        <option value="LY" <? if ($x_Country=="LY") echo "SELECTED"; ?>>Libya</option>
        <option value="MA" <? if ($x_Country=="MA") echo "SELECTED"; ?>>Morocco</option>
        <option value="MC" <? if ($x_Country=="MC") echo "SELECTED"; ?>>Monaco</option>
        <option value="MD" <? if ($x_Country=="MD") echo "SELECTED"; ?>>Moldova</option>
        <option value="MG" <? if ($x_Country=="MG") echo "SELECTED"; ?>>Madagascar</option>
        <option value="MH" <? if ($x_Country=="MH") echo "SELECTED"; ?>>Marshall Islands</option>
        <option value="MK" <? if ($x_Country=="MK") echo "SELECTED"; ?>>Macedonia</option>
        <option value="ML" <? if ($x_Country=="ML") echo "SELECTED"; ?>>Mali</option>
        <option value="MM" <? if ($x_Country=="MM") echo "SELECTED"; ?>>Myanmar</option>
        <option value="MN" <? if ($x_Country=="MN") echo "SELECTED"; ?>>Mongolia</option>
        <option value="MO" <? if ($x_Country=="MO") echo "SELECTED"; ?>>Macau</option>
        <option value="MP" <? if ($x_Country=="MP") echo "SELECTED"; ?>>Northern Mariana Islands</option>
        <option value="MQ" <? if ($x_Country=="MQ") echo "SELECTED"; ?>>Martinique</option>
        <option value="MR" <? if ($x_Country=="MR") echo "SELECTED"; ?>>Mauritania</option>
        <option value="MS" <? if ($x_Country=="MS") echo "SELECTED"; ?>>Montserrat</option>
        <option value="MT" <? if ($x_Country=="MT") echo "SELECTED"; ?>>Malta</option>
        <option value="MU" <? if ($x_Country=="MU") echo "SELECTED"; ?>>Mauritius</option>
        <option value="MV" <? if ($x_Country=="MV") echo "SELECTED"; ?>>Maldives</option>
        <option value="MW" <? if ($x_Country=="MW") echo "SELECTED"; ?>>Malawi</option>
        <option value="MX" <? if ($x_Country=="MX") echo "SELECTED"; ?>>Mexico</option>
        <option value="MY" <? if ($x_Country=="MY") echo "SELECTED"; ?>>Malaysia</option>
        <option value="MZ" <? if ($x_Country=="MZ") echo "SELECTED"; ?>>Mozambique</option>
        <option value="NA" <? if ($x_Country=="NA") echo "SELECTED"; ?>>Namibia</option>
        <option value="NC" <? if ($x_Country=="NC") echo "SELECTED"; ?>>New Caledonia</option>
        <option value="NE" <? if ($x_Country=="NE") echo "SELECTED"; ?>>Niger</option>
        <option value="NF" <? if ($x_Country=="NF") echo "SELECTED"; ?>>Norfolk Island</option>
        <option value="NG" <? if ($x_Country=="NG") echo "SELECTED"; ?>>Nigeria</option>
        <option value="NI" <? if ($x_Country=="NI") echo "SELECTED"; ?>>Nicaragua</option>
        <option value="NL" <? if ($x_Country=="NL") echo "SELECTED"; ?>>Netherlands</option>
        <option value="NO" <? if ($x_Country=="NO") echo "SELECTED"; ?>>Norway</option>
        <option value="NP" <? if ($x_Country=="NP") echo "SELECTED"; ?>>Nepal</option>
        <option value="NR" <? if ($x_Country=="NR") echo "SELECTED"; ?>>Nauru</option>
        <option value="NT" <? if ($x_Country=="NT") echo "SELECTED"; ?>>Neutral Zone</option>
        <option value="NU" <? if ($x_Country=="NU") echo "SELECTED"; ?>>Niue</option>
        <option value="NZ" <? if ($x_Country=="NZ") echo "SELECTED"; ?>>New Zealand (Aotearoa)</option>
        <option value="OM" <? if ($x_Country=="OM") echo "SELECTED"; ?>>Oman</option>
        <option value="PA" <? if ($x_Country=="PA") echo "SELECTED"; ?>>Panama</option>
        <option value="PE" <? if ($x_Country=="PE") echo "SELECTED"; ?>>Peru</option>
        <option value="PF" <? if ($x_Country=="PF") echo "SELECTED"; ?>>French Polynesia</option>
        <option value="PG" <? if ($x_Country=="PG") echo "SELECTED"; ?>>Papua New Guinea</option>
        <option value="PH" <? if ($x_Country=="PH") echo "SELECTED"; ?>>Philippines</option>
        <option value="PK" <? if ($x_Country=="PK") echo "SELECTED"; ?>>Pakistan</option>
        <option value="PL" <? if ($x_Country=="PL") echo "SELECTED"; ?>>Poland</option>
        <option value="PM" <? if ($x_Country=="PM") echo "SELECTED"; ?>>St. Pierre and Miquelon</option>
        <option value="PN" <? if ($x_Country=="PN") echo "SELECTED"; ?>>Pitcairn</option>
        <option value="PR" <? if ($x_Country=="PR") echo "SELECTED"; ?>>Puerto Rico</option>
        <option value="PT" <? if ($x_Country=="PT") echo "SELECTED"; ?>>Portugal</option>
        <option value="PW" <? if ($x_Country=="PW") echo "SELECTED"; ?>>Palau</option>
        <option value="PY" <? if ($x_Country=="PY") echo "SELECTED"; ?>>Paraguay</option>
        <option value="QA" <? if ($x_Country=="QA") echo "SELECTED"; ?>>Qatar</option>
        <option value="RE" <? if ($x_Country=="RE") echo "SELECTED"; ?>>Reunion</option>
        <option value="RO" <? if ($x_Country=="RO") echo "SELECTED"; ?>>Romania</option>
        <option value="RU" <? if ($x_Country=="RU") echo "SELECTED"; ?>>Russian Federation</option>
        <option value="RW" <? if ($x_Country=="RW") echo "SELECTED"; ?>>Rwanda</option>
        <option value="SA" <? if ($x_Country=="SA") echo "SELECTED"; ?>>Saudi Arabia</option>
        <option value="Sb" <? if ($x_Country=="Sb") echo "SELECTED"; ?>>Solomon Islands</option>
        <option value="SC" <? if ($x_Country=="SC") echo "SELECTED"; ?>>Seychelles</option>
        <option value="SD" <? if ($x_Country=="SD") echo "SELECTED"; ?>>Sudan</option>
        <option value="SE" <? if ($x_Country=="SE") echo "SELECTED"; ?>>Sweden</option>
        <option value="SG" <? if ($x_Country=="SG") echo "SELECTED"; ?>>Singapore</option>
        <option value="SH" <? if ($x_Country=="SH") echo "SELECTED"; ?>>St. Helena</option>
        <option value="SI" <? if ($x_Country=="SI") echo "SELECTED"; ?>>Slovenia</option>
        <option value="SJ" <? if ($x_Country=="SJ") echo "SELECTED"; ?>>Svalbard and Jan Mayen Islands</option>
        <option value="SK" <? if ($x_Country=="SK") echo "SELECTED"; ?>>Slovak Republic</option>
        <option value="SL" <? if ($x_Country=="SL") echo "SELECTED"; ?>>Sierra Leone</option>
        <option value="SM" <? if ($x_Country=="SM") echo "SELECTED"; ?>>San Marino</option>
        <option value="SN" <? if ($x_Country=="SN") echo "SELECTED"; ?>>Senegal</option>
        <option value="SO" <? if ($x_Country=="SO") echo "SELECTED"; ?>>Somalia</option>
        <option value="SR" <? if ($x_Country=="SR") echo "SELECTED"; ?>>Suriname</option>
        <option value="ST" <? if ($x_Country=="ST") echo "SELECTED"; ?>>Sao Tome and Principe</option>
        <option value="SU" <? if ($x_Country=="SU") echo "SELECTED"; ?>>USSR (former)</option>
        <option value="SV" <? if ($x_Country=="SV") echo "SELECTED"; ?>>El Salvador</option>
        <option value="SY" <? if ($x_Country=="SY") echo "SELECTED"; ?>>Syria</option>
        <option value="SZ" <? if ($x_Country=="SZ") echo "SELECTED"; ?>>Swaziland</option>
        <option value="TC" <? if ($x_Country=="TC") echo "SELECTED"; ?>>Turks and Caicos Islands</option>
        <option value="TD" <? if ($x_Country=="TD") echo "SELECTED"; ?>>Chad</option>
        <option value="TF" <? if ($x_Country=="TF") echo "SELECTED"; ?>>French Southern Territories</option>
        <option value="TG" <? if ($x_Country=="TG") echo "SELECTED"; ?>>Togo</option>
        <option value="TH" <? if ($x_Country=="TH") echo "SELECTED"; ?>>Thailand</option>
        <option value="TJ" <? if ($x_Country=="TJ") echo "SELECTED"; ?>>Tajikistan</option>
        <option value="TK" <? if ($x_Country=="TK") echo "SELECTED"; ?>>Tokelau</option>
        <option value="TM" <? if ($x_Country=="TM") echo "SELECTED"; ?>>Turkmenistan</option>
        <option value="TN" <? if ($x_Country=="TN") echo "SELECTED"; ?>>Tunisia</option>
        <option value="TO" <? if ($x_Country=="TO") echo "SELECTED"; ?>>Tonga</option>
        <option value="TP" <? if ($x_Country=="TP") echo "SELECTED"; ?>>East Timor</option>
        <option value="TR" <? if ($x_Country=="TR") echo "SELECTED"; ?>>Turkey</option>
        <option value="TT" <? if ($x_Country=="TT") echo "SELECTED"; ?>>Trinidad and Tobago</option>
        <option value="TV" <? if ($x_Country=="TV") echo "SELECTED"; ?>>Tuvalu</option>
        <option value="TW" <? if ($x_Country=="TW") echo "SELECTED"; ?>>Taiwan</option>
        <option value="TZ" <? if ($x_Country=="TZ") echo "SELECTED"; ?>>Tanzania</option>
        <option value="UA" <? if ($x_Country=="UA") echo "SELECTED"; ?>>Ukraine</option>
        <option value="UG" <? if ($x_Country=="UG") echo "SELECTED"; ?>>Uganda</option>
        <option value="UK" <? if ($x_Country=="UK") echo "SELECTED"; ?>>United Kingdom</option>
        <option value="UM" <? if ($x_Country=="UM") echo "SELECTED"; ?>>US Minor Outlying Islands</option>
        <option value="US" <? if ($x_Country=="US") echo "SELECTED"; ?>>United States</option>
        <option value="UY" <? if ($x_Country=="UY") echo "SELECTED"; ?>>Uruguay</option>
        <option value="UZ" <? if ($x_Country=="UZ") echo "SELECTED"; ?>>Uzbekistan</option>
        <option value="VA" <? if ($x_Country=="VA") echo "SELECTED"; ?>>Vatican City State (Holy See)</option>
        <option value="VC" <? if ($x_Country=="VC") echo "SELECTED"; ?>>Saint Vincent and the Grenadines</option>
        <option value="VE" <? if ($x_Country=="VE") echo "SELECTED"; ?>>Venezuela</option>
        <option value="VG" <? if ($x_Country=="VG") echo "SELECTED"; ?>>Virgin Islands (British)</option>
        <option value="VI" <? if ($x_Country=="VI") echo "SELECTED"; ?>>Virgin Islands (U.S.)</option>
        <option value="VN" <? if ($x_Country=="VN") echo "SELECTED"; ?>>Viet Nam</option>
        <option value="VU" <? if ($x_Country=="WF") echo "SELECTED"; ?>>Vanuatu</option>
        <option value="WF" <? if ($x_Country=="WF") echo "SELECTED"; ?>>Wallis and Futuna Islands</option>
        <option value="WS" <? if ($x_Country=="WS") echo "SELECTED"; ?>>Samoa</option>
        <option value="YE" <? if ($x_Country=="YE") echo "SELECTED"; ?>>Yemen</option>
        <option value="YT" <? if ($x_Country=="YT") echo "SELECTED"; ?>>Mayotte</option>
        <option value="YU" <? if ($x_Country=="YU") echo "SELECTED"; ?>>Yugoslavia</option>
        <option value="ZA" <? if ($x_Country=="ZA") echo "SELECTED"; ?>>South Africa</option>
        <option value="ZM" <? if ($x_Country=="ZM") echo "SELECTED"; ?>>Zambia</option>
        <option value="ZR" <? if ($x_Country=="ZR") echo "SELECTED"; ?>>Zaire</option>
        <option value="ZW" <? if ($x_Country=="ZW") echo "SELECTED"; ?>>Zimbabwe</option>
        </select>
         <?
}

# --------------- #
#  "V" Functions  #
# --------------- #
function vortech_referrer_menu($referrer)
{
         GLOBAL $this_vortech_config,$stop;

         if (!$stop) { return "<input type=hidden name=referrer value=\"$referrer\">"; }

         $referrer_array = explode("|",trim($this_vortech_config["config_47"]));
         $count = count($referrer_array);
         echo "<select name=referrer>";
         if ($count == 1)
         {
            list($value,$name)=explode("=",$referrer_array[0]);
            $selected = (trim($value) == $referrer) ? "SELECTED" : NULL ;
            echo "<option value=\"".trim($value)."\" $selected>".trim($name)."</option>";
         }
         elseif ($count > 1)
         {
            for ($i = 0; $i <= $count - 1; $i++)
            {
                list($value,$name)=explode("=",$referrer_array[$i]);
                $selected = (trim($value) == $referrer) ? "SELECTED" : NULL ;
                echo "<option value=\"".trim($value)."\" $selected>".trim($name)."</option>";
            }
         }
         echo "</select>";
}

function vortech_package_select_menu($pack_display,$pack_id_selected,$pack_plan)
{
    GLOBAL $dbh,
           $this_vortech_config,
           $contract_pricing,
           $details_view,
           $child_package,
           $cart,
           $debug;

    if(!$dbh)dbconnect();
    $allow_xyear           = $this_vortech_config["config_8"];
    $xannual_name          = $this_vortech_config["config_9"];
    $allow_buy_domain_only = $this_vortech_config["config_14"];
    $allow_monthly         = $this_vortech_config["config_27"];
    $monthly_name          = $this_vortech_config["config_28"];
    $allow_quarterly       = $this_vortech_config["config_29"];
    $quarterly_name        = $this_vortech_config["config_30"];
    $allow_semiannual      = $this_vortech_config["config_31"];
    $semiannual_name       = $this_vortech_config["config_32"];
    $allow_annual          = $this_vortech_config["config_33"];
    $annual_name           = $this_vortech_config["config_34"];
    $pack_display          = ($pack_display) ? $pack_display : $this_vortech_config["config_25"];
    $package_menu_display_order  = ($this_vortech_config["config_51"]) ? $this_vortech_config["config_51"] : "pack_name"; //v3.1.0
    $package_menu_display_type   = ($this_vortech_config["config_52"]) ? $this_vortech_config["config_52"] : 2; //v3.1.0

    $string = "&nbsp;&nbsp;&nbsp;&nbsp;";

    $sql = "SELECT pack_id,
                   pack_name,
                   pack_price,
                   pack_setup FROM package_type WHERE ";
    $sql .= ($child_package && $pack_id_selected) ? "pack_id = $pack_id_selected" : " pack_status=2 AND pack_display=$pack_display" ;

    // Select Pacakge Display Order v3.1.0
    $package_menu_display_order = ($package_menu_display_order) ? $package_menu_display_order : "pack_name" ;

    $sql .= " ORDER BY $package_menu_display_order";
    if ($debug) { echo "SQL-$sql<br clear=all>"; }
    $result = mysql_query($sql);

    // ORDER PACKAGE DISPLAY TYPE 1
    $package_select_menu  = "<pre><select name=packages[]>";
    $package_select_menu .= ($allow_buy_domain_only&&count($cart[domains])>0) ? "<option value=\"0\">".DOMAINONLYORSELECT."</option>" : NULL ;

    // ORDER PACKAGE DISPLAY TYPE 2 -- v3.0.9
    $package_select_menu_2 .= ($allow_buy_domain_only&&count($cart[domains])>0) ? "<input type=radio name=this_package_menu_2 value=0>&nbsp;<b>".DOMAINONLYORSELECT."</b><p>" : NULL ;

    // ORDER PACKAGE DISPLAY TYPE 3 -- v3.1.0
    $package_select_menu_3 .= "<select name=type3_package>";
    $package_select_menu_3 .= ($allow_buy_domain_only&&count($cart[domains])>0) ? "<option value=\"DOMAIN\">".DOMAINONLYORSELECT."</option>" : NULL ;

    while(list($pack_id,$pack_name,$pack_price,$pack_setup) = mysql_fetch_array($result))
    {
         $price_array = explode("|",$pack_price);
         $setup_array = explode("|",$pack_setup);

         // ORDER PACKAGE DISPLAY TYPE 2 -- v3.0.9
         $package_select_menu_2 .= "<input type=radio name=this_package_menu_2 value=\"$pack_id\" ";
         $package_select_menu_2 .= ($pack_id_selected == $pack_id || !$pack_id_selected) ? "CHECKED" : NULL ;
         $pack_id_selected       = ($pack_id_selected) ? $pack_id_selected : "x" ;
         $package_select_menu_2 .= "> <b>$pack_name</b><br>";
         $package_select_menu_2 .= "<select name=packages[]>";

         // ORDER PACKAGE DISPLAY TYPE 3 -- v3.1.0
         $package_select_menu_3 .= "<option value=\"$pack_id\" ";
         $package_select_menu_3 .= ($pack_id_selected == $pack_id) ? "SELECTED" : NULL ;
         $package_select_menu_3 .= ">$pack_name</option>";

         if ($contract_pricing)
         {
             // Build Monthly Menu
             if ($allow_monthly && ($price_array[0]!=0 || $child_package))
             {
                 $this_price = $price_array[0] * 1;
                 $this_setup = ($setup_array[0]!=0) ? $setup_array[0] : 0 ;

                 $package_select_menu   .= "<option value=\"$pack_id|1|$this_price|$this_setup\" ";
                 $package_select_menu_2 .= "<option value=\"$pack_id|1|$this_price|$this_setup\" ";
                 $package_select_menu   .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 1 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu_2 .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 1 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu   .= ">$pack_name:$string $monthly_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";
                 $package_select_menu_2 .= ">".PAY." $monthly_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";

                 if (($pack_id_selected==$pack_id)&&($pack_plan==1)) $this_package = "<b>$pack_name:</b><br>&nbsp;&nbsp;$monthly_name @ ".display_currency($this_price)." +<br>".SETUP.": ".display_currency($this_setup);
             }

             // Build Quarterly Menu
             if ($allow_quarterly && ($price_array[1]!=0 || $child_package))
             {
                 $this_price = $price_array[1] * 3;
                 $this_setup = ($setup_array[1]!=0) ? $setup_array[1] : 0 ;

                 $package_select_menu   .= "<option value=\"$pack_id|3|$this_price|$this_setup\" ";
                 $package_select_menu_2 .= "<option value=\"$pack_id|3|$this_price|$this_setup\" ";
                 $package_select_menu   .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 3 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu_2 .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 3 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu   .= ">$pack_name:$string $quarterly_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";
                 $package_select_menu_2 .= ">".PAY." $quarterly_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";

                 if (($pack_id_selected==$pack_id)&&($pack_plan==3)) $this_package = "<b>$pack_name:</b><br>&nbsp;&nbsp;$quarterly_name @ ".display_currency($this_price)." +<br>".SETUP.": ".display_currency($this_setup);
             }

             // Build Semi-Annual Menu
             if ($allow_semiannual && ($price_array[2]!=0 || $child_package))
             {
                 $this_price = $price_array[2] * 6;
                 $this_setup = ($setup_array[2]!=0) ? $setup_array[2] : 0 ;

                 $package_select_menu   .= "<option value=\"$pack_id|6|$this_price|$this_setup\" ";
                 $package_select_menu_2 .= "<option value=\"$pack_id|6|$this_price|$this_setup\" ";
                 $package_select_menu   .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 6 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu_2 .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 6 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu   .= ">$pack_name:$string $semiannual_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";
                 $package_select_menu_2 .= ">".PAY." $semiannual_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";

                 if (($pack_id_selected==$pack_id)&&($pack_plan==6)) $this_package = "<b>$pack_name:</b><br>&nbsp;&nbsp;$semiannual_name @ ".display_currency($this_price)." +<br>".SETUP.": ".display_currency($this_setup);
             }

             // Build Annual Menu
             if ($allow_annual && ($price_array[3]!=0 || $child_package))
             {
                 $this_price = $price_array[3] * 12;
                 $this_setup = ($setup_array[3]!=0) ? $setup_array[3] : 0 ;

                 $package_select_menu   .= "<option value=\"$pack_id|12|$this_price|$this_setup\" ";
                 $package_select_menu_2 .= "<option value=\"$pack_id|12|$this_price|$this_setup\" ";
                 $package_select_menu   .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 12 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu_2 .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 12 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu   .= ">$pack_name:$string $annual_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";
                 $package_select_menu_2 .= ">".PAY." $annual_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";

                 if (($pack_id_selected==$pack_id)&&($pack_plan==12)) $this_package = "<b>$pack_name:</b><br>&nbsp;&nbsp;$annual_name @ ".display_currency($this_price)." +<br>".SETUP.": ".display_currency($this_setup);
             }

             // Build 2-Year Menu
             if ($allow_xyear && ($price_array[4]!=0 || $child_package))
             {
                 $this_price = $price_array[4] * 24;
                 $this_setup = ($setup_array[4]!=0) ? $setup_array[4] : 0 ;

                 $package_select_menu   .= "<option value=\"$pack_id|24|$this_price|$this_setup\" ";
                 $package_select_menu_2 .= "<option value=\"$pack_id|24|$this_price|$this_setup\" ";
                 $package_select_menu   .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 24 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu_2 .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 24 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu   .= ">$pack_name:$string $xannual_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";
                 $package_select_menu_2 .= ">".PAY." $xannual_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";

                 if (($pack_id_selected==$pack_id)&&($pack_plan==24)) $this_package = "<b>$pack_name:</b><br>&nbsp;&nbsp;$xannual_name @ ".display_currency($this_price)." +<br>".SETUP.": ".display_currency($this_setup);
             }
         }
         else
         {
             // Build the default 1 Month Menu
                 $this_price = $price_array[0] * 1;
                 $this_setup = ($setup_array[0]!=0) ? $setup_array[0] : 0 ;

                 $package_select_menu   .= "<option value=\"$pack_id|1|$this_price|$this_setup\" ";
                 $package_select_menu_2 .= "<option value=\"$pack_id|1|$this_price|$this_setup\" ";
                 $package_select_menu   .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 1 ) ) ? "SELECTED" : NULL ;
                 $package_select_menu_2 .= ( ( $pack_id_selected == $pack_id ) && ( $pack_plan == 1 ) ) ? "SELECTED" : NULL ;
                 $num    = strlen(strval($pack_name));
                 $string = NULL;
                 $num    = 20 - $num;
                 for($i=1;$i<=$num;$i++) { $string .= "&nbsp;"; }
                 $package_select_menu   .= ">$pack_name:$string $monthly_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";
                 $package_select_menu_2 .= ">".PAY." $monthly_name @ ".display_currency($this_price)." + ".SETUP.": ".display_currency($this_setup)."</option>";

                 if (($pack_id_selected==$pack_id)&&($pack_plan==1)) $this_package = "<b>$pack_name:</b><br>&nbsp;&nbsp;$monthly_name: ".display_currency($this_price).", ".SETUP.": ".display_currency($this_setup);
         }
         // ORDER PACKAGE DISPLAY TYPE 2 -- v3.0.9
         $package_select_menu_2 .= "</select><br><br clear=all>";
    }
    // ORDER PACKAGE DISPLAY TYPE 1
    $package_select_menu .= "</select></pre>";

    // ORDER PACKAGE DISPLAY TYPE 3 -- v3.1.0
    $package_select_menu_3 .= "</select><br>";

    // Time to select which display to serve up!
    switch ($package_menu_display_type) {
       case 1:  $this_package_menu = $package_select_menu; break;
       case 2:  $this_package_menu = $package_select_menu_2; break;
       case 3:  $this_package_menu = $package_select_menu_3; break;
       default: $this_package_menu = $package_select_menu_2; break;
    }

    return ($details_view) ? $this_package : $this_package_menu ;
}

function vortech_HTML_start($type=NULL)
{
         GLOBAL $company_name,
                $include_css,
                $include_header,
                $table_width,
                $version;
         ?>
         <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
         <HTML dir="<?=TEXTDIRECTION?>">
         <head>
         <title><?=$company_name." ".SERVICESIGNUP?></title>
         <META HTTP-EQUIV="Content-Type" CONTENT="text/html;" CHARSET="<?=CHARSET?>">
         <!-- ModernBill TM .:. Client Billing System .:. Version <?=$version?> -->
         <!-- Copyright © 2001,2002 .:. ModernGigabyte, LLC .:. All Rights Reserved. -->
         <? if ($include_css) include("template/index.css"); ?>
         </head>
         <body>
         <?
         print str_repeat(" ", 300) . "\n";
         flush();
         if ($include_header && file_exists("template/".$type."header.html") ) {
           include("template/".$type."header.html");
         } elseif ($include_header) {
           include("template/header.html");
         }
         ?>
         <br><table cellpadding=0 cellspacing=0 border=0 align=center width=<?=$table_width?> COLS=1><tr><td>
         <?
}

function vortech_HTML_stop($type=NULL)
{
         GLOBAL $include_footer,
                $HTTP_POST_VARS,
                $PHP_SELF,
                $cart,
                $debug,
                $script_url_non_secure;
         ?>
         <? if (is_int($type)) { ?><center>[<a href=<?=$script_url_non_secure?>index.php?submit_clear=1><?=STARTOVER?></a>]</center><br><? } ?>
         </td></tr></table>
         <?
         if ($include_footer && file_exists("template/".$type."footer.html") ) {
           include("template/".$type."footer.html");
         } elseif ($include_footer) {
           include("template/footer.html");
         }
         ?>
         <? if ($debug) { ?>
         <hr>
         <pre>
         <?=var_dump($cart)?>
         </pre>
         <hr>
         <? } ?>
         </body>
         </html>
         <?
}



function vortech_TABLE_start($title)
{
         GLOBAL $outerborder,
                $headercolor,
                $headertextcolor;
         ?>
         <table cellpadding=0 cellspacing=0 border=0 bgcolor=<?=$outerborder?> align=center width="100%">
         <tr><td bgcolor=<?=$headercolor?> height=15 align=center><font color=<?=$headertextcolor?>><b><?=$title?></b></font></td></tr>
         <tr><td>
         <?
}

function vortech_TABLE_stop()
{
         ?>
         </td></tr>
         </table>
         <?
}

function validate_coupon($coupon_code)
{
         GLOBAL $dbh,
                $coupon_details;
         if(!$dbh)dbconnect();
         $sql ="SELECT * FROM coupon_codes WHERE coupon_status = 2 AND coupon_code = '$coupon_code'";
         $coupon_details = mysql_fetch_array(mysql_query($sql));
         return $coupon_details;
}

function vortech_login($username,$password) {
    global $dbh,
           $isloggedin,
           $this_user,
           $username,
           $prefix,
           $logout_hourly;

    if(!$dbh)dbconnect();
    $hash = md5($password);

    if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$username))
    {
        $sql = "SELECT * FROM client_info WHERE client_email='$username' AND client_password='$hash'";
        $result = mysql_query($sql,$dbh) or die (mysql_error());
            if (!$result || mysql_num_rows($result) < 1)
            {
                $this_user  = NULL;
                $this_admin = NULL;
                return FALSE;
            }
            else
            {
                $this_user  = mysql_fetch_array($result);
                $this_admin = NULL;
                $hashvar    = ($logout_hourly) ? date("F d, Y H") : date("F d, Y") ;
                $isloggedin = md5($hashvar);
                return TRUE;
            }
    }
    else
    {
                $this_admin = NULL;
                $this_user  = NULL;
                return FALSE;
    }
}

function validate_ip($security_level,$aclass,$bclass,$cclass,$dclass)
{
         switch ($security_level) {
            case 0:  return ($aclass&&$bclass&&$cclass&&$dclass) ? TRUE : FALSE ; break;
            case 1:  return ($aclass&&$bclass&&$cclass) ? TRUE : FALSE ; break;
            case 2:  return ($aclass&&$bclass) ? TRUE : FALSE ; break;
            case 3:  return ($aclass) ? TRUE : FALSE ; break;
            default: return TRUE; break;
         }
}

# --------------- #
#  "W" Functions  #
# --------------- #
function whois($domain,$tld_extension)
{
        GLOBAL $dbh,$debug;
        $this_domain = trim($domain.".".$tld_extension);
        if(!$dbh)dbconnect();

        list($tld_whois_server,$tld_whois_response) = mysql_fetch_row(mysql_query("SELECT tld_whois_server, tld_whois_response FROM tld_config WHERE tld_extension = '$tld_extension'"));

        if (eregi("http://",$tld_whois_server)) {
                // DO HTML WHOIS LOOKUP
                $server    = str_replace("%%domain%%",$this_domain,$tld_whois_server);
                $fcontents = file($server);
                $search    = array ("'<script[^>]*?>.*?</script>'si","'</tr>'i","'<br>'i","'<p>'i","'<[\/\!]*?[^<>]*?>'si","'&(quote|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
                $replace   = array ("","\n","\n","\n\n","","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\\1)");
                $document  = "";
                while (list($lnum,$line)=each($fcontents)) {
                       $document=$document.$line;
                }
                $text      = preg_replace($search,$replace,$document);
                $data      = explode("\n",$text);
                $ec        = 1;
                while (list($lnum,$line)=each($data)) {
                       $line=trim($line);
                       if ($line=="") {
                           if ($ec<1) {
                               $whois_buffer[output].="";
                           }
                           $ec++;
                        } else {
                           $whois_buffer[output].=$line;
                           $ec=0;
                        }
                }
                if($debug) echo "<b>HTML: $server</b><br>".$whois_buffer[output]."<hr>";
        } else {
                // DO WHOIS SERVER LOOKUP
                $server = ($tld_whois_server) ? $tld_whois_server : "whois.internic.net" ;
                $fp = fsockopen ($server, 43, $errnr, $errstr, 10);
                if (!$fp) {
                     echo "[".ERROR."] $errstr: ($errnr)<br>";
                } else {
                     fputs($fp,"$this_domain\r\n");
                     $whois_buffer[output] .= "<pre>\r\n";
                     while (!feof($fp)) {
                             $whois_buffer[output] .= "<pre>".fgets($fp,2048)."</pre>";
                     }
                     $whois_buffer[output] .= "</pre>";
                     fclose ($fp);
                }
                if($debug) echo "<b>WHOIS: $server</b><br>".$whois_buffer[output]."<hr>";
        }

        $tld_whois_response = ($tld_whois_response) ? $tld_whois_response : "No match" ;
        $whois_buffer[is_registered] = (eregi($tld_whois_response,$whois_buffer[output])) ? FALSE : TRUE ;

        return $whois_buffer;
}


?>