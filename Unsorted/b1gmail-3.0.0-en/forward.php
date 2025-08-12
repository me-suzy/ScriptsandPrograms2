<? header("Cache-Control: no-cache"); 
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
    $message  = $row->Body;
    $cc       = $row->CC;
    $an       = $row->An;
 
  }

mysql_free_result($ergebnis);
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
      
	function parse_output(&$obj, &$parts, &$id, &$lap){

		if(!empty($obj->parts)){
			for($i=0; $i<count($obj->parts); $i++)
				parse_output($obj->parts[$i], $parts, $id, $lap);

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
						$parts['html'][] = $obj->body;
					}
					break;

				default:
					$parts['attachments'][] = $obj->body;

                                        $lap = "$lap<a href=\"showattach.php?mid=$id&fn=".$obj->ctype_parameters['name']."&id=".count($parts['attachments'])."\" class=\"dl\" >".$obj->ctype_parameters['name']."</a> ";
		}
		}

	}

	parse_output($output, $parts, $id, $lap);


for ($i=0;$i<count($parts['text']);$i++) {
 $text = $parts['text'][$i]; 
}

//for ($i=0;$i<count($parts['attachments']);$i++) {
// echo $parts['attachments'][$i]; 
//}


if (strtolower($an)==strtolower($usermail)) {


 $filename = "templates/${template}/forward.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 
$intext = stripslashes(htmlspecialchars($text));
$dat = date("d.m.Y");

  $output = str_replace ( "%VON%", "$usermail", $tmpl);
  $output = str_replace ( "%AN%", "$absender", $output);
  $output = str_replace ( "%BETREFF%", "$betreff", $output);
  $output = str_replace ( "%TEXT%", "$intext", $output);
  $output = str_replace ( "%DATUM%", "$dat", $output);


  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);

} else {
?>
Fehler: Keine Berechtigung zum Anzeigen der Mail.
<?
}
?>
