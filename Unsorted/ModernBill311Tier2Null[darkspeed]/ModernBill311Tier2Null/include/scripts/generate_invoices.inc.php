<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
*/
## Cronjob Settings
## -----------------------------------
if ($argv) {
    $DIR  = $argv[1]; // Set the full path to the ModenrBill root directory
    $cron = $argv[2]; // Enable Cron
    $z = ($argv[3]) ? $argv[3] : ($z) ? $z : 0 ; ; // set the month
    $d = ($argv[4]) ? $argv[4] : ($d) ? $d : 0 ; ; // set the day
} else {
    $DIR  = NULL;
}

## INCLUDE FIUNCTIONS AND DB CONNECTIONS
include_once($DIR."include/functions.inc.php");

## WARNING!  If you are running this from a cron job comment
##           this line out and change the <br> to a \n
if (!$cron) { if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; } }
$debug_br           = "<br>";

GLOBAL $dbh,$email_id,$success,$date_format;
if(!$dbh)dbconnect();

if ($debug) { echo SFB; }

## INVOICE SPECIFIC DEFINES
define(S,"<tr><td bgcolor=FFFFFF><font face=Verdana size=1>");
define(SF,"<font face=Verdana size=1>");
define(M,"</font></td><td bgcolor=FFFFFF><font face=Verdana size=1>");
define(MR,"</font></td><td align=right bgcolor=FFFFFF><font face=Verdana size=1>");
define(MC,"</td><td align=center bgcolor=FFFFFF><font face=Verdana size=1>");
define(E,"</font></td></tr>");

## Define the Table/TitleRow of the invoice_snapshot
$table_start  = "<table border=0 cellpadding=0 cellspacing=0 width=98% align=center>";
$table_start .= "<tr><td bgcolor=DDDDDD>";
$table_start .= "<table border=0 cellpadign=1 cellspacing=1 width=100%>";
$table_stop   = "</table></td></tr></table>";
$title_row   = S."<u>".PACKAGE."</u>".
              MC."<u>".QTY."</u>".
              MC."<u>".STARTDATE."</u>".
              MC."<u>".RENEWDATE."</u>".
              MC."<u>".NEXTRENEWAL."</u>".
              MC."<u>".PAYPERIOD."</u>".
              MC."<u>".PRICE."</u>".
              MC."<u>".PRORATED."</u>".
              MC."<u>".SETUP."</u>".
              MC."<u>".DISCOUNT."</u>".
              MC."<u>".TOTAL."</u>".
               E;

## Generate invoices for "Z" months in the future.
##   EX. If this month is July...
##       ...url?z=1 will generate invoices for August
##       ...url?z=2 will generate invoices for September
$z = ($z) ? $z : 0 ;
$d = ($d) ? $d : 0 ;
## WARNING: This is for debugging ONLY.
##          Later versions may use this to generate invoices for
##          upcoming months in advance.
##

## Today's Stamp & Date
$today_stamp                  = mktime(0,0,0,date("m")+$z,date("d"),date("Y"));
$today                        = date("Y/m/d",$today_stamp);

## Today's "Day of the Year" as Stamp & Number
$today_day_of_year_stamp      = mktime(0,0,0,date("m")+$z,date("d")+$d,date("Y"));
$today_day_of_year            = date("z",$today_day_of_year_stamp);

## The "Day of the Year" for the 1st of THIS Month
$this_month_day_of_year_stamp = mktime(0,0,0,date("m")+$z,1+$d,date("Y"));
$this_month_day_of_year       = date("z",$this_month_day_of_year_stamp);

## The "Day of the Year" for the first of NEXT Month
$next_month_day_of_year_stamp = mktime(0,0,0,date("m")+$z+1,1+$d,date("Y"));
$next_month_day_of_year       = date("z",$next_month_day_of_year_stamp);

## The "Day of the Year" for the 1st of LAST Month
$last_month_day_of_year_stamp = mktime(0,0,0,date("m")+$z-1,1+$d,date("Y"));
$last_month_day_of_year       = date("z",$last_month_day_of_year_stamp);

## Total number of days in THIS month
$total_days_in_this_month     = $next_month_day_of_year-$this_month_day_of_year;

