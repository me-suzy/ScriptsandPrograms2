<?php

class Q{
    var $fuse;
		var $layoutName;
		var $parentFuse;
		var $FILE;
		var $LINE;
		var $module;
		var $subModule;
		
		function Q($fuse = '',$layoutName = 'noname',$ParentFuse = '',$FILE = '',$LINE=''){
		    $this->fuse = $fuse;
				$this->layoutName = $layoutName;
				$this->parentFuse = $ParentFuse;
				$this->module_subModule();
				$this->File_Line();
		}
		
		function File_Line(){
		    $array = debug_backtrace();
				$this->FILE = $array[1]['file'];
				$this->LINE = $array[1]['line'];
		}
		
		function module_subModule(){
		    $temp = explode('/',$this->fuse);
				$this->module = $temp[0];
				if(isset($temp[1])){
				    $this->subModule = $temp[1];
				}
		}

}

?>