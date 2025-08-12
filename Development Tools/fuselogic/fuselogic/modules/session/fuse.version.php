<?php
$dir_reader = &new dir_reader();
$dir_reader->read(dirname(__FILE__));		
		foreach($dir_reader->files as $j => $file){		
		  if(preg_match('/^(version\.).+(\.php)$/i',$file)){			
			  $temp = str_replace('version.','',$file);
				$temp = str_replace('.php','',$temp); 
				echo 'Version: '.$temp;
				break;		
			}
		}    	
?>			
