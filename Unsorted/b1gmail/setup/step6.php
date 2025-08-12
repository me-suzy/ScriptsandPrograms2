<html>
<head>
<title>b1gMail Setup</title>
</head>
<body bgcolor="#ffffff">
<center>
 <table width="100%" height="100%" cellspacing="0" cellpadding="0">
  <tr>
   <td width="100%" height="100%" valign="middle" align="center">
    <table cellspacing="1" bgcolor="#000000" cellpadding="0" width="65%">
     <tr>
      <td bgcolor="#003366" background="table.gif" align="center">
       <font face="verdana" size="2" color="#ffffff">
        b1gMail Setup
       </font>
      </td>
     </tr>
     <tr>
      <td bgcolor="#D4D0C8" align="center">
       <table width="100%">
        <tr>
         <td width="150" valign="middle" align="center">
          <img src="mbox.gif" border="0">
         </td>
         <td valign="top" align="center">
           <font face="verdana" size="2" color="#000000">
             <b><u>b1gMail Installing</u></b>
             <br><br>
             <b>Installing b1gMail...</b><br>
<?

 @chmod("../config.inc.php",0777);
 @chmod("../admin",0777);
 @chmod("../admin/*",0777);


$sql_server = $config_sql_server;
$sql_user = $config_sql_user;
$sql_passwort = $config_sql_passwort;
$sql_db = $config_sql_db;

$forum_name = $config_board_name;

$table1_name="b1gmail_adressen";
$table1_query="
CREATE TABLE b1gmail_adressen (
  id int(11) NOT NULL auto_increment,
  Name varchar(255) default NULL,
  Email varchar(255) default NULL,
  User varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$table2_name="b1gmail_mails";
$table2_query="
CREATE TABLE b1gmail_mails (
  id int(11) NOT NULL auto_increment,
  Titel varchar(255) default NULL,
  Von varchar(255) default NULL,
  An varchar(255) default NULL,
  CC varchar(255) default NULL,
  BCC varchar(255) default NULL,
  Body longtext,
  Gelesen int(2) default NULL,
  Beantwortetet int(2) default NULL,
  Weitergeleitet int(2) default NULL,
  Ordner varchar(255) default NULL,
  Datum varchar(50) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$table3_name="b1gmail_users";
$table3_query="
CREATE TABLE b1gmail_users (
  id int(11) NOT NULL auto_increment,
  User varchar(255) default NULL,
  Name varchar(255) default NULL,
  Hash varchar(255) default NULL,
  Strasse varchar(255) default NULL,
  PLZ varchar(8) default NULL,
  Ort varchar(255) default NULL,
  Telefon varchar(255) default NULL,
  FAX varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$table4_name="b1gmail_ordner";
$table4_query="
CREATE TABLE b1gmail_ordner (
id int(11) NOT NULL auto_increment,
Name varchar(255) default NULL,
User varchar(255) default NULL,
PRIMARY KEY (id)
) TYPE=MyISAM;";


$table5_name="b1gmail_banner";
$table5_query="
CREATE TABLE b1gmail_banner (
  id int(11) NOT NULL auto_increment,
  code text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$table6_name="b1gmail_outbox";
$table6_query="
CREATE TABLE b1gmail_outbox (
  id int(11) NOT NULL auto_increment,
  Titel varchar(255) default NULL,
  Von varchar(255) default NULL,
  An varchar(255) default NULL,
  CC varchar(255) default NULL,
  BCC varchar(255) default NULL,
  Body longtext,
  Datum varchar(50) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$phpin = "<" . "?";

$phpout = "?" . ">";

$configphp = "$phpin"."

 "."$"."pop_host = \"{"."${config_pop_host}:110/pop3"."}INBOX\";
 "."$"."pop_user = \"${config_pop_user}\";
 "."$"."pop_pass = \"${config_pop_pass}\";
 "."$"."domain = \"${config_domain}\";

 "."$"."sql_server = \"${sql_server}\";
 "."$"."sql_user = \"${sql_user}\";
 "."$"."sql_passwort = \"${sql_passwort}\";
 "."$"."sql_db = \"${sql_db}\";

 "."$"."speicher = \"${config_speicher}\";
 "."$"."sigwe = \"Powered by b1gMail - www.B1G.de\";
 "."$"."b1gversion = \"3.00\";
 
 "."$"."template = \"standard\";
 "."$"."copyright = \"Script &copy; 2002 by B1G.de\";

"."$phpout";


echo ("MySQL: Connecting to $sql_server... ");
$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if ($verbindung) {
 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
} else {
 echo ("<font color=\"#FF0000\"><b>Error</b></font><br>");
}
mysql_select_db($sql_db, $verbindung);

echo ("Creating table ${table1_name}... ");
$ergebnis = mysql_query($table1_query, $verbindung);
if ($ergebnis) {
 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
} else {
 echo ("<font color=\"#FF0000\"><b>Error</b></font><br>");
}


echo ("Creating table ${table2_name}... ");
$ergebnis = mysql_query($table2_query, $verbindung);
if ($ergebnis) {
 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
} else {
 echo ("<font color=\"#FF0000\"><b>Error</b></font><br>");
}

echo ("Creating table ${table3_name}... ");
$ergebnis = mysql_query($table3_query, $verbindung);
if ($ergebnis) {
 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
} else {
 echo ("<font color=\"#FF0000\"><b>Error</b></font><br>");
}


echo ("Creating table ${table4_name}... ");
$ergebnis = mysql_query($table4_query, $verbindung);
if ($ergebnis) {
 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
} else {
 echo ("<font color=\"#FF0000\"><b>Error</b></font><br>");
}

echo ("Creating table ${table5_name}... ");
$ergebnis = mysql_query($table5_query, $verbindung);
if ($ergebnis) {
 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
} else {
 echo ("<font color=\"#FF0000\"><b>Error</b></font><br>");
}

echo ("Creating table ${table6_name}... ");
$ergebnis = mysql_query($table6_query, $verbindung);
if ($ergebnis) {
 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
} else {
 echo ("<font color=\"#FF0000\"><b>Error</b></font><br>");
}

mysql_close($verbindung);


echo ("Writing config file... ");
$datei = fopen("../config.inc.php", "w");
    fwrite($datei, "$configphp");
fclose($datei);

$afi = "AuthType Basic 
AuthName \"b1gMail Admin\" 
AuthUserFile ".realpath("../admin")."/.htpasswd 
require user admin";

$salt=21;
$cpw = crypt($apass,$salt);

$pfi = "admin:${cpw}";

$datei = fopen("../admin/.htaccess", "w");
    fwrite($datei, "$afi");
fclose($datei);

$datei = fopen("../admin/.htpasswd", "w");
    fwrite($datei, "$pfi");
fclose($datei);


 echo ("<font color=\"#006600\"><b>OK</b></font><br>");
echo ("<b>Installation completed.</b><br><font color=\"#ff0000\"><b>WARNING:</b> Please remove the directory \"setup\" from your webspace!!</font>");
?>

           </font>
         </td>
        </tr>
        <tr>
         <td align="center"><font face="verdana" size="1" color="#000000">&copy; 2002 by www.b1g.de</font></td>
         <td align="right"><font face="verdana" size="2" color="#000000">Completed</font></td>
        <tr>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
</center>
</body>
</html>
