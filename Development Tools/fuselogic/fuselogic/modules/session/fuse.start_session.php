<?php
SingletonQueue();

foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/before_start_session');				
}

QueueIf(module().'/session');

foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/after_start_session');				
}
?>			
