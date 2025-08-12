<?php

//path to contenido, for all inclusions
$contenido_path = "../contenido/";
$errorfont = "font3";
//fo the language file
$language="de";
//optional    if it isnt set the first language of the client is choose
$load_lang = "1";
$load_client = "1";


$frontend_debug["container_display"] = false;
$frontend_debug["module_display"] = false;
$frontend_debug["module_timing"] = false;

/*
* Uncomment this to benefit from the "alldebug" functionality :)

if ($HTTP_GET_VARS["alldebug"] == 1)
{
$frontend_debug["container_display"] = true;
$frontend_debug["module_display"] = true;
$frontend_debug["module_timing"] = true;
$force = 1;
} 
*/


?>
