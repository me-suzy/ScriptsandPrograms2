<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './view/gdb.php';
require './view/vvis.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('pict.php||constructor of config class has failed');
  $err->log_out();
  exit;
}

//database
$gdb = & new gdb;
if($err->flag) {
  $err->reason('pict.php||constructor of gdb class has failed');
  $err->log_out();
  exit;
}

//visualisation
$vis = & new vvis($rf);
if($err->flag) {
  $err->reason('pict.php||constructor of vvis class has failed');
  $err->log_out();
  exit;
}

//get ID of graph's data
if(isset($GLOBALS['gid'])) $gid=$GLOBALS['gid'];
elseif(isset($HTTP_GET_VARS['gid'])) $gid=$HTTP_GET_VARS['gid'];
else {
  $err->reason('pict.php||can\'t get ID of graph\'s data');
  $err->log_out();
  exit;
}

//get ID of graph's data
if(isset($GLOBALS['act'])) $act=$GLOBALS['act'];
elseif(isset($HTTP_GET_VARS['act'])) $act=$HTTP_GET_VARS['act'];
else {
  $err->reason('pict.php||can\'t get action for graph\'s data');
  $err->log_out();
  exit;
}

//get statistics parameter
if(isset($GLOBALS['stat'])) $stat=$GLOBALS['stat'];
elseif(isset($HTTP_GET_VARS['stat'])) $stat=$HTTP_GET_VARS['stat'];
else {
  $err->reason('pict.php||can\'t get statistics parameter for graph\'s data');
  $err->log_out();
  exit;
}

//get graph type
if(isset($GLOBALS['type'])) $type=$GLOBALS['type'];
elseif(isset($HTTP_GET_VARS['type'])) $type=$HTTP_GET_VARS['type'];
else {
  $err->reason('pict.php||can\'t get type of graph image');
  $err->log_out();
  exit;
}

db_init();
if($err->flag) {
  $err->reason('pict.php||initialization of database has failed');
  $err->log_out();
  exit;
}

//image
$vis->img($gid,$stat,$type,$act);
if($err->flag) {
  $err->reason('pict.php||function \'show\' has failed');
  $err->log_out();
  exit;
}

db_close();
if($err->flag) {
  $err->reason('pict.php||closing of connection with database has failed');
  $err->log_out();
  exit;
}

?>
