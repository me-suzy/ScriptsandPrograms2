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

## Validate that the user
if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }


        switch ($tile) {
           case myinvoices:
                $db_table="client_invoice";
                if ($id=="due")  {
                    $where = " WHERE client_id=$this_user[0] AND ( invoice_date_paid = 0 OR invoice_amount > invoice_amount_paid ) ";
                } elseif ($id=="paid") {
                    $where = " WHERE client_id=$this_user[0] AND ( invoice_amount <= invoice_amount_paid ) ";
                } elseif ($id=="partial") {
                    $where = " WHERE client_id=$this_user[0] AND ( invoice_amount > invoice_amount_paid ) ";
                } elseif ($where||$id=="all") {
                    $where = " WHERE client_id=$this_user[0] ";
                }
           break;

           case mypackages:
                $db_table="client_package";
                $where  = ($where)  ? " ".urldecode($where)." AND client_id=$this_user[0] " : NULL ;
                $use_user_select = TRUE;
           break;

           case mydomains:
                $db_table="domain_names";
           break;
        }
        $where = ($where) ? $where : " WHERE client_id=$this_user[0] " ;

        //$recursive = 1;
        include("include/db_attributes.inc.php");
        validate_table($db_table,1); if(isset($error)) return;

        if ($search) {
            if ($query!="") {
              list($column_query,$date)=explode("|",$column_query);
              if ($date=="date") { // &&eregi("([0-9]{4})/([0-9]{2})",$query)
                  list($year,$month) = explode("/",$query);
                  $first_day_stamp   = mktime(0,0,0,$month,1,$year);
                  $last_day_stamp    = mktime(0,0,0,$month+1,-1,$year);
                  $where            .= " AND ( $column_query<=$last_day_stamp AND $column_query>=$first_day_stamp ) ";
              } elseif ($date=="id") { //&&eregi("([0-9])",$query)
                  $where            .= " AND $column_query=".strip_tags($query)." ";
              } else {
                  $where            .= " AND $column_query LIKE '%".strip_tags($query)."%' ";
              }
              $select_sql = $select_sql.$where;
              $result = mysql_query($select_sql,$dbh);
              $num = ($result) ? mysql_num_rows($result) : 0 ;
            } else {
              $search=NULL; # <-- Remove the $serch variable if the $query is empty
            }

        }
        if ($num == 1 && $date == "id") {
            $url = "$page?op=$details_link&db_table=$db_table&tile=$tile&id=$column_query|".strip_tags($query)."&".session_id() ;
            Header("Location: $url");
            exit;
        } else {
            start_html();
            user_heading($tile);
            start_table(NULL,$u_tile_width,"center","#999999");
                 echo "<tr><td>";
                 display_list($args,$select_sql,$where,$db_table,$order,$sort,NULL,NULL);
                 echo "</td></tr>";
                 echo "<tr><td>";
                 PieceNavigation($db_table, $limit, $where);
                 echo "</td></tr>";
            stop_table();
            stop_html();
        }
?>