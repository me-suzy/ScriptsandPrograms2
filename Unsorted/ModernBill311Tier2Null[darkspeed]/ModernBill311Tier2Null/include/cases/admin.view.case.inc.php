<?
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

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

/* ---- DISPLAY LIST ----*/
        validate_table($db_table,1); if(isset($error)) return;
        if ($search)
        {
            if ($query!="")
            {
              list($column_query,$date)=explode("|",$column_query);
              if ($date=="date")
              {
                  list($year,$month) = explode("/",$query);
                  $first_day_stamp   = mktime(0,0,0,$month,1,$year);
                  $last_day_stamp    = mktime(0,0,0,$month+1,-1,$year);
                  $where             = " WHERE ( $column_query<=$last_day_stamp AND $column_query>=$first_day_stamp ) ";
              }
              elseif ($date=="id")
              {
                  $where             = " WHERE $column_query=".trim(strip_tags($query))." ";
              }
              else
              {
                  $where             = " WHERE $column_query LIKE '%".trim(strip_tags($query))."%' ";
              }
              switch($date)
              {
                   case domain:
                        $db_table = "domain_names";
                        include("include/db_attributes.inc.php");
                        $details_link = "details";
                        $select_sql = "SELECT * FROM domain_names $where";
                   break;

                   case server:
                        $db_table = "account_details";
                        include("include/db_attributes.inc.php");
                        $details_link = "details";
                        $select_sql = "SELECT * FROM account_details $where";
                   break;

                   case event:
                        $db_table = "event_log";
                        include("include/db_attributes.inc.php");
                        $details_link = "details";
                        $select_sql = "SELECT * FROM event_log $where";
                   break;

                   case dbs:
                        $db_table = "account_dbs";
                        include("include/db_attributes.inc.php");
                        $details_link = "details";
                        $select_sql = "SELECT * FROM account_dbs $where";
                   break;

                   default:
                        $select_sql = $select_sql.$where;
                   break;
              }
              $result = mysql_query($select_sql,$dbh);
              $num = ($result) ? mysql_num_rows($result) : 0 ;
            }
            else
            {
              $search=NULL; # <-- Remove the $serch variable if the $query is empty
            }

        }
        if ($num == 1 && $date == "id") {
          $this_op = ($details_link) ? $details_link : "details" ;
          $url = "$page?op=$this_op&db_table=$db_table&tile=$tile&id=$column_query|".strip_tags($query)."&".session_id()."" ;
          Header("Location: $url");
          exit;
        } else {
          start_html();
          admin_heading($tile);
          start_table($title,$a_tile_width);
               echo "<tr><td>";
               display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
               echo "</td></tr>";
               echo "<tr><td align=center>";
               PieceNavigation($db_table,$limit,$where);
               echo "</td></tr>";
          stop_table();
          stop_html();
        }
?>