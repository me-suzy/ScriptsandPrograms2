<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
session_register('decrypt_key');
session_register('total_records');
session_register('cycle_count');
session_register('batch_rows');
session_register('batch_offset');
session_register('batch_limit');
session_register('batch_sum_approved');
session_register('batch_sum_declined');
session_register('batch_sum_error');
session_register('batch_num_approved');
session_register('batch_num_declined');
session_register('batch_num_error');
session_register('batch_delete_sql');

$count_down = ($count_down) ? $count_down : 5 ;
$default_batch_limit = ($default_batch_limit) ? $default_batch_limit : 2 ;

$batch_num_approved = ($batch_num_approved) ? $batch_num_approved : 0 ;
$batch_sum_approved = ($batch_sum_approved) ? $batch_sum_approved : 0 ;
$batch_num_declined = ($batch_num_declined) ? $batch_num_declined : 0 ;
$batch_sum_declined = ($batch_sum_declined) ? $batch_sum_declined : 0 ;
$batch_num_error    = ($batch_num_error)    ? $batch_num_error : 0 ;
$batch_sum_error    = ($batch_sum_error)    ? $batch_sum_error : 0 ;

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

/* ---- RUN BATCH ----*/
// BILLING (PART 3a)
$result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
if ($result && mysql_num_rows($result) == 1)
{
   $decrypt_key=md5($HTTP_POST_VARS['decrypt_key']);
   header("location: $page?op=run_batch&".session_id()."");
}
elseif ($total_records&&$decrypt_key)
{
   if ($cycle_count<$total_records)
   {
      include("include/scripts/run_background_batch.inc.php");
   }

   $percent    = ($total_records) ? 100 * $cycle_count / $total_records : 0 ;
   $percentInt = (int)$percent * 4;
   $percent2   = (int)$percent;

   $is_popup = TRUE;
   start_short_html($title);
   $title = ($cycle_count<$total_records) ? PROCESSING." ..." : COMPLETED ;
   start_table($title,"425");

   if ($cycle_count<$total_records)
   {
?>
<script language="JavaScript">
var countDownInterval=<?=$count_down?>;
//configure width of displayed text, in px (applicable only in NS4)
var c_reloadwidth=200
</script>

<ilayer id="c_reload" width=&{c_reloadwidth}; ><layer id="c_reload2" width=&{c_reloadwidth}; left=0 top=0></layer></ilayer>

<script>
var countDownTime=countDownInterval+1;
function countDown()
{
 countDownTime--;
 if (countDownTime <=0)
 {
  countDownTime=countDownInterval;
  clearTimeout(counter)
  window.location.reload()
  return
 }
 if (document.all) //if IE 4+
  document.all.countDownText.innerText = countDownTime+" ";
 else if (document.getElementById) //else if NS6+
  document.getElementById("countDownText").innerHTML=countDownTime+" "
 else if (document.layers)
 { //CHANGE TEXT BELOW TO YOUR OWN
  document.c_reload.document.c_reload2.document.write('<tr><td bgcolor=FFFFFF align=center colspan=2>Next <a href="javascript:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds</td></tr>')
  document.c_reload.document.c_reload2.document.close()
 }
 counter=setTimeout("countDown()", 1000);
}

function startit()
{
 if (document.all||document.getElementById) //CHANGE TEXT BELOW TO YOUR OWN
 document.write('<tr><td bgcolor=FFFFFF align=center colspan=2>Next <a href="javascript:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds</td></tr>')
 countDown()
}

if (document.all||document.getElementById)
  startit()
else
  window.onload=startit
</script>
<?
   }

   echo "<tr><td bgcolor=FFFFFF align=center colspan=2><b>".STATUS.":</b> ".number_format($percent,2)."%</td></tr>";
   echo "<tr><td bgcolor=FFFFFF align=left colspan=2>";
   if ($percent > 0)
   {
       echo "<img src=\"images/leftbar.gif\" height=14 width=7>";
       echo "<img src=\"images/mainbar.gif\" height=14 width=$percentInt Alt=\"$percent2 %\">";
       echo "<img src=\"images/rightbar.gif\" height=14 width=7>";
   }
   else
   {
       echo "<img src=\"images/leftbar.gif\" height=14 width=7 Alt=\"$percent2 %\">";
       echo "<img src=\"images/mainbar.gif\" height=14 width=3 Alt=\"$percent2 %\">";
       echo "<img src=\"images/rightbar.gif\" height=14 width=7 Alt=\"$percent2 %\">";
   }
   echo "</td></tr>";
   echo "<tr><td bgcolor=FFFFFF align=left>0%</td><td bgcolor=FFFFFF align=right>100%</td></tr>";
   echo "<tr><td bgcolor=FFFFFF colspan=2><hr></td></tr>";
   echo "<tr><td bgcolor=FFFFFF align=right width=50%><b>".TTLAPPROVED.":</b></td><td bgcolor=FFFFFF>$batch_num_approved = ".display_currency($batch_sum_approved)."</td></tr>";
   echo "<tr><td bgcolor=FFFFFF align=right><b>".TTLDECLINED.":</b></td><td bgcolor=FFFFFF>$batch_num_declined = ".display_currency($batch_sum_declined)."</td></tr>";
   echo "<tr><td bgcolor=FFFFFF align=right><b>".TTLERROR.":</b></td><td bgcolor=FFFFFF>$batch_num_error = ".display_currency($batch_sum_error)."</td></tr>";
   if ($cycle_count<$total_records) {} else {
     echo "<tr><td bgcolor=FFFFFF colspan=2 align=right><hr size=1><A onclick=\"window.close(); return false;\" href=\"#\"><b>Close this window</b></A></td></tr>";

     ### BATCH TOTALS
     $insert_sql = "INSERT INTO batch_details (batch_id,
                                               batch_stamp,
                                               batch_sum_approved,
                                               batch_sum_declined,
                                               batch_sum_error,
                                               batch_num_approved,
                                               batch_num_declined,
                                               batch_num_error) VALUES
                                                                (NULL,
                                                                 '".mktime()."',
                                                                 '$batch_sum_approved',
                                                                 '$batch_sum_declined',
                                                                 '$batch_sum_error',
                                                                 '$batch_num_approved',
                                                                 '$batch_num_declined',
                                                                 '$batch_num_error')";
     if($debug) { echo $insert_sql."<br>"; }
     @mysql_query($insert_sql,$dbh);

     for($i=0;$i<=count($batch_delete_sql);$i++)
     {
         mysql_query($batch_delete_sql[$i],$dbh);
     }
     $decrypt_key=$batch_rows=$batch_offset=$batch_limit=NULL;

     if ($debug)
     {
       print_r($batch_delete_sql);
     }
     $decrypt_key=$batch_rows=$batch_offset=$batch_limit=$cycle_count=NULL;
     $batch_num_approved = $batch_sum_approved = $batch_num_declined = $batch_sum_declined = $batch_num_error = $batch_sum_error = 0;
   }
   stop_table();
   stop_short_html(0);
   $result = mysql_query("OPTIMIZE TABLE authnet_batch,client_invoice,client_register,event_logs");
   $opt_results = ($result) ? OPTIMIZEGOOD : OPTIMIZEBAD;
   if ($debug) { echo EF; }
}
else
{
   $ap = ($this_admin["admin_level"]==9) ? $this_admin["ap"] : NULL ;
   $total_records = $batch_rows = mysql_num_rows(mysql_query("SELECT * FROM authnet_batch",$dbh));
   $is_popup = TRUE;
   start_short_html();
   start_form("run_batch",NULL);
   start_table(RUNBATCH,"425");
   echo "<tr><td align=right width=35%>".SFB."<b>".TOTALINVOICES.":</b>".EF."</td>
         <td>".SFB."<b>$batch_rows</b>".EF."</td></tr>";
   echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
         <td><input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";
   echo "<tr><td align=right width=35%>".SFB."<b>".ENCRYPTIONKEY.":</b>".EF."</td>
         <td><textarea name=decrypt_key rows=8 cols=40 maxlength=1000></textarea></td></tr>";
   echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center><input type=hidden name=tile value=$tile></td></tr>";
   stop_table();
   stop_form();
   stop_short_html(0);
}
?>