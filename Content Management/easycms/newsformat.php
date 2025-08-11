<?php

  session_start() ;
import_request_variables("gP", "r_");

if  ($_SESSION["aut"]<> 1){
   echo "You are not allowed to view this page<br>";
   exit();}


 ?>
<?php
include 'conection.php';
 ?>
<html>
<head>
<title>News Format</title>
<link rel="stylesheet" type="text/css"
href="style.css" />
</head>
<body bgcolor="#66CCFF">
<?php

import_request_variables("gP", "r_");


if (isset($r_submit)){
//$incl="echo('$row'[title])" ;
//$incl2="echo('$row'[description])" ;
$news=str_replace("{TITLE}","<?php echo $" ."row['title']; ?>",$r_description);
$news=str_replace("{CONTENT}","<?php echo $" ."row['description']; ?>",$news);
$news=str_replace("{DATE}","<?php echo $" ."row['date']; ?>",$news);
$newstop= "<?php \n include 'conection.php';\n$" ."result = mysql_query('SELECT title, description, date FROM $prefix"."news limit $r_numb');\n";
$newstop.="while ( $" ."row = mysql_fetch_array($" ."result) ) { ?>\n";
$newstop.=$news;
$newstop.="\n<?php \n }\n?>";

$newstop=stripslashes($newstop);
$r_description=  stripslashes($r_description);

  $file = fopen( "newsnum.txt", "w" );
        fwrite( $file, $r_numb);
 $file = fopen( "news.php", "w" );
        fwrite( $file, $newstop);
 $file = fopen( "newstemp.txt", "w" );
        fwrite( $file, $r_description );
  echo "<h1 style='text-align:center'>News Format</h1>";
echo "News format set<br><br>";
echo "This page is to set the news format<br>";
echo "Use {TITLE} to place the title {DATE} for date and {CONTENT} to set the content<br>\n";

echo "<form action=newsformat.php method='post'>\n  ";
echo "<select name='numb' value=10>  \n";
for ($conteo = 1; $conteo <= 20; $conteo++) {
   echo("<option value=$conteo");
   if ($r_numb==$conteo){
   echo(" selected"); }
echo   (">$conteo</option> \n");
}
echo "</select>Number of News to show";
echo "<textarea cols=70 rows=10 name='description'   >$r_description</textarea><br> \n" ;
echo "<input type='submit' name='submit' value='Submit'> \n";
echo "</form>";        }

else {
 echo "<h1 style='text-align:center'>News Format</h1>";
echo "This page is to set the news format<br>";
echo "Use {TITLE} to place the title {DATE} for date and {CONTENT} to set the content<br>";

echo "<form action=newsformat.php method='post'>  ";
echo "<select name='numb' value=10>  \n";
$handle = fopen ("newsnum.txt", "r");
$contents = fread ($handle, filesize ("newsnum.txt"));
fclose ($handle);

for ($conteo = 1; $conteo <= 20; $conteo++) {
   echo("<option value=$conteo");
   if ($contents==$conteo){
   echo(" selected"); }
echo   (">$conteo</option> \n");
}
echo "</select>Number of News to show";
 if ($file = fopen( "newstemp.txt", "r" )){
        $des="";
        while ( ! feof( $file ) )
        {
        $line = fgets( $file, 1024 );
        $des.=$line;
        }
        echo "<textarea cols=70 rows=10 name='description'   >$des</textarea><br> \n" ; }
 else {
 echo "<textarea cols=70 rows=10 name='description'   ></textarea><br> \n" ;  }

echo "<input type='submit' name='submit' value='Submit'> \n";
echo "</form>";}
?>


</html>
</body>
