<?
$version = "1.05";
$felder = array("Kurztext", "Haupttext", "Bild (Normal)", "Bild (Links)", "Bild (Rechts)", "Video", "JavaScript");

function StartBox($width, $height) {
	?>
<table width="<? echo($width); ?>" height="<? echo($height); ?>" border="0" align="center" cellpadding="8" cellspacing="1" class="boxstandartborder">
              <tr> 
                <td valign="top" class="boxstandart">
    <?
}

function CreateEditor($width, $height, $text, $name) {
	echo "<TEXTAREA  name=\"$name\" style=\"width:$width;height:$height;\" ONSELECT=\"storeCaret(this);\" ONCLICK=\"storeCaret(this);\" ONKEYUP=\"storeCaret(this);\">" . htmlentities( $text ) . "</TEXTAREA>" ;
	//wrap=\"off\"
	//echo "<input type=\"button\" value=\"Bearbeiten\" onClick=\"edipop('$name');\">";
}

function TableDef($table) {
	global $verbindung;
	$result = mysql_query("SHOW FIELDS FROM $table");
	
	$felder = array();
	$prim = array();
	
	while ($row = mysql_fetch_object($result)) {
		if (!$row->Extra=="") {
			$extra = " " . $row->Extra;
		} else {
			$extra = "";
		}
		if ($row->Default=="") {
			$default = " NULL";
		} else {
			$default = " '" . $row->Default . "'";
		}
		if ($row->Key=="PRI") {
			$prim[count($prim)+1] = "  PRIMARY KEY (" . $row->Field . ")";
		}
		
		$felder[count($felder)+1] = "  " . $row->Field . " " . $row->Type . " DEFAULT$default$extra";
	}
	
	$res .= "DROP TABLE IF EXISTS $table;\n";
	$res .= "CREATE TABLE $table (\n";
	$frak = array_merge($felder, $prim);
	for ($i=0; $i<=count($frak)-1; $i++) {
		$res .= $frak[$i];
		if ($i == count($frak)-1) {
			$res .= "\n";
		} else {
			$res .= ",\n";
		}
	}
	$res .= ") TYPE=MyISAM;\n\n";
	mysql_free_result($result);
	return $res;
}

function Felder($table) {
	$res = array();
	$result = mysql_query("SHOW FIELDS FROM $table");
	while ($row = mysql_fetch_object($result)) {
		$res[count($res)+1] = $row->Field;
	}
	mysql_free_result($result);
	return $res;
}

function MySQLDump() {
	global $verbindung;
	global $sql_db;
	global $sql_prefix;
	
	$result = mysql_list_tables($sql_db, $verbindung);
	
	while ($row = mysql_fetch_row($result)) {
		$doit = false;
		
		if (($row[0] != $sql_prefix . "galerien_bilder")) {
			$doit = true;
		} else {
			if ($_REQUEST['gal'] == "ja") {
				$doit = true;
			}
		}
		
		if ($doit) {
			$dump .= (TableDef($row[0]));
			$felder = Felder($row[0]);
			$sql = "SELECT * FROM " . $row[0] . "";
			$zeilen = mysql_query($sql);
			while ($zrow = mysql_fetch_array($zeilen)) {
				$def = "";
				$cnt = "";
				for ($i=1; $i<=count($felder); $i++) {
					$def .= ", " . $felder[$i];
					$cnt .= ", '" . str_replace("\r\n", "\\r\\n", addslashes($zrow[$felder[$i]])) . "'";
				}
				$def = substr($def, 2);
				$cnt = substr($cnt, 2);
				$dump .= "INSERT INTO " . $row[0] . "(" . $def . ") VALUES (" . $cnt . ");\n";
			}
			$dump .= "\n";
			mysql_free_result($zeilen);
		}
	}
	mysql_free_result($result);
	return $dump;
}

function CNTFile($file) {
	$filename = $file;
	$fd = fopen ($filename, "r");
	$tmpl = fread ($fd, filesize ($filename));
	fclose ($fd);
	return $tmpl;
}

function EndBox() {
	?>
	</td>
              </tr>
</table>
 	<?
}

