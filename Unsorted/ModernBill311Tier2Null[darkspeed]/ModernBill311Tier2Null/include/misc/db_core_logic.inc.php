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

##
## build_form:   By reading an associative array of variables, we can
##               control the look and feel of EVERY form dynamically!
##
function build_form($args,$result=0)
{
  GLOBAL $db_table,
         $op,
         $details_view,
         $do,
         $HTTP_POST_VARS,
         $from,
         $this_user,
         $cell_color_2,
         $cell_color_1,
         $default_bgcolor,
         $feature_name;
  // add custom views to if statement
  $details_view = ( ($details_view) || ($op=="view"||$op=="reports"||$op=="details"||$op=="client_details") ) ? 1 : 0 ;

  // Define Fields with Values from $result
  if($result){
    $fields = mysql_num_fields($result);
    $myrow = mysql_fetch_row($result);
    for($index = 0; $index < $fields; $index++){
       ${mysql_field_name($result,$index)}=$myrow[$index];
    }
    // Reset $args
    include("include/db_attributes.inc.php");
  }

  // strip METHOD=POST form variables
  if(is_array($HTTP_POST_VARS)) {
   reset($HTTP_POST_VARS);
   while (list($key, $val) = each($HTTP_POST_VARS)) {
          if (is_array($val)) {
             while (list($akey,$aval) = each($val)) {
                  $HTTP_POST_VARS[$key][$akey] = strip_tags($aval);
                  ${$key}[$akey] = strip_tags($aval);
             }
          } else {
             $HTTP_POST_VARS[$key] = strip_tags($val);
             ${$key} = strip_tags($val);
             $key=${$key};
          }
   }
  }

  // Build form from fields defined in $args

  $i=0;
  foreach ($args as $value) {
    if( ($do=="add"&&$args[$i]["no_add"]==1) ||
        ($do=="edit"&&$args[$i]["no_edit"]==1) ||
        ( ($op=="details"||$op=="client_details") && $args[$i]["no_details"]==1 ) ||
        ( ($this_user && $args[$i]["admin_only"]==1) ) ) { // this logic is foobar
    } else {

    $alt_row1 = $cell_color_1;
    $alt_row2 = $cell_color_2;

    if ($args[$i]["type"]!="HIDDEN") {
        $row_bgcolor = ($row_bgcolor==$alt_row2) ? $alt_row2 : $alt_row1 ;
        echo "<tr bgcolor=$row_bgcolor>";
        $row_bgcolor = ($row_bgcolor==$alt_row1) ? $alt_row2 : $alt_row1 ;
    }

    ${$args[$i]["column"]} = (!${$args[$i]["column"]}&&$args[$i]["default_value"]) ? $args[$i]["default_value"] : ${$args[$i]["column"]} ;
    ${$args[$i]["column"]} = (${$args[$i]["column"]}==""&&$details_view) ? "[".EMPTY_t."]" : ${$args[$i]["column"]} ;

    switch ($args[$i]["type"]) {

      case HEADERROW:
           echo "<td colspan=2 align=center bgcolor=$default_bgcolor><b>". MFHR . $args[$i]["title"] . EF ."</b></td>";
           $row_bgcolor=$cell_color_1;
      break;

      case TEXT:
           echo "<td align=right width=35%><b>". SFB . $args[$i]["title"] . EF .":</b></td>";
           if($details_view)
           {
              if ($args[$i]["column"]=="batch_stamp")
              {
                  echo "<td>".SFB.stamp_to_date($batch_stamp).EF."</td>"; # <-- HACK
              }
              else
              {
                  if ($args[$i]["swatch"])
                  {
                     echo "<td valign=middle><table width=100% cellpadding=0 cellspacing=0><tr><td width=15%>".SFB.${$args[$i]["column"]}.EF."</td><td algin=left>".make_swatch(${$args[$i]["column"]})."</td></tr></table></td>";
                  }
                  else
                  {
                     echo "<td valign=middle>".SFB.${$args[$i]["column"]}.EF."</td>";
                  }
              }
           }
           else
           {
              if ($args[$i]["swatch"])
              {
                 echo "<td valign=middle><table width=100% cellpadding=0 cellspacing=0><tr><td width=15%><input type=text name=\"".$args[$i]["column"]."\" value=\"".${$args[$i]["column"]}."\" size=\"".$args[$i]["size"]."\" maxlength=\"".$args[$i]["maxlength"]."\"></td><td algin=left>".make_swatch(${$args[$i]["column"]})."</td></tr></table>".SFB.$args[$i]["append"].EF."</td>";
              }
              else
              {
                 echo "<td valign=middle><input type=text name=\"".$args[$i]["column"]."\" value=\"".${$args[$i]["column"]}."\" size=\"".$args[$i]["size"]."\" maxlength=\"".$args[$i]["maxlength"]."\"> ".SFB.$args[$i]["append"].EF."</td>";
              }
           }
      break;

      case HIDDEN:
           echo "<input type=hidden name=\"".$args[$i]["column"]."\" value=\"".${$args[$i]["column"]}."\">";
      break;

      case PASSWORD:
           echo "<td align=right width=35%><b>". SFB . $args[$i]["title"] . EF .":</b></td>";
           if($details_view){
           echo "<td>".SFB.${$args[$i]["column"]}.EF."</td>";
           } else {
           echo "<td><input type=hidden   name=\"".$args[$i]["column"]."\" value=\"this_".${$args[$i]["column"]}."\">
                     <input type=password name=\"".$args[$i]["column"]."\"
                                          size=\"".$args[$i]["size"]."\"
                                          maxlength=\"".$args[$i]["maxlength"]."\"> ". SFB .$args[$i]["append"]. EF ."</td>";
           }
      break;

      case TEXTAREA:
           echo "<td align=right width=35%><b>". SFB .nl2br($args[$i]["title"]). EF .":</b></td>";
           if($details_view){
           echo "<td>".SFB.nl2br(${$args[$i]["column"]}).EF."</td>";
           } else {
           echo "<td><textarea name=\"".$args[$i]["column"]."\"
                               rows=\"".$args[$i]["rows"]."\"
                               cols=\"".$args[$i]["cols"]."\"
                               maxlength=\"".$args[$i]["maxlength"]."\">".${$args[$i]["column"]}."</textarea><br>". SFB .$args[$i]["append"]. EF ."</td>";
           }
      break;

      case FUNCTION_CALL:
           echo "<td align=right width=35%><b>". SFB . $args[$i]["title"] . EF .":</b></td>";
           if($details_view||$from==$args[$i]["column"]){
             if ($from) {
               $this_function = ($args[$i]["nl2br"]) ? nl2br($args[$i]["function_call"]) : $args[$i]["function_call"] ;
               echo "<td>".MFB."<b>".$this_function."</b>".EF."<input type=hidden name=from value=$from></td>";
             } else {
               $this_function = ($args[$i]["nl2br"]) ? nl2br($args[$i]["function_call"]) : $args[$i]["function_call"] ;
               echo "<td>".SFB.$this_function.EF."</td>";
             }
           } else {
             echo "<td>".$args[$i]["function_call"]." ". SFB .$args[$i]["append"]. EF ."</td>";
           }
      break;

    }
    if ($args[$i]["type"]!="HIDDEN") echo "</tr>";
    } // end edit && no_details
  $i++;
  }
}

