<?php
ignore_user_abort(true); @set_time_limit(90); require_once("./admin/admin_max_settings.php"); require_once("./admin/db.php");
preg_match("/^(http:\/\/)?([^\/]+)/i", $HTTP_SERVER_VARS['HTTP_REFERER'], $matches); $fhost = $matches[2]; $get_s = db_query("select siteid from ttp_sites where furl='$fhost' limit 1");
list($s) = db_rows($get_s); if ($s < 1) { $s = 0; } $badips = Array("^127.","^10.","^172.16.","^172.17.","^172.18.","^172.19.","^172.2","^172.16.30","^172.16.31","^192.168.");
if(isset($_SERVER[HTTP_VIA])) { if(isset($_SERVER[HTTP_X_FORWARDED_FOR])) {$x = 0; while(!isset($ip)) {$badip = $badips[$x]; if(ereg($badip, $_SERVER[HTTP_X_FORWARDED_FOR])) {$ip = $_SERVER[REMOTE_ADDR];}
if($x == count($badips)-1) {$ip = $_SERVER[HTTP_X_FORWARDED_FOR];} $x++;} } else {$ip = "";} } else { $ip = $_SERVER[REMOTE_ADDR];}
$today_d2 = date("ymd"); $today_d2b = $today_d2."0000"; function dotop(){ $top_text = implode('',file("top12.max")); $top_q1 = db_query("select a.siteid sid, sitename, count(distinct ipaddr) rnk from ttp_sites a left join ttp_traffic b on b.siteid=a.siteid group by a.siteid order by rnk DESC");
for ($count = 1; $top_q1r = mysql_fetch_array($top_q1); ++$count) { $top_text = str_replace("<ttp_url$count>","ttpro/out.php?out=".$top_q1r["sid"],$top_text); $top_text = str_replace("<ttp_name$count>",urldecode($top_q1r["sitename"]),$top_text); $top_text = str_replace("<ttp_num$count>",urldecode($top_q1r["rnk"]),$top_text); } @unlink("top12.htm");$fp = fopen("top12.htm", "w"); fputs($fp, "$top_text");
fclose($fp); } if ((date("ymd") > @date("ymd", filemtime("top12.htm"))) && is_writeable("top12.htm")){ $clean_q = db_query("select duniq, dprox, dprod, emailw, furl from ttp_settings limit 1"); $clean_r = mysql_fetch_array($clean_q);
$clean_q2 = db_query("select distinct a.siteid sid, email, count(ipaddr) rawz, ifnull((count(ipaddr))/(count(distinct ipaddr))*100,100) uniqz,  ifnull(sum(prox)/(count(distinct ipaddr))*100,0) proxz, ifnull(sum(click)/(count(distinct ipaddr))*100,0) clickz from ttp_sites a left join ttp_traffic b on a.siteid=b.siteid where perm=0 group by a.siteid having rawz>0 and uniqz<".$clean_r["duniq"]." or proxz>".$clean_r["dprox"]." or clickz<".$clean_r["dprod"]);
while (($clean_r2 = mysql_fetch_array($clean_q2)) && (is_writeable("top12.htm"))){ db_query("delete from ttp_sites where siteid=".$clean_r2["sid"]." limit 1"); if ($clean_r["emailw"] == 1){ mail($clean_r2["email"], "Trade Deleted","Your site has been removed from ".$clean_r2["furl"],"From: ".$clean_r2["email"]."\r\nReply-To: ".$clean_r2["email"]."\r\nX-Mailer: PHP/" . phpversion());
}} if (db_query("update ttp_sites set sent=0") == 0) {$facked = 3;} if (db_query("delete from ttp_traffic") == 0) {$facked = 3;} if ($facked == 3) { dotop(); }
} if ((date("ymdHi") - @date("ymdHi", filemtime("top12.htm")) >= 15) || (!(file_exists('top12.htm')))) { dotop(); }
$scheck = db_query("select siteid from ttp_sites where siteid='$s' and active>0 limit 1"); $httprefer = urlencode($HTTP_SERVER_VARS['HTTP_REFERER']);  $raddr = urlencode($_SERVER[REMOTE_ADDR]);  if(db_numrows($scheck) != 0 && $ip != "") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev) values ($s,'$raddr','$httprefer',NULL)");
} elseif (db_numrows($scheck) != 0) { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev,prox) values ($s,'$raddr','$httprefer',NULL,1)");
} elseif ($httprefer == "" && $ip != "") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev) values (0,'$raddr','bookmark',NULL)");
} elseif ($httprefer == "") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev,prox) values (0,'$raddr','bookmark',NULL,1)");
} elseif ($fhost == "www.google.com" && $ip != "") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev) values (-1,'$raddr','google',NULL)"); $s=-1;
} elseif ($fhost == "www.google.com") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev,prox) values (-1,'$raddr','google',NULL,1)"); $s=-1;
} elseif ($fhost == "search.yahoo.com" && $ip != "") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev) values (-2,'$raddr','yahoo',NULL)"); $s=-2;
} elseif ($fhost == "search.yahoo.com") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev,prox) values (-2,'$raddr','yahoo',NULL,1)"); $s=-2;
} elseif ($fhost == "www.altavista.com" && $ip != "") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev) values (-3,'$raddr','altavista',NULL)"); $s=-3;
} elseif ($fhost == "www.altavista.com") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev,prox) values (-3,'$raddr','altavista',NULL,1)"); $s=-3;
} elseif (db_numrows($scheck) == 0 && $ip != "") { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev) values (0,'$raddr','$httprefer',NULL)");
} elseif (db_numrows($scheck) == 0) { db_query("insert into ttp_traffic (siteid,ipaddr,refer,datev,prox) values (0,'$raddr','$httprefer',NULL,1)"); }
db_free($scheck); db_close(); echo "<script language=\"JavaScript\">\n document.cookie='ttpro_free=".$s."|".urlencode($_SERVER[REMOTE_ADDR])."|".urlencode($HTTP_SERVER_VARS['HTTP_REFERER'])."; expires=Monday, 23-Aug-10 01:01:01 GMT;';\n</script>\n";echo @ implode('',file("top12.htm")); exit;
?> 
