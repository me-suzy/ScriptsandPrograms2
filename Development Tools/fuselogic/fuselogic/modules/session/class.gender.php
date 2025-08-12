<?php
class gender{
   
	 function check(){
	    $this->gender = ($this->gender == 'Male')?'Female':'Male';
	    return $this->gender;
	 }
	 
}

?>