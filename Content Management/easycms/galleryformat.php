<?php

  session_start() ;
import_request_variables("gP", "r_");

if  ($_SESSION["aut"]<> 1){
   echo "You are not allowed to view this page<br>";
   exit();}


 ?>
<html>
<head>
<title>New Gallery</title>
<link rel="stylesheet" type="text/css"
href="style.css" />
</head>
<body bgcolor="#66CCFF">


<?php
//this is the galleryformat
 include 'conection.php';
 import_request_variables("gP", "r_");
 
if (isset($r_submit)){
//$incl="echo('$row'[title])" ;
//$incl2="echo('$row'[description])" ;

//this is to write the comment.php file
$com=str_replace("{NICKNAME}", "<?php echo $"."comrow['username']; ?>", $r_comment);
$com=str_replace("{COMMENT}", "<?php echo $"."comrow['description']; ?>", $com);
$comtop="<?php \n while ( $"."comrow = mysql_fetch_array($"."comments) ) { \n ?> \n";
$comtop.=$com;
$comtop.="\n <?php \n } \n ?> \n" ;

 $file = fopen( "comment.php", "w" );
        fwrite( $file, $comtop);
  $file = fopen( "comtemp.txt", "w" );
        fwrite( $file, $r_comment );

$news=str_replace("{TITLE}","<?php echo $" ."row['title']; ?>",$r_description);
$news=str_replace("{PICTURE}","<?php echo "."\""."<img src="."'"."$" ."row[picture]' border='0'><br>"."\""."; ?>",$news);
$news=str_replace("{DESCRIPTION}","<?php echo $" ."row['description']; ?>",$news);
$news=str_replace("{COM}",$comtop ,$news);
$news=str_replace("{TOP}","<?php include 'top.php' ?>" ,$news);
$news=str_replace("{LEFT}","<?php include 'left.php' ?>" ,$news);
$news=str_replace("{RIGHT}","<?php include 'right.php' ?>" ,$news);
$news=str_replace("{BOTTOM}","<?php include 'bottom.php' ?>" ,$news);

$newstop= "<html><body> \n <?php \n include 'conection.php';\n   import_request_variables('gP', 'r_') ;\n if (isset($"."r_submit)){ \n $" ."newcomment=mysql_query(" ."\""."insert into $prefix"."comments set description='$"."r_comment', username='$"."r_username', gallery='$"."r_gallery' "."\"".");} \n " ;
$newstop.="$"."result = mysql_query("."\""."SELECT * FROM $prefix"."galleries where id='$"."r_gallery'"."\""."); \n"  ;
$newstop.="$"."row = mysql_fetch_array($"."result) ; \n"     ;
$newstop.="$"."comments=mysql_query("."\""."select description, username from $prefix"."comments where gallery='$"."r_gallery'"."\""."); ?> \n" ;
$newstop.=$news;

$file = fopen( "galbot.txt", "r" )   ;
        $des="";
        while ( ! feof( $file ) )
        {
        $line = fgets( $file, 1024 );
        $des.=$line;
        }
        
$newstop.="\n". $des  ;

$newstop=stripslashes($newstop);
$r_description=  stripslashes($r_description);

 $file = fopen( "gallery.php", "w" );
        fwrite( $file, $newstop);
 $file = fopen( "gallerytemp.txt", "w" );
        fwrite( $file, $r_description );
  echo "<h1 style='text-align:center'>Gallery Format</h1>";
echo "Gallery format set<br><br>";
echo "This page is to set the galleries format<br>";
echo "Use {TITLE} to place the title, {PICTURE} for the picture, {DESCRIPTION} to set the description";
echo " and {COM} will mark the place where all your comments will appear. \n";
echo "You can also use {TOP}, {LEFT}, {RIGHT} and {BOTTOM} \n";
echo "<form action=galleryformat.php method='post'> \n ";
echo "<textarea cols=50 rows=10 name='description'   >$r_description</textarea><br> \n" ;
echo "<br>The area bellow is to set the format of each individual comment \n <br>";
echo "{NICKNAME} is the name of the comment poster and {COMMENT} the comment.<br>\n";
echo "<textarea cols=50 rows=7 name='comment'   >$r_comment</textarea><br> \n" ;
echo "<input type='submit' name='submit' value='Submit'> \n";
echo "</form>";        }

else {
 echo "<h1 style='text-align:center'>Gallery Format</h1>";
echo "This page is to set the galleries format<br>";
echo "Use {TITLE} to place the title, {PICTURE} for the picture, {DESCRIPTION} to set the description";
echo " and {COM} will mark the place where all your comments will appear. \n";
echo "You can also use {TOP}, {LEFT}, {RIGHT} and {BOTTOM} \n";
echo "<form action=galleryformat.php method='post'>  ";

 if ($file = fopen( "gallerytemp.txt", "r" )){
        $des="";
        while ( ! feof( $file ) )
        {
        $line = fgets( $file, 1024 );
        $des.=$line;
        }
        echo "<textarea cols=50 rows=10 name='description'   >$des</textarea><br> \n" ; }
 else {
 echo "<textarea cols=50 rows=10 name='description'   ></textarea><br> \n" ;  }
echo "The area bellow is to set the format of each individual comment \n";
echo "{NICKNAME} is the name of the comment poster and {COMMENT} the comment. \n<br>";
if ($file = fopen( "comtemp.txt", "r" )){
        $des="";
        while ( ! feof( $file ) )
        {
        $line = fgets( $file, 1024 );
        $des.=$line;
        }
echo "<textarea cols=50 rows=7 name='comment'   >$des</textarea><br> \n" ;    }
else{
echo "<textarea cols=50 rows=7 name='comment'   ></textarea><br> \n" ;}

echo "<input type='submit' name='submit' value='Submit'> \n";
echo "</form>";}
?>


 </html>
</body>