## Total number of days in LAST month
$total_days_in_last_month     = $this_month_day_of_year-$last_month_day_of_year;

## --- DEBUGGING DATA --- ##
if ($debug)
{
echo "<pre>
    ## Today's Stamp & Date
    $today_stamp                  = mktime(0,0,0,date(\"m\")+$z,date(\"d\"),date(\"Y\"));
    $today                        = date(\"Y/m/d\",$today_stamp);

    ## Today's \"Day of the Year\" as Stamp & Number
    $today_day_of_year_stamp      = mktime(0,0,0,date(\"m\")+$z,date(\"d\"),date(\"Y\"));
    $today_day_of_year            = date(\"z\",$today_day_of_year_stamp);

    ## The \"Day of the Year\" for the 1st of THIS Month
    $this_month_day_of_year_stamp = mktime(0,0,0,date(\"m\")+$z,1,date(\"Y\"));
    $this_month_day_of_year       = date(\"z\",$this_month_day_of_year_stamp);

    ## The \"Day of the Year\" for the first of NEXT Month
    $next_month_day_of_year_stamp = mktime(0,0,0,date(\"m\")+$z+1,1,date(\"Y\"));
    $next_month_day_of_year       = date(\"z\",$next_month_day_of_year_stamp);

    ## The \"Day of the Year\" for the 1st of LAST Month
    $last_month_day_of_year_stamp = mktime(0,0,0,date(\"m\")+$z-1,1,date(\"Y\"));
    $last_month_day_of_year       = date(\"z\",$last_month_day_of_year_stamp);

    ## Total number of days in THIS month
    $total_days_in_this_month     = $next_month_day_of_year-$this_month_day_of_year;

    ## Total number of days in LAST month
    $total_days_in_last_month     = $this_month_day_of_year-$last_month_day_of_year;
    </pre>";
}

## Set all counting $variables to Zero
$count_first_renewal=$count_renewed=$count_thismonth=$sum_first_renewal=$sum_renewed=$tax_due=$total_due=$this_credit=$credit_amount=0;

#-------------------------------------#
#  Loop through all "active" clients  #
#-------------------------------------#
set_time_limit(0);
$sql  = "SELECT * FROM client_info WHERE client_status=2 "; # <-- 2 = active
$sql .= ($client_id) ? "AND client_id = $client_id" : NULL ;
$result=mysql_query($sql,$dbh);
if (!$result) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
while($client_info=mysql_fetch_array($result))
{

     ## Reset $variable for each client
     $this_credit = $new_credit_amount = $this_client_owes = 0;
     $new_client_row = $rows = $invoice_insert = NULL;

     ## --- DEBUGGING DATA --- ##
     if ($debug) echo "THIS CLIENT: ".$client_info['client_id']."$debug_br$debug_br";

     ## Define a new client row in the invoice_snapshot
     $new_client_row = "<tr><td colspan=9>&nbsp;".SF."<b>(".$client_info['client_id'].")".$client_info['client_fname']." ".$client_info['client_lname']."</b>".E;

     #------------------------------------------------------#
     #  Loop through all "active" packages for this client  #
     #------------------------------------------------------#
     $sql="SELECT * FROM client_package WHERE client_id=".$client_info['client_id']." AND cp_status=2"; # <-- 2 = active
     if ($debug) { echo "SQL-> ".$sql.$debug_br; }
     $result2=mysql_query($sql,$dbh);
     if (!$result2) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
     while($client_package=mysql_fetch_array($result2)) {

          ## SELECT THIS PACKAGE_TYPE
          $sql="SELECT * FROM package_type WHERE pack_id=".$client_package['pack_id']."";
          if ($debug) { echo "SQL-> ".$sql.$debug_br; }
          $result3=mysql_query($sql,$dbh);
          if (!$result3) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
          $package_type=mysql_fetch_array($result3);

          ## --- DEBUGGING DATA --- ##
          if ($debug) echo "THIS pack_id:".$client_package['cp_id']."$debug_br";

          ## Setting date $variable, "z" DAY-OF-THE-YEAR
          $this_start_day  = date("z",$client_package['cp_start_stamp']);
          $this_renew_day  = ($client_package['cp_renew_stamp']==0) ? 0 : date("z",$client_package['cp_renew_stamp']) ;
          $this_renewed_on = date("z",$client_package['cp_renewed_on']);

          if
             #----------- STARTED THIS MONTH, DO NOTHING -----------#
             # --> No charges applied in this batch                 #
             # --> if this 1st <= this 15th && next 1st > this 15th #
             #------------------------------------------------------#
             ( ($this_month_day_of_year_stamp <= $client_package['cp_start_stamp']) &&
               ($next_month_day_of_year_stamp > $client_package['cp_start_stamp']) )
          {

                ## Stats $variable
                $count_thismonth++;

                ## Figure pro-rated days
                $prorated_days=$next_month_day_of_year-$this_start_day;

                ## Set display dates
                $start_date = date("Y/m/d",$client_package['cp_start_stamp']);
                $renew_date = date("Y/m/d",$client_package['cp_renew_stamp']);
                //echo "<h1>$renew_date</h1>";
                ## --- DEBUGGING DATA --- ##
                if ($debug)
                {
                    echo "Block 1 (STARTED THIS MONTH, DO NOTHING)$debug_br
                           Package: ".$package_type['pack_name']."$debug_br
                           Started This Month: $start_date$debug_br
                           Renews: $renew_date$debug_br
                           Will be payed in next month's batch.$debug_br";
                }

                ## Reset $variables for next block
                $total_cp=$pro_pay=$full_pay=$setup_pay=$renew_date=$next_renew_date=NULL;

          } # <-- END STARTED THIS MONTH, DO NOTHING
             //echo "1".date("Yz",$this_month_day_of_year_stamp)."<br>";
             //echo "2".date("Yz",$client_package['cp_renew_stamp'])."<br>";
          if (
             #------- STARTED LAST MONTH, DO FIRST RENEWAL ---------#
             # --> Pro-rate for last month & Charge full cycle      #
             # --> if last 1st < this 15th && this 1st > this 15th  #
             #------------------------------------------------------#
             ( ($client_package['cp_renewed_on'] == 0) &&
               ( ($last_month_day_of_year_stamp < $client_package['cp_start_stamp']) &&
                 ($this_month_day_of_year_stamp >= $client_package['cp_start_stamp']) ) ) ||

             #--------------- RENEWED THIS MONTH -------------------#
             # --> RENEW Package this month for full cycle          #
             # --> if this 1st == this renew 1st                    #
             #------------------------------------------------------#
             ( (date("Yz",$this_month_day_of_year_stamp) == date("Yz",$client_package['cp_renew_stamp'])) &&
               ($client_package['cp_renewed_on'] != 0) )
             )
          {

                ## Set do_propay = 1 or 0
                $do_propay = ((date("Yz",$this_month_day_of_year_stamp)==date("Yz",$client_package['cp_renew_stamp']))&&($client_package['cp_renewed_on']!=0)) ? 0 : 1 ;

                ## Stats $variable
                if ($client_package['cp_renewed_on'] == 0)
                {
                    $count_first_renewal++;
                }
                else
                {
                    $count_renewed++;
                }

                ## Figure pro-rated days
                $prorated_days=$this_month_day_of_year-$this_start_day;

                ## Set display dates
                $start_date=date("Y/m/d",$client_package['cp_start_stamp']);
                $renew_date=date("Y/m/d",$client_package['cp_renew_stamp']);

                ## Number of months to set renew date forward
                ## Ex (if "100", set "1" [one-time])
                ## Ex (if "112", [one-time annually])
                ## ALWAYS RENEW ON THE FIRST OF THE MONTH!
                ## Last Month Due: Pro-Rate the Previous Month
                ## This Month Due: Full Billing Cycle for This Month
                list($y,$m,$d)      = explode("/",$renew_date);

                ## CALCULATE PRO-RATED AMOUNT
                $next_first = mktime(0,0,0,date("m")+$z+1,1,date("Y"));
                $this_first = mktime(0,0,0,date("m")+$z,1,date("Y"));
                $this_last  = mktime(0,0,0,date("m")+$z,0,date("Y")); //v3.0.8

                // Fix for today's date as start date when should be real start date.
                #$this_day   = mktime(0,0,0,date("m")+$z,date("d"),date("Y")); // v3.0.8
                $this_day   = $client_package['cp_start_stamp']; // v3.0.8

                $one_day    = 60*60*24;
                $pro_pay    = 0;
                $prorated_days = (date("z",$client_package['cp_start_stamp'])==date("z",$client_package['cp_renew_stamp'])) ? 0 : ( ( $this_last - $this_day ) / $one_day ) ;

                /* FIX ME -- NEED TO UPDATE PRORATE LOGIC TO BE THE SAME AS FROM TEH VORTECH ORDER FORM
                $sub_total = $total_package_price;
                $prorated_days = date("t") - date("j");

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
                */

                switch($client_package['cp_billing_cycle'])
                {
                     case 1:   #"1"   => MONTHLY,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+1,$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",1) ;
                               if ($do_propay&&$prorated_days) $pro_pay = ($prorated_days / date("t") * $package_type['pack_price'] * $client_package['cp_qty']) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty'] * 1) ;
                               $is_domain          = FALSE;
                     break;

                     case 3:   #"3"   => QUARTERLY,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+3,$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",3) ;
                               if ($do_propay) $pro_pay = ($prorated_days / date("t") * $package_type['pack_price'] * $client_package['cp_qty']) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty'] * 3) ;
                               $is_domain          = FALSE;
                     break;

                     case 6:   #"6"   => SEMIANNUALLY,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+6,$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",6) ;
                               if ($do_propay) $pro_pay = ($prorated_days / date("t") * $package_type['pack_price'] * $client_package['cp_qty']) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty'] * 6) ;
                               $is_domain          = FALSE;
                     break;

                     case 12:  #"12"  => ANNUALLY,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+12,$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",12) ;
                               if ($do_propay) $pro_pay = ($prorated_days / date("t") * $package_type['pack_price'] * $client_package['cp_qty']) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty'] * 12) ;
                               $is_domain          = FALSE;
                     break;

                     case 24:  #"24"  => 2YEARS,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+24,$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",24) ;
                               if ($do_propay) $pro_pay = ($prorated_days / date("t") * $package_type['pack_price'] * $client_package['cp_qty']) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty'] * 24) ;
                               $is_domain          = FALSE;
                     break;

                     case 100: #"100" => ONETIME." ".NORENEWAL);
                               $raw_cp_renew_stamp = 1;
                               $next_renew_date    = ONETIME;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",1) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = FALSE;
                     break;

                     case 103: #"103" => ONETIME." ".QUARTERLY,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+3,$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",1) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = FALSE;
                     break;

                     case 106: #"106" => ONETIME." ".SEMIANNUALLY,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+6,$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",1) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = FALSE;
                     break;

                     case 112: #"112" => ONETIME." ".ANNUALLY,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+12,$renew_on_this_day,$y);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",1) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = FALSE;
                     break;

                     case 111: #"111" => DOMAIN.": ".YEAR1,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+1);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 124: #"124" => DOMAIN.": ".YEAR2,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+2);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 136: #"136" => DOMAIN.": ".YEAR3,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+3);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 148: #"148" => DOMAIN.": ".YEAR4,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+4);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 160: #"160" => DOMAIN.": ".YEAR5,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+5);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 172: #"172" => DOMAIN.": ".YEAR6,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+6);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 184: #"184" => DOMAIN.": ".YEAR7,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+7);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 196: #"196" => DOMAIN.": ".YEAR8,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+8);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 1108: #"1108" => DOMAIN.": ".YEAR9,
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+9);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     case 1120: #"1120" => DOMAIN.": ".YEAR10);
                               $raw_cp_renew_stamp = mktime(0,0,0,$m,$renew_on_this_day,$y+10);
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $pro_pay            = 0;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : $client_package['pack_price'] ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']);
                               $is_domain          = TRUE;
                     break;

                     default:
                               $raw_cp_renew_stamp = mktime(0,0,0,$m+$client_package['cp_billing_cycle'],$renew_on_this_day,$y) ;
                               $next_renew_date    = date($date_format,$raw_cp_renew_stamp) ;
                               $this_pack_price    = ($client_package[pack_price]>0) ? $client_package[pack_price] : split_price($package_type['pack_price'],"price",$client_package['cp_billing_cycle']) ;
                               if ($do_propay) $pro_pay = ($prorated_days / date("t") * $package_type['pack_price'] * $client_package['cp_qty']) ;
                               $full_pay           = ($this_pack_price * $client_package['cp_qty']) ;
                               $is_domain          = FALSE;
                     break;
                }

                credit_affiliate($client_package[cp_id],$client_package[cp_start_stamp],$client_package[aff_code],$full_pay);

                ## UPDATE Renew Stamps for This Package
                $update_sql = "UPDATE client_package SET cp_renew_stamp='$raw_cp_renew_stamp', cp_renewed_on='".mktime()."' WHERE cp_id='".$client_package['cp_id']."'";
                if (!mysql_query($update_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
                $log_comments = RENEWEDPACKAGE." ".$client_package['cp_id']." - ".date("$date_format: h:i:s")." - ".$this_admin['admin_realname'];
                log_event($client_info['client_id'],$log_comments,3);

                ## --- DEBUGGING DATA --- ##
                if ($debug) echo "UPDATE: $update_sql$debug_br";

                ## Setup IF First Billing Cycle
                if ($client_package['cp_renewed_on']==0)
                {
                    $setup_pay_amount = split_price($package_type['pack_setup'],"setup",$client_package['cp_billing_cycle']);
                    $setup_pay_debug  = SETUP.": ".display_currency(split_price($package_type['pack_setup'],"setup",$client_package['cp_billing_cycle']));
                }

                ## Calculate Total After DISCOUNT
                $total_cp = $pro_pay + $full_pay + $setup_pay_amount;
                $discount = ($client_package['cp_discount']) ? $client_package['cp_discount'] * $total_cp : 0 ;
                $total_cp = $total_cp - $discount;

                ## Calculate TOTAL for This Package (Add to Client Total)
                $this_client_owes  += $total_cp;

                ## Map Domain Names
                /*
                $list_domains=$domain_name=NULL;
                $sql="SELECT d.domain_name FROM account_details a, domain_names d WHERE a.domain_id=d.domain_id AND a.cp_id='".$client_package['cp_id']."' ORDER BY a.details_id LIMIT 0,1";
                $domain_result=mysql_query($sql,$dbh);
                if (!$domain_result) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }
                while(list($domain_name)=mysql_fetch_array($domain_result))
                {
                   $list_domains .= $domain_name.",";
                }
                $list_domains = ($list_domains) ? substr($list_domains,0,-1) : NONE ;
                */
                $list_domains = map_domains($client_package['cp_id'],1);

                ## Save $variables for This Package
                $rows .= /* Pack Name  */  S.$package_type['pack_name'].": ".$list_domains.
                         /* QTY        */ MC.$client_package['cp_qty'].
                         /* Start Date */ MC.$start_date.
                         /* Renew Date */ MC.$renew_date.
                         /* Next Renew */ MC.$next_renew_date.
                         /* PayPeriod  */ MC.$cycle_types[$client_package[cp_billing_cycle]].
                         /* Price      */ MC.display_currency($this_pack_price).
                         /* ProRated   */ MC.display_currency($pro_pay).
                         /* Setup      */ MC.display_currency($setup_pay_amount).
                         /* Discount   */ MC.display_currency($discount).
                         /* Total      */ MR.display_currency($total_cp).
                                           E;

                ## --- DEBUGGING DATA --- ##
                if ($debug&&($client_package['cp_renewed_on'] == 0))
                {
                    echo "Block 2: (STARTED LAST MONTH, DO FIRST RENEWAL)$debug_br
                          Package: ".$package_type['pack_name']."$debug_br
                          Started Last Month: $start_date$debug_br
                          Pro-Rated: ".display_currency($pro_pay)." (for: $prorated_days days)$debug_br
                          Full Cycle: ".display_currency($full_pay)." (".$client_package['cp_billing_cycle']."x".$this_pack_price.")$debug_br
                          $setup_pay_debug$debug_br
                          Discount ".display_currency($discount)."$debug_br
                          Total for pack_id:".$client_package['cp_id'].": ".display_currency($total_cp)."$debug_br";
                }
                ## --- DEBUGGING DATA --- ##
                if ($debug&&($client_package['cp_renewed_on'] != 0))
                {
                    echo "Block 3: (RENEWED THIS MONTH)$debug_br
                          Package: ".$package_type['pack_name']."$debug_br
                          Renewed This Month: $renew_date$debug_br
                          Full Cycle: ".display_currency($full_pay)." (".$client_package['cp_billing_cycle']."x".$this_pack_price.")$debug_br
                          $setup_pay_debug$debug_br
                          Discount ".display_currency($discount)."$debug_br
                          Total for pack_id:".$client_package['cp_id'].": \$".display_currency($total_cp)."$debug_br";
                }

                ## Set INSERT Invoice Flag
                $invoice_insert = 1;

                ## Reset $variables for next block
                $total_cp=$pro_pay=$full_pay=$setup_pay_debug=$setup_pay_amount=$renew_date=$next_renew_date=$discount=NULL;

          } # <-- END STARTED LAST MONTH, DO FIRST RENEWAL

     } # <-- NEXT PACKAGE

    ## DO CLIENT_CREDIT after all packages have been tallied
    ## Update if some credit is left over
    if ($invoice_insert)
    {
        $sql = "SELECT credit_id,credit_amount,credit_comments,credit_stamp FROM client_credit WHERE client_id=".$client_info['client_id']." AND credit_amount > 0";
        $credit_result  = mysql_query($sql,$dbh);
        $paid_by_credit = FALSE;
        while(list($credit_id,$credit_amount,$credit_comments,$credit_stamp)=mysql_fetch_row($credit_result))
        {
          if ($credit_amount <= $this_client_owes)
          {
             $update_sql = "UPDATE client_credit SET credit_amount='0', credit_comments='$credit_comments\n\n$credit_amount ~ ".date("$date_format: h:i:s")."', credit_stamp='".mktime()."' WHERE credit_id='$credit_id' ";
             @mysql_query($update_sql,$dbh);
             $this_credit = $this_credit + $credit_amount;
          }
          elseif ($credit_amount > $this_client_owes)
          {
             $new_credit_amount = $credit_amount - $this_client_owes;
             $update_sql = "UPDATE client_credit SET credit_amount='".display_currency($new_credit_amount,1)."', credit_comments='$credit_comments\n\n$credit_amount ~ ".date("$date_format: h:i:s")."', credit_stamp='".mktime()."' WHERE credit_id='$credit_id' ";
             @mysql_query($update_sql,$dbh);
             $this_credit = $this_client_owes;
             $paid_by_credit = TRUE;
          }
        }
    }

    ## COMPUTE THIS CLIENT's TOTALS
    $this_client_owes_pre_tax = $this_client_owes - $this_credit;

    if ($tax_enabled)
    {
       $rows .= "<tr><td colspan=10 align=right>".SF." (".CREDIT."): ".MR.display_currency($this_credit).E;
       $rows .= "<tr><td colspan=10 align=right>".SF.SUBTOTAL.": ".MR."<b>".display_currency($this_client_owes_pre_tax)."</b>".E;
       $tax_due = $this_client_owes_pre_tax * $tax_amount;
       if ($debug) echo "<br><b>$tax_due = $this_client_owes_pre_tax * $tax_amount</b><br>";
       $this_tax = $tax_amount * 100;
       if ($tax_number) $this_tax_number = " (ID: $tax_number)";
       $rows .= "<tr><td colspan=10 align=right>".SF."$this_tax%".TAXDUE." $this_tax_number: ".MR."<b>".display_currency($tax_due)."</b>".E;
       $total_due = $this_client_owes_pre_tax + $tax_due;
       $rows .= "<tr><td colspan=10 align=right>".SF.TOTALDUE.": ".MR."<b>".display_currency($total_due)."</b>".E;
       $invoice_date_paid = ($total_due > 0) ? 0 : mktime() ;
       ## Stats $variable
       if ($do_propay) {
           $sum_first_renewal += $total_due;
       } else {
           $sum_renewed += $total_due;
       }
    }
    else
    {
       $rows .= "<tr><td colspan=10 align=right bgcolor=FFFFFF>".SF.SUBTOTAL.": ".MR.display_currency($this_client_owes).E;
       $rows .= "<tr><td colspan=10 align=right bgcolor=FFFFFF>".SF." (".CREDIT."): ".MR.display_currency($this_credit).E;
       $rows .= "<tr><td colspan=10 align=right bgcolor=FFFFFF>".SF.TOTALDUE.": ".MR."<b>".display_currency($this_client_owes_pre_tax)."</b>".E;
       $invoice_date_paid = ($this_client_owes_pre_tax > 0) ? 0 : mktime() ;
       ## Stats $variable
       if ($do_propay) {
           $sum_first_renewal += $this_client_owes_pre_tax;
       } else {
           $sum_renewed += $this_client_owes_pre_tax;
       }
    }


    ## --- DEBUGGING DATA --- ##
    if ($debug) {
        echo "Sub Total: ".display_currency($this_client_owes)."$debug_br
              Less Credit: ".display_currency($client_credit[0])."$debug_br
              Total Due: ".display_currency($this_client_owes_pre_tax)."$debug_br
              $this_tax%".TAXDUE." $this_tax_number: ".display_currency($tax_due)."
              ".TOTALDUE.": ".display_currency($total_due);
    }

    ## GENERATE INVOICE_SNAPSHOT
    $invoice_snapshot = $table_start.$title_row.$rows.$table_stop;

    ## --- DEBUGGING DATA --- ##
    if ($debug) echo $invoice_snapshot."$debug_br";

    ## FINALLY, DO INVOICE INSERT
    if ($invoice_insert) {
       ## [$dd]    = Due Date
       ## IF Satic = Due on the 15th ($due_on_this_day)
       ## ELSE     = Due 15 days after today
       $dd = ($dd_static) ? $due_on_this_day : date("d")+$due_on_this_day ;
       $invoice_date_due = mktime(0,0,0,date("m")+$z,$dd,date("Y"));
       $this_client_owes = ($tax_enabled) ? $total_due : $this_client_owes_pre_tax ;
       $insert_sql       = "INSERT INTO client_invoice (invoice_id,
                                                        client_id,
                                                        invoice_amount,
                                                        invoice_date_entered,
                                                        invoice_date_due,
                                                        invoice_date_paid,
                                                        invoice_payment_method,
                                                        invoice_snapshot,
                                                        invoice_comments,
                                                        invoice_stamp) VALUES (NULL,
                                                                               '".$client_info['client_id']."',
                                                                               '".str_replace(",","",display_currency($this_client_owes,1))."',
                                                                               '".mktime()."',
                                                                               '$invoice_date_due',
                                                                               '$invoice_date_paid',
                                                                               '".$client_info['billing_method']."',
                                                                               '$invoice_snapshot',
                                                                               '$invoice_comments',
                                                                               '".mktime()."')";
       $result8    = mysql_query($insert_sql,$dbh);
       $invoice_id = (mysql_affected_rows()>0) ? mysql_insert_id() : "[".ERROR."]" ;
       if (!$result8) { echo mysql_errno(). ": ".mysql_error(). "$debug_br"; }

       ## --- DEBUGGING DATA --- ##
       if ($debug) echo htmlentities($insert_sql)."$debug_br";

       ## SEND CUSTOM INVOICE EMAIL
       if ($send_client_email)
       {
           switch($client_info["billing_method"])
           {
                case 1:  $email_id = $cc_email_id;      break;
                case 2:  $email_id = $check_email_id;   break;
                case 3:  $email_id = $cc_email_id;      break;
                case 4:  $email_id = $check_email_id;   break;
                case 5:  $email_id = $paypal_email_id;  break;
                case 6:  $email_id = $worldpay_email_id;break;
                default: $email_id = $check_email_id;   break;
           }
           $email_type     = "invoice";
           $where          = "i.invoice_id = '".mysql_insert_id()."'";
           $email_to[0]    = $client_info['client_id'];
           $email_cc       = $inv_email_cc;
           $email_priority = $inv_email_priority;
           $email_subject  = $inv_email_subject;
           $email_from     = $inv_email_from;
           $email_body     = "%%LEAVE_FOR_ORIGINAL_INVOICE_HERE%%";
           if ($debug) echo SFB."@send_email(".$email_to[0]."$email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);<br>".EF;
           @send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
       }

       ## event_log entry
       $log_comments = INVOICE.": $invoice_id - ".date("$date_format: h:i:s")." - ".$this_admin['admin_realname'];
       log_event($client_info['client_id'],$log_comments,3);

       ## client_register entry
       $reg_desc = GENINVOICE;
       $reg_bill = $this_client_owes;
       register_insert($client_info['client_id'],$reg_desc,$invoice_id,$reg_bill);
    }

## --- DEBUGGING DATA --- ##
if ($debug) echo "-n-e-x-t--c-l-i-e-n-t-$debug_br$debug_br";

} # <-- Go to next client in loop!
$result = mysql_query("OPTIMIZE TABLE client_package,client_invoice,client_register,event_logs");
$opt_results = ($result) ? OPTIMIZEGOOD : OPTIMIZEBAD;
if ($debug) { echo EF; }

## SEND EMAIL RESULTS TO ADMIN
## -------------------------------------
$email_body  = "<html><head></head><body>";
$email_body .= "<TABLE cellSpacing=0 cellPadding=0 width=300 align=left bgcolor=EEEEEE border=0>";
$email_body .= "<TR><TD align=center><b>".LFH.GI.EF."</b></TD></TR>";
$email_body .= "<TR><TD>";
$email_body .= "<TABLE cellSpacing=1 cellPadding=1 bgColor=#ffffff width=100% border=0>";
$email_body .= "<tr><td align=center><table>";
$email_body .= "<tr><td width=33% align=center>".SFB."&nbsp;".EF."</td><td width=33% align=right>".SFB."<b>#</b>".EF."</td><td width=33% align=right>".SFB."<b>".TOTALS."</b>".EF."</td></tr>";
$email_body .= "<tr><td width=33% align=left><nobr>".SFB."<b>".STM.":</b>".EF."</nobr></td><td width=33% align=right>".SFB.$count_thismonth.EF."</td><td width=33% align=right>".SFB."[".NA."]".EF."</td></tr>";
$email_body .= "<tr><td width=33% align=left><nobr>".SFB."<b>".FR.":</b>".EF."</nobr></td><td width=33% align=right>".SFB.$count_first_renewal.EF."</td><td width=33% align=right>".SFB.display_currency($sum_first_renewal).EF."</td></tr>";
$email_body .= "<tr><td width=33% align=left><nobr>".SFB."<b>".RTM.":</b>".EF."</nobr></td><td width=33% align=right>".SFB.$count_renewed.EF."</td><td width=33% align=right>".SFB.display_currency($sum_renewed).EF."</td></tr>";
$count_invoices=$count_first_renewal+$count_renewed;
$sum_invoices=$sum_first_renewal+$sum_renewed;
$email_body ."<tr><td width=33% align=left>".SFB."<b>".TIGEN.":</b>".EF."</td><td width=33% align=right>".SFB.$count_invoices.EF."</td><td width=33% align=right>".SFB.display_currency($sum_invoices).EF."</td></tr>";
$email_body .= "</td></tr></table>";
$email_body .= "<hr size=1 width=98%>";
$email_body .= "<table cellpadding=0 cellspacing=0 border=0 align=center width=100%>";
$email_body .= "<tr><td>".MFB."<b>".STATS.":</b>".EF."</td></tr>";
$email_body .= "<tr><td valign=top>";
$email_body .= SFB.TESS." = <b>";
$email_body .= ($success["sent"]) ? $success["sent"] : 0 ;
$email_body .= "</b>".EF."<br>";
$email_body .= SFB.TENS." = <b>";
$email_body .= ($success["failed"]) ? $success["failed"] : 0 ;
$email_body .= "</b>".EF."<br>";
$email_body .= SFB.$opt_results.EF."<br>";
$email_body .= "</td></tr></table>";
$email_body .= "</td></tr></table>";
$email_body .= "</body></html>";
mail($inv_email_cc,CRONINVOICES.": ".date("$date_format: h:i:s"),$email_body,"From: $inv_email_cc\r\nContent-Type: text/html; charset=".CHARSET."\r\n");
?>