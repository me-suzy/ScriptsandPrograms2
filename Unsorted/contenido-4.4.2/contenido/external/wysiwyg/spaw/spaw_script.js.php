<?php
   header('Content-Type: application/x-javascript');
   $contenido_path = "../../../"; // CONTENIDO
  @include ("config.php"); // CONTENIDO
  @include ($contenido_path . "includes/config.php"); // CONTENIDO
   include $cfg["path"]["wysiwyg"].'config/spaw_control.config.php';

   include $cfg["path"]["wysiwyg"].'class/script.js.php';

?>
