<? ## TIER2 PAGE
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
session_register("payment_completed");

## Validate that the user
if (!testlogin()&&(!$this_user||!$this_admin))  { Header("Location: http://$standard_url?op=logout"); exit; }

$is_popup=TRUE;

if ($this_admin) {
    $sql = "SELECT * FROM client_info WHERE client_id=$client_id";
    if($debug) echo SFB.$sql.EF."<br>";
    $result = mysql_query($sql,$dbh) or die (mysql_error());
    $this_user = mysql_fetch_array($result);
}
//FROM client_invoice WHERE invoice_date_paid!=0 AND invoice_amount <= invoice_amount_paid"));

        $sql = "SELECT * FROM client_invoice WHERE invoice_id=$id AND client_id=".$this_user['client_id']."";
        if($debug) echo SFB.$sql.EF."<br>";
        addslashes($result = mysql_query($sql,$dbh));
        $this_invoice = mysql_fetch_array($result);
             start_short_html(SECUREPAYMENTS);
             if ($this_invoice[invoice_amount] <= $this_invoice[invoice_amount_paid]) {
                 start_table("".INVOICENUM." [".$this_invoice[0]."]</b><br><font color=gray><b>".display_currency($this_invoice['invoice_amount'])."</b></font>","200");
                 echo "<tr><td align=center>".SFB."<b><font color=GREEN>".PAID."</font></b>: ".stamp_to_date($this_invoice[invoice_date_paid]).EF."</td></tr>";
                 stop_table();
                 $payment_completed = TRUE;
             } else {
                 echo "<form method=post action=$https://$secure_url"."$page?op=pay_invoice_response&".session_id().">";
                 start_table("".INVOICENUM." [".$this_invoice[0]."]</b><br><font color=gray><b>".display_currency($this_invoice['invoice_amount'])."</b></font>","200");
                 echo "<tr><td>".SFB."<b>".CCNUM.":</b>".EF."<br><input type=TEXT name=billing_cc_num size=25 maxlength=20><br>".SFB.$we_accept.EF."</td></tr>";
                 echo "<tr><td> ".SFB."<b>".EXPIRATIONDATE2.":</b>".EF."<br>";
                 ?>
                    <select name=x_Exp_Month>
                    <?
                    for($i=1;$i<=12;$i++){
                        echo "<option value=\"$i\"";
                        if($x_Exp_Month==$i) { echo " SELECTED "; }
                        echo ">$i</option>";
                    }
                    ?>
                    </select>
                    /
                    <select name=x_Exp_Year>
                    <?
                    for($i=date("Y");$i<=date("Y")+10;$i++){
                        echo "<option value=\"$i\"";
                        if($x_Exp_Year==$i) { echo " SELECTED "; }
                        echo ">$i</option>";
                    }
                    ?>
                    </select>
                    (mm/yyyy)
                 <?
                 //echo "<tr><td> ".SFB."<b>".EXPIRATIONDATE2.":</b>".EF."<br><input type=TEXT name=billing_cc_exp size=11 maxlength=7> ".SFB.DATEFORMAT.EF."<hr noshade></td></tr>";
                 echo "<tr><td><center>".CHARGE_IMG."</center><input type=hidden name=id value=$id></td></tr>";
                 if ($this_admin) { echo "<input type=hidden name=client_id value=".$this_user[client_id].">"; }
                 stop_table();
                 stop_form();
             }
             stop_short_html(1);
?>