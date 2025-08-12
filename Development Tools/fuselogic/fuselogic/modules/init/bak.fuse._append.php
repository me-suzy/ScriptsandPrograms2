<?php
SingletonQueue();
QueueIf('display/xhtml');
QueueIf('browser/_css');
QueueIf('cache/_end');
QueueIf('printer_friendly/_print');			
QueueIf('display/_replace');

?>