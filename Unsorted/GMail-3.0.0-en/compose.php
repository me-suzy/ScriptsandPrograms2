<? header("Cache-Control: no-cache"); 
 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);



 $filename = "templates/${template}/compose.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 

 $dat = date("d.m.Y");

  $output = str_replace ( "%VON%", "$usermail", $tmpl);
  $output = str_replace ( "%DATUM%", "$dat", $output);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>
