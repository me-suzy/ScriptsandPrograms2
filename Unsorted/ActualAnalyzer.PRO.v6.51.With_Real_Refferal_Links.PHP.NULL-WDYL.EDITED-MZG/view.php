<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './common/confdb.php';
require './common/auth.php';
require './view/vdb.php';
require './view/vstat.php';

//HTML code accumulator
$pagehtml='';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('view.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database initialisation
db_init();
if($err->flag) {
  $err->reason('view.php||can\'t init database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//config in database
$configdb = & new confdb;
if($err->flag) {
  $err->reason('view.php||constructor of confdb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//authentication
$login = & new auth($rf,'view',_VIEWAREA);
if($err->flag) {
  $err->reason('view.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database
$vdb = & new vdb;
if($err->flag) {
  $err->reason('view.php||constructor of vdb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//view
$vstat = & new vstat($rf);
if($err->flag) {
  $err->reason('view.php||constructor of vstat class has failed');
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
