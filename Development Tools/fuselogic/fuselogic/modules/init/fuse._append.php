<?php

SingletonQueue();
$FLLayout->setLayout('user_layout',$FLLayout->getLayout('noname'));	
echo $FLLayout->getLayout('user_layout');

//auto load sub module with name '_start'
foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/_end3',$module_name.'_end3');				
}

//auto load sub module with name '_start'
foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/_end2',$module_name.'_end2');				
}

//auto load sub module with name '_autoload'
foreach($FuseLogic->modules as $module_name => $directory_name){   
  QueueIf($module_name.'/_end1',$module_name.'_end1');				
}

?>