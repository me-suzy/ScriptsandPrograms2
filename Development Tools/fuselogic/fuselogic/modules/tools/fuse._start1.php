<?php

singletonQueue();

require_once('class/class.include_path.php');
$include_path = &new include_path();

$path[] = getcwd().'/class';
$path[] = getcwd().'/functions';
$path[] = './class';
$path[] = './functions';
$path[] = './scripts';

$include_path->add($path);

//sequence is important
require_once('function.__autoload.php');

FirstQueueIf(module().'/no_spam');
FirstQueueIf(module().'/_time');



?>
