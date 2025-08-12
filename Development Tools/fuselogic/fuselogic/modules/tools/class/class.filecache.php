<?php
//smaller / faster is important!!!
if(!class_exists('filecache')){
class fileCache{
    var $directory;
		var $time;
		var $id;
		var $cache;		
		var $time_to_life;
		function fileCache($option = array()){
		    $this->directory = '';
				$this->time = 3600; //in seccond
				$this->id = '';
		    $this->cache = '';
				$this->setOption($option);
		}		
		function setOption($option = array()){
		    if(!empty($option['directory'])){
				    $temp = str_replace('//','/',$option['directory']).'///';						
						$temp = str_replace('////','',$temp);						
				    $this->directory = str_replace('///','',$temp);						
				}						
				$this->time = (!empty($option['time']))?(int)$option['time']:$this->time;
		    $this->id = (!empty($option['id']))?$option['id']:$this->id;
		}		
		function get($id = ''){
		    if(!empty($id)){
				    $this->id = $id;
				    $file = $this->directory.'/'.$id;
				    if(file_exists($file)){				    
		            $this->time_to_life = $this->time + filemtime($file) - time();
						    if($this->time_to_life > 0){
		                return file_get_contents($file);								
						    }		
		        }
				}		    
		}
		function timeToLife(){
		    return $this->time_to_life;
		}
		function save($Cache = ''){		    
				$temp_name = $this->directory.'/'.rand(0,5).$this->id;
		    $fp = fopen($temp_name, "wb");		
        fwrite($fp,$Cache);
		    fclose($fp);
				touch($temp_name,time());
				chmod($temp_name,0777);  				
				$this->_remove_file($this->directory.'/'.$this->id);
				@rename($temp_name,$this->directory.'/'.$this->id);
		}			
		function clean($id = ''){		 		    
				if(!empty($id)){
				    $this->_remove_file($this->directory.'/'.$id);
				}else{
		        $this->_remove_file($this->directory);			
				}    
		}	
		function _remove_file($file = ''){
		    if(file_exists($file)){
            if(is_dir($file)){
                $handle = opendir($file);
								while(false !== ($filename = readdir($handle))){
                    if ($filename != "." && $filename != ".."){
                        $this->_remove_file($file."/".$filename);												
                    }
                }								
                closedir($handle);                
            }else{
                unlink($file);
            }
         }
		}	
} //end class
} //end if
?>
