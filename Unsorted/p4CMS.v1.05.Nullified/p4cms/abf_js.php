<?
 @require_once($abs_pfad."include/config.inc.php");
 @require_once($abs_pfad."include/mysql-class.inc.php");
 @require_once($abs_pfad."include/functions.inc.php");

 $_REQUEST['id'] = $aabf;
 
 $id = $_REQUEST['id'];
 
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "abfragen WHERE id='$id'");
 $row = $sql->FetchRow();
 $sql->Close();
 
 if ($row->typ=="letzten") {
 	$elm_1 = "DESC";
 } else {
 	$elm_1 = "ASC";
 }
 
 $elm_2 = $row->zahl;
 $elm_3 = $row->rubrik;
 
 $vorlage = stripslashes($row->template);
 $etext = "";
 
 $qr = "SELECT * FROM " . $sql_prefix . "dokumente WHERE rubrik='$elm_3' AND published!='no' ORDER BY id $elm_1 LIMIT $elm_2";
 $sql =& new MySQLq();
 $sql->Query($qr);
 while ($row = $sql->FetchRow()) {
 	$elm = $vorlage;
 	
 	$sql2 =& new MySQLq();
 	$sql2->Query("SELECT * FROM " . $sql_prefix . "dokumente_felder WHERE dokument='$row->id'");
 	while ($row2 = $sql2->FetchRow()) {
 		$sql3 =& new MySQLq();
 		$sql3->Query("SELECT typ FROM " . $sql_prefix . "rubriken_felder WHERE id='$row2->feld'");
 		$row3 = $sql3->FetchRow();
 		$sql3->Close();
 		$prefix = "";
		$inhh = "";
 		if (substr($row3->typ,0,4) == "bild") {
			if ($row3->typ=="bildr") {
				$zusatz = " align=\"right\"";
			}
			if ($row3->typ=="bildl") {
				$zusatz = " align=\"left\"";
			}
			if ($row3->typ=="bild") {
				$zusatz = "";
			}
			if (rtrim(ltrim($row2->inhalt))=="") {
				$inh = "";
			} else {
				$inhh = "<img src=\"$p4cms_pfad/media$row2->inhalt\" border=\"0\"$zusatz>";
			}
 		}

		if ($typ=="video") {
			$inhh = "<img dynsrc=\"$p4cms_pfad/media$row2->inhalt\" border=\"0\">";
		}
 
		if ($inhh=="") {
			$inhh = $row2->inhalt;
		}
 		$elm = str_replace("{RUB:$row2->feld}", stripslashes($inhh), $elm);
		$elm = str_replace("{p4:datum}", date("d.m.Y",time()), $elm);
 	}
 	$sql2->Close();
 	
 	$elm = str_replace("{LINK}", $row->datei, $elm);
	$elm = str_replace("{p4:datum}", date("d.m.Y",time()), $elm);
 	
 	$etext .= $elm . "\n";
 	
 	$rubid = $row->rubrik;
 	$sql2 =& new MySQLq();
 	$sql2->Query("SELECT * FROM " . $sql_prefix . "variablen WHERE rub='$rubid'");
 	while ($row2 = $sql2->FetchRow()) {
 		$varname = "{USER:$row2->id}";
 			$substr_1 = $row2->tw_start;
 			$substr_2 = $row2->tw_count;
 			$thevar = $row2->tw_var;
 			$txt = $row2->tw_var_t;
 			$sql3 =& new MySQLq();
 			$sql3->Query("SELECT inhalt FROM " . $sql_prefix . "dokumente_felder WHERE dokument='$row->id' AND feld='$thevar'");
 			$row3 = $sql3->FetchRow();
 			$sql3->Close();
 			
 			$var_inh = substr(stripslashes($row3->inhalt), $substr_1, $substr_2);
 			if (strlen(stripslashes($row3->inhalt)) > ($substr_2 - $substr_1)) {
 				$var_inh .= stripslashes($txt);
 			}
 		$etext = str_replace($varname, $var_inh, $etext);
		$etext = str_replace("{p4:datum}", date("d.m.Y",time()), $etext);
 	}
 	$sql2->Close();
 }
 $sql->Close();
 
 $etext = str_replace("\r\n", "\n", $etext);
 
 echo $etext;
?>
