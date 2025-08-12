<?php
include_once("phpdbform/phpdbform_main.php");
include_once("phpdbform/phpdbform_db.php");

require_once("phpdbform/phpdbform_textbox.php");
require_once("phpdbform/phpdbform_image.php");

check_login(1);

$form = new phpdbform( $phpdbform_main->db, "photos", "cod", "name", "name" );

new phpdbform_textbox( $form, "name", "Name:", 30 );
new phpdbform_image( $form, "image", "Image:" );

draw_adm_header( "Test Photos" );
$form->process();
$form->draw();
draw_adm_footer();
?>