function ShowLogin() {
	?>
                  <form action="index.php?action=login" method="post"  name="login" id="login" style="display:inline">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td class="Stil1">Benutzername</td>
                    </tr>
                    <tr> 
                      <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <input name="benutzername" type="text" class="feld" id="benutzername"  size="35">
                        </font></td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td><span class="Stil1">Passwort</span></td>
                    </tr>
                    <tr> 
                        <td> 
                          <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>
                                <input name="passwort" type="password"  id="passwort" size="35">
                            </td>
                              <td><input class="button" type="submit" name="Submit" value="login"></td>
                          </tr>
                        </table>
                       </td>
                    </tr>
                  </table>
</form>
	<?
}

function MsgBox($message) {
?>

<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

 <tr>
    <td valign="top" class="boxstandart"><? echo($message); ?></td>
  </tr>
</table>
<?
}

function PageID($file) {
	@require_once("include/config.inc.php");
	@require_once("include/mysql-class.inc.php");
	global $sql_prefix;
	
	$end = explode(".", $file);
	$endung = $end[1];
	
	$file = str_replace("../","",$file);
	$file = str_replace("_print.$endung",".$endung",$file);
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "dokumente WHERE datei='/$file'");
	while ($row = $sql->FetchRow()) {
		$page = $row->id;
	}
	$sql->Close();
	
	if (!isset($page)) {
		$page = 0;
	}
	return $page;
}

function eLog($typ, $zeile) {
	@require_once("include/config.inc.php");
	global $sql_prefix;
	
	$log_sql =& new MySQLq();
	$log_sql->Query("INSERT INTO " . $sql_prefix . "logs(datum,zeile,typ) VALUES ('" . time() . "', '" . addslashes($zeile) . "', '$typ')");
	$log_sql->Close();
	return true;
}

