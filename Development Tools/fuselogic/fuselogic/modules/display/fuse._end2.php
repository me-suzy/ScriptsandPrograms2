<?php
SingletonQueue();
FirstQueueIf('display/_replace');
FirstQueueIf('printer_friendly/_print');		
FirstQueueIf('browser/_css');
FirstQueueIf('display/xhtml');
?>