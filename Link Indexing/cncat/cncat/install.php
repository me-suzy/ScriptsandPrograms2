<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/
error_reporting(E_ALL & ~E_NOTICE);

$SOFTTITLE="CNCat 2.0";
$TOTAL=5;
$step=intval($_GET["step"]);
if (isset($_POST["step"])) $step=intval($_POST["step"]);

function install_title($c,$t,$text) {
	$ttl="Step $c/$t - $text";
?>
<HTML>
<HEAD>
<TITLE><?=$ttl;?></TITLE>
<STYLE>
<!--
body {font-family:verdana;font-size:11px;}
th {color:white;text-align:left;font-family:verdana;font-size:11px;}
td {font-family:verdana;font-size:11px;}
input,select {font-family:verdana;font-size:11px;}
.t1 {background-color:#EFE5F0;}
.t2 {background-color:#D9C2DC;}
//-->
</STYLE>
</HEAD>
<BODY>
<table cellspacing=0 cellpadding=6 border=0 width=100%><tr><th background=cat/tablebg.gif><?=$ttl;?></th></tr></table>
<?
	}

function install_bottom() {
	print "</BODY></HTML>";
	}

if ($step==4) {
	install_title(5,$TOTAL,"Saving configuration.");
	$config=StripSlashes($_POST["config"]);
	$config.="\n\$LANGFILE=\"lang_eng.php\";\n//\$LANGFILE=\"lang_rus.php\";\n//\$LANGFILE=\"lang_dk.php\";\n\n";

$config.="

////////////////////////////////////////////

@mysql_connect(\$db[\"host\"],\$db[\"user\"],\$db[\"password\"]);
@mysql_select_db(\$db[\"name\"]);
\$db[\"prefix\"]=\"cncat_\";

function mhtml(\$s) {
	\$s=StripSlashes(\$s);
	\$s=str_replace(\"'\",\"&#39;\",\$s);
	\$s=str_replace(\"\\\"\",\"&quot;\",\$s);
	return(\$s);
	}

\$COPY=\"<HR size=1><table><tr><td><a href=http://www.cn-software.com/><img src=cat/cnlogow.gif width=32 height=32 border=0></a></td><td><small><a href=http://www.cn-software.com/?cncat>Powered by $SOFTTITLE</a><br>&copy 2002-2004 <a href=http://www.cn-software.com/?cncat>CN-Software</a></small></td></tr></table>\";
?>
";

$ftop="";
if (is_file("_top.php")) $ftop=implode("",file("_top.php"));
else $ftop="<HTML>\n<HEAD>\n<TITLE><?=\$title;?></TITLE>\n</HEAD>\n<BODY>\n<H1>Top</H1>\n\n";
$fbottom="";
if (is_file("_bottom.php")) $fbottom=implode("",file("_bottom.php"));
else $fbottom="\n</BODY>\n</HTML>\n";

$f1=$f2=$f3=false;
$fw=@fopen("_top.php","wt");
if ($fw) {
	$f1=true;
	fputs($fw,$ftop);
	fclose($fw);
	}
$fw=@fopen("_bottom.php","wt");
if ($fw) {
	$f2=true;
	fputs($fw,$fbottom);
	fclose($fw);
	}
$fw=@fopen("config.php","wt");
if ($fw) {
	$f3=true;
	fputs($fw,$config);
	fclose($fw);
	}

?>
<br>
<table cellspacing=1 cellpadding=6 border=0 align=center width=650><form action=install.php method=post>
<tr><th background=cat/tablebg.gif colspan=2>File: config.php</th></tr>
<? if ($f1) { ?>
<tr class=t2><td>File saved successfully.</td></tr>
<? } else { ?>
<tr class=t2><td>
<P>Installation program could not save this file. This is NORMAL, correctly configured server should work this way. You have to create this file manually and copy the following text into it: </P>
<textarea readonly name=config style='width:100%;height:450px;'><?=htmlspecialchars($config);?></textarea></td></tr>
<? } ?>
<tr><th background=cat/tablebg.gif colspan=2>File: _top.php</th></tr>
<? if ($f2) { ?>
<tr class=t1><td>File saved successfully.</td></tr>
<? } else { ?>
<tr class=t1><td>
<P>Installation program could not save this file. This is NORMAL, correctly configured server should work this way. You have to create this file manually and copy the following text into it: </P>
<textarea readonly name=config style='width:100%;height:150px;'><?=htmlspecialchars($ftop);?></textarea></td></tr>
<? } ?>
<tr><th background=cat/tablebg.gif colspan=2>File: _bottom.php</th></tr>
<? if ($f3) { ?>
<tr class=t2><td>File saved successfully.</td></tr>
<? } else { ?>
<tr class=t2><td>
<P>Installation program could not save this file. This is NORMAL, correctly configured server should work this way. You have to create this file manually and copy the following text into it: </P>
<textarea readonly name=config style='width:100%;height:150px;'><?=htmlspecialchars($fbottom);?></textarea></td></tr>
<? } ?>
<tr class=t1><td>
Installation <?=$SOFTTITLE;?> completed. When you erase file <b>install.php</b>, you will be able to jump to the directory
<a href=index.php>main page</a> or <a href=admin/>administrative interface</a>.
<P>We recommend setting access rights for files config.php, _top.php and _bottom.php. Use the following commands: </P>

<PRE>
chmod 644 config.php
chmod 644 _top.php
chmod 644 _bottom.php
</PRE>

</td></tr>
<input type=hidden name=step value=5>
</form>
</table>
<?
	}

if ($step==3) {
	install_title(4,$TOTAL,"Creating tables.");
	$config=StripSlashes($_POST["config"]);
	$db["host"]=$_POST["host"];
	$db["user"]=$_POST["user"];
	$db["password"]=$_POST["password"];
	$db["name"]=$_POST["name"];

	@mysql_connect($db["host"],$db["user"],$db["password"]) or die("<P>".mysql_error());
	@mysql_select_db($db["name"]) or die("<P>".mysql_error()."<br><br><P><a href=install.php>Return to the first step of the installation.</a></p>");

	mysql_query("DROP TABLE IF EXISTS cncat_cat") or die(mysql_error());
	mysql_query("CREATE TABLE `cncat_cat` (`cid` int(11) NOT NULL auto_increment,`name` text,`parent` int(11) default NULL,`count` int(11) default '0',PRIMARY KEY  (`cid`)) TYPE=MyISAM;") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS cncat_cat_linear") or die(mysql_error());
	mysql_query("CREATE TABLE `cncat_cat_linear` (`cid` int(11) default NULL,`name` text) TYPE=MyISAM;") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS cncat_mail") or die(mysql_error());
	mysql_query("CREATE TABLE `cncat_mail` (`mid` int(11) NOT NULL default '0',`body` text NOT NULL,`active` tinyint(4) NOT NULL default '0',`from` varchar(255) NOT NULL default '',`replyto` varchar(255) NOT NULL default '',`subject` varchar(255) NOT NULL default '',`headers` text NOT NULL,PRIMARY KEY  (`mid`)) TYPE=MyISAM;") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS cncat_main") or die(mysql_error());
	mysql_query("CREATE TABLE `cncat_main` (`lid` int(11) NOT NULL auto_increment,`title` text,`description` text,`url` text,`cat1` int(11) default NULL,`gin` int(11) default NULL,`gout` int(11) default NULL,`moder_vote` int(11) default NULL,`email` text,`type` int(11) default NULL,`broken` int(11) default '0',`insert_date` datetime default NULL,`resfield1` text,`resfield2` text,`resfield3` text,`mail_sended` tinyint(4) NOT NULL default '0',PRIMARY KEY  (`lid`)) TYPE=MyISAM;") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS cncat_templates") or die(mysql_error());
	mysql_query("CREATE TABLE `cncat_templates` (`name` varchar(16) NOT NULL default '',`html` text NOT NULL,`parent` int(11) NOT NULL default '0',PRIMARY KEY  (`name`)) TYPE=MyISAM;") or die(mysql_error());
    
	mysql_query("INSERT INTO `cncat_mail` VALUES (3, 'Moderator has declined your link submission to %CATNAME%\r\n\r\n%SITENAME%\r\n%SITEURL%\r\n\r\nMost probably, your site does not correspond to our directory requirements.\r\n', 1, '%FROMEMAIL%', '', 'Your web-site have not been added to the directory %CATNAME%', '');;") or die(mysql_error());
	mysql_query("INSERT INTO `cncat_mail` VALUES (2, 'Moderator has checked you link and approved it.\r\n\r\n%SITENAME%\r\n%SITEURL%\r\n\r\n_______________\r\n%CATNAME%', 1, '%FROMEMAIL%', '', 'Your web-site has been successfully added to the directory %CATNAME%', '');;") or die(mysql_error());
	mysql_query("INSERT INTO `cncat_mail` VALUES (1, 'You web-site has been successfully submited to the directory %CATNAME%.\r\n\r\n%SITENAME%\r\n%SITEURL%\r\n\r\nIt will be checked by moderator.\r\n', 0, '%FROMEMAIL%', '', 'You web-site has been successfully submited to the directory %CATNAME%.', '');;") or die(mysql_error());
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
	mysql_query("INSERT INTO cncat_templates VALUES ('linkstop', '<UL>', 1);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('linksmiddle', '</UL>\r\n<br>\r\n<OL start=\'%NUM\'>', 1);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('linksbottom', '</OL>\r\n<table cellspacing=0 cellpadding=0 border=0 width=100%><tr><td><img src=./cat/none.gif width=1 height=1></td></tr></table>', 1);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('linksbit', '\r\n<LI>\r\n<a target=_blank href=\"jump.php?%ID\">%TITLE\r\n</a>\r\n<font color=gray>(%GIN/%GOUT) /</font> %MODERVOTE<br>\r\n%DESC <a href=\"javascript:badlink(\'%ID\')\"><small><font color=gray>[%BROKENTEXT]</font></small></a><br>\r\n<font color=gray>%URL</font>\r\n%ADMINIFACE\r\n<br><br>\r\n', 1);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('bestlinksbit', '\r\n<LI>\r\n<a target=_blank href=\"jump.php?%ID\"><B>%TITLE</B>\r\n</a>\r\n<font color=gray>(%GIN/%GOUT) /</font> %MODERVOTE %BEST<br>\r\n%DESC <a href=\"javascript:badlink(\'%ID\')\"><small><font color=gray>[%BROKENTEXT]</font></small></a><br>\r\n<font color=gray>%URL</font>\r\n%ADMINIFACE\r\n<br><br>\r\n', 1);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('newlinkstop', '<br><table width=200 align=right cellspacing=1 cellpadding=4 border=0 bgcolor=\'#896819\'><tr><td bgcolor=\'#FFEEB3\'><center><B>%NEWLINKSTEXT</B>:</center><Hr size=1>', 2);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('newlinkstbottom', '</td></tr></table>', 2);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('newlinkstbit', '\r\n<b>&raquo; <a target=_blank href=\'jump.php?%ID\' style=\'font-size:10px;\'>%TITLE</b></a><br>\r\n<small>%DESC75</small><br>\r\n<img src=./cat/none.gif width=1 height=5><br>\r\n', 2);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('searchtop', '<OL start=\'%STARTNUM\'>\r\n', 4);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('searchbottom', '</OL>\r\n', 4);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('brokenscript', '\r\n<SCRIPT language=\"JavaScript\">\r\n<!--\r\nfunction badlink(l) {\r\n	t=screen.height/2-50;\r\n	w=screen.width/2-50;\r\n	wnd=window.open(\"\",\"badlink\"+l,\"width=200,height=100,top=\"+t+\",left=\"+w);\r\n	wnd.document.write(\"<HTML><BODY style=\'font-family:verdana;font-size:11px;color:gray;\'><center><B>%BROKENSURETEXT</B><br><br>\");\r\n	wnd.document.write(\"<a href=add.php?bad=\"+l+\">%YESTEXT</a> &nbsp;|&nbsp; <a href=\'javascript:window.close();\'>%NOTEXT</a></center></BODY></HTML>\");\r\n	}\r\n// -->\r\n</SCRIPT>\r\n', 5);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('partstop', '<br><table width=100% cellspacing=0 cellpadding=0>\r\n', 3);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('partsdelimtop', '<tr>', 3);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('partsdelimbottom', '</tr>\r\n', 3);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('partsbottom', '</table><br>\r\n<table cellspacing=0 cellpadding=0 border=0 width=100% bgcolor=#E0E0E0><tr><td><img src=./cat/none.gif width=1 height=1></td></tr></table>', 3);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('partsbit', '\r\n<td valign=top width=33%>\r\n<a href=./?c=%CID><img src=./cat/ismall.gif width=16 height=12 border=0 align=absmiddle hspace=3>%CTITLE</a>\r\n(%CCOUNT)\r\n</td>', 3);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('catname', '\r\n<br><table width=100% cellspacing=0 cellpadding=0 border=0 bgcolor=#E0E0E0>\r\n<tr><td colspan=2><img src=./cat/none.gif width=1 height=1></td></tr>\r\n<tr bgcolor=#F0F0F0>\r\n<td><a href=\"./\"><img src=\"./cat/i.gif\" width=32 height=32 border=0></a></td>\r\n<td width=99% style=\'font-size:14px;color:#606060;\'>&nbsp;<a href=\"./\"><b>%MAINTEXT</b></a> &raquo; %OTHERTEXT</td>\r\n</tr></table>\r\n', 5);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('sortby', '\r\n<img src=./cat/none.gif width=1 height=4><br>\r\n<div align=right><font color=black>\r\n%SORTBYTEXT: %SORTBYPOP | %SORTBYTITLE | %SORTBYMODER\r\n</font><br></div>\r\n', 5);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('searchform', '<br>\r\n<table cellspacing=0 cellpadding=0 border=0 width=100% bgcolor=#E0E0E0>\r\n<tr><td><img src=./cat/none.gif width=1 height=1></td></tr>\r\n<tr><td align=center bgcolor=#F0F0F0>\r\n<table width=300 border=0 cellspacing=0 cellpadding=7><tr><td>\r\n<table width=300 border=0 cellspacing=1 cellpadding=0><form action=search.php><tr>\r\n<td><input value=\'%QUERYTEXT\' size=45 type=text name=q style=\'font-size:12px;height:21px;\'></td><td><input type=submit value=\'   %SEARCHTEXT   \' style=\'font-size:11px;\'></td>\r\n</td></tr></table>\r\n</td></tr></table>\r\n</td></tr></table>\r\n</center>', 4);") or die(mysql_error());
	mysql_query("INSERT INTO cncat_templates VALUES ('bmenu', '<center><br><a href=add.php><B>%ADDLINKTEXT</B></a> | <a href=admin/>%MODERATORSTEXT</a><br></center>', 5);") or die(mysql_error());

    

?>
<br>
<table cellspacing=1 cellpadding=6 border=0 align=center width=650>
<form action=install.php method=post>
<tr><th background=cat/tablebg.gif >Message</th></tr>
<tr class=t1><td><br>Tables created successfully. Click "Proceed" to continue with installation.<br><br></td></tr>
<tr class=t2><td><textarea readonly name=config style='width:100%;height:150px;'><?=htmlspecialchars($config);?></textarea></td></tr>
<tr class=t1><td align=right><input type=submit value='Proceed &gt;&gt;'></td></tr>
<input type=hidden name=step value=4>
</form>
</table>
<?
	}

if ($step==2) {
	install_title(3,$TOTAL,"Creating database.");
	$config=StripSlashes($_POST["config"]);

	$db["host"]=$_POST["host"];
	$db["user"]=$_POST["user"];
	$db["password"]=$_POST["password"];
	$db["name"]=$_POST["name"];

	@mysql_connect($db["host"],$db["user"],$db["password"]) or die("<P>".mysql_error());
	@mysql_query("CREATE DATABASE ".mysql_escape_string($db["name"])) or die("<P>".mysql_error()."<br><br><P><a href=install.php>Return to the first step of the installation.</a></p>");

?>
<br>
<table cellspacing=1 cellpadding=6 border=0 align=center width=650>
<form action=install.php method=post>
<tr><th background=cat/tablebg.gif >Message</th></tr>
<tr class=t1><td><br>Database created successfully. Click "Proceed" to continue with installation.<br><br></td></tr>
<tr class=t2><td><textarea readonly name=config style='width:100%;height:150px;'><?=htmlspecialchars($config);?></textarea></td></tr>
<tr class=t1><td align=right><input type=submit value='Proceed &gt;&gt;'></td></tr>
<input type=hidden name=step value=3>
<input type=hidden name=host value='<?=htmlspecialchars($db["host"]);?>'>
<input type=hidden name=user value='<?=htmlspecialchars($db["user"]);?>'>
<input type=hidden name=password value='<?=htmlspecialchars($db["password"]);?>'>
<input type=hidden name=name value='<?=htmlspecialchars($db["name"]);?>'>
</form>
</table>
<?
	}

// Step 1 - Get DB login and passowrd
if ($step<2) {
	$type=0;

	// Displaying the first form
	if ($step==0) {
		if (is_file("config.php")) {
			include "config.php";
			$type=1;
			}
		else {
			$CATNAME="Web-sites directory";
			$db["host"]="localhost";
			$cat["mailifnewlink"]="no";
			$cat["mailifnewlinksubject"]="New links";
			$cat["shownew"]=1;
			$cat["shownew"]=1;
			$cat["mailifnewlinkto"]=$SERVER_ADMIN;
			$cat["shownewcount"]=5;
			$cat["duptestcount"]=10;
			$cat["linksatmain"]=1;
			$cat["rows"]=3;
			}
		if ($type==0) install_title(1,$TOTAL,"Installation $SOFTTITLE");
		else install_title(1,$TOTAL,"Upgrade to $SOFTTITLE");
		}

	// First check ---------------------------------------------
	if ($step==1) {
		install_title(2,$TOTAL,"Selected data check");
		$error="";
	
		$CATNAME=$_POST["CATNAME"];
		if (empty($CATNAME)) $error.="<LI>Directory name has not been entered";
	
		$db["host"]=$_POST["host"];
		if (empty($db["host"])) $error.="<LI>MySql server name has not been entered";
		$db["user"]=$_POST["user"];
		if (empty($db["user"])) $error.="<LI>MySql server login has not been entered";
		$db["password"]=$_POST["password"];
		$db["name"]=$_POST["name"];
		if (empty($db["name"])) $error.="<LI>Database name has not been entered";
	
		$db["alogin"]=$_POST["alogin"];
		if (empty($db["alogin"])) $error.="<LI>Administrator login has not been entered";
		$db["apassword1"]=$_POST["apassword1"];
		$db["apassword2"]=$_POST["apassword2"];
		if ($db["apassword1"]!=$db["apassword2"]) $error.="<LI>Two copies of Administrator's password do not match";
		if (empty($db["apassword1"])) $error.="<LI>Administrator's password has not been entered";
		$db["apassword"]=md5($db["apassword2"]);
	
		$cat["mailifnewlink"]=$_POST["mailifnewlink"];
		$cat["mailifnewlinkto"]=$_POST["mailifnewlinkto"];
		$cat["mailifnewlinksubject"]=$_POST["mailifnewlinksubject"];
		if ($cat["mailifnewlink"]=="on") {
			$cat["mailifnewlink"]="yes";
			if (empty($cat["mailifnewlinkto"])) $error.="<LI>E-mail to send new links notifications has not been set.";
			if (empty($cat["mailifnewlinksubject"])) $error.="<LI>New links notification subject has not been set.";
			}
		else $cat["mailifnewlink"]="no";
	
		$cat["shownew"]=$_POST["shownew"];
		$cat["shownewcount"]=$_POST["shownewcount"];
		$cat["shownew"]=($cat["shownew"]=="on")?1:0;
		if (empty($cat["shownewcount"])) $cat["shownewcount"]=5;

		$cat["duptestcount"]=$_POST["duptestcount"];
		if (empty($cat["duptestcount"])) $cat["duptestcount"]=10;
		$cat["linksatmain"]=$_POST["linksatmain"];
		$cat["linksatmain"]=($cat["linksatmain"]=="on")?1:0;
		$cat["rows"]=$_POST["rows"];
		if (empty($cat["rows"])) $cat["rows"]=3;

		$cat["resfield1"]=$_POST["resfield1"];
		$cat["resfield2"]=$_POST["resfield2"];
		$cat["resfield3"]=$_POST["resfield3"];
		$cat["defaultorder"]=$_POST["defaultorder"];

		if ($_POST["onlyshow"]!=1) {
			if (!@mysql_connect($db["host"],$db["user"],$db["password"])) $error.="<LI>Can not connect to database. (MySql message: ".mysql_error().")";
			else {

// Generating config.php
$config="<?
\$db[\"host\"]=\"".$db["host"]."\";
\$db[\"user\"]=\"".$db["user"]."\";
\$db[\"password\"]=\"".$db["password"]."\";
\$db[\"name\"]=\"".$db["name"]."\";

\$db[\"alogin\"]=\"".$db["alogin"]."\";
\$db[\"apassword\"]=\"".$db["apassword"]."\";

\$cat[\"mailifnewlink\"]=\"".$cat["mailifnewlink"]."\";
\$cat[\"mailifnewlinkto\"]=\"".$cat["mailifnewlinkto"]."\";
\$cat[\"mailifnewlinksubject\"]=\"".$cat["mailifnewlinksubject"]."\";
\$cat[\"duptestcount\"]=".$cat["duptestcount"].";
\$cat[\"shownew\"]=".$cat["shownew"].";
\$cat[\"shownewcount\"]=".$cat["shownewcount"].";
\$cat[\"linksatmain\"]=".$cat["linksatmain"].";
\$cat[\"rows\"]=".$cat["rows"].";
\$cat[\"resfield1\"]=\"".$cat["resfield1"]."\";
\$cat[\"resfield2\"]=\"".$cat["resfield2"]."\";
\$cat[\"resfield3\"]=\"".$cat["resfield3"]."\";
\$cat[\"defaultorder\"]=".$cat["defaultorder"].";

\$CATNAME=\"".$CATNAME."\";
";
		
				if (empty($error)) {
					// Database not found. Should the installation program create it?
					if (!@mysql_select_db($db["name"])) {
?>
<br>
<table cellspacing=1 cellpadding=6 border=0 align=center width=650>
<tr><th background=cat/tablebg.gif >Warning!!!</th></tr>
<tr class=t1><td><br>Database &laquo;<B><?=htmlspecialchars($db["name"]);?></B>&raquo; not found!. Do you want to create it?<br><br></td></tr>
<tr class=t2><td align=right>
<table><tr><form action=install.php method=post><td>
<input type=submit name=s1 value='&lt;&lt; No, step back'>
<input type=hidden name=CATNAME value='<?=htmlspecialchars($CATNAME);?>'>
<input type=hidden name=host value='<?=htmlspecialchars($db["host"]);?>'>
<input type=hidden name=user value='<?=htmlspecialchars($db["user"]);?>'>
<input type=hidden name=password value='<?=htmlspecialchars($db["password"]);?>'>
<input type=hidden name=name value='<?=htmlspecialchars($db["name"]);?>'>
<input type=hidden name=alogin value='<?=htmlspecialchars($db["alogin"]);?>'>
<input type=hidden name=apassword1 value='<?=htmlspecialchars($db["apassword1"]);?>'>
<input type=hidden name=apassword2 value='<?=htmlspecialchars($db["apassword2"]);?>'>
<input type=hidden name=mailifnewlinkto value='<?=htmlspecialchars($cat["mailifnewlinkto"]);?>'>
<input type=hidden name=mailifnewlinksubject value='<?=htmlspecialchars($cat["mailifnewlinksubject"]);?>'>
<input type=hidden name=shownewcount value='<?=htmlspecialchars($cat["shownewcount"]);?>'>
<input type=hidden name=duptestcount value='<?=$cat["duptestcount"];?>'>
<input type=hidden name=rows value='<?=htmlspecialchars($cat["rows"]);?>'>
<input type=hidden name=defaultorder value='<?=htmlspecialchars($cat["defaultorder"]);?>'>
<input type=hidden name=resfield1 value='<?=htmlspecialchars(StripSlashes($cat["resfield1"]));?>'>
<input type=hidden name=resfield2 value='<?=htmlspecialchars(StripSlashes($cat["resfield2"]));?>'>
<input type=hidden name=resfield3 value='<?=htmlspecialchars(StripSlashes($cat["resfield3"]));?>'>
<input type=hidden name=mailifnewlink value='<?=($cat["mailifnewlink"]=="yes")?"on":"off";?>'>
<input type=hidden name=shownew value='<?=($cat["shownew"]==1)?"on":"off";?>'>
<input type=hidden name=linksatmain value='<?=($cat["linksatmain"]==1)?"on":"off";?>'>
<input type=hidden name=step value=1>
<input type=hidden name=onlyshow value=1>
</td></form></tr></table>
<form action=install.php method=post>
<tr class=t1><td>
<P>At this step you may have a look at the contents of the configuration file. To have full control over the installation procedure, you will be able to see it at each step.</P>
<textarea readonly name=config style='width:100%;height:150px;'><?=htmlspecialchars($config);?></textarea></td></tr>
<tr class=t2><td align=right>
<input type=submit name=s2 value='Yes, create ! &gt;&gt;'>
<input type=hidden name=step value=2>
<input type=hidden name=host value='<?=htmlspecialchars($db["host"]);?>'>
<input type=hidden name=user value='<?=htmlspecialchars($db["user"]);?>'>
<input type=hidden name=password value='<?=htmlspecialchars($db["password"]);?>'>
<input type=hidden name=name value='<?=htmlspecialchars($db["name"]);?>'>
</td></tr></form>
</table>
<?
						die();
						}

					else {
?>
<br>
<table cellspacing=1 cellpadding=6 border=0 align=center width=650>
<tr><th background=cat/tablebg.gif >Warning !!!</th></tr>
<tr class=t1><td><br>Database &laquo;<B><?=htmlspecialchars($db["name"]);?></B>&raquo; was found. The following tables will be created <B>"cncat_main"</B>, <B>"cncat_cat"</B>, <B>"cncat_cat_liner"</B>.
<?
						$r=mysql_query("SHOW tables");
						while ($a=mysql_fetch_array($r)) {
							if ($a[0]=="cncat_main") print "<br><br><B><font color=red>Attention !</B> Table \"<B>cncat_main</B>\" will be erased !</font>";
							if ($a[0]=="cncat_mail") print "<br><br><B><font color=red>Attention !</B> Table \"<B>cncat_mail</B>\" will be erased !</font>";
							if ($a[0]=="cncat_cat") print "<br><br><B><font color=red>Attention !</B> Table \"<B>cncat_cat</B>\" will be erased !</font>";
							if ($a[0]=="cncat_templates") print "<br><br><B><font color=red>Attention !</B> Table \"<B>cncat_templates</B>\" will be erased !</font>";
							if ($a[0]=="cncat_cat_linear") print "<br><br><B><font color=red>Attention !</B> Table \"<B>cncat_cat_linear</B>\" will be erased !</font>";
							}
?>
<br><br></td></tr>
<form action=install.php method=post>
<tr><td class=t2 align=right>
<input type=submit name=s1 value='&lt;&lt; No, step back'>
<input type=hidden name=CATNAME value='<?=htmlspecialchars($CATNAME);?>'>
<input type=hidden name=host value='<?=htmlspecialchars($db["host"]);?>'>
<input type=hidden name=user value='<?=htmlspecialchars($db["user"]);?>'>
<input type=hidden name=password value='<?=htmlspecialchars($db["password"]);?>'>
<input type=hidden name=name value='<?=htmlspecialchars($db["name"]);?>'>
<input type=hidden name=alogin value='<?=htmlspecialchars($db["alogin"]);?>'>
<input type=hidden name=apassword1 value='<?=htmlspecialchars($db["apassword1"]);?>'>
<input type=hidden name=apassword2 value='<?=htmlspecialchars($db["apassword2"]);?>'>
<input type=hidden name=mailifnewlinkto value='<?=htmlspecialchars($cat["mailifnewlinkto"]);?>'>
<input type=hidden name=mailifnewlinksubject value='<?=htmlspecialchars($cat["mailifnewlinksubject"]);?>'>
<input type=hidden name=shownewcount value='<?=htmlspecialchars($cat["shownewcount"]);?>'>
<input type=hidden name=duptestcount value='<?=$cat["duptestcount"];?>'>
<input type=hidden name=rows value='<?=htmlspecialchars($cat["rows"]);?>'>
<input type=hidden name=mailifnewlink value='<?=($cat["mailifnewlink"]=="yes")?"on":"off";?>'>
<input type=hidden name=defaultorder value='<?=htmlspecialchars($cat["defaultorder"]);?>'>
<input type=hidden name=resfield1 value='<?=htmlspecialchars(StripSlashes($cat["resfield1"]));?>'>
<input type=hidden name=resfield2 value='<?=htmlspecialchars(StripSlashes($cat["resfield2"]));?>'>
<input type=hidden name=resfield3 value='<?=htmlspecialchars(StripSlashes($cat["resfield3"]));?>'>
<input type=hidden name=shownew value='<?=($cat["shownew"]==1)?"on":"off";?>'>
<input type=hidden name=linksatmain value='<?=($cat["linksatmain"]==1)?"on":"off";?>'>
<input type=hidden name=step value=1>
<input type=hidden name=onlyshow value=1>
</td></tr>
</form>
<form action=install.php method=post>
<tr class=t1><td>
<P>At this step you may have a look at the contents of the configuration file. To have full control over the installation procedure, you will be able to see it at each step.</P>
<textarea readonly name=config style='width:100%;height:150px;'><?=htmlspecialchars($config);?></textarea></td></tr>
<tr class=t2><td align=right>
<input type=submit name=s2 value='Yes, create ! &gt;&gt;'>
<input type=hidden name=step value=3>
<input type=hidden name=host value='<?=htmlspecialchars($db["host"]);?>'>
<input type=hidden name=user value='<?=htmlspecialchars($db["user"]);?>'>
<input type=hidden name=password value='<?=htmlspecialchars($db["password"]);?>'>
<input type=hidden name=name value='<?=htmlspecialchars($db["name"]);?>'>
</td></form></tr></table>
</table>
<?
							die();
						} /* of else */
					} /* of if (empty($error) */
				} /* of if (!mysql_connect */
			} /* of if ($onlyshow */
	
		if (!empty($error)) {
			print "<P><B>The following errors have been discovered: </B></P><OL>";
			print $error;
			print "</OL>";
			}
		}
?>
<br>
<table cellspacing=1 cellpadding=6 border=0 align=center width=650><form action=install.php method=post>
<input type=hidden name=step value='1'>
<tr class=t1><td>Directory name</td><td><input style='width:300px;' type=text name=CATNAME value='<?=htmlspecialchars($CATNAME);?>'></td></tr>
<tr><th background=cat/tablebg.gif colspan=2>MySql connection parameters</th></tr>
<tr class=t1><td>MySql server (for example localhost)</td><td><input style='width:300px;' type=text name=host value='<?=htmlspecialchars($db["host"]);?>'></td></tr>
<tr class=t2><td>MySql connection login</td><td><input style='width:300px;' type=text name=user value='<?=htmlspecialchars($db["user"]);?>'></td></tr>
<tr class=t1><td>MySql connection password</td><td><input style='width:300px;' type=text name=password value='<?=htmlspecialchars($db["password"]);?>'></td></tr>
<tr class=t2><td>Database name</td><td><input style='width:300px;' type=text name=name value='<?=htmlspecialchars($db["name"]);?>'></td></tr>
<tr><th background=cat/tablebg.gif colspan=2>Administrator's data</th></tr>
<tr class=t1><td>Administrators login</td><td><input style='width:300px;' type=text name=alogin value='<?=htmlspecialchars($db["alogin"]);?>'></td></tr>
<tr class=t2><td>Administrators password</td><td><input style='width:300px;' type=password name=apassword1 value='<?=htmlspecialchars($db["apassword1"]);?>'></td></tr>
<tr class=t1><td>Retype password</td><td><input style='width:300px;' type=password name=apassword2 value='<?=htmlspecialchars($db["apassword2"]);?>'></td></tr>
<tr><th background=cat/tablebg.gif colspan=2>Confirmations via e-mail</th></tr>
<tr class=t2><td colspan=2><input type=checkbox name=mailifnewlink <?=($cat["mailifnewlink"]=="yes")?"checked":"";?>>Send new links notifications via e-mail</td></tr>
<tr class=t1><td>E-Mail address:</td><td><input style='width:300px;' type=text name=mailifnewlinkto value='<?=htmlspecialchars($cat["mailifnewlinkto"]);?>'></td></tr>
<tr class=t2><td>Message subject</td><td><input style='width:300px;' type=text name=mailifnewlinksubject value='<?=htmlspecialchars($cat["mailifnewlinksubject"]);?>'></td></tr>
<tr><th background=cat/tablebg.gif colspan=2>Section "New Links"</th></tr>
<tr class=t1><td colspan=2><input type=checkbox name=shownew <?=($cat["shownew"]==1)?"checked":"";?>>Display "New Links" section on main page</td></tr>
<tr class=t2><td>Number of links to display:</td><td><input style='width:300px;' type=text name=shownewcount value='<?=htmlspecialchars($cat["shownewcount"]);?>'></td></tr>
<tr><th background=cat/tablebg.gif colspan=2>Miscellaneous settings</th></tr>
<tr class=t2><td valign=top>Optional fields (banner or icon, for example):<br><small>
If you need optional fields in addition to standard ones (URL, name, description and E-Mail), type in their names. If do not need any optional fields, leave it blank.</small></td><td><input style='width:300px;' type=text name=resfield1 value='<?=htmlspecialchars(StripSlashes($cat["resfield1"]));?>'><br><input style='width:300px;' type=text name=resfield2 value='<?=htmlspecialchars(StripSlashes($cat["resfield2"]));?>'><br><input style='width:300px;' type=text name=resfield3 value='<?=htmlspecialchars(StripSlashes($cat["resfield3"]));?>'></td></tr>
<tr class=t1><td>Default order:</td><td>

<select name=defaultorder style='width:300px;'>
<option value=0 <?if ($cat["defaultorder"]=="0") echo "selected";?>>By popularity (descending order)
<option value=1 <?if ($cat["defaultorder"]=="1") echo "selected";?>>By name (ascending order)
<option value=2 <?if ($cat["defaultorder"]=="2") echo "selected";?>>By moderator's grade (descending order)
<option value=3 <?if ($cat["defaultorder"]=="3") echo "selected";?>>By date of entry (descending order)
</select>

</td></tr>
<tr class=t2><td>Number of new links automatically checked for reduplication:</td><td><input style='width:300px;' type=text name=duptestcount value='<?=$cat["duptestcount"];?>'></td></tr>
<tr class=t1><td colspan=2><input type=checkbox name=linksatmain <?=($cat["linksatmain"]==1)?"checked":"";?>>Display links on main page (otherwise, only categories are displayed)</td></tr>
<tr class=t2><td>Show categories in N columns. N=</td><td><input style='width:300px;' type=text name=rows value='<?=htmlspecialchars($cat["rows"]);?>'></td></tr>
<tr class=t1><td colspan=2 align=right><input type=submit value='Proceed &gt;&gt;'></td></tr>
</form></table>
<?	
//$LANGFILE="lang_eng.php";
//$LANGFILE="lang_rus.php";

	install_bottom();
	}
?>