##
## display_list: By reading an associative array of variables, we can
##               control the look and feel of EVERY view dynamically!
##
function display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit)
{
        GLOBAL $crazy,
               $this_num_results,
               $tile,
               $print,
               $search,
               $this_admin,
               $this_user,
               $query,
               $select_order,
               $column_query,
               $op,
               $id,
               $details_view,
               $details_link_old,
               $dbh,
               $debug,
               $recursive,
               $selectlimit,
               $cuttext_off,
               $cellspacing,
               $cellpadding,
               $head_bgcolor,
               $cell_color_2,
               $cell_color_1,
               $offset,
               $limit,
               $page,
               $details_link,
               $suppress_reload,
               $display_checkboxes,
               $use_user_select,
               $domain_whois_server_for_hotlink;
          // add custom views to if statement
          $details_view = ( ($details_view) || ($op=="view"||$op=="reports"||$op=="details"||$op=="client_details") ) ? 1 : 0 ;

        if (!$search&&!$suppress_reload) include("include/db_attributes.inc.php");

        $limit       = $selectlimit;
        $order       = (!$order&&$select_order) ? $select_order : $order ;
        $sort        = (!$sort) ? "ASC" : $sort ;
        $select_sql .= ($where&&!$search) ? " ".str_replace("\\",NULL,stripslashes(urldecode($where)))." " : NULL ; // WHERE is passed in, not via SEARCH
        $select_sql .= ($order) ? "ORDER BY $order $sort " : "" ;
        $this_num_results = mysql_num_rows(mysql_query($select_sql,$dbh));
        $offset      = ($offset=="") ? 0 : $offset ;
        $select_sql .= (!$recursive||$selectlimit) ? "LIMIT $offset,$limit" : NULL ;
        $this_sort   = $sort;
        // Debugging Info  if($debug)
        if($debug)echo SFB ."in function->". $select_sql . EF;

        // Select Query
        addslashes($result = mysql_query($select_sql,$dbh));
        $fields = ($result) ? mysql_num_fields($result) : 0 ;
        $num = ($result) ? mysql_num_rows($result)   : 0 ;

        // Start Inside Table
        if(isset($query)&&isset($column_query)) {
           $query_clean = ($query=="") ? NOTHINGENTERED : strip_tags($query) ;
           if ($num) {
               echo SFB."<center><b>$num ".MATCHESFOR." [".KEYWORDS.": <i>\"$query_clean\"</i>].</b></center><br>".EF;
           } else {
                echo "<br><center>".SFB;
                switch ($tile) {
                        case mypackages: echo NOPACKSEARCH." [".KEYWORDS.": <i>\"$query_clean\"</i>]"; break;
                        case myinvoices: echo NOINVSEARCH." [".KEYWORDS.": <i>\"$query_clean\"</i>]"; break;
                        case mydomains:  echo NODOMSEARCH." [".KEYWORDS.": <i>\"$query_clean\"</i>]"; break;
                        default: echo SFB."<center><b>$num ".MATCHESFOUND." [".KEYWORDS.": <i>\"$query_clean\"</i>].</b></center><br>".EF; break;
                }
                echo EF."</center>";
                if(!$recursive) { echo "<br><center>".MFB; go_back(); echo EF."</center>"; }
           }

        }

        echo "<table border=0 width=\"100%\" cellspacing=$cellspacing cellpadding=$cellpadding align=center>";
        if($num==0) {
           if ($where) $num_where = " [".urldecode($where)."]";
           if (!$query&&!$column_query)
           {
                echo "<tr><td align=center>".SFB;
                switch ($tile) {
                        case mypackages: echo NOPACKFOUND; break;
                        case myinvoices: echo NOINVFOUND; break;
                        case mydomains:  echo NODOMFOUND; break;
                        default:         echo NORECFOUND." [$db_table]$num_where."; break;
                }
                echo EF."</td></tr>";
                if(!$recursive) { echo "<tr><td align=center>".MFB; go_back(); echo EF."</td></tr>"; }
           }
        } else {
          // Alternate SORT ORDER
          $sort=($sort=="ASC")?"DESC":"ASC";

          // Start Each Row
          $title_row="<tr>";

          if ($display_checkboxes) $title_row.="<td>&nbsp;</td>";

          // Header Row
          for($index = 0; $index < $fields; $index++){

               // Set Real Field Name

               // Start <td>
               $title_row.="<td nowrap valign=middle bgcolor=$head_bgcolor>".SFB;
               $title_row.= (!$recursive) ? "<a href=\"$page?".
                                             "op=$op&".
                                             "db_table=$db_table&".
                                             "order=".mysql_field_name($result,$index)."&".
                                             "sort=$sort&".
                                             "offset=$offset&".
                                             "id=$id&".
                                             "tile=$tile&".
                                             "print=$print&".
                                             "where=".stripslashes(urlencode($where))."\">" : "" ;

               // Which Sort Method?
               if ($order == mysql_field_name($result,$index)){
                   $title_row.=($this_sort=="ASC") ? ASC_IMG : DESC_IMG ;
               }

               // Lookup Column Name as Defined in $arg Array
               $i=0;
               reset($args);
               foreach ($args as $value) {
                        if ($args[$i]["column"]==mysql_field_name($result,$index)) {
                            $sql_field = $args[$i]["title"];
                            break;
                        }
               $i++;
               }

               // End </td>
               $title_row .= (!$recursive) ? $sql_field."</a>".EF."</td>" : $sql_field.EF."</td>" ;

               // Reset variable
               $sql_field = NULL;

          }
          $action     = ($op=="make_payments") ? "<nobr>".MAKEPAYMENTS."</nobr>" : ACTION ;
          $title_row .= (!$recursive&&$this_admin&&!$this_user) ? "<td align=center colspan=2 width=5%>".SFB.$action.EF."</td></tr>" : "" ;

          // Display Header Row
          echo $title_row;

          // Start Field Iteration
          while ($myrow = mysql_fetch_array($result)){

               // alternating bgcolor in TRs
               $color = ($color==$cell_color_1)?$cell_color_2:$cell_color_1;

               // Start Each Row
               echo "<TR bgcolor=$color>";

               if ($display_checkboxes) echo "<td><input type=checkbox name=check_id[] value=$myrow[0]></td>";

               // first TD contains linked ID to VIEW page
               $details_op = ($details_link) ? $details_link : "details" ;
               echo "<td valign=middle>".SFB."<a href=\"$page?op=$details_op&db_table=$db_table&tile=$tile&print=$print&id=".mysql_field_name($result,0)."|$myrow[0]\">". $myrow[0] ."</a>".EF."</td>";

               switch($db_table)
               {
               case client_info:
                    for($index = 1; $index < $fields; $index++) { ${mysql_field_name($result,$index)}=$myrow[$index]; }
                    echo "<td nowrap>$client_fname</td>
                          <td nowrap>$client_lname</td>
                          <td nowrap><a href=mailto:$client_email>$client_email</a></td>
                          <td nowrap>$client_company</td>
                          <td nowrap>$client_phone1</td>
                          <td nowrap>".status_select_box($client_status,"client_status")."</td>";
                    if (!$recursive&&$this_admin&&!$this_user)
                    {
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".EDIT_IMG."</a>".EF."</td>";
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".DELETE_IMG."</a>".EF."</td>";
                    }

               break;

               case client_package:
                    for($index = 1; $index < $fields; $index++) { ${mysql_field_name($result,$index)}=$myrow[$index]; }
                    if ($use_user_select) {
                    echo "<td nowrap><a href=$page?op=details&tile=$tile&db_table=package_type&tile=$tile&print=$print&id=pack_id|$pack_id>".package_select_box($pack_id,$cp_billing_cycle)."</a> ".map_domains($myrow[0],1)."</td>
                          <td nowrap align=right>".display_currency($pack_price)."</td>
                          <td nowrap>$cp_qty</td>
                          <td nowrap>$cp_discount</td>
                          <td nowrap>".stamp_to_date($cp_start_stamp)."</td>
                          <td nowrap>".stamp_to_date($cp_renew_stamp)."</td>
                          <td nowrap>".stamp_to_date($cp_renewed_on)."</td>
                          <td nowrap>".cycle_select_box($cp_billing_cycle)."</td>
                          <td nowrap>".status_select_box($cp_status,"cp_status")."</td>";
                    } elseif ($recursive) {
                    echo "<td nowrap><a href=$page?op=details&tile=$tile&db_table=package_type&tile=$tile&print=$print&id=pack_id|$pack_id>".package_select_box($pack_id,$cp_billing_cycle)."</a> ".map_domains($myrow[0],1)."</td>
                          <td nowrap align=right>".display_currency($pack_price)."</td>
                          <td nowrap><a href=$page?op=details&tile=$tile&db_table=client_package&tile=$tile&print=$print&id=cp_id|$parent_cp_id>$parent_cp_id</a></td>
                          <td nowrap>$cp_qty</td>
                          <td nowrap>$cp_discount</td>
                          <td nowrap>".stamp_to_date($cp_start_stamp)."</td>
                          <td nowrap>".stamp_to_date($cp_renew_stamp)."</td>
                          <td nowrap>".stamp_to_date($cp_renewed_on)."</td>
                          <td nowrap>".cycle_select_box($cp_billing_cycle)."</td>
                          <td nowrap>".status_select_box($cp_status,"cp_status")."</td>
                          <td nowrap>".affiliate_select_box($aff_code,"aff_code")."</td>
                          <td nowrap>".stamp_to_date($aff_last_paid)."</td>";
                    } else {
                    echo "<td nowrap><a href=$page?op=client_details&tile=$tile&db_table=client_info&tile=$tile&print=$print&id=client_id|$client_id>".client_select_box($client_id)."</a></td>
                          <td nowrap>".package_select_box($pack_id,$cp_billing_cycle).map_domains($myrow[0],1)."</td>
                          <td nowrap>".stamp_to_date($cp_start_stamp)."</td>
                          <td nowrap>".stamp_to_date($cp_renew_stamp)."</td>
                          <td nowrap>".stamp_to_date($cp_renewed_on)."</td>
                          <td nowrap>".cycle_select_box($cp_billing_cycle)."</td>
                          <td nowrap>".status_select_box($cp_status,"cp_status")."</td>
                          <td nowrap>".affiliate_select_box($aff_code,"aff_code")."</td>
                          <td nowrap>".stamp_to_date($aff_last_paid)."</td>";
                    }
                    if (!$recursive&&$this_admin&&!$this_user)
                    {
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".EDIT_IMG."</a>".EF."</td>";
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".DELETE_IMG."</a>".EF."</td>";
                    }

               break;

               case client_invoice:
                    for($index = 1; $index < $fields; $index++) { ${mysql_field_name($result,$index)}=$myrow[$index]; }
                    if ($recursive)
                    {
                    echo "<td nowrap align=right>".display_currency($invoice_amount)."</td>
                          <td nowrap align=right>".display_currency($invoice_amount_paid)."</td>
                          <td nowrap align=right>".display_currency($due)."</td>
                          <td nowrap>".stamp_to_date($invoice_date_entered)."</td>
                          <td nowrap>".stamp_to_date($invoice_date_due)."</td>
                          <td nowrap>".stamp_to_date($invoice_date_paid)."</td>
                          <td nowrap>".payment_select_box($invoice_payment_method)."</td>
                          <td nowrap>$auth_return</td>
                          <td nowrap>$auth_code</td>
                          <td nowrap>$avs_code</td>
                          <td nowrap>$trans_id</td>
                          <td nowrap>".stamp_to_date($batch_stamp)."</td>";
                    }
                    else
                    {
                    echo "<td nowrap><a href=$page?op=client_details&tile=$tile&db_table=client_info&tile=$tile&print=$print&id=client_id|$client_id>".client_select_box($client_id)."</a></td>
                          <td nowrap align=right>".display_currency($invoice_amount)."</td>
                          <td nowrap align=right>".display_currency($invoice_amount_paid)."</td>
                          <td nowrap align=right>".display_currency($due)."</td>
                          <td nowrap>".stamp_to_date($invoice_date_entered)."</td>
                          <td nowrap>".stamp_to_date($invoice_date_due)."</td>
                          <td nowrap>".stamp_to_date($invoice_date_paid)."</td>
                          <td nowrap>".payment_select_box($invoice_payment_method)."</td>
                          <td nowrap align=center>$auth_return</td>
                      <!--<td nowrap>$auth_code</td>-->
                      <!--<td nowrap>$avs_code</td>-->
                      <!--<td nowrap>$trans_id</td>-->
                          <td nowrap>".stamp_to_date($batch_stamp)."</td>";
                    }
                    if (!$recursive&&$this_admin&&!$this_user)
                    {
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".EDIT_IMG."</a>".EF."</td>";
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".DELETE_IMG."</a>".EF."</td>";
                    }

               break;

               case authnet_batch:
                    for($index = 1; $index < $fields; $index++) { ${mysql_field_name($result,$index)}=$myrow[$index]; }
                    echo "<td nowrap><a href=$page?op=client_invoice&tile=$tile&db_table=client_invoice&tile=$tile&print=$print&id=invoice_id|$x_Invoice_Num>$x_Invoice_Num</a></td>
                          <td nowrap>$x_Amount</td>
                          <td nowrap>$x_Type</td>
                          <td nowrap><a href=$page?op=client_details&tile=$tile&db_table=client_info&tile=$tile&print=$print&id=client_id|$x_Cust_ID>$x_Cust_ID</a></td>
                          <td nowrap>$x_First_Name</td>
                          <td nowrap>$x_Last_Name</td>
                          <td nowrap><a href=mailto:$x_Email>$x_Email</a></td>
                          <td nowrap>".stamp_to_date($an_stamp)."</td>";
                    if (!$recursive&&$this_admin&&!$this_user)
                    {
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".EDIT_IMG."</a>".EF."</td>";
                       echo "<td valign=top align=center>".SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".DELETE_IMG."</a>".EF."</td>";
                    }

               break;

               default:

               // cycles thru all fields returned from sql query and builds TDs for each one
               for($index = 1; $index < $fields; $index++)
               {
                    // Need to set the field name to the row value
                    ${mysql_field_name($result,$index)}=$myrow[$index];
                    include("include/db_attributes.inc.php");

                    // Iterate to determine if a FUNCTION_CALL exists
                    $i=0;
                    foreach ($args as $value) {
                         $crazy++;
                         if ($args[$i]["column"]==mysql_field_name($result,$index)) {
                             if ($args[$i]["type"]=="FUNCTION_CALL") {
                                 $field = $args[$i]["function_call"];
                                 $flag=1;
                                 if ($args[$i]["link_to_parent"]&&$parent) {
                                     $parent_op = ($args[$i]["parent_op"]) ? $args[$i]["parent_op"] : "details" ;
                                     $field = ($args[$i]["cuttext"]) ? cuttext($args[$i]["function_call"],$args[$i]["cuttext"]) : $field ;
                                     $field = "<a href=$page?op=$parent_op&tile=$tile&db_table=".$parent[$args[$i]["link_to_parent"]]."&tile=$tile&print=$print&id=".mysql_field_name($result,$index)."|".$myrow[$index].">$field</a>";
                                     $flag=1;
                                 }
                             } elseif ($args[$i]["whois_lookup"]) {
                                 $whois_url = str_replace("{DOMAIN}",$myrow[$index],$domain_whois_server_for_hotlink);
                                 $append    = " <a href=$whois_url target=_blank>[?]</a>";
                                 $flag      = 2;
                             } elseif ($args[$i]["cuttext"]) {
                                 $field  = cuttext($myrow[$index],$args[$i]["cuttext"]) ;
                                 $flag=1;
                             }
                             break;
                         }
                         $i++;
                    }
                    $field = ($flag==1)   ? $field         : $myrow[$index] ;
                    $field = ($flag==2)   ? $field.$append : makeHREF($field) ;
                    $field = ($field=="") ? "&nbsp;"       : $field ;
                    $field = (mysql_field_name($result,$index)=="batch_stamp") ? stamp_to_date($batch_stamp) : $field ; # <-- HACK
                    $field = (mysql_field_name($result,$index)=="an_stamp") ? stamp_to_date($an_stamp) : $field ; # <-- HACK

                    // Display value
                    echo "<td nowrap valign=middle>".SFB . $field . EF."</td>";
                    $flag = NULL; // Reset Flag
               }

               if (!$recursive&&$this_admin&&!$this_user)
                                                         {
                 // Start EDIT <td>
                 echo "<td valign=top align=center>".SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".EDIT_IMG."</a>".EF."</td>";

                 // Start DELETE <td>
                 echo "<td valign=top align=center>".SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&id=".mysql_field_name($result,0)."|$myrow[0]\">".DELETE_IMG."</a>".EF."</td>";
               }

               break;
               }
               // End Each Row
               echo "</tr>";
         }

         // Display Footer Row
         //if(!$recursive)echo $title_row;

         } // Endif $num==0
         echo "</table>";
}