function RenderPages() {
	@require_once("include/config.inc.php");
	@require_once("include/mysql-class.inc.php");
	global $sql_prefix;
	global $abs_pfad;
	global $p4cms_pfad;
	
	$sql =& new MySQLq();
	$sql->Query("SELECT datei,ablauf,id FROM " . $sql_prefix . "dokumente");
	while ($row = $sql->FetchRow()) {
		if (strlen($row->ablauf) > 3 && ereg(".", $row->ablauf)) {
			list($tag, $monat, $jahr) = explode(".", $row->ablauf);
			$abl = mktime(0, 0, 0, $monat, $tag, $jahr);
			if ($abl < time()) {
				
				$end = explode(".", $row->datei);
				$endung = $end[1];
				
				@unlink(".." . $row->datei);
				@unlink(".." . str_replace(".$endung", "_print.$endung", $row->datei));
				
				$sql2 =& new MySQLq();
				$sql2->Query("UPDATE " . $sql_prefix . "dokumente SET pubdatum='99999999999999',published='no' WHERE id='$row->id'");
				$sql2->Close();
			}
		}
	}
	$sql->Close();
	
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "dokumente WHERE published='no' AND pubdatum<='" . time() . "'");
	while ($row = $sql->FetchRow()) {
		$rusql =& new MySQLq();
		$rusql->Query("SELECT * FROM " . $sql_prefix . "rubriken WHERE id='$row->rubrik'");
		$rubrik = $rusql->FetchRow();
		$rusql->Close();
		
		$vosql =& new MySQLq();
		$vosql->Query("SELECT * FROM " . $sql_prefix . "vorlagen WHERE id='$rubrik->stdvorlange'");
		$vorlage = $vosql->FetchRow();
		$vosql->Close();
		
		$page = stripslashes($vorlage->vorlage);
		$rub = stripslashes($rubrik->template);
		
		$sql2 =& new MySQLq();
		$sql2->Query("SELECT id,feld,inhalt FROM " . $sql_prefix . "dokumente_felder WHERE dokument='$row->id'");
		while ($row2 = $sql2->FetchRow()) {
			$prefix = "";
			
			$sql3 =& new MySQLq();
			$sql3->Query("SELECT typ FROM " . $sql_prefix . "rubriken_felder WHERE id='$row2->feld'");
			$tarr = $sql3->FetchRow();
			$typ = $tarr->typ;
			$sql3->Close();
			
			$inhh = "";
			
			if (substr($typ,0,4)=="bild") {
				if ($typ=="bildr") {
					$zusatz = " align=\"right\"";
				}
				if ($typ=="bildl") {
					$zusatz = " align=\"left\"";
				}
				if ($typ=="bild") {
					$zusatz = "";
				}
				if (rtrim(ltrim($row2->inhalt))=="") {
					$inh = "";
				} else {
					$inhh = "<img src=\"$p4cms_pfad/media$row2->inhalt\" border=\"0\"$zusatz>";
				}
			}
			
			if ($typ=="video") {
				if (rtrim(ltrim($row2->inhalt))=="") {
					$inh = "";
				} else {
					$inhh = "<img dynsrc=\"$p4cms_pfad/media$row2->inhalt\" border=\"0\">";
				}
			}
			
			if ($inhh=="") {
				$inhh = $row2->inhalt;
			}
			
			$rub = str_replace("{RUB:" . $row2->feld . "}", stripslashes($prefix . $inhh), $rub);
		}
		$sql2->Close();
		
		$end = explode(".", $row->datei);
		$endung = $end[1];
		
		$rub = str_replace("{PRINTURL}", str_replace(".$endung", "_print.$endung", $row->datei), $rub);
		$page = str_replace("{PRINTURL}", str_replace(".$endung", "_print.$endung", $row->datei), $page);
		$page = str_replace("{CONTENT}", $rub, $page);
		$page = str_replace("{p4:datum}", date("d.m.Y",time()), $page);
		
		/* - Unperformant -
		$sql2 =& new MySQLq();
		$sql2->Query("SELECT id FROM " . $sql_prefix . "abfragen");
		while ($row2 = $sql2->FetchRow()) {
		$page = str_replace("{ABF:$row2->id}", "<script language=\"javascript\" src=\"$p4cms_pfad/abf_js.php?id=$row2->id\"></script>", $page);
		$rub = str_replace("{ABF:$row2->id}", "<script language=\"javascript\" src=\"$p4cms_pfad/abf_js.php?id=$row2->id\"></script>", $rub);
		}
		$sql2->Close();
		*/
		
		/*
		$page = ereg_replace("{ABF:([0-9]*)}", "<script language=\"javascript\" src=\"$p4cms_pfad/abf_js.php?id=\\1\"></script>", $page);
		$rub = ereg_replace("{ABF:([0-9]*)}", "<script language=\"javascript\" src=\"$p4cms_pfad/abf_js.php?id=\\1\"></script>", $rub);
		*/
		
		$page = ereg_replace("{ABF:([0-9]*)}", "<" . "? \$abs_pfad = \"$abs_pfad\"; \$aabf = \\1; include(\"${abs_pfad}abf_js.php\"); ?" . ">", $page);
		$rub = ereg_replace("{ABF:([0-9]*)}", "<" . "? \$abs_pfad = \"$abs_pfad\"; \$aabf = \\1; include(\"${abs_pfad}abf_js.php\"); ?" . ">", $rub);
		
		
		$page = str_replace("{p4:datum}", date("d.m.Y",time()), $page);
		$rub = str_replace("{p4:datum}", date("d.m.Y",time()), $rub);
		
		$page = str_replace("{TITEL}", stripslashes($row->titel), $page);
		$rub = str_replace("{RUB}", stripslashes($row->titel), $rub);
		
		$sql2 =& new MySQLq();
		$sql2->Query("SELECT * FROM " . $sql_prefix . "module_vars");
		while ($row2 = $sql2->FetchRow()) {
			$neues = stripslashes($row2->neu);
			$neues = str_replace("{abs}", $abs_pfad, $neues);
			$page = ereg_replace(stripslashes($row2->alt), $neues, $page);
			$rub = ereg_replace(stripslashes($row2->alt), $neues, $rub);
		}
		$sql2->Close();
		
		$page_header = "<!--
	
 p4Content Managment System
 Datei      : $row->datei
 Generiert  : " . date("d.m.Y H:i:s") . "
	
-->";
		
		$page = $page_header . "\n\n" . $page;
		
		$curfn = "..";
		$fns = explode("/", $row->datei);
		for ($i=0; $i<count($fns)-1; $i++) {
			$curfn .= $fns[$i] . "/";
			if (!file_exists($curfn)) {
				@mkdir($curfn);
				@chmod($curfn, 0777);
			}
		}
		
		$handle = @fopen("../" . $row->datei, "w+");
		@fwrite($handle, $page);
		@fclose($handle);
		@chmod("../" . $row->datei, 0777);
		
		if ($rubrik->printv=="yes") {
			$printcode = "<!--
 <START> p4cms Seiten-Druck-Code
-->
			
 <script language=\"javascript\">
 <!--
   window.print();
 //-->
 </script>
			
<!--
 <ENDE> p4cms Seiten-Druck-Code
-->";	$end = explode(".", $row->datei);
			$endung = $end[1];
			$rub = str_replace(".$endung", "_print.$endung", $page_header) . "\n\n" . $rub . "\n\n" . $printcode;
			$handle = @fopen("../" . str_replace(".$endung", "_print.$endung", $row->datei), "w+");
			@fwrite($handle, $rub);
			@fclose($handle);
			@chmod("../" . str_replace(".$endung", "_print.$endung", $row->datei), 0777);
			eLog('system', "Seite " . str_replace(".$endung", "_print.$endung", $row->datei) . " gerendert und gespeichert");
		}
		
		$sql2 =& new MySQLq();
		$sql2->Query("UPDATE " . $sql_prefix . "dokumente SET published='yes', pubdatum='" . time() . "' WHERE id='$row->id'");
		$sql2->Close();
		
		eLog('system', "Seite $row->datei gerendert und gespeichert");
	}
	$sql->Close();
}

