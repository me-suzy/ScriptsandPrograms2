<?php

   $contenido_path = "../../../"; // CONTENIDO
  @include ("config.php"); // CONTENIDO
  @include ($contenido_path . "includes/config.php"); // CONTENIDO
   include $cfg["path"]["wysiwyg"].'config/spaw_control.config.php';
   include $cfg["path"]["wysiwyg"].'spaw_control.class.php';

/*
  $editor = new SPAW_Wysiwyg(
              $control_name='CMS_HTML', // control's name
              $value='',                  // initial value
              $lang='en',                 // language
              $mode = '',                 // toolbar mode
              $theme='',                  // theme (skin)
              $width='100%',              // width
              $height='300px',            // height
              $css_stylesheet='',         // css stylesheet file for content
              $dropdown_data=''           // data for dropdowns (style, font, etc.)
            );

$editor->show();*/

$sw = new SPAW_Wysiwyg('CMS_HTML',urldecode($a_content[$type][$typenr]),$spaw_default_lang,
                       $toolbar_mode,'contenido','100%',$editorheight, '' /*stylesheet file*/);

$sw->show();

?>
