<?

$p4cms_version = "1.05";

$mysql_dump[] = "CREATE TABLE p4cms_abfragen (
  id int(11) NOT NULL auto_increment,
  rubrik int(11) default NULL,
  typ enum('letzten','ersten') default NULL,
  zahl int(11) default NULL,
  titel varchar(255) default NULL,
  template longtext,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_dokumente (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  datei varchar(255) default NULL,
  rubrik int(11) default NULL,
  datum int(14) default NULL,
  redakteur int(11) default NULL,
  pubdatum int(14) default NULL,
  published enum('yes','no') default NULL,
  lastupdate int(14) default NULL,
  ablauf varchar(14) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_dokumente_felder (
  id int(11) NOT NULL auto_increment,
  dokument int(11) default NULL,
  feld int(11) default NULL,
  inhalt longtext,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_gruppen (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  m_redakteur enum('yes','no') default NULL,
  m_vorlagen enum('yes','no') default NULL,
  m_abfragen enum('yes','no') default NULL,
  m_dokumente enum('yes','no') default NULL,
  m_mediapool enum('yes','no') default NULL,
  m_newsletter enum('yes','no') default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_listsubscribers (
  id int(11) NOT NULL auto_increment,
  email varchar(255) default NULL,
  art enum('text','html') default NULL,
  name varchar(255) default NULL,
  datum int(14) default NULL,
  liste int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_logs (
  id int(11) NOT NULL auto_increment,
  datum int(14) default NULL,
  zeile varchar(255) default NULL,
  typ enum('system','user') default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_mailinglisten (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_module (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  name varchar(255) default NULL,
  optionen text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_redakteure (
  id int(11) NOT NULL auto_increment,
  username varchar(255) default NULL,
  passwort varchar(255) default NULL,
  name varchar(255) default NULL,
  email varchar(255) default NULL,
  gruppe int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_rubriken (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  template longtext,
  stdvorlange int(11) default NULL,
  stdname varchar(255) NOT NULL default '',
  printv enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_rubriken_felder (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  typ enum('kurztext','haupttext','bild','bildl','bildr','video','javascript','flash') default NULL,
  rubrik int(11) default NULL,
  stdwert varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_suchen (
  id int(11) NOT NULL auto_increment,
  rubrik int(11) default NULL,
  felder text,
  titel varchar(255) default NULL,
  vorlage int(11) default NULL,
  elem longtext,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_variablen (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  name varchar(255) default NULL,
  tw_var int(11) default NULL,
  tw_var_t varchar(255) default NULL,
  tw_start int(11) default NULL,
  tw_count int(11) default NULL,
  rub int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_vorlagen (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  vorlage longtext,
  lastupdate int(14) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_navi_items (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  parent int(11) default NULL,
  link varchar(255) default NULL,
  target varchar(100) default NULL,
  ebene enum('1','2') NOT NULL default '1',
  rang int(11) NOT NULL default '1',
  subrang int(11) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_navis (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  ebene1 longtext NOT NULL,
  ebene2 longtext NOT NULL,
  ebene1a longtext NOT NULL,
  ebene2a longtext NOT NULL,
  vor longtext NOT NULL,
  nach longtext NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_galerien (
  id int(11) NOT NULL auto_increment,
  titel varchar(255) default NULL,
  prozeile int(5) default '4',
  w int(3) NOT NULL default '80',
  h int(3) NOT NULL default '80',
  bgcolor varchar(10) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_galerien_bilder (
  id int(11) NOT NULL auto_increment,
  gallerie int(11) default NULL,
  bild longblob,
  titel varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_banner (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  kontakt varchar(255) NOT NULL default '',
  src text NOT NULL,
  target text NOT NULL,
  format enum('image','flash') NOT NULL default 'image',
  order_type enum('views','clicks') NOT NULL default 'views',
  max_views int(11) NOT NULL default '0',
  max_hits int(11) NOT NULL default '0',
  views int(11) NOT NULL default '0',
  hits int(11) NOT NULL default '0',
  time_from smallint(2) NOT NULL default '24',
  time_to smallint(2) NOT NULL default '24',
  alt varchar(255) NOT NULL default '',
  link_target enum('_blank','_self') NOT NULL default '_blank',
  flash_width int(11) NOT NULL default '468',
  flash_height int(11) NOT NULL default '60',
  bannerzone int(11) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_bannerzone (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_newsintern (
  id int(11) NOT NULL auto_increment,
  kat int(11) NOT NULL default '0',
  datum int(11) NOT NULL default '0',
  titel varchar(255) NOT NULL default '',
  text text NOT NULL,
  wichtigkeit enum('niedrig','wichtig','hoch') NOT NULL default 'niedrig',
  STATUS enum('zu erledigen','in Arbeit','erledigt') NOT NULL default 'zu erledigen',
  autor varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_newsintern_kommentare (
  id int(11) NOT NULL auto_increment,
  newsid int(11) NOT NULL default '0',
  titel varchar(255) NOT NULL default '',
  text text NOT NULL,
  autor varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE `p4cms_buttons` (
  `id` int(11) NOT NULL auto_increment,
  `bild` varchar(255) default NULL,
  `t` int(4) default NULL,
  `l` int(4) default NULL,
  `font` varchar(255) default NULL,
  `farbe` varchar(9) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_module_vars (
  id int(11) NOT NULL auto_increment,
  alt varchar(255) default NULL,
  neu varchar(255) default NULL,
  modul int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_kommentare (
  id int(11) NOT NULL auto_increment,
  commid varchar(255) NOT NULL default '',
  datum int(11) NOT NULL default '0',
  email varchar(255) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  titel varchar(255) NOT NULL default '',
  text text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_docstats (
  id int(11) NOT NULL auto_increment,
  ref varchar(255) NOT NULL default '',
  statid varchar(255) NOT NULL default '0',
  tag smallint(2) NOT NULL default '0',
  monat smallint(2) NOT NULL default '0',
  jahr mediumint(4) NOT NULL default '0',
  stamp int(11) NOT NULL default '0',
  hits int(11) NOT NULL default '0',
  datum varchar(25) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_referer (
  id int(11) NOT NULL auto_increment,
  statid int(11) default '0',
  jahr mediumint(4) default '0',
  name varchar(255) default NULL,
  visits int(11) default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "CREATE TABLE p4cms_stats (
  id int(11) NOT NULL auto_increment,
  statid int(11) default '0',
  datum varchar(12) default NULL,
  ip varchar(255) default NULL,
  os varchar(255) default NULL,
  browser varchar(255) default NULL,
  ref varchar(255) default NULL,
  refdomain varchar(255) default NULL,
  tag smallint(2) default '0',
  monat smallint(2) default '0',
  jahr mediumint(4) default '0',
  stamp int(11) default '0',
  hits int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

$mysql_dump[] = "INSERT INTO `p4cms_buttons` (`id`, `bild`, `t`, `l`, `font`, `farbe`) VALUES (1, 'button1.png', 23, 25, 'verdana.ttf', '#FFFFFF');";
$mysql_dump[] = "INSERT INTO `p4cms_buttons` (`id`, `bild`, `t`, `l`, `font`, `farbe`) VALUES (2, 'button2.png', 23, 25, 'verdana.ttf', '#FFFFFF');";
$mysql_dump[] = "INSERT INTO `p4cms_buttons` (`id`, `bild`, `t`, `l`, `font`, `farbe`) VALUES (3, 'button3.png', 23, 25, 'verdana.ttf', '#FFFFFF');";
$mysql_dump[] = "INSERT INTO `p4cms_buttons` (`id`, `bild`, `t`, `l`, `font`, `farbe`) VALUES (4, 'button4.png', 26, 25, 'verdana.ttf', '#FFFFFF');";
$mysql_dump[] = "INSERT INTO `p4cms_buttons` (`id`, `bild`, `t`, `l`, `font`, `farbe`) VALUES (5, 'button5.png', 26, 25, 'verdana.ttf', '#FFFFFF');";
$mysql_dump[] = "INSERT INTO `p4cms_buttons` (`id`, `bild`, `t`, `l`, `font`, `farbe`) VALUES (6, 'button6.png', 20, 33, 'verdana.ttf', '#000000');";

$mysql_dump[] = "INSERT INTO p4cms_newsintern (id, kat, datum, titel, text, wichtigkeit, STATUS, autor) VALUES (1, 0, '".time()."', 'Herzlichen Glückwunsch!', 'Die Installation hat geklappt. Nun können Sie loslegen :)', 'hoch', 'erledigt', 'admin');";
$mysql_dump[] = "INSERT INTO p4cms_bannerzone VALUES (1, 'Bannerzone 1');";
$mysql_dump[] = "INSERT INTO p4cms_gruppen VALUES (1, 'Administratoren', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes');";

$mysql_dump[] = "INSERT INTO p4cms_module VALUES (2, 'Suche', 'suche', 'Übersicht,mod.overview.php;Neue Suche,mod.create.php');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (3, 'Newsletter', 'newsletter', 'Mailinglisten,mod.mailinglisten.php;Schreiben,mod.write.php');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (4, 'Navigation', 'navigation', 'Übersicht,mod.overview.php;Erstellen,mod.edit.php');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (5, 'Kontaktformular', 'kontakt', 'Generieren,mod.generieren.php');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (6, 'Galerie', 'galerie', 'Übersicht,mod.overview.php;Erstellen,mod.edit.php');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (7, 'Banner', 'banner', 'Übersicht,mod.overview.php;Erstellen,mod.new.php;Bannerzonen,mod.zone.php');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (8, 'ButtonFactory', 'buttonfactory', 'Starten,mod.bf.php');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (99, 'Statistik', 'stats', 'Besucher,mod.show.php;Dokumente,mod.show.php&mode=docs');";
$mysql_dump[] = "INSERT INTO p4cms_module VALUES (100, 'Kommentar', 'comment', 'Übersicht,mod.overview.php');";

$mysql_dump[] = "INSERT INTO p4cms_module_vars VALUES (1, '\\\\{NAVI:([0-9]*)\\\\}', '<" . "? \$abs_pfad = \"{abs}\"; \$anavi = \\\\\\\\1; include(\"{abs}modules/navigation/js.php\"); ?" . ">', 4);";
$mysql_dump[] = "INSERT INTO p4cms_module_vars VALUES (2, '\\\\{GALERIE:([0-9]*)\\\\}', '<" . "? \$abs_pfad = \"{abs}\"; \$agalerie = \\\\\\\\1; include(\"{abs}modules/galerie/js.php\"); ?" . ">', 6);";
$mysql_dump[] = "INSERT INTO p4cms_module_vars VALUES (4, '{p4:counter}', '<" . "? \$abs_pfad = \"{abs}\"; include(\"{abs}modules/stats/stat.php\"); ?" . ">', 99);";
$mysql_dump[] = "INSERT INTO p4cms_module_vars VALUES (3, '{p4:kommentar}', '<" . "? \$abs_pfad = \"{abs}\";  \$commid = \$_SERVER[\\'REQUEST_URI\\']; include(\"{abs}modules/comment/c.inc.php\");  ?" . ">', 100);";
$mysql_dump[] = "INSERT INTO p4cms_module_vars VALUES (5, '{q}','<" . "? echo htmlspecialchars(\$_REQUEST[\\'query\\']); ?" . ">','');";
?>
<html>
<head>
<link href="style/setup.css" rel="stylesheet" type="text/css">
<title>Installation p4cms nullify by WTN Team</title>
</head>
<body>
<br>
<br>
<table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td background="gfx/setup/bg_h.gif"><img src="gfx/setup/logo1_0.gif" width="144" height="85"></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><?
if (!isset($_REQUEST['step']) || $_REQUEST['step']==1) {
	$error = 0;
	?>
<center><font face=Arial size=2>
<b>Welcome p4CMS Setup Nullified by WTN Team<br> </center></b>
Folgende Anforderungen sollte Ihr Webserver 
erf&uuml;llen, um ein einwandfreie Funktion zu gew&auml;hrleisten.<br>
<br>
Sollten auf einem oder mehren Ordnern keine Schreibrechte liegen, m&uuml;ssen
Sie das noch vor der Installation &auml;ndern.<br>
Legen Sie bitte auf diese Ordner die Zugriffsrechte &quot;<b>Chmod 777</b>&quot;.<br>
<br></td>
</tr>
<tr> 
<td colspan="4" class="fcell"><br>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#5BBC58">
				    <tr> 
					  <td><b><font color="#FFFFFF">Anforderung</font></b></td>
					  <td> <div align="center"><font color="#FFFFFF"><b>erforderlich</b></font></div></td>
					  <td> <div align="center"><font color="#FFFFFF"><b>vorhanden</b></font></div></td>
				    </tr>
				    <tr bgcolor="#FFFFFF"> 
					  <td bgcolor="#FAFAFB">PHP Version</td>
					  <td> <div align="center">4.1.1</div></td>
					  <td bgcolor="#FAFAFB"> <div align="center"><b> 
						  <?
							$nv = str_replace(".","",phpversion());
							if ($nv < 411) {
								$error = 1;
								?>
								<font color=red><?=phpversion();?></font>
								<?
							} else {
								?>
								<font color=green><?=phpversion();?></font>
								<?
							}
						  ?>
						  						   </b></div></td>
				    </tr>
				    <tr bgcolor="#FFFFFF">
				    	<td bgcolor="#FAFAFB">Schreibrechte auf die Datei &quot;<b>include/config.inc.php</b>&quot; </td>
				    	<td><div align="center"><b>JA</b></div></td>
				    	<td bgcolor="#FAFAFB"><div align="center">
				    		<?
							if(is_writeable("include/config.inc.php")) {
								?>
								<font color=green><b>Ja</b></font>
								<?
							} else {
								$error = 1;
								?>
								<font color=red><b>Nein</b></font>
								<?
							}
				    		?></div></td>
			    	</tr>
				    <tr bgcolor="#FFFFFF">
				    	<td bgcolor="#FAFAFB">Schreibrechte auf den Ordner &quot;<b>media/</b>&quot; </td>
				    	<td><div align="center"><b>JA</b></div></td>
				    	<td bgcolor="#FAFAFB"><div align="center">
				    		<?
							if(is_writeable("media/")) {
								?>
								<font color=green><b>Ja</b></font>
								<?
							} else {
								$error = 1;
								?>
								<font color=red><b>Nein</b></font>
								<?
							}
				    		?></div></td>
			    	</tr>
				    <tr bgcolor="#FFFFFF">
				    	<td bgcolor="#FAFAFB">Schreibrechte auf den Ordner &quot;<b>../</b>&quot; </td>
				    	<td><div align="center"><b>JA</b></div></td>
				    	<td bgcolor="#FAFAFB"><div align="center">
				    		<?
							if(is_writeable("../")) {
								?>
								<font color=green><b>Ja</b></font>
								<?
							} else {
								$error = 1;
								?>
								<font color=red><b>Nein</b></font>
								<?
							}
				    		?></div></td>
			    	</tr>
				    <tr bgcolor="#FFFFFF">
				    	<td bgcolor="#FAFAFB">Schreibrechte auf den Ordner &quot;<b>archive/</b>&quot; </td>
				    	<td><div align="center"><b>JA</b></div></td>
				    	<td bgcolor="#FAFAFB"><div align="center">
				    		<?
							if(is_writeable("archive/")) {
								?>
								<font color=green><b>Ja</b></font>
								<?
							} else {
								$error = 1;
								?>
								<font color=red><b>Nein</b></font>
								<?
							}
				    		?></div></td>
			    	</tr>
				    <tr bgcolor="#FFFFFF">
				    	<td bgcolor="#FAFAFB">Schreibrechte auf den Ordner &quot;<b>temp/</b>&quot; </td>
				    	<td><div align="center"><b>JA</b></div></td>
				    	<td bgcolor="#FAFAFB"><div align="center">
				    		<?
							if(is_writeable("temp/")) {
								?>
								<font color=green><b>Ja</b></font>
								<?
							} else {
								$error = 1;
								?>
								<font color=red><b>Nein</b></font>
								<?
							}
				    		?></div></td>
			    	</tr>
				    <tr bgcolor="#FFFFFF">
				      <td bgcolor="#FAFAFB">Schreibrechte auf den Ordner &quot;<b>modules/</b>&quot; </td>
				      <td><div align="center"><b>JA</b></div></td>
				      <td bgcolor="#FAFAFB"><div align="center"><?
							if(is_writeable("modules/")) {
								?>
                        <font color=green><b>Ja</b></font>
                        <?
							} else {
								$error = 1;
								?>
                        <font color=red><b>Nein</b></font>
                        <?
							}
				    		?></div></td>
        </tr>
    </table>
<br>
</td>
</tr>
<tr> 
<td height="35" colspan="4" class="fcell">
<div align="center"> 
<form style="display:inline;" name="form1" method="post" action="">
<?
if ($error == 0) {
	?>
	<div align="center">
	  <input name="step" type="hidden" value="2">
	  <input name="Submit" type="submit" class="button" onclick="this.blur()" onfocus="this.blur()" value="mit der Installation fortfahren">
	  <?
} else {
	?>
	  <input name="step" type="hidden" value="1">
	  ACHTUNG: Es wurden nicht alle Anforderungen erfüllt.<br>
	  <input name="Submit" type="submit" class="button" onclick="this.blur()" onfocus="this.blur()" value="Beheben Sie den Fehler und klicken Sie auf diese Schaltfläche.">
	  <?
}
?>
    </div>
</form></td>
  </tr>
</table>

	<?
}

if ($_REQUEST['step'] == 2) {
	?>
<center><font face=Arial size=2>
<b>Welcome p4CMS Setup Nullified by WTN Team<br> </center></b>
	<form style="display:inline;" action="" method="post">
	<input type="hidden" name="step" value="3">
	Bitte geben Sie im folgenden die Zugangsdaten
	zu einer MySQL-Datenbank an, in der p4cms die Daten
	ablegen soll.<br><br>
	
	<table width="550" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#5BBC58">
	<tr>
	<td bgcolor="#FAFAFB">MySQL - Host: &nbsp;</td>
	<td bgcolor="#FFFFFF"><input type="text" name="mysql_host" size="32"></td>
	</tr>
	<tr>
	<td bgcolor="#FAFAFB">MySQL - Datenbank: &nbsp;</td>
	<td bgcolor="#FFFFFF"><input type="text" name="mysql_db" size="32"></td>
	</tr>
	<tr>
	<td bgcolor="#FAFAFB">MySQL - Benutzer: &nbsp;</td>
	<td bgcolor="#FFFFFF"><input type="text" name="mysql_user" size="32"></td>
	</tr>
	<tr>
	<td bgcolor="#FAFAFB">MySQL - Passwort: &nbsp;</td>
	<td bgcolor="#FFFFFF"><input type="text" name="mysql_pw" size="32"></td>
	</tr>
	</table>
	<br>
	</center>
	
	<center>
    <input name="Submit" type="submit" class="button" onclick="this.blur()" onfocus="this.blur()" value="Weiter &gt;&gt;">		
	</center>
	</form>
	<?
}

if ($_REQUEST['step'] == 3) {
	$mysql_host = $_REQUEST['mysql_host'];
	$mysql_db = $_REQUEST['mysql_db'];
	$mysql_user = $_REQUEST['mysql_user'];
	$mysql_pw = $_REQUEST['mysql_pw'];
	
	$error = 1;
	
	if (@mysql_connect($mysql_host, $mysql_user, $mysql_pw)) {
		if (@mysql_select_db($mysql_db)) {
			$error = 0;
		}
	}
	@mysql_close();
	
	if ($error == 1) {
		?>
		<form style="display:inline;" action="" method="post">
		<input type="hidden" name="step" value="2">
		<center>
		<br>
		Fehler: Die Verbindung zur Datenbank konnte nicht 
		hergestellt werden.
		<br><br>
		</center>
		
		<center>
   		<input name="Submit" type="submit" class="button" onclick="this.blur()" onfocus="this.blur()" value="&lt;&lt; Zurück">		
		</center>
		</form>
		<?
	} else { 
		?>
<center><font face=Arial size=2>
<b>Welcome p4CMS Setup Nullified by WTN Team<br> </center></b>
		<form style="display:inline;" action="" method="post">
		<input type="hidden" name="step" value="4">
		
		<input type="hidden" name="mysql_host" value="<?=$mysql_host;?>">
		<input type="hidden" name="mysql_user" value="<?=$mysql_user;?>">
		<input type="hidden" name="mysql_pw" value="<?=$mysql_pw;?>">
		<input type="hidden" name="mysql_db" value="<?=$mysql_db;?>">
		
		
	Bitte geben Sie hier Ihr gew&uuml;nschtes Passwort, Vor- u. Zuname, sowie Ihre E-Mail Adresse an. <br>
	<br>
	
	<table width="550" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#5BBC58">
	<tr>
	<td bgcolor="#FAFAFB">Benutzername: &nbsp;</td>
	<td bgcolor="#FFFFFF">admin</td>
	</tr>
	<tr>
	<td bgcolor="#FAFAFB">Passwort: &nbsp;</td>
	<td bgcolor="#FFFFFF"><input type="text" name="admin_pass" size="32" value="<?=$_REQUEST['admin_pass'];?>"></td>
	</tr>
	<tr>
	<td bgcolor="#FAFAFB">Vor- u. Zuname: &nbsp;</td>
	<td bgcolor="#FFFFFF"><input type="text" name="admin_name" size="32" value="<?=$_REQUEST['admin_name'];?>"></td>
	</tr>
	<tr>
	<td bgcolor="#FAFAFB">E-Mail: &nbsp;</td>
	<td bgcolor="#FFFFFF"><input type="text" name="admin_mail" size="32" value="<?=$_REQUEST['admin_mail'];?>"></td>
	</tr>
	</table>
	</center>
	
	<center>
      <br>
      <input name="Submit" type="submit" class="button" onclick="this.blur()" onfocus="this.blur()" value="Weiter &gt;&gt;">		
	</center>
	</form>
		<?
	}
}

if ($_REQUEST['step'] == 4) {
if(ltrim(rtrim($_REQUEST['admin_pass']))==""   ||   ltrim(rtrim($_REQUEST['admin_name']))=="" || ltrim(rtrim($_REQUEST['admin_mail']))==""){
?>
<form style="display:inline;" action="" method="post">
		<input type="hidden" name="step" value="3">
		<input type="hidden" name="mysql_host" value="<?=$mysql_host;?>">
		<input type="hidden" name="mysql_user" value="<?=$mysql_user;?>">
		<input type="hidden" name="mysql_pw" value="<?=$mysql_pw;?>">
		<input type="hidden" name="mysql_db" value="<?=$mysql_db;?>">
		<center>
		<br>
		Fehler: Sie m&uuml;ssen alle Felder ausf&uuml;llen. <br>
		<br>
		</center>
		
		<center>
   		<input name="Submit" type="submit" class="button" onclick="this.blur()" onfocus="this.blur()" value="&lt;&lt; Zurück">		
		</center>
		<input type="hidden" name="admin_pass" value="<?=$_REQUEST['admin_pass'];?>">
		<input type="hidden" name="admin_name" value="<?=$_REQUEST['admin_name'];?>">
		<input type="hidden" name="admin_mail" value="<?=$_REQUEST['admin_mail'];?>">
</form>
<?
exit();
}
	$mysql_host = $_REQUEST['mysql_host'];
	$mysql_db = $_REQUEST['mysql_db'];
	$mysql_user = $_REQUEST['mysql_user'];
	$mysql_pw = $_REQUEST['mysql_pw'];
	
	mysql_connect($mysql_host, $mysql_user, $mysql_pw);
	mysql_select_db($mysql_db);

	while(list($key,$val) = each($mysql_dump)) {
		if (!mysql_query($val)) {
			echo "<b>Fehler:</b> 10$key". mysql_error(). "<br>";
		}
	}
	
	$sql  = "INSERT INTO p4cms_redakteure(username,passwort,name,email,gruppe) VALUES ";
	$sql .= "('admin','$_REQUEST[admin_pass]','$_REQUEST[admin_name]','$_REQUEST[admin_mail]','1')";
	mysql_query($sql);
	
	mysql_close();	
	
	$sp = str_replace("/setup.php", "", $_SERVER['PHP_SELF']);
	
	$ab = str_replace("setup.php", "", $_SERVER['SCRIPT_FILENAME']);
	
	$cfg = "<" . "?

$"."sql_server = \"$mysql_host\";
$"."sql_user = \"$mysql_user\";
$"."sql_passwort = \"$mysql_pw\";
$"."sql_db = \"$mysql_db\";
$"."sql_prefix = \"p4cms_\";

$"."dok_pfad = \"/\";
$"."p4cms_pfad = \"$sp\";
$"."abs_pfad = \"$ab\";

?".">";
	
	$handle = fopen("include/config.inc.php", "w+");
	fwrite($handle, $cfg);
	fclose($handle);
	
	$handle = fopen("../.htaccess", "w+");
	fwrite($handle, "AddType application/x-httpd-php .htm\nAddType application/x-httpd-php .html");
	fclose($handle);
	
	?>
<center><font face=Arial size=2>
<b>Welcome p4CMS Setup Nullified by WTN Team<br> </center></b>
	Herzlichen Gl&uuml;ckwunsch!<br>
p4cms wurde soeben installiert. Sie k&ouml;nnen sich in das CMS &uuml;ber diese
	URL einloggen: <a href="index.php" target="_blank"><?=str_replace("setup.php", "index.php", $_SERVER['PHP_SELF']);?></a>.
	Als Benutzernamen verwenden Sie bitte <b>admin</b>, als Passwort <b><?=$_REQUEST['admin_pass'];?></b>.
	<br>
	<br>
	<font color="red"><b>ACHTUNG:<br>
	</b>L&ouml;schen Sie nun bitte <b>unbedingt</b> die Datei "setup.php", damit kein Dritter Ihre Konfiguration ver&auml;ndern kann!</font>
	<?
}
?>


</body>
</html>