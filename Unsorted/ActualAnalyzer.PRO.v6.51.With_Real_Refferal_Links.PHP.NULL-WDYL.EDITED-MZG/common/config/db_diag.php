<?php

  for($i=11;$i<=19;$i++) $test[$i]='Skip';

  $this->link=@mysql_connect($this->dbhost,$this->dbuser,$this->dbpass);
  if(!$this->link) {$test[11]='Fail';$err->reason('config.php|db_diagnostics|connection with mysql server has failed');return;}
  $test[11]='Ok';
  $result=@mysql_select_db($this->dbase,$this->link);
  if(!$result) {
      $request='CREATE DATABASE '.$this->dbase;
      $result=mysql_query($request,$this->link);
      if(!$result) {$test[12]='Fail';$err->reason('config.php|db_diagnostics|the request \'create database '.$this->dbase.'\' has failed -- '.mysql_error());return;}
      $test[12]='Ok';
      $result=mysql_select_db($this->dbase,$this->link);
      if(!$result) {$test[12]='Fail';$err->reason('config.php|db_diagnostics|the request \'use '.$this->dbase.'\' has failed -- '.mysql_error());return;}
      $test[12]='Ok';
  }
  else {
      $test[12]='Ok';
  }
  // Verify of showing the table aa_test.
  $request='SHOW TABLES LIKE "aa_test"';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[16]='Fail';$err->reason('config.php|db_diagnostics|the request \'show aa_test\' has failed -- '.mysql_error());return;}

  // Drop aa_test table if it exists
  if(mysql_num_rows($result)) {
      $request='DROP TABLE aa_test';
      $resultd=mysql_query($request,$this->link);
      if(!$resultd) {$test[18]='Fail';$err->reason('config.php|db_diagnostics|the request \'drop table aa_test\' has failed -- '.mysql_error());return;}
      $test[18]='Ok';
  }
  mysql_free_result($result);

  // Verify of creating the table aa_test.
  $request='CREATE TABLE aa_test(testid SMALLINT(2) UNSIGNED NOT NULL,testurl VARCHAR(255) NOT NULL, INDEX (testid))';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[13]='Fail';$err->reason('config.php|db_diagnostics|the request \'create table aa_test\' has failed -- '.mysql_error());return;}
  $test[13]='Ok';

  // Verify of locking the table aa_test to WRITE.
  $request='LOCK TABLES aa_test WRITE';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[19]='Fail';$err->reason('config.php|db_diagnostics|blocking of table \'aa_test\' has failed -- '.mysql_error());return;}
  $test[19]='Ok';

  // Verify of inserting into the table aa_test.
  $request='INSERT INTO aa_test (testid,testurl) VALUES (2,"test.html")';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[14]='Fail';$err->reason('config.php|db_diagnostics|the request \'insert into aa_test\' has failed -- '.mysql_error());return;}
  $test[14]='Ok';

  // Verify of updating of the table aa_test.
  $request='UPDATE aa_test SET testid=1 WHERE testid=2';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[15]='Fail';$err->reason('config.php|db_diagnostics|the request \'update aa_groups\' has failed -- '.mysql_error());return;}
  $test[15]='Ok';

  // Verify of selecting from the table aa_test.
  $request='SELECT * FROM aa_test WHERE testid=1';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[16]='Fail';$err->reason('config.php|db_diagnostics|the request \'select from aa_test\' has failed -- '.mysql_error());return;}
  $test[16]='Ok';
  mysql_free_result($result);

  // Verify of deleting from the table aa_test.
  $request='DELETE FROM aa_test WHERE testid=1';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[17]='Fail';$err->reason('config.php|db_diagnostics|the request \'delete from aa_test\' has failed -- '.mysql_error());return;}
  $test[17]='Ok';

  // Verify of unlocking the tables (WRITE).
  $request='UNLOCK TABLES';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|db_diagnostics|unlocking of table \'aa_test\' has failed -- '.mysql_error());return;}

  // Verify of drop the table aa_test.
  $request='DROP TABLE aa_test';
  $result=mysql_query($request,$this->link);
  if(!$result) {$test[18]='Fail';$err->reason('config.php|db_diagnostics|the request \'drop table aa_test\' has failed -- '.mysql_error());return;}
  $test[18]='Ok';


  if($this->link) {
    $result=mysql_close($this->link);
    if(!$result) {$err->reason('config.php|db_diagnostics|disconnect with mysql server has failed');return;}
  }

?>
