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

<title>News Manager</title>

<link rel="stylesheet" type="text/css"

href="style.css" />

</head>

<body bgcolor="#66CCFF">



<?php



import_request_variables("gP", "r_");



//if submit3 is presed

if (isset($r_submit3) and isset($r_delete)){

$deleting=mysql_query("DELETE FROM $prefix"."news where title='$_SESSION[deltitle]'");



}



//if submit is presed

if (isset($r_submit2)){

if ($r_title=="" or $r_description=="") {

        echo "Please fill the content area of the page and the news title before presing submit \n"; }

        else {

$news=mysql_query("SELECT * FROM $prefix"."news where title='$r_title'");



  if (!$news) {

        echo("<P>Error performing query: " .

          mysql_error() . "</P>");

        exit();

        }

        $num_rows = mysql_num_rows($news);

       //if there is no result then we add a  news

        if ($num_rows == 0){

        $dt=date("Y-m-d");

        $query="insert into $prefix"."news set title='$r_title', description='$r_description', date='$dt' ";



                if (mysql_query($query)) {

                echo("<P>Your news have been added to the database</P>");

                } else {

                 echo("<P>Error creating new page users: " .

                mysql_error() . "</P>");

                 }



      }

        else {   //if there is a result we modify it

                $query="update $prefix"."news set description='$r_description' where title='$r_title'";



                if (mysql_query($query)) {

                echo("<P>The news was modified</P>");

                } else {

                 echo("<P>Error modifiying page: " .

                mysql_error() . "</P>");

                 }

                    }}}



//printing selection box

$result = mysql_query("SELECT title FROM $prefix"."news");

if (!$result) {

  echo("<P>Error performing query: " .

       mysql_error() . "</P>");

  exit();

}

  echo "<h1 style='text-align:center'>News Manager</h1>";

  echo "Select news to modify ";

  echo "<form action=newspost.php method='post'>  ";

  echo "<select name='news'>  \n";





while ( $row = mysql_fetch_array($result) ) {

    echo("<option>" . $row["title"] . "\n");

  }

     echo "</select> \n";

     echo "<input type='submit' name='submit1' value='Submit'> \n";

     echo "</form>";



// load content if submit is pressed

if (isset($r_submit1)) {

        $conquery = mysql_query("SELECT * FROM $prefix"."news where title='$r_news'");

        if (!$conquery) {

        echo("<P>Error performing query: " .

          mysql_error() . "</P>");

        exit();

        }

        $conrow = mysql_fetch_array($conquery) ;

 }







//this is the second form

echo "Add your news below ";

echo "<form action=newspost.php method='post'>  ";

echo "Check box to confirm<input type='checkbox' name='delete' >";

echo "<input type='submit' name='submit3' value='Delete this News'> \n";

     echo "</form>";

echo "<form action=newspost.php method='post'>  ";

 if (isset($conrow)) {

    $_SESSION["deltitle"] = $conrow['title']  ;

     echo "<textarea cols=50 rows=1 name='title' >$conrow[title]</textarea>News Title<br>";

     echo "<textarea cols=50 rows=20 name='description'  >$conrow[description]</textarea><br>" ;}

     else {

	$_SESSION["deltitle"] = "g"  ; 

     echo "<textarea cols=50 rows=1 name='title' > </textarea>News Title<br>";

     echo "<textarea cols=50 rows=20 name='description'   ></textarea><br> \n" ;}

     echo "<input type='submit' name='submit2' value='Submit'> \n";

     echo "</form>";



?>





</html>

</body>

