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

/* ---- DISPLAY REPORTS (LISTS) ----*/
        if(!$db_table)list($db_table,$where)=explode("|",urldecode($report_type));

        if(!$db_table)header("location: $page?op=menu&tile=reports");

        include("include/db_attributes.inc.php");

        if ($print) {
          start_short_html();
        } else {
          start_html();
          admin_heading($tile);
        }
        start_table(REPORTS.": ".$title,$a_tile_width,"center","FFFFFF");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             echo "<tr><td align=center>";
             PieceNavigation($db_table,$limit,$where);
             echo "</td></tr>";
        stop_table();
        stop_html();
?>