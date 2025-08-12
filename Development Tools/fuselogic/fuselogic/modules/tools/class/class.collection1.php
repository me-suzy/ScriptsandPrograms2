<?php

class Collection1{
    var $struct;
    function Collection1(&$struct){
        $this->struct = &$struct;
    }
    function &next(){
        $element = each($this->struct);
        if($element){
            return $element['value'];
        }else{
            reset($this->struct);
            return NULL;
        }
    }
}

?>