function ListDir($dir, $node) {
	global $i;
	global $sessid;
	if (!isset($i)) {
		$i = 1;
	}
	$d = dir($dir);
	while (false !== ($entry = $d->read())) {
		if (!($entry==".") and !($entry=="..") and !($entry=="p4cms")) {
			if (is_dir($dir . $entry)) {
				$i++;
				echo "		d.add($i,$node,\"$entry\",\"\",\"\",\"\",\"gfx/tree/folder.gif\");\n";
				ListDir($dir . $entry . "/", $i);
			} else {
				//$i++;
				//if (substr($entry,strlen($entry)-5)==".html" or substr($entry,strlen($entry)-4)==".htm") {
				//	$edfunc = "EditDocument";
				//	$docid = PageID($dir . $entry);
				//} else {
				//	$edfunc = "ShowFile";
				//	$docid = str_replace("../", "", $dir . $entry);
				//}
				//if ($docid==0) {
				//	$edfunc = "ShowFile";
				//	$docid = str_replace("../", "", $dir . $entry);
				//}
				//echo "		d.add($i,$node,\"$entry\",\"javascript:$edfunc('$docid','$sessid');\");\n";
			}
		}
	}
	$d->close();
	$d = dir($dir);
	while (false !== ($entry = $d->read())) {
		if (!($entry==".") and !($entry=="..") and !($entry=="p4cms")) {
			if (is_dir($dir . $entry)) {
				//$i++;
				//echo "		d.add($i,$node,\"$entry\",\"\",\"\",\"\",\"gfx/tree/folder.gif\");\n";
				//ListDir($dir . $entry . "/", $i);
			} else {
				$i++;
				if (substr($entry,strlen($entry)-5)==".html" or substr($entry,strlen($entry)-4)==".htm") {
					$edfunc = "EditDocument";
					$docid = PageID($dir . $entry);
				} else {
					$edfunc = "ShowFile";
					$docid = str_replace("../", "", $dir . $entry);
				}
				if ($docid==0) {
					$edfunc = "ShowFile";
					$docid = str_replace("../", "", $dir . $entry);
				}
				echo "		d.add($i,$node,\"$entry\",\"javascript:$edfunc('$docid','$sessid');\");\n";
			}
		}
	}
	$d->close();
}

function ListDir2($dir, $node) {
	global $i;
	global $sessid;
	if (!isset($i)) {
		$i = 1;
	}
	$d = dir($dir);
	while (false !== ($entry = $d->read())) {
		if (!($entry==".") and !($entry=="..") and !($entry=="p4cms")) {
			if (is_dir($dir . $entry)) {
				$i++;
				echo "		d.add($i,$node,\"$entry\",\"\",\"\",\"\",\"gfx/tree/folder.gif\");\n";
				ListDir2($dir . $entry . "/", $i);
			} else {
				$i++;
				if (substr($entry,strlen($entry)-5)==".html" or substr($entry,strlen($entry)-4)==".htm") {
					$edfunc = "EditDocument";
					$docid = PageID($dir . $entry);
					echo "		d.add($i,$node,\"$entry\",\"javascript:ChangeFField('$docid','fm','dok');\");\n";
				}
			}
		}
	}
	$d->close();
}


