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

//this is the page to make galleries

 include 'conection.php';

   import_request_variables("gP", "r_");

   $numoffile = 2;

  $j=0    ;

   

 if ( isset( $r_submit ) )   {





        $path=pathinfo($_SERVER['PATH_INFO']);

        $newfile = "./gallery/".$_FILES['myfiles']['name']['first'];

        $newfile2 = "./gallery/".$_FILES['myfiles']['name']['second'];

        $newfileb = "gallery/".$_FILES['myfiles']['name']['first'];

        $newfile2b = "gallery/".$_FILES['myfiles']['name']['second'];



        move_uploaded_file($_FILES['myfiles']['tmp_name']['first'], $newfile);

        move_uploaded_file($_FILES['myfiles']['tmp_name']['second'], $newfile2);

       $newgallery=mysql_query("insert into $prefix"."galleries set description='$r_description', picture='$newfileb', thumb='$newfile2b', title='$r_title' ");







  echo "Gallery added<br>";

 }

 //this is to delete a gallery if the delete button is pressed

 if ( isset( $r_submit2 ) )   {

 $deleting=mysql_query("DELETE FROM $prefix"."galleries where title='$r_gallery'");

echo "Gallery deleted";

}

 

 

    echo "<h1 style='text-align:center'>Gallery Manager</h1>";

echo "<h3>Add New Gallery</h3>"	;

 echo "<form enctype='multipart/form-data' action=newgallery.php method='post'> \n";

 echo "<input type='text' name='title' >Title<br>\n";

 echo "<input type='text' name='description' >Description<br>\n";



echo "<input type='file' name='myfiles[first]'>Picture<br> \n"   ;

echo "<input type='file' name='myfiles[second]'>Thumb<br> \n"   ;

echo "<input type='submit' name='submit' value='Submit'> \n";

echo "</form>";





echo "<br>";

echo "<br>";



//this bellow is the section to delete a gallery

echo "<h3>Delete a Gallery</h3>";

$com=mysql_query("select title from $prefix"."galleries ");

if (!$com)  {

  echo("<P>Error performing query: " .

       mysql_error() . "</P>");

  exit();

}

echo "<form action=newgallery.php method='post'>  ";

  echo "Gallery Title<select name='gallery'>  \n";





while ( $row = mysql_fetch_array($com) ) {

    echo("<option>" . $row["title"] . "\n");

  }

     echo "</select> \n";

     echo "<input type='submit' name='submit2' value='Delete'> \n";

     echo "</form>";



echo "<br>";

echo "<br>";

?>

</html>

</body>

