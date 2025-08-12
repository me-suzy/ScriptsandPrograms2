<?php
/******************************************************************************/
/*                         (c) CN-Software CNStats                            */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/
error_reporting(E_ALL & ~E_NOTICE);

function CheckTable($name,$T) {

	$R=Array();
	while (list ($key, $val) = each ($T)) $R[$val]=1;

	print "<LI>Checking table <B>".$name."</B>...";
	$r=mysql_query("SHOW FIELDS FROM ".$name);
	if (mysql_errno()!=0) {
		print "<font color=red>".mysql_error()."</font><br><br><a href='javascript:history.back();'>Back</a>";
		die();
		}
	$Y=Array();
	while ($a=mysql_fetch_array($r)) $Y[$a[0]]=1;
	if (count($R)!=count($Y)) {
		print "<font color=red>Table ".$name." is not a CNStats 2.1 table.</font><br><br><a href='javascript:history.back();'>Back</a>";
		die();
		}
	while (list ($key, $val) = each ($R)) {
		if ($Y[$key]!=1) {
		print "<font color=red>Table ".$name." is not a CNStats 2.1 table.</font><br><br><a href='javascript:history.back();'>Back</a>";
		die();
		}
		}
	print "Ok<br>";
	}

$chost="http://".$HTTP_SERVER_VARS["HTTP_HOST"];

$CCODE[0]="&lt;SCRIPT language=\"JavaScript\"&gt;
cnsd=document;cnsd.cookie=\"b=b\";cnsc=cnsd.cookie?1:0;
document.write('&lt;img src=\"".$chost."/cnstats/cntg.php?c='+cnsc+'&r='+escape(cnsd.referrer)+'&p='+escape(cnsd.location)+'\" width=\"1\" height=\"1\" border=\"0\"&gt;');
&lt;/SCRIPT&gt;&lt;NOSCRIPT&gt;&lt;img src=\"".$chost."/cnstats/cntg.php?468&c=0\" width=\"1\" height=\"1\" border=\"0\"&gt;&lt;/NOSCRIPT&gt;";

$CCODE[1]="&lt;SCRIPT language=\"JavaScript\"&gt;
cnsd=document;cnsd.cookie=\"b=b\";cnsc=cnsd.cookie?1:0;
document.write('&lt;img src=\"".$chost."/cnstats/cntg.php?c='+cnsc+'&r='+escape(cnsd.referrer)+'&p='+escape(cnsd.location)+'\" width=\"88\" height=\"31\" border=\"0\"&gt;');
&lt;/SCRIPT&gt;&lt;NOSCRIPT&gt;&lt;img src=\"".$chost."/cnstats/cntg.php?468&c=0\" width=\"88\" height=\"31\" border=\"0\"&gt;&lt;/NOSCRIPT&gt;";

$CCODE[2]="<?
include \"".str_replace("install.php","cnt.php",$HTTP_SERVER_VARS["SCRIPT_FILENAME"])."\";
?>";

$SOFTTITLE="CNStats 2.1";
$TOTAL=2;
$step=intval($HTTP_GET_VARS["step"]);
if (isset($HTTP_POST_VARS["step"])) $step=intval($HTTP_POST_VARS["step"]);

$ispng=function_exists("imagepng")?1:0;