function SessionError() {
	?>
<html>
<head>
<title>p4cms Fehler</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<? StyleSheet(); ?>
</head>
<body scroll="no" bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
MsgBox("<center>Die Session ist abgelaufen oder Sie sind nicht eingeloggt. Bitte loggen Sie sich neu ein.</center>");
?>
</body>
</html>
	<?
}

function Gruppe($id) {
	@require_once("include/config.inc.php");
	@require_once("include/mysql-class.inc.php");
	global $sql_prefix;
	
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "gruppen WHERE id='$id'");
	while ($row = $sql->FetchRow()) {
		$grp = array();
		$grp[titel] = stripslashes($row->titel);
		$grp[m_redakteur] = $row->m_redakteur;
		$grp[m_vorlagen] = $row->m_vorlagen;
		$grp[m_abfragen] = $row->m_abfragen;
		$grp[m_dokumente] = $row->m_dokumente;
		$grp[m_mediapool] = $row->m_mediapool;
		$grp[m_newsletter] = $row->m_newsletter;
	}
	$sql->Close();
	if (!isset($grp)) {
		$grp = false;
	}
	return $grp;
}

function Yes2Ja($text) {
	$text = str_replace("yes","Ja",$text);
	$text = str_replace("no","Nein",$text);
	return $text;
}

function Zufall($min, $max) {
	mt_srand((double)microtime()*1000000);
	return mt_rand($min, $max);
}

function TipOfTheDay() {
	$tipfile = file("include/tipoftheday.txt");
	$zufall = Zufall(0, count($tipfile)-1);
	return $tipfile[$zufall];
}

function PFelder($sheme, $a) {
	global $felder;
	reset($felder);
	$res = "";
	while(list($key,$val) = each($felder)) {
		$nm = "";
		if ($val=="Bild (Rechts)") {
			$nm = "bildr";
		}
		if ($val=="Bild (Links)") {
			$nm = "bildl";
		}
		if ($val=="Bild (Normal)") {
			$nm = "bild";
		}
		if ($nm=="") {
			$nm = $val;
		}
		if (strtolower($nm) == strtolower($a)) {
			$sel = " selected";
		} else {
			$sel = "";
		}
		$res .= str_replace("%s", $val, str_replace("%x", $sel, str_replace("%y", $nm, $sheme)));
	}
	return $res;
}








class Template {
	var $_file;
	var $_out;
	
	function Template($fn) {
		global $theme,$RL,$CR,$abs_pfad,$modules;
		$this->_file = "" . $fn;
		
		$filename = $this->_file;
		$fd = fopen ($filename, "r")or die("FEHLER $filename");
		$tmpl = fread ($fd, filesize ($filename));
		fclose ($fd);
		
		$this->_out = $tmpl;
		
		return true;
	}
	
	function Insert($cap,$wert) {
		$this->_out = str_replace($cap, $wert, $this->_out);
		return true;
	}
	
	function POut() {
		$this->_aaprepare();
		$page = stripslashes($this->_out);
		echo ($page);
		return true;
	}
	
	function VOut() {
		$this->_aaprepare();
		$page = stripslashes($this->_out);
		return $page;
	}
	
	function _aaprepare() {
		global $sessid;
		$this->_out = str_replace("%sess%", $sessid, $this->_out);
	}
}



