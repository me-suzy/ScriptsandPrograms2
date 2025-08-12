<?php
SingletonQueue();
if(!@$attributes['layout']){
    Queue('display/main');		
		Queue('display/_css');
		Queue('display/_xhtml');		
		Queue('cache/_end');
		Queue('printer_friendly/_print');				
    Queue('display/_replace');	
		
}

?>