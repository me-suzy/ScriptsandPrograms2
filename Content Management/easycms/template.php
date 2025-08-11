<?php

  session_start() ;
import_request_variables("gP", "r_");

if  ($_SESSION["aut"]<> 1){
   echo "You are not allowed to view this page<br>";
   exit();}


 ?>
<html>
<head>
<title>Template Manager</title>
<link rel="stylesheet" type="text/css"
href="style.css" />
</head>
<body bgcolor="#66CCFF">

<?php
// this is to have the top bottom etc templates
include 'conection.php';


if (isset($r_submit)){

update();
$file = fopen( 'top.php', "w" );
        fwrite( $file, stripslashes($r_content1 ));
$file = fopen( 'left.php', "w" );
        fwrite( $file, stripslashes($r_content2) );
$file = fopen( 'right.php', "w" );
        fwrite( $file, stripslashes($r_content3 ));
$file = fopen( 'bottom.php', "w" );
        fwrite( $file, stripslashes($r_content4) );
}

//function to update the templates
function update(){
global $r_content1, $r_content2, $r_content3, $r_content4, $prefix;
$update=mysql_query("update $prefix"."template set content='$r_content1' where id=1");
 if (!$update) {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}

 $update=mysql_query("update $prefix"."template set content='$r_content2' where id=2");
 if (!$update) {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}
$update=mysql_query("update $prefix"."template set content='$r_content3' where id=3");
 if (!$update) {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}
$update=mysql_query("update $prefix"."template set content='$r_content4' where id=4");
 if (!$update) {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}

}

//function to load the templates
function template($id){
global $prefix;
$result = mysql_query("SELECT * FROM $prefix"."template where id=$id");
if (!$result) {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}
$row = mysql_fetch_array($result) ;
echo "$row[section] <br>\n";
echo "<textarea cols=70 rows=6 name='content$id'  >$row[content]</textarea><br><br>\n" ;
  }

// printing the form
echo "<h1 style='text-align:center'>Template Manager</h1>";
echo "This are the diferent sections of your site<br><br>\n";
echo "<form action=template.php method='post'>\n  ";
for ( $counter=1; $counter<=4; $counter++ ) {
template($counter);}
echo "<input type='submit' name='submit' value='Submit'> \n";
echo "</form><br><br>";
?>

</html>
</body>
