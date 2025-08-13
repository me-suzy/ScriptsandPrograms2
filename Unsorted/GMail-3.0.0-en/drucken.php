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

                                   $lap = "$lap".$obj->ctype_parameters['name'];
					}else{
						$parts['text'][] = $obj->body;
					}
					break;

				case 'text/html':
					if(!empty($obj->disposition) AND $obj->disposition == 'attachment'){
						$parts['attachments'][] = $obj->body;

                                        $lap = "$lap".$obj->ctype_parameters['name'];
					}else{
						$parts['html'][] = $obj->body;
					}
					break;

				default:
					$parts['attachments'][] = $obj->body;

                                   $lap = "$lap".$obj->ctype_parameters['name'];
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



if ($an==$usermail) {
?>
<html>
<head>
<title>Drucken: <? echo($betreff); ?></title>
<style>
<!--
a:link     { text-decoration: none      }
a:hover    { text-decoration: underline }
a:active   { text-decoration: none      }
a:visited  { text-decoration: none      }

a.newmsg:link     { color: #ff0000; text-decoration: none      }
a.newmsg:hover    { color: #ff0000; text-decoration: underline }
a.newmsg:active   { color: #ff0000; text-decoration: none      }
a.newmsg:visited  { color: #ff0000; text-decoration: none      }

a.oldmsg:link     { color: #000000; text-decoration: none      }
a.oldmsg:hover    { color: #000000; text-decoration: underline }
a.oldmsg:active   { color: #000000; text-decoration: none      }
a.oldmsg:visited  { color: #000000; text-decoration: none      }

a.dl:link     { color: #ffffff; text-decoration: none      }
a.dl:hover    { color: #ffffff; text-decoration: underline }
a.dl:active   { color: #ffffff; text-decoration: none      }
a.dl:visited  { color: #ffffff; text-decoration: none      }
-->
</style>
<SCRIPT language=javascript>
<!--

function LmOver(elem, clr)
{elem.style.backgroundColor = clr;}

function LmOut(elem, clr)
{elem.style.backgroundColor = clr;}

function LmDown(elem, clr)
{elem.style.backgroundColor = clr;}

//-->
</SCRIPT>

</head>
<body onload="window.print();">
<center>

<font face="arial" size="+2">Drucken</font><br>
<small>Sollte der Ausdruck nicht innerhalb von 15 Sekunden starten, klicken sie <a href="javascript:window.print();">hier</a>.</small><br><br>
<hr>
</center>
<table width="100%">

<tr>
 <td><b>An:</b></td>
 <td><? echo($an); ?></td>
</tr>

<tr>
 <td><b>Von:</b></td>
 <td><? echo($absender); ?></td>
</tr>

<tr>
 <td><b>Datum:</b></td>
 <td><? echo($datum); ?></td>
</tr>

<tr>
 <td><b>Kopieempfänger (CC):</b></td>
 <td><? echo($cc); ?></td>
</tr>

<tr>
 <td><b>Betreff:</b></td>
 <td><? echo($betreff); ?></td>
</tr>

<tr>
 <td><b>Anlagen:</b></td>
 <td><? echo($lap); ?></td>
</tr>

</table>
<hr>
<? echo(nl2br(stripslashes(htmlspecialchars($text)))); ?>
<hr>



</body>
</html>

<?
} else {
?>
Fehler: Keine Berechtigung zum Anzeigen der Mail.
<?
}
?>
