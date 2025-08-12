<?php
class Gsearch{
   Function findgif(){
   $file_num = 1;
   $file = file_exists($file_num.'.gif');

   while ($file){
      $file_num = $file_num + 1;  
      $file = file_exists($file_num.'.gif');
      }

   $file_num = $file_num - 1;  
   return $file_num;
   }    
}
?>