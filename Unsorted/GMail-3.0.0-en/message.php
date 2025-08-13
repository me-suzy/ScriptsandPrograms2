<? header("Cache-Control: no-cache"); ?>
<?
 session_start();
 include ("config.inc.php");
 include ("mimeDecode.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);


$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_mails WHERE id='$id'";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {

    $betreff  = $row->Titel;
    $absender = $row->Von;
    $datum    = $row->Datum;
    //$text     = $row->Body;
    $cc       = $row->CC;
    $an       = $row->An;
    $message  = $row->Body;

  }

mysql_free_result($ergebnis);


$sql = "UPDATE b1gmail_mails SET Gelesen=1 WHERE id='$id'";
$ergebnis = mysql_query($sql, $verbindung);


mysql_close($verbindung);




$params = array(
					'input'          => $message,
					'crlf'           => "\r\n",
					'include_bodies' => TRUE,
					'decode_headers' => TRUE,
					'decode_bodies'  => TRUE
					);

	$output = Mail_mimeDecode::decode($params);

	$parts = array();
	$htmlm = "0";
      
	function parse_output(&$obj, &$parts, &$id, &$lap, &$htmlm){

		if(!empty($obj->parts)){
			for($i=0; $i<count($obj->parts); $i++)
				parse_output($obj->parts[$i], $parts, $id, $lap, $htmlm);

		}else{
			$ctype = $obj->ctype_primary.'/'.$obj->ctype_secondary;
			switch($ctype){
				case 'text/plain':
					if(!empty($obj->disposition) AND $obj->disposition == 'attachment'){
						$parts['attachments'][] = $obj->body;


                                        $lap = "$lap<a href=\"showattach.php?mid=$id&fn=".$obj->ctype_parameters['name']."&id=".count($parts['attachments'])."\" class=\"dl\" >".$obj->ctype_parameters['name']."</a> ";
					}else{
						$parts['text'][] = $obj->body;
					}
					break;

				case 'text/html':
					if(!empty($obj->disposition) AND $obj->disposition == 'attachment'){
						$parts['attachments'][] = $obj->body;

                                        $lap = "$lap<a href=\"showattach.php?mid=$id&fn=".$obj->ctype_parameters['name']."&id=".count($parts['attachments'])."\" class=\"dl\" >".$obj->ctype_parameters['name']."</a> ";
					}else{
					    $htmlm = "1";
						$parts['html'][] = $obj->body;
					}
					break;

				default:
					$parts['attachments'][] = $obj->body;

                                        $lap = "$lap<a href=\"showattach.php?mid=$id&fn=".$obj->ctype_parameters['name']."&id=".count($parts['attachments'])."\" class=\"dl\" >".$obj->ctype_parameters['name']."</a> ";
		}
		}

	}

	parse_output($output, $parts, $id, $lap, $htmlm);


for ($i=0;$i<count($parts['text']);$i++) {
 $text = $parts['text'][$i]; 
 $htmt = $parts['html'][$i];
}

//for ($i=0;$i<count($parts['attachments']);$i++) {
// echo $parts['attachments'][$i]; 
//}

if ($lap=="") {
 $lap = "Keine";
}

if (strtolower($an)==strtolower($usermail)) {


 $filename = "templates/${template}/message.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


if ($cc=="") {
 $cc="<i>Keine Kopieempfänger</i>";
} else {
 $cc="$cc";
}


$text = nl2br(stripslashes(htmlspecialchars($text)));

if ($html=="on") {
    $text = "<table width=\"100%\" height=\"100%\" bgcolor=\"#ffffff\"><tr><td>$htmt</td></tr></table>";
} else {
if($htmlm=="1") {
    $text = "<b><center><a href=\"message.php?id=$id&html=on\" class=\"dl\">(Diese Nachricht befindet sich im HTML Format. Aus Sicherheitsgründen wird nur die Text-Ansicht angezeigt. Um in die HTML Ansicht zu wechseln, klicken sie hier.)</a></center></b><br><br>$text";
}
}
  $output = str_replace ( "%VON%", "$absender", $tmpl);
  $output = str_replace ( "%DATUM%", "$datum", $output);
  $output = str_replace ( "%AN%", "$an", $output);
  $output = str_replace ( "%CC%", "$cc", $output);
  $output = str_replace ( "%BETREFF%", htmlspecialchars($betreff), $output);
  $output = str_replace ( "%TEXT%", "$text", $output);
  $output = str_replace ( "%ID%", "$id", $output);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);
  $output = str_replace ( "%ANLAGEN%", "$lap", $output);

  $output = stripslashes ($output);
 
  echo ($output);

} else {
?>
Fehler: Keine Berechtigung zum Anzeigen der Mail.
<?
}
?>
