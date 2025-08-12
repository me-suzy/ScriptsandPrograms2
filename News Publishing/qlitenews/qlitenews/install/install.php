<?php

  include("../admin/config.php");
  $db = mysql_connect($dbhost,$dbuser,$dbpass); 
  mysql_select_db($dbname) or die("Cannot connect to database");

  $query = 'CREATE TABLE qlitenews ( '.
         'id int(11) NOT NULL auto_increment, '.
         'author VARCHAR(50), '.
         'title VARCHAR(100), '.
         'news TEXT, '.
         'date CHAR(20), '.
         'ip CHAR(15), '.
         'PRIMARY KEY(id) )';

  $result = mysql_query($query);
  echo "qlitenews table created successfully...<br/>";

  $query = 'CREATE TABLE qlitenews_users ( '.
         'id int(11) NOT NULL auto_increment, '.
         'user VARCHAR(50), '.
         'password VARCHAR(50), '.
         'PRIMARY KEY(id) )';

  $result = mysql_query($query);
  echo "qlitenews user table table created successfully...<br/>";

  $pass = md5(admin);
  mysql_query("INSERT INTO qlitenews_users(user,password) VALUES('admin','$pass')");
  echo "default username and password created successfully...";

  mysql_close($db);

?>