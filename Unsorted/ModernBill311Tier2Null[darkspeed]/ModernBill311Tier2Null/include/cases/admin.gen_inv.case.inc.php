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
/* ---- GENERATE INVOICES ----*/
// BILLING (PART 1)
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if ($submit && $result && mysql_num_rows($result) == 1) {
             $debug = ($do_debug) ? $do_debug : $debug ;
             if ($anniversary_billing) {
                 include("include/scripts/generate_invoices_daily.inc.php");
             }
             else
             {
                 include("include/scripts/generate_invoices.inc.php");
             }
             if(!$debug) {
             start_short_html($title);
             start_table(GI,"425");
                  echo"<tr><td align=center><table>";
                  echo "<tr><td width=33% align=center>".SFB."&nbsp;".EF."</td>
                         <td width=33% align=right>".SFB."<b>#</b>".EF."</td>
                         <td width=33% align=right>".SFB."<b>".TOTALS."</b>".EF."</td></tr>";
                  echo "<tr><td width=33% align=left>".SFB."<b>".STM.":</b>".EF."</td>
                         <td width=33% align=right>".SFB.$count_thismonth.EF."</td>
                         <td width=33% align=right>".SFB."[".NA."]".EF."</td></tr>";
                  echo "<tr><td width=33% align=left>".SFB."<b>".FR.":</b>".EF."</td>
                         <td width=33% align=right>".SFB.$count_first_renewal.EF."</td>
                         <td width=33% align=right>".SFB.display_currency($sum_first_renewal).EF."</td></tr>";
                  echo "<tr><td width=33% align=left>".SFB."<b>".RTM.":</b>".EF."</td>
                         <td width=33% align=right>".SFB.$count_renewed.EF."</td>
                         <td width=33% align=right>".SFB.display_currency($sum_renewed).EF."</td></tr>";
                         $count_invoices=$count_first_renewal+$count_renewed;
                         $sum_invoices=$sum_first_renewal+$sum_renewed;
                  echo "<tr><td width=33% align=left>".SFB."<b>".TIGEN.":</b>".EF."</td>
                         <td width=33% align=right>".SFB.$count_invoices.EF."</td>
                         <td width=33% align=right>".SFB.display_currency($sum_invoices).EF."</td></tr>";
                  echo "</td></tr></table>";
        ?><hr size=1 width=98%>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=MFB?><b><?=STATS?>:</b><?=EF?></td></tr>
             <tr>
               <td valign=top>
                       <?=SFB?><?=TESS?> = <b><?=($success["sent"]) ? $success["sent"] : 0;?></b><?=EF?><br>
                       <?=SFB?><?=TENS?> = <b><?=($success["failed"]) ? $success["failed"] : 0 ;?></b><?=EF?><br>
                       <?=SFB.$opt_results.EF?><br>
               </td>
             </tr>
            </table><?
             stop_table();
             stop_short_html(0);
             }
        } else {
             start_short_html($title);
             start_form("gen_inv",NULL);
             echo "<input type=hidden name=submit value=1>";
             start_table(GI,"425");
                  if ($this_admin[admin_level]>=8) {
                      echo "<tr><td valign=middle align=right>".SFB."<b>".DEBUG.":</b>".EF."</td>
                                <td><input type=radio name=do_debug value=1>&nbsp;".YES."&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name=do_debug value=0 CHECKED>&nbsp;".NO."</td></tr>";
                  }
                  if ($client_id) {

                      echo "<tr><td valign=middle align=right>".SFB."<b>".CLIENT.":</b>".EF."</td>
                                <td>".client_select_box($client_id,"client_id",NULL,NULL)."</td></tr>";
                  }
                  if ($this_admin[admin_level]>=8) {
                      $num = date("n");
                      $monthnames  = array(M_JANUARY,M_FEBRUARY,M_MARCH,M_APRIL,M_MAY,M_JUNE,M_JULY,M_AUGUST,M_SEPTEMBER,M_OCTOBER,M_NOVEMBER,M_DECEMBER,M_JANUARY);

                      echo "<tr><td valign=middle align=right>".SFB."<b>".MONTH.":</b>".EF."</td>";
                      echo "<td>";
                      ?>
                      <select name=z>
                      <?
                      for($i=0;$i<=11;$i++){
                            list($month,$year) = explode("|",date("n|Y",mktime(0,0,0,date("m")-12+$i-1,1,date("Y"))));
                            $past = $i - 12;
                            echo "<option value=$past>($past): $monthnames[$month] $year</option>";
                      }
                      list($month,$year) = explode("|",date("n|Y",mktime(0,0,0,date("m")-1,1,date("Y"))));
                      echo "<option value=0 SELECTED>(0): $monthnames[$month] $year</option>";
                      for($i=1;$i<=12;$i++){
                            list($month,$year) = explode("|",date("n|Y",mktime(0,0,0,date("m")+$i-1,1,date("Y"))));
                            echo "<option value=$i>($i): $monthnames[$month] $year</option>";
                      }
                      ?>
                      </select>
                      <?
                      echo "</tr>";
                  }
                  echo "<tr><td valign=middle align=right>".SFB."<b>".DAYSBEFORE.":</b>".EF."</td>";
                  echo "<td>";
                  ?>
                  <select name=d>
                  <?
                  for($i=0;$i<=date("t");$i++){
                      echo "<option value=$i>$i</option>";
                  }
                  ?>
                  </select>
                  <?
                  echo "</tr>";
                  echo "<tr><td valign=middle align=right>".SFB."<b>".YOURPW.":</b>".EF."</td>
                            <td><input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";

                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center><input type=hidden name=tile value=$tile></td></tr>";
             stop_table();
             stop_form();
             stop_short_html(0);
        }
?>