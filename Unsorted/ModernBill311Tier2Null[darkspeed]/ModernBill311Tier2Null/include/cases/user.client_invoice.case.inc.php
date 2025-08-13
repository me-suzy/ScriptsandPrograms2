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

## Validate that the user
if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }


        $tid=explode("|",$id);
        $num_invoices=mysql_num_rows(mysql_query("SELECT invoice_id FROM client_invoice WHERE $tid[0]=$tid[1] AND client_id=$this_user[0]",$dbh));
        //start_html();
        //user_heading($tile);
        //start_table(NULL,$u_tile_width,"center","#999999");
        if ($num_invoices==1) {
            $db_table="client_invoice";
            $recursive=1;
            include("include/db_attributes.inc.php");
            include("include/html/client_invoice.inc.php");
        } else {
            start_short_html();
            echo "<center>
                      <blockquote>
                      ".MFB."[".ERROR."] ".NOIVOICENUM1.$tid[1]." ".NOIVOICENUM2.EF."
                      </blocquote>
                 </center>";
            stop_short_html();
        }
        //stop_table();
        //stop_html();
?>