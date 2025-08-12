<?
if (isset($_SERVER["DOCUMENT_ROOT"])) $CNSTATS_DR=$_SERVER["DOCUMENT_ROOT"];
elseif (isset($HTTP_SERVER_VARS["DOCUMENT_ROOT"])) $CNSTATS_DR=$HTTP_SERVER_VARS["DOCUMENT_ROOT"];
elseif (isset($DOCUMENT_ROOT)) $CNSTATS_DR=$DOCUMENT_ROOT;

if ($CNSTATS_DR[strlen($CNSTATS_DR)-1]!="/") $CNSTATS_DR.="/";

if (!isset($STATS_CONF["dbname"])) include $CNSTATS_DR."cnstats/config.php";

function cnstats_sql_query($query,$CONN) {
	GLOBAL $LANG,$STATS_CONF,$COUNTER;

	if ($STATS_CONF["sqlserver"]="MySql") {
		$r=@mysql_db_query($STATS_CONF["dbname"],$query,$CONN);
		if (mysql_errno($CONN)!=0) {
			if ($COUNTER["senderrorsbymail"]=="yes" && !empty($STATS_CONF["cnsoftwarelogin"])) {
				mail($STATS_CONF["cnsoftwarelogin"],"CNStats MySql Error",">A fatal MySQL error occured\n\n".mysql_error()."\nQuery:\n------------\n".$query."\n-----------\nURL: http://".$HTTP_SERVER_VARS["HTTP_HOST"].$HTTP_SERVER_VARS["REQUEST_URI"]."\nDate: ".date($LANG["datetime_format"]));
				}
			die("<font color=red><B>A fatal MySQL error occured:</B></font><br><br>\n\n".mysql_error($CONN)."<br><br>\n\n ".$query);
			}
		}
	return($r);
	}

function nmail($CONN) {
	GLOBAL $LANG,$COUNTER;

	$CONFIG=mysql_fetch_array(cnstats_sql_query("SELECT language FROM cns_config",$CONN));
	include "lang/lang_".$CONFIG["language"].".php";

	$MAIL=mysql_fetch_array(cnstats_sql_query("SELECT mail_day, mail_email, mail_subject, mail_content FROM cns_config",$CONN));
	if (!empty($MAIL["mail_email"])) {
	
		$need=0;$per=1;
		if ($a["mail_day"]==0) {$need=1;$pper=$LANG["yesterday"];}
		else if ($a["mail_day"]==date("d",time()+$COUNTER["timeoffset"])) {$need=1;$per=7;$pper=$LANG["last7dayes"];}
	
	
		if ($need==1) {
			$mail="";
			if (($MAIL["mail_content"]&1)!=0) {
				$mail.=$LANG["attendanceper"]." $pper\n\n      ".$LANG["date"]."     ".$LANG["hostss"]."     ".$LANG["sessionss"]."      ".$LANG["hitss"]."\n-------------------------------------------\n";
	
				$r=cnstats_sql_query("select LEFT(date,10) as dt,hits,hosts,users from cns_counter_total ORDER BY date desc LIMIT $per",$CONN);
				while (($a=mysql_fetch_array($r))) {
					$str=sprintf("%s %10d %10d %10d\n",$a["dt"],$a["hosts"],$a["sessions"],$a["hits"]);
					$mail.=$str;
					}
				}
	
			if (($MAIL["mail_content"]&2)!=0) {
				$mail.="\n>------------------------------------------\n\n".$LANG["yesterdayreferers"]."\n\n".$LANG["count"]."                         ".$LANG["Ppage"]."\n----------+--------------------------------\n";
	
	
				$t1=date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-1,date("Y"))+$COUNTER["timeoffset"]);
				$t2=date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-1,date("Y"))+$COUNTER["timeoffset"]);
				$r=cnstats_sql_query("select referer,count(referer) as cnt from cns_log WHERE referer!='' AND date>='$t1' AND date<'$t2' group by referer order by 2 desc LIMIT 20",$CONN);
				while (($a=mysql_fetch_array($r))) {
					$a["referer"]=str_replace(" ","%20",$a["referer"]);
					while (strlen($a["cnt"])<10) $a["cnt"]=".".$a["cnt"];
					$str=sprintf("%s|%s\n",$a["cnt"],$a["referer"]);
					$mail.=$str;
					}
				}

			if (($MAIL["mail_content"]&4)!=0) {
				$mail.="\n>------------------------------------------\n\n".$LANG["Sspages"]."\n\n".$LANG["count"]."                         ".$LANG["Ppage"]."\n----------+--------------------------------\n";
				$r=cnstats_sql_query("select page,count(page) as cnt from cns_log WHERE page!='' AND date>='$t1' AND date<'$t2' group by page order by 2 desc LIMIT 20",$CONN);
				while (($a=mysql_fetch_array($r))) {
					while (strlen($a["cnt"])<10) $a["cnt"]=".".$a["cnt"];
					$a["page"]=str_replace(" ","%20",urldecode($a["page"]));
					$str=sprintf("%s|%s\n",$a["cnt"],$a["page"]);
					$mail.=$str;
					}

				$mail=$mail.date("Y-m-d H:i:s",time()+$COUNTER["timeoffset"])."\n";
				}
	
			$MAIL["mail_subject"]=str_replace("%Y",date("Y",time()+$COUNTER["timeoffset"]),$MAIL["mail_subject"]);
			$MAIL["mail_subject"]=str_replace("%d",date("d",time()+$COUNTER["timeoffset"]),$MAIL["mail_subject"]);
			$MAIL["mail_subject"]=str_replace("%m",date("m",time()+$COUNTER["timeoffset"]),$MAIL["mail_subject"]);
	        mail($MAIL["mail_email"],$MAIL["mail_subject"],$mail,"From: \"CNStats\" <".$MAIL["mail_email"].">\nContent-type: text/plain; charset=windows-1251","-f".$MAIL["mail_email"]);
			}
		}
	}

