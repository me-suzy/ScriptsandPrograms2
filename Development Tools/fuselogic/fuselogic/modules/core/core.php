<?php

define('FL_OB_GZHANDLER',True);
define("FL_MICROTIME_START",microtime());

require_once(dirname(__FILE__).'/class.env.php');
$FL_ENV = &new env();

require_once($FL_ENV->core_path.'/'.'class.dir_reader.php');
require_once($FL_ENV->core_path.'/'.'class.fuselogic.php');
require_once($FL_ENV->core_path.'/'.'class.queue.php');
require_once($FL_ENV->core_path.'/'.'class.layout.php');

$FuseLogic = &new FuseLogic($FL_ENV);
$_URL = $FL_ENV->uri;
$URI = $FL_ENV->uri;

$FLQueue   = &new FLQueue();
$FLQueue->max_number_of_queue = 35;   

$FLLayout  = &new FLLayout();

require_once($FL_ENV->core_path.'/'.'API.php');
require_once($FL_ENV->core_path.'/'.'auto_detect_modules.php');

$FL_one_time = 0;
for($FL_h=1;$FL_h<=3;$FL_h++){

    switch($FL_h){
        case 1:	          
		$FLQueue->Queue('init/_prepend'); 
		break;
        case 2:	                 			
		$FLQueue->Queue($FL_ENV->user_fuse,'user_layout');
		break;											
        case 3:            
		$FLQueue->Queue('init/_append');
		$FL_one_time = 1;
		break;    
    }
		
   while($FLQueue->next()){ 		
				
      //<RUN FUSEACTION>				
      $FuseLogic->initFuse($FLQueue->activeQueue);		
      $FLQueue->log_start();
      ob_start();				
				 
      if($FuseLogic->isModuleExists()){											 
         chdir($FuseLogic->getModulePath());						 
	 if($FL_one_time > 0){
	    $FL_one_time = 0;
	    echo $FLLayout->getLayout('noname');										
         }
				
	if(isset($FuseLogic->fuse[$FuseLogic->module][$FuseLogic->subModule])){										
	   require $FuseLogic->fuse($FuseLogic->module.'/'.$FuseLogic->subModule);								
	}elseif(isset($FuseLogic->fuse[$FuseLogic->module]['default'])){
	   require $FuseLogic->fuse($FuseLogic->module.'/default');				 		
	}else{		                
	   require($FL_ENV->core_path.'/'.'no_sub_module.php');	
	}			    	
				
      }else{	           
	  require($FL_ENV->core_path.'/'.'no_module.php');											 
      }		
										 				 
      $FLLayout->setLayout('noname',ob_end());						
      $FLLayout->setLayout($FuseLogic->LayoutName,$FLLayout->getLayout('noname'));			
      $FLQueue->log_end();		 			 		
      //</RUN FUSEACTION>				    
   } 
		
} 

//SEND OUT PUT TO CLIENT
if(FL_OB_GZHANDLER){
    ob_start('ob_gzhandler');
        print trim($FLLayout->getLayout('noname'));
    ob_flush();
}else{
    print trim($FLLayout->getLayout('noname'));
}

//GO BACK TO CALLER DIRECTORY
chdir($FL_ENV->door_path);

?>