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


 $result = mysql_query("SELECT distinct gallery FROM $prefix"."comments");
if (!$result) {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}
  echo "<h1 style='text-align:center'>Comments Manager</h1>";
  echo "Select gallery comments to manage ";
  echo "<br>";
  echo "<form action=commentmanager.php method='get'>  ";
  echo "Gallery #<select name='gallery'>  \n";


while ( $row = mysql_fetch_array($result) ) {
    echo("<option>" . $row["gallery"] . "\n");
  }
     echo "</select> \n";
     echo "<input type='submit' name='submit' value='Submit'> \n";
     echo "</form>";

//if delete is pressed
if (isset($r_delete)){

$del=mysql_query("delete  from $prefix"."comments where ID='$r_delete'");
if (!$del)  {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}   }

//if submit is pressed
if (isset($r_submit)){

$com=mysql_query("select * from $prefix"."comments where gallery='$r_gallery'");
if (!$com)  {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}
while ( $rowcom = mysql_fetch_array($com) ) {
echo ("Posted by:".$rowcom["username"] ."<br> \n") ;
    echo( $rowcom["description"] ."<br> \n");
echo ("<a href='commentmanager.php?delete=".$rowcom['ID']."'>Delete comment</a>   ");
echo (" <a href='commentmanager.php?edit=".$rowcom['ID']."'>Edit comment</a><br><br>");
  }

 }

echo "<form action=commentmanager.php method='get'>  ";

//if edit is pressed
if (isset($r_edit)){

$edi=mysql_query("select description from $prefix"."comments where ID='$r_edit'");
if (!$edi)  {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}
  $edirow = mysql_fetch_array($edi) ;
echo "<textarea cols=50 rows=4 name='comment'  >$edirow[description]</textarea><br>" ;
echo "<input type='hidden' name='comnum' value='$r_edit'>";}
else {
echo "<textarea cols=50 rows=4 name='comment'  ></textarea><br>" ;}

echo "<input type='submit' name='submit2' value='Submit'> \n";
echo "</form>";

//if submit2 is pressed
 if (isset($r_submit2)) {
 if ($r_comment=="" or $r_comnum=="") {}
 else{
 $com2=mysql_query("update $prefix"."comments set description='$r_comment' where ID='$r_comnum' ");
if (!$com2)  {
  echo("<P>Error performing query: " .
       mysql_error() . "</P>");
  exit();
}
                    }   }





?>
</html>
</body>

