<?php

class testfile{

   function testfile($pattern = ''){
      $this->result = array();
      $this->pattern = '/^(testof)\w+(\.php)$/';
      $this->setPattern($pattern);
   }

   function setPattern($pattern = ''){
      if(!empty($pattern)) $this->pattern = $pattern;
   }

   function getList($curpath = ''){
      if(empty($curpath)) $curpath = $_SERVER['DOCUMENT_ROOT'];  
      $dir = dir($curpath);        
      while ($file = $dir->read()){
         if($file != "." && $file != ".."){
            if(is_dir($curpath.'/'.$file)){
               //$check = substr(basename($curpath.'/'.$file),0,1);
               $check = basename($curpath.'/'.$file);
               if($check[0] !== '_') $this->getList($curpath.'/'.$file);
               }else{
                  if(preg_match($this->pattern,strtolower($file)) or preg_match('/^(test\.)\w+(\.php)$/',strtolower($file))){
                     $temp = str_replace('\\','/',$curpath.'/'.$file);
                     $this->result[] = str_replace('//','/',$temp);
                  }
            }
        }
      }
      $dir->close();
      return $this->result;
   }

}



?>
