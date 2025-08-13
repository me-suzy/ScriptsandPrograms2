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

/* ---- GENERATE BATCH ----*/
// BILLING (PART 2)
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if ($result && mysql_num_rows($result) == 1){
             include("include/scripts/generate_batch.inc.php");
             if (!$debug) {
             start_html();
             admin_heading($tile);
             start_table(GB,$a_tile_width);
                  echo"<tr><td align=center><table>";
                  echo "<tr><td width=33% align=center>".SFB."&nbsp;".EF."</td>
                         <td width=33% align=right>".SFB."<b>#</b>".EF."</td>
                         <td width=33% align=right>".SFB."<b>".TOTALS."</b>".EF."</td></tr>";
                  echo "<tr><td width=33% align=left>".SFB."<b>".IATB.":</b>".EF."</td>
                         <td width=33% align=right>".SFB."$num_batch".EF."</td>
                         <td width=33% align=right>".SFB.display_currency($sum_batch).EF."</td></tr>";
                  echo "</td></tr></table>";
             stop_table();
             stop_html();
             }
        } else {
             start_html();
             admin_heading($tile);
             start_form("gen_batch",NULL);
             start_table(GB,$a_tile_width);
                  echo "<tr><td colspan=2 valign=middle><center>".SFB."<b>".YOURPW.":</b>".EF." <input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";
                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center><input type=hidden name=tile value=$tile></td></tr>";
             stop_table();
             stop_form();
             stop_html();
        }
?>