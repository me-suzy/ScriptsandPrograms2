<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                         db.php file                          */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/
if(eregi("db.php",$HTTP_SERVER_VARS['PHP_SELF'])) {
   echo "You can't access this file directly";
   exit();
}

class db {
          var $dbhost, $dbuser, $dbpass, $dbname, $query, $sql, $conn, $num, $fetch;
 
          function db($dbhost, $dbuser, $dbpass, $dbname) {
                      $this->connect($dbhost, $dbuser, $dbpass, $dbname);
          }

          function connect($dbhost, $dbuser, $dbpass, $dbname = '', $persistant = 0) {
                           $this->dbhost = $dbhost;
                           $this->dbuser = $dbuser;
                           $this->dbpass = $dbpass;
                           if($persistant) {
                                   $this->conn = mysql_pconnect($this->dbhost, $this->dbuser, $this->dbpass);
                           } else {
                                   $this->conn = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
                           }
                           if(!$this->conn) {
                              $msg = mysql_errno() . "\t" . mysql_error();
                              return false;
                           } else {
                              $this->setdb($dbname);
                           }
          }
          function setdb($dbname) {
                            $this->dbname = $dbname;
                            if(mysql_select_db($this->dbname,$this->conn)) {
                               return true;
                            } else {
                               $msg = mysql_errno() . "\t" . mysql_error() . "\t" . $this->db;
                            }
          }
          function query($sql) {
                        $this->sql = $sql;
                        $this->query = mysql_query($this->sql, $this->conn);
                        if(!$this->query) {
                           $msg = mysql_errno() . "\t" . mysql_error() . "\t" . $this->sql;
                           return false;
                        } else {
                           return true;
                        }
          }
          function fetch() {
                        if($this->query) {
                           $this->fetch = mysql_fetch_array($this->query);
                           return $this->fetch;
                        } else {
                           return 0;
                        }
          }
          function result() {
                        if($this->query) {
                           $this->result = mysql_result($this->query, 0);
                           return $this->result;
                        } else {
                          return 0;
                        }
          }
          function num() {
                    if($this->query) {
                           $this->num = mysql_num_rows($this->query);
                           return $this->num;
                    } else {
                           return 0;
                    }
          }
          function close() {
               if(mysql_close($this->conn)) {
                  return true;
               } else {
                  $msg = mysql_errno() . "\t" . mysql_error() . "\t" . $this->sql;
                  return false;
              }
          }
}
?>