function midnight_calc() {
	global $COUNTER;

	$r=cnstats_sql_query("SHOW TABLE STATUS",$COUNTER["CONN"]);
	$size=0;
	while ($a=mysql_fetch_array($r,MYSQL_ASSOC)) {
		while (list ($key, $val) = each ($a)) {
			if ($key=="Data_length" && (substr($tname,0,4)=="cns_")) $size+=$val;
			if ($key=="Index_length" && (substr($tname,0,4)=="cns_")) $size+=$val;
			if ($key=="Name") $tname=$val;
	    	}
		}
	cnstats_sql_query("INSERT INTO cns_size SET date=NOW(), size='".$size."';",$COUNTER["CONN"]);

	$sdays=intval($COUNTER["savelog"]);if ($sdays<1 || $sdays>30) $sdays=30;
	cnstats_sql_query("DELETE FROM cns_today",$COUNTER["CONN"]);
	cnstats_sql_query("DELETE FROM cns_log WHERE date<'".date("Y-m-d H:i:s",mktime(0,0,0,date("m") ,date("d")-$sdays,date("Y"))+$COUNTER["timeoffset"])."'",$COUNTER["CONN"]);
	nmail($COUNTER["CONN"]);
	mysql_close($COUNTER["CONN"]);	
	}

function stats_hit($sqlhost,$sqluser,$sqlpassword,$db_name) {
	GLOBAL $HTTP_SERVER_VARS,$HTTP_COOKIE_VARS,$STATS_CONF,$COUNTER,$HTTP_GET_VARS;

	$noclose=false;

	$eip=ip2long($COUNTER["excludeip"]);
	$emask=ip2long($COUNTER["excludemask"]);
	if ((ip2long($HTTP_SERVER_VARS["REMOTE_ADDR"])&$emask)==($eip&$emask)) {
		$CONN=mysql_connect($STATS_CONF["sqlhost"],$STATS_CONF["sqluser"],$STATS_CONF["sqlpassword"],TRUE);
		mysql_select_db($STATS_CONF["dbname"],$CONN);
		$r=mysql_query("SELECT t_hits,hits,hosts FROM cns_counter") or die(mysql_error());
		$STATS_CONF=mysql_fetch_array($r,MYSQL_ASSOC);
		mysql_close($CONN);
		return;
		}

	// Connecting to DB
	if (version_compare(phpversion(), "4.2.0", ">=")) 
		$CONN=@mysql_connect($STATS_CONF["sqlhost"],$STATS_CONF["sqluser"],$STATS_CONF["sqlpassword"],TRUE);
	else
		$CONN=@mysql_connect($STATS_CONF["sqlhost"],$STATS_CONF["sqluser"],$STATS_CONF["sqlpassword"]);

	if (!$CONN) return;
	if (!@mysql_select_db($STATS_CONF["dbname"],$CONN)) return;

	$r=mysql_query("select GET_LOCK('cnstats',60);",$CONN);
	if (mysql_result($r,0,0)!=1) return;
	$r=cnstats_sql_query("SELECT DATE_FORMAT(last,'%d') FROM cns_counter",$CONN);

	if (mysql_result($r,0,0)!=date("d",time()+$COUNTER["timeoffset"])) {
		$date=date("Y-m-d H:i:s",mktime(0,0,0,date("m")  ,date("d")-1,date("Y"))+$COUNTER["timeoffset"]);
		$r=cnstats_sql_query("SELECT hits,hosts,users FROM cns_counter",$CONN);
		cnstats_sql_query("UPDATE cns_counter SET hits=0, hosts=0, users=0, last='".date("Y-m-d H:i:s",time()+$COUNTER["timeoffset"])."';",$CONN);
		for ($i=0;$i<mysql_num_rows($r);$i++) {
			$hits=mysql_result($r,$i,0);
			$hosts=mysql_result($r,$i,1);
			$users=mysql_result($r,$i,2);
			cnstats_sql_query("INSERT INTO cns_counter_total set hits='".$hits."',hosts='".$hosts."',date='".$date."', users='".$users."';",$CONN);
			}
		$COUNTER["CONN"]=$CONN;
		$noclose=true;
		register_shutdown_function("midnight_calc");
		} /* of if (mysql_result( */

	$r=mysql_query("SELECT RELEASE_LOCK('cnstats');",$CONN);
             	
	$agent=htmlspecialchars($HTTP_SERVER_VARS["HTTP_USER_AGENT"]);
	$ip=$HTTP_SERVER_VARS["REMOTE_ADDR"];
	$proxy="";
	if (!empty($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])) {
	    $proxy=$ip;
    	$ip=$HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
	    }
	$zpos=strrpos($ip,",");
	if ($zpos!=0) $ip=trim(substr($ip,$zpos+1));
	$ip=ip2long($ip);

	$zpos=strrpos($proxy,",");
	if ($zpos!=0) $proxy=trim(substr($proxy,$zpos+1));
	$proxy=ip2long($proxy);


	$c=intval($c);
	$depth=intval($d);
	if ($STATS_CONF["graph"]==1) {
		$page=$STATS_CONF["page"];
		$referer=$STATS_CONF["referer"];
		}
	else {
		if (isset($STATS_CONF["page"])) $page=urlencode(htmlspecialchars($STATS_CONF["page"]));
		else $page=urlencode(htmlspecialchars("http://".$HTTP_SERVER_VARS["HTTP_HOST"].$HTTP_SERVER_VARS["REQUEST_URI"]));

		if (isset($STATS_CONF["referer"])) $referer=htmlspecialchars($STATS_CONF["referer"]);
		else $referer=htmlspecialchars($HTTP_SERVER_VARS["HTTP_REFERER"]);
		}
	$res=htmlspecialchars($rs);
	$date=date("Y-m-d H:i:s",time()+$COUNTER["timeoffset"]);
	$language=htmlspecialchars($HTTP_SERVER_VARS["HTTP_ACCEPT_LANGUAGE"]);

	$flag=0;
	$r=cnstats_sql_query("SELECT count(*) FROM cns_today WHERE ip='$ip'",$CONN);
	if ($r) $is=mysql_result($r,0,0);
	else $is=0;
	if ($is==0) {
	    cnstats_sql_query("INSERT INTO cns_today (ip) VALUES ('$ip')",$CONN);
	    $flag=1;
	    }

	// Geting user ID
	if ($STATS_CONF["is_cookie"]==1) {
		$uid=intval($HTTP_COOKIE_VARS["counter"]);

		if ($uid!=0) $type1=0;
		else {
			$r=cnstats_sql_query("select max(uid) from cns_log",$CONN);
			$uid=mysql_result($r,0,0)+1;
			$type1=1;
			@setcookie("counter",$uid,mktime(23,59,59,date("m"),date("d"),date("Y")),"/",$STATS_CONF["cookie_host"]);
			}
		}
	else $type1=$flag;

	// Get country for unique hosts
	$country=0;
	if ($flag==1) {
		$nip=$HTTP_SERVER_VARS["REMOTE_ADDR"];
		$zpos=strrpos($nip,",");
		if ($zpos!=0) $nip=trim(substr($nip,$zpos+1));
		$r=cnstats_sql_query("SELECT c FROM cns_countries WHERE INET_ATON('".$nip."')>=ip1 AND INET_ATON('".$nip."')<=ip2 LIMIT 1;",$CONN);
		if (mysql_num_rows($r)==1)	$country=mysql_result($r,0,0);
		}
	cnstats_sql_query("INSERT DELAYED INTO cns_log (date,ip,type,proxy,page,agent,referer,language,type1,uid,res,depth,cookie,country) VALUES ('$date','$ip',$flag,'$proxy','$page','$agent','$referer','$language','$type1','$uid','$res','$depth','$c','".$country."')",$CONN);

	$r=cnstats_sql_query("SELECT hits,hosts,t_hits,t_hosts,users,t_users FROM cns_counter",$CONN);
	if (mysql_num_rows($r)!=1) {
		cnstats_sql_query("INSERT INTO cns_counter SET hits='1', hosts='1', t_hits='1', t_hosts='1', users='1', t_users='1'",$CONN);
		$hits=1;
		$hosts=1;
		$t_hits=1;
		$t_hosts=1;
		}
	else {
		$hits=mysql_result($r,0,0)+1;
		$t_hits=mysql_result($r,0,2)+1;
		$hosts=mysql_result($r,0,1);
		$t_hosts=mysql_result($r,0,3);
		$users=mysql_result($r,0,4);
		$t_users=mysql_result($r,0,5);
		if ($flag==1) {
			$hosts++;
			$t_hosts++;
			}
		if ($type1==1) {
		$users++;
			$t_users++;
			}
		cnstats_sql_query("UPDATE cns_counter SET hits='$hits', hosts='$hosts', t_hits='$t_hits', t_hosts='$t_hosts', users='$users', t_users='$t_users', last='".date("Y-m-d H:i:s",time()+$COUNTER["timeoffset"])."';",$CONN);
		}
	$STATS_CONF["hits"]=$hits;
	$STATS_CONF["hosts"]=$hosts;
	$STATS_CONF["t_hits"]=$t_hits;
	if (!$noclose) mysql_close($CONN);
	}

stats_hit($STATS_CONF["sqlhost"], $STATS_CONF["sqluser"], $STATS_CONF["sqlpassword"], $STATS_CONF["dbname"]);
?>