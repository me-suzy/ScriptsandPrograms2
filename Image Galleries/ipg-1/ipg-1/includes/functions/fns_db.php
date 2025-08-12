<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/


function db_connect() {
  if(!$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS)) {;
    if(DB_DEBUG){
      print "Cannot connect to DB_HOST as DB_USER<br>";
      print mysql_error();
    } 
    else {
       print "<h2>Database error encountered</h2>";
    }
    if(DB_DIE_ON_FAIL) {
      print "<p>This script cannot continue, terminating...";
      exit();
    }
  }

  if(!mysql_select_db(DB_NAME)) {
    if(DB_DEBUG) {
      print "Cannot select database DB_NAME<br>";
      print mysql_error();
    }
    else {
      print "<h2>Database error encountered</h2>";
    }
    if(DB_DIE_ON_FAIL) {
      print "<p>This script cannot continue, terminating...";
      exit();
    }
  }
  return $dbh;
}//end function db_connect()

function db_query($query) {
  if(! $qid = mysql_query($query)) {
    if(DB_DEBUG) {
     print "Cannot do query ," .  htmlspecialchars($query) . "<br>";
     print mysql_error();
    }
/*
    else {
      print "<h2>Database error encountered</h2>";
    }
*/
    if(DB_DIE_ON_FAIL) {
      print "<p>This script cannot continue, terminating...";
      exit();
    }
  }
  return $qid;
}//end function db_query()

function db_fetch_array($qid) {
  return @mysql_fetch_array($qid);
}//end function db_featch_array()

function db_fetch_assoc($qid) {
  return @mysql_fetch_assoc($qid);
}//end function

function db_num_rows($result) {
  return @mysql_num_rows($result);
}//end function db_num_rows()

function db_affected_rows() {
  return @mysql_affected_rows();
}//end function db_affected_rows()

function db_clean_string($str) {
  return trim(addslashes($str));
}
?>
