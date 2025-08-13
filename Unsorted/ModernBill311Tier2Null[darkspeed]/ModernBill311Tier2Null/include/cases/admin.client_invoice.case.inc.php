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


/* ---- DISPLAY SPECIAL CLIENT INVOICE ----*/
// CUSTOME "DETAILS" TEMPALTE
        validate_table($db_table,1); if(isset($error)) return;
        start_html();
        admin_heading($tile);
        start_table(NULL,$a_tile_width);
        echo "<tr><td>";
        include("include/html/client_invoice.inc.php");
        echo "</td></tr>";
        stop_table();
        stop_html();
?>
