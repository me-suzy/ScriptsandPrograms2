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


/* ---- CHANGE CC NUMBER/EXP DATE ----*/
// UPDATE CC (PART 1)
// D O   N O T   M O D I F Y

        $sql = "SELECT * FROM client_info WHERE client_id=$this_user[0]";
        if($debug) echo SFB.$sql.EF."<br>";
        addslashes($result = mysql_query($sql,$dbh));
        $this_client = mysql_fetch_array($result);
             start_html();
             echo "<form method=post action=$page?op=update_cc_response&".session_id().">";
             user_heading($tile);
             start_table(UPDATECC,$u_tile_width);
             if ($this_user["billing_method"]!=1)
             {
                  echo "<tr><td colspan=2>".SFB."<b>[".WARNING."]</b> ".YOURBILLINGMETHOD.".".EF."</td></tr>";
             }
             else
             {
                  echo "<tr><td align=right width=35%>".SFB."<b>".NEWCC.":</b>".EF."</td>
                          <td><input type=TEXT name=billing_cc_num size=16 maxlength=20> ".SFB."(".$we_accept.")".EF."</td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".NEWEXPDATE.":</b>".EF."</td>
                          <td><input type=TEXT name=billing_cc_exp size=7 maxlength=7> ".SFB."(".DATEFORMAT.")".EF."</td></tr>";
                  echo "<!--<tr><td align=right width=35%>".SFB."<b>CVV2/CVC2:</b>".EF."</td>
                          <td><input type=TEXT name=billing_cc_code size=4 maxlength=3> ".SFB."(".THREEDIGIT.")".EF."</td></tr>-->";
                  echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
                          <td><input type=password name=password size=15 maxlength=15></td></tr>";
                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center></td></tr>";
             }
             stop_table();
             stop_form();
             stop_html();
?>