function BBcode($text) {
	
	$text = preg_replace("#\[glow=(\#?[\da-fA-F]{6}|[a-z\ \-]{3,})\](.*?)\[/glow\]+#i","<font style=\"filter:Glow(color=\\1, strength=2); WIDTH: 100%\">\\2</font>",$text);
	$text = preg_replace("#\[color=(\#?[\da-fA-F]{6}|[a-z\ \-]{3,})\](.*?)\[/color\]+#i","<font color=\"\\1\">\\2</font>",$text);
	$text = preg_replace("#\[size=()?(.*?)\](.*?)\[/size\]#si", "<font size=\"\\2\">\\3</font>", $text);
	$text = preg_replace("#\[face=()?(.*?)\](.*?)\[/face\]#si", "<font face=\"\\2\">\\3</font>", $text);
	$text = preg_replace("#\[font=()?(.*?)\](.*?)\[/font\]#si", "<font face=\"\\2\">\\3</font>", $text);
	$text = preg_replace("!\[(?i)marquee\]!", "<marquee>", $text);
	$text = preg_replace("!\[/(?i)marquee\]!", "</marquee>", $text);
	$text = preg_replace("!\[(?i)s\]!", "<s>", $text);
	$text = preg_replace("!\[/(?i)s\]!", "</s>", $text);
	$text = preg_replace("!\[(?i)small\]!", "<font size=1>", $text);
	$text = preg_replace("!\[/(?i)small\]!", "</font>", $text);
	$text = preg_replace("!\[(?i)b\]!", "<b>", $text);
	$text = preg_replace("!\[/(?i)b\]!", "</b>", $text);
	$text = preg_replace("!\[(?i)u\]!", "<u>", $text);
	$text = preg_replace("!\[/(?i)u\]!", "</u>", $text);
	$text = preg_replace("!\[(?i)i\]!", "<i>", $text);
	$text = preg_replace("!\[/(?i)i\]!", "</i>", $text);
	$text = preg_replace("!\[(?i)url\](http://|ftp://)([a-zA-Z0-9:/\?\[\]=.@-]+)\[/(?i)url\]+!", "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>", $text);
	$text = preg_replace("!\[(?i)url\]([a-zA-Z0-9:/\?\[\]=.@-]+)\[/(?i)url\]+!", "<a href=\"http://\\1\" target=\"_blank\">\\1</a>", $text);
	$text = preg_replace("!\[(?i)email\]([a-zA-Z0-9-._]+@[a-zA-Z0-9-.]+)\[/(?i)email\]!", "<a href=\"mailto:\\1\">\\1</a>", $text);
	$text = preg_replace("!\[(?i)img\]([_-a-zA-Z0-9:/\?\[\]=.@-]+)\[(?i)/img\]!", "<img src=\"\\1\" border=\"0\">", $text);
	$text = preg_replace("!\[(?i)IMG\]([_-a-zA-Z0-9:/\?\[\]=.@-]+)\[(?i)/IMG\]!", "<img src=\"\\1\" border=\"0\">", $text);
	
	$text = preg_replace("!\[(?i)code\]!", "<blockquote>Code<hr noshade size=1><pre>", $text);
	$text = preg_replace("!\[/(?i)code\]!", "</pre><hr noshade size=1></blockquote>", $text);
	
	$text = preg_replace("!\[(?i)quote\]!", "<blockquote>Zitat<hr noshade size=1>", $text);
	$text = preg_replace("!\[/(?i)quote\]!", "<hr noshade size=1></blockquote>", $text);
	
	
	
	$text = preg_replace("#\[url=(http://)?(.*?)\](.*?)\[/url\]#si", "<A HREF=\"http://\\2\" TARGET=\"_blank\">\\3</A>", $text);
	$text = preg_replace("#\[email=()?(.*?)\](.*?)\[/email\]#si", "<A HREF=\"mailto:\\2\">\\3</A>", $text);
	
	
	return $text;
}

function parseURL($out) {
	$urlsearch[]="/([^]_a-z0-9-=\"'\/])((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
	$urlsearch[]="/^((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
	$urlreplace[]="\\1[URL]\\2\\4[/URL]";
	$urlreplace[]="[URL]\\1\\3[/URL]";
	$emailsearch[]="/([\s])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
	$emailsearch[]="/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
	$emailreplace[]="\\1[EMAIL]\\2[/EMAIL]";
	$emailreplace[]="[EMAIL]\\0[/EMAIL]";
	$out = preg_replace($urlsearch, $urlreplace, $out);
	if (strpos($out, "@")) $out = preg_replace($emailsearch, $emailreplace, $out);
	return $out;
}


function inca($id) {
	global $abs_pfad;
	global $sql_prefix;
	$aabf = str_replace("\\","",$id);
	ob_start();
	include("${abs_pfad}abf_js.php");
	$cnt = ob_get_contents();
	ob_end_clean();
	return $cnt;
}

function incn($id) {
	global $abs_pfad;
	global $sql_prefix;
	$anavi = str_replace("\\","",$id);
	ob_start();
	$modules = "modules";
	include("${abs_pfad}modules/navigation/js.php");
	$cnt = ob_get_contents();
	ob_end_clean();
	return $cnt;
}

function countsuche(){
	global $abs_pfad;
	global $sql_server;
	global $sql_user;
	global $sql_passwort;
	global $sql_db;
	global $sql_prefix;
	ob_start();
	@include("${abs_pfad}modules/stats/stat.php");
	$cne = ob_get_contents();
	ob_end_clean();
	return $cne;
}
?>