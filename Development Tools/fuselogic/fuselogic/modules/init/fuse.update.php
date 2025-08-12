<?php
if(isset($_SERVER['HTTP_REFERER'])){

   if('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] != $_SERVER['HTTP_REFERER']){
	    Location($_SERVER['HTTP_REFERER']);			
	 }
	 	 
}else{
   echo '<div align="center">';
   echo '<h2>Updated!</h2>';
	 echo '</div>';
}
?>