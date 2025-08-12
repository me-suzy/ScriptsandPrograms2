<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               include/security.inc.php         #
# File purpose            For some secure reasons          #
# File created by         AzDG <support@azdg.com>          #
############################################################
if(C_SHOW_LANG == '0') {
   $l = 'default';
   include_once C_PATH.'/languages/'.$l.'/'.$l.'.php';
   include_once C_PATH.'/languages/'.$l.'/'.$l.'_.php';
}
elseif(C_SHOW_LANG == '1') {
    $l = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    if(!file_exists(C_PATH.'/languages/'.$l.'/'.$l.'.php') || (empty($l))) $l='default';
    include_once C_PATH.'/languages/'.$l.'/'.$l.'.php';
    include_once C_PATH.'/languages/'.$l.'/'.$l.'_.php';
}
else {
if(isset($_GET['l']) || isset($_POST['l'])) $l = isset($_GET['l']) ? $_GET['l'] : $_POST['l'];
else $l='';

   if ($l == '') {
       $l = C_MULTLANG_DEF ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'default';
       if(!file_exists(C_PATH.'/languages/'.$l.'/'.$l.'.php') || (empty($l))) $l='default';
       include_once C_PATH.'/languages/'.$l.'/'.$l.'.php';
       include_once C_PATH.'/languages/'.$l.'/'.$l.'_.php';
       $l='';
   }
   else {
      if (isset($l) && !file_exists(C_PATH.'/languages/'.$l.'/'.$l.'.php') && $l != "") {
           include_once C_PATH.'/languages/default/default.php';
           include_once C_PATH.'/languages/default/default_.php';
      }
      else {
           include_once C_PATH.'/languages/'.$l.'/'.$l.'.php';
           include_once C_PATH.'/languages/'.$l.'/'.$l.'_.php';
      }
   }
}   
?>
