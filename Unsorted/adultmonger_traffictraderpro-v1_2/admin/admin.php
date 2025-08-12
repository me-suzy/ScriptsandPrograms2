<?php        
@set_time_limit(60);  require_once("admin_max_settings.php");
require_once("db.php");  $today_d2 = date("ymd");
$today_d2 = date("ymd")."0000";  if (check_user2()) {
?>

<html><head><title>Stats</title>
<STYLE type=text/css>.title {FONT: 10pt Verdana, Helvetica, sans-serif}</STYLE>
<STYLE type=text/css>.main {FONT: 8pt Verdana, Helvetica, sans-serif; color:white}</STYLE>
<STYLE type=text/css>.small {FONT: 7pt Verdana, Helvetica, sans-serif; color:white}</STYLE>
<STYLE type=text/css>
A:link {COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:visited {COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:active {COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:hover {COLOR: #0000FF; TEXT-DECORATION: none}
</STYLE></head>

<body bgcolor=#000000 background=../assets/am-interfacev1_r6_c4.jpg leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<table height=280 border=0 cellpadding=0 cellspacing=0 background=../assets/am-interfacev1_r6_c4.jpg align=center>
  <tr>
    <td valign=top align=center class=main>
   <?php /*<table border="0" cellpadding="3" cellspacing="1" width=720>
    <tr bgcolor="#425B7E"><td class="main" align=left><b>Custom Date Report</b></td></tr>
    <tr bgcolor="#557AB1"><form method=post><td class="main" align=left>Show stats From: <select name=tfrom><option value="01/29/2003">01/29/2003</select> Through: <select name=tthrough><option value="01/30/2003">01/30/2003</select> <input type=submit name="past" value="See Stats"></td></form></tr>
   </table>
   <br>*/?>
   <table border="0" cellpadding="3" cellspacing="1" width=715>
    <tr bgcolor="425B7E">
     <td class="main" align=left colspan=12><b>Stats for: <?php echo date("M d, Y");?> (Today)</b></td>
    </tr>
    <tr bgcolor="425B7E">
     <td class="main" align=center>ID</td>
     <td class="main" align=left>Site Name</td>
     <td class="main" align=center>Raw</td>
     <td class="main" align=center>Unique</td>
     <td class="main" align=center>Clicks</td>
     <td class="main" align=center>Prod.</td>
     <td class="main" align=center>Proxy</td>
     <td class="main" align=center>Sent</td>
     <td class="main" align=center>Owed</td>
     <td class="main" align=center>Force/day</td>
     <td class="main" align=center colspan=2>&nbsp;</td>
    </tr>
<?php
$total_raw = 0; $total_uniq = 0; $total_sent = 0; $total_clicks = 0; $total_prox = 0; $total_force = 0;
$stat_q = db_query("select a.siteid siteid, sitename, siteurl, force, sent, sum(click) click, ifnull(count(distinct(ipaddr)),0) uniq, ifnull(count(ipaddr),0) raw, ifnull(((sum(click)/count(distinct(ipaddr)))*100),0) prod2, ifnull(sum(b.prox),0) prox, ifnull(((sum(b.prox)/count(distinct(ipaddr)))*100),0) prox2 from ttp_sites a LEFT JOIN ttp_traffic b ON a.siteid=b.siteid where a.siteid > 0 and active>=0 group by siteid order by siteid ASC");
while ($myrow = mysql_fetch_array($stat_q)) {$total_raw = $total_raw + $myrow["raw"]; $total_uniq = $total_uniq + $myrow["uniq"]; $total_sent = $total_sent + $myrow["sent"];
$total_click = $total_click + $myrow["click"]; $total_prox = $total_prox + $myrow["prox"];  $total_force = $total_force + $myrow["force"]; $click_owe = $myrow["uniq"]+$myrow["force"]-$myrow["sent"];
echo "    <tr bgcolor=\"557AB1\">\n"; echo "     <td class=main align=center>".$myrow["siteid"]."</td>\n";
echo "     <td class=main align=left><a href=\"".urldecode($myrow["siteurl"])."\" target=_blank>".substr(urldecode($myrow["sitename"]),0,20)."</a></td>\n";
echo "     <td class=main align=center>".$myrow["raw"]."</td>\n"; echo "     <td class=main align=center>".$myrow["uniq"]."</td>\n";
echo "     <td class=main align=center>".$myrow["click"]."</td>\n"; echo "     <td class=main align=center>".$myrow["prod2"]."%</td>\n";
echo "     <td class=main align=center>".$myrow["prox2"]."%</td>\n"; echo "     <td class=main align=center>".$myrow["sent"]."</td>\n";
echo "     <td class=main align=center>$click_owe</td>\n"; echo "     <td class=main align=center>".$myrow["force"]."</td>\n";
echo "     <form method=POST action=detail.php><td class=main align=center>\n"; echo "     <input type=hidden name=\"site_id\" value=\"".$myrow["siteid"]."\"><input type=submit value=\"details\" name=details></td></form>\n";
echo "     <form method=POST action=manage.php><td class=main align=center>\n"; echo "     <input type=hidden name=\"site_id\" value=\"".$myrow["siteid"]."\"><input type=submit value=\"manage\" name=manage></td></form>\n"; echo "    </tr>\n";}
$stat_o1 = db_query("select ifnull(count(distinct ipaddr),0) uniq, ifnull(count(ipaddr),0) raw, ifnull(sum(click),0) click, ifnull(((sum(click)/count(distinct(ipaddr)))*100),0) prod2, ifnull(((sum(prox)/count(distinct(ipaddr)))*100),0) prox2, ifnull(sum(prox),0) prox from ttp_traffic where refer='bookmark'");
while ($myrow = mysql_fetch_array($stat_o1)) { $total_raw = $total_raw + $myrow["raw"];   $total_uniq = $total_uniq + $myrow["uniq"];  $total_click = $total_click + $myrow["click"];
$total_prox = $total_prox + $myrow["prox"];   echo "    <tr bgcolor=\"557AB1\">\n";
echo "     <td class=main align=right colspan=2><b>Bookmarks&nbsp;</td>\n";     echo "     <td class=main align=center>".$myrow["raw"]."</td>\n";  echo "     <td class=main align=center>".$myrow["uniq"]."</td>\n";   echo "     <td class=main align=center>".$myrow["click"]."</td>\n";
echo "     <td class=main align=center>".$myrow["prod2"]."%</td>\n"; echo "     <td class=main align=center>".$myrow["prox2"]."%</td>\n"; echo "     <td class=main align=center colspan=5>&nbsp;</td>\n";
echo "    </tr>\n"; } $stat_o1a = db_query("select ifnull(count(distinct ipaddr),0) uniq, ifnull(count(ipaddr),0) raw, ifnull(sum(click),0) click, ifnull(((sum(click)/count(distinct(ipaddr)))*100),0) prod2, ifnull(((sum(prox)/count(distinct(ipaddr)))*100),0) prox2, ifnull(sum(prox),0) prox from ttp_traffic where refer='google'");
while ($myrow = mysql_fetch_array($stat_o1a)) {
$total_raw = $total_raw + $myrow["raw"];        $total_uniq = $total_uniq + $myrow["uniq"];
$total_click = $total_click + $myrow["click"]; $total_prox = $total_prox + $myrow["prox"];
echo "    <tr bgcolor=\"557AB1\">\n";        echo "     <td class=main align=right colspan=2><b>Google&nbsp;</td>\n";
echo "     <td class=main align=center>".$myrow["raw"]."</td>\n";      echo "     <td class=main align=center>".$myrow["uniq"]."</td>\n";
echo "     <td class=main align=center>".$myrow["click"]."</td>\n";     echo "     <td class=main align=center>".$myrow["prod2"]."%</td>\n";
echo "     <td class=main align=center>".$myrow["prox2"]."%</td>\n";    echo "     <td class=main align=center colspan=5>&nbsp;</td>\n";
echo "    </tr>\n";                      }  $stat_o1b = db_query("select ifnull(count(distinct ipaddr),0) uniq, ifnull(count(ipaddr),0) raw, ifnull(sum(click),0) click, ifnull(((sum(click)/count(distinct(ipaddr)))*100),0) prod2, ifnull(((sum(prox)/count(distinct(ipaddr)))*100),0) prox2, ifnull(sum(prox),0) prox from ttp_traffic where refer='yahoo'");
while ($myrow = mysql_fetch_array($stat_o1b)) {     $total_raw = $total_raw + $myrow["raw"];
$total_uniq = $total_uniq + $myrow["uniq"];
$total_click = $total_click + $myrow["click"]; $total_prox = $total_prox + $myrow["prox"];
echo "    <tr bgcolor=\"557AB1\">\n";
echo "     <td class=main align=right colspan=2><b>Yahoo&nbsp;</td>\n";
echo "     <td class=main align=center>".$myrow["raw"]."</td>\n";      echo "     <td class=main align=center>".$myrow["uniq"]."</td>\n";     echo "     <td class=main align=center>".$myrow["click"]."</td>\n";
echo "     <td class=main align=center>".$myrow["prod2"]."%</td>\n";  echo "     <td class=main align=center>".$myrow["prox2"]."%</td>\n";  echo "     <td class=main align=center colspan=5>&nbsp;</td>\n";
echo "    </tr>\n";
}$stat_o1c = db_query("select ifnull(count(distinct ipaddr),0) uniq, ifnull(count(ipaddr),0) raw, ifnull(sum(click),0) click, ifnull(((sum(click)/count(distinct(ipaddr)))*100),0) prod2, ifnull(((sum(prox)/count(distinct(ipaddr)))*100),0) prox2, ifnull(sum(prox),0) prox from ttp_traffic where refer='altavista'");
while ($myrow = mysql_fetch_array($stat_o1c)) {  $total_raw = $total_raw + $myrow["raw"];            $total_uniq = $total_uniq + $myrow["uniq"];
$total_click = $total_click + $myrow["click"];      $total_prox = $total_prox + $myrow["prox"];
echo "    <tr bgcolor=\"557AB1\">\n";                echo "     <td class=main align=right colspan=2><b>AltaVista&nbsp;</td>\n";
echo "     <td class=main align=center>".$myrow["raw"]."</td>\n";
echo "     <td class=main align=center>".$myrow["uniq"]."</td>\n";  echo "     <td class=main align=center>".$myrow["click"]."</td>\n";
echo "     <td class=main align=center>".$myrow["prod2"]."%</td>\n";   echo "     <td class=main align=center>".$myrow["prox2"]."%</td>\n";
echo "     <td class=main align=center colspan=5>&nbsp;</td>\n";   echo "    </tr>\n";
} $stat_o2 = db_query("select ifnull(count(distinct ipaddr),0) uniq, ifnull(count(ipaddr),0) raw, ifnull(sum(click),0) click, ifnull(((sum(click)/count(distinct(ipaddr)))*100),0) prod2, ifnull(((sum(prox)/count(distinct(ipaddr)))*100),0) prox2, ifnull(sum(prox),0) prox from ttp_traffic where siteid=0 and refer<>'bookmark' and refer<>'google' and refer<>'altavista' and refer<>'yahoo'");
while ($myrow = mysql_fetch_array($stat_o2)) {  $total_raw = $total_raw + $myrow["raw"];  $total_uniq = $total_uniq + $myrow["uniq"]; $total_click = $total_click + $myrow["click"];
$total_prox = $total_prox + $myrow["prox"];  echo "    <tr bgcolor=\"557AB1\">\n";  echo "     <td class=main align=right colspan=2><b>Misc. Traffic&nbsp;</td>\n";
echo "     <td class=main align=center>".$myrow["raw"]."</td>\n";  echo "     <td class=main align=center>".$myrow["uniq"]."</td>\n";
echo "     <td class=main align=center>".$myrow["click"]."</td>\n"; echo "     <td class=main align=center>".$myrow["prod2"]."%</td>\n";
echo "     <td class=main align=center>".$myrow["prox2"]."%</td>\n"; echo "     <td class=main align=center colspan=5>&nbsp;</td>\n";
echo "    </tr>\n"; } $total_owe = ($total_uniq+$total_force)-$total_sent;
if ($total_uniq > 0) {   $total_prod2 = ($total_click/$total_uniq)*100;  $total_prod2 = round($total_prod2,2);
$total_prox2 = ($total_prox/$total_uniq)*100; $total_prox2 = round($total_prox2,2); } else { $total_prod2 = 0.00; $total_prox2 = 0.00; }

echo "    <tr bgcolor=\"425B7E\">\n";  echo "     <td class=main colspan=2 align=right><b>Total:&nbsp;</td>\n";
echo "     <td class=main align=center>$total_raw</td>\n";  echo "     <td class=main align=center>$total_uniq</td>\n";
echo "     <td class=main align=center>$total_click</td>\n";  echo "     <td class=main align=center>$total_prod2%</td>\n";
echo "     <td class=main align=center>$total_prox2%</td>\n"; echo "     <td class=main align=center>$total_sent</td>\n";
echo "     <td class=main align=center>$total_owe</td>\n"; echo "     <td class=main align=center>$total_force</td>\n";
echo "     <td class=main align=center colspan=2>&nbsp;</td>\n"; echo "    </tr>\n";
?>

    </table>
</body>
</html>

<?php
db_close(); exit(); } else { db_close();  header("Location: login.htm\n\n");  exit();
}
?>
