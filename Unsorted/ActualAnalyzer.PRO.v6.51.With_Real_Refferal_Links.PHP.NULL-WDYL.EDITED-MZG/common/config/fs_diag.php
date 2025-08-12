<?php

  for($i=1;$i<=5;$i++) $test[$i]='Skip';

  $file=fopen($this->rf.'test.php','w');
  if(!$file) {$test[1]='Fail';$err->reason('config.php|fs_diagnostics|creating of test file has failed');return;}
  else $test[1]='Ok';
  flock($file,LOCK_EX);
  if(!fwrite($file,'test')) {$test[2]='Fail';$err->reason('config.php|fs_diagnostics|can\'t write into test file');return;}
  else $test[2]='Ok';
  flock($file,LOCK_UN);
  fclose($file);

  $file=fopen($this->rf.'test.php','r');
  if(!$file) {$test[3]='Fail';$err->reason('config.php|fs_diagnostics|can\'t open test file');return;}
  else $test[3]='Ok';
  flock($file,LOCK_EX);
  $str=fgets($file,10);
  if(strcmp($str,'test')) {$test[4]='Fail';$err->reason('config.php|fs_diagnostics|reading from test file has failed');return;}
  else $test[4]='Ok';
  flock($file,LOCK_UN);
  fclose($file);

  if(!unlink($this->rf.'test.php')) {$test[5]='Fail';$err->reason('config.php|fs_diagnostics|deleting of test file has failed');return;}
  else $test[5]='Ok';

?>