function PieceNavigation($db_table,$limit,$where)
{
         GLOBAL $dbh,
                $op,
                $debug,
                $db_table,
                $order,
                $sort,
                $offset,
                $page,
                $tile,
                $print,
                $client_id,
                $id;

     //finds out how many rows there are in a table
     $where = ($where) ? urldecode(stripslashes($where)) : NULL ;
     $sql = "select * from $db_table $where";
     $numresults=mysql_query($sql,$dbh);
     $numrows=($numresults)?mysql_num_rows($numresults):0;
     if ($debug) echo "SQL: $sql -- ROWS: $numrows";

     $more_args = ($client_id) ? "&client_id=$client_id" : NULL ;

     if ($limit<=($numrows-1)) {
        echo "<TABLE border=0 align=center width=80% cellpadding=2 cellspacing=2>";
        echo "<TR><TD align=right valign=middle width=50%>".SFB;

         //sets new offset value
          $newoffset=$offset+$limit;
          if ($offset==0) {
             echo "&nbsp;<font color=cccccc>&lt; ".PREV."</font>";
          } elseif ($offset !=0) {  // bypass PREV link if offset is 0
             $prevoffset=$offset-$limit;
             echo "<a href=\"$page?".
                    "op=$op&".
                    "db_table=$db_table&".
                    "where=".urlencode($where)."&".
                    "order=$order&".
                    "sort=$sort&".
                    "offset=$prevoffset&".
                    "tile=$tile&".
                    "print=$print&".
                    "id=$id".
                    "$more_args\">&lt; ".PREV."</a> ";
          }

          echo EF."</td><TD align=center>&nbsp;&nbsp;</td><TD align=left valign=middle width=50%>".SFB;

          $newoffset=$offset+$limit;
          if ($numrows <= $newoffset) {
             echo "&nbsp;<font color=cccccc>&nbsp;".NEXT." &gt;</font>";
          } elseif (!(($offset/$limit)==$pages) || $pages!=1) {
             $newoffset=$offset+$limit;
             echo "<a href=\"$page?".
                    "op=$op&".
                    "db_table=$db_table&".
                    "where=".urlencode($where)."&".
                    "order=$order&".
                    "sort=$sort&".
                    "offset=$newoffset&".
                    "tile=$tile&".
                    "print=$print&".
                    "id=$id".
                    "$more_args\">".NEXT." &gt;</a> ";
          }

          echo EF."</td></tr><tr><td colspan=3 align=center>".SFB;

          // calculate number of pages needing links
          $pages=intval($numrows/$limit);

          // $pages now contains int of pages needed unless there is a remainder from division
          if ($numrows%$limit) { // has remainder so add one page
              $pages++;
          }

          $page_number = (intval($offset/$limit))+1;

          echo PAGE.": ";
          for ($i=1;$i<=$pages;$i++) {  // loop thru
              $newoffset=$limit*($i-1);
              if ($page_number==$i){
                  echo "<strong>".SFB."$i".EF."</strong> ";
              } else {
                  echo "<a href=\"$page?".
                         "op=$op&".
                         "db_table=$db_table&".
                         "where=".urlencode($where)."&".
                         "order=$order&".
                         "sort=$sort&".
                         "offset=$newoffset&".
                         "tile=$tile&".
                         "print=$print&".
                         "id=$id".
                         "$more_args\"><u>$i</u></a> ";
              }

          }
          echo EF."</TD></TR></table>";
    }
}


##
## Build a colored table to view hex color.
##
function make_swatch($hex)
{
         return "<table width=25 border=1 cellpadding=1 cellspacing=1><tr><td bgcolor=$hex>".TFB."&nbsp;".EF."</td></tr></table>";
}

##
## Transform links to html
##
function makeHREF($text,$target="_blank")
{
         eregi("<form(.*)</form>",$text,$form);
         $text = eregi_replace("<form(.*)</form>","[FORM]",$text);
         $text = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])","<a href=\"\\1://\\2\\3\" target=\"$target\">\\1://\\2\\3</a>",$text);
         $text = eregi_replace("(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))","<a href=\"mailto:\\1\">\\1</a>", $text);
         $text = str_replace("[FORM]","<form$form[1]</form>",$text);
         return $text;
}

##
## Truncate Text
##
function cuttext($texto,$cuttextlimit=100)
{
         $texto = htmlspecialchars($texto);
         if (strlen($texto) > $cuttextlimit) {
             $texto = substr($texto, 0, $cuttextlimit);
             $texto .=  " .. <i>".MORE."</i>";
         }
         return $texto;
}
?>