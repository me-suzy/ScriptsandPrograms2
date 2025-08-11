<html>
<head>
<title>Database Setup</title>
<link rel="stylesheet" type="text/css"
href="style.css" />
</head>
<body bgcolor="#66CCFF">


<?php
include'settings.php';
//database conection
$conection = @mysql_connect($hostname, $username, $password);
$er=0;
if (!$conection) {
  echo( "<P>Unable to connect to the " .
        "database server at this time.</P>" );
  exit();
}

//if ($create="on"){

//if (@mysql_query("create database $databasename")){
//    mysql_select_db($databasename);
//  echo ("<p>The database have been created<p>");}
//  else {
// echo ("<p>Error creating the database: ".
// mysql_error(). "</p>");
//  exit();
//  }
// }
//else {
if (! @mysql_select_db($databasename) ) {
  echo( "<P>Unable to locate the '$databasename' " .
        "database at this time.</P>" );
  exit();
}

// Creating tables
      $sql = "create table $prefix" . "galleries (" .
             "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
             "title text, ".
             "picture text, ".
             "thumb text, ".
             "description text)";

      if (mysql_query($sql)) {
        echo("<P>The table galleries have been created</P>");
      } else {
        echo("<P>Error creating the table galleries: " .
             mysql_error() . "</P>");
              $er=1 ;
      }

     $sql = "create table $prefix" ."comments (" .
             "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
             "description text, " .
             "username text, " .
             "gallery text)";

      if (mysql_query($sql)) {
        echo("<P>The table comments have been created</P>");
      } else {
        echo("<P>Error creating the table comments: " .
             mysql_error() . "</P>");
              $er=1 ;
      }


           $sql = "create table $prefix" ."users (" .
             "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
             "username text, " .
             "type text, " .
             "password text, ".
             "email VARCHAR(100))";

      if (mysql_query($sql)) {
        echo("<P>The table users have been created</P>");
      } else {
        echo("<P>Error creating the table users: " .
             mysql_error() . "</P>");
              $er=1 ;
      }
      
      
           $sql = "create table $prefix" ."news (" .
             "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
             "description text, title text, date date)";

      if (mysql_query($sql)) {
        echo("<P>The table news have been created</P>");
      } else {
        echo("<P>Error creating the table news: " .
             mysql_error() . "</P>");
              $er=1 ;
      }
      
               $sql = "create table $prefix" ."pages (" .
             "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
             "name text, content text)";

      if (mysql_query($sql)) {
        echo("<P>The table pages have been created</P>");
      } else {
        echo("<P>Error creating the table pages: " .
             mysql_error() . "</P>");
              $er=1 ;
      }
      
       $sql = "create table $prefix" ."template (" .
             "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
             "section text, content text, inc text)";

      if (mysql_query($sql)) {
        echo("<P>The table template have been created</P>");
      } else {
        echo("<P>Error creating the table template: " .
             mysql_error() . "</P>");
         $er=1 ;
      }
      
      //inserting template data

      $sql="insert into $prefix"."template set section='{TOP}'";

      mysql_query($sql);

      
      $sql="insert into $prefix"."template set section='{LEFT}'";
       mysql_query($sql);
       $sql="insert into $prefix"."template set section='{RIGTH}'" ;
       mysql_query($sql);
       $sql="insert into $prefix"."template set section='{BOTTOM}'" ;
       mysql_query($sql);
      
      //inserting admin username and password
       $sql="insert into $prefix"."users set username='$admin', password='$adpass'" ;
       mysql_query($sql);
      
if  ($er<>1){
echo "<p> You can go now to your <a href='adminlog.php'>Site Administration</a></p>";}
       
      
?>

</body>
 </html>

