<? header("Cache-Control: no-cache"); 
 session_start();
 include ("config.inc.php");


$usermail = strtolower($user."@".$sdomain);


 $hheader = $hfrom;


 $natext = $text;

 $re = mail("$an@pager.mirabilis.com", stripslashes($betreff), stripslashes($natext), "From: $name <$usermail>");

 if ($re) {
  $tdd = "Die Nachricht wurde erfolgreich versendet.";
 } else {
  $tdd = "Es gab einen Fehler beim Versenden der Nachricht.";
 }


 $filename = "templates/${template}/icqsend.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%MESSAGE%", "$tdd", $tmpl);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>
