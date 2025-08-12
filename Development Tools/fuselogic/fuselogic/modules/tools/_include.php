<?php
require_once('class/class.init.php');
$include = &new init();
$include->unlink('./pear');
$include->unlink('./includes');
$include->link('./class');
$include->link('./functions');
$include->link(getcwd().'/class');
$include->link(getcwd().'/functions');

?>