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
$ap = ($this_admin["admin_level"]==9) ? $this_admin["ap"] : NULL ;
$is_popup = TRUE;
if ($this_admin[admin_level]<8)
{
    start_short_html($title);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".MFB.ACCESSDENIED.EF."</b>&nbsp;&nbsp;&nbsp;&nbsp;";
    stop_short_html();
    exit;
}
/* ---- ENCRYPT CC NUMBER RESPONSE ----*/
// D O   N O T   M O D I F Y

        GLOBAL $data;

        // validate and clean form input
        include("include/misc/validate_form_input.inc.php");

        // validate admin password
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops = "[".ERROR."] ".INVALIDPASSWORD."!<br>";

        $id=explode("|",$id);
        $sql="SELECT * FROM client_info WHERE $id[0]=$id[1]";
        if($debug)echo SFB.$sql.EF."<br>";
        $result = mysql_query($sql,$dbh);
        $this_client = mysql_fetch_array($result);
        $show_this_client = MFB.CLIENTID." [<a href=$page?op=client_details&db_table=$db_table&tile=$tile&id=client_id|$id>".$id[1]."</a>]: ".$this_client['client_fname']." ".$this_client['client_lname']." <a href=mailto:".$this_client['client_email'].">".$this_client['client_email']."</a>".EF;
        $billing_cc_num=encrpyt($this_client['client_stamp'].md5($encryption_key),$this_client['billing_cc_num'],1);

        if ($oops) {
             start_short_html(SECUREPAYMENTS);
             echo "<form method=post action=$page?op=view_cc_response&".session_id().">";
             start_table(VIEWCC,400);
                  echo "<tr><td align=center colspan=2>$show_this_client<hr noshade></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".ENCRYPTIONKEY.":</b>".EF."</td>
                          <td><textarea name=encryption_key rows=8 cols=40 maxlength=1000></textarea></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
                          <td><input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";
                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center><input type=hidden name=id value=$id[0]|$id[1]></td></tr>";
             stop_table();
             stop_form();
             stop_html(NULL);
        } else {
             start_short_html(SECUREPAYMENTS);
             start_table(VIEWCC,400);
                  echo "<tr><td align=center colspan=2>$show_this_client<hr noshade></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".CCNUM.":</b>".EF."</td><td>".SFB.$billing_cc_num.EF."</td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".EXPIRATIONDATE2.":</b>".EF."</td><td>".SFB.$this_client["billing_cc_exp"].EF."</td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".CVV2.":</b>".EF."</td><td>".SFB.$this_client["billing_cc_code"].EF."</td></tr>";
             stop_table();
             stop_short_html(NULL);
        }
?>