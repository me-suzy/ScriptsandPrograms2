<?php

class Collection2{
    var $collection;
    var $element;  // Single element stored here
    var $first = TRUE;
    function Collection2(&$collection) {
        $this->collection = &$collection;
    }
    function reset(){
        $this->first = TRUE;
    }
    function next(){
        if($this->first){				    
            $record = &reset($this->collection);
            $this->first = FALSE;
        }else{
            $record = next($this->collection);
        }
        if(is_array($record)){
            $this->record = $record;
            return TRUE;
        }else{
            $this->record = NULL;
            return FALSE;
        }
    }
    function get(){
        return $this->record;
    }
}

?>