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
        $show_this_client = MFB."<b>".CLIENTID." [<a href=$page?op=client_details&db_table=$db_table&tile=$tile&id=client_id|$id>".$id[1]."</a>]: ".$this_client['client_fname']." ".$this_client['client_lname']." &lt;<a href=mailto:".$this_client['client_email'].">".$this_client['client_email']."</a>&gt;</b>".EF;
        $billing_cc_num=encrpyt($this_client['client_stamp'].md5($encryption_key),encrpyt($this_client['client_stamp'],$this_client['billing_cc_num'],1));

        if ($oops) {
             start_html();
             start_form("quick_encrypt_response",$db_table);
             admin_heading($tile);
             start_table(ENCRYPTCC,$a_tile_width);
                  echo "<tr><td align=center colspan=2>$show_this_client<hr noshade></td></tr>";
                  echo "<tr><td>".SFB.$oops.EF."</td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".ENCRYPTIONKEY.":</b>".EF."</td>
                          <td><textarea name=encryption_key rows=8 cols=40 maxlength=1000></textarea></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
                          <td><input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";
                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center>
                             <input type=hidden name=id value=$id[0]|$id[1]>
                             <input type=hidden name=stamp value=$stamp></td></tr>";
             stop_table();
             stop_form();
             stop_html();
        } else {
            $delete_todo = "UPDATE todo_list SET todo_desc='CLIENT CC UPDATED: [$page?op=client_details&db_table=client_info&tile=$tile&id=$id[0]|$id[1]]', todo_status=4 WHERE admin_id=$id[1] AND todo_stamp=$stamp";
            @mysql_query($delete_todo,$dbh);
            $cc_sql = "UPDATE client_info SET billing_cc_num='$billing_cc_num' WHERE $id[0]=$id[1]";
            if ($debug) echo SFB.$cc_sql.EF."<br>";
            if (!mysql_query($cc_sql,$dbh)) {
               echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
            } else {
               header("Location: $page?op=client_details&db_table=client_info&tile=$tile&id=$id[0]|$id[1]&".session_id()."");
            }
        }
?>