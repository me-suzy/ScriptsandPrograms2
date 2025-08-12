<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './common/confdb.php';
require './common/auth.php';
require './admin/adb.php';
require './admin/aset.php';

//HTML code accumulator
$pagehtml='';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('admin.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database initialisation
db_init();
if($err->flag) {
  $err->reason('admin.php||can\'t init database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//config in database
$configdb = & new confdb;
if($err->flag) {
  $err->reason('admin.php||constructor of confdb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//authentication
$login = & new auth($rf,'admin');
if($err->flag) {
  $err->reason('admin.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database
$adb = & new adb;
if($err->flag) {
  $err->reason('admin.php||constructor of adb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//settings
$aset = & new aset($rf);
if($err->flag) {
  $err->reason('admin.php||constructor of aset class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database closing
db_close();
if($err->flag) {
  $err->reason('admin.php||can\'t close connection with database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//output HTML page
out();

?>
