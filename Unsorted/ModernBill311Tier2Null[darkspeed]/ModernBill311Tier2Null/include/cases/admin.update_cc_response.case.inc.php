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

/* ---- CHANGE CC NUMBER/EXP DATE RESPONSE ----*/
// UPDATE CC (PART 2)
// D O   N O T   M O D I F Y
        GLOBAL $data;

        // validate and clean form input
        include("include/misc/validate_form_input.inc.php");

        // validate admin password
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops = "[".ERROR."] ".INVALIDPASSWORD."!<br>";
        if (!$billing_cc_exp) $oops .= "[".REQUIRED."] ".EXPIRATIONDATE."<br>";

           // validate credit card type, encrypt cc
           $billing_cc_type=validate_cc_input($billing_cc_num,NULL);
           if ($billing_cc_type&&$data&&$id) {
               $id=explode("|",$id);
               $sql="SELECT * FROM client_info WHERE $id[0]=$id[1]";
               if($debug)echo SFB.$sql.EF."<br>";
               $result = mysql_query($sql,$dbh);
               $this_client = mysql_fetch_array($result);
               $billing_cc_num=encrpyt($this_client['client_stamp'].md5($encryption_key),$data);
           } else {
               $oops .= "[".ERROR."] ".CCNUMINVALID."!<br>";
           }

        if ($oops) {
             start_html();
             start_form("update_cc_response",NULL);
             admin_heading($tile);
             start_table(NULL,$a_tile_width);
                  echo "<tr><td>".SFB.$oops.EF."</td></tr>";
             stop_table();
             stop_form();
             stop_html();
        } else {
            $cc_sql = "UPDATE client_info SET billing_cc_type='$billing_cc_type', billing_cc_num='$billing_cc_num', billing_cc_exp='$billing_cc_exp', billing_cc_code='$billing_cc_code' WHERE $id[0]=$id[1]";
            if ($debug) echo SFB.$cc_sql.EF."<br>";
            if (!mysql_query($cc_sql,$dbh)) {
               echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
            } else {
               header("Location: $page?op=client_details&db_table=client_info&tile=$tile&id=$id[0]|$id[1]&".session_id()."");
            }
        }
?>