function install_title($c,$t,$text) {
	$ttl="Step $c/$t - $text";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?=$ttl;?></TITLE>
<STYLE TYPE="text/css">
<!--
select,input,td,th,body {font-family:tahoma,sans-serif;font-size:11px;}
a,a:visited {text-decoration:none;color:blue;}
a:hover {text-decoration:underline}
.t0 {background-color:#D4F3D7;}
.t1 {background-color:#E7F9EA;}
.t2 {background-color:#F2FCF4;}
.ttl {width:100%;background:#B8E1BD;background-image:url("img/bg.gif");}
.m0 {margin:0px;}
//-->
</STYLE>
</HEAD>
<BODY bgcolor="white">
<table cellspacing=0 cellpadding=5 border=0 width=100%><tr><th class="ttl" style="text-align:left;"><?=$ttl;?></th></tr></table>
<?
	}

function install_bottom() {
	print "</BODY></HTML>";
	}

function printvars($A) {
	while (list ($key, $val) = each ($A)) {
		$val=StripSlashes(htmlspecialchars($val));
		print "<input type=hidden name=\"$key\" value=\"$val\">\n";
		}	
	}

if ($HTTP_POST_VARS["step"]==1) {
	$step=2;
	$error="";
	if (empty($HTTP_POST_VARS["sqlhost"])) $error.="<LI>MySql host was not set";
	if (empty($HTTP_POST_VARS["dbname"])) $error.="<LI>Database name was not set";
	if (empty($HTTP_POST_VARS["adminpassword2"])) $error.="<LI>Administrator's password was not set";
	if ($HTTP_POST_VARS["adminpassword2"]!=$HTTP_POST_VARS["adminpassword1"]) $error.="<LI>Two copies of the administrator's password do not match";
	$HTTP_POST_VARS["savelog"]=intval($HTTP_POST_VARS["savelog"]);
	$HTTP_POST_VARS["type"]=intval($HTTP_POST_VARS["type"]);
	if (strlen($HTTP_POST_VARS["inkcolor"])!=7 && $HTTP_POST_VARS["type"]==1) $error.="<LI>Colour was not set correctly. Colour should consist of 7 symbols and begin with &quot;#&quot;";
	$HTTP_POST_VARS["adminpassword"]=$HTTP_POST_VARS["adminpassword2"];

	$STATS_CONF["cnsoftwarelogin"]=$HTTP_POST_VARS["cnsoftwarelogin"];
	$STATS_CONF["regpassword"]=$HTTP_POST_VARS["regpassword"];
	$STATS_CONF["sqlhost"]=$HTTP_POST_VARS["sqlhost"];
	$STATS_CONF["sqluser"]=$HTTP_POST_VARS["sqluser"];
	$STATS_CONF["sqlpassword"]=$HTTP_POST_VARS["sqlpassword"];
	$STATS_CONF["dbname"]=$HTTP_POST_VARS["dbname"];
	$STATS_CONF["adminpassword"]=$HTTP_POST_VARS["adminpassword"];
	$STATS_CONF["adminpassword1"]=$HTTP_POST_VARS["adminpassword1"];
	$STATS_CONF["adminpassword2"]=$HTTP_POST_VARS["adminpassword2"];
	$COUNTER["savelog"]=$HTTP_POST_VARS["savelog"];
	$COUNTER["type"]=$HTTP_POST_VARS["type"];
	$COUNTER["exmask"]=$HTTP_POST_VARS["exmask"];
	$COUNTER["exip"]=$HTTP_POST_VARS["exip"];

	$COUNTER["inkR"]=intval(hexdec(substr($HTTP_POST_VARS["inkcolor"],1,2)));
	$COUNTER["inkG"]=intval(hexdec(substr($HTTP_POST_VARS["inkcolor"],3,2)));
	$COUNTER["inkB"]=intval(hexdec(substr($HTTP_POST_VARS["inkcolor"],5,2)));

	if (empty($COUNTER["exip"])) $COUNTER["exip"]="0.0.0.0";
	if (empty($COUNTER["exmask"])) $COUNTER["exmask"]="255.255.255.255";

	if (empty($error)) {
		if (!@mysql_connect($STATS_CONF["sqlhost"],$STATS_CONF["sqluser"],$STATS_CONF["sqlpassword"])) $error.="<LI>Can not connect to database. (MySql message: ".mysql_error().")";
		else {
			install_title(2,$TOTAL,"Building configuration");
			print "<UL>";
			if (!@mysql_select_db($STATS_CONF["dbname"])) {
				print "<LI>Database <B>".$STATS_CONF["dbname"]."</B> not found...";
				print "<LI>Creating...";
				mysql_query("CREATE DATABASE `".$STATS_CONF["dbname"]."`");
				if (mysql_errno()!=0) {
					print "<font color='red'>".mysql_error()."</font><br><br><a href='javascript:history.back();'>Back</a>";
					exit;
					}
				print "Ok";
				if (!@mysql_select_db($STATS_CONF["dbname"])) { 
					print "<font color='red'>".mysql_error()."</font><br><br><a href='javascript:history.back();'>Back</a>";
					exit;
					}
				}
			print "<LI>Database <B>".$STATS_CONF["dbname"]."</B> found...<br>";

			$r=mysql_query("SHOW tables") or die(mysql_error());
			$f=0;while ($a=mysql_fetch_array($r)) {
				if ($a[0]=="cns_log" && $f==0) $f=1;
				if ($a[0]=="cns_filters") $f=2;
				}

			if ($f==2) {
				print "<LI>It seems that database is as in version 2.2\n";
				}
			elseif ($f==1) {	
				$TBL=Array("language","mail_day","mail_email","mail_subject","mail_content","version","hints","gauge","percents","diagram","antialias","date_format","shortdate_format","datetime_format","datetimes_format","shortdm_format");
				CheckTable("cns_config",$TBL);
				$TBL=Array("hits","hosts","t_hits","t_hosts","last","visible","t_users","users");
				CheckTable("cns_counter",$TBL);
				$TBL=Array("hits","hosts","date","users");
				CheckTable("cns_counter_total",$TBL);
				$TBL=Array("txt");
				CheckTable("cns_exclude",$TBL);
				$TBL=Array("url","name");
				CheckTable("cns_goodies",$TBL);
				$TBL=Array("code","eng");
				CheckTable("cns_languages",$TBL);
				$TBL=Array("id","date","ip","type","page","proxy","agent","referer","uid","type1","res","depth","cookie","language","country");
				CheckTable("cns_log",$TBL);
				$TBL=Array("id","ip");
				CheckTable("cns_today",$TBL);
				$TBL=Array("ip1","ip2","title","id","uniqueid");
				CheckTable("cns_subnets",$TBL);

				print "<LI>Updating table cns_config\n";
				mysql_query("ALTER table cns_config ADD column hash varchar(32) NOT NULL default ''") or die(mysql_error());

				print "<LI>Creating table cns_filters\n";
				mysql_query("CREATE TABLE `cns_filters` (`id` int(11) NOT NULL auto_increment,`txt` varchar(255) NOT NULL default '',`title` varchar(255) NOT NULL default '',PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=18;") or die(mysql_error());
				print "<LI>Creating table cns_size\n";
				mysql_query("CREATE TABLE `cns_size` (`date` date NOT NULL default '0000-00-00',`size` int(11) NOT NULL default '0',PRIMARY KEY  (`date`)) TYPE=MyISAM;") or die(mysql_error());
    
				print "<LI>Inserting default data into cns_filters\n";
				mysql_query("INSERT INTO `cns_filters` VALUES (1, '-', 'English language');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (2, '3|||0|||1|||en', 'English language');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (3, '-', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (4, '2|||0|||4|||http://yandex.ru/', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (5, '2|||0|||4|||http://www.yandex.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (6, '2|||0|||2|||google.*/search', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (7, '2|||0|||4|||http://search.msn.', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (8, '2|||0|||4|||http://search.yahoo.', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (9, '2|||0|||4|||http://www.ya.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (10, '2|||0|||4|||http://ya.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (11, '2|||0|||4|||http://sm.aport.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (12, '2|||0|||4|||http://search.rambler.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (13, '2|||0|||4|||http://go*.mail.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (14, '2|||0|||4|||http://www.altavista.com', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (15, '2|||0|||4|||http://altavista.com', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (16, '2|||0|||4|||http://ie*.rambler.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (17, '2|||0|||4|||http://results.metabot.ru', 'Exclude jumps from search systems');") or die(mysql_error());
    
				}
			/* Installing */
			else {
				print "<LI>Creating table cns_config\n";
				mysql_query("CREATE TABLE `cns_config` (`language` text,`mail_day` tinyint(4) default '1',`mail_email` varchar(32) default NULL,`mail_subject` varchar(64) default '[%d.%m.%Y] CNStats report',`mail_content` tinyint(4) default '1',`version` int(11) NOT NULL default '20',`hints` int(11) NOT NULL default '1',`gauge` int(11) NOT NULL default '1',`percents` int(11) NOT NULL default '0',`diagram` tinyint(4) NOT NULL default '1',`antialias` tinyint(4) NOT NULL default '1',`date_format` varchar(32) NOT NULL default '',`shortdate_format` varchar(32) NOT NULL default '',`datetime_format` varchar(32) NOT NULL default '',`datetimes_format` varchar(32) NOT NULL default '',`shortdm_format` varchar(32) NOT NULL default '',`hash` varchar(32) NOT NULL default '') TYPE=MyISAM;") or die(mysql_error());
				print "<LI>Creating table cns_counter\n";
				mysql_query("CREATE TABLE `cns_counter` (`hits` bigint(20) default NULL,`hosts` bigint(20) default NULL,`t_hits` bigint(20) default NULL,`t_hosts` bigint(20) default NULL,`last` datetime default NULL,`visible` int(11) default '1',`t_users` int(11) default NULL,`users` int(11) default NULL) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_counter_total\n";
				mysql_query("CREATE TABLE `cns_counter_total` (`hits` int(11) default NULL,`hosts` int(11) default NULL,`date` datetime default NULL,`users` int(11) default NULL,KEY `idx` (`date`)) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_countries\n";
				mysql_query("CREATE TABLE `cns_countries` (`ip1` int(10) unsigned NOT NULL default '0',`ip2` int(10) unsigned NOT NULL default '0',`c` smallint(5) unsigned NOT NULL default '0',PRIMARY KEY  (`ip1`,`ip2`)) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_exclude\n";
				mysql_query("CREATE TABLE `cns_exclude` (`txt` text) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_goodies\n";
				mysql_query("CREATE TABLE `cns_goodies` (`url` text,`name` text) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_languages\n";
				mysql_query("CREATE TABLE `cns_languages` (`code` char(2) NOT NULL default '',`eng` text,PRIMARY KEY  (`code`),KEY `code_idx` (`code`)) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_log\n";
				mysql_query("CREATE TABLE `cns_log` (`id` int(11) NOT NULL auto_increment,`date` datetime NOT NULL default '2001-01-01 00:00:00',`ip` int(11) default NULL,`type` smallint(6) NOT NULL default '0',`page` text NOT NULL,`proxy` int(11) default NULL,`agent` text NOT NULL,`referer` text NOT NULL,`uid` int(11) NOT NULL default '0',`type1` smallint(6) NOT NULL default '0',`res` varchar(10) NOT NULL default '',`depth` smallint(6) NOT NULL default '0',`cookie` smallint(6) NOT NULL default '0',`language` varchar(32) NOT NULL default '',`country` smallint(5) unsigned NOT NULL default '0',PRIMARY KEY  (`id`),KEY `idx3` (`uid`),KEY `idx1` (`date`)) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_subnets\n";
				mysql_query("CREATE TABLE `cns_subnets` (`ip1` int(11) NOT NULL default '0',`ip2` int(11) NOT NULL default '0',`title` text NOT NULL,`id` int(11) NOT NULL default '0',`uniqueid` int(11) NOT NULL auto_increment,PRIMARY KEY  (`uniqueid`),KEY `id` (`id`)) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_today\n";
				mysql_query("CREATE TABLE `cns_today` (`id` int(11) NOT NULL auto_increment,`ip` text,PRIMARY KEY  (`id`)) TYPE=MyISAM;;") or die(mysql_error());
				print "<LI>Creating table cns_filters\n";
				mysql_query("CREATE TABLE `cns_filters` (`id` int(11) NOT NULL auto_increment,`txt` varchar(255) NOT NULL default '',`title` varchar(255) NOT NULL default '',PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=18;") or die(mysql_error());
				print "<LI>Creating table cns_size\n";
				mysql_query("CREATE TABLE `cns_size` (`date` date NOT NULL default '0000-00-00',`size` int(11) NOT NULL default '0',PRIMARY KEY  (`date`)) TYPE=MyISAM;") or die(mysql_error());

				print "<LI>Inserting default data\n";
				mysql_query("INSERT INTO `cns_counter` VALUES (0, 0, 0, 0, NOW(), 1, 0, 0);");
				mysql_query("INSERT INTO `cns_config` VALUES ('english', 0, '', '[%d.%m.%Y] CNStats report', 0, 0, 1, 1, 1, 1, 1, '', '', '', '', '', '');");

				mysql_query("INSERT INTO `cns_filters` VALUES (1, '-', 'English language');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (2, '3|||0|||1|||en', 'English language');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (3, '-', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (4, '2|||0|||4|||http://yandex.ru/', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (5, '2|||0|||4|||http://www.yandex.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (6, '2|||0|||2|||google.*/search', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (7, '2|||0|||4|||http://search.msn.', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (8, '2|||0|||4|||http://search.yahoo.', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (9, '2|||0|||4|||http://www.ya.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (10, '2|||0|||4|||http://ya.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (11, '2|||0|||4|||http://sm.aport.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (12, '2|||0|||4|||http://search.rambler.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (13, '2|||0|||4|||http://go*.mail.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (14, '2|||0|||4|||http://www.altavista.com', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (15, '2|||0|||4|||http://altavista.com', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (16, '2|||0|||4|||http://ie*.rambler.ru', 'Exclude jumps from search systems');") or die(mysql_error());
				mysql_query("INSERT INTO `cns_filters` VALUES (17, '2|||0|||4|||http://results.metabot.ru', 'Exclude jumps from search systems');") or die(mysql_error());

				mysql_query("INSERT INTO `cns_languages` VALUES ('ab','Abkhazian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('af','Afrikaans');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sq','Albanian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ar','Arabic');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('hy','Armenian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('az','Azerbaijani');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ba','Bashkir');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('eu','Basque');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('bn','Bengali');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('bi','Bislama');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('br','Breton');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('bg','Bulgarian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('my','Burmese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('be','Byelorussian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('km','Cambodian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ca','Catalan');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('zh','Chinese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('co','Corsican');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('hr','Croatian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('cs','Czech');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('da','Danish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('nl','Dutch');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('en','English');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('eo','Esperanto');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('et','Estonian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('fo','Faeroese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('fj','Fiji');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('fi','Finnish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('fr','French');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ka','Georgian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('de','German');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('el','Greek');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('kl','Greenlandic');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('gn','Guarani');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('iw','Hebrew');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('hi','Hindi');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('hu','Hungarian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('is','Icelandic');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('in','Indonesian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ik','Inupiak');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ga','Irish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('it','Italian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ja','Japanese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('jw','Javanese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('kn','Kannada');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ks','Kashmiri');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('kk','Kazakh');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ky','Kirghiz');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ko','Korean');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ku','Kurdish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('lo','Laothian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('la','Latin');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('lv','Latvian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('lt','Lithuanian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('mk','Macedonian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ms','Malay');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('mt','Maltese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('gv','Manx-Gaelic');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('mo','Moldavian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('mn','Mongolian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ne','Nepali');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('no','Norwegian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('pl','Polish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('pt','Portuguese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('rm','Rhaeto-Romance');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ro','Romanian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ru','Russian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('gd','Scots-Gaelic');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sr','Serbian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sh','Serbo-Croatian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('tn','Setswana');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sn','Shona');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sk','Slovak');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sl','Slovenian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('so','Somali');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('es','Spanish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sv','Swedish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('tg','Tajik');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('tt','Tatar');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('th','Thai');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('bo','Tibetan');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('to','Tonga');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('tr','Turkish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('tk','Turkmen');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('uk','Ukrainian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ur','Urdu');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('uz','Uzbek');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('vi','Vietnamese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('cy','Welsh');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('wo','Wolof');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ji','Yiddish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('yi','Yiddish');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('zu','Zulu');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('aa','Afar');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('am','Amharic');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('as','Assamese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ay','Aymara');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('dz','Bhutani');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('bh','Bihari');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('fa','Farsi');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('fy','Frisian');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('gl','Galician');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('gu','Gujarati');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ha','Hausa');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ia','Interlingua');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ie','Interlingue');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('iu','Inuktitut');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('rw','Kinyarwanda');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ln','Lingala');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('mg','Malagasy');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ml','Malayalam');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('rn','Kirundi');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('mi','Maori');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('mr','Marathi');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('na','Nauru');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('oc','Occitan');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('or','Oriya');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('om','Oromo');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ps','Pashto');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('pa','Punjabi');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('qu','Quechua');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sm','Samoan');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sg','Sangro');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sa','Sanskrit');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('st','Sesotho');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sd','Sindhi');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('si','Singhalese');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ss','Siswati');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('sw','Swahili');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('tl','Tagalog');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ta','Tamil');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('te','Telugu');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ts','Tsonga');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ti','Tigrinya');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('tw','Twi');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('ug','Uighur');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('xh','Xhosa');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('yo','Yoruba');");
				mysql_query("INSERT INTO `cns_languages` VALUES ('he','Hebrew');");
				}

			print "<LI>All necessary changes completed successfully.\n";
			print "</UL>";

?>
<br>
<table cellspacing=1 cellpadding=6 border=0 align=center width=650><form action=install.php method=post>
<tr><th class='ttl' colspan=2>File: config.php</th></tr>
<tr class=t1><td>
<P>Follow the instructions, described below and <b>make sure</b> that you delete the file <code>install.php</code> from the directory <code>./cnstats</code> after the installation is over
</td></tr>
<tr class=t2><td>
<?
$config="
<?

// Nullified and released by WDYL-WTN Team

\$STATS_CONF[\"dbname\"]=\"".$STATS_CONF["dbname"]."\";
\$STATS_CONF[\"sqlhost\"]=\"".$STATS_CONF["sqlhost"]."\";
\$STATS_CONF[\"sqluser\"]=\"".$STATS_CONF["sqluser"]."\";
\$STATS_CONF[\"sqlpassword\"]=\"".$STATS_CONF["sqlpassword"]."\";
\$STATS_CONF[\"adminpassword\"]=\"".md5($STATS_CONF["adminpassword"])."\";
\$STATS_CONF[\"sqlserver\"]=\"MySql\";
\$STATS_CONF[\"cnsoftwarelogin\"]=\"".$STATS_CONF["cnsoftwarelogin"]."\";

// Storing up the statistics.

\$COUNTER[\"savelog\"]=".$COUNTER["savelog"].";

// The following params are activated if you use graphical
// counter.

// Type of the counter
//
// 0 - transparent GIF (1x1 pixel) 
// 1 - PNG button 88x31. Button background is stored in file button.png.
//     (Presence of GD library is required, see http://www.php.net/manual/en/ref.image.php)
// 2 - built in PHP code
\$COUNTER[\"type\"]=".$COUNTER["type"].";

// If the image is not transparent the following three params define color
// of symbols on the counter (respectively components R,G and B)

\$COUNTER[\"inkR\"]=".$COUNTER["inkR"].";
\$COUNTER[\"inkG\"]=".$COUNTER["inkG"].";
\$COUNTER[\"inkB\"]=".$COUNTER["inkB"].";

// Do not count jumps from network excludeip/excludemask

\$COUNTER[\"excludeip\"]=\"".$COUNTER["exip"]."\";
\$COUNTER[\"excludemask\"]=\"".$COUNTER["exmask"]."\";
\$COUNTER[\"timeoffset\"]=0;

// Turn off CNStats authorization
//
// yes - turn off
// no - do not turn off

\$COUNTER[\"disablepassword\"]=\"no\";

// Send errors reports to E-Mail (E-Mail is set
// in option \$STATS_CONF[\"cnsoftwarelogin\"]

\$COUNTER[\"senderrorsbymail\"]=\"yes\";

// Adjust tables and diagrammes to the necessary resolution.
// May be 800 or 1024

\$COUNTER[\"resolution\"]=800;
?>";

?>
<P>Create file <code>config.php</code> in the directory <code>./cnstats</code> and insert the below code into it:<br></P>
<P>Try this really nice script, if u like, buy it ! //WDYL-WTN</P>

<textarea readonly name=config style='width:100%;height:450px;'><?=htmlspecialchars($config);?></textarea></td></tr>
</td></tr>
<tr class=t1><td>
<br><P>The following code should be inserted in the top part of every page of the web-site. It activates the stats system. </P>

<textarea readonly name=config style='width:100%;height:140px;'><?=$CCODE[$COUNTER["type"]];?></textarea></td></tr>
</td></tr>

</form>
</table>
<?
			install_bottom();
			exit;
			} /* of else */
		} /* if (empty($error)) */
	} /* of if ($HTTP_POST_VARS["step"] */


if ($step==0) {
	if (is_file("config.php")) include "config.php";
	}
if (intval($COUNTER["savelog"])==0) $COUNTER["savelog"]=30;
if (!isset($COUNTER["type"])) $COUNTER["type"]=2; else $COUNTER["type"]=intval($COUNTER["type"]);
if (empty($STATS_CONF["sqlhost"])) $STATS_CONF["sqlhost"]="localhost";
if (empty($STATS_CONF["sqluser"])) $STATS_CONF["sqluser"]="root";
if (empty($STATS_CONF["dbname"])) $STATS_CONF["dbname"]="cnstats";

$cntstr=sprintf("#%02X%02X%02X",$COUNTER["inkR"],$COUNTER["inkG"],$COUNTER["inkB"]);

install_title(1,$TOTAL,"Enter data");

if (!empty($error)) {
	print "<P><b><font color=red>The following mistakes have occured while filling in the form</font></B>\n<UL>".$error."</UL>";
	}
?>
<script language="JavaScript" type="text/javascript">
<!--
function upd(v) {
	var i=document.getElementById("cnsoftwarelogin");
	i.value=v;
	}
//-->
</script>

<br>
<form action=install.php method=post>
<table cellspacing=1 cellpadding=5 border=0 align=center width=650>
<input type=hidden name=step value='1'>
<tr><th class=ttl colspan=2>MySql connection parameters</th></tr>
<tr class=t1><td width=100%>MySql server (for example localhost)</td><td><input style='width:300px;' type=text name=sqlhost value='<?=htmlspecialchars($STATS_CONF["sqlhost"]);?>'></td></tr>
<tr class=t2><td>MySql connection login</td><td><input style='width:300px;' type=text name=sqluser value='<?=htmlspecialchars($STATS_CONF["sqluser"]);?>'></td></tr>
<tr class=t1><td>MySql connection password</td><td><input style='width:300px;' type=text name=sqlpassword value='<?=htmlspecialchars($STATS_CONF["sqlpassword"]);?>'></td></tr>
<tr class=t2><td>Database name</td><td><input style='width:300px;' type=text name=dbname value='<?=htmlspecialchars($STATS_CONF["dbname"]);?>'></td></tr>
<tr><th class=ttl colspan=2>Administrator's data</th></tr>
<tr class=t2><td>Administrators login</td><td><input style='width:300px;'  id='cnsoftwarelogin' type=text name=cnsoftwarelogin value='<?=htmlspecialchars($STATS_CONF["cnsoftwarelogin"]);?>'></td></tr>
<tr class=t1><td>Administrators password</td><td><input style='width:300px;' type=password name=adminpassword1 value='<?=htmlspecialchars($STATS_CONF["adminpassword1"]);?>'></td></tr>
<tr class=t2><td>Retype password</td><td><input style='width:300px;' type=password name=adminpassword2 value='<?=htmlspecialchars($STATS_CONF["adminpassword2"]);?>'></td></tr>
<tr><th class=ttl colspan=2>Statistics saving</th></tr>
<tr class=t2><td>Save statistics for the period of:</td><td>

<select style='width:300px;' name=savelog>
<?
for ($i=1;$i<31;$i++) print "<OPTION value='$i' ".($i==$COUNTER["savelog"]?"selected":"").">$i day".($i>1?"s":"")."</option>\n";
?>
</select>

</td></tr>
<tr><th class=ttl colspan=2>Type of the counter</th></tr>
<tr class=t1><td colspan=2>
<table cellspacing=0 cellpadding=0 border=0>
<tr><td valign=top><input value=2 type=radio name=type <?=($COUNTER["type"]==2)?"checked":"";?>></td><td>Built in PHP page code (recommended if PHP is used at your site)</td></tr>
<tr><td valign=top><input value=0 type=radio name=type <?=($COUNTER["type"]==0)?"checked":"";?>></td><td>Trasparent GIF 1x1 pixel</td></tr>
<tr><td valign=top><input value=1 type=radio <? if ($ispng==0) print "disabled"?> name=type <?=($COUNTER["type"]==1)?"checked":"";?>></td><td>PNG button 88x31. (Total hits, hosts today, total hosts)
<br>
<?
if ($ispng==0) print "<font color=red><b>Requires GD library (see <a href=http://www.php.net/manual/en/ref.image.php target=_blank>http://www.php.net/manual/en/ref.image.php</a>)</b></font>";
?>
</td></tr>
</table>
</td></tr>
<tr class=t2><td>Symbols color (for PNG counter):</td><td><input <? if ($ispng==0) print "disabled"?> style='width:300px;' type=text name=inkcolor value='<?=htmlspecialchars($cntstr);?>'></td></tr>

<tr><th class=ttl colspan=2>Exclude from statistics</th></tr>
<tr class=t1><td>IP (e.g. <?=$HTTP_SERVER_VARS["REMOTE_ADDR"];?>):</td><td><input style='width:300px;' type=text name=exip value='<?=htmlspecialchars($exip);?>'></td></tr>
<tr class=t2><td>Mask (e.g. 255.255.255.255):</td><td><input style='width:300px;' type=text name=exmask value='<?=htmlspecialchars($exmask);?>'></td></tr>
<tr class=t1><td colspan=2 align=right><input type=submit value='Proceed &gt;&gt;'></td></tr>
</table></form>

<?
install_bottom()
?>