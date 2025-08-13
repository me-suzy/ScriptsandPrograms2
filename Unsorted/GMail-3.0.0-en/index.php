<?
 include("config.inc.php");

 $filename = "templates/${template}/index.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 

$domains = split(":",$domain);
$dcount = count($domains);

for ($i=0;$i<$dcount;$i++) {
 $actuell = $domains[$i];
 
$options = $options . "
<option value=\"$domains[$i]\">$domains[$i]</option>"; 

}

  $output = str_replace ( "%DOMAINOPTIONS%", "$options", $tmpl);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>

