<?php

class state{
    var $state;
		var $number_of_state;
    function state($setting = array()){
		    $this->state = 0;
				$this->number_of_state = (@$setting['number_of_state']>=2)?$setting['number_of_state']:2;
				$this->number_of_state = (int)$this->number_of_state - 1;
		}
		function changeState(){
		    if($this->state < $this->number_of_state){
				    $this->state++;
				}else{
				    $this->state = 0;
				}
				return $this->getState();
		}
		function getState(){
		    return $this->state;
		}

}

?>