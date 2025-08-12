<?php

singletonQueue();

if(isset($_SESSION['afterseven'])){
   $afterseven = $_SESSION['afterseven'];
}else{   
	 
	 $afterseven = &new afterseven();
	 $_SESSION['afterseven'] = $afterseven;
}

if(!function_exists('sid')){
   function sid(){
	    return '&PHPSESSID='.session_id().'&';
	 }
}

function presistence($instance_name,$object_name){

}

?>