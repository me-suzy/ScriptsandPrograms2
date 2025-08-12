<?php
if ($fp = fopen("admin_max_settings.php",'w')){
fwrite($fp,"<?\n");
fwrite($fp,"\$config[db_hostname] = \"$mh\";\n");
fwrite($fp,"\$config[db_username] = \"$mu\";\n");
fwrite($fp,"\$config[db_password] = \"$mp\";\n");
fwrite($fp,"\$config[db_database] = \"$md\";\n");
fwrite($fp,"\$config[ttp_username] = \"$ttpu\";\n");
fwrite($fp,"\$config[ttp_password] = \"$ttpp\";\n");
fwrite($fp,"function check_user(\$u,\$p) {\n");
fwrite($fp,"global \$config;\n");
fwrite($fp,"if (\$u == \$config[ttp_username] && \$p == \$config[ttp_password]) {return true;} else {return false;}}\n");
fwrite($fp,"function check_user2() {\n");
fwrite($fp,"global \$HTTP_COOKIE_VARS; global \$config;\n");
fwrite($fp,"@\$dacook = \$HTTP_COOKIE_VARS[\"ttp_set\"];\n");
fwrite($fp,"list(\$u,\$p) = split(\"\\|\",\$dacook,2);\n");
fwrite($fp,"if (\$u == \$config[ttp_username] && \$p == \$config[ttp_password]) {return true;} else {return false;}}\n");
fwrite($fp,"?>\n");
fclose($fp);

require_once("admin_max_settings.php");
require_once("db.php");

db_query("drop table ttp_settings"); db_query("drop table ttp_sites"); db_query("drop table ttp_traffic");

db_query("CREATE TABLE ttp_settings (
  furl char(255) NOT NULL default '$su',
  nemail char(128) default NULL,
  dntf int(9) NOT NULL default '50',
  mhpd int(9) NOT NULL default '20',
  duniq int(3) NOT NULL default '5',
  dprox int(3) NOT NULL default '5',
  dprod int(3) NOT NULL default '5',
  emailw int(1) NOT NULL default '0',
  fctg int(1) NOT NULL default '1',
  manage_type tinyint(1) NOT NULL default '0',
  send_ratio int(4) NOT NULL default '0',
  active tinyint(1) NOT NULL default '1'
) TYPE=MyISAM;");

db_query("CREATE TABLE ttp_sites (
  siteid int(4) NOT NULL auto_increment,
  wname char(64) NOT NULL default '',
  email char(64) NOT NULL default '',
  siteurl char(255) NOT NULL default '',
  sitename char(128) NOT NULL default '',
  furl char(255) NOT NULL default '',
  icqnumb char(20) default '',
  icqname char(20) default '',
  sent int(9) NOT NULL default '0',
  force int(9) NOT NULL default '0',
  perm tinyint(1) NOT NULL default '0',
  active tinyint(1) NOT NULL default '0',
  manage_type tinyint(1) NOT NULL default '0',
  send_ratio int(4) NOT NULL default '0',
  PRIMARY KEY  (siteid),
  KEY siteurl (siteurl),
  KEY sitename (sitename),
  KEY active (active)
) TYPE=MyISAM;");

db_query("CREATE TABLE ttp_traffic (
  siteid int(4) NOT NULL default '0',
  ipaddr char(25) NOT NULL default '',
  click int(3) NOT NULL default '0',
  prox int(1) NOT NULL default '0',
  refer char(255) NOT NULL default '',
  datev timestamp(10) NOT NULL,
  KEY siteid (siteid),
  KEY datev (datev),
  KEY click (click),
  KEY ipaddr (ipaddr)
) TYPE=MyISAM;");

db_query("insert into ttp_settings (nemail) values (NULL)");

} else { echo "Couldnt Open admin_max_settings.php please chmod 777"; exit;}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>AdultMonger TTPro Installation</title>
<STYLE type=text/css>.main { FONT: 8pt Verdana, Helvetica, sans-serif; COLOR: FFFFFF}</STYLE>
<STYLE type=text/css>.small { FONT: 7pt Verdana, Helvetica, sans-serif;  COLOR: FFFFFF}</STYLE>
<STYLE type=text/css>
A:link { COLOR: #FFFFFF; TEXT-DECORATION: underline }
A:visited { COLOR: #FFFFFF; TEXT-DECORATION: underline }
A:active { COLOR: #FFFFFF; TEXT-DECORATION: underline }
A:hover { COLOR: #FFFFFF; TEXT-DECORATION: none }
</STYLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

<body bgcolor="#000000" background="../assets/am-interfacev1_r6_c4.jpg" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="726" height="280" border="0" cellpadding="0" cellspacing="0" background="../assets/am-interfacev1_r6_c4.jpg">
  <tr>
    <td valign="middle" align=center class=main><font size=+1>Install Complete, <b>DO THE FOLLOWING:</b><br><br>
    Chmod 644 admin_max_settings.php<br>
    DELETE: install.htm, install1.htm and install.php<br><br>
    </td>
  </tr>
</table>
</body>
</html>
