<?php

/*
+-------------------------------------------------------------+
|   PHP version 4                                             |
+-------------------------------------------------------------+
|   Version : 0.0.7                                           |
+-------------------------------------------------------------+
|   Copyright (c) 2002 - 2003 Eko Budi Setiyo                 |
+-------------------------------------------------------------+ 
| License : BSD License                                       |
| http:www.haltebis.com/index.php/wakka/main/license                    |
+-------------------------------------------------------------|
| Authors : Setiyo, Eko Budi <ekobudi@haltebis.com>           |
+-------------------------------------------------------------+
*/

if(!defined("FL_LAYOUT_CLASS")){

    define("FL_LAYOUT_CLASS",1);

    class FLLayout{
        var $Layout = array();	 
	 
	      function FLLayout(){
	          $this->setLayout('noname','');
	      }
	 
	      function setLayout($layoutName='noname',$Layout = ''){
	          $this->Layout[$layoutName] = $Layout;
	      }
	 
	      function getLayout($layoutName = 'noname'){
	          if(isset($this->Layout[$layoutName])){
			          return $this->Layout[$layoutName];
			      }else return '';	 
	      }        
    }
//end of class Layout
}
?>