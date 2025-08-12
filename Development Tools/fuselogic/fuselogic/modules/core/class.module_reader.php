<?php

if (!function_exists('file_put_contents')) {
   function file_put_contents($filename, $data)
   {
       if (($h = @fopen($filename, 'w')) === false) {
           return false;
       }
       if (($bytes = @fwrite($h, $data)) === false) {
           return false;
       }
       fclose($h);
       return $bytes;
   }
}

if(!class_exists('module_reader')){
class module_reader{
    var $text;
		var $file_name;
		
    function module_reader($file_name = 'circuits.php'){
		    $this->text = '';
				$this->set_file_name($file_name);
		}
		function set_file_name($name = 'file.php'){
		    $this->file_name = $name;
		}
		function get_text(){
		    return $this->text;
		}
		function start(){
		    $this->text .= "<?php\n\n";
		}
		function newLine(){
		    $this->text .= "\n";
		}
		function end(){
		    $this->text .= "\n?>";
		}
		function add_array($module = null){
		    if(isset($module)) $this->module = $module;		
		    foreach($this->module as $module_name => $directory_name){
            $this->text .= '$modules[\''.$module_name.'\']=\''.$directory_name.'\';'."\n";
        }
		}
		function add_fuse(){
		    foreach($this->module as $module => $path){
				    foreach($this->fuse[$module] as $subModule => $file){
                $this->text .= '$fuse[\''.$module.'\'][\''.$subModule.'\']=\''.$file.'\';'."\n";
						}
        }
		}
		function update($module){
		    $this->module = $module;
				    $this->start();				
						$this->add_array();		
						$this->end();						
						$this->file_put($this->file_name,$this->get_text());	
		}
		function save($module,$fuse){
		    $this->module = $module;
				$this->fuse = $fuse;
				    $this->start();				
						$this->add_array();		
						$this->newLine();
						$this->add_fuse();		
						$this->end();						
						$this->file_put($this->file_name,$this->get_text());	
		}
		
	  function file_put($file,$string){
		    file_put_contents($file,$string);				
		}
		function file_delete($file){
		    @unlink($file);
		}
}		
}


?>