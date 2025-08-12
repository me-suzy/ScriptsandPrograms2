<?php

switch(subModule()) {
  case '_setting':
	      singletonQueue();
	      require_once($_SERVER['DOCUMENT_ROOT'].'/../haltebis_setting.php');
	      break;
	case "_time":  
	      singletonQueue();				
	      include_once getClass('stopwatch');
				$flTime = &new StopWatch(FL_MICROTIME_START); 
				require_once('function.viewarray.php'); //temporary  
        break;		
	case "singleton":  
	      //singletonQueue();				
	      //require_once('function.singleton.php');
				break;	
	case "cookie":  
	      //singletonQueue();				
	      //require_once('class.cookie.0.01.php');
				break;								
	case "cache":
	      //singletonQueue();
				//require_once('time/class.0.0.3.cache.php');							
				//$setting['dataBaseHost'] = MYSQL_HOST;
				//$setting['dataBaseName'] = MYSQL_DATABASE;
				//$setting['dataBaseTable'] = 'cached';
				//$setting['dataBaseUser'] = MYSQL_USER;
				//$setting['dataBasePassword'] = MYSQL_PASSWORD;					
				//$cache = &new cache($setting);						  
				break;	
	case "_globals_on":
	      singletonQueue();
				require_once('mglobalson.php');							
			  break;				
	case "cachetest":
	      //include('time/test.php');
				break;		
	case "cachetest2":
	      //include('time/test.php');
				break;				
	case "cachetest3":	      
	      //Queue(module().'.cachetest','cache1');	
				//Queue(module().'.cachetest2','cache2');			
				//Queue(module().'.cachetest4','cache4');		
				break;				
	case "cachetest4":
	      //require('time/test3.php');
	      break;	
	case "_patTemplate":
	      singletonQueue();			
				error_reporting(E_ALL ^E_NOTICE);	
	      include_once getClass('pattemplate');			
				break;		
	case "validator":
	      //singletonQueue();				
	      //include_once getClass('validator');
				break;														
	case "validator.example":	      
	      //viq(module().'.view.validator.example',LayoutName());
				//viq(module().'.validator');		
				break;			
	case "view.validator.example":
	      //require_once('example.validator.php');		
	      break;	
	case "_md5":
	      singletonQueue();
	      //require_once("class.md5.php");
				include_once getClass('md5');
	      break;	
	case '_autoload':
	      singletonQueue();
	      require_once('./class/function.autoload.php');
	      break;			
	case 'futest':
	      //include('test.url.php');				
				break;
	case 'fuselogic':
	      //include('test.fuselogic.php');				
				break;			
	case 'test1':
	      //include('test1.php');				
				break;
	default:	         
	      noSubModule();
				break;
				

}


?>
