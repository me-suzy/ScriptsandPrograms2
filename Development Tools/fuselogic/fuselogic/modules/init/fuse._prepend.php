<?php
SingletonQueue();

//auto load sub module with name '_start1'
foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/_start1');				
}

//auto load sub module with name '_start2'
foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/_start2');				
}

QueueIf('session/session');

//auto load sub module with name '_start3'
foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/_start3');				
}

?>