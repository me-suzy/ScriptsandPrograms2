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


## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { if ($op!="exp_batch") { Header("Location: http://$standard_url?op=logout"); exit; } }
$ap = ($this_admin["admin_level"]==9) ? $this_admin["ap"] : NULL ;

if ($this_admin[admin_level]<8)
{
    $is_popup = TRUE;
    start_short_html($title);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".MFB.ACCESSDENIED.EF."</b>&nbsp;&nbsp;&nbsp;&nbsp;";
    stop_short_html();
    exit;
}
/* ---- EXPORT BATCH ----*/
// BILLING (PART 3b)
        $pw = ($password1) ? $password1 : md5(strip_tags($password)) ;
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='$pw'",$dbh) or die (mysql_error());
        if ($result && mysql_num_rows($result) == 1){
           if($db_table=="continue"){
             include("include/scripts/export_batch.inc.php");
           } else {
             start_html();
             echo "<form method=post action=http://".$standard_url."$admin_page?".session_id().">";
             echo "<input type=hidden name=op value=exp_batch>";
             echo "<input type=hidden name=db_table value=continue>";
             admin_heading($tile);
             start_table(EXPORTBATCH,$a_tile_width);
                  echo "<tr><td colspan=2>
                          <center><input type=submit name=submit value=\"".DOWNLOADNOW."!\"></center>
                          <input type=hidden name=decrypt_key value=\"".md5(strip_tags($decrypt_key))."\" size=15 maxlength=15>
                          <input type=hidden name=password1 value=\"$pw\" size=15 maxlength=15>
                          </td>
                     </tr>";
             stop_table();
             stop_form();
             stop_html();
           }
        } else {
             start_html();
             start_form("exp_batch","authnet_batch");
             admin_heading($tile);
             start_table(EXPORTBATCH,$a_tile_width);
                  echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
                          <td><input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".ENCRYPTIONKEY.":</b>".EF."</td>
                          <td><textarea name=decrypt_key rows=8 cols=40 maxlength=1000></textarea></td></tr>";
                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center><input type=hidden name=tile value=$tile></td></tr>";
             stop_table();
             stop_form();
             stop_html();
        }
?>