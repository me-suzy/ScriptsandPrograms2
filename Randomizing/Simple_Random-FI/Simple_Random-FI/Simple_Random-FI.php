<?php

/* ***************************************************************
* Simple Random Frase includer
* Author: Javier Valderrama
*
* Developed for ADW Group - www.adwgroup.it
* e-mail: javier@adwgroup.it
* 
*  Files extensions supported txt|html|htm|php| and adding extra hand made code you can include files link images or somenthing else....LOL
*	Please don't remove this lines, I think it's a very low price and a way to say TNX if it's usefull for you ;)
*************************************************************** */

$txt_array = Array();
$my_dir = "myDir/";   //Replace it with your frase's directory (dont'n delete the slash)
	if ($dir = @opendir("$my_dir")) {
		
		  while (($file = readdir($dir)) !== false) { 
			   if ($file != "." && $file != ".."  && !is_dir($my_dir.$file)) 
					   {
					   /* echo $my_dir.$file."<br>";  DEBUG show file list */
					   $txt_array[] = $file;
					   
					   } 
		  }  
	  closedir($dir);
	}

$random_number=rand(0, count($txt_array)-1); 
$random_txt=$txt_array[$random_number];
include($my_dir.$random_txt);
?>