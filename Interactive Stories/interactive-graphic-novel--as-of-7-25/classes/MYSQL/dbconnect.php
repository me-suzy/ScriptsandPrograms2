<?php
   unset($dbfailed);

   if (mysql_connect('localhost', 'tsukasa', '', false, MYSQL_CLIENT_COMPRESS)) {
      mysql_select_db('SIGN', $link)or $dbfailed=mysql_error();
      }
   else {
      $dbfailed = "Could not connect to database";
      }

   if (isset($dbfailed)) {
      echo $dbfailed;
      }
?>