<?php
require_once("admin_max_settings.php"); require_once("db.php"); $today_d = date("M d, Y");
$today_d2 = date("ymd")."0000"; if (check_user2()) {  $site_id = $s;
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
   <table border="0" cellpadding="3" cellspacing="1" width=720>
    <tr bgcolor="425B7E"><td class="main" align=left colspan=6><b>IP Log for:
<?php
echo "$today_d (Today)&nbsp;&nbsp;-&nbsp;&nbsp;</b>\n";
$h_data = db_query("select sitename, siteurl from ttp_sites where siteid='$site_id'");
$h_row = mysql_fetch_array($h_data);
echo $h_row["sitename"]."&nbsp;&nbsp;<a href=\"".urldecode($h_row["siteurl"])."\" target=_blank>".urldecode($h_row["siteurl"])."</a>\n";
?>
</td></tr>

    <tr bgcolor="425B7E">
     <td class="main" align=center>Site ID</td>
     <td class="main" align=center>Proxy IP</td>
     <td class="main" align=center>Refer URL</td>
    </tr>


<?php
$stat_q = db_query("select sitename, siteurl, ipaddr, b.prox prox, refer from ttp_sites a, ttp_traffic b where b.prox=1 and a.siteid='$site_id' and a.siteid=b.siteid and datev>='$today_d2' and active=1");
while ($myrow_t = mysql_fetch_array($stat_q)) {
echo "    <tr bgcolor=\"425B7E\">\n";
echo "     <td class=main align=center><a href=\"".$myrow_t["siteurl"]."\" target=_blank>".$myrow_t["sitename"]."</a></td>\n";
echo "     <td class=main align=left>".urldecode($myrow_t["ipaddr"])."</td>\n";
echo "     <td class=main align=left>".urldecode($myrow_t["refer"])."</td>\n";
echo "    </tr>\n"; }
?>

    </table>
</body>
</html>

<?php
db_close();
exit();
} else { db_close();
header("Location: login.htm\n\n");
exit();
}
?>
