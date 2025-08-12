<?php
if(!class_exists('dir_reader')){
class dir_reader{
    var $directory;
		var $files;
    function module_loader(){
		    
		}
		
		function read_directory($directory = ''){
		    $this->directory = array();
				$this->files = array();
		    if(!empty($directory)){				    
				    $d = dir($directory);
            while(false !== ($entry = $d->read())){
						    if($entry !== '.' and $entry !== '..'){
                    if(is_dir($directory.'/'.$entry)){
								        $this->directory[] = $entry;
								    }else $this->files[] = $entry;								    
								}
            }
            $d->close();
				}
		}
		
		function get_directory(){
		    return $this->directory;
		}
		
		function get_files(){
		    return $this->files;
		}

}
}


?>