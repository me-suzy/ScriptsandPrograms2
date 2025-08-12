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

ignore_user_abort(true);
//include ("cj-filter.php");
include ("cj-conf.inc.php");
include ("cj-functions.inc.php"); 
cjoverkill_connect();

include ("cj-filter.php");

//global variables set

$stime=localtime();
$thishour=$stime[2];

$referer=$_SERVER["HTTP_REFERER"];
$ref=$referer;

if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
    $proxy=$_SERVER["REMOTE_ADDR"];
    $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
}
else {
    $ip=$_SERVER["REMOTE_ADDR"];
    $proxy="";
}

$tid=$_GET["tid"];

//reset if proceeds

$sql=@mysql_query("SELECT * FROM cjoverkill_reset") OR 
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
extract($tmp);
$time_tmp=time();
if ($time_tmp>$rst_h){
    cjoverkill_hourly();
    cjoverkill_cheats();
    cjoverkill_toplist();
}
if ($time_tmp>$rst_d){
    cjoverkill_daily();
}

//credit the trade that sends the surfer

if (isset($tid) && $tid!=""){
    $sql=@mysql_query("SELECT trade_id FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $trade_id=$tid;
    if ($referer=="" || $referer=="-") {
	$referer="bookmarks";
	$ref="no refering url";
    }
}
else {
    if ($referer!="" && $referer!="-") {
	if (strlen($referer)>256){
	    $what="Referer string too long, possible overflow attack";
	    @mysql_query("INSERT INTO cjoverkill_security (fecha, what, ip, proxy, hour) 
	      VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	      print_error(mysql_error());
	    print_error("Security violation attempt detected!<BR>
			  IP=$ip<BR>
			  Proxy=$proxy<BR>
			  are you trying to hack me duhdah?
			  ");
	}
	$tmp=parse_url($referer);
	$domain=eregi_replace("www\.", "", $tmp["host"]);
	if ($domain!=""){
	    $referer=$domain;
	}
	else {
	    $referer="bookmarks";
	    $ref="no refering url";
	}
    }
    else {
	$referer="bookmarks";
	$ref="no refering url";
    }
    $sql=@mysql_query("SELECT trade_id FROM cjoverkill_trades WHERE domain='$referer'") OR 
      print_error(mysql_error());
}
if (@mysql_num_rows($sql)==0 && (!isset($tid) || $tid=="") && $referer=="") { 
    $trade_id=1;
}
elseif (@mysql_num_rows($sql)==0 && (!isset($tid) || $tid=="") && referer!=""){
    $trade_id=2;
}
else { 
    $tmp=@mysql_fetch_array($sql); 
    extract($tmp);
}

// ref log 
@mysql_query("UPDATE cjoverkill_ref SET raw_in=raw_in+1 WHERE trade_id='$trade_id' AND referer='$ref'") OR 
  print_error(mysql_error());
if (@mysql_affected_rows()==0) { 
    @mysql_query("INSERT INTO cjoverkill_ref (trade_id, referer, raw_in, hour) 
      VALUES ('$trade_id', '$ref', '1', '$thishour')") OR
      print_error(mysql_error());
}
// IP log + Trade track
@mysql_query("UPDATE cjoverkill_iplog_in SET raw_in=raw_in+1 WHERE ip='$ip' AND proxy='$proxy' AND trade_id='$trade_id'") OR 
  print_error(mysql_error());
if (@mysql_affected_rows()==0) {
    @mysql_query("INSERT INTO cjoverkill_iplog_in (trade_id, ip, proxy, raw_in, hour) 
      VALUES ($trade_id, '$ip', '$proxy', '1', '$thishour')") OR
      print_error(mysql_error());
    @mysql_query("UPDATE cjoverkill_stats SET raw_in$thishour=raw_in$thishour+1, uniq_in$thishour=uniq_in$thishour+1
		   WHERE trade_id='$trade_id'") OR
      print_error(mysql_error());
    @mysql_query("UPDATE cjoverkill_trades SET raw_tot=raw_tot+1, uniq_tot=uniq_tot+1 WHERE trade_id='$trade_id'") OR
      print_error(mysql_error());
}
else {
    @mysql_query("UPDATE cjoverkill_stats SET raw_in$thishour=raw_in$thishour+1 WHERE trade_id='$trade_id'") OR
      print_error(mysql_error());
    @mysql_query("UPDATE cjoverkill_trades SET raw_tot=raw_tot+1 WHERE trade_id='$trade_id'") OR
      print_error(mysql_error());
}

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


?>
