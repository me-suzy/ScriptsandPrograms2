<?php

/******************************************************
 * CjOverkill version 2.0.1
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/

include ("../cj-conf.inc.php");
include ("../cj-functions.inc.php");
cjoverkill_connect();

include ("security.inc.php");

$stime=localtime();
$thishour=$stime[2];
$sql=@mysql_query("SELECT COUNT(*) FROM cjoverkill_trades");
$tmp_sql=@mysql_fetch_row($sql);
$tmp=$tmp_sq[0];
$all_trades=$tmp-4;

$msg="CjOverkill";


echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$cjoverkill_version</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	<script language=\"JavaScript\" type=\"text/JavaScript\">
	");
echo ("function cjaction(act) {
var tmp = \"\";
for (i=0; i<document.form1.trade_id.length; i++) {
if (document.form1.trade_id[i].checked) {
var tmp = document.form1.trade_id[i].value;
}
	    }
	    if (act == \"addtrade\") { url = \"addtrade.php\"; params = \"width=400,height=350,status=0,resizable=1\"; }
	    if (act == \"edit\" && tmp != \"\") { url = \"edit.php?id=\"+tmp; params = \"width=660,height=650,scrollbars=1,resizable=1\"; }
	    if (act == \"iplog-in\") { url = \"iplog-in.php?id=\"+tmp; params = \"width=600,height=400,status=1,resizable=1,scrollbars=1\"; }
	    if (act == \"iplog-out\") { url = \"iplog-out.php?id=\"+tmp; params = \"width=600,height=400,status=1,resizable=1,scrollbars=1\"; }	
	    if (act == \"reflog\") { url = \"reflog.php?id=\"+tmp; params = \"width=600,height=400,status=1,resizable=1,scrollbars=1\"; }  
	    if (act == \"settings\") { url = \"settings.php\"; params = \"width=440,height=500,status=1,resizable=1,scrollbars=1\"; } 
	    if (act == \"delete\") { url = \"deltrade.php?id=\"+tmp; params = \"width=400,height=180,status=0,resizable=1,scrollbars=0\"; }
	    if (act == \"reset\") { url = \"reset.php?id=\"+tmp; params = \"width=400,height=120,status=0,resizable=1,scrollbars=0\"; }
	    if (act == \"boosttrade\") { url = \"boost.php?id=\"+tmp; params = \"width=500,height=150,status=0,resizable=1,scrollbars=0\"; }
	    if (act == \"overkilltrade\") { url = \"overkill.php?id=\"+tmp; params = \"width=500,height=150,status=0,resizable=1,scrollbars=0\"; }	
	    if (act == \"toplist\") { url = \"toplist.php\"; params = \"width=490,height=550,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"changepass\") { url = \"changepass.php\"; params = \"width=400,height=310,status=0,resizable=1\"; }
	    if (act == \"links\") { url = \"links.php\"; params = \"width=750,height=350,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"daily\") { url = \"daily.php\"; params = \"width=490,height=400,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"blacklist\") { url = \"blacklist.php\"; params = \"width=600,height=400,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"security\") { url = \"security.php\"; params = \"width=600,height=400,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"filter_country\") { url = \"filter-country.php\"; params = \"width=600,height=400,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"filter_ip\") { url = \"filter-ip.php\"; params = \"width=600,height=400,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"filter_client\") { url = \"filter-client.php\"; params = \"width=600,height=400,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"filter_method\") { url = \"filter-method.php\"; params = \"width=600,height=400,status=0,resizable=1,scrollbars=1\"; }
	    if (act == \"edit-mass\") { url = \"edit-mass.php\"; params = \"width=490,height=400,scrollbars=1,resizable=1\"; }            
	    window.open(url, \"_blank\", params);
	}
      </script>
	<base target=\"_blank\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\"> 
	");
if (file_exists("../cjoverkill-install.php")) {
    echo ("<strong><font size=\"4\">YOU HAVE NOT DELETED \"cjoverkill-install.php\".<br>
	    PLEASE DELETE THE FILE OR ANYBODY WILL BE ABLE TO MESS YOUR SCRIPT!<br></font></strong>");
}
if (file_exists("../cjoverkill-update.php")) {
    echo ("<strong><font size=\"4\">YOU HAVE NOT DELETED \"cjoverkill-update.php\".<br>
	    PLEASE DELETE THE FILE OR ANYBODY WILL BE ABLE TO MESS YOUR SCRIPT!<br></font></strong>");
}
if (file_exists("../cjoverkill_filter_base/filter_base-1.php")) {
    echo ("<strong><font size=\"4\">YOU HAVE NOT DELETED \"cjoverkill_filter_base\".<br>
	    PLEASE DELETE THE DIRECTORY WITH THE FILES IT CONTAINS OR ANYBODY WILL BE ABLE TO MESS YOUR SCRIPT!<br></font></strong>");
}

echo ("
	<form name=\"form1\" action=\"\"> 
	<table width=\"850\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<tr class=\"traderow\">
	<td valign=\"top\"><table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" bgcolor=\"#E4E4E4\">
	<tr class=\"toprows\">
	<td colspan=\"9\" height=\"30\">$cjoverkill_version</td> 
	</tr>
	<tr class=\"normalrow\">
	<td colspan=\"9\" height=\"20\">This Is a Free Script With Limited Free Lisence - <a href=\"http://cjoverkill.icefire.org/\" target=\"_blank\">Click Here For More Info</a></td>
	</tr>
	<tr class=\"menu\" height=\"20\">
	<td><a href=\"trades.php\" target=\"_self\">Refresh</a></td>
	<td><a href=\"javascript:cjaction('links')\" target=\"_self\">Links</a></td>
	<td><a href=\"javascript:cjaction('daily')\" target=\"_self\">Daily</a></td>
	<td><a href=\"javascript:cjaction('settings')\" target=\"_self\">Settings</a></td>
	<td><a href=\"javascript:cjaction('blacklist')\" target=\"_self\">Blacklist</a></td>
	<td><a href=\"javascript:cjaction('toplist')\" target=\"_self\">Toplist</a></td>
	<td><a href=\"javascript:cjaction('changepass')\" target=\"_self\">Change Pass</a></td>
	<td><a href=\"javascript:cjaction('edit-mass')\" target=\"_self\">Mass Edit</a></td>
	<td><a href=\"javascript:cjaction('addtrade')\" target=\"_self\">Add Trade</a></td>
	</tr> 
	<tr class=\"menu\" height=\"20\">
	<td><a href=\"javascript:cjaction('security')\" target=\"_self\">Security</a></td>
	<td><a href=\"javascript:cjaction('filter_country')\" target=\"_self\">Country Filter</a></td>
	<td><a href=\"javascript:cjaction('filter_ip')\" target=\"_self\">IP Filter</a></td>
	<td><a href=\"javascript:cjaction('filter_client')\" target=\"_self\">Client Filter</a></td>
	<td><a href=\"javascript:cjaction('filter_method')\" target=\"_self\">Method Filter</a></td>
	<td><a href=\"http://cjoverkill.icefire.org/manual/\">Manual</a></td>
	<td><a href=\"http://www.powercum.com/webmasters/\">Feeder Traffic</a></td>
	<td><a href=\"http://www.powercum.com/webmasters/\">Gallery Spots</a></td>
	<td><a href=\"http://www.icefire.org/\">Webmaster Resources</a></td>
	<td>
	</tr>
       	</table></td>
	</tr>
	<tr class=\"traderow\"> 
	<td align=\"left\">
	<table cellspacing=\"5\" border=\"0\" align=\"center\">
	<tr align=\"center\">
	<td align=\"center\">
	<script language='JavaScript' type='text/javascript'>
	<!--
	if (!document.phpAds_used) document.phpAds_used = ',';
      document.write (\"<\" + \"script language='JavaScript' type='text/javascript' src='\");
      document.write (\"http://cjads.icefire.org//adjs.php?n=a4e8f9be\");
      document.write (\"&amp;what=zone:2&amp;target=_blank&amp;block=1\");
      document.write (\"&amp;exclude=\" + document.phpAds_used);
      if (document.referer)
	document.write (\"&amp;referer=\" + escape(document.referer));
      document.write (\"'><\" + \"/script>\");
      //-->
      </script>
	<noscript>
	<a href='http://cjads.icefire.org//adclick.php?n=a4e8f9be' target='_blank'>
	<img src='http://cjads.icefire.org//adview.php?what=zone:2&amp;n=a4e8f9be' border='0' alt=''></a>
	</noscript>
	</td>
	<td align=\"center\">
	<script language='JavaScript' type='text/javascript'>
	<!--
	if (!document.phpAds_used) document.phpAds_used = ',';
      document.write (\"<\" + \"script language='JavaScript' type='text/javascript' src='\");
      document.write (\"http://cjads.icefire.org//adjs.php?n=aa75a88e\");
      document.write (\"&amp;what=zone:1&amp;target=_blank\");
      document.write (\"&amp;exclude=\" + document.phpAds_used);
      if (document.referer)
	document.write (\"&amp;referer=\" + escape(document.referer));
      document.write (\"'><\" + \"/script>\");
      //-->
      </script>
	<noscript>
	<a href='http://cjads.icefire.org//adclick.php?n=aa75a88e' target='_blank'>
	<img src='http://cjads.icefire.org//adview.php?what=zone:1&amp;n=aa75a88e' border='0' alt=''></a>
	</noscript>
	</td>
	<td align=\"center\">
	<script language='JavaScript' type='text/javascript'>
	<!--
	if (!document.phpAds_used) document.phpAds_used = ',';
      document.write (\"<\" + \"script language='JavaScript' type='text/javascript' src='\");
      document.write (\"http://cjads.icefire.org//adjs.php?n=a4e8f9be\");
      document.write (\"&amp;what=zone:2&amp;target=_blank&amp;block=1\");
      document.write (\"&amp;exclude=\" + document.phpAds_used);
      if (document.referer)
	document.write (\"&amp;referer=\" + escape(document.referer));
      document.write (\"'><\" + \"/script>\");
      //-->
      </script>
	<noscript>
	<a href='http://cjads.icefire.org//adclick.php?n=a4e8f9be' target='_blank'>
	<img src='http://cjads.icefire.org//adview.php?what=zone:2&amp;n=a4e8f9be' border='0' alt=''></a>
	</noscript>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<center><br>
	<font size=\"4\"><strong>$msg</strong></font><br>
	</center> 
	<table width=\"850\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	<tr> 
	<td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\"> 
	<tr class=\"toprows\">
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td colspan=\"7\">This Hour</td> 
	<td colspan=\"6\">Last 24 Hours</td> 
	<td>&nbsp;</td> 
	<td>&nbsp;&nbsp;Force&nbsp;&nbsp;</td> 
	</tr>
	<tr class=\"toprows\">
	<td>&nbsp;</td>
	<td>Site Domain</td>
	<td>Q</td>
	<td width=\"40\">Raw</td>
	<td width=\"40\">Uniq</td>
	<td width=\"40\">Out</td>
	<td>Clicks</td>
	<td>Prod</td>
	<td>Return</td>
	<td><a title=\"F = Force   Fd = Forced\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"toprows\">
	<tr align=\"center\"><td width=\"50%\">F</td><td>|</td><td width=\"50%\">Fd</td></tr></table></a></td>
	<td>&nbsp;Raw&nbsp;</td>
	<td>&nbsp;Uniq&nbsp;</td>
	<td>&nbsp;Out&nbsp;</td>
	<td>Clicks</td>
	<td>Prod</td>
	<td>Return</td>
	<td>Ratio</td>
	<td><a title=\"F = Force   Fd = Forced\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"toprows\">
	<tr align=\"center\"><td width=\"50%\">F</td><td>|</td><td width=\"50%\">Fd</td></tr></table></a></td>
	</tr>
	");
/*
$sql=@mysql_query("SELECT cjoverkill_trades.trade_id, domain, url, site_desc, site_name, email, icq, boost, overkill,
		    return AS ratio, status, raw_tot, 
		    uniq_tot, clicks_tot, out_tot,
		    raw_in$thishour AS raw_now, uniq_in$thishour AS uniq_now, clicks$thishour AS clicks_now, out$thishour AS out_now,
		    raw_in0+raw_in1+raw_in2+raw_in3+raw_in4+raw_in5+raw_in6+raw_in7+raw_in8+raw_in9+raw_in10+raw_in11+raw_in12+
		    raw_in13+raw_in14+raw_in15+raw_in16+raw_in17+raw_in18+raw_in19+raw_in20+raw_in21+raw_in22+raw_in23 AS raw_day,
		    uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+uniq_in11+uniq_in12+
		    uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+uniq_in21+uniq_in22+uniq_in23 AS uniq_day,
		    clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+
		    clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_day,
		    out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
		    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_day,
		    f$thishour AS f_now, h$thishour AS h_now,
		    f0+f1+f2+f3+f4+f5+f6+f7+f8+f9+f10+f11+f12+
		    f13+f14+f15+f16+f17+f18+f19+f20+f21+f22+f23 AS f_day,
		    h0+h1+h2+h3+h4+h5+h6+h7+h8+h9+h10+h11+h12+
		    h13+h14+h15+h16+h17+h18+h19+h20+h21+h22+h23 AS h_day
		    FROM cjoverkill_trades, cjoverkill_stats, cjoverkill_forces
		    WHERE cjoverkill_trades.trade_id=cjoverkill_stats.trade_id AND cjoverkill_trades.trade_id=cjoverkill_forces.trade_id
		    AND cjoverkill_trades.trade_id>'4' ORDER BY uniq_day,raw_day,clicks_day,out_day,uniq_now,raw_now,
		    clicks_now,out_now DESC") or 
  print_error(mysql_error());
*/
$sql=@mysql_query("SELECT cjoverkill_trades.trade_id, domain, url, site_desc, site_name, email, icq, boost, overkill,
		    max_ret AS ratio, status, raw_tot, 
		    uniq_tot, clicks_tot, out_tot,
		    raw_in$thishour AS raw_now, uniq_in$thishour AS uniq_now, clicks$thishour AS clicks_now, out$thishour AS out_now,
		    raw_in0+raw_in1+raw_in2+raw_in3+raw_in4+raw_in5+raw_in6+raw_in7+raw_in8+raw_in9+raw_in10+raw_in11+raw_in12+
		    raw_in13+raw_in14+raw_in15+raw_in16+raw_in17+raw_in18+raw_in19+raw_in20+raw_in21+raw_in22+raw_in23 AS raw_day,
		    uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+uniq_in11+uniq_in12+
		    uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+uniq_in21+uniq_in22+uniq_in23 AS uniq_day,
		    clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+
		    clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_day,
		    out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
		    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_day,
		    f$thishour AS f_now, h$thishour AS h_now,
		    f0+f1+f2+f3+f4+f5+f6+f7+f8+f9+f10+f11+f12+
		    f13+f14+f15+f16+f17+f18+f19+f20+f21+f22+f23 AS f_day,
		    h0+h1+h2+h3+h4+h5+h6+h7+h8+h9+h10+h11+h12+
		    h13+h14+h15+h16+h17+h18+h19+h20+h21+h22+h23 AS h_day
		    FROM cjoverkill_trades, cjoverkill_stats, cjoverkill_forces
		    WHERE cjoverkill_trades.trade_id=cjoverkill_stats.trade_id AND cjoverkill_trades.trade_id=cjoverkill_forces.trade_id
		    AND cjoverkill_trades.trade_id>'4' ORDER BY uniq_day DESC,raw_day DESC,clicks_day DESC,out_day DESC,
		    uniq_now DESC,raw_now DESC, clicks_now DESC, out_now DESC") or 
  print_error(mysql_error());
$raw_ntot=0;
$uniq_ntot=0;
$out_ntot=0;
$clicks_ntot=0;
$raw_dtot=0;
$uniq_dtot=0;
$out_dtot=0;
$clicks_dtot=0;
$f_dtot=0;
$h_dtot=0;
$f_ntot=0;
$h_ntot=0;
while ($tmp=@mysql_fetch_array($sql)) {
    extract($tmp);
    $raw_ntot=$raw_ntot+$raw_now;
    $uniq_ntot=$uniq_ntot+$uniq_now;
    $out_ntot=$out_ntot+$out_now;
    $clicks_ntot=$clicks_ntot+$clicks_now;
    $raw_dtot=$raw_dtot+$raw_day;
    $uniq_dtot=$uniq_dtot+$uniq_day;
    $out_dtot=$out_dtot+$out_day;
    $clicks_dtot=$clicks_dtot+$clicks_day;
    $f_dtot=$f_dtot+$f_day;
    $h_dtot=$h_dtot+$h_day;
    $f_ntot=$f_ntot+$f_now;
    $h_ntot=$h_ntot+$h_now;
    if ($out_day!=0){
	$vale="".round(($clicks_day/$out_day)*100,0)."%";
    }
    else {
	$vale="--";
    }
    if ($uniq_day!=0){
	$prod_day="".round($clicks_day/$uniq_day*100, 1)."%";
	$return_day="".round($out_day/$uniq_day*100, 1)."%";
    }
    else {
	$prod_day="0%";
	$return_day="0%";
    }
    if ($uniq_now!=0){
	$prod_now="".round($clicks_now/$uniq_now*100, 1)."%";
	$return_now="".round($out_now/$uniq_now*100, 1)."%";
    }
    else {
	$prod_now="0%";
	$return_now="0%";
    }
    $bgcolor="#EFEFEF";
    if ($status==0){
	$bgcolor="#9999ff";
    }
    elseif ($status==2){
	$bgcolor="#ff9999";
    }
    elseif ($boost==1){
	$bgcolor="#99ff99";
    }
    elseif ($overkill==1){
	$bgcolor="#ffff99";
    }
    else {
	$bgcolor="#EFEFEF";
    }
    $title="Sitename:$site_name    Email: $wm_email    ICQ: $wm_icq    Desc:$site_desc";
    echo ("<tr bgcolor=\"$bgcolor\" class=\"traderow\">
	    <td><input type=\"radio\" name=\"trade_id\" value=\"$trade_id\"></td>
	    <td align=\"left\"><a href=\"$url\" title=$title\" alt=\"$title\">$domain</a></td>
	    <td>$vale</td>
	    <td>$raw_now</td> 
	    <td>$uniq_now</td>
	    <td>$out_now</td>
	    <td>$clicks_now</td>
	    <td>$prod_now</td>
	    <td>$return_now</td>
	    <td>$f_now | $h_now</td>
	    <td>$raw_day</td>
	    <td>$uniq_day</td>
	    <td>$out_day</td>
	    <td>$clicks_day</td>
	    <td>$prod_day</td>
	    <td>$return_day</td>
	    <td>$ratio%</td>
	    <td>$f_day | $h_day</td>
	    </tr>\n
	    ");
}
echo ("<tr class=\"toprows\">
	<td>&nbsp;</td>
	<td>Site Domain</td>
	<td>Q</td>
	<td width=\"40\">Raw</td>
	<td width=\"40\">Uniq</td>
	<td width=\"40\">Out</td>
	<td>Clicks</td>
	<td>Prod</td>
	<td>Return</td>
	<td><a title=\"F = Force   Fd = Forced\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"toprows\">
	<tr align=\"center\"><td width=\"50%\">F</td><td>|</td><td width=\"50%\">Fd</td></tr></table></a></td>
	<td>&nbsp;Raw&nbsp;</td>
	<td>&nbsp;Uniq&nbsp;</td>
	<td>&nbsp;Out&nbsp;</td>
	<td>Clicks</td>
	<td>Prod</td>
	<td>Return</td>
	<td>Ratio</td>
	<td><a title=\"F = Force   Fd = Forced\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"toprows\">
	<tr align=\"center\"><td width=\"50%\">F</td><td>|</td><td width=\"50%\">Fd</td></tr></table></a></td>
	</tr>\n
	");
$sql=@mysql_query("SELECT cjoverkill_trades.trade_id, domain, url, site_desc, site_name, email, icq, max_ret AS ratio, status, raw_tot, 
		    uniq_tot, clicks_tot, out_tot,
		    raw_in$thishour AS raw_now, uniq_in$thishour AS uniq_now, clicks$thishour AS clicks_now, out$thishour AS out_now,
		    raw_in0+raw_in1+raw_in2+raw_in3+raw_in4+raw_in5+raw_in6+raw_in7+raw_in8+raw_in9+raw_in10+raw_in11+raw_in12+
		    raw_in13+raw_in14+raw_in15+raw_in16+raw_in17+raw_in18+raw_in19+raw_in20+raw_in21+raw_in22+raw_in23 AS raw_day,
		    uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+uniq_in11+uniq_in12+
		    uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+uniq_in21+uniq_in22+uniq_in23 AS uniq_day,
		    clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+
		    clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_day,
		    out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
		    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_day,
		    f$thishour AS f_now, h$thishour AS h_now,
		    f0+f1+f2+f3+f4+f5+f6+f7+f8+f9+f10+f11+f12+
		    f13+f14+f15+f16+f17+f18+f19+f20+f21+f22+f23 AS f_day,
		    h0+h1+h2+h3+h4+h5+h6+h7+h8+h9+h10+h11+h12+
		    h13+h14+h15+h16+h17+h18+h19+h20+h21+h22+h23 AS h_day
		    FROM cjoverkill_trades, cjoverkill_stats, cjoverkill_forces
		    WHERE cjoverkill_trades.trade_id=cjoverkill_stats.trade_id AND cjoverkill_trades.trade_id=cjoverkill_forces.trade_id
		    AND cjoverkill_trades.trade_id<='4' ORDER BY trade_id ASC") or 
  print_error(mysql_error());
while ($tmp=@mysql_fetch_array($sql)) {
    extract($tmp);
    $raw_ntot=$raw_ntot+$raw_now;
    $uniq_ntot=$uniq_ntot+$uniq_now;
    $out_ntot=$out_ntot+$out_now;
    $clicks_ntot=$clicks_ntot+$clicks_now;
    $raw_dtot=$raw_dtot+$raw_day;
    $uniq_dtot=$uniq_dtot+$uniq_day;
    $out_dtot=$out_dtot+$out_day;
    $clicks_dtot=$clicks_dtot+$clicks_day;
    $f_dtot=$f_dtot+$f_day;
    $h_dtot=$h_dtot+$h_day;
    if ($out_day!=0){
	$vale="".round(($clicks_day/$out_day)*100,0)."%";
    }
    else {
	$vale="--";
    }
    if ($uniq_day!=0){
	$prod_day="".round($clicks_day/$uniq_day*100, 1)."%";
	$return_day="".round($out_day/$uniq_day*100, 1)."%";
    }
    else {
	$prod_day="0%";
	$return_day="0%";
    }
    if ($uniq_now!=0){
	$prod_now="".round($clicks_now/$uniq_now*100, 1)."%";
	$return_now="".round($out_now/$uniq_now*100, 1)."%";
    }
    else {
	$prod_now="0%";
	$return_now="0%";
    }
    $title="Sitename:$site_name    Email: $wm_email    ICQ: $wm_icq    Desc:$site_desc";
    echo ("<tr bgcolor=\"#EFEFEF\" class=\"traderow\">
	    <td><input type=\"radio\" name=\"trade_id\" value=\"$trade_id\"></td>
	    <td align=\"left\">$domain</td>
	    <td>$vale</td>
	    <td>$raw_now</td> 
	    <td>$uniq_now</td>
	    <td>$out_now</td>
	    <td>$clicks_now</td>
	    <td>$prod_now</td>
	    <td>$return_now</td>
	    <td>-- | --</td>
	    <td>$raw_day</td>
	    <td>$uniq_day</td>
	    <td>$out_day</td>
	    <td>$clicks_day</td>
	    <td>$prod_day</td>
	    <td>$return_day</td>
	    <td>--</td>
	    <td>-- | --</td>
	    </tr>\n
	    ");
}

if ($out_dtot!=0){
    $vale="".round(($clicks_dtot/$out_dtot)*100,0)."%";
}
else {
    $vale="--";
}
if ($uniq_ntot!=0){
    $prod_ntot="".round($clicks_ntot/$uniq_ntot*100, 1)."%";
    $return_ntot="".round($out_ntot/$uniq_ntot*100, 1)."%";
}
else {
    $prod_ntot="0%";
    $return_ntot="0%";
}
if ($uniq_dtot!=0){
    $prod_dtot="".round($clicks_dtot/$uniq_dtot*100, 1)."%";
    $return_dtot="".round($out_dtot/$uniq_dtot*100, 1)."%";
}
else {
    $prod_dtot="0%";
    $return_dtot="0%";
}

echo ("<tr class=\"toprows\">
	<td>&nbsp;</td>
	<td align=\"left\">TOTALS</td>
	<td>$vale</td>
	<td>$raw_ntot</td>
	<td>$uniq_ntot</td>
	<td>$out_ntot</td>
	<td>$clicks_ntot</td>
	<td>$prod_ntot</td>
	<td>$return_ntot</td>
	<td>$f_ntot | $h_ntot</td>
	<td>$raw_dtot</td>
	<td>$uniq_dtot</td>
	<td>$out_dtot</td>
	<td>$clicks_dtot</td>
	<td>$prod_dtot</td>
	<td>$return_dtot</td>
	<td>&nbsp;</td>
	<td>$f_dtot | $h_dtot</td>
	</tr>
	</table>
	");

echo ("
	</table>
	<br>
	<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<tr align=\"center\">
	<td><input name=\"edit\" type=\"button\" class=\"buttons\" id=\"edit\" value=\" Edit \" onclick=\"cjaction('edit')\"></td>
	<td><input name=\"iplog-in\" type=\"button\" class=\"buttons\" id=\"iplog\" value=\" IP Log IN\" onclick=\"cjaction('iplog-in')\"></td>
	<td><input name=\"iplog-out\" type=\"button\" class=\"buttons\" id=\"iplog\" value=\" IP Log OUT\" onclick=\"cjaction('iplog-out')\"></td>
	<td><input name=\"reflog\" type=\"button\" class=\"buttons\" id=\"reflog\" value=\" Ref Log \" onclick=\"cjaction('reflog')\"></td>
	</tr>
	<tr align=\"center\">
	<td colspan=\"4\">&nbsp;</td>
	</tr>
	<tr align=\"center\">
	<td><input name=\"reset\" type=\"button\" class=\"buttons\" id=\"reset\" value=\" Reset \" onclick=\"cjaction('reset')\"></td>
	<td><input name=\"delete\" type=\"button\" class=\"buttons\" id=\"delete\" value=\" Delete \" onclick=\"cjaction('delete')\"></td>
	<td><input name=\"boost\" type=\"button\" class=\"buttons\" value=\"Add/Delete Boost\" onclick=\"cjaction('boosttrade')\"></td>
	<td><input name=\"overkill\" type=\"button\" class=\"buttons\" value=\"Add/Delete Overkill\" onclick=\"cjaction('overkilltrade')\"></td>
	</tr>
	</table>
	
	<br><br>\n
	
	<br><br><br>
	<table width=\"800\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<tr class=\"traderow\">
	<td width=\"45%\" align=\"left\"><b><font size=\"3\">Helper Links:</font></b></td>
	<td width=\"55%\" align=\"left\"><b><font size=\"3\">Trade Info:</font></b></td>
	</tr>
	<tr class=\"traderow\">
	<td align=\"left\" valign=\"top\"><b>Trading Page:</b> <a href=\"../trade.php\">trade.php</a><br>
	<b>Out Script:</b> <a href=\"../out.php\">out.php</a><br>
	<b>Documentation and Help:</b> <a href=\"http://cjoverkill.icefire.org/manual/\">User Manual</a><br>
	<b>CjOverkill Support Forum:</b> <a href=\"http://cjoverkill.icefire.org/bbs.php\">Support Forum</a><br>
	</td>
	<td align=\"left\" valign=\"top\">
	<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" class=\"traderow\">
	<tr>
	<td width=\"20\" bgcolor=\"#9999ff\">&nbsp;</td>
	<td align=\"left\">Disabled trade</td>
	</tr>
	<tr>
	<td width=\"20\" bgcolor=\"#99ff99\">&nbsp;</td>
	<td align=\"left\">Boosted trade</td>
	</tr>
	<tr>
	<td width=\"20\" bgcolor=\"#ffff99\">&nbsp;</td>
	<td align=\"left\">Overkill trade</td>
	</tr>
	<tr>
	<td width=\"20\" bgcolor=\"#ff9999\">&nbsp;</td>
	<td align=\"left\">Autodisabled trade</td>
	</tr>
	</td>
       	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td colspan=\"2\">
	<b>Normal:</b> \"out.php\"<br>
	<b>To URL:</b> \"out.php?url=http://www.example.com\"<br>
	<b>Skimming:</b> \"out.php?pct=50&url=http://www.example.com\"<br>
	<b>Skimming:</b> \"out.php?p=50&url=http://www.example.com\" (alternative method to declare skim)<br>
	<b>First Click:</b> \"out.php?f=1&pct=50&url=http://www.example.com\"<br>
	<b>To Trade:</b> \"out.php?trade=example.com\"<br>
	<b>Track Link:</b> \"out.php?link=blablabla\"<br>
	<b>SSI Code:</b> &lt;!--#include file=\"in.php\" --&gt;<br>
	Insert code as the first line of your .shtml file<br>
	</td>
	</tr>	
	</table>
	<br><br><br>
	<center>
	<a href=\"http://cjoverkill.icefire.org\" target=\"_blank\">$cjoverkill_version</a>
	</center>
	<br>
	<br>
	</body>
	</html>
	");

?>
	

