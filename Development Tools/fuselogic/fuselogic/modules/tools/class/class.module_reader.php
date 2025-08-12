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
		    $this->text .= "<?php\n";
		}
		function end(){
		    $this->text .= "\n?>";
		}
		function add_array($fl_module){
		    foreach($fl_module as $module_name => $directory_name){
            $this->text .= '$fl_module[\''.$module_name.'\']=\''.$directory_name.'\';'."\n";
        }
		}
		function update($array){
				    $this->start();				
						$this->add_array($array);		
						$this->end();
						//$this->file_delete($this->file_name);
						//$this->file_put('next_'.$this->file_name,$this->get_text());
						//rename('next_'.$this->file_name,$this->file_name);
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