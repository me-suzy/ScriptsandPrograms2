<?php



  session_start() ;

import_request_variables("gP", "r_");



if  ($_SESSION["aut"]<> 1){

   echo "You are not allowed to view this page<br>";

   exit();}





 ?>

<html>

<head>

<title>Page Manager</title>

<link rel="stylesheet" type="text/css"

href="style.css" />

</head>

<body bgcolor="#66CCFF">





<?php

  import_request_variables("gP", "r_");

  include 'conection.php';





// save new content

 if (isset($r_submit2)) {

 if ($r_pagename=="" or $r_content=="") {

        echo "Please fill the content area of the page and the page name before presing submit \n"; }

        else {

        $temp=mysql_query("select * from $prefix"."template");

        $newpage=$r_content        ."<?php echo \"<br><br><p style='"."font-size:10px; text-align: center'>Powered by <a href='http://www.ngksoft.com'>Easy CMS</a>\" ?>" ;

        $newpage=str_replace("{NEWS}", "<?php include 'news.php' ?>", $newpage);

        $newpage=str_replace("{GALLERY}", "<?php include 'gallshow.php' ?>", $newpage);

        $newpage=str_replace("{TOP}", "<?php  session_start() ; include 'top.php' ?>", $newpage);

        $newpage=str_replace("{LEFT}", "<?php include 'left.php' ?>", $newpage);

        $newpage=str_replace("{RIGHT}", "<?php include 'right.php' ?>", $newpage);

        $newpage=str_replace("{BOTTOM}", "<?php include 'bottom.php' ?>", $newpage);

        //this is to write the new page

       // while ( $row = mysql_fetch_array($temp) ) {

       // $newpage=str_replace($row['section'],$row['inc'],$newpage);



 // }

  //writing the new page

         $file = fopen( $r_pagename, "w" );

        fwrite( $file, stripslashes($newpage) );

        

        $conquery = mysql_query("SELECT * FROM $prefix"."pages where name='$r_pagename'");

        if (!$conquery) {

        echo("<P>Error performing query: " .

          mysql_error() . "</P>");

        exit();

        }

        $num_rows = mysql_num_rows($conquery);

       //if there is no result then we add a new page

        if ($num_rows == 0){

        $query="insert into $prefix"."pages set name='$r_pagename', content='$r_content'";



                if (mysql_query($query)) {

                echo("<P>The new page have been created</P>");

                } else {

                 echo("<P>Error creating new page users: " .

                mysql_error() . "</P>");

                 }



      }

        else {   //if there is a result we modify it

                $query="update $prefix"."pages set content='$r_content' where name='$r_pagename' ";



                if (mysql_query($query)) {

                echo("<P>The page was modified</P>");

                } else {

                 echo("<P>Error modifiying page: " .

                mysql_error() . "</P>");

                 }

                    } }    }

//printing form

$result = mysql_query("SELECT * FROM $prefix"."pages");

if (!$result) {

  echo("<P>Error performing query: " .

       mysql_error() . "</P>");

  exit();

}

  echo "<h1 style='text-align:center'>Page Manager</h1>";

  echo "Select page to modify ";

  echo "<form action=pagemanager.php method='get'>  ";

  echo "<select name='pages'>  \n";





while ( $row = mysql_fetch_array($result) ) {

    echo("<option>" . $row["name"] . "\n");

  }

     echo "</select> \n";

     echo "<input type='submit' name='submit' value='Submit'> \n";

     echo "</form>";



// load content if submit is pressed

if (isset($r_submit)) {

        $conquery = mysql_query("SELECT * FROM $prefix"."pages where name='$r_pages'");

        if (!$conquery) {

        echo("<P>Error performing query: " .

          mysql_error() . "</P>");

        exit();

        }

        $conrow = mysql_fetch_array($conquery) ;

 }





      //printing second form

     echo "<form action=pagemanager.php method='post'> \n";

     echo"You can make use of this tags to set the different sections of your site: ";

     echo"{TOP}, {LEFT}, {RIGHT}, {BOTTOM}, {NEWS}, and {GALLERY}<BR>";



     if (isset($conrow)) {

     echo "<input type='text' name='pagename' value=$conrow[name]>Page Name<br>";

     echo "<textarea cols=50 rows=20 name='content'  >$conrow[content]</textarea><br>" ;}

     else {

     echo "<input type='text' name='pagename' > Page Name<br>";

     echo "<textarea cols=50 rows=20 name='content'   ></textarea><br> \n" ;}

     echo "<input type='submit' name='submit2' value='Submit'> \n";

     echo "</form>";

     

?>



</html>

</body>

