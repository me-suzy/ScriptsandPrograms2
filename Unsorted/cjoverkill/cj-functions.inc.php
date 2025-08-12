<?php

$cjoverkill_version="CjOverkill Version 2.0.1";

//cookie time to count uniques (in seconds)

$cookietime=86400;

//internal functions. Do not edit unless you know what you do

function cjoverkill_connect(){
    global $cjoverkill_host;
    global $cjoverkill_user;
    global $cjoverkill_passwd;
    global $cjoverkill_db;
    global $cjoverkill_link;
    $cjoverkill_link=@mysql_connect("$cjoverkill_host","$cjoverkill_user","$cjoverkill_passwd") OR
      print_error("Chould not connect to database<br>Please check your settings cj-conf.inc.php");
    @mysql_select_db("$cjoverkill_db") OR 
      print_error("Could not choose the database: $mysql_db");
}

function cjoverkill_disconnect(){
    global $cjoverkill_link;
    @mysql_close($cjoverkill_link) OR 
      print_error("Could not close connection to MySQL.");
}

function print_error($msg) {
    echo "<font face='verdana' size='3'><b>Error:</b><br><font size='2'>$msg</font></font>";
    exit;
}

function cjoverkill_cheats(){
    $cheat1="N";
    $cheat2="N";
    $cheat3="N";
    $cheat4="N";
    $cheat5="N";
    $stime=localtime();
    $thishour=$stime[2];
    $next_hour=mktime($stime[2]+1,0,0,date("m"),date("d"),date("Y"));
    @mysql_query("UPDATE cjoverkill_reset SET rst_h='$next_hour'") OR 
      print_error(mysql_error());
    $sql=@mysql_query("SELECT min_uniq,px_enable,ip_enable,clicks_enable,cheatstart FROM cjoverkill_settings") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $sql=@mysql_query("SELECT cjoverkill_trades.trade_id, max_p, min_p, max_px, max_clicks, max_ip, status,
			uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			uniq_in21+uniq_in22+uniq_in23 AS uniq_day,
			clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			clicks21+clicks22+clicks23 AS clicks_day,
			raw_in0+raw_in1+raw_in2+raw_in3+raw_in4+raw_in5+raw_in6+raw_in7+raw_in8+raw_in9+raw_in10+
			raw_in11+raw_in12+raw_in13+raw_in14+raw_in15+raw_in16+raw_in17+raw_in18+raw_in19+raw_in20+
			raw_in21+raw_in22+raw_in23 AS raw_day
			FROM cjoverkill_stats, cjoverkill_trades WHERE
			cjoverkill_stats.trade_id=cjoverkill_trades.trade_id AND
			cjoverkill_trades.trade_id>'4' AND
			(status='1' OR status='2') AND
			boost!='1' AND
			overkill!='1'") OR
      print_error(mysql_error());
    while ($tmp=@mysql_fetch_array($sql)) {
	extract($tmp);
	$tmp_status=$status;
	$cheat1="N"; // Minimum hits
	$cheat2="N"; // Productivity
        $cheat3="N"; // Proxy
	$cheat4="N"; // Clicks
	$cheat5="N"; // Same IP hits
	// Minimum hits 
	if ($uniq_day<$min_uniq){
	    $tmp_status="2";
	    $cheat1="Y";
	}
	elseif ($uniq_day>=$min_uniq){
	    $tmp_status="1";
	}
	// Productivity
	if ($uniq_day!=0 && $uniq_day>=$cheatstart && $cheat1=="N"){
	    $prod=$clicks_day/$uniq_day;
	    if ($prod<$min_p || $prod>$max_p){
		$tmp_status="2";
		$cheat2="Y";
	    }
	    else {
		$tmp_status="1";
	    }
	}
	// Proxy 
	if (isset($px_enable) && $px_enable=="1" && $raw_day>=$cheatstart && $cheat1=="N" && $cheat2=="N"){
	    $tmp_sql1=@mysql_query("SELECT sum(raw_in) AS px FROM cjoverkill_iplog_in WHERE
				    proxy!='' AND
				    raw_in>0 AND
				    trade_id='$trade_id'") OR
	      print_error(mysql_error());
	    $tmp1=@mysql_fetch_array($tmp_sql1);
	    extract($tmp1);
	    $tmp_sql2=@mysql_query("SELECT sum(raw_in) AS all_raw FROM cjoverkill_iplog_in WHERE
				     raw_in>0 AND
				     trade_id='$trade_id'") OR
	      print_error(mysql_error());
	    $tmp2=@mysql_fetch_array($tmp_sql2);
	    extract($tmp2);
	    if ($px>0 && $all_raw>0){
		$px_pct=($px/$all_raw)*100;
		if ($px_pct>$max_px){
		    $tmp_status="2";
		    $cheat3="Y";
		}
	    }
	}
	// Clicks
	if (isset($clicks_enable) && $clicks_enable=="1" && $raw_day>=$cheatstart){
	    $tmp_sql3=@mysql_query("SELECT COUNT(*) AS cheated_clicks FROM cjoverkill_iplog_in WHERE 
				     clicks>=$max_clicks AND
				     trade_id='$trade_id'") OR
	      print_error(mysql_error());
	    $tmp3=@mysql_fetch_array($tmp_sql3);
	    extract($tmp3);
	    if ($cheated_clicks>=1){
		$tmp_status="2";
		$cheat4="Y";
	    }
	}
	// Same IP hits
	if (isset($ip_enable) && $ip_enable=="1" && $raw_day>=$cheatstart){
	    $tmp_sql4=@mysql_query("SELECT COUNT(*) AS cheated_ips FROM cjoverkill_iplog_in WHERE
				     raw_in>=$max_ip AND
				     trade_id='$trade_id'") OR
	      print_error(mysql_error());
	    $tmp4=@mysql_fetch_array($tmp_sql4);
	    extract($tmp4);
	    if ($cheated_ips>=1){
		$tmp_status="2";
		$cheat5="Y";
	    }
	}
	// Global cheating checker
        if ($cheat1=="Y" || $cheat2=="Y" || $cheat3=="Y" || $cheat4=="Y" || $cheat5=="Y"){
	    $tmp_status="2";
	}
	else {
	    $tmp_status="1";
	}
	@mysql_query("UPDATE cjoverkill_trades SET status='$tmp_status' WHERE trade_id='$trade_id'") OR
	  print_error(mysql_error());
    }
}

function cjoverkill_toplist($admin=0){
    if ($admin==1) { 
	$prefix = "../";
    }
    else { 
	$prefix = "";
    }
    $sql=@mysql_query("SELECT domain,site_name,site_desc,
			uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			uniq_in21+uniq_in22+uniq_in23 AS uniq_day FROM
			cjoverkill_stats, cjoverkill_trades WHERE
			cjoverkill_stats.trade_id=cjoverkill_trades.trade_id AND
			cjoverkill_trades.trade_id>'4' AND
			(status='1' OR status='3') ORDER BY
			uniq_day DESC") OR
      print_error(mysql_error());
    if (@mysql_num_rows($sql)==0){
	$tms="No trades found";
//	print_error($tms);
    }
    while ($tmp=mysql_fetch_array($sql)) {
	extract($tmp);
	$sites["$domain"]=$site_name;
	$descs["$domain"]=$site_desc;
	$uniqs["$domain"]=$uniq_day;
    }
    $fdir=@opendir($prefix."toplist") OR 
      print_error("Could not open directory \"toplist\"<BR>
		    Make shure it exist and it is writable");
    while (($tfile = readdir($fdir)) !== FALSE) {
	if (substr($tfile,-6)==".templ") { 
	    $toplists[] = $tfile; 
	}
    }
    closedir($fdir);
    for ($i=0; $i<sizeof($toplists); $i++){
	$template=implode("", file($prefix."toplist/$toplists[$i]"));
	reset($uniqs);
	for ($a=1; $a<=125; $a++) {
	    $ky=key($uniqs);
	    if ($ky=="") { 
		$ky="Your Site Here";
		$lnk="<a href=\"/trade.php\">$ky</a>";
	    }
	    else {
		$lnk="<a href=\"/out.php?trade=$ky\">$sites[$ky]</a>";
	    }
	    $template=str_replace("##sitename$a##", $sites["$ky"], $template);
	    $template=str_replace("##sitedesc$a##", $descs["$ky"], $template);
	    $template=str_replace("##in$a##", $uniqs["$ky"], $template);
	    $template=str_replace("##sitedomain$a##", "$ky", $template);
	    $template=str_replace("##link$a##", "$lnk", $template);
	    next($uniqs);
	}
	$tml=str_replace(".templ", ".html", $toplists[$i]);
	$tfile=@fopen($prefix . "toplist/$tml", "w") OR 
	  print_error("Could not open \"$tml\"<BR>
			Make shure it exists and is writable or the toplist directory is writable");
	fputs($tfile, "$template");
	fclose($tfile);
    }
}

function cjoverkill_hourly(){
    $stime=localtime();
    $thishour=$stime[2];
    @mysql_query("UPDATE cjoverkill_stats SET raw_in$thishour=0, uniq_in$thishour=0, clicks$thishour=0, out$thishour=0") OR 
      print_error(mysql_error());
    @mysql_query("UPDATE cjoverkill_forces SET h$thishour=0") OR 
      print_error(mysql_error());
    @mysql_query("UPDATE cjoverkill_links SET h$thishour=0") OR 
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_iplog_in WHERE hour='$thishour'") OR
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_iplog_out WHERE hour='$thishour'") OR
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_ref WHERE hour='$thishour'") OR
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_security WHERE hour='$thishour'") OR 
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_filter_ip WHERE hour='$thishour' AND auto='1'") OR
      print_error(mysql_error());
}

function cjoverkill_daily(){
    $today=date("Y-m-d", mktime(0,0,0,date("m"), date("d")-1, date("Y")));
    $tomorrow=mktime (0,0,0, date("m"), date("d")+1, date("Y"));
    @mysql_query("UPDATE cjoverkill_reset SET rst_d=$tomorrow") OR 
      print_error(mysql_error());
    $sql=@mysql_query("SELECT sum(raw_in0+raw_in1+raw_in2+raw_in3+raw_in4+raw_in5+raw_in6+raw_in7+raw_in8+raw_in9+raw_in10+
				  raw_in11+raw_in12+raw_in13+raw_in14+raw_in15+raw_in16+raw_in17+raw_in18+raw_in19+raw_in20+
				  raw_in21+raw_in22+raw_in23) AS raw_day,
			sum(uniq_in0+uniq_in1+uniq_in2+uniq_in3+uniq_in4+uniq_in5+uniq_in6+uniq_in7+uniq_in8+uniq_in9+uniq_in10+
			    uniq_in11+uniq_in12+uniq_in13+uniq_in14+uniq_in15+uniq_in16+uniq_in17+uniq_in18+uniq_in19+uniq_in20+
			    uniq_in21+uniq_in22+uniq_in23) AS uniq_day,
			sum(clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+
			    clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+
			    clicks21+clicks22+clicks23) AS clicks_day,
			sum(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+
			    out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23) AS out_day
			FROM cjoverkill_stats") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    @mysql_query("INSERT INTO cjoverkill_daily (fecha, raw_in, uniq_in, clicks, uniq_out)
      VALUES ('$today', '$raw_day', '$uniq_day', '$clicks_day', '$out_day')") OR
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_links WHERE h0+h1+h2+h3+h4+h5+h6+h7+h8+h9+h10+h11+h12+
		   h13+h14+h15+h16+h17+h18+h19+h20+h21+h22+h23=0") OR
      print_error(mysql_error());
}


?>
