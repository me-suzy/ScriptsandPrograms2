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

start_html();
admin_heading($tile);
start_table(NULL,$a_tile_width,"center","#999999");

$include_path = "include/html/cases";
switch ($tile) {

        // $this_admin[admin_level]
        // ------------------------
        // 9 = God         [Uber Admin, Do All]
        // 8 = Admin       [View All, Config Changes = Yes, No Delete]
        // 7 = Power User  [View All, Config Changes = No, No Delete]
        // 6 = NOT IN USE
        // 5 = Support User [Support Desk & FAQ]
        // 4 = NOT IN USE
        // 3 = NOT IN USE
        // 2 = NOT IN USE
        // 1 = NOT IN USE

   case affiliate:       if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case banned:          if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case billing:         if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case client:          if ($this_admin[admin_level]>=5) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case client_news:     if ($this_admin[admin_level]>=5) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case client_register: if ($this_admin[admin_level]>=5) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case config:          if ($this_admin[admin_level]>=8) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case coupon:          if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case dbexport:        if ($this_admin[admin_level]>=8) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case faq_config:      if ($this_admin[admin_level]>=1) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case mail:            if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case mbsupport:       if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case nettools:        if ($this_admin[admin_level]>=5) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case news:            if ($this_admin[admin_level]>=5) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case package:         if ($this_admin[admin_level]>=5) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case phpinfo:         if ($this_admin[admin_level]>=5) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case reports:         if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case server:          if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case support_desk:    if ($this_admin[admin_level]>=1) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case tld_config:      if ($this_admin[admin_level]>=8) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;
   case todo:            if ($this_admin[admin_level]>=7) { include("$include_path/admin.$tile.case.inc.php"); } else { deny_access(); } break;

   /* --- DASHBOARD ---*/
   default:
   if ($this_admin[admin_level]>=5) {
             if(!$dbh)dbconnect();
             $num_new=$i=$num_cp_start=$sum_cp_start=$num_domains=$num_pending_clients=$num_client_packages=$sum_client_packages=$num_invoices_unpaid=$amount_invoices_unpaid=0;
             $sql="SELECT * FROM whois_stats ORDER BY ws_stamp DESC LIMIT 0,10";
             $result2=mysql_query($sql,$dbh);
             $this_whois_stats  = "<table width=100% cellpadding=1 cellspacing=1 border=0>";
             $this_whois_stats .= "<tr><td>&nbsp;</td><td><b>".SFB.DOMAIN.EF."</b></td><td><b>".SFB.TIMESTAMP.EF."</b></td></tr>";
             if (@mysql_num_rows($result2)==0) $this_whois_stats .= "<tr><td colspan=3 align=left>".SFB."No Records Found".EF."</td></tr>";
             while(list($ws_id,$ws_domain,$ws_qty,$ws_from,$ws_stamp)=@mysql_fetch_array($result2)) {
                $i++;
                $this_whois_stats .= "<tr><td><b>".SFB."$i.".EF."</b></td><td>".SFB."$ws_domain".EF."</td><td>".SFB.date("Y/m/d: h:i:s",$ws_stamp).EF."</td></tr>";
             }
             $this_whois_stats .= "</table>";

   ?>

             <tr>
               <td width=50% valign=top>
                 <?=LFH?><b><?=QUICKSTATS?>:</b><?=EF?><br>
                 <table width=100%>
                   <tr><td colspan=3><hr size=1></td></tr>

                   <? $num_new = mysql_one_data("SELECT count(todo_id) FROM todo_list WHERE todo_status=1") ?>
                   <tr><td><?=SFB?><b><?=TODO.": ".TOTALNEW?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=todo_list&tile=todo&where=<?=urlencode("WHERE todo_status=1")?>><?=$num_new?></a>]<?=EF?></td>
                       <td><?=SFB?>&nbsp;<?=EF?></td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=NEWTODOITEMS?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                   <? $num_pending_clients = mysql_one_data("SELECT count(client_id) FROM client_info WHERE client_status=3") ?>
                   <tr><td><?=SFB?><b><?=CLIENTS.": ".TOTALPENDING?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_info&tile=client&where=<?=urlencode("WHERE client_status=3")?>><?=$num_pending_clients?></a>]<?=EF?></td>
                       <td><?=SFB?>&nbsp;<?=EF?></td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=NEWCLIENTS?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                   <?
                   list($num_cp_start,$sum_cp_start)=mysql_fetch_row(mysql_query("SELECT count(c.client_id),
                                                                                                 sum(p.pack_price) FROM client_package c,
                                                                                                                        package_type p WHERE c.pack_id=p.pack_id AND
                                                                                                                                             c.cp_status=2 AND
                                                                                                                                             ( c.cp_start_stamp >= ".mktime(0,0,0,date("m"),1,date("Y"))." AND
                                                                                                                                               c.cp_start_stamp <= ".mktime(0,0,0,date("m")+1,0,date("Y"))." ) "));
                   ?>
                   <tr><td><?=SFB?><b><?=CLIENTS.": ".NEWPACKAGES?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_package&tile=package&where=<?=urlencode("WHERE cp_status=2 AND ( cp_start_stamp >= ".mktime(0,0,0,date("m"),1,date("Y"))." AND cp_start_stamp <= ".mktime(0,0,0,date("m")+1,0,date("Y"))." )")?>><?=$num_cp_start?></a>]<?=EF?></td>
                       <td><?=SFB?><?=display_currency($sum_cp_start)?><?=EF?></td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=CLIENTPACKS2?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                   <?
                   list($num_client_packages,$sum_client_packages)=mysql_fetch_row(mysql_query("SELECT count(c.client_id),
                                                                                                 sum(p.pack_price) FROM client_package c,
                                                                                                                        package_type p WHERE c.pack_id=p.pack_id AND
                                                                                                                                             c.cp_status=2 AND
                                                                                                                                             ( c.cp_renew_stamp >= ".mktime(0,0,0,date("m"),1,date("Y"))." AND
                                                                                                                                               c.cp_renew_stamp <= ".mktime(0,0,0,date("m")+1,0,date("Y"))." ) "));
                   ?>
                   <tr><td><?=SFB?><b><?=CLIENTS.": ".RENEWPACKAGES?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_package&tile=package&where=<?=urlencode("WHERE cp_status=2 AND ( cp_renew_stamp >= ".mktime(0,0,0,date("m"),1,date("Y"))." AND cp_renew_stamp <= ".mktime(0,0,0,date("m")+1,0,date("Y"))." )")?>><?=$num_client_packages?></a>]<?=EF?></td>
                       <td><?=SFB?><?=display_currency($sum_client_packages)?><?=EF?></td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=CLIENTPACKS?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                   <? $num_domains = mysql_one_data("SELECT count(client_id) FROM domain_names") ?>
                   <tr><td><?=SFB?><b><?=CLIENTS.": ".TTLDOMS?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=domain_names&tile=client><?=$num_domains?></a>]<?=EF?></td>
                       <td><?=SFB?><a href=<?=$page?>?op=menu&tile=reports><?=SEEDETAILS?></a><?=EF?></td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=DOMAINSTATSSEE?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                   <?
                   // v3.0.9 - Make sure $0.00 invoices are excluded.
                   list($num_invoices_unpaid,$amount_invoices_unpaid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount-invoice_amount_paid) FROM client_invoice WHERE invoice_amount > invoice_amount_paid"));
                   ?>
                   <tr><td><?=SFB?><b><?=INVOICES.": ".TOTALDUE?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=client_invoice&tile=billing&where=<?=urlencode("WHERE invoice_amount > invoice_amount_paid")?> ><?=$num_invoices_unpaid?></a>]<?=EF?></td>
                       <td><?=SFB?><?=display_currency($amount_invoices_unpaid)?><?=EF?></td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=APPLYPAYMENTSOR?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                  <? $aff_hits = mysql_one_data("SELECT sum(aff_hits) FROM affiliate_config WHERE aff_status = 2"); ?>
                  <tr><td><?=SFB?><b><?=AFFILIATES?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=affiliate_config&tile=affiliate&where=<?=urlencode("WHERE aff_status = 2 ")?>><?=($aff_hits)?$aff_hits:0;?></a>]<?=EF?></td>
                       <td>&nbsp;</td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=TOTALAFFILIATEHITS?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                  <? $coupon_hits = mysql_one_data("SELECT sum(coupon_count) FROM coupon_codes WHERE coupon_status = 2"); ?>
                  <tr><td><?=SFB?><b><?=COUPONS?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=view&db_table=coupon_codes&tile=coupon&where=<?=urlencode("WHERE coupon_status = 2 ")?>><?=($coupon_hits)?$coupon_hits:0;?></a>]<?=EF?></td>
                       <td>&nbsp;</td>
                   </tr>
                   <tr><td colspan=3><?=SFB?><?=TOTALCOUPONHITS?><?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>


                  <? list($reg_count,$account_balance)=mysql_fetch_row(mysql_query("SELECT COUNT(reg_id), SUM(reg_payment)-SUM(reg_bill) FROM client_register")); ?>
                  <tr><td><?=SFB?><b><?=REGISTERBALANCE?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=menu&db_table=client_register&tile=client_register><?=$reg_count?></a>]<?=EF?></td>
                       <td><?=SFB.display_currency($account_balance).EF?></td>
                   </tr>
                   <tr><td colspan=3><?=SFB?>&nbsp;<?=EF?></td></tr>
                   <tr><td colspan=3><hr size=1></td></tr>

                  <?
                  $result = mysql_query("SELECT call_status, COUNT(*) FROM support_desk GROUP BY call_status");
                  while(list($call_status,$call_num)=mysql_fetch_array($result))
                        $call_stats[$call_status] = $call_num;
                  ?>
                  <tr><td><?=SFB?><b><?=SUPPORTDESK?>:</b><?=EF?></td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=menu&tile=support_desk&type=view&id=1><?=($call_stats[1])?$call_stats[1]:0;?></a>]<?=EF?></td>
                       <td><?=SFB.NEW_t.EF?></td>
                   </tr>
                  <tr><td>&nbsp;</td>
                       <td><?=SFB?>[<a href=<?=$page?>?op=menu&tile=support_desk&type=view&id=2><?=($call_stats[2])?$call_stats[2]:0;?></a>]<?=EF?></td>
                       <td><?=SFB.OPEN.EF?></td>
                   </tr>
                   <tr><td colspan=3><hr size=1></td></tr>

                  </table>
               </td>
               <td width=50% valign=top>
                  <?
                  start_box(NEWTHISWEEK);
                  // w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)
                  $sun = mktime(0,0,0,date("m"),date("d")-date("w")+0,date("Y"));
                  $mon = mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"));
                  $tue = mktime(0,0,0,date("m"),date("d")-date("w")+2,date("Y"));
                  $wed = mktime(0,0,0,date("m"),date("d")-date("w")+3,date("Y"));
                  $thr = mktime(0,0,0,date("m"),date("d")-date("w")+4,date("Y"));
                  $fri = mktime(0,0,0,date("m"),date("d")-date("w")+5,date("Y"));
                  $sat = mktime(0,0,0,date("m"),date("d")-date("w")+6,date("Y"));
                  for($i=1;$i<=7;$i++)
                  {
                      $time = mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"));
                      $time_plus_one = mktime(0,0,0,date("m"),date("d")-date("w")+$i,date("Y"));
                      $sql = "SELECT count(cp_id)
                              FROM client_package
                              WHERE ( cp_start_stamp >= $time AND cp_start_stamp < $time_plus_one )";
                      list($num) = mysql_fetch_row(mysql_query($sql));
                      //Monday:10|Tuesday:10|Wednesday:10|Thursday:10|Friday:10
                      switch ($i) {
                         case 1: $tempdata .= SUN." ".date($date_format,mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"))); break;
                         case 2: $tempdata .= MON." ".date($date_format,mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"))); break;
                         case 3: $tempdata .= TUE." ".date($date_format,mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"))); break;
                         case 4: $tempdata .= WED." ".date($date_format,mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"))); break;
                         case 5: $tempdata .= THR." ".date($date_format,mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"))); break;
                         case 6: $tempdata .= FRI." ".date($date_format,mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"))); break;
                         case 7: $tempdata .= SAT." ".date($date_format,mktime(0,0,0,date("m"),date("d")-date("w")+$i-1,date("Y"))); break;
                      }
                      $tempdata .= ":$num|";
                  }
                  ?>
                  <?=print_graph(substr($tempdata,0,-1),NULL,200,10)?>
                  <?=stop_box()?>
                  <br>
                  <hr size=1>
                  <br>

                  <?PHP
                  $fontfamily       = isset($reg_face) ? $reg_face : "Tahoma, Geneva, sans-serif";
                  $defaultfontcolor = isset($defaultfontcolor) ? $defaultfontcolor : "#000000";
                  $defaultbgcolor   = isset($defaultbgcolor) ? $defaultbgcolor : "#FFFFFF";
                  $todayfontcolor   = isset($todayfontcolor) ? $todayfontcolor : "#FFFFFF";
                  $todaybgcolor     = isset($todaybgcolor) ? $todaybgcolor : "#CC0000";
                  $monthcolor  = isset($monthcolor) ? $monthcolor : "#333399";
                  $relfontsize = isset($relfontsize) ? $relfontsize : "1";
                  $cssfontsize = isset($cssfontsize) ? $cssfontsize : "8pt";
                  $month       = (isset($month)) ? $month : date("n",time());
                  $monthnames  = array(M_JANUARY,M_FEBRUARY,M_MARCH,M_APRIL,M_MAY,M_JUNE,M_JULY,M_AUGUST,M_SEPTEMBER,M_OCTOBER,M_NOVEMBER,M_DECEMBER);
                  $textmonth   = $monthnames[$month - 1];
                  $year        = (isset($year)) ? $year : date("Y",time());
                  $today       = (isset($today))? $today : date("j", time());  ## Make $today really big to avoid hilite
                  $today       = ($month == date("n",time())) ? $today : 32;
                  if ( (($month < 8) && ($month % 2 == 1)) || (($month > 7) && ($month % 2 == 0)) )
                  $days = 31;
                  if ( (($month < 8) && ($month % 2 == 0)) || (($month > 7) && ($month % 2 == 1)) )
                  $days = 30;
                  if ($month == 2)
                  $days = (date("L",time())) ? 29 : 28;

                  $dayone = date("w",mktime(1,1,1,$month,1,$year));
                  $daylast = date("w",mktime(1,1,1,$month,$days,$year));

                  start_box($textmonth." ".date("Y"));
                  ?>
                  <table border="0" cellpadding="2" cellspacing="2" width="350" align=center>
                  <tr>
                  <td bgcolor="<?PHP echo $defaultbgcolor ?>" valign="middle" align="center" width="15%"><font face="<?PHP echo $fontfamily ?>" size="1"><b><?=SUN?></b></font></td>
                  <td bgcolor="<?PHP echo $defaultbgcolor ?>" valign="middle" align="center" width="14%"><font face="<?PHP echo $fontfamily ?>" size="1"><b><?=MON?></b></font></td>
                  <td bgcolor="<?PHP echo $defaultbgcolor ?>" valign="middle" align="center" width="14%"><font face="<?PHP echo $fontfamily ?>" size="1"><b><?=TUE?></b></font></td>
                  <td bgcolor="<?PHP echo $defaultbgcolor ?>" valign="middle" align="center" width="14%"><font face="<?PHP echo $fontfamily ?>" size="1"><b><?=WED?></b></font></td>
                  <td bgcolor="<?PHP echo $defaultbgcolor ?>" valign="middle" align="center" width="14%"><font face="<?PHP echo $fontfamily ?>" size="1"><b><?=THR?></b></font></td>
                  <td bgcolor="<?PHP echo $defaultbgcolor ?>" valign="middle" align="center" width="14%"><font face="<?PHP echo $fontfamily ?>" size="1"><b><?=FRI?></b></font></td>
                  <td bgcolor="<?PHP echo $defaultbgcolor ?>" valign="middle" align="center" width="15%"><font face="<?PHP echo $fontfamily ?>" size="1"><b><?=SAT?></b></font></td>
                  </tr>
                  <?PHP
                  $defaultbgcolor = $cell_color_1;
                  if($dayone != 0)
                  $span1 = $dayone;
                  if(6 - $daylast != 0)
                  $span2 = 6 - $daylast;

                  for($i = 1; $i <= $days; $i++):
                  $dayofweek = date("w",mktime(1,1,1,$month,$i,$year));
                  $width = "14%";

                  if($dayofweek == 0 || $dayofweek == 6)
                  $width = "15%";

                  if($i == $today):
                  $fontcolor = $todayfontcolor;
                  $bgcellcolor = $todaybgcolor;
                  endif;
                  if($i != $today):
                  $fontcolor = $defaultfontcolor;
                  $bgcellcolor = $defaultbgcolor;
                  endif;

                  if($i == 1 || $dayofweek == 0):
                  $bgcellcolor = $defaultbgcolor = ($defaultbgcolor==$cell_color_1) ? $cell_color_2 : $cell_color_1 ;
                  echo " <tr bgcolor=\"$defaultbgcolor\">\n";
                  if($span1 > 0 && $i == 1)
                  echo "  <td align=\"left\" bgcolor=\"$defaultbgcolor\" colspan=\"$span1\"><font face=\"null\" size=\"1\">&nbsp;</font></td>\n";
                  endif;

                  list($num) = mysql_fetch_row(mysql_query("SELECT count(cp_id) FROM client_package WHERE ( cp_start_stamp >= ".mktime(0,0,0,date("m"),$i,date("Y"))." AND cp_start_stamp < ".mktime(0,0,0,date("m"),$i+1,date("Y"))." )"));
                  $total_signups += $num;
                  $num = ($num) ? "<b>$num</b>" : "-";
                  ?>
                  <td bgcolor="<?PHP echo $bgcellcolor ?>" valign="middle" align="center" width="<?PHP echo $width ?>"><font color="<?PHP echo $fontcolor ?>" face="<?PHP echo $fontfamily ?>" size="1"><?="$i: [$num]"?></font></td>
                  <?PHP
                  if($i == $days):
                  if($span2 > 0)
                  echo "  <td align=\"left\" bgcolor=\"$defaultbgcolor\" colspan=\"$span2\"><font face=\"null\" size=\"1\">&nbsp;</font></td>\n";
                  endif;
                  if($dayofweek == 6 || $i == $days):
                  echo " </tr>\n";
                  endif;

                  $average = $total_signups/$i;
                  endfor;
                  ?>
                  </table>
                  <br><center><B><?=SFB?><?=TOTAL?>: <?=$total_signups?> | <?=AVERAGE?>: <?=number_format($average,2,'.','')?><?=EF?></B></center>
                  <?=stop_box()?>

                  <br>
                  <hr size=1>
                  <br>

                  <?=start_box(WHOISSTATS)?>
                  <?=$this_whois_stats?>
                  <center><?=SFB?>[<a href=<?=$page?>?op=view&db_table=whois_stats&tile=reports><?=VIEWALL?></a>]&nbsp;[<a href=<?=$page?>?op=delete_whois><?=DELETEALL?></a>]<?=EF?></center>
                  <?=stop_box()?>
               </td>
             </tr>

   <?
   } else {
     deny_access();
   }
   break;
}
stop_table();
stop_html();